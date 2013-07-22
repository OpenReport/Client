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
 *
 * Support Funtions
 *
 */

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
	else if(type === 'date'){
		retValue = moment(value.date).format('ll');
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
		media = media + '<img class="span12 thumb" src="http://api.openreport.local/media/data/'+images[i]+'" >';
	}

	return media === '' ? 'no photos':media;
}

/**
 * List Reports
 *
 *
 */
app.views.ReportsView = Backbone.View.extend({
  el: '#reportContext',
  collection: null,
  pageIndex: 0,
  recordCount: 0,
  initialize: function(options){
	var base = this;
    //_.bind(this, 'render', 'next', 'prev');
    this.listenTo(this.collection, 'reset', this.render);
	this.collection.fetch();
  },

  render: function(){
	var params = { forms: this.collection.models, count:this.collection.recCount};

	//console.log(params.records[0].get('title'));
	var template = _.template($("#reportingForms").html(), params);
	$(this.el).html(template);


	return this;
  },
  events:{

    "click #nextPage":"prevPage",
    "click #prevPage":"nextPage"
  },

  prevPage: function(index){
	if((this.pageIndex) < paging.items ) return;
	this.pageIndex = this.pageIndex - paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  nextPage:function(index){
	if((this.pageIndex + paging.items) > this.collection.recCount) return;
	this.pageIndex = this.pageIndex + paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
	$("#infoBox").unbind();
	$("#infoBox").empty();
  }

});

/**
 * List Reports
 *
 *
 */
app.views.RelatedView = Backbone.View.extend({
  el: '#reportContext',
  pageIndex: 0,
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
	this.collection.fetch();
  },

  render: function(){

	var params = {relatedReports: this.collection.models, identity:this.collection.identity, count:this.collection.recCount };

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
    "click #navNext":"next",
    "click #nextPage":"prevPage",
    "click #prevPage":"nextPage"
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
  prevPage: function(index){
	if((this.pageIndex) < paging.items ) return;
	this.pageIndex = this.pageIndex - paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  nextPage:function(index){
	if((this.pageIndex + paging.items) > this.collection.recCount) return;
	this.pageIndex = this.pageIndex + paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  refresh:function(){
	this.pageIndex = 0;
	this.collection.fetch();
  },
  exportRecords: function(){
	var csv = jsonExport(this.collection.models[0].get('rows'), true, true);
	window.open("data:text/csv;charset=utf-8," + escape(csv));
  },
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
	$("#infoBox").unbind();
	$("#infoBox").empty();
  }

});


/**
 * Show Records
 *
 *
 */
app.views.RecordsView = Backbone.View.extend({
  el: '#reportContext',
  pageIndex: 0,
  recordCount: 0,
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();
  },
  render: function(){

	var params = { report:this.collection.models[0].get('report'), headers: this.collection.models[0].get('columns'), records: this.collection.models[0].get('rows'), count:this.collection.recCount };
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
  events:{
    "click #export":"exportRecords",
    "click #navPrev":"prev",
    "click #navNext":"next",
    "click #nextPage":"prevPage",
    "click #prevPage":"nextPage"

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

  prevPage: function(index){
	if((this.pageIndex) < paging.items ) return;
	this.pageIndex = this.pageIndex - paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  nextPage:function(index){
	if((this.pageIndex + paging.items) > this.collection.recCount) return;
	this.pageIndex = this.pageIndex + paging.items;
	this.collection.fetchRecords({pageOffset:this.pageIndex});

  },
  refresh:function(){
	this.pageIndex = 0;
	this.collection.fetch();
  },

  exportRecords: function(){
	var csv = jsonExport(this.collection.models[0].get('rows'), true, true);
	window.open("data:text/csv;charset=utf-8," + escape(csv));
  },
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
	$("#infoBox").unbind();
	$("#infoBox").empty();
  }

});

/**
 * Display Record Details
 *
 *
 */
app.views.RecordDetail = Backbone.View.extend({
  el: '#reportContext',
  model: null,
  initialize: function(options){
	_.bindAll(this, 'tabShown');
    this.listenTo(this.model,'change', this.render);
    this.model.fetch();
  },
  render: function(){
	var params = {title: this.model.attributes.data.title, form_name: this.model.attributes.data.form_name, record:this.model.attributes.data.record, headers:this.model.attributes.data.headers};
	var template = _.template($("#recordDetails").html(), params);
	$(this.el).html(template);
	// info box
	$("#infoBox").html(_.template($("#infoDetails").html(), {title: this.model.attributes.data.title, form_name: this.model.attributes.data.form_name,record: params.record, columns:params.headers}));
	// (late) bind tab events
	$('a[data-toggle="tab"]').on('shown', this.tabShown);


	return this;
  },
  events:{


  },
  tabShown: function(e){
	// catch map tab
	if(e.target.hash == '#map' && map == null){

		try{

			var Latlng = new google.maps.LatLng(this.model.attributes.data.record.lat,this.model.attributes.data.record.lon);
			var mapOptions = {
				zoom: 20,
				center: Latlng,
				mapTypeId: google.maps.MapTypeId.HYBRID
			}
		    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

			var marker = new google.maps.Marker({
				  position: Latlng,
				  map: map,
				  title: this.model.attributes.data.record.identity
			  });
		}
		catch(e){
			$('div#map').html('<p>Maps Offline</p>');
		    return;
		}
	}

  },
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
	$("#infoBox").unbind();
	$("#infoBox").empty();
  }

});


/**
 * Routes
 *
 *
 */
app.controller = Backbone.Router.extend({

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
        this.reportList = new app.collections.Forms({key: apiKey, tag: tag});
        if(app.pageView !== null) app.pageView.close();
        app.pageView = new app.views.ReportsView({collection: this.reportList});
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
        this.recordList = new app.collections.Records({key: apiKey, formId: id});
		//console.log(this.recordList);
        if(app.pageView !== null) app.pageView.close();
        app.pageView = new app.views.RecordsView({collection: this.recordList});

    },
	details: function(id){

		this.record = new app.models.Record({id:id});
		if(app.pageView !== null) app.pageView.close();
        app.pageView = new app.views.RecordDetail({model:this.record});

	},
    related: function(identity){
        this.reportList = new app.collections.Records({key: apiKey, identity: identity});
		if(app.pageView !== null) app.pageView.close();
        app.pageView = new app.views.RelatedView({collection: this.reportList});

    }

});

/**
 * initilize app
 *
 */
app.init(new app.controller());
/**
 *
 * Start App
 *
 */
$(document).ready(function(){
	Backbone.history.start({pushstate:false});

});
