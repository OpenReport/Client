<?php
/**
 * OpenReport
 *
 * Copyright 2013, The Austin Conner Group
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/api/config.php';

/**
 * Fetch all assignment records for account
 *
 * GET: /api/assignment/{apiKey}
 *
 * Returns: assignment Records
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    $join = array('LEFT JOIN forms ON(assignments.form_id = forms.id)',
                  'LEFT JOIN users ON(assignments.user_id = users.id)');
    $sel = 'assignments.*, users.username AS user_name, forms.title AS form_title';
    $data = Assignment::all(array('select'=>$sel, 'joins'=>$join,'conditions'=>array('assignments.api_key = ?', $apiKey)));
    // package the data

    $response['data'] = assignmentArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);

});

/**
 * Fetch Assignment Forms by userid
 *
 * GET: /api/assignment/forms/
 *
 */
$app->get("/forms/:apiKey/:userId", function ($apiKey, $userId) use ($app, $response) {
    $data = Assignment::all(array('conditions'=>array('api_key = ? AND user_id = ?', $apiKey, $userId)));
    // package the data
    $response['data'] = assignmentArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);
});

/**
 * Fetch Form Assignment Users by form_id
 *
 * GET: /api/assignment/users
 *
 */
$app->get("/users/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {
    $data = Assignment::all(array('conditions'=>array('api_key = ? AND form_id = ?', $apiKey, $formId)));
    // package the data
    $response['data'] = assignmentArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);
});


$app->post("/:apiKey/", function ($apiKey) use ($app, $response) {


    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the form
        $assignment = new Assignment();
        $assignment->api_key = $apiKey;
        $assignment->form_id = $request->form_id;
        $assignment->user_id = $request->user_id;
        $assignment->is_active = true;
        $assignment->save();
        // package the data
        $response['data'] = $assignment->values_for(array('id','user_id','form_id'));
        $response['message'] = "Assignment saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "error";
    }

    // send the data
    echo json_encode($response);

});

$app->delete("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    $assignment = Assignment::find($id);
    $assignment->delete();
});

/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();


/**
 * Data conversion utilites
 *
 *
 */
function assignmentArrayMap($data){

   return array_map(create_function('$m','return $m->values_for(array(\'id\',\'user_id\',\'user_name\',\'form_id\',\'form_title\',\'is_active\'));'),$data);

}
