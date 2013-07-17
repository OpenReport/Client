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
app.models.Form = Backbone.Model.extend({
    urlRoot: '/api/form/'+apiKey,
    defaults:{
        id:null,
        title:'',
        description:'',
        tags:'',
        meta:{'name':'frm', "title":"", "desc":"",'fieldset':[{'name':'grp1', 'legend':'',fields:[]}]},
        date_created:'',
        is_published: 0,
        is_public: 0,
        is_deleted: 0,
        api_key:apiKey
    },
    validate: function(attr){
        attr || (attr = this.attributes);
        var errors = [];
        if(!attr.title){
            errors.push('Report Title is requried');
        }
        if(!attr.meta.name){
            errors.push('Report ID is requried');
        }
        if(errors.length !== 0){
            return errors
        }
    }
});

app.collections.Forms = Backbone.Collection.extend({

    tag:'',
    startDate:'',   // record month
    endDate:'',   // record year
    model:app.models.Form,
    initialize: function(options) {
        options || (options = {});
        this.key = options.key;
        this.tag = options.tag;
    },
    //fetchReportForms: function(options) {
    //    options || (options = {});
    //    this.key = options.key;
    //    this.fetch();
    //},
    // override fetch url for addtional uri elements
    url:function() {
        // fetch records for an event (get:/record/event/{id})
        var uri = this.key;
        // fetch records based on tags
        uri = uri + (typeof this.tag != 'undefined' ? '/'+this.tag:'');
        return "/api/form/"+uri;
    },
    parse:function(response){
        return response.data;
    }
});
