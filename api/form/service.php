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
 * Fetch Form records for apiKey
 *
 * GET: /api/form/{apiKey}[/{tag}]?l={limit[,offset]}]
 *
 */
$app->get("/:apiKey(/:tag)", function ($apiKey, $tag = '') use ($app, $response) {


    try {
        $options = array();
        $options['conditions'] = array('api_key = ? AND is_deleted = 0 AND (tags=? OR ?=\'\')', $apiKey, $tag, $tag);

        $recCount = Form::count($options);

        if((int)$recCount > 0){
            $page = $app->request()->params('l');
            if($page != null){
                $limit = split(',',$page);
                if(count($limit)>1){
                     $options['offset'] = $limit[1];
                }
                $options['limit'] = $limit[0];
            }
            $options['order'] = 'date_modified DESC';
        }

        $formData = Form::all($options);
        // package the data
        $response['data'] = formArrayMap($formData);
        $response['count'] = $recCount;
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
 * Fetch Form List (title and id)
 *
 * GET: /api/form/list/{apiKey}
 *
 */
$app->get("/list/:apiKey", function($apiKey) use ($app, $response){

    try {
        $options = array();
        $options['conditions'] = array('api_key = ? AND is_deleted = 0 AND is_published = 1', $apiKey);
        $options['order'] = 'title';
        $forms = Form::all($options);
        // package the data
        $response['data'] = array_map(create_function('$m','return $m->values_for(array(\'id\',\'title\'));'),$forms);
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
 * Fetch Forms Tags
 *
 * GET: /api/form/tags/{apiKey}
 *
 */
$app->get("/tags/:apiKey", function ($apiKey) use ($app, $response) {

    $formTags = Tag::all(array('conditions'=>array('api_key = ? AND scope=\'reports\'', $apiKey)));
    $tags = array();
    foreach($formTags as $tag){
        $tags[] = $tag->name;
    }

    $response['data'] = $tags;
    $response['count'] = count($tags);
    // send the data
    echo json_encode($response);
});

/**
 * Fetch Form record
 *
 * GET: /api/form/{api}/{id}
 *
 */
$app->get('/:apiKey/:id', function ($apiKey, $id) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    try {
        $formData = Form::find($id);
        // package the data
        $response['data'] = $formData->values_for(array('id','meta'));
        $response['count'] = 1; //count($formData->meta['fields']);
    }
    catch (\ActiveRecord\RecordNotFound $e) {
        $response['message'] = 'No Records Found';
        $response['data'] = null;
        $response['count'] = 0;
    }

    // send the data
    echo json_encode($response);


});


/**
 * Add Reporting form record
 *
 * POST: /api/form/{apiKey}
 *
 */
$app->post("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the form
        $form = new Form();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->tags = $request->tags;
        $form->report_version = ($request->is_published?1:0);
        $form->date_created = $today;
        $form->date_modified = $today;
        $form->is_published = $request->is_published;
        $form->is_public = $request->is_public;
        $form->identity_name = $request->identity_name;
        $form->meta = json_encode($request->meta);
        $form->api_key = $apiKey;
        $form->save();
        // Add a basic report record
        $report = new Report();
        $report->api_key = $apiKey;
        $report->form_name = $request->meta->name;
        $report->form_id = $form->id;
        $report->version = 1;
        $report->title = $request->title;
        $report->meta = json_encode($request->meta->fieldset);    //TODO: Strip unnessary attr from meta
        $report->save();
        // update tags as needed
        checkTags($apiKey, $request->tags);

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
 * Update Reporting Form record
 *
 * PUT: /api/form/{apiKey}/{formId}
 *
 */
$app->put("/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // TODO: validate formId with apiKey

    // Validate account apiKey
    if($request->id == $formId){
        $ver = Report::Count(array('conditions'=>array('form_id = ?', $formId)));
        $form = Form::find($request->id);
        // only add report defination on published forms otherwise update
        if($request->new_report && $form->is_public){
            $ver = $ver+1;
            // Add new version of report
            $report = new Report();
            $report->api_key = $apiKey;
            $report->form_name = $request->meta->name;
            $report->form_id = $formId;
            $report->version = $ver;
            $report->title = $request->title;
            $report->meta = json_encode($request->meta->fieldset);    //TODO: Strip unnessary attr from meta
            $report->save();
        }
        elseif($ver > 0){  // if we have report defination then update
            // update report defination
            $report = Report::first(array('conditions' => array('api_key = ? AND form_id = ? AND version = ?', $apiKey, $formId, $ver)));
            $report->form_name = $request->meta->name;
            $report->title = $request->title;
            $report->meta = json_encode($request->meta->fieldset);    //TODO: Strip unnessary attr from meta
            $report->save();
        }
        // Update Report Form
        $form->title = $request->title;
        $form->description = $request->description;
        $form->tags = $request->tags;
        $form->report_version = $ver;
        $form->date_modified = $today;
        $form->is_published = $request->is_published;
        $form->is_public = $request->is_public;
        $form->identity_name = $request->identity_name;
        $form->meta = json_encode($request->meta);
        $form->api_key = $apiKey;
        $form->save();

        checkTags($apiKey, $request->tags);

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
 * Delete Reporting form record (soft delete)
 *
 * DELETE: /api/form/{apiKey}/{taskId}
 *
 */
$app->delete("/:apiKey/:formId", function ($apiKey, $formId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    // remove form
    $form = Form::find($formId);
    $form->date_modified = $today;
    $form->is_published = 0;
    $form->is_deleted = 1;
    $form->save();
    // package the data
    $response['data'] = $form->values_for(array('id','title','description','date_created','date_modified'));
    $response['message'] = "form saved";

    // confirmation
    echo json_encode($response);

});


/**
 * Assign Form to User
 *
 * POST: /api/form/assign/{apiKey}/{formId}/{userid}
 *
 */
$app->post("/assign/:apiKey/:formId/:userId", function ($apiKey, $formId, $userId) use ($app, $response) {

   // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

        // create the event
        $form = new Assignment();
        $form->api_key = $apiKey;
        $form->form_id = $formId;
        $form->user_id = $userId;
        $form->date_assigned = $today;
        $form->is_active = 1;
        $form->save();
        // package the data
        $response['data'] = $form->values_for(array('id','form_id','user_id','date_assigned'));
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


function checkTags($apiKey, $name){
    if($name == '') return;

    $formTags = Tag::first(array('conditions'=>array('api_key = ? AND scope=\'reports\' AND name = ?', $apiKey, $name)));
    if(empty($formTags)){
        $tag = new Tag();
        $tag->api_key = $apiKey;
        $tag->scope = 'reports';
        $tag->name = strtolower($name);
        $tag->save();
    }

}

function getColumns($data){
    return array_keys($data[0]);
}

/**
 * Data conversion utilites for copy events
 *
 *
 */
function formArrayMap($forms){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'api_key\',\'title\',\'report_version\',\'description\',\'tags\',\'meta\',\'date_created\',\'date_modified\',\'is_public\',\'is_published\',\'identity_name\'));'),$forms);

}

function fieldsArrayMap($data){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'api_key\',\'role\',\'meta\',\'is_active\'));'),$data);

}
