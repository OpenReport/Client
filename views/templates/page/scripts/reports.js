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


	return this;
  },

  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
  }

});

/**
 * List Reports
 *
 *
 */
window.RelatedView = Backbone.View.extend({
  el: '#reportContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
	this.collection.fetch();
  },

  render: function(){

	var params = {relatedReports: this.collection.models, identity:this.collection.identity};

	var template = _.template($("#relatedReports").html(), params);
	$(this.el).html(template);

	// render once
	if($('.filters').length === 0){
		// add filters to infoBox
		var infoTemplate = _.template($("#filters").html(),{columns:params.headers});
		$("#infoBox").html(infoTemplate);
		// Refresh after filter updates
		var base = this;
		$('.filters').bind('click', function(){ base.refresh();});
    }

	return this;
  },
  events:{
    "click #export":"exportRecords",
    "click #navPrev":"prev",
    "click #navNext":"next"
  },
  next: function(e){

	filters.startDate.add(filters.navigate.on,filters.navigate.index);
	filters.endDate.add(filters.navigate.on,filters.navigate.index);
	this.refresh();
  },
  prev: function(e){

	filters.startDate.subtract(filters.navigate.on,filters.navigate.index);
	filters.endDate.subtract(filters.navigate.on,filters.navigate.index);
	this.refresh();
  },
  refresh:function(){
	this.collection.fetch();
  },
  exportRecords: function(){
	var csv = jsonExport(this.collection.models[0].get('rows'), true, true);
	window.open("data:text/csv;charset=utf-8," + escape(csv));
  },
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
  }

});


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
    "click #export":"exportRecords",
    "click #navPrev":"prev",
    "click #navNext":"next"
  },
  render: function(){

	var params = { report:this.collection.models[0].get('report'), headers: this.collection.models[0].get('columns'), records: this.collection.models[0].get('rows') };
    var template = _.template($("#reportRecords").html(), params);
    $(this.el).html(template);
	// render once
	if($('.filters').length === 0){
		// add filters to infoBox
		var infoTemplate = _.template($("#filters").html(),{columns:params.headers});
		$("#infoBox").html(infoTemplate);
		// Refresh after filter updates
		var base = this;
		$('.filters').bind('click', function(){ base.refresh();});
    }
    return this;
  },
  next: function(e){

	filters.startDate.add(filters.navigate.on,filters.navigate.index);
	filters.endDate.add(filters.navigate.on,filters.navigate.index);
	this.refresh();
  },
  prev: function(e){

	filters.startDate.subtract(filters.navigate.on,filters.navigate.index);
	filters.endDate.subtract(filters.navigate.on,filters.navigate.index);
	this.refresh();
  },
  refresh:function(){
	this.collection.fetch();
  },
  exportRecords: function(){
	var csv = jsonExport(this.collection.models[0].get('rows'), true, true);
	window.open("data:text/csv;charset=utf-8," + escape(csv));
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
	var params = {title: this.model.attributes.data.title, form_name: this.model.attributes.data.form_name, record:this.model.attributes.data.record, headers:this.model.attributes.data.headers};
	var template = _.template($("#recordDetails").html(), params);
	$(this.el).html(template);
	// info box
	$("#infoBox").html(_.template($("#infoDetails").html(), {title: this.model.attributes.data.title, form_name: this.model.attributes.data.form_name,record: params.record, columns:params.headers}));
	return this;
  },

});


/**
 * Routes
 *
 *
 */
window.Routes = Backbone.Router.extend({

	routes: {
        "" : "index",                       // initial view - View Reporting Tasks
		"tag/:tag" : "index",				// filter by tag
        "records/:id" : "records",      // list report records
		"related/:identity": "related",	    // list record for 'identity'
        "details/:id" : "details"           // report details
	},
    /*
     * Display Reporting Forms
     */
    index: function(tag){
        this.reportList = new window.Forms({key: apiKey, tag: tag});
        new window.ReportsView({collection: this.reportList});
		// info box
		$.ajax({
			url:'/api/form/tags/'+apiKey,
			dataType: "json",
			success: function(response){
				$("#infoBox").html(_.template($("#info").html(), {tags:response.data, select:tag}));
			}
		});
    },
    /*
     * Display Report Records
     */
    records: function(id){

		// direct call - do not pull from collection
        this.recordList = new window.Records({key: apiKey, formId: id});
		//console.log(this.recordList);
        var view = new window.RecordsView({collection: this.recordList});

    },
	details: function(id){

		this.record = new window.Record({id:id});
		new window.RecordDetail({model:this.record});


		//var template = _.template($("#infoDetails").html());
		//$("#infoBox").html(template);
	},
    related: function(identity){

        this.reportList = new window.Records({key: apiKey, identity: identity});
        new window.RelatedView({collection: this.reportList});

    }

});
// template pattern (Mustache {{ name }})
_.templateSettings = {
	interpolate: /\{\{\=(.+?)\}\}/g,
	evaluate: /\{\{(.+?)\}\}/g
};
/**
 *
 *
 *
 */
function formatColumnData(value, type){

	if('undefined' == typeof value ) return '--';
	retValue = value;

	if(type === 'media:image'){
		if(value !==''){
			uri = value.split(',');
			retValue = ''+uri.length+' photo(s)';
		}
		else{
			retValue = 'no photo(s)'
		}
	}
	else if(type === 'comment' && value.length > 26){
		retValue = value.substring(0,23)+'...';
	}

	return retValue;
}
/**
 *
 *
 *
 */
function formatReportData(value, type, options){
	if('undefined' == typeof value ) return '--';
	retValue = value;
	if(type === 'media:image'){
		if(value !==''){
			uri = value.split(',');
			retValue = ''+uri.length+' photo(s)';
		}
		else{
			retValue = 'no photo'
		}
	}
	if('undefined' != typeof options){
		retValue = '';
		if('string' == typeof value) value = [value];
		_.filter(value,
			function(obj){
				if(retValue != '') retValue = retValue + ', ';
				retValue = retValue + _.findWhere(options, {'value':obj}).label;
			}
		);

	}

	return retValue;
}
/**
 * format 'media:image'
 *
 *
 */
function formatMedia(links){

	media = '';
	images = links.split(',');

	for(i=0;i<images.length;i++){
		if(images[i] === '') continue;
		media = media + '<img class="thumb" src="http://api.openreport.local/media/data/'+images[i]+'" >';
	}

	return media === '' ? 'no photos':media;
}

var router = new window.Routes();
/**
 *
 * Start App
 *
 */
$(document).ready(function(){
		Backbone.history.start({pushstate:false});
});
