<?php
/**
 * Open Report
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

// define('__ROOT__', dirname(dirname(__FILE__)));

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");

require_once $_SERVER['DOCUMENT_ROOT'].'/system/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
/**
 * ActiveRecord Model Config
 *
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/models/config.php';

$app = new \Slim\Slim();

/**
 *
 */
\Slim\Route::setDefaultConditions(array(
    'apiKey' => '[a-zA-Z0-9]{32}'
));



/**
 * Authenticate all requests
 *
 */
$app->hook('slim.before.dispatch', function () use ($app) {

    // Provide a better validation here...
    //if ($app->request()->params('apiKey') !== "65b109869265518f7801f2ce3ba55402") {
        #$app->halt(403, "Invalid or Missing Key");
    //}
});

/**
 * Set the default content type
 *
 */
$app->hook('slim.after.router', function() use ($app) {

    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    $res['X-Powered-By'] = 'OpenReport';

});

// Standardize response
$response = array('status'=>'ok', 'message'=>'', 'count'=>0, 'data'=>array());

/**
 * Status Page
 *
 * get: /[account|assignments|dashboard|form|report|user]/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'OpenReport v1.0';
    echo json_encode($response);

});
