<?php
/**
 * Open Report
 *
 * An open source application framework for Open Report
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@theaustinconnergroup.info so we can send you a copy immediately.
 *
 * @package		Open Report
 * @author		Open Report Dev Team
 * @copyright   Copyright (c) 2013, The Austin Conner Group. (http://theaustinconnergroup.info/)
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		https://sites.google.com/site/openfieldreport/
 * @since		Version 1.0
 * @filesource
 */
//session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'system/Slim/Slim.php';
\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array('session.handler' => null, 'templates.path'=>$_SERVER['DOCUMENT_ROOT'].'views/templates/'));
$app->add(new \Slim\Middleware\SessionCookie());

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
