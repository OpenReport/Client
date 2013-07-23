/**
 * OpenReport Form Valdation
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
   * Usage: $("#formId").validate();
   * Alt Usage:	$.validate('#testForm', options)
   *
   */
  $.validateForm = function(el, success, failure, options){
	// To avoid scope issues, use 'base' instead of 'this'
	// to reference this class from internal events and functions.
	var base = this;

	// Access to jQuery and DOM versions of element
	base.$el = $(el);
	base.el = el;
	// Add a reverse reference to the DOM object
	base.$el.data("validateForm", base);
	// regex patterns
	var ruleRegex = /^(.+?)\[(.+)\]$/,
		numericRegex = /^[0-9]+$/,
		integerRegex = /^\-?[0-9]+$/,
		decimalRegex = /^\-?[0-9]*\.?[0-9]+$/,
		emailRegex = /^[a-zA-Z0-9.!#$%&amp;'*+\-\/=?\^_`{|}~\-]+@[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*$/,
		alphaRegex = /^[a-z]+$/i,
		alphaNumericRegex = /^[a-z0-9]+$/i,
		alphaDashRegex = /^[a-z0-9_\-]+$/i,
		naturalRegex = /^[0-9]+$/i,
		naturalNoZeroRegex = /^[1-9][0-9]*$/i,
		ipRegex = /^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/i,
		base64Regex = /[^a-zA-Z0-9\/\+=]/i,
		numericDashRegex = /^[\d\-\s]+$/,
		urlRegex = /^((http|https):\/\/(\w+:{0,1}\w*@)?(\S+)|)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;

	base.init = function(){
	  base.options = $.extend({},$.validateForm.defaultOptions, options);
	  // process
	  var errorCnt = 0;
	  base.$el.find('div').each(function(i){
            // fetch rules
			var ctl = $(this).data('rules');
			if(typeof(ctl) !== 'undefined'){
				var rules = ctl.split("|");
				var name = $(this).data('name');
				rules.forEach(function(rule, index){
					// 1st Test
					if(rule === 'required'){
						$('span',this).text('');
						switch($(this).data('type')){
							case 'text':
							case 'paragraph':
								var field = $('#'+name);
								if((field.val() === null || field.val() === '')){
									errorCnt++;
									$('span',this).text(base.options.messages[rule]);
								}
							break;
							case 'checkbox-group':
							case 'radio-group':
								var field = $('input[name='+name+']:checked');
								if((field.length == 0)){
									$('span',this).text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'dropdown':
							case 'select':
								if($('select[name='+name+']').val() == 0){
									$('span',this).text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
						}
					}
					else if($(this).data('type') === 'text'){
						var field = $('#'+name);
						var span = $('span',this);
						switch(rule){

							case 'alpha':
								if(!alphaRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'alpha_numeric':
								if(!alphaNumericRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'alpha_dash':
								if(!alphaDashRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'is_natural':
								if(!naturalRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'is_natural_no_zero':
								if(!naturalNoZeroRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'numeric':
								if(!numericRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'integer':
								if(!integerRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
							case 'decimal':
								if(!decimalRegex.test(field.val())){
									span.text(base.options.messages[rule]);
									errorCnt++;
								}
							break;
						}
					}
					// process other validation rules

				}, this);

			}

		});

		if(errorCnt > 0){
			if (typeof failure === 'function') {
				failure(errorCnt);
			}
		}
		else{
			if (typeof success === 'function') {
				success();
			}
		}

	};
	// Run initializer
	base.init();
  };
  $.validateForm.defaultOptions = {
	messages:{
	  optional: 'This field is optional. ',
	  required: 'This field is required. ',
	  valid_email: 'This field must contain a valid email address. ',
	  alpha: 'This field must only contain alphabetical characters. ',
	  alpha_numeric: 'This field must only contain alpha-numeric characters. ',
	  alpha_dash: 'This field must only contain alpha-numeric characters, underscores, and dashes. ',
	  numeric: 'This field must contain only numbers. ',
	  integer: 'This field must contain an integer. ',
	  decimal: 'This field must contain a decimal number. ',
	  is_natural: 'This field must contain only positive numbers. ',
	  is_natural_no_zero: 'This field must contain a number greater than zero. ',
	  valid_ip: 'This field must contain a valid IP. ',
	  valid_base64: 'This field must contain a base64 string. ',
	  valid_credit_card: 'This field must contain a vaild credit card number',
	  valid_url: 'This field must contain a valid URL. '
    }
  };
  $.fn.validateForm = function(formMeta, options){
  // return for chaining
	  return this.each(function(){
		  (new $.validateForm(this, options));
	  });
  };
})(window.Zepto || window.jQuery);
