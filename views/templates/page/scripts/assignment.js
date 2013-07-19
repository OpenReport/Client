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
app.views.AssignmentsView = Backbone.View.extend({
  el: '#assignmentContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  render: function(){
    var params = { records: this.collection.models};

    var template = _.template($("#assignments").html(), params);
    $(this.el).html(template);

  }/*,
  events:{
    "click button.edit":"editAssignment",
	"click #add":"addAssignment"
  },
  editAssignment:function(){

  },
  addAssignment: function(){
	var base = this;
	$('#dialog').html('');
    var template = _.template($("#assignDialog").html());
    $('#dialog').html(template).modal();
  }*/
});


app.views.AssignmentForm = Backbone.View.extend({
  el: '#assignmentContext',
  model: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.model, 'reset', this.render);

  },

  render: function(){
    var params = { assignment: this.model.attributes};

    var template = _.template($("#assignmentForm").html(), params);
    $(this.el).html(template);

	// render user and forms controls
	// fill in the blanks
	$.ajax({
		url:'/api/user/'+apiKey,
		dataType: "json",
		success: function(response){
			// add users to drop down
			for (var i = 0; i < response.data.length; i++) {
			  var item = response.data[i]
			  $('#userList').append('<option value="'+item.id+'">'+item.username+'</option>');
			}
		}

	});

	$.ajax({
		url:'/api/form/'+apiKey,
		dataType: "json",
		success: function(response){
			// add forms to drop down
			for (var i = 0; i < response.data.length; i++) {
			  var item = response.data[i]
			  $('#reportList').append('<option value="'+item.id+'">'+item.title+'</option>');
			}
		}

	});


  },
  events:{
    "click #submit":"save",
    "click #close":"cancel"
  },
  save:function(){

  },
  cancel:function () {
    this.close();
    window.history.back();
  }/*,
  close:function () {

    $(this.el).unbind();
    $(this.el).empty();
  }*/


});

/**
 * Routes
 *
 *
 */
app.controller = Backbone.Router.extend({

	routes: {
        "" : "index",
		"add" : "add",						// add a reporting assigment
        "remove/:id" : "remove",			// remove reporting assigment
		"edit/:id" : "edit"					// edit reporting assigment

	},
    /*
     * Display Assignment List
     */
    index: function(){

	  this.assignmentList = new app.collections.Assignments({key: apiKey});
	  new app.views.AssignmentsView({collection: this.assignmentList});

    },
	add: function(){
	  // if called direct the need this.assignmentList
	  if(typeof this.assignmentList == 'undefined') this.assignmentList = new app.collections.Assignments({key: apiKey});
	  var assigment = new app.models.Assignment({is_active:1});
	  new app.views.AssignmentForm({model:assigment}).render();
	},
	edit: function(id){
	  var assigment = this.assignmentList.get(id);
	  new app.views.AssignmentForm({model:assigment}).render();
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
