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
 */


/**
 * Show Records
 *
 *
 */
window.RecordsView = Backbone.View.extend({
  el: '#reportContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    //"click #export":"exportRecords",
    //"click .filters":"refresh",
    "click #navPrev":"prev",
    "click #navNext":"next"
  },
  render: function(){
    var params = { headers: this.collection.models[0].get('columns'), records: this.collection.models[0].get('rows') };
    var template = _.template($("#reportRecords").html(), params);
    $(this.el).html(template);
	$.bootstrapSortable();
    return this;
  },
  next: function(){

	filters.startDate.add(filters.navigate.on,filters.navigate.index);
	filters.endDate.add(filters.navigate.on,filters.navigate.index);
	this.refresh()
  },
  prev: function(){

	filters.startDate.subtract(filters.navigate.on,filters.navigate.index);
	filters.endDate.subtract(filters.navigate.on,filters.navigate.index);
	this.refresh()
  },
  refresh:function(){
	this.collection.fetch()
  },
  exportRecords: function(){

  }

});

/**
 * Display Record Details
 *
 *
 */
window.RecordDetail = Backbone.View.extend({
  el: '#reportContext',
  model: null,
  initialize: function(options){
    this.listenTo(this.model,'change', this.render);
    this.model.fetch();
  },

  render: function(){
	var params = { record: this.model.attributes.data.record, headers:this.model.attributes.data.headers};
	var template = _.template($("#recordDetails").html(), params);
	$(this.el).html(template);
	return this;
  },

});
/**
 * List Reports
 *
 *
 */
window.ReportsView = Backbone.View.extend({
  el: '#reportContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
	this.collection.fetch();
  },

	render: function(){

	var params = { records: this.collection.models};

	//console.log(params.records[0].get('title'));
	var template = _.template($("#reportingForms").html(), params);
	$(this.el).html(template);

	// info box
	var template = _.template($("#info").html()	);
	$("#infoBox").html(template);

	return this;
  },

  close:function () {
    $('.datetimepicker').remove();
    $(this.el).unbind();
    $(this.el).empty();
  }

});

/**
 * Routes
 *
 *
 */
window.Routes = Backbone.Router.extend({

	routes: {
        "" : "index",                       // initial view - View Reporting Tasks
        "records/:id" : "records",          // list report records
        "details/:id" : "details"           // list report records
	},
    /*
     * Display Reporting Forms
     */
    index: function(){
        this.reportList = new window.Forms({key: apiKey});
        new window.ReportsView({collection: this.reportList});
    },
    /*
     * Display Report Records
     */
    records: function(id){
		// direct call
        this.recordList = new window.Records({key: apiKey, formId: id});
		//console.log(this.recordList);
        new window.RecordsView({collection: this.recordList});
		// info box
		var template = _.template($("#filters").html());
		$("#infoBox").html(template);

    },
	details: function(id){

		this.record = new window.Record({id:id});
		new window.RecordDetail({model:this.record});
	}
});


/**
 *
 * Start App
 *
 */
// template pattern (Mustache {{ name }})
_.templateSettings = {
    interpolate: /\{\{\=(.+?)\}\}/g,
    evaluate: /\{\{(.+?)\}\}/g
};
var router = new window.Routes();
Backbone.history.start({pushstate:false});
