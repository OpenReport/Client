<div class="container-fluid">
	<div id="formContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>

<!-- Templates -->
<script id="forms" type="text/template">
        <div class="span12">

			<h4>Reporting Forms</h4>
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
            {{ _(records).each(function(form) { }}
              <tr>
                <td><a class="detailBtn" id="{{= form.get('id') }}" href="#"><i class="icon-info-sign icon-white"></i>&nbsp;{{= form.get('title') }}</a></td>
                <td>{{= form.get('description') }}</td>
				<td>{{= form.get('tags') }}</td>
				<td>{{= form.get('is_published') === 1 ? 'Yes':'No' }}</td>
				<td>{{= form.get('is_public') === 1 ? 'Yes':'No' }}</td>
                <td><span class="pull-right"><a class="btn btn-mini btn-info" href="#edit/{{= form.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
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
</style>

<script id="formBuilder" type="text/template">
<div class="tabbable">
	<!-- List of controls rendered into Bootstrap Tabs -->
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#formAttr" data-toggle="tab">Form</a>
		</li>
		<li>
			<a href="#frmCtrls" data-toggle="tab">Fields</a>
		</li>
	</ul>
	<div class="row-fluid">
	<div id="listOfFields" class="span4 tab-content">
	  <div class="tab-pane active well" id="formAttr">
	  <h3>Report Form Details</h3>
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
	  <h3>Report Fields <small>Click to add</small></h3>
		<div class="selectorField well clearfix" data-rules="required" data-name="iText" data-type="text">
			<label class="control-label">Text Input</label>
			<input type="text" class="ctrl-textbox span12">
			<span></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="" data-type="comment">
			<label class="control-label">Comments</label>
			<textarea class="span12"></textarea>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="" data-type="checkbox-group">
			<label class="control-label" style="vertical-align:top">Checkboxes</label>
			<ul class="ctrl-checkboxgroup ">
				<li><input type="checkbox" name="checkboxField" value="option1">Option 1</li>
				<li><input type="checkbox" name="checkboxField" value="option2">Option 2</li>
				<li><input type="checkbox" name="checkboxField" value="option3">Option 3</li>
			</ul>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="" data-type="radio-group">
			<label class="control-label" style="vertical-align:top">Radio buttons</label>
			<ul>
				<li><input type="radio" name="radioField" value="option1">Option 1</li>
				<li><input type="radio" name="radioField" value="option2">Option 2</li>
				<li><input type="radio" name="radioField" value="option3">Option 3</li>
			</ul>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="optional" data-name="" data-type="media:image">
			<label class="control-label">Attach Photo</label>
			<input type="hidden">
			<input id="imageCapture-1" style="display: none;" type="file" accept="video/*;capture=camera">
			<ul class="capture-list" id="capture-img-colImg1">
			<li>
			    <img class="capture-btn" data-for="colImg1" src="./assets/img/camara.png" style="height: 64px; width:64px;"></img>
			</li>
		    	</ul>
			<span class="clearfix"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="" data-type="dropdown">
			<label class="control-label">Combobox</label>
			<select class="ctrl-combobox span12">
				<option value="0">Select an Option</option>
				<option value="option1">Option 1</option>
				<option value="option2">Option 2</option>
			</select>
			<span class="error"></span>
		</div>
		<div class="selectorField well clearfix" data-rules="required" data-name="" data-type="select">
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
		<label class="pull-left"><input type="checkbox" id="new_report" >Check this box to create a new report defination</label>
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
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
	<select id="identity">
	{{ for (var i = 0; i < columns.length; i++) { if(columns[i].type === 'text') }}
	<option value="{{= columns[i].name }}" {{= columns[i].name === identity ? 'selected':'' }}>{{= columns[i].display }}</option>
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
					<div class="control-group pull-right">
					<button type="button" onclick='remove_form({{= id }})' class="btn btn-danger" data-dismiss="modal">REMOVE<i class="icon-minus-sign icon-white"></i></button>
					<button type="button" onclick='router.navigate("/edit/{{= id }}", true);' class="btn btn-primary" data-dismiss="modal">Edit<i class="icon-minus-sign icon-white"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script id="info" type="text/template">

  <div id="form-tags" class="control-group">
    <h4>Filter by Form Tag</h4>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#tag/{{= tags[i] }}" class="label {{= select == tags[i] ? 'label-info':''}}">{{= tags[i] }}</a>
	{{ } }}
	<a href="#" class="label label-important">x</a>
  </div>

</script>

<script type="text/javascript" src="/views/templates/page/scripts/lib/openreport.builder.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/forms.js"></script>
