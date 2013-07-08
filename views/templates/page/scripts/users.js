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
 * User List
 *
 *
 */
window.UsersView = Backbone.View.extend({
  el: '#userContext',
  collection: null,
  pageIndex: 0,
  recordCount: 0,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .user":"detail",
	//"click .assignReport":"assignReport",
	"click #nextPage":"next",
	"click #prevPage":"prev"
  },

  render: function(){
	this.recordCount = (this.collection.models.length);

	var params = { records: this.collection.models.slice(this.pageIndex,10+this.pageIndex)};
    var template = _.template($("#users").html(), params);
    $(this.el).html(template);

    return this;
  },

  next: function(index){
	this.pageIndex = (this.pageIndex + 10) < this.recordCount ?  this.pageIndex + 10 : this.pageIndex;
	this.render();

  },
  prev:function(index){
	this.pageIndex = this.pageIndex > 10 ? this.pageIndex - 10 : 0;
	this.render();

  },

  /**
   * Display User Details in a Modal Dialog
   *
   */
  detail: function(e){

    var target = e.target;
    model = this.collection.get(target.id);

    var template = _.template($("#userDetail").html(), model.attributes);
    $('#dialog').html(template).modal();
    return this;
  },

  deleteUser: function(e){

    console.log('delete called');
    var target = e.target;
    //model = this.collection.get(target.id);
	console.log(target.id);

    return this;
  }
});

/**
 * User Form
 *
 *
 */
window.UserFormView = Backbone.View.extend({
  el: '#userContext',
  model: null,
  initialize: function(options){
	options || (options = {});
    _.bind(this, 'render');
    this.listenTo(this.model, 'change', this.render);

  },
  events:{
    "click #submit":"saveUser",
    "click #close":"cancel"
  },
  saveUser:function () {
    this.model.set({

        username: $('#username').val(),
        email: $('#email').val(),
		password: $('#password').val()

    });
	// validation
	var errors = this.model.validate();
	if(typeof(errors) !== 'undefined'){
		console.log(errors);
		var template = _.template($("#errorModal").html(), {'caption':'The following error(s) have occured:', 'errors':errors});
		$('#dialog').html(template).modal();
		return true;
	}
    if (this.model.isNew()) {
        var self = this;
        router.userList.create(this.model, {
            success:function () {
                router.navigate('/', {trigger: true});
            }
        });
    } else {
        this.model.save({}, {
            success:function () {
			  // update assignments
              router.navigate('/', {trigger: true});
            }
        });
    }
    this.close();
    // force refreash
    preview = $('#preview').attr('src');
    $('#preview').attr('src', '');
    setTimeout(function () {
        $('#preview').attr('src', preview);
    }, 300);

    return false;
  },
  render: function(){
	// build content
	var template = _.template($("#userForm").html(), {user:this.model.attributes});
    $(this.el).html(template);
    return this;
  },
  cancel:function () {
    this.close();
    window.history.back();
  },
  close:function () {

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
        "" : "index",                       // initial view /tasks
        "add" : "add",						// add a reporting task /tasks#add
        "remove/:id" : "remove",			// remove reporting task /tasks#remove/{task_id}
		"edit/:id" : "edit"					// edit reporting task /task#edit/{task_id}
	},
    /*
     * Display Account User List
     */
    index: function(){

        this.userList = new window.Users({key: apiKey});
		//console.log(this.taskList);
        new window.UsersView({collection: this.userList});
    },
    /*
     * Add User
     */
    add: function(){
	  var user = new window.User();
	  new window.UserFormView({model:user}).render();

	  $('#email, #password').prop('disabled', false);

    },
    /*
     * Edit user
     */
    edit: function(id){
	  var user = this.userList.get(id);
	  new window.Assignments().fetchForms({key: apiKey, user_id: id, success: function(data){
		  new window.UserFormView({model:user}).render();
		}
	  });

    },
    /*
     * Remove User
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
