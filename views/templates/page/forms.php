
<div id="formContext" class="row-fluid well well-small"></div>
<!-- Modals -->
<div id="dialog"></div>

<!-- Templates -->
<script id="forms" type="text/template">
        <div class="span12">

			<h4>Reporting Forms<br/>
			<small>showing {{= forms.length}} of {{= count }} forms</small></h4>
			<table class="table table-condensed">
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
				<th>Tag</th>
                <th>Published</th>
                <th>Public</th>
                <th><a href="#add" class="btn btn-mini btn-primary pull-right">New Form&nbsp;<i class="icon icon-list-alt"></i></a></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(forms).each(function(form) { }}
              <tr>
                <td><a class="detailBtn" id="{{= form.id }}" href="#"><i class="icon-info-sign icon-white"></i>&nbsp;{{= form.attributes.title }}</a></td>
                <td>{{= form.attributes.description }}</td>
				<td>{{= form.attributes.tags }}</td>
				<td>{{= form.attributes.is_published === 1 ? 'Yes':'No' }}</td>
				<td>{{= form.attributes.is_public === 1 ? 'Yes':'No' }}</td>
                <td><span class="pull-right"><a class="btn btn-mini btn-info" href="#edit/{{= form.attributes.id }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
		<div class="btn-group btn-group pull-right">
		<button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
		<button class="btn btn-mini">Page</button>
		<button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
        </div>
	</div>
</script>
<style>
	img.capture-btn,
	img.capture-img{
		 border: solid 1px black;height: 64px; width:64px;
	margin: 2px;
	padding: 1px;
	}
	ul.capture-list {
	  list-style-type: none;
	  margin: 0;
	  padding: 0;
	  float: none;
	}
	ul.capture-list > li {
	 float: left;
	}
	span.error{
		float: left;
		clear: both;
	}
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
</style>
<script id="formBuilder" type="text/template">
<div class="tabbable">
	<!-- List of controls rendered into Bootstrap Tabs -->
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#formAttr" data-toggle="tab">Form</a>
		</li>
		<li>
			<a href="#frmCtrls" data-toggle="tab">Controls</a>
		</li>
		<li>
			<a href="#frmFields" data-toggle="tab">Libaray</a>
		</li>
	</ul>
	<div class="row-fluid">
	<div id="listOfFields" class="span4 tab-content">
	  <div class="tab-pane active well" id="formAttr">
	  <h4>Report Form Details</h4>
		<div class="control-group">
			<label>Title:&nbsp;<small>Enter report title.</small></label>
			<input type="text" id="formTitle" placeholder="Title..." value="{{= title }}" class="ctrl-textbox span12">
		</div>
		<div class="control-group">
			<label>Description:&nbsp;<small>Enter report description.</small></label>
			<textarea type="text" id="formDescription" placeholder="Report Description..." class="ctrl-textbox span12">{{= description }}</textarea>
		</div>
		<div class="control-group">
			<label>Tag:&nbsp;<small>Enter report tag.</small></label>
			<input type="text" id="formTags" placeholder="Form Tag..." value="{{= tags }}" class="ctrl-textbox span12">
		</div>
		<div class="control-group">
			<label>Report Id:&nbsp;<small>Enter report control number.</small></label>
			<input type="text" id="formName" placeholder="Form Id..." value="{{= meta.name }}" class="ctrl-textbox span12">
		</div>
		<div class="control-group">
			<label class="control-label" style="vertical-align:top">Report Options</label>
			<ul style="display:inline-block;" class="unstyled">
				<li><input type="checkbox" id="is_published" {{= (is_published === 1) ? 'checked':'' }}>Publish Form</li>
				<li><input type="checkbox" id="is_public" {{= (is_public === 1) ? 'checked':'' }}>Assign to All Users</li>
			</ul>
		</div>

	  </div>

	  <div class="tab-pane well" id="frmCtrls">
	  <h4>Report Form Controls</br><small>Click to add</small></h4>
		<div class="selectorField well clearfix" data-rules="required" data-name="input" data-type="text" id="ctrl-A">
			<label class="control-label">Text Input</label>
			<input type="text" class="ctrl-textbox span12">
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="comment" data-type="comment">
			<label class="control-label">Comments</label>
			<textarea class="span12"></textarea>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="options" data-type="checkbox-group">
			<label class="control-label" style="vertical-align:top">Checkboxes</label>
			<ul class="ctrl-checkboxgroup ">
				<li><input type="checkbox" name="checkboxField" value="option1">Option 1</li>
				<li><input type="checkbox" name="checkboxField" value="option2">Option 2</li>
				<li><input type="checkbox" name="checkboxField" value="option3">Option 3</li>
			</ul>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="option" data-type="radio-group">
			<label class="control-label" style="vertical-align:top">Radio buttons</label>
			<ul>
				<li><input type="radio" name="radioField" value="option1">Option 1</li>
				<li><input type="radio" name="radioField" value="option2">Option 2</li>
				<li><input type="radio" name="radioField" value="option3">Option 3</li>
			</ul>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="optional" data-type="media:image" data-name="photos">
			<label for="photos">Attach Photo</label>
			<input name="imageCapture-photos" id="photo-files" type="file" accept="video/*;capture=camera" style="display: none;" class="imageCapture">
			<input name="photos" id="photos" value="" type="hidden">
			<ul class="capture-list" id="capture-img-photos">
				<li><img src="./img/camara.png" class="capture-btn" data-for="photos"></li>
			</ul>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="option" data-type="dropdown">
			<label class="control-label">Combobox</label>
			<select class="ctrl-combobox span12">
				<option value="0">Select an Option</option>
				<option value="option1">Option 1</option>
				<option value="option2">Option 2</option>
			</select>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="options" data-type="select">
			<label class="control-label" style="vertical-align:top">Select multiple</label>
			<div>
				<select multiple="multiple" class="ctrl-selectmultiplelist span12">
					<option value="0">Select an Option</option>
					<option value="option1">Option 1</option>
					<option value="option2">Option 2</option>
					<option value="option3">Option 3</option>
				</select>
				<span class="error"></span>
			</div>
		</div>
	  </div>


	  <div class="tab-pane well" id="frmFields">
	  <h4>Report Form Fields<br/><small>Predefined</small></h4>

		<div id="standards"></div>

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

	<!-- submit button -->
	<div class="row-fluid">
        <div class="form-actions">
		<label class="pull-left"><input type="checkbox" id="new_report" {{= (is_published === 1) ? 'checked':'disabled' }} >Create a new report defination</label>
            <span class="pull-right"><button id="close" class="btn btn-mini">Cancel</button> <button class="btn btn-mini btn-primary" id="submit">Save changes</button> </span>
        </div>
	</div>
  </div>

