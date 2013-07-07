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
 * Views - Collection of Task Forms
 *
 *
 */
window.FormsView = Backbone.View.extend({
  el: '#formContext',
  collection: null,
  initialize: function(options){
    _.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .detailBtn":"detail",
    "click .deleteBtn":"deleteForm"
  },

  render: function(){
    var params = { records: this.collection.models};

    var template = _.template($("#forms").html(), params);
    $(this.el).html(template);

    return this;
  },


  /**
   * Display Form Details in a Modal Dialog
   *
   */
  detail: function(e){

    console.log('detail called');
    var target = e.target;
    model = this.collection.get(target.id);
	console.log(model.attributes);
    var template = _.template($("#formDetail").html(), model.attributes);
    $('#dialog').html(template).modal();
    return this;
  },

  deleteForm: function(e){

    console.log('delete called');
    var target = e.target;
    //model = this.collection.get(target.id);
	console.log(target.id);

    return this;
  }

});

/**
 * View - Single Task Form
 *
 *
 */
window.FormView = Backbone.View.extend({
  el: '#formContext',
  model: null,
  initialize: function(options){
    _.bind(this, 'render');
    //this.listenTo(this.model, 'change', this.render);

  },
  events:{

    "click #submit":"saveForm",
    "click #close":"cancel"
  },

  saveForm:_.debounce(function() {

    this.model.set({
        title: $('#formTitle').val(),
        description: $('#formDescription').val(),
		tags: $('#formTags').val(),
		is_published: ($('#formPublished').is(':checked') ? 1:0),
		meta: {name:$('#formName').val(),
			   title:$('#formTitle').val(),
			   desc:$('#formDescription').val(),
			   fieldset:[{name:'group-1', legend:'',fields:parseFormMeta()}]}

    });
	// validation
	var errors = this.model.validate();
	if(typeof(errors) !== 'undefined'){
		console.log(errors);
		var template = _.template($("#errorModal").html(), {'caption':'The following error(s) have occured:', 'errors':errors});
		$('#dialog').html(template).modal();
		return true;
	}

	// Save It
    if (this.model.isNew()) {
        //var self = this;
        router.formList.create(this.model, {
            success:function () {
                router.navigate('/', {trigger: true});
            }
        });
    } else {

        this.model.save({}, {
            success:function () {
				console.log('success')
                router.navigate('/', {trigger: true});
            }
        });
    }

    //window.history.back();

    return false;
  }, 500),
  details:function(e){
    console.log('detail called');
    var target = e.target;
	console.log(target);
  },
  render: function(){
	// build content
    var template = _.template($("#formForm").html(), this.model.attributes);
    $(this.el).html(template);
    // build form controls
	var ctrlIndex = 1, fldIndex = 1;
	$('#'+this.model.attributes.meta.name).buildForm(this.model.attributes.meta, {'ctrlClass':'well', 'fsClass':'droppedFields'});

    // make sortable
	$( ".droppedFields" ).sortable({
		cancel: null, // Cancel the default events on the controls
		connectWith: ".droppedFields"
	}).disableSelection();

	// assign dialogs
	$('fieldset.droppedFields', '#'+this.model.attributes.meta.name).find('div.well').each(function(i,e){
		var ctrlId = 'ctl'+ctrlIndex++;
		assingDialog(this, ctrlId);
		$('div#'+ctrlId).find('span').text($('div#'+ctrlId).data('rules'));
	});

	// assign add events to selectorField(s)
	$(".selectorField").each(function(i, e){
		// append to the form
		$(e).on('click', function(){
			//clone and add
			t = $(this).clone();
			$(t).removeClass("selectorField");
			$(t).appendTo($('fieldset.droppedFields'));
			$(t).data('name', 'col'+fldIndex++);
			// assign dialog
			assingDialog($(t), 'ctl'+ctrlIndex++);
			$(t).trigger('click');
		})
	});

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
        "" : "index",                       // initial view
		"tag/:tag" : "index",				// filter by tag
		"edit/:id" : "edit",
		"add" : "add"
	},

    /*
     * Display Reporting Tasks Forms
     */
    index: function(tag){

        this.formList = new window.Forms({key: apiKey, tag: tag});
        new window.FormsView({collection: this.formList});
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
     * Add Form
     */
    add: function(){

		var form = new window.Form();

        new window.FormView({model: form}).render();
    },

    /*
     * Edit Form
     */
    edit: function(id){
		$("#infoBox").html('');
        var form = this.formList.get(id);

        new window.FormView({model:form}).render();
    }
});


/**
 *
 *
 *
 *
 */

function remove_form(ctlId){
	model = router.formList.get(ctlId);
	console.log(router.formList);
	model.destroy();
	console.log(router.formList);
	router.formList.fetch();

}

function assingDialog(field, id){
	$(field).attr('id', id);
	$(field).on('click', function(){

		ctrl = $(this);
		// fetch data attrb from div
		attrb = {
			id:ctrl.attr('id'),
			type:ctrl.data('type'),
			rules:ctrl.data('rules').split("|"),
			name: ctrl.data('name'),
			display: ctrl.find('label:first-child').text(),
			options:[]
		};
		// build options as needed
		if(attrb.type == 'checkbox-group' || attrb.type == 'radio-group' ){
			attrb.options = (getOptions($(this).find('ul'), 'li'));
		}
		else if(attrb.type == 'dropdown' || attrb.type == 'select' ){
			attrb.options = (getOptions($(this).find('select'),'option'));
		}

		var template = _.template($("#fieldDetail").html(), attrb);
		$('#dialog').html(template).modal();

		// SET RULES IF ANY
		if(attrb.rules.length == 2) {
			$('select#rules option[value="|'+attrb.rules[1]+'"]').prop('selected', true);
			console.log(attrb.rules[1]);
		}
	})
}

function delete_ctrl(ctlId){
	ctlId.remove();
}

function update_ctrl(ctlId){

	var ctlType = $(ctlId).data('type');
	// //*[@id="fieldDisplay"]
	$(ctlId).find('label').text($('#formModal').find('#fieldDisplay').val());
	// //*[@id="fieldName"]
	$(ctlId).data('name', $('#formModal').find('#fieldName').val());
	// validation
	if($('#formModal').find('input#required').is(':checked')){
		$(ctlId).data('rules', 'optional');
	}
	else{
		$(ctlId).data('rules', 'required');
	}
	if(ctlType == 'text'){
		$(ctlId).data('rules', $(ctlId).data('rules')+$('#formModal').find('select#rules').val());

	}
	$(ctlId).find('span').text($(ctlId).data('rules'));
	// options
	if(ctlType == 'checkbox' || ctlType == 'radio' || ctlType == 'dropdown' || ctlType == 'select' ){
		var options = $('#formModal').find('textarea#options').val().split('\n');
		if(ctlType == 'checkbox-group' || ctlType == 'radio-group'){
			setOptions($(ctlId), 'ul', ctlType, options);
		}
		else if(ctlType == 'dropdown' || ctlType == 'select' ){
			setOptions($(ctlId), 'select', ctlType, options);
		}

	}

}

function parseFormMeta(){
    var frm = $('fieldset.droppedFields').find('div.well');

    var formFields = [];
    //
    $.each(frm, function(index, value) {

        var field = {};

        field.display = $(this).find('label').text();
        field.type = fieldType = $(this).data('type');
        switch(fieldType){
            case 'text':
            case 'paragraph':
                field.name = $(this).data('name');
                break;
            case 'checkbox-group':
            case 'radio-group':
                field.name = $(this).data('name');
                field.values = getOptions($(this).find('ul'), 'li');
                break;
            case 'dropdown':
            case 'select':
                field.name = $(this).data('name');
                field.values = getOptions($(this).find('select'),'option');
                break;
        }
        field.rules = $(this).data('rules');
        formFields.push(field);

    });
	return formFields;
}
function getOptions(sel, el){

	opt = [];
	sel.find(el).each(function(index, value){
		opt.push({'label': $(this).text(), "value": el=='li'?$(this).find('input').attr('value'):$(this).attr('value')});
	});
	return opt;

}
function setOptions(sel, el, typ, opt){
	// Reset
	sel.find(el).html('');
	// Build
	opt.forEach(function(item){
		var lv = item.split('=');
		// only key-value pairs
		if(lv.length === 2){
			if(typ == 'checkbox' || typ == 'radio'){
				sel.find(el).append('<li><input type="'+typ+'" value="'+lv[1]+'">'+lv[0]+'</li>');
			}
			else if(typ == 'dropdown' || typ == 'select'){
				sel.find(el).append('<option value="'+lv[1]+'">'+lv[0]+'</option>');
			}
		}
	});
}
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
