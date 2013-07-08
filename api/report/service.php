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




// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}
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
    if ($app->request()->params('apiKey') !== "65b109869265518f7801f2ce3ba55402") {
        #$app->halt(403, "Invalid or Missing Key");
    }
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


// return HTTP 200 for HTTP OPTIONS requests
$app->map('/', function() {
    //http_response_code(200);
})->via('OPTIONS');
/**
 * Status Page
 *
 * GET: /report/
 *
 */
$app->get('/', function () use($app, $response)  {

    $response['message'] = 'OpenReport Services v1.0';
    echo json_encode($response);

});


/**
 * Fetch Single Report Record
 *
 * get: /report/record/{apiKey}/{id}
 *
 */
$app->get("/record/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $record = Record::find($id);
    $headers = Report::first(array('conditions' => array('api_key = ? AND form_id = ?', $apiKey, $record->form_id)));
    // package the data
    $data = $record->values_for(array('id', 'form_id','meta', 'record_date','user', 'lat', 'lon'));
    $response['data'] = array('headers'=>$headers->meta,  'record'=>$data);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


/**
 * Fetch Report Data for Form
 *
 * get: /report/{apiKey}/{formId}[?s={startDate}e={endDate}[&t={tags}][&l={limit[,start]}]]
 *
 */
$app->get("/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    try{

        $startDate = $app->request()->params('s');
        $endDate = $app->request()->params('e');

        if($startDate == null || $endDate == null){
        // find all records
            $records = Record::all(array('conditions' => array('api_key = ? AND form_id = ?', $apiKey, $formId)));
        }
        else{
            $records = Record::all(array('conditions' => array('api_key = ? AND form_id = ? AND record_date BETWEEN ? AND ?', $apiKey, $formId, $startDate, $endDate)));
        }
        // check if empty
        if(!empty($records)){

            // normalize data
            $data = array();
            foreach($records as $record){
                $temp = $record->meta;
                $temp['id'] = $record->id;
                $temp['lon'] = $record->lon;
                $temp['lat'] = $record->lat;
                $temp['recorded'] = $record->record_date->format('Y-m-d H:i:s');
                $data[] = $temp;
            }

            // package the data
            $response['data'] = array('columns'=>getColumns($data), 'rows'=>$data);
            $response['count'] = count($response['data']['rows']);
        }
        else{
            $response['message'] = 'No Records Found';
            $response['data'] = array('columns'=>array(), 'rows'=>array());
        }
    }
    catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        $response['data'] = array();;
        $response['count'] = 0;
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
