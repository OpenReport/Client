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
window.Record = Backbone.Model.extend({
   urlRoot: '/api/report/record/'+apiKey,
    defaults:{
        id:null,
        user:'',

        api_key:apiKey
    },
});

window.Records = Backbone.Collection.extend({
    model:Record,
    id:0,   // record id
    startDate:'',   // record month
    endDate:'',   // record year

    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.formId = options.formId;

    },
    fetchRecords: function(options) {
        options || (options = {});
        this.key = options.key;
        this.formId = options.formId;
        this.fetch();
    },
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records forn an event (get:/record/event/{id})
        var uri = this.key+'/'+this.formId;
        // get filter dates
        this.startDate = filters.startDate.format('YYYY-MM-DD')
        this.endDate = filters.endDate.format('YYYY-MM-DD')

        return "/api/report/"+uri+"?s="+this.startDate+'&e='+this.endDate;
    },
    parse:function(response){
        return response.data;
    }
});
