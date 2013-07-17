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
    "click .delete":"deleteAssigned",
	"click #add":"addAssignment"
  },

  render: function(){
    var params = { records: this.collection.models};

    var template = _.template($("#assignments").html(), params);
    $(this.el).html(template);
	//$.bootstrapSortable();
    return this;
  },

  deleteAssigned: function(e){
	var target = e.target;
	//this.collection.remove(this.collection.get(target.id));
	router.assignmentList.sync('delete', this.collection.get(target.id));
	router.assignmentList.fetch();
  },

  addAssignment: function(e){

	$('#dialog').html('');
    var template = _.template($("#assignDialog").html());
    $('#dialog').html(template).modal();
	// fill in the blanks
	$.ajax({
		url:'/api/user/roles/'+apiKey,
		dataType: "json",
		success: function(response){
			// add users to drop down
			for (var i = 0; i < response.data.length; i++) {
			  var item = response.data[i]
			  $('#userList').append('<option value="'+item+'">'+item+'</option>');
			}
		}

	});

	$.ajax({
		url:'/api/form/tags/'+apiKey,
		dataType: "json",
		success: function(response){
			// add forms to drop down
			for (var i = 0; i < response.data.length; i++) {
			  var item = response.data[i]
			  $('#reportForms').append('<option value="'+item+'">'+item+'</option>');
			}
		}

	});

	$('#assignSubmit').on('click', function(event) {
	  // TODO: Feedback (i.e errors)
	  var forms = $('#reportForms').val();
	  var role = $('#userList').val();
	  if(forms !== null && role !== ''){
		for (var i = 0; i < forms.length; i++) {
		  // assign for each tag
		  var assignment = new window.Assignment();
		  assignment.set({
			user_role: role,
			form_tag: forms[i]
		  });
		  assignment.save({success: function(){router.assignmentList.fetch();}});
		}
	  }

	});

    return this;
  },
  /**
   * Display User Details in a Modal Dialog
   *
   */
  detail: function(e){


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
		"tag/:tag" : "index",				// filter by tag
        "add" : "add",						// add a reporting task /tasks#add
        "remove/:id" : "remove",			// remove reporting task /tasks#remove/{task_id}
		"edit/:id" : "edit"					// edit reporting task /task#edit/{task_id}
	},
    /*
     * Display Account User List
     */
    index: function(tag){

        this.assignmentList = new window.Assignments({key: apiKey, tag:tag});
		//console.log(this.taskList);
        new window.AssignmentsView({collection: this.assignmentList});
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
