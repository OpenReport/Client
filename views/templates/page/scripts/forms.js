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
 *   **** SUPPORT FUNCTIONS ****
 *
 *
 *
 */
var updateRD = false;
/**
 *
 *
 *
 *
 */
function remove_form(ctlId){
	model = router.formList.get(ctlId);
	model.destroy();
	router.formList.fetch();

}

function assignDialog(field, id){
	$(field).attr('id', id);
	$(field).on('click', function(){

		ctrl = $(this);
		// fetch data attrb from div
		attrb = {
			id:ctrl.attr('id'),
			type:ctrl.data('type'),
			rules:ctrl.data('rules').split("|"),
			name: ctrl.data('name'),
			display: ctrl.find('label:first').text(),
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
		}
	})
}

function delete_ctrl(ctlId){
	$(ctlId).remove();
	updateRD = true;
	updateList();
}
/**
 *
 *
 */
function update_ctrl(ctlId){

	var ctlType = $(ctlId).data('type');
	// //*[@id="fieldDisplay"]
	$(ctlId).find('label').text($('#formModal').find('#fieldDisplay').val());
	// TODO - Warn on name change and validate name format (lowercase and no white-space)
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
	$(ctlId).find('span').text(rulesToString($(ctlId).data('rules')));
	// options
	if(ctlType == 'checkbox-group' || ctlType == 'radio-group' || ctlType == 'dropdown' || ctlType == 'select' ){
		var options = $('#formModal').find('textarea#options').val().split('\n');
		if(ctlType == 'checkbox-group' || ctlType == 'radio-group'){
			setOptions($(ctlId), 'ul', ctlType, options);
		}
		else if(ctlType == 'dropdown' || ctlType == 'select' ){
			setOptions($(ctlId), 'select', ctlType, options);
		}
	}
	updateRD = true;
	updateList();
}
function parseFormMeta(){
    var frm = $('fieldset.droppedFields').find('div.well');

    var formFields = [];
    //
    $.each(frm, function(index, value) {

        var field = {};

        field.display = $(this).find('label:first').text();
        field.type = fieldType = $(this).data('type');
        switch(fieldType){
            case 'text':
            case 'comment':
            case 'media:image':
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
			if(typ == 'checkbox-group' || typ == 'radio-group'){
				sel.find(el).append('<li><input type="'+typ.split('-')[0]+'" value="'+lv[1]+'">'+lv[0]+'</li>');
			}
			else if(typ == 'dropdown' || typ == 'select'){
				sel.find(el).append('<option value="'+lv[1]+'">'+lv[0]+'</option>');
			}
		}
	});
}
function rulesToString(rules){
	var ruleStrings = $.validateForm.defaultOptions.messages;
	var r =rules.split('|');
	var ret = '';
	for(i=0; i< r.length; i++){
		ret = ret+ruleStrings[r[i]];
	}
	return ret;
}
function updateList(){
	fieldset = [{fields:parseFormMeta()}];

	//$.each(, function(index, value) {
	ol = $('ol#col-list');
	ol.html('');

	select = $('select#identity_name');
	id = select.val();
	select.html('');

	for (var i = 0; i < fieldset[0].fields.length; i++){
		ol.append('<li><strong>'+fieldset[0].fields[i].name+':</strong>'+fieldset[0].fields[i].type+'</li>');
		select.append('<option value="'+fieldset[0].fields[i].name+'">'+fieldset[0].fields[i].display+'</option>');
	}
	select.val(id);
}


function listNames(fieldset){
	names=[];
	_.filter(fieldset[0].fields,
		function(obj){
			names.push({'name':obj.name, 'display':obj.display, 'type':obj.type});
		}
	);
	return names;
}

/**********************************************************************************************/

/**
 * Views - Collection of Reporting Forms
 *
 *
 */
app.views.FormsView = Backbone.View.extend({
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

    var target = e.target;
    model = this.collection.get(target.id);

    var template = _.template($("#formDetail").html(), model.attributes);
    $('#dialog').html(template).modal();
    return this;
  },

  deleteForm: function(e){


    var target = e.target;
    //model = this.collection.get(target.id);


    return this;
  }

});

