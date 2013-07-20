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
app.models.Record = Backbone.Model.extend({
   urlRoot: '/api/report/record/'+apiKey,
    defaults:{
        id:null,
        user:'',

        api_key:apiKey
    },
});

app.collections.Records = Backbone.Collection.extend({
    model:app.models.Record,
    id:0,   // record id
    formId:0,
    identity:'',
    startDate:'',   // record month
    endDate:'',   // record year
    recCount:0,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.identity = options.identity;
        this.formId = options.formId;
    },
    fetchRecords: function(options) {
        options || (options = {});
        this.pageOffset = options.pageOffset;
        this.fetch(options);
    },
    // override fetch url for addtional uri elements
    url:function() {

        if('undefined' == typeof this.identity){
            // fetch records bt report (get:/record/{apikey}/{id})
            var uri = this.key+'/'+this.formId;
        }
        else{
            // fetch records by idenity
            var uri = 'records/'+   this.key+'/'+this.identity;
        }
        var limit = paging.items;
        // check for paging
        if('undefined' != typeof this.pageOffset){

            limit = limit+','+this.pageOffset;
        }

        // get by filter dates
        this.startDate = filters.startDate.format('YYYY-MM-DD')
        this.endDate = filters.endDate.format('YYYY-MM-DD')
        return "/api/report/"+uri+"?s="+this.startDate+'&e='+this.endDate+'&l='+limit;
    },
    parse:function(response){
        this.recCount = response.count;
        return response.data;
    }
});
