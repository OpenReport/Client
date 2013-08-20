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
 * GET: /api/assignment/{apiKey}[?l=5[,0]]
 *
 * Returns: assignment Records
 *
 */
$app->get("/:apiKey(/:formId)", function ($apiKey, $formId = 0) use ($app, $response) {
    try {
        $options = array();
        $options['joins'] = array('LEFT JOIN forms ON(assignments.form_id = forms.id)','LEFT JOIN users ON(assignments.user = users.email)');
        $options['select'] = 'assignments.*, users.username AS user_name, forms.title AS form_title';
        if($formId == 0){
            $options['conditions'] = array('assignments.api_key = ? AND assignments.is_active = 1', $apiKey);
        }
        else{
             $options['conditions'] = array('assignments.api_key = ? AND forms.id = ? AND assignments.is_active = 1', $apiKey, $formId);
        }
        $recCount = Assignment::count($options);

        if((int)$recCount > 0){
            $page = $app->request()->params('l');
            if($page != null){
                $limit = explode(',',$page);
                if(count($limit)>1){
                     $options['offset'] = $limit[1];
                }
                $options['limit'] = $limit[0];
            }
            $options['order'] = 'date_assigned ASC';
        }

        $data = Assignment::all($options);
        // package the data
        $response['data'] = assignmentArrayMap($data);
        $response['count'] = $recCount;
    }
    catch (\ActiveRecord\RecordNotFound $e) {
        $response['message'] = 'No Records Found';
        $response['data'] = array();;
        $response['count'] = 0;
    }

    // send the data
    echo json_encode($response);

});
//
///**
// * Fetch Assignment Forms by user
// *
// * GET: /api/assignment/forms/
// *
// */
//$app->get("/forms/:apiKey/:user", function ($apiKey, $user) use ($app, $response) {
//    $data = Assignment::all(array('conditions'=>array('api_key = ? AND user = ?', $apiKey, $user)));
//    // package the data
//    $response['data'] = assignmentArrayMap($data);
//    $response['count'] = count($data);
//    // send the data
//    echo json_encode($response);
//});
//
///**
// * Fetch Form Assignment Users by form_id
// *
// * GET: /api/assignment/users
// *
// */
//$app->get("/users/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {
//    $data = Assignment::all(array('conditions'=>array('api_key = ? AND form_id = ?', $apiKey, $formId)));
//    // package the data
//    $response['data'] = assignmentArrayMap($data);
//    $response['count'] = count($data);
//    // send the data
//    echo json_encode($response);
//});

/**
 * Add Assignment
 *
 *
 */
$app->post("/:apiKey/", function ($apiKey) use ($app, $response) {


    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the form
        $assignment = new Assignment();
        $assignment->api_key = $apiKey;
        $assignment->form_id = $request->form_id;
        $assignment->identity = $request->identity;
        $assignment->user = $request->user;
        $assignment->schedule = $request->schedule;
        $assignment->repeat_schedule = $request->repeat_schedule;
        $assignment->date_assigned = $request->date_assigned;
        $assignment->date_expires = $request->date_expires;
        $assignment->date_next_report = $request->date_assigned;
        $assignment->status = $request->status;
        $assignment->is_active = true;
        $assignment->save();
        // package the data
        $response['data'] = $assignment->values_for(array('id','user','form_id'));
        $response['message'] = "Assignment saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "error";
    }

    // send the data
    echo json_encode($response);

});
/**
 * Update Assignment
 *
 */
$app->put("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {


    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key && $id == $request->id){

        // update the form
        $assignment = Assignment::find($request->id);
        $assignment->api_key = $apiKey;
        $assignment->form_id = $request->form_id;
        $assignment->identity = $request->identity;
        $assignment->user = $request->user;
        $assignment->schedule = $request->schedule;
        $assignment->repeat_schedule = $request->repeat_schedule;
        $assignment->date_assigned = $request->date_assigned;
        $assignment->date_expires = $request->date_expires;
        $assignment->date_next_report = $request->date_assigned;
        $assignment->status = $request->status;
        $assignment->is_active = true;
        $assignment->save();
        // package the data
        $response['data'] = $assignment->values_for(array('id','user','form_id'));
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

   return array_map(create_function('$m','return $m->values_for(array(\'id\',\'user\',\'user_name\',\'form_id\',\'identity\',\'form_title\',\'schedule\',\'repeat_schedule\',\'status\',\'date_assigned\',\'date_last_reported\',\'date_expires\',\'is_active\'));'),$data);

}
