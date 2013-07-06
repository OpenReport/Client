<!--


-->
<div class="container-fluid">
	<div id="userContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>



<!-- Templates -->
<script id="users" type="text/template">
        <div class="span12">
		<h4>Users</h4>
            <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
				<th></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(user) { }}
              <tr>
                <td><a class="user" id="{{= user.get('id') }}"><i class="icon-info icon-white"></i>&nbsp;{{= user.get('username') }}</a></td>
                <td>{{= user.get('email') }}</td>
                <td>{{= user.get('password') }}</td>
                <td><span class="pull-right"><a class="" href="#edit/{{= user.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>

</script>

<script id="userForm" type="text/template">
    <div class="form-horizontal" id="postEvent">
    <fieldset class="span12">
        <legend>User</legend>
        <div class="control-group">
            <label class="control-label">Name</label>
            <div class="controls">
                <input type="text" value="{{= user.username }}"class="span12" id="username" name="username" placeholder="" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Email</label>
            <div class="controls">
				<input type="text" value="{{= user.email }}"class="span12" id="email" name="email" placeholder="" disabled >
            </div>
        </div>
        <div class="control-group">
			<label class="control-label"><strong>Report Assignments</strong></label>
			<div class="controls">
            <table class="table">
            <thead>
             <tr>
                <th>Title</th>
                <th>Assigned</th>
              </tr>
            </thead>
            <tbody>
            {{ _(assignments).each(function(form) {  }}
              <tr>
                <td>{{= form.get('form_title') }}</td>
                <td><input name="assign" id="{{= form.get('form_id') }}" type="checkbox" {{= form.get('is_assigned')? 'checked' : '' }} ></td>
              </tr>
            {{ }); }}
            </tbody>
            </table>
			</div>
        </div>
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
    </fieldset>
    </div>


	<script type="text/javascript">

	$('input[name=assign]').mousedown(function() {
		if ($(this).is(':checked')) {
			this.checked = !confirm("Are you sure you want to un-assign this report?");
			$(this).trigger("change");
		}

	});
	</script>

</script>


<script id="userDetail" type="text/template">
	<div class="modal" id="taskModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>User Details</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span10">
					<ul class="details">
						<li><strong>Name:</strong> {{= username }}</li>
						<li><strong>email:</strong> {{= email }}</li>
					</ul>
					<span class="pull-right">&nbsp;<a class="" href="#">Delete <i class="icon-minus-sign icon-white"></i></a>&nbsp;<a id="{{= id }}" href="#edit/{{= id }}">Edit <i class="icon-edit icon-white"></i></a></span>
				</div>
			</div>
		</div>
	</div>
</script>


<script type="text/javascript" src="/views/templates/page/scripts/shared/userModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/users.js"></script>
