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
 *
 * Support Funtions
 *
 */

function formatSet(arr){
  var set = '';
	for(i=0; i< arr.length; i++){
		set = set + (set == '' ? '':',')+hyphenFormat(trim(arr[i])).toLowerCase();
	}
	return set;
}

/**
 * User List
 *
 *
 */
app.views.UsersView = Backbone.View.extend({
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


    var target = e.target;
    //model = this.collection.get(target.id);


    return this;
  }
});

/**
 * User Form
 *
 *
 */
app.views.UserFormView = Backbone.View.extend({
  el: '#userContext',
  model: null,
  initialize: function(options){
	options || (options = {});
    _.bind(this, 'render');
    this.listenTo(this.model, 'change', this.render);

  },
  render: function(){
	// build content
	var template = _.template($("#userForm").html(), {user:this.model.attributes});
    $(this.el).html(template);
	$('#roles').autocomplete({
		delimiter: ',',
		lookup: userView.roles/*,
		onSelect: function (suggestion) {
			alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
		}*/
	});
    return this;
  },
  events:{
    "click #submit":"saveUser",
	"click #password": "changePassword",
    "click #close":"cancel"
  },
  saveUser:function () {
    this.model.set({

        username: $('#username').val(),
        is_active: ($('#is_active').is(':checked') ? 1:0),
        email: $('#email').val(),
		roles: formatSet($('#roles').val().split(',')),
		password: $('#password').val()

    });
	// validation
	var errors = this.model.validate();
	if(typeof(errors) !== 'undefined'){

		var template = _.template($("#errorModal").html(), {'caption':'The following error(s) have occured:', 'errors':errors});
		$('#dialog').html(template).modal();
		return true;
	}
    if (this.model.isNew()) {
        var self = this;
        app.router.userList.create(this.model, {
            success:function () {
                app.router.navigate('/', {trigger: true});
            }
        });
    } else {
        this.model.save({}, {
            success:function () {
			  // update assignments
              app.router.navigate('/', {trigger: true});
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

  changePassword: function(e){
	console.log(e.target);
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
app.controller = Backbone.Router.extend({

	routes: {
        "" : "index",                       // initial view /tasks
		"role/:role" : "index",				// filter by tag
        "add" : "add",						// add a reporting task /tasks#add
        "remove/:id" : "remove",			// remove reporting task /tasks#remove/{task_id}
		"edit/:id" : "edit"					// edit reporting task /task#edit/{task_id}
	},
    /*
     * Display Account User List
     */
    index: function(role){

        this.userList = new app.collections.Users({key: apiKey, role:role});
        new app.views.UsersView({collection: this.userList});
		// info box
		$.ajax({
			url:'/api/user/roles/'+apiKey,
			dataType: "json",
			success: function(response){
			  userView.roles = response.data;
				$("#infoBox").html(_.template($("#info").html(), {roles:response.data, select:role}));
			}

		});

    },
    /*
     * Add User
     */
    add: function(){
	  // if called direct the need this.userList
	  if(typeof this.userList == 'undefined') this.userList = new app.collections.Users({key: apiKey});
	  var user = new app.models.User({is_active:1});
	  new app.views.UserFormView({model:user}).render();
	  // enable password and email controls on new user
	  $('#email, #password').prop('disabled', false);

    },
    /*
     * Edit user
     */
    edit: function(id){
	  var user = this.userList.get(id);
	  new app.views.UserFormView({model:user}).render();
	  // add password change dialog??




    },
    /*
     * Remove User
     */
    remove: function(id){

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

  		$.ajax({
			url:'/api/user/roles/'+apiKey,
			dataType: "json",
			success: function(response){
			  userView.roles = response.data;
			  Backbone.history.start({pushstate:false});
			}

		});

});
