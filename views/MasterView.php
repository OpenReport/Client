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

header('X-Powered-By: Open Report');

require $_SERVER['DOCUMENT_ROOT'].'/system/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
    $cfg->set_model_directory($_SERVER['DOCUMENT_ROOT'].'/models');
    $cfg->set_connections(array(
        'development' => 'mysql://root:acg100199@localhost/meta_forms'
    ));
});


class MasterView extends \Slim\View
{
    public $errors = array();


    public function __construct()
    {
        // Grab User Account info....
        $accountData = Account::find('first',array('conditions'=>array('id = ?', $this->accountId())));
        // grab only what we need
        $this->setData('account', $accountData->values_for(array('id','name', 'api_key')));
    }

    /** Render Page
     *
     */
    public function render($template)
    {

        $this->setData('childView', $template);
        return parent::render('master.php');

    }
    /** Render Content Page
     *
     */
    public function partial($template, $data = array())
    {
        extract($data);

        require $this->getTemplatesDirectory().'/'.$template;

    }

    public function user()
    {
        return $_SESSION['user']['username'];
    }

    /** user id
     *
     *
     *
     */
    public function userId()
    {
        return $_SESSION['user']['id'];
    }

    /** account id
     *
     *
     *
     */
    public function accountId()
    {
        return $_SESSION['user']['accountId'];
    }
}