/**
 * View - Single Reporting Form
 *
 *
 */
app.views.FormView = Backbone.View.extend({
  el: '#formContext',
  model: null,
  columns: null,
  initialize: function(options){
    _.bind(this, 'render');
    //this.listenTo(this.model, 'change', this.render);

  },
  events:{
	"keyup #formTags": "tagAutoComplete",
    "click #submit":"saveForm",
    "click #close":"cancel"
  },

  tagAutoComplete: function(e){
	//console.log(String.fromCharCode(e.keyCode).toLocaleLowerCase());

  },

  saveForm:_.debounce(function() {

    this.model.set({
        title: $('#formTitle').val(),
        description: $('#formDescription').val(),
		tags: $('#formTags').val(),
		is_published: ($('#is_published').is(':checked') ? 1:0),
		is_public: ($('#is_public').is(':checked') ? 1:0),
		new_report: ($('#new_report').is(':checked') ? 1:0),
		identity_name: $('#identity_name').val(),
		meta: {name:$('#formName').val(),
			   title:$('#formTitle').val(),
			   desc:$('#formDescription').val(),
			   fieldset:[{name:'group-1', legend:'',fields:parseFormMeta()}]}

    });
	// validation
	var errors = this.model.validate();
	if(typeof(errors) !== 'undefined'){

		var template = _.template($("#errorModal").html(), {'caption':'The following error(s) have occured:', 'errors':errors});
		$('#dialog').html(template).modal();
		return true;
	}

	// Save It
    if (this.model.isNew()) {
        //var self = this;
        app.data.formList.create(this.model, {
            success:function () {
                app.router.navigate('/', {trigger: true});
            }
        });
    } else {

        this.model.save({}, {
            success:function () {

                app.router.navigate('/', {trigger: true});
            }
        });
    }

    //window.history.back();

    return false;
  }, 500),

  render: function(){
	// build content
    var template = _.template($("#formBuilder").html(), this.model.attributes);
    $(this.el).html(template);
    // build form controls
	var ctrlIndex = 1, fldIndex = 1;
	$('#'+this.model.attributes.meta.name).buildForm(this.model.attributes.meta, {'ctrlClass':'well clearfix', 'fsClass':'droppedFields','fldClass':'span12'});

    // make sortable
	$( ".droppedFields" ).sortable({
		cancel: null, // Cancel the default events on the controls
		connectWith: ".droppedFields"
	}).disableSelection();

	// assign dialogs
	$('fieldset.droppedFields', '#'+this.model.attributes.meta.name).find('div.well').each(function(i,e){
		var ctrlId = 'ctl'+ctrlIndex++;
		assignDialog(this, ctrlId);
		$('div#'+ctrlId).find('span').text(rulesToString($('div#'+ctrlId).data('rules')));
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
			assignDialog($(t), 'ctl'+ctrlIndex++);
			$(t).trigger('click');
		})
	});
	// capture column names
	this.columns = listNames(this.model.attributes.meta.fieldset);
	$("#infoBox").html(_.template($("#formInfo").html(), {columns:this.columns, identity_name:this.model.attributes.identity_name}));

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
app.controller = Backbone.Router.extend({

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

        app.data.formList = new app.collections.Forms({key: apiKey, tag: tag});
        new app.views.FormsView({collection: app.data.formList});
		// info box
		$.ajax({
			url:'/api/form/tags/'+apiKey,
			dataType: "json",
			success: function(response){
				app.data.tags = response.data;
				$("#infoBox").html(_.template($("#info").html(), {tags:app.data.tags, select:tag}));
			}

		});
	},

    /*
     * Add Form
     */
    add: function(){
		// if called direct we need this.formList
		if(typeof app.data.formList == 'undefined') app.data.formList = new app.collections.Forms({key: apiKey});
		var form = new app.models.Form();
		new app.views.FormView({model: form}).render();
    },

    /*
     * Edit Form
     */
    edit: function(id){
		$("#infoBox").html('');
        var form = app.data.formList.get(id);
        new app.views.FormView({model:form}).render();
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
	Backbone.history.start({pushstate:false});
});
