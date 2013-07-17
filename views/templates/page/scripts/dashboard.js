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
app.models.Dashboard = Backbone.Model.extend({
    url: '/api/dashboard/'+apiKey
});

/**
 * Views
 *
 *
 */
app.views.DashboardView = Backbone.View.extend({
  el: '#dashboardContext',
  model: null,
  initialize: function(options){
    this.listenTo(this.model,'change', this.render);
    this.model.fetch();
  },

  render: function(){
	var params = { stats: this.model.attributes.data};
	var template = _.template($("#details").html(), params);
	$(this.el).html(template);

	var template = _.template($("#info").html(), params);
	$('#infoBox').html(template);
	return this;
  },

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
     * Display Reporting Forms
     */
    index: function(){
        app.data.dashboard = new app.models.Dashboard();
        new app.views.DashboardView({model: app.data.dashboard});

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
