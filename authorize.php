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

require_once $_SERVER['DOCUMENT_ROOT'].'system/Slim/Slim.php';
\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array('session.handler' => null, 'templates.path'=>$_SERVER['DOCUMENT_ROOT'].'views/templates/'));
$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'Z54cN9Jf8nE6hqj9V0wAuoaldIQ=','expires' => '60 minutes')));

require $_SERVER['DOCUMENT_ROOT'].'views/AuthView.php';
$app->view(new AuthView());


/**
 * Login page
 *
 * /a/login
 *
 */
$app->get('/a/login', function () use($app)  {

    $app->render('page/login.php');

});
/**
 * Login page
 *
 * /a/login
 *
 */
$app->post("/a/login", function () use ($app) {
    $email = $app->request()->post('email');
    $password = $app->request()->post('password');

    $app->view()->authorize($email, $password, $app);

    if (!isset($_SESSION['user'])) {
        $app->flash('errors', $app->view()->errors);
        $app->redirect('/a/login');
    }
    $app->redirect('/');
});
/**
 * Logout page
 *
 * /a/logout
 *
 */
$app->get("/a/logout", function () use ($app) {
   unset($_SESSION['user']);
   $app->redirect('/a/login');
});
/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
