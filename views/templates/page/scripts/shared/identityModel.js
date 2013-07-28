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
 * Single Identity
 *
 */
app.models.Identity = Backbone.Model.extend({
    urlRoot: '/api/identity/'+apiKey,
    defaults:{
        id:null,
        identity: '',
        identity_name: '',
        description: '',
        api_key: apiKey
    },
    url: function(){
        return this.urlRoot;
    }
});

/**
 * List of Identities
 *
 */
app.collections.Identities = Backbone.Collection.extend({
    tag:'',
    recCount:0,
    pageOffset:0,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.tag = options.tag;
    },
    fetchRecords: function(options) {
        options || (options = {});
        this.pageOffset = options.pageOffset;
        this.fetch(options);
    },
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records (get:/api/identity/{key}[/tag])
        var uri = this.key;
         uri = uri + (typeof this.tag != 'undefined' ? '/'+this.tag:'');
        var limit = paging.items;
        // check for paging
        if('undefined' != typeof this.pageOffset){

            limit = limit+','+this.pageOffset;
        }

        return "/api/identity/"+uri+'?l='+limit;
    },
    parse:function(response){
        this.recCount = response.count;
        return response.data;
    }
});
