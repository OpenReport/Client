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
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  render: function(){
    var params = { records: this.collection.models, count:this.collection.recCount};

    var template = _.template($("#identities").html(), params);
    $(this.el).html(template);

  },
  events:{
    "click button.edit":"editIdentity",
		"click button.add":"addIdentity"
  },
  editIdentity:function(e){
		var base = this;
		var id = $(e.currentTarget).data('for');
		var param = this.collection.get(id);

		$('#dialog').html('');
		var template = _.template($("#identityForm").html(), param.attributes);
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
		$('#dialog').empty();
		var template = _.template($("#identityForm").html(), identity.attributes);
		$('#dialog').html(template).modal();
		$('#identity_name').prop('readonly', false);
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
	}

});


/**
 * Routes
 *
 *
 */
app.controller = Backbone.Router.extend({

	routes: {
    "" : "index"
	},
    /*
     * Display Identities List
     */
    index: function(){

      this.idenityList = new app.collections.Identities({key: apiKey});
      new app.views.IdentitiesView({collection: this.idenityList});

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
