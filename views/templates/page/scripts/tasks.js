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
window.TasksView = Backbone.View.extend({
  el: '#taskContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .task":"detail",
    "click #prevMo":"prevMo",
    "click #nextMo":"nextMo"
  },

  render: function(){
    var params = { records: this.collection.models};

	console.log(params.records[0].get('title'));
    var template = _.template($("#tasks").html(), params);
    $(this.el).html(template);
	//$.bootstrapSortable();
    return this;
  },

  prevMo: function(){

    if(--curMonth < 1){
        curMonth = 12;
        --curYear;
    }
	navTime.subtract('M',1)
    this.collection.fetchMonth({id: 2, mo: 0, yr: 0});
  },

  nextMo: function(){
    if(++curMonth > 12){
        curMonth = 1;
        ++curYear;
    }
	navTime.subtract('M',1)
    this.collection.fetchMonth({id: 2, mo: curMonth, yr: curYear});
  },

  /**
   * Display Event Details in a Modal Dialog
   *
   */
  detail: function(e){

    console.log('detail called');
    var target = e.target;
    model = this.collection.get(target.id);

    var template = _.template($("#taskDetail").html(), model.toJSON());
    $('#dialog').html(template).modal();
    return this;
  }

});

window.TaskFormView = Backbone.View.extend({
  el: '#taskContext',
  model: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.model, 'change', this.render);

  },
  events:{
    //"change input":"change",
    "click #submit":"savePost",
    "click #close":"cancel"
  },
  //change:function (event) {
  //    var target = event.target;
  //    console.log('changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value);
  //
  //},
  savePost:function () {
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
	var template = _.template($("#taskForm").html(), this.model.toJSON());
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
     * Display Current Month's Events
     */
    index: function(){

        this.taskList = new window.Tasks({key: apiKey, mo: curMonth, yr: curYear});
		//console.log(this.taskList);
        new window.TasksView({collection: this.taskList});
    },
    /*
     * Add Event
     */
    add: function(){

         new window.TaskFormView({model: new window.Task()}).render();

    },
    /*
     * Edit Events
     */
    edit: function(id){
        var form = this.taskList.get(id);
        new window.TaskFormView({model:form}).render();
    },
    /*
     * Remove Events
     */
    remove: function(id){

    }
});


/**
 *
 * Start App
 *
 */
// template pattern (Mustache {{ name }})
_.templateSettings = {
    interpolate: /\{\{\=(.+?)\}\}/g,
    evaluate: /\{\{(.+?)\}\}/g
};
var router = new window.Routes();
Backbone.history.start({pushstate:false});
