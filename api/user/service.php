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

require_once $_SERVER['DOCUMENT_ROOT'].'api/config.php';


/**
 * Fetch all User records for apiKey
 *
 * GET: /api/user/{apiKey}
 *
 * Returns: User Accounts
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $userData = Account::find('first', array('conditions'=>array('api_key = ?', $apiKey)));
    // package the data
    $response['data'] = userArrayMap($userData->users);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


$app->post("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

       // create the event
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->date_modified = $today;
        $user->account_id = $request->account_id;
        $user->is_active = $request->is_active;
        $user->save();
        // package the data
        $response['data'] = $user->values_for(array('id','username','email','is_active'));
        $response['message'] = "user saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);
});


$app->put("/:apiKey/:userId", function ($apiKey, $userId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

       // create the event
        $user = User::find($userId);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password == '' ? $user->password:$request->password;
        $user->date_modified = $today;
        $user->is_active = $request->is_active;
        $user->save();
        // package the data
        $response['data'] = $user->values_for(array('id','username','email','is_active'));
        $response['message'] = "user saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);
});
/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();


/**
 * Data conversion utilites for copy events
 *
 *
 */
 function userArrayMap($tasks){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'username\',\'email\',\'password\'));'),$tasks);

 }
