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
 * Views
 *
 *
 */
app.views.IdentitiesView = Backbone.View.extend({
  el: '#identityContext',
	pageIndex:0,
	tag:'',
	columns:[],
  collection: null,
  initialize: function(options){
		var base = this;
		options || (options = {});
		this.tag = options.tag;
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetchRecords();
		// info box - column filters
		$.ajax({
			url:'/api/identity/names/'+apiKey,
			dataType: "json",
			success: function(response){
				base.columns = response.data;
				$("#infoBox").html(_.template($("#idenityFilters").html(), {tags:base.columns, select:base.tag}));
				$('#identityImport').on('click', function(event){base.identityImport(event, base);});
			}
		});
  },

  render: function(){
    var params = { records: this.collection.models, count:this.collection.recCount};

    var template = _.template($("#identities").html(), params);
    $(this.el).html(template);

  },
  events:{
    "click button.edit":"editIdentity",
		"click button.add":"addIdentity",
    "click #nextPage":"prevPage",
    "click #prevPage":"nextPage",
		//"click #identityImport": "identityImport"
  },
  editIdentity:function(e){
		var base = this;
		var id = $(e.currentTarget).data('for');
		var param = this.collection.get(id);

		$('#dialog').html('');
		var template = _.template($("#identityEditDialog").html(), param.attributes);
		$('#dialog').html(template).modal();
		// assign button event
		$('#identitySubmit').on('click', function(event) {

			var id = $(event.currentTarget).data('for');
			var identity = base.collection.get(id);

			identity.set({
				identity: hyphenFormat($('#identity').val().toUpperCase()),
				identity_name: underscoreFormat($('#identity_name').val().toLowerCase()),
				description: $('#description').val()
			});
			identity.save();
			// HACK - Need a better method
			setTimeout(function(){base.collection.fetch();}, 150);

		});

	},
	addIdentity: function(event){
		var base = this;
		var identity = new app.models.Identity();
		$('#dialog').empty();$('#dialog').unbind();
		var template = _.template($("#identityEditDialog").html(), identity.attributes);
		$('#dialog').html(template).modal();
		$('#identity, #identity_name').prop('readonly', false);
		// add auto-complete
		$('input#identity_name').autocomplete({
			autoSelectFirst: true,
			lookup: base.columns
		});
		// assign button event
		$('#identitySubmit').on('click', function(event) {
			identity.set({
				identity: hyphenFormat($('#identity').val().toUpperCase()),
				identity_name: underscoreFormat($('#identity_name').val().toLowerCase()),
				description: $('#description').val()
			});
			identity.save();
			// HACK - Need a better method
			setTimeout(function(){base.collection.fetch();}, 150);

		});

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
	identityImport:function(event, base){

		var identityList = $("#importData").val().split(/\r|\n/);
		$.each(identityList, function (i, name) {
				 // empty string check
				 if(name != ""){
					cols = name.split(',');
					base.collection.create(new app.models.Identity({identity_name:cols[0], identity:cols[1], description:cols[2]}));

				 }
		 });
		base.collection.fetchRecords({pageOffset:0});

	},
  close:function () {
    $(this.el).unbind();
    $(this.el).empty();
		$("#infoBox").unbind();
		$("#infoBox").empty();
  }

});


//app.views.IdentityImport = Backbone.View.extend({
//  el: '#identityContext',
//  collection: null,
//  initialize: function(options){	}
//
//});


/**
 * Routes
 *
 *
 */
app.controller = Backbone.Router.extend({

	routes: {
    "" : "index",
		"filter/:tag" : "index",				// filter by tag
		"import": "importIDs"
	},
    /*
     * Display Identities List
     */
    index: function(tag){

      this.idenityList = new app.collections.Identities({key: apiKey, tag: tag});
			if(app.pageView !== null) app.pageView.close();
      app.pageView = new app.views.IdentitiesView({collection: this.idenityList, tag: tag});

    },
		importIDs: function(){

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
