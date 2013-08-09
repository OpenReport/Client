/*
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

/*
 *
 * Golbals
 *
 */
 var libarayFields = [];
/*
 *   **** SUPPORT FUNCTIONS ****
 *
 *
 *
 */

function remove_form(ctlId){
	model = router.formList.get(ctlId);
	model.destroy();
	router.formList.fetch();

}

function assignDialog(field){

  // assign event
	$(field).on('click', function(event){

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

	});
	return $(field); // for channing
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
	updateList();
	// check if standard control
	if($('#standard').is(':checked')){

		 libarayFields.push(parseField($(ctlId)));

	}

}

function delete_ctrl(ctlId){
		$(ctlId).remove();
		updateList();
}

function parseFormMeta(){
    var frm = $('fieldset.droppedFields').find('div.well');

    var formFields = [];
    //
    $.each(frm, function(index, value) {
        formFields.push(parseField($(this)));
    });
		return formFields;
}

function parseField(ctlEl){
		var field = {};

		 field.display = ctlEl.find('label:first').text();
		 field.type = fieldType = ctlEl.data('type');
		 switch(fieldType){
				 case 'text':
				 case 'comment':
				 case 'media:image':
						 field.name = ctlEl.data('name');
						 break;
				 case 'checkbox-group':
				 case 'radio-group':
						 field.name = ctlEl.data('name');
						 field.values = getOptions(ctlEl.find('ul'), 'li');
						 break;
				 case 'dropdown':
				 case 'select':
						 field.name = ctlEl.data('name');
						 field.values = getOptions(ctlEl.find('select'),'option');
						 break;
		 }
		 field.name = underscoreFormat(field.name).toLowerCase();
		 field.rules = ctlEl.data('rules');
		 return field;
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
	select.html('<option value="" selected>None</option>');

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
    //_.bind(this, 'render');
    this.listenTo(this.collection, 'reset', this.render);
    this.collection.fetch();

  },

  events:{
    "click .detailBtn":"detail"
  },

  render: function(){
	var params = { forms:this.collection.models, count:this.collection.recCount };
    var template = _.template($("#forms").html(), params);
    $(this.el).html(template);

    return this;
  },

  /**
   * Display Form Details in a Modal Dialog
   *
   */
  detail: function(e){

    var target = e.currentTarget;
    model = this.collection.get(target.id);
    var template = _.template($("#formDetail").html(), model.attributes);
    $('#dialog').html(template).modal();
    return this;
  },

  close: function(){
    $(this.el).unbind();
    $(this.el).empty();
  }

});

/**
 * View - Edit Reporting Form
 *
 *
 */
