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


require_once $_SERVER['DOCUMENT_ROOT'].'system/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('session.handler' => null, 'templates.path'=>$_SERVER['DOCUMENT_ROOT'].'views/templates/'));
$app->add(new \Slim\Middleware\SessionCookie());

/**
 *
 *
 */
$authenticate = function ($app) {
    return function () use ($app) {
        if (!isset($_SESSION['user'])) {
            $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
            $app->flash('error', 'Login required');
            $app->redirect('/a/login');
        }
    };
};



/**
 * Home Page - Dashboard
 *
 *
 *
 */
$app->get('/', $authenticate($app), function () use($app) {

    require $_SERVER['DOCUMENT_ROOT'].'views/DashboardView.php';
    $app->view(new DashboardView());

    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/home.php', $data);


});

/**
 * Settings Page - Dashboard
 *
 *
 *
 */
$app->get('/account/settings', $authenticate($app), function () use($app) {
    require $_SERVER['DOCUMENT_ROOT'].'views/AccountView.php';
    $app->view(new AccountView());
    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/account/settings.php', $data);


});

/**
 * Billing Page - Dashboard
 *
 *
 *
 */
$app->get('/account/billing', $authenticate($app), function () use($app) {
    require $_SERVER['DOCUMENT_ROOT'].'views/AccountView.php';
    $app->view(new AccountView());
    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/account/billing.php', $data);


});

/**
 * Calendar Admin
 *
 */
$app->get('/calendar', $authenticate($app), function () use ($app){
    require $_SERVER['DOCUMENT_ROOT'].'views/CalendarView.php';
    $app->view(new CalendarView());
    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/calendar.php', $data);

});

/**
 * Reports - Used to render reports/records/ect
 *
 *
 *
 */
$app->get('/reports', $authenticate($app), function () use ($app){
    require $_SERVER['DOCUMENT_ROOT'].'views/ReportsView.php';
    $app->view(new ReportsView());
    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/reports.php', $data);

});

/**
 * Reporting Tasks Admin - Create and mamage reporting tasks
 *
 */
//$app->get('/tasks', $authenticate($app), function () use ($app){
//    require $_SERVER['DOCUMENT_ROOT'].'views/TasksView.php';
//    $app->view(new TasksView());
//    // Page Meta
//    $meta = array();
//    $data = array('meta'=>$meta);
//    $app->render('page/tasks.php', $data);
//
//});


/**
 * Forms - Create and mamage reporting forms
 *
 */
$app->get('/forms', $authenticate($app), function () use ($app){
    require $_SERVER['DOCUMENT_ROOT'].'views/FormsView.php';
    $app->view(new FormsView());

    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/forms.php', $data);

});

/**
 * Users - Create and mamage users
 *
 */
$app->get('/users', $authenticate($app), function () use ($app){
    require $_SERVER['DOCUMENT_ROOT'].'views/UsersView.php';
    $app->view(new UsersView());

    // Page Meta
    $meta = array();
    $data = array('meta'=>$meta);
    $app->render('page/forms.php', $data);

});



/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
