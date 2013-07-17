<!--


-->
<div class="container-fluid">
	<div id="assignmentContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>
<!-- Templates -->
<script id="assignments" type="text/template">
        <div class="span12">
            <table class="table table-condensed">
            <thead>
              <tr>
                <th>Forms</th>
                <th>Roles</th>

				<th><button id="add" class="btn btn-mini btn-primary pull-right">New Assignment&nbsp;<i class="icon icon-check"></i></button></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(assignment) { }}
              <tr>
                <td>{{= assignment.get('form_tag') }}</td>
                <td>{{= assignment.get('user_role') }}</td>

                <td><span class="pull-right"><button class="delete btn btn-mini btn-danger" id="{{= assignment.get('id') }}">Delete <i class="icon-remove icon-white"></i></button></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>
		<div class="btn-group btn-group pull-right" style="display: none;">
		<button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
		<button class="btn btn-mini">Page</button>
		<button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
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

<script id="assignDialog" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Report Form Assignment</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<select id="userList" class="span12">
						<option value="">Select User</option>
						</select>
					</div>
					<div class="control-group">
					<select id="reportForms" name="reportForms" class="span12" multiple="multiple" size='10'>
					</select>
					</div>
					<div class="control-group pull-right">
					<button id='assignSubmit' class="btn btn-mini btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>

</script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/assignment.js"></script>
