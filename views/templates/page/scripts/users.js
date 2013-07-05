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
window.UsersView = Backbone.View.extend({
  el: '#userContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .user":"detail",
	"click .assignReport":"assignReport"
  },

  render: function(){
    var params = { records: this.collection.models};

	console.log(params);

    var template = _.template($("#users").html(), params);
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

    var template = _.template($("#userDetail").html(), model.attributes);
    $('#dialog').html(template).modal();
    return this;
  },
  assignReport: function(e){

    var target = e.target;
    var user = this.collection.get(target.id);
	console.log('before');
	formList = new window.Assignments().fetchForms({key: apiKey, user_id: user.attributes.id, success: function(data){

		var template = _.template($("#userAssign").html(), { records:data.models });
		$('#dialog').html(template).modal();
		return this;
	  }
	});
	console.log('after');
  }

});

window.UserFormView = Backbone.View.extend({
  el: '#userContext',
  model: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.model, 'change', this.render);

  },
  events:{
    //"change input":"change",
    "click #submit":"saveUser",
    "click #close":"cancel"
  },
  //change:function (event) {
  //    var target = event.target;
  //    console.log('changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value);
  //
  //},
  saveUser:function () {
    this.model.set({
        title: $('#taskTitle').val(),
        description: $('#taskDescription').val(),
        //post_date: {date: $('#postDate input').val(),timezone_type:2,timezone:timeZone},
        //expire_date: {date: $('#expireDate input').val(),timezone_type:2,timezone:timeZone}
    });
    if (this.model.isNew()) {
        var self = this;
        router.taskList.create(this.model, {
            success:function () {
                router.navigate('/', {trigger: true});
            }
        });
    } else {
        this.model.save({}, {
            success:function () {
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

	console.log(this.model.toJSON());
	var template = _.template($("#userForm").html(), this.model.toJSON());
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

         new window.UserFormView({model: new window.User()}).render();

    },
    /*
     * Edit user
     */
    edit: function(id){
        var form = this.userList.get(id);
        new window.UserFormView({model:form}).render();
    },
    /*
     * Remove User
     */
    remove: function(id){

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