</script>

<script id="formInfo" type="text/template">

  <div id="form-stats" class="control-group">
  <h4>Report Fields</h4>
	<ol id="col-list">
	{{ for (var i = 0; i < columns.length; i++) { }}
	<li><strong>{{= columns[i].name }}:</strong>{{= columns[i].type }}</li>
	{{ } }}
	</ol>
	<h4>Identity Field</h4>
	<select id="identity_name">
	{{ for (var i = 0; i < columns.length; i++) { if(columns[i].type === 'text') }}
	<option value="{{= columns[i].name }}" {{= columns[i].name === identity_name ? 'selected':'' }}>{{= columns[i].display }}</option>
	{{ } }}
	</select>

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
					<fieldset class="well">
						<div class="control-group"><label>Display:&nbsp;<small>This is displayed on the report form</small></label><input class="span12" id="fieldDisplay" type="text" placeholder="Display Text Here..." value="{{= display }}"></div>
						<div class="control-group"><label>Label:&nbsp;<small>Name of Datapoint (column name).</small></label><input class="span12" id="fieldName" type="text" placeholder="Data label Here..." value="{{= name }}"></div>
						{{ if(options.length > 0){ }}
							<div class="control-group"><label>Options:&nbsp;<small>Set Options for the field (display=value)</small></label><textarea id="options" class="span12" rows="3">{{ options.forEach(function (item) { }}{{= item.label }}={{= item.value + '\n'}}{{ }) }}</textarea></div>
						{{ } }}
						{{if(type==='text'){ }}
						<div class="control-group"><label>Validation:&nbsp;<small>Select type of validation.</small></label>
						<select id="rules" class="span12">
							<option value="">Any Characters</option>
							<option value="|valid_email">Valid email address.</option>
							<option value="|alpha">Contain alphabetical characters only.</option>
							<option value="|alpha_numeric">Contain alpha-numeric characters.</option>
							<option value="|alpha_dash">Contain alpha-numeric characters, underscores, and dashes.</option>
							<option value="|numeric">Contain only numbers.</option>
							<option value="|integer">Contain an integer.</option>
							<option value="|decimal">Contain a decimal number.</option>
							<option value="|is_natural">Contain only positive numbers.</option>
							<option value="|is_natural_no_zero">Contain a number greater than zero.</option>
							<option value="|valid_ip">Contain a valid IP.</option>
							<option value="|valid_base64">Contain a base64 string.</option>
							<option value="|valid_credit_card">Contain a vaild credit card number</option>
							<option value="|valid_url">Contain a valid URL.</option>
						</select>
						</div>
						{{ } }}
						<div class="control-group"><label class="checkbox">Optional:&nbsp;<small>Check this box if this field is optional.</small><input id="required" type="checkbox" {{= rules[0]==='required' ? '' : 'checked' }}></label></div>
					</fieldset>

					<div class="control-group pull-right">
					<button type="button" onclick='delete_ctrl({{= id }})' class="btn btn-mini" data-dismiss="modal">DELETE<i class="icon-minus-sign icon-white"></i></button>
					<button type="button" onclick='update_ctrl({{= id }})' class="btn btn-mini btn-primary" data-dismiss="modal">Save<i class="icon-minus-sign icon-white"></i></button>
					</div>
					<div class="control-group pull-left"><label class="checkbox">Standard:&nbsp;<small>Check this box if this field is a Standard.</small><input id="standard" type="checkbox" ></label></div>
				</div>
			</div>
		</div>
	</div>
</script>



<script id="formDetail" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Report Form Details</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span12">
					<div>
						<dl class="dl-horizontal">
							<dt>Title:</dt><dd>{{= title }}</dd>
							<dt>Description:</dt><dd>{{= description }}</dd>
							<dt>Tags:&nbsp;</dt><dd>{{= tags }}</dd>
							<dt>Created On:&nbsp;</dt><dd>{{= moment(date_created.date).format('L') }}</dd>
							<dt>Last Modified:&nbsp;</dt><dd>{{= moment(date_modified.date).format('L') }}</dd>
						<dl>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script id="info" type="text/template">

  <div id="form-tags" class="control-group">
    <h4>Filter by Form Tag</h4>
	<a href="#" class="label label-important">x</a>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#tag/{{= tags[i] }}" class="label {{= select == tags[i] ? 'label-info':''}}">{{= tags[i] }}</a>
	{{ } }}
  </div>

</script>

<script type="text/javascript" src="/assets/js/vendor/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/lib/OpenReport.builder.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/lib/OpenReport.validate.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/forms.js"></script>
