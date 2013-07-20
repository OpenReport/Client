<script type="text/javascript">
<!--

var userView = {
	roles: []
}

-->
</script>
<div class="container-fluid">
	<div id="userContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>



<!-- Templates -->
<script id="users" type="text/template">
        <div class="span12">
		<h4>Users</h4>
            <table class="table table-condensed">
            <thead>
              <tr>
                <th>Name</th>
                <th>Active</th>
                <th>Email</th>
                <th>Roles</th>
				<th><a href="#add" class="btn btn-mini btn-primary pull-right">New User&nbsp;<i class="icon icon-user"></i></a></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(user) { }}
              <tr>
                <td><a class="user" id="{{= user.get('id') }}"><i class="icon-info-sign icon-white"></i>&nbsp;{{= user.get('username') }}</a></td>
                <td>{{= (user.get('is_active') === 1) ? 'Yes':'No' }}</td>
				<td>{{= user.get('email') }}</td>
                <td>{{= user.get('roles') }}</td>
                <td><span class="pull-right"><a class="btn btn-mini btn-info" href="#edit/{{= user.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>

		<div class="btn-group btn-group pull-right">
		<button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
		<button class="btn btn-mini">Page</button>
		<button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
		</div>
</script>

<script id="info" type="text/template">

  <div id="user-roles" class="control-group">
    <h4>Filter by User Roles</h4>
	<a href="#" class="label label-important">x</a>
	{{ for (var i = 0; i < roles.length; i++) { }}
		<a href="#role/{{= roles[i] }}" class="label {{= select == roles[i] ? 'label-info':''}}">{{= roles[i] }}</a>
	{{ } }}
  </div>

</script>

<style>
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
</style>
<script id="userForm" type="text/template">

    <div class="form-horizontal">
    <fieldset class="span12">
        <legend>User</legend>
        <div class="control-group">
            <label class="control-label">Status</label>
            <div class="controls">
				<label><input type="checkbox" id="is_active" {{= (user.is_active === 1) ? 'checked':'' }}>Is Acitve</label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Name</label>
            <div class="controls">
                <input type="text" value="{{= user.username }}"class="span12" id="username" name="username" placeholder="" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Roles</label>
            <div class="controls">
                <input type="text" value="{{= user.roles }}"class="span12" id="roles" name="roles" placeholder="" >
            </div>
        </div>
		<div class="control-group">
            <label class="control-label">Email</label>
            <div class="controls">
				<input type="text" value="{{= user.email }}"class="span12" id="email" name="email" placeholder="" disabled >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Password</label>
            <div class="controls">
				<input type="password" value=""class="span12" id="password" name="password" placeholder="" disabled >
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
    </fieldset>
    </div>

	 <script type="text/javascript">

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
						<li><strong>Accessed:</strong> {{= date_last_accessed === null ? '':date_last_accessed.date }}</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/javascript" src="/assets/js/vendor/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/userModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/users.js"></script>
