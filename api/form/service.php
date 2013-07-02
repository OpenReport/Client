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
 * get: /form/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'Open Report Services v1.0';
    echo json_encode($response);

});


/**
 * Fetch Form records for apiKey
 *
 * get: /form/{api}
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    try {
        $formData = Form::find('all', array('conditions'=>array('api_key = ?', $apiKey)));
        // package the data
        $response['data'] = formArrayMap($formData);
        $response['count'] = count($response['data']);//$response['data']->count();
    }
    catch (\ActiveRecord\RecordNotFound $e) {
        $response['message'] = 'No Records Found';
        $response['data'] = array();;
        $response['count'] = 0;//$response['data']->count();
    }

    // send the data
    echo json_encode($response);


});


/**
 * Fetch Form record
 *
 * get: /form/{api}/{id}
 *
 */
$app->get('/:apiKey/:id', function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    try {
        $formData = Form::find($id);
        // package the data
        $response['data'] = $formData->values_for(array('id','meta'));
        $response['count'] = count($formData->meta['fields']);//$response['data']->count();
    }
    catch (\ActiveRecord\RecordNotFound $e) {
        $response['message'] = 'No Records Found';
        $response['data'] = null;
        $response['count'] = 0;//$response['data']->count();
    }

    // send the data
    echo json_encode($response);


});


/**
 * Add Reporting form record
 *
 * POST: /form/{apiKey}
 *
 */
$app->post("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // TODO: validate task_id with api_key

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the event
        $form = new Form();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->tags = $request->tags;
        $form->date_created = $today;
        $form->date_modified = $today;
        $form->is_published = $request->is_published;
        $form->meta = json_encode($request->meta);
        $form->api_key = $apiKey;
        $form->save();
        // package the data
        $response['data'] = $form->values_for(array('id','title','description','date_created'));
        $response['message'] = "form saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);

});

/**
 * Update Reporting form record
 *
 * PUT: /form/{apiKey}/{taskId}
 *
 */
$app->put("/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // TODO: validate task_id with api_key

    // Validate account apiKey
    if($request->id == $formId){

        // create the event
        $form = Form::find($request->id);
        $form->title = $request->title;
        $form->description = $request->description;
        $form->tags = $request->tags;
        $form->date_modified = $today;
        $form->is_published = $request->is_published;
        $form->meta = json_encode($request->meta);
        $form->api_key = $apiKey;
        $form->save();
        // package the data
        $response['data'] = $form->values_for(array('id','title','description','date_created','date_modified'));
        $response['message'] = "form saved";
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

function getColumns($data){
    return array_keys($data[0]);
}

/**
 * Data conversion utilites for copy events
 *
 *
 */
 function formArrayMap($forms){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'api_key\',\'title\',\'description\',\'tags\',\'meta\',\'date_created\',\'date_modified\',\'is_published\'));'),$forms);

 }
