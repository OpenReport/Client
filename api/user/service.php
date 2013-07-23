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
 * Fetch User List (title and id)
 *
 * GET: /api/user/list/{apiKey}
 *
 */
$app->get("/list/:apiKey", function($apiKey) use ($app, $response){

    try {
        $options = array();
        $options['select'] = 'users.email, users.username';
        $options['joins'] = array('LEFT JOIN accounts ON(accounts.id = users.account_id)');
        $options['conditions'] = array('accounts.api_key = ? AND users.is_active = 1', $apiKey);
        $options['order'] = 'users.username';
        $users = User::all($options);
        // package the data
        $response['data'] = array_map(create_function('$m','return $m->values_for(array(\'email\',\'username\'));'),$users);
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
 * Fetch all User records for apiKey
 *
 * GET: /api/user/{apiKey}
 *
 * Returns: User Accounts
 *
 */
$app->get("/:apiKey(/:role)", function ($apiKey, $role='') use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    // BE VERY CAREFUL WITH THIS QUERY
    $join = array('LEFT JOIN accounts ON(accounts.id = users.account_id)');
    $conditions = array('accounts.api_key = ? AND (FIND_IN_SET(?, users.roles) OR ? =\'\')', $apiKey,$role,$role);
    $userData = User::all(array('joins' => $join, 'conditions'=>$conditions));

    // package the data
    $response['data'] = userArrayMap($userData);
    $response['count'] = 1;
    // send the data
    echo json_encode($response);

});

/**
 * Fetch User Roles
 *
 * GET: /api/user/roles/{apiKey}
 *
 */
$app->get("/roles/:apiKey", function ($apiKey) use ($app, $response) {

    $userTags = Tag::all(array('conditions'=>array('(api_key = ? OR api_key = \'***GLOBAL***\') AND scope=\'roles\'', $apiKey)));
    $tags = array();
    foreach($userTags as $tag){
        $tags[] = $tag->name;
    }

    $response['data'] = $tags;
    $response['count'] = count($tags);
    // send the data
    echo json_encode($response);
});

$app->post("/:apiKey", function ($apiKey) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

       // create the event
        $user = new User();
        $user->username = $request->username;
        $user->roles = $request->roles;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->date_created = $today;
        $user->date_modified = $today;
        $user->account_id = $request->account_id;
        $user->is_active = $request->is_active;
        $user->save();

        checkRoles($apiKey, $request->roles);

        // package the data
        $response['data'] = $user->values_for(array('id','username','email','is_active'));
        $response['message'] = "user saved";
    }
    else{
        $response['status'] = "error";
        $response['message'] = "cancled";
    }

    // confirmation
    echo json_encode($response);
});


$app->put("/:apiKey/:userId", function ($apiKey, $userId) use ($app, $response) {

    // get date
    $today = new DateTime('GMT');
    $request = json_decode($app->request()->getBody());

    // Validate account apiKey
    if($apiKey == $request->api_key){

       // create the event
        $user = User::find($userId);
        $user->username = $request->username;
        $user->roles = $request->roles;
        $user->email = $request->email;
        if($request->password !== '')
            $user->password = $request->password;
        $user->date_modified = $today;
        $user->is_active = $request->is_active;
        $user->save();

        checkRoles($apiKey, $request->roles);

        // package the data
        $response['data'] = $user->values_for(array('id','username','email','is_active'));
        $response['message'] = "user saved";
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

function checkRoles($apiKey, $names){

    if($names == '') return;

    $roles = split(',',strtolower($names));

    foreach($roles as $role){

        $cnt = Tag::count(array('conditions'=>array('(api_key = ? OR api_key = \'***GLOBAL***\') AND scope=\'roles\' AND name = ? ', $apiKey, $role)));

        if($cnt == 0){
            $tag = new Tag();
            $tag->api_key = $apiKey;
            $tag->scope = 'roles';
            $tag->name = strtolower($role);
            $tag->save();
        }
    }

}

/**
 * Data conversion utilites for copy events
 *
 *
 */
 function userArrayMap($tasks){

    return array_map(create_function('$m','return $m->values_for(array(\'id\',\'username\',\'is_active\',\'roles\',\'email\',\'password\',\'date_last_accessed\'));'),$tasks);

 }
