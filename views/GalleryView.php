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

require $_SERVER['DOCUMENT_ROOT'].'/views/MasterView.php';
class GalleryView extends MasterView
{
    public function __construct()
    {
        parent::__construct();
    }
    // http://graph.facebook.com/381492044069/albums?fields=photos.fields(source,link,name,created_time),name
    public function fetchPhotos(){



        $c = curl_init("http://graph.facebook.com/29047014069/albums?fields=photos.fields(source,link,name,created_time),name");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		//don't verify SSL (required for some servers)
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        $photos = curl_exec($c);
		curl_close($c);
        return json_decode($photos)->photos;
    }

    public function uploadPhotos(){

    }




}
