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
app.models.Assignment = Backbone.Model.extend({
    urlRoot: '/api/assignment/'+apiKey,
    defaults:{
        id:null,
        api_key:apiKey
    }
});


app.collections.Assignments = Backbone.Collection.extend({
    recCount:0,
    model:app.models.Assignment,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
    },
    fetchRecords: function(options) {
        options || (options = {});
        this.pageOffset = options.pageOffset;
        this.fetch(options);
    },
    // override fetch url for addtional uri elements
    url:function() {
        var uri = this.key; // default

        var limit = paging.items;
        // check for paging
        if('undefined' != typeof this.pageOffset){

            limit = limit+','+this.pageOffset;
        }
        // return new url
        return "/api/assignment/"+uri+'?l='+limit;
    },
    parse:function(response){
        this.recCount = response.count;
        return response.data;
    }
});
