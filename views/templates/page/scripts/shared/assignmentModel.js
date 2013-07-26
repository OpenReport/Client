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
				schedule: '',
				repeat_schedule: 0,
				date_assigned: null,
				date_expires: null,
				form_id: 0,
				identity: '',
				user: '',
				status: 'open',
        api_key:apiKey
    },
    validate: function(attr){
        attr || (attr = this.attributes);
        var errors = [];
        if(!attr.date_expires){
            errors.push('Date Expire is requried');
        }
        if(!attr.date_assigned){
            errors.push('Assign Date is requried');
        }
        if(errors.length !== 0){
            return errors
        }
    }
});


app.collections.Assignments = Backbone.Collection.extend({
    recCount:0,
    tag:0,
    model:app.models.Assignment,
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
        var uri = this.key; // default
        uri = uri + (typeof this.tag != 'undefined' ? '/'+this.tag:'');
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
