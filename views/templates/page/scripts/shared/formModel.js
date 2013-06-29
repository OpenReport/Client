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



/**
 * Models
 *
 *
 */
window.Form = Backbone.Model.extend({
    urlRoot: '/api/form/'+apiKey,
    defaults:{
        id:null,
        title:'',
        description:'',
        meta:{'name':'',fields:[]},
        date_created:'',
        api_key:apiKey
    }
});

window.Forms = Backbone.Collection.extend({
    model:Form,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;

    },
    fetchReportForms: function(options) {
        options || (options = {});
        this.key = options.key;
        this.fetch();
    },
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records forn an event (get:/record/event/{id})
        var uri = this.key;
        // fetch record for task id
        //uri = uri + (this.taskId > 0 ? '/'+this.taskId:'');
        // build new uri
        console.log(uri);
        return "/api/form/"+uri;
    },
    parse:function(response){
        return response.data;
    }
});
