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
    //urlRoot: '/api/record/'
});

window.Records = Backbone.Collection.extend({
    model:Record,
    id:0,   // record id
    mo:0,   // record month
    yr:0,   // record year

    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.formId = options.formId;

    },
    fetchEvent: function(options) {
        options || (options = {});
        this.key = options.key;
        this.formId = options.formId;
        this.yr = options.yr;
        this.mo = options.mo;
        this.fetch();
    },
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records forn an event (get:/record/event/{id})
        var uri = this.key+'/'+this.formId;
        // fetch records forn an event filter by month-year (get:/record/event/{id}/m-y)
        //uri = uri + (this.mo > 0 ? '/'+this.mo:'')+(this.yr > 0 ? '-'+this.yr:'2013');
        // build new uri
        console.log(uri);
        return "/api/record/report/"+uri;
    },
    parse:function(response){
        console.log(response);
        return response.data;
    }
});
