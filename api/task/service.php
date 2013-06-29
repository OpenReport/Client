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

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");

require_once $_SERVER['DOCUMENT_ROOT'].'system/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require $_SERVER['DOCUMENT_ROOT'].'/system/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
    $cfg->set_model_directory($_SERVER['DOCUMENT_ROOT'].'api/models');
    $cfg->set_connections(array(
        'development' => 'mysql://root:acg100199@localhost/meta_forms'
    ));
});

$app = new \Slim\Slim();

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
    $res['X-Powered-By'] = 'Open Report';

});

// Standardize response
$response = array('status'=>'ok', 'message'=>'', 'count'=>0, 'data'=>array());

/**
 * Status Page
 *
 * get: /task/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'Open Report v1.0';
    echo json_encode($response);

});


/**
 * Fetch all Task records for apiKey
 *
 * get: /task/{apiKey}
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $taskData = Task::find('all', array('conditions'=>array('api_key = ?', $apiKey)));
    // package the data
    $response['data'] = taskArrayMap($taskData);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


/**
 * Add Reporting Task
 *
 * post: /task/{apiKey}
 *
 */
$app->post("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the event
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->date_created = $today;
        //$task->date_modified = $request->end_time->date;
        $task->api_key = $apiKey;
        $task->save();
        // package the data
        $response['data'] = $task->values_for(array('id','title','description','date_created'));
        $response['message'] = "task saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);

});

/**
 * update Reporting Task
 *
 * put: /task/{apiKey}/{}
 *
 */
$app->put("/:apiKey/:taskId", function ($apiKey, $taskId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

         // find the task
        $task = Task::find($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->date_created = $today;
        //$task->date_modified = $request->end_time->date;
        $task->api_key = $apiKey;
        $task->save();
        // package the data
        $response['data'] = $task->values_for(array('id','title','description','date_created'));
        $response['message'] = "task saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);

});

/**
 * Fetch all Repporting Task Forms records for apiKey
 *
 * get: /task/forms/{apiKey}
 *
 */
$app->get("/forms/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $taskData = Form::find('all', array('conditions'=>array('api_key = ?', $apiKey)));
    // package the data
    $response['data'] = formArrayMap($taskData);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);


});


/**
 * Fetch single Task records for apiKey and id
 *
 * get: /task/{api}/{id}
 *
 */
$app->get("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    // find all event records
    $taskData = Task::find('first', array('conditions' => array('api_key = ? AND id = ?', $apiKey, $id)));

    // package the data
    $response['data'] = $taskData->values_for(array('id', 'title','description', 'date_created'));
    $response['count'] = 1;
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
 function taskArrayMap($tasks){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'title\',\'description\',\'date_created\'));'),$tasks);

 }
