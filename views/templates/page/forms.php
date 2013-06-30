<div class="container-fluid">
	<div id="formContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>

<!-- Templates -->
<script id="forms" type="text/template">
        <div class="span12">
            <table class="table">
            <thead>
              <tr>
                <th colspan="5"><h3>Reporting Forms<small class="pull-right"><a id="" href="#add">Add new reporting form</a></small></h3></th>
              </tr>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(form) { }}
              <tr>
                <td><a class="form" id="{{= form.get('id') }}" href="#"><i class="icon-folder-open icon-white"></i>&nbsp;{{= form.get('title') }}</a></td>
                <td>{{= form.get('description') }}</td>
                <td>{{= moment(form.get('date_created').date).format('L') }}</td>
                <td><span class="pull-right"><a class="" href="#edit/{{= form.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>

</script>

<script id="formForm" type="text/template">
<div class="tabbable">
	<!-- List of controls rendered into Bootstrap Tabs -->
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#formAttr" data-toggle="tab">Form</a>
		</li>
		<li>
			<a href="#frmCtrls" data-toggle="tab">Form Fields</a>
		</li>
	</ul>
	<div class="row-fluid">
	<div id="listOfFields" class="span4 tab-content">
	  <div class="tab-pane active well" id="formAttr">
	  <h3>Report Details</h3>
		<div class="control-group">
			<label>Title:&nbsp;<small>Enter report title.</small></label>
			<input type="text" id="formTitle" placeholder="Form Title..." value="{{= title }}" class="ctrl-textbox span12">
		</div>
		<div class="control-group">
			<label>Description:&nbsp;<small>Enter report description.</small></label>
			<textarea type="text" id="formDescription" placeholder="Form Description..." class="ctrl-textbox span12">{{= description }}</textarea>
		</div>
		<div class="control-group">
			<label>Tags:&nbsp;<small>Enter report tags.</small></label>
			<input type="text" id="formTags" placeholder="Form Title..." value="{{= tags }}" class="ctrl-textbox span12">
		</div>
		<div class="control-group">
			<label>Report Id:&nbsp;<small>Enter report control number.</small></label>
			<input type="text" id="formName" placeholder="Form Id..." value="{{= meta.name }}" class="ctrl-textbox span12">
		</div>

	  </div>

	  <div class="tab-pane" id="frmCtrls">
	  <h3>Report Fields <small>Click to add</small></h3>
		<div class="selectorField well" data-rules="required" data-name="iText" data-type="text">
			<label class="control-label">Text Input</label>
			<input type="text" class="ctrl-textbox">
			<span></span>
		</div>
		<div class="selectorField well" data-rules="required" data-name="" data-type="paragraph">
			<label class="control-label">Comments</label>
			<textarea class=""></textarea>
			<span></span>
		</div>
		<div class="selectorField well" data-rules="required" data-name="" data-type="checkbox-group">
			<label class="control-label" style="vertical-align:top">Checkboxes</label>
			<ul style="display:inline-block;" class="ctrl-checkboxgroup">
				<li><input type="checkbox" name="checkboxField" value="option1">Option 1</li>
				<li><input type="checkbox" name="checkboxField" value="option2">Option 2</li>
				<li><input type="checkbox" name="checkboxField" value="option3">Option 3</li>
			</ul>
			<span></span>
		</div>
		<div class="selectorField well" data-rules="required" data-name="" data-type="radio-group">
			<label class="control-label" style="vertical-align:top">Radio buttons</label>
			<ul>
				<li><input type="radio" name="radioField" value="option1">Option 1</li>
				<li><input type="radio" name="radioField" value="option2">Option 2</li>
				<li><input type="radio" name="radioField" value="option3">Option 3</li>
			</ul>
			<span></span>
		</div>
		<div class="selectorField well" data-rules="required" data-name="" data-type="dropdown">
			<label class="control-label">Combobox</label>
			<select class="ctrl-combobox">
				<option value="option1">Option 1</option>
				<option value="option2">Option 2</option>
				<option value="option3">Option 3</option>
			</select>
			<span></span>
		</div>
		<div class="selectorField well" data-rules="required" data-name="" data-type="select">
			<label class="control-label" style="vertical-align:top">Select multiple</label>
			<div style="display:inline-block;">
				<select multiple="multiple" style="width:150px" class="ctrl-selectmultiplelist">
					<option value="option1">Option 1</option>
					<option value="option2">Option 2</option>
					<option value="option3">Option 3</option>
				</select>
				<span></span>
			</div>
		</div>
	  </div>

    </div>

	<div class="span8" id="selected-content">

	  <div class="row-fluid">
		<div id="form-ctrl-column" class="span12 well" style="min-height:80px;background-color: rgb(255, 255, 255);">
			<form id="{{= meta.name }}" class="" submit="return false"></form>
		</div>
	  </div>
	</div>
	</div>

	<!-- Preview button -->
	<div class="row-fluid">
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
	</div>
  </div>
