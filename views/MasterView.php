<?php
/**
 * OpenReport
 *
 * An open source application framework for OpenReport
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
 * @package		OpenReport
 * @author		OpenReport Dev Team
 * @copyright   Copyright (c) 2013, The Austin Conner Group. (http://theaustinconnergroup.info/)
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		https://sites.google.com/site/openfieldreport/
 * @since		Version 1.0
 * @filesource
 */

header('X-Powered-By: OpenReport');

/**
 * ActiveRecord Model Config
 *
 */
require_once $_SERVER['DOCUMENT_ROOT'].'models/config.php';

class MasterView extends \Slim\View
{
    public $errors = array();


    public function __construct()
    {
        // Grab User Account info....
        $accountData = Account::find('first',array('conditions'=>array('id = ?', $this->accountId())));
        // grab only what we need
        $this->setData('account', $accountData->values_for(array('id','name','api_key','map_api_key', 'mobile_url')));
    }

    /** Render Page
     *
     */
    public function render($template)
    {

        $this->setData('childView', $template);
        return parent::render('master.php');

    }
    /** Render Content
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
