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

window.AccountFormView = Backbone.View.extend({
  el: '#userContext',
  model: null,
  initialize: function(options){
    this.listenTo(this.model,'change', this.render);
    this.model.fetch();

  },
  events:{
    //"change input":"change",
    "click #submit":"saveAccount",
    "click #close":"cancel"
  },
  //change:function (event) {
  //    var target = event.target;
  //    console.log('changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value);
  //
  //},
  saveAccount:function () {


	this.model.save({
        name: $('#acctname').val()
	  },
	  {
		success:function () {
		  window.history.back();
		}
	  }
	);

    return this;
  },
  render: function(){
	// build content

	var template = _.template($("#accountForm").html(), this.model.attributes);
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
        "" : "index"                       // initial view
	},
    /*
     * Display Account User List
     */
    index: function(){

        this.account = new window.Account({key: apiKey});
		//console.log(this.taskList);
        new window.AccountFormView({model: this.account});
    },

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
