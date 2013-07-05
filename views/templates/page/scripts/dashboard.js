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
window.Dashboard = Backbone.Model.extend({
    urlRoot: '/api/dashboard/'+apiKey

});

/**
 *
 *
 *
 */
window.DashboardView = Backbone.View.extend({
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
	console.log(params);
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
        "" : "index"
	},
    /*
     * Display Reporting Forms
     */
    index: function(){
        this.dashboard = new window.Dashboard();
        new window.DashboardView({model: this.dashboard});

    }

});


/**
 *
 * Start App
 *
 */
$(document).ready(function(){

	// template pattern (Mustache {{ name }})
	_.templateSettings = {
		interpolate: /\{\{\=(.+?)\}\}/g,
		evaluate: /\{\{(.+?)\}\}/g
	};

		var router = new window.Routes();
		Backbone.history.start({pushstate:false});
});
