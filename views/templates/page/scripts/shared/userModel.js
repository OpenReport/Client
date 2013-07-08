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
window.User = Backbone.Model.extend({
    urlRoot: '/api/user/'+apiKey,
    defaults:{
        id:null,
        username:'',
        email:'',
        password:'',
        is_active:0,
        account_id:acctNo,
        api_key:apiKey
    },
    validate: function(attr){
        attr || (attr = this.attributes);
        var errors = [];
        if(!attr.username){
            errors.push('User Name Required');
        }
        if(!attr.email){
            errors.push('Email Required');
        }
        //if(!attr.password){
        //    errors.push('Password Required');
        //}
        if(errors.length !== 0){
            return errors
        }

    }
});

window.Users = Backbone.Collection.extend({
    model:User,
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

        return "/api/user/"+uri;
    },
    parse:function(response){

        return response.data;
    }
});
