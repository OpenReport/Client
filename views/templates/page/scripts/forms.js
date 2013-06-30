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
			   fieldset:[{name:'group-1', legend:'',fields:parseFormMeta()}]}

    });

	// Save It
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
    //preview = $('#preview').attr('src');
    //$('#preview').attr('src', '');
    //setTimeout(function () {
    //    $('#preview').attr('src', preview);
    //}, 300);

    return false;
  },
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
		console.log($('div#'+ctrlId).find('span'));
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

		var form = new window.Form();

        new window.FormView({model: form}).render();
    },

    /*
     * Edit Events
     */
    edit: function(id){
		//if(typeof(this.formList) == 'undefined') this.formList = new window.Forms({key: apiKey, taskId: 1});
        var form = this.formList.get(id);

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
		if(attrb.type == 'checkbox' || attrb.type == 'radio' ){
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
		if(ctlType == 'checkbox' || ctlType == 'radio'){
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
            case 'checkbox':
            case 'radio':
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
// template pattern (Mustache {{ name }})
_.templateSettings = {
    interpolate: /\{\{\=(.+?)\}\}/g,
    evaluate: /\{\{(.+?)\}\}/g
};
var router = new window.Routes();
Backbone.history.start({pushstate:false});
