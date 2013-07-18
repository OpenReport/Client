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
app.models.Distribution = Backbone.Model.extend({
    urlRoot: '/api/distribution/'+apiKey,
    defaults:{
        id:null,
        api_key:apiKey
    }
});

app.collections.Distributions = Backbone.Collection.extend({

    model:app.models.Distribution,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
    },
    fetchByTag: function(options) {
        options || (options = {});
        this.tag = options.tag;
        this.fetch(options);
    },
    fetchByRole: function(options) {
        options || (options = {});
        this.role = options.role;
        this.fetch(options);
    },
    // override fetch url for addtional uri elements
    url:function() {
        var uri = this.key; // default
        //// by form tags
        if(typeof this.tag != 'undefined'){
            uri = 'forms/'+this.key+'/'+this.tag;
        }
        // by user role
        else if(typeof this.role != 'undefined'){
            uri = 'roles/'+this.key+'/'+this.role;
        }
        // return new url
        return "/api/distribution/"+uri;
    },
    parse:function(response){
        return response.data;
    }
});
