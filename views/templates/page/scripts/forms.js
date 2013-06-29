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
    "click .feed":"detail",
    "click #prevMo":"prevMo",
    "click #nextMo":"nextMo"
  },

  render: function(){
    var params = { records: this.collection.models};


    var template = _.template($("#forms").html(), params);
    $(this.el).html(template);

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
   * Display Form Details in a Modal Dialog
   *
   */
  detail: function(e){

    console.log('detail called');
    var target = e.target;
    model = this.collection.get(target.id);

    var template = _.template($("#formDetail").html(), model.toJSON());
    $('#dialog').html(template).modal();
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
    //"change input":"change",
    //"click .field-info":"details",
    "click #submit":"saveForm",
    "click #close":"cancel"
  },
  //change:function (event) {
  //    var target = event.target;
  //    console.log('changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value);
  //
  //},
  saveForm:function () {
    this.model.set({
        title: $('#formTitle').val(),
        description: $('#formDescription').val(),
		meta: {name:$('#formName').val(),
			   title:$('#formTitle').val(),
			   desc:$('#formDescription').val(),
			   fields:parseFormMeta()}

    });

    if (this.model.isNew()) {
        var self = this;
        router.formList.create(this.model, {
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
  details:function(e){
    console.log('detail called');
    var target = e.target;
	console.log(target);
  },
  render: function(){
	// build content
    var template = _.template($("#formForm").html(), this.model.toJSON());
    $(this.el).html(template);
    // build form controls
	console.log(this.model.toJSON());

	buildForm(0, 'buildForm', this.model.toJSON().meta, '#form-ctrl-column');

	// assign add events to selectorField(s)
	$(".selectorField").each(function(i, e){
		// append to the form
		$(this).on('click', function(){
			//clone and add
			t = $(this).clone();
			t.removeClass("selectorField");
			t.appendTo($('.droppedFields'));
			t.attr('id', 'ctl'+ctrlIndex++);
			assingDialog(t);
			$(t).trigger('click');
		})

	});

	//buildForm(form-ctrl-column, form-ctrl-column, )
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
        "remove/:id" : "remove",
		"edit/:id" : "edit",
		"add" : "add"
	},

    /*
     * Display Reporting Tasks Forms
     */
    index: function(){

        this.formList = new window.Forms({key: apiKey});
        new window.FormsView({collection: this.formList});
    },

    /*
     * Add Form
     */
    add: function(){
		console.log('Add Form');
		var form = new window.Form();
		console.log(form);
        new window.FormView({model: form}).render();
    },

    /*
     * Edit Events
     */
    edit: function(id){
		//if(typeof(this.formList) == 'undefined') this.formList = new window.Forms({key: apiKey, taskId: 1});
        var form = this.formList.get(id);
		console.log(this.form);
        new window.FormView({model:form}).render();
    },

    /*
     * Remove Events
     */
    remove: function(id){

    }
});


/**
 *
 *
 *
 *
 */

function assingDialog(field){
	$(field).on('click', function(){

		ctrl = $(this);
		// fetch attrb
		attrb = {
			id:ctrl.attr('id'),
			type:ctrl.data('type'),
			rules:ctrl.data('rules').split("|"),
			name: ctrl.data('name'),
			display: ctrl.find('label:first-child').text(),
			options:[]
		};

		if(attrb.type == 'checkbox' || attrb.type == 'radio' ){
			attrb.options = (getOptions($(this).find('ul'), 'li'));
		}
		else if(attrb.type == 'dropdown' || attrb.type == 'select' ){
			attrb.options = (getOptions($(this).find('select'),'option'));
		}

		var template = _.template($("#fieldDetail").html(), attrb);
		$('#dialog').html(template).modal();

	})
}

function delete_ctrl(ctlId){
	ctlId.remove();
}
function update_ctrl(ctlId){

	$(ctlId).find('label').text($('#formModal').find('#display').val());

	$(ctlId).data('name', $('#formModal').find('#name').val());

	if($('#formModal').find('#required').is(':checked')){
		$(ctlId).data('rules', 'required'+$('#formModal').find('#rules').val());
	}
	else{
		$(ctlId).data('rules', 'optional'+$('#formModal').find('#rules').val());
	}
	$('#formModal').find('#rules').val();

}

var ctrlIndex = 100;
//
//
function buildForm(taskId, formId, formMeta, div){

    var newForm = document.createElement('form');
    newForm.setAttribute('id', formMeta.name);
    newForm.setAttribute('onSubmit', 'return false;');
	newForm.setAttribute('class', 'droppedFields'); //
    $(div).append(newForm);
    // create error div
    var error = document.createElement('div');
    error.setAttribute('id', 'error');
    $(newForm).append(error);
    // create form fields
    for (index in formMeta.fields){
        field = formMeta.fields[index];
        fieldSet = document.createElement('div');
		fieldSet.setAttribute('id', 'ctl'+ctrlIndex++); //
		fieldSet.setAttribute('class', 'well'); //
		fieldSet.setAttribute('data-name', field.name); //
		fieldSet.setAttribute('data-rules', field.rules); //
		fieldSet.setAttribute('data-type', field.type); //
		// add to form
        $(newForm).append(fieldSet);
		// add info btn
		//$(fieldSet).append('<div class="pull-right field-info"><i class="icon-edit icon" data-field-type="'+field.type+'">&nbsp</i></div>');

		assingDialog(fieldSet);
		// Field Label (display)
        $(fieldSet).append(createLabel(field.display));
        // Field Input Control
        switch(field.type){
            case 'text':
                buildText(fieldSet, field.name);
                break;
            case 'paragraph':
                buildParagraph(fieldSet, field.name);
                break;
            case 'checkbox':
                buildCheckbox(fieldSet, field.name, field.values);
                break;
            case 'radio':
                buildRadio(fieldSet, field.name, field.values);
                break;
            case 'select':
                buildSelect(fieldSet, field.name, field.values);
                break;
        }
    }
    // make sortable
	$( ".droppedFields" ).sortable({
		cancel: null, // Cancel the default events on the controls
		connectWith: ".droppedFields"
	}).disableSelection();
}

function parseFormMeta(){
    var frm = $('form.droppedFields').find('div.well');

    var formFields = [];
    //
    $.each(frm, function(index, value) {

        var field = {};

        field.display = $(this).find('label').text();
        field.type = fieldType = $(this).data('type');
        switch(fieldType){
            case 'text':
                field.name = $(this).find('input').attr('name');
                break;
            case 'paragraph':
                field.name = ($(this).find('textarea').attr('name'));
                break;
            case 'checkbox':
                field.name = ($(this).find('ul').data('name'));
                field.values = getOptions($(this).find('ul'), 'li');
                break;
            case 'radio':
                field.name = ($(this).find('ul').data('name'));
                field.values = getOptions($(this).find('ul'), 'li');
                break;
            case 'dropdown':
                field.name = ($(this).find('select').attr('name'));
                field.values = getOptions($(this).find('select'),'option');
                break;
            case 'select':
                field.name = ($(this).find('select').attr('name'));
                field.values = getOptions($(this).find('select'),'option');
                break;
        }
        field.rules = $(this).data('field-rules');
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
