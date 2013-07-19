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
app.views.DistributionsView = Backbone.View.extend({
  el: '#distributionContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  render: function(){
    var params = { records: this.collection.models};

    var template = _.template($("#distributions").html(), params);
    $(this.el).html(template);

	// info box
	if($("#infoBox").html() !== '')return this;
	var base = this;
	$.ajax({
		url:'/api/form/tags/'+apiKey,
		dataType: "json",
		success: function(response){
			$("#infoBox").html(_.template($("#tags").html(), {tags:response.data}));
			//"click .tag-btn":"filterByTag",
			$('.tag-btn').on('click', function(e){base.filterByTag(e, base);});
		  $.ajax({
			  url:'/api/user/roles/'+apiKey,
			  dataType: "json",
			  success: function(response){
				  $("#infoBox").append(_.template($("#roles").html(), {roles:response.data}));
				  $('.role-btn').on('click', function(e){base.filterByRole(e, base);});
			  }
		  });
		}
	});

	//$.bootstrapSortable();
    return this;
  },
  events:{
    "click .delete":"deleteDistribution",
	"click #add":"addDistribution"
  },

  deleteDistribution: function(e){
	var target = e.target;
	var base = this;

	this.collection.sync('delete', base.collection.get(target.id), {success:function(){base.collection.fetch();}});
  },

  addDistribution: function(e){
	var base = this;
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
	  var form = $('#reportForms').val();
	  var roles = $('#userList').val();
	  if(roles !== null && form !== ''){
		for (var i = 0; i < roles.length; i++) {
		  // assign for each tag
		  var assignment = new app.models.Distribution();
		  assignment.set({
			user_role: roles[i],
			form_tag: form
		  });
		  assignment.save();
		}
		// HACK - Need a better method
		setTimeout(function(){base.collection.fetch();}, 500);

	  }

	});

    return this;
  },
  /**
   * REVIEW - SHOULD USE _.filterBy(collection, 'tag', 'inspection');
   *
   */
  filterByTag: function(e, base){
	$('.tag-btn, .role-btn').removeClass('label-success');
	if($(e.target).data('for')!==''){
	  $(e.target).addClass('label-success');
	  base.collection.fetchByTag({tag:$(e.target).data('for')});
	}
	else{
	  base.collection.fetchAll();
	}
  },
  filterByRole: function(e, base){
	$('.tag-btn, .role-btn').removeClass('label-success');
	if($(e.target).data('for')!==''){
	  $(e.target).addClass('label-success');
	  base.collection.fetchByRole({role:$(e.target).data('for')});
	}
	else{
	  base.collection.fetchAll();
	}
  }

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
     * Display Distribution List
     */
    index: function(){

	  this.distributionList = new app.collections.Distributions({key: apiKey});
	  new app.views.DistributionsView({collection: this.distributionList});

    },

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
