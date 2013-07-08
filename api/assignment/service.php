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
 * GET: /api/assignment/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'OpenReport Client API v1.0';
    echo json_encode($response);

});


/**
 * Fetch all assignmets records for apiKey
 *
 * GET: /api/assignment/{apiKey}
 *
 * Returns: Assesment Records
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');

    $join = array('LEFT JOIN users ON(assignments.user_id = users.id)',
                  'LEFT JOIN forms ON(assignments.form_id = forms.id)');
    $sel = 'assignments.*, forms.title AS form_title, users.username AS user';


    $data = Assignment::all(array('joins' => $join, 'select'=>$sel, 'conditions'=>array('assignments.api_key = ? AND forms.is_published = 1 AND forms.is_deleted = 0', $apiKey)));
    // package the data
    $response['data'] = assignmentArrayMap($data);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});

/**
 * Fetch all assignmets records for formId
 *
 * GET: /api/assignment/forms/{apiKey}/{formId}
 *
 * Returns: Assesment Records
 *
 */
$app->get("/forms/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $join = array('LEFT JOIN users ON(assignments.user_id = users.id)',
                  'LEFT JOIN forms ON(assignments.form_id = forms.id)');
    $sel = 'assignments.*, forms.title AS form_title, users.username AS user';

    $data = Assignment::all(array('joins' => $join, 'select'=>$sel, 'conditions'=>array('assignments.api_key = ? AND form_id = ? AND forms.is_published = 1 AND forms.is_deleted = 0', $apiKey, $formId)));
    // package the data
    $response['data'] = assignmentArrayMap($data);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});

/**
 * Fetch all assignmets records for userId
 *
 * GET: /api/assignment/users/{apiKey}/{userId}
 *
 * Returns: Assesment Records
 *
 */
$app->get("/users/:apiKey/:userId", function ($apiKey, $userId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');

    $join = array('LEFT JOIN users ON(assignments.user_id = users.id)',
                  'LEFT JOIN forms ON(assignments.form_id = forms.id)');
    $sel = 'assignments.*, forms.title AS form_title, users.username AS user';


    $data = Assignment::all(array('joins' => $join, 'select'=>$sel, 'conditions'=>array('assignments.api_key = ? AND user_id = ? AND forms.is_published = 1 AND forms.is_deleted = 0', $apiKey, $userId)));
    // package the data
    $response['data'] = assignmentArrayMap($data);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


/**
 * Fetch assignmet record for id
 *
 * GET: /api/assignment/{apiKey}/{id}
 *
 * Returns: Assesment Record
 *
 */
$app->get("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $data = Assignment::first(array('conditions'=>array('api_key = ? AND id = ?', $apiKey, $id)));
    // package the data
    $response['data'] = $data->values_for(array('id','user_id','form_id','date_assigned','is_active'));
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});



$app->delete("/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    $assignment = Assignment::find($id);
    $assignment->delete();
});

/**
 * Fetch all assignable reporting forms
 *
 *
 */
$app->get("/forms/:apiKey", function ($apiKey) use ($app, $response) {

    try {
        $formData = Form::find('all',
            array('conditions'=>array('api_key = ? AND is_deleted = 0 AND is_published = 1', $apiKey), 'order'=>'date_modified DESC'));
        // package the data
        $response['data'] = formArrayMap($formData);
        $response['count'] = count($response['data']);
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
 * Fetch form records with assignments - Returns all user form assignmets records
 *
 * GET: /api/assignment/list/{apiKey}/{userId}
 *
 */
$app->get("/list/:apiKey/:userId", function ($apiKey, $userId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');

    // Fetch Forms
    $forms = Form::all(array('conditions'=>array('api_key = ? AND is_deleted = 0 AND is_published = 1', $apiKey)));
    // Fetch Assignments
    $assignments = Assignment::all(array('select'=>'assignments.id, assignments.form_id', 'conditions'=>array('api_key = ? AND user_id = ? AND is_active = 1', $apiKey, $userId)));
    // normalize form_id(s) to an array
    $formIds = array_map(create_function('$m','return $m->form_id;'),$assignments);

     // build the data set (form_id, form_title, is_assigned, user_id )
    $list = array();
    foreach($forms as $form){
        $temp['form_id'] = $form->id;
        $temp['form_title'] = $form->title;
        $temp['is_assigned'] = in_array($form->id, $formIds, true);
        $temp['user_id'] = $userId;
        $list[] = $temp;
    }
    // package the data
    $response['data'] = $list;
    $response['count'] = count($list);
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


/**
 * Data conversion utilites
 *
 *
 */
function assignmentArrayMap($data){

   return array_map(create_function('$m','return $m->values_for(array(\'id\',\'user_id\',\'form_id\',\'date_assigned\',\'is_active\',\'form_title\',\'user\'));'),$data);

}

function formArrayMap($forms){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'title\'));'),$forms);

}
