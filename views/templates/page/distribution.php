<!--


-->
<div class="container-fluid">
	<div id="distributionContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>
<!-- Templates -->
<script id="distributions" type="text/template">
        <div class="span12">
			<h4>Report Distribution</h4>
            <table class="table table-condensed">
            <thead>
              <tr>
                <th>Role</th>
                <th>Forms</th>
				<th><button id="add" class="btn btn-mini btn-primary pull-right">Add Form&nbsp;<i class="icon icon-share"></i></button></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(distribution) { }}
              <tr>
                <td>{{= distribution.get('user_role') }}</td>
                <td>{{= distribution.get('form_tag') }}</td>
                <td><span class="pull-right"><button class="delete btn btn-mini btn-danger" id="{{= distribution.get('id') }}">Delete <i class="icon-remove icon-white"></i></button></span></td>
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

<script id="tags" type="text/template">


  <div id="form-tags" class="control-group">
    <h4>Filter by Form Tag</h4>
	<span data-for="" class="tag-btn label label-important">x</span>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<span data-for="{{= tags[i] }}" class="tag-btn label">{{= tags[i] }}</span>
	{{ } }}
  </div>

</script>
<script id="roles" type="text/template">


  <div id="user-roles" class="control-group">
    <h4>Filter by User Role</h4>
	<span data-for="" class="role-btn label label-important">x</span>
	{{ for (var i = 0; i < roles.length; i++) { }}
		<span data-for="{{= roles[i] }}" class="role-btn label">{{= roles[i] }}</span>
	{{ } }}
  </div>

</script>
<script id="assignDialog" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Report Form Distribution</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label>Add...</label>
						<select id="reportForms" name="reportForms" class="span12" >
						<option value="">Select Forms</option>
						</select>
					</div>
					<div class="control-group">
						<label>To User Role...</label>
						<select id="userList" class="span12" multiple="multiple" size='5'>
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
<script type="text/javascript" src="/views/templates/page/scripts/shared/distributionModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/distribution.js"></script>
