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
window.AssignmentsView = Backbone.View.extend({
  el: '#assignmentContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .user":"detail"
  },

  render: function(){
    var params = { records: this.collection.models};

	console.log(params);

    var template = _.template($("#assignments").html(), params);
    $(this.el).html(template);
	//$.bootstrapSortable();
    return this;
  },

  /**
   * Display User Details in a Modal Dialog
   *
   */
  detail: function(e){

    console.log('detail called');
    var target = e.target;
    model = this.collection.get(target.id);

    var template = _.template($("#assignmentDetail").html(), model.toJSON());
    $('#dialog').html(template).modal();
    return this;
  }

});


/**
 * Routes
 *
 *
 */
window.Routes = Backbone.Router.extend({

	routes: {
        "" : "index",                       // initial view /tasks
        "add" : "add",						// add a reporting task /tasks#add
        "remove/:id" : "remove",			// remove reporting task /tasks#remove/{task_id}
		"edit/:id" : "edit"					// edit reporting task /task#edit/{task_id}
	},
    /*
     * Display Account User List
     */
    index: function(){

        this.assignmentList = new window.Assignments({key: apiKey});
		//console.log(this.taskList);
        new window.AssignmentsView({collection: this.assignmentList});
    },
    /*
     * Add Assignment
     */
    add: function(){



    },
    /*
     * Edit Assignment
     */
    edit: function(id){

    },
    /*
     * Remove Assignment
     */
    remove: function(id){

    }
});


// template pattern (Mustache {{ name }})
_.templateSettings = {
	interpolate: /\{\{\=(.+?)\}\}/g,
	evaluate: /\{\{(.+?)\}\}/g
};

var router = new window.Routes();
/**
 *
 * Start App
 *
 */
$(document).ready(function(){
		Backbone.history.start({pushstate:false});
});
