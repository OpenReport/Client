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
window.Assignment = Backbone.Model.extend({
    urlRoot: '/api/assignment/'+apiKey,
    defaults:{
        id:null,

        api_key:apiKey
    }
});

window.Assignments = Backbone.Collection.extend({
    model:Assignment,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.form_id = 0;
        this.user_id = 0;
    },
    fetchForms: function(options) {
        options || (options = {});
        this.key = options.key;
        this.form_id = 0;
        this.user_id = options.user_id;
        this.fetch(options);
    },
    fetchUsers: function(options) {
        options || (options = {});
        this.key = options.key;
        this.form_id = options.form_id;
        this.user_id = 0;
        this.fetch(options);
    },
    // override fetch url for addtional uri elements
    url:function() {
        var uri = '';
        if(this.form_id > 0 || this.user_id > 0){
        // fetch records (get:/api/assignmenst/{scope}/{apiKey}/{user_id | form_id}
        uri = (this.form_id == 0 ? 'list/':'list/')+this.key;
        // fetch task records for forms or users
        uri = uri + (this.form_id == 0 ? '/'+this.user_id:'/'+this.form_id);
        }
        else{
            uri = this.key;
        }
        // build new uri
        console.log(uri);
        return "/api/assignment/"+uri;
    },
    parse:function(response){
        console.log(response);
        return response.data;
    }
});