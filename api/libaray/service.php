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
 * Fetch Libaray
 *
 * GET: /api/libaray/{apiKey}
 *
 */
$app->get("/:apiKey", function($apiKey) use ($app, $response){
    try {
        $options = array();
        $options['conditions'] = array('api_key = ? AND role = \'field\' AND is_active = 1', $apiKey);
        //
        $records = Libaray::all($options);

        $fields = array();
        foreach($records as $field){
            $fields[] = $field->meta;
        }

        // package the data
        $response['data'] = $fields;
        $response['count'] = count($fields);
    }
    catch (\ActiveRecord\RecordNotFound $e) {
        $response['message'] = 'No Records Found';
        $response['data'] = array();;
        $response['count'] = 0;
    }

    // send the data
    echo json_encode($response);

});

/**
 * Add Libaray Items
 *
 *
 * POST: /api/libaray/{apiKey}
 *
 */
$app->post("/:apiKey/", function ($apiKey) use ($app, $response) {

    $request = json_decode($app->request()->getBody());

    foreach($request as $item){
        // TODO: Check for dups

        $field = new Libaray();
        $field->api_key = $apiKey;
        $field->meta = json_encode($item);
        $field->role = 'field';
        $field->is_active = 1;
        $field->save();
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
