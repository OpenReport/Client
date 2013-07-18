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
if (!defined('__ROOT__')) {
   define('__ROOT__', dirname(dirname(__FILE__)));
}

require_once $_SERVER['DOCUMENT_ROOT'].'/api/config.php';

/**
 * Fetch all User records for apiKey
 *
 * get: /account/{apiKey}
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $accountData = Account::find('first', array('conditions'=>array('api_key = ? AND is_active = 1', $apiKey)));
    // package the data
    $response['data'] = $accountData->values_for(array('id','api_key','name','account_limits','admin_email','map_api_key'));
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


$app->put("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){
        $accountData = Account::find($id);
        $accountData->name = $request->name;
        $accountData->admin_email = $request->admin_email;
        $accountData->map_api_key = $request->map_api_key;
        $accountData->save();
        // package the data
        $response['data'] = $accountData->values_for(array('id','api_key','name', 'admin_email','account_limits','map_api_key'));
        $response['count'] = 1;
    }
    // send the data
    echo json_encode($response);

});




/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

function getColumns($data){
    return array_keys($data[0]);
}

/**
 * Data conversion utilites for copy events
 *
 *
 */
 function userArrayMap($tasks){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'username\',\'email\',\'password\'));'),$tasks);

 }
