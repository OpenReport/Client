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
 * Fetch Single Report Record
 *
 * get: /report/record/{apiKey}/{id}
 *
 */
$app->get("/record/:apiKey/:id", function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $record = Record::find($id);
    $headers = Report::last(array('conditions' => array('api_key = ? AND form_id = ?', $apiKey, $record->form_id)));
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