app.views.FormView = Backbone.View.extend({
  el: '#formContext',
  model: null,
  columns: null,
	ctrlIndex: 1,
  initialize: function(options){
    //_.bind(this, 'render');
    //this.listenTo(this.model, 'change', this.render);

  },
  render: function(){
		var base = this;
	// build content
    var template = _.template($("#formBuilder").html(), this.model.attributes);
    $(this.el).html(template);
    // build form controls
		$('#'+this.model.attributes.meta.name).buildForm(this.model.attributes.meta, {'ctrlClass':'field well clearfix', 'fsClass':'droppedFields','fldClass':'span12'});
		$('#formTags').autocomplete({
			tabDisabled: true,
			autoSelectFirst: true,
			lookup: app.data.tags
		});
		// make sortable
		$( ".droppedFields" ).sortable({
				cancel: null, // Cancel the default events on the controls
				connectWith: ".droppedFields"
		}).disableSelection();

		// assign dialog event to existing controls
		$('div.field').each(function(i,e){
			var ctrlId = 'ctl'+base.ctrlIndex++;
			$(e).attr('id', ctrlId);
		  $(e).find('input, textarea, select').each(function(){$(this).prop('disabled', true);})
			$('div#'+ctrlId).find('span').text(rulesToString($('div#'+ctrlId).data('rules')));
		});

		// Build Libaray Tab
		$.ajax({
			url:'/api/libaray/'+apiKey,
			dataType: "json",
			async: false,
			success: function(response){
				// add items
				$('#standards').buildForm(response.data, {'ctrlClass':'selectorField well clearfix','fldClass':'span12'});

			}
		});
		// assign add events to selectorField(s)
		$(".selectorField").each(function(i, e){
			$(e).unbind();
			$(e).attr('id','fld'+i);
			$(e).find('input, textarea, select').each(function(){$(this).prop('disabled', true);})

		});
		// capture column names
		this.columns = listNames(this.model.attributes.meta.fieldset);
		$("#infoBox").html(_.template($("#formInfo").html(), {columns:this.columns, identity_name:this.model.attributes.identity_name}));
			return this;
		},

		events:{
			"click #submit": "saveForm",
			"click #close": "cancel",
			"click div.selectorField": "addField",
			"click div.field": "openDialog"
		},

  saveForm:_.debounce(function() {

    this.model.set({
				title: $('#formTitle').val(),
				description: $('#formDescription').val(),
				tags: hyphenFormat($('#formTags').val()),
				is_published: ($('#is_published').is(':checked') ? 1:0),
				is_public: ($('#is_public').is(':checked') ? 1:0),
				new_report: ($('#new_report').is(':checked') ? 1:0),
				identity_name: $('#identity_name').val(),
				meta: {name:hyphenFormat($('#formName').val()).toUpperCase(),
				title:$('#formTitle').val(),
				desc:$('#formDescription').val(),
				fieldset:[{name:hyphenFormat($('#formName').val()).toUpperCase()+'-A', legend:'',fields:parseFormMeta()}]}
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
            success:function (d) {
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
		// check for any new libaray items
		if(libarayFields.length > 0){
			$.ajax({
					type: "POST",
					url: "/api/libaray/"+apiKey+"/",
					data: JSON.stringify(libarayFields),
					contentType: "application/json; charset=utf-8",
					dataType: "json",
			});
			libarayFields = []; // CLEAR IT
		}
		this.close();
    return false;
  }, 150),

	addField: function(event){

		ctrl = $(event.currentTarget).clone();
		$(ctrl).removeClass("selectorField");
		$(ctrl).addClass("field");
		$(ctrl).attr('id', 'ctl'+this.ctrlIndex++);
		$(ctrl).appendTo($('fieldset.droppedFields'));
		$(ctrl).trigger('click');
	},

	openDialog:function(event){

		ctrl = $(event.currentTarget);
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
			attrb.options = (getOptions($(ctrl).find('ul'), 'li'));
		}
		else if(attrb.type == 'dropdown' || attrb.type == 'select' ){
			attrb.options = (getOptions($(ctrl).find('select'),'option'));
		}
		var template = _.template($("#fieldDetail").html(), attrb);
		$('#dialog').html(template).modal();

		// SET RULES IF ANY
		if(attrb.rules.length == 2) {
			$('select#rules option[value="|'+attrb.rules[1]+'"]').prop('selected', true);
		}
		//be sure to un-bind and distroy on hidden
		$("#fieldDetail").on('hidden', function(){
				$("#dialog").unbind();
				$("#dialog").empty();
		});
		$('#fieldDisplay').focus();
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

  initialize: function(){
		// get lists...
		$.ajax({
			url:'/api/form/tags/'+apiKey,
			dataType: "json",
			async:false,
			success: function(response){
				app.data.tags = response.data;
			}
		});
	},

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
				if(app.pageView != null) app.pageView.close();
        app.pageView = new app.views.FormsView({collection: app.data.formList});
				$("#infoBox").html(_.template($("#info").html(), {tags:app.data.tags, select:tag}));

	},
    /*
     * Add Form
     */
    add: function(){
				// if called direct we need this.formList
				if(typeof app.data.formList == 'undefined') app.data.formList = new app.collections.Forms({key: apiKey});
				var form = new app.models.Form();
				if(app.pageView != null) app.pageView.close();
				app.pageView = new app.views.FormView({model: form}).render();
    },

    /*
     * Edit Form
     */
    edit: function(id){
				// if called direct we need this.formList
				if(typeof app.data.formList == 'undefined') app.data.formList = new app.collections.Forms({key: apiKey});
				$("#infoBox").empty();
        var form = app.data.formList.get(id);
				if(app.pageView != null) app.pageView.close();
        app.pageView = new app.views.FormView({model:form}).render();
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