</script>
<style>
	.control-group > label{font-weight: bold;}
	label > small{font-weight: normal;}
</style>
<script id="fieldDetail" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Report Field Details</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span12">
					<fieldset class="">
						<div class="control-group"><label>Display:&nbsp;<small>This is displayed on the report form</small></label><input class="span12" id="fieldDisplay" type="text" placeholder="Display Text Here..." value="{{= display }}"></div>
						<div class="control-group"><label>Label:&nbsp;<small>Name of Datapoint (column name).</small></label><input class="span12" id="fieldName" type="text" placeholder="Data label Here..." value="{{= name }}"></div>
						{{ if(options.length > 0){ }}
							<div class="control-group"><label>Options:&nbsp;<small>Set Options for the field (display=value)</small></label><textarea id="options" class="span12" rows="4">{{ options.forEach(function (item) { }}{{= item.label }}={{= item.value + '\n'}}{{ }) }}</textarea></div>
						{{ } }}
						<div class="control-group"><label>Validation:&nbsp;</label></div>
						{{if(type==='text'){ }}
						<div class="control-group"><label><small>Select type of validation.</small></label>
						<select id="rules" class="span12">
							<option value="">Any Characters</option>
							<option value="|valid_email">'Valid email address.'</option>
							<option value="|alpha">'Contain alphabetical characters only.'</option>
							<option value="|alpha_numeric">'Contain alpha-numeric characters.'</option>
							<option value="|alpha_dash">'Contain alpha-numeric characters, underscores, and dashes.'</option>
							<option value="|numeric">'Contain only numbers.'</option>
							<option value="|integer">'Contain an integer.'</option>
							<option value="|decimal">'Contain a decimal number.'</option>
							<option value="|is_natural">'Contain only positive numbers.'</option>
							<option value="|is_natural_no_zero">'Contain a number greater than zero.'</option>
							<option value="|valid_ip">'Contain a valid IP.'</option>
							<option value="|valid_base64">'Contain a base64 string.'</option>
							<option value="|valid_credit_card">'Contain a vaild credit card number'v
							<option value="|valid_url">'Contain a valid URL.'</option>
						</select>
						</div>
						{{ } }}
						<div class="control-group"><label class="checkbox">Optional:&nbsp;<small>Check this box if this field is optional.</small><input id="required" type="checkbox" {{= rules[0]==='required' ? '' : 'checked' }}></label></div>
					</fieldset>
					<div class="control-group pull-right">
					<button type="button" onclick='delete_ctrl({{= id }})' class="btn" data-dismiss="modal">DELETE<i class="icon-minus-sign icon-white"></i></button>
					<button type="button" onclick='update_ctrl({{= id }})' class="btn btn-primary" data-dismiss="modal">Save<i class="icon-minus-sign icon-white"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>


<script type="text/javascript" src="/views/templates/page/scripts/lib/openreport.builder.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/forms.js"></script>
