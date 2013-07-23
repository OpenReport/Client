/**
 * OpenReport Form Builder
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
 */
;(function($){
/**
 *
 * Usage: $("#formId").buildForm(formMeta, options);
 * Alt Usage: $.buildForm('#testForm', formMeta, options)
 *
 */
  $.buildForm = function(el, formMeta, options){
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;
		if( typeof( formMeta ) === "undefined" || formMeta === null ) return base;
		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;

		base.init = function(){
			//base.formMeta = formMeta;
			base.options = $.extend({},$.buildForm.defaultOptions, options);

			if(typeof( formMeta.fieldset ) === "undefined" || formMeta.fieldset === null){
				_buildFields(formMeta, base.$el);
			}
			else{
				_buildFieldsets(formMeta.fieldset);
			}

			function _buildFieldsets(fieldsets){
				// Build the form sets
				for(fsi in fieldsets){
					fieldset = fieldsets[fsi];

					var fs = document.createElement('fieldset');
					fs.setAttribute("id", fieldset.name);
					fs.setAttribute("class", base.options.fsClass);
					var el = base.$el.append(fs).find('fieldset');
					if(fieldset.legend !== ''){
						el.append(document.createElement('legend').appendChild(document.createTextNode(fieldset.legend)));
					}

					// create fieldset controls
					_buildFields(fieldset.fields, el);

				} // end fieldset loop

			}

			function _buildFields(fields, el){

				for(index in fields){
					field = fields[index];

					// field wrapper
					fieldCtrl = document.createElement('div');
					fieldCtrl.setAttribute("class", base.options.ctrlClass);
					fieldCtrl.setAttribute("data-rules", field.rules);
					fieldCtrl.setAttribute("data-type", field.type);
					fieldCtrl.setAttribute("data-name", field.name);
					el.append(fieldCtrl);
					// add form label
					$(fieldCtrl).append(build.Label(field.name, field.display));

					// render input fields
					switch(field.type){
						case 'text':
							build.TextBox(fieldCtrl, field.name);
							break;
						case 'comment':
							build.CommentBox(fieldCtrl, field.name);
							break;
						case 'checkbox-group':
							build.CheckboxGroup(fieldCtrl, field.name, field.values);
							break;
						case 'radio-group':
							build.RadioGroup(fieldCtrl, field.name, field.values);
							break;
						case 'dropdown':
							build.Dropdown(fieldCtrl, field.name, field.values);
							break;
						case 'select':
							build.Select(fieldCtrl, field.name, field.values);
							break;
						case 'media:image':
							build.MediaCapture(fieldCtrl, field.name, 'video/*;capture=camera');
							break;
					}
					$(fieldCtrl).append('<span class="error"></span>');
				}

			}

		};
		// private functions
		var build = {
			Label:function(name, title){
				var label = document.createElement('label');
				label.setAttribute('for',name);
				$(label).append(title);
				return label;
			},
			TextBox: function(el, name){
				$(el).append(createInput(name, 'text', ''));
			},
			CommentBox: function(selector, name){
				$(selector).append(createText(name));
			},
			CheckboxGroup: function(selector, name, values){
				//ul
				var ul = document.createElement('ul');
				ul.setAttribute('class', 'options');
				$(selector).append(ul);
				for (index in values){
					var li = document.createElement('li');
					field = values[index];
					$(li).append($(build.Label(name, field.label)).prepend(createInput(name, 'checkbox', field.value)));
					$(ul).append(li);
				}
			},
			RadioGroup: function(selector, name, values){
				//ul
				var ul = document.createElement('ul');
				ul.setAttribute('class', 'options');
				$(selector).append(ul);
				for (index in values){
					var li = document.createElement('li');
					field = values[index];
					$(li).append($(build.Label(name, field.label)).prepend(createInput(name, 'radio', field.value)));
					$(ul).append(li);
				}
			},
			Dropdown: function(selector, name, values){
				var select = createSelect(name);
				$(selector).append(select);
				//$(select).append(createOption("Select One", 0)); DROP
				// build options for select
				for (index in values){
					field = values[index];
					$(select).append(createOption(field.label, field.value));
				}
			},
			Select: function(selector, name, values){
				var select = createSelect(name);
				$(select).attr('multiple', 'multiple')
				$(selector).append(select);
				// build options for select
				for (index in values){
					field = values[index];
					$(select).append(createOption(field.label, field.value));
				}
			},
			// mobile <input type="file" accept="video/*;capture=camera" />
			MediaCapture: function(el, name, accept){
				var field = createInput('imageCapture-'+name, 'file', '');
				field.setAttribute('accept', accept);;
				field.setAttribute('style', 'display: none;');
				field.setAttribute('class', 'imageCapture');
				$(el).append(field);
				var input = createInput(name, 'hidden', '');
				$(el).append(input);
				var ul = document.createElement('ul');
				ul.setAttribute('class', 'capture-list');
				ul.setAttribute('id', 'capture-img-'+name);
				var li = document.createElement('li');
				var img = document.createElement('img');
				img.setAttribute('src', './img/camara.png');
				img.setAttribute('class', 'capture-btn')
				img.setAttribute('data-for', name)
				$(li).append(img);
				$(ul).append(li);
				$(el).append(ul);
			}
		}
		// Run initializer
		base.init();

		// low level libs
		// input type text
		function createInput(name, type, value){
			var field = document.createElement('input');
			field.setAttribute('name',name);
			field.setAttribute('id',name);
			field.setAttribute('value',value);
			field.setAttribute('type',type);
			if(type == 'text'){
				field.setAttribute('class',base.options.fldClass);
			}
			return field;
		}
		// textarea (paragraph)
		function createText(name){
			var field = document.createElement('textarea');
			field.setAttribute('name',name);
			field.setAttribute('id',name);
			field.setAttribute('class',base.options.fldClass);
			return field;
		}
		function createSelect(name){
			var field = document.createElement('select');
			field.setAttribute('id',name);
			field.setAttribute('name',name);
			field.setAttribute('class',base.options.fldClass);
			return field;
		}
		//
		function createOption(label, value){
			var option=document.createElement("option");
			option.text = label;
			option.value = value;
			return option;
		}
		return base;
  };

  $.buildForm.defaultOptions = {
	  ctrlClass: "ctrl clearfix",
	  lblClass: "ctrlLabel",
	  fsClass: "crtlSet",
	  fldClass: "ctrlField",
	  errClass: "ctrlError"
  };
  $.fn.buildForm = function(formMeta, options){
  // return for chaining
	  return this.each(function(){
		  (new $.buildForm(this, formMeta, options));
	  });
  };


})(window.Zepto || window.jQuery);
