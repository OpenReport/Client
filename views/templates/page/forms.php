<!--


-->


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
	  <h3>Details</h3>
		<div class="">
			<label class="control-label">Form Id</label>
			<input type="text" id="formName" placeholder="Form Id..." value="{{= meta.name }}" class="ctrl-textbox span12">
		</div>
		<div class="">
			<label class="control-label">Title</label>
			<input type="text" id="formTitle" placeholder="Form Title..." value="{{= title }}" class="ctrl-textbox span12">
		</div>
		<div class="">
			<label class="control-label">Description</label>
			<textarea type="text" id="formDescription" placeholder="Form Description..." class="ctrl-textbox span12">{{= description }}</textarea>
		</div>
	  </div>

	  <div class="tab-pane" id="frmCtrls">
		<div class="selectorField well" data-rules="optional" data-name="" data-type="text">
			<label class="control-label">Text Input</label>
			<input type="text" placeholder="Text here..." class="ctrl-textbox">
		</div>
		<div class="selectorField well" data-rules="optional" data-name="" data-type="paragraph">
			<label class="control-label">Comments</label>
			<textarea placeholder="Enter Comments" class=""></textarea>
		</div>
		<div class="selectorField well" data-rules="optional" data-name="" data-type="dropdown">
			<label class="control-label">Combobox</label>
			<select class="ctrl-combobox">
				<option value="option1">Option 1</option>
				<option value="option2">Option 2</option>
				<option value="option3">Option 3</option>
			</select>
		</div>
		<div class="selectorField well" data-rules="optional" data-name="" data-type="radio">
			<label class="control-label" style="vertical-align:top">Radio buttons</label>
			<div style="display:inline-block;" class="ctrl-radiogroup">
				<label class="radio"><input type="radio" name="radioField" value="option1">Option 1</label>
				<label class="radio"><input type="radio" name="radioField" value="option2">Option 2</label>
				<label class="radio"><input type="radio" name="radioField" value="option3">Option 3</label>
			</div>
		</div>
		<div class="selectorField well" data-rules="optional" data-name="" data-type="checkbox">
			<label class="control-label" style="vertical-align:top">Checkboxes</label>
			<div style="display:inline-block;" class="ctrl-checkboxgroup">
				<label class="checkbox"><input type="checkbox" name="checkboxField" value="option1">Option 1</label>
				<label class="checkbox"><input type="checkbox" name="checkboxField" value="option2">Option 2</label>
				<label class="checkbox"><input type="checkbox" name="checkboxField" value="option3">Option 3</label>
			</div>
		</div>
		<div class="selectorField well" data-rules="optional" data-name="" data-type="select">
			<label class="control-label" style="vertical-align:top">Select multiple</label>
			<div style="display:inline-block;">
				<select multiple="multiple" style="width:150px" class="ctrl-selectmultiplelist">
					<option value="option1">Option 1</option>
					<option value="option2">Option 2</option>
					<option value="option3">Option 3</option>
				</select>
			</div>
		</div>
	  </div>

    </div>

	<div class="span8" id="selected-content">

	  <div class="row-fluid">
		<div id="form-ctrl-column" class="span12 well" style="min-height:80px;background-color: rgb(255, 255, 255);">

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

<script id="fieldDetail" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Report Field Details</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span10">
					<ul class="">
						<li><strong>Display:</strong><input id="display" type="text" placeholder="Display Text Here..." value="{{= display }}"></li>
						<li><strong>Label:</strong><input id="name" type="text" placeholder="Data label Here..." value="{{= name }}"></li>
						<li><strong>Required:</strong><input  id="required" type="checkbox">
						<select  id="rules" class="ctrl-combobox">
							<option value="">Any Characters</option>
							<option value="|alpha">Text Only</option>
							<option value="|alpha_numeric">Alpha-Numeric</option>
							<option value="|numeric">Numeric Only</option>
							<option value="|valid_email">Valid Email</option>
						</select>
						</li>
						{{ if(options.length > 0){ }}
							<li><strong>Options:</strong><textarea id="options" class="">{{ options.forEach(function (item) { }}	{{= item.name }}={{= item.value + '\n'}} {{ }) }}</textarea></li>
						{{ } }}
					</ul>
					<button type="button" onclick='delete_ctrl({{= id }})' class="btn" data-dismiss="modal">DELETE<i class="icon-minus-sign icon-white"></i></button>
					<button type="button" onclick='update_ctrl({{= id }})' class="btn btn-primary" data-dismiss="modal">Save<i class="icon-minus-sign icon-white"></i></button>

				</div>
			</div>
		</div>
	</div>
</script>


<script type="text/javascript" src="/views/templates/page/scripts/lib/form.controls.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/forms.js"></script>
