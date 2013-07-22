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

    $record = Record::find($id);
    $headers = Report::last(array('conditions' => array('api_key=? AND form_id=? AND version=?', $apiKey, $record->form_id, $record->report_version)));
    // package the data
    $data = $record->values_for(array('id','form_id','report_version','meta','identity','record_date','record_time_offset','user','lat','lon'));
    $response['data'] = array('title'=>$headers->title,'form_name'=>$headers->form_name,'headers'=>$headers->meta,  'record'=>$data);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});


/**
 * Fetch Report Records for Form
 *
 * get: /report/{apiKey}/{formId}[?s={startDate}e={endDate}[&id={}][&t={tags}][&l={limit[,offset]}]]
 *
 */
$app->get("/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    try{

        $options = array();
        $startDate = $app->request()->params('s');
        $endDate = $app->request()->params('e');
        if($startDate == null || $endDate == null){
            // find all records
            $options['conditions'] = array('api_key = ? AND form_id = ?', $apiKey, $formId);
        }
        else{
            // filter records
            $options['conditions'] = array('api_key = ? AND form_id = ? AND record_date BETWEEN ? AND ?', $apiKey, $formId, $startDate, $endDate);
        }
        $recCount = Record::count($options);
        if((int)$recCount > 0){
            $page = $app->request()->params('l');
            if($page != null){
                $limit = split(',',$page);
                if(count($limit)>1){
                     $options['offset'] = $limit[1];
                }
                $options['limit'] = $limit[0];
            }
            $options['order'] = 'record_date desc';
        }
        // fetch records
        $records = Record::all($options);
        // get report info - use last version
        $report = Report::last(array('conditions' => array('api_key = ? AND form_id = ?', $apiKey, $formId)));

        // check if empty
        if(!empty($records)){
            // normalize data
            $data = array();
            foreach($records as $record){
                $temp = $record->meta;
                $temp['id'] = $record->id;
                $temp['lon'] = $record->lon;
                $temp['lat'] = $record->lat;
                $temp['recorded'] = $record->record_date;
                $data[] = $temp;
            }
            $columns = array();
            foreach($report->meta as $fieldset){
                foreach($fieldset['fields'] as $column){
                    $columns[] = array('name'=>$column['name'], 'type'=>$column['type'], 'values'=>(!array_key_exists('values', $column)? '':$column['values']));
                }
            }
            // add record date
            $columns[] = array('name'=>'recorded', 'type'=>'date', 'values'=>'');

            // package the data
            $response['data'] = array('report'=>$report->values_for(array('id','title','version')), 'columns'=>$columns, 'rows'=>$data);
            $response['count'] = $recCount;
        }
        else{
            $response['message'] = 'No Records Found';
            $response['data'] = array('report'=>$report->values_for(array('id','title','version')),'columns'=>array(), 'rows'=>array());
        }
    }
    catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        $response['data'] = array('report'=>null, 'columns'=>array(), 'rows'=>array() );
        $response['count'] = 0;
    }
    // send the data
    echo json_encode($response);

});

/**
 * Fetch Reports for Identity
 *
 * get: /report/records/{apiKey}/{identity}[?s={startDate}e={endDate}][&p={page[,limit]}]]
 *
 */
$app->get("/records/:apiKey/:identity", function ($apiKey, $identity) use ($app, $response) {
//var_dump($identity); die;
    try{
        $options = array();

        $options['joins'] = array('LEFT JOIN forms ON(records.form_id = forms.id)');
        $options['select'] = 'records.*, forms.title AS form_title';
        // Date filtering
        $startDate = $app->request()->params('s');
        $endDate = $app->request()->params('e');
        if($startDate == null || $endDate == null){
        // find all records
            $options['conditions'] = array('records.api_key = ? AND records.identity = ?', $apiKey, $identity);
        }
        else{
           $options['conditions'] = array('records.api_key = ? AND records.identity = ? AND records.record_date BETWEEN ? AND ?', $apiKey, $identity, $startDate, $endDate);
        }
        $recCount = Record::count($options);
        if((int)$recCount > 0){
            // paging
            $page = $app->request()->params('l');
            if($page != null){
                $limit = split(',',$page);
                if(count($limit)>1){
                     $options['offset'] = $limit[1];
                }
                $options['limit'] = $limit[0];
            }
            $options['order'] = 'record_date desc';
        }
        // fetch records
        $records = Record::all($options);
        // check if empty
        if(!empty($records)){

            // normalize data
            $data = array();
            foreach($records as $record){
                //$temp = $record->meta;
                $temp['form_title'] = $record->form_title;
                $temp['id'] = $record->id;
                $temp['lon'] = $record->lon;
                $temp['lat'] = $record->lat;
                $temp['record_date'] = $record->record_date;
                $temp['user'] = $record->user;

                $data[] = $temp;
            }

            // package the data
            $response['data'] = $data;
            $response['count'] = $recCount;
        }
        else{
            $response['message'] = 'No Records Found';
            $response['data'] = array();
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
