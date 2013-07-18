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
 * Fetch assignmet all records for account
 *
 * GET: /api/distribution/{apiKey}
 *
 * Returns: Assesment Records
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    $data = Distribution::all(array('conditions'=>array('api_key = ?', $apiKey)));
    // package the data
    $response['data'] = distributionArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);

});

/**
 * Fetch Distributions by user role
 *
 * GET: /api/distribution/roles/
 *
 */
$app->get("/roles/:apiKey/:role", function ($apiKey, $role) use ($app, $response) {
    $data = Distribution::all(array('conditions'=>array('api_key = ? AND user_role = ?', $apiKey, $role)));
    // package the data
    $response['data'] = distributionArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);
});

/**
 * Fetch Distributions by form tag
 *
 * GET: /api/distribution/forms
 *
 */
$app->get("/forms/:apiKey/:tag", function ($apiKey, $tag) use ($app, $response) {
    $data = Distribution::all(array('conditions'=>array('api_key = ? AND form_tag = ?', $apiKey, $tag)));
    // package the data
    $response['data'] = distributionArrayMap($data);
    $response['count'] = count($data);
    // send the data
    echo json_encode($response);
});


$app->post("/:apiKey/", function ($apiKey) use ($app, $response) {


    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the form
        $distribution = new Distribution();
        $distribution->form_tag = $request->form_tag;
        $distribution->user_role = $request->user_role;
        $distribution->is_active = true;
        $distribution->api_key = $apiKey;
        $distribution->save();
        // package the data
        $response['data'] = $distribution->values_for(array('id','form_tag','user_role'));
        $response['message'] = "Distribution saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "error";
    }

    // send the data
    echo json_encode($response);

});

$app->delete("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    $distribution = Distribution::find($id);
    $distribution->delete();
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
function distributionArrayMap($data){

   return array_map(create_function('$m','return $m->values_for(array(\'id\',\'user_role\',\'form_tag\',\'is_active\'));'),$data);

}

function formArrayMap($forms){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'title\'));'),$forms);

}
