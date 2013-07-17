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
app.models.User = Backbone.Model.extend({
    urlRoot: '/api/user/'+apiKey,
    defaults:{
        id:null,
        username:'',
        email:'',
        password:'',
        is_active:1,
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

app.collections.Users = Backbone.Collection.extend({
    model:app.models.User,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.role = options.role;
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
        // fetch records based on tags
        uri = uri + (typeof this.role != 'undefined' ? '/'+this.role:'');

        return "/api/user/"+uri;
    },
    parse:function(response){

        return response.data;
    }
});
