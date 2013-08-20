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
if (!defined('__ROOT__')) {
   define('__ROOT__', dirname(dirname(__FILE__)));
}

require_once $_SERVER['DOCUMENT_ROOT'].'/api/config.php';

/**
 * Fetch all Identity records for apiKey
 *
 * GET: /identity/{apiKey}[?l={limit[,offset]}]]
 *
 */
$app->get("/:apiKey", function ($apiKey) use ($app, $response) {

   try{
      $options = array();
      $options['conditions'] = array('api_key = ? AND is_active = 1', $apiKey);
      $recCount = Identity::count($options);
      if((int)$recCount > 0){
          $page = $app->request()->params('l');
          if($page != null){
              $limit = explode(',',$page);
              if(count($limit)>1){
                   $options['offset'] = $limit[1];
              }
              $options['limit'] = $limit[0];
          }
          $options['order'] = 'identity_name, identity';
      }
      $identities = Identity::all($options);
      // package the data
      $response['data'] = identityArrayMap($identities);
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
 * Fetch all Identity records for identity_name
 *
 * GET: /identity/{apiKey}/{name}[?l={limit[,offset]}]]
 *
 */
$app->get("/:apiKey/:name", function ($apiKey, $name) use ($app, $response) {

   try{
      $options = array();
      $options['conditions'] = array('api_key = ? AND identity_name = ? AND is_active = 1 AND is_active = 1', $apiKey, $name);
      $recCount = Identity::count($options);
      if((int)$recCount > 0){
          $page = $app->request()->params('l');
          if($page != null){
              $limit = explode(',',$page);
              if(count($limit)>1){
                   $options['offset'] = $limit[1];
              }
              $options['limit'] = $limit[0];
          }
          $options['order'] = 'identity_name, identity';
      }
      $identities = Identity::all($options);
      // package the data
      $response['data'] = identityArrayMap($identities);
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
 * Fetch all Identity names (ie column names)
 *
 * GET: /identity/names/{apiKey}
 *
 */
$app->get("/names/:apiKey", function ($apiKey) use ($app, $response) {

    try {
        $options = array();
        $options['conditions'] = array('api_key = ? AND is_active = 1', $apiKey);
        $options['order'] = 'identity_name';
        $options['select'] = "DISTINCT identity_name";
        $identities = Identity::all($options);
        $names = array();
        foreach($identities as $name){
          $names[] = $name->identity_name;
        }

        // package the data
        $response['data'] = $names;
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
 * Add Identity
 *
 *
 * POST: /idenity/{apiKey}
 */
$app->post("/:apiKey", function ($apiKey) use ($app, $response) {


    $request = json_decode($app->request()->getBody());

    $count = Identity::count(array('conditions'=>array('api_key = ? AND identity_name = ? AND identity = ?', $apiKey, $request->identity_name, $request->identity)));

    // Validate account apiKey
    if($apiKey == $request->api_key && $count == 0){

        // create the form
        $identity = new Identity();
        $identity->api_key = $apiKey;
        $identity->identity_name = $request->identity_name;
        $identity->identity = $request->identity;
        $identity->description = $request->description;
        $identity->is_active = true;
        $identity->save();
        // package the data
        $response['data'] = $identity->values_for(array('id','identity_name','identity'));
        $response['message'] = "Identity saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "error";
    }

    // send the data
    echo json_encode($response);

});
/**
 * Add Identity
 *
 *
 * PUT: /idenity/{apiKey}/{id}
 */
$app->put("/:apiKey/:id", function ($apiKey,$id) use ($app, $response) {

    $request = json_decode($app->request()->getBody());
    // Validate account apiKey
    if($apiKey == $request->api_key){

        // update the Identity
        $identity = Identity::find($request->id);
        $identity->api_key = $apiKey;
        $identity->identity_name = $request->identity_name;
        $identity->identity = $request->identity;
        $identity->description = $request->description;
        $identity->is_active = true;
        $identity->save();
        // package the data
        $response['data'] = $identity->values_for(array('id','identity_name','identity'));
        $response['message'] = "Identity saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "error";
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
 function identityArrayMap($tasks){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'api_key\',\'identity\',\'identity_name\',\'description\'));'),$tasks);

 }
