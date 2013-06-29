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


/**
 * Models
 *
 *
 */
window.Task = Backbone.Model.extend({
    urlRoot: '/api/task/'+apiKey,
    defaults:{
        id:null,
        title:'',
        description:'',
        date_created:'',
        api_key:apiKey
    }
});

window.Tasks = Backbone.Collection.extend({
    model:Task,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
    },
    fetchEvent: function(options) {
        options || (options = {});
        this.key = options.key;
        this.fetch();
    },
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records forn an event (get:/api/task/{apiKey})
        var uri = this.key;
        // fetch task records, optional filter by month-year (get:/api/task/{id}{/m-y})
        //uri = uri + (this.mo > 0 ? '/'+this.mo:'')+(this.yr > 0 ? '-'+this.yr:'');
        // build new uri
        console.log(uri);
        return "/api/task/"+uri;
    },
    parse:function(response){
        console.log(response);
        return response.data;
    }
});
