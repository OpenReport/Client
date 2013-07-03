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
 * get: /account/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'Open Report v1.0';
    echo json_encode($response);

});


/**
 * Fetch System Stats
 *
 * get: /dashboard/{apiKey}
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    // get stats
    $join = array('LEFT JOIN users ON(records.user = users.email)',
                  'LEFT JOIN forms ON(records.form_id = forms.id)');
    $sel = 'records.*, forms.title AS form_title, users.username AS user';

    $recentRcords = Record::all(array('joins' => $join, 'select'=>$sel, 'conditions' =>array('records.api_key = ?', $apiKey)));

    if(!empty($recentRcords)){
        $recentReports = arrayMapRecord($recentRcords);
    }
    else {
        $recentReports = array('count'=>0);
    }


    $recCount =  Record::count(array('conditions' =>array('api_key = ?', $apiKey)));
    $frmCount =  Form::count(array('conditions' =>array('api_key = ? AND is_deleted = 0', $apiKey)));


    // package data
    $stats = array(
        "recordCount"=>$recCount,
        "formCount"=>$frmCount,
        "recentReports"=>$recentReports
    );
    $response['data'] = $stats;
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
function formArrayMap($data){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'api_key\',\'title\',\'description\',\'tags\',\'meta\',\'date_created\',\'date_modified\',\'is_published\'));'),$data);

}

function arrayMapRecord($data){

    return array_map(create_function('$m','return $m->values_for(array(\'form_title\',\'form_id\',\'user\',\'lat\',\'lon\',\'record_date\'));'),$data);

}