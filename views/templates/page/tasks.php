<!--


-->
<div class="container-fluid">
	<div id="taskContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>



<!-- Templates -->
<script id="tasks" type="text/template">
        <div class="span12">
            <table class="table">
            <thead>
              <tr>
                <th colspan="5"><h3>Reporting Task<small class="pull-right"><a id="" href="#add">Add new reporting task</a></small></h3></th>
              </tr>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(task) { }}
              <tr>
                <td><a href="/forms#{{= task.get('id') }}"><i class="icon-folder-open icon-white"></i>&nbsp;{{= task.get('title') }}</a></td>
                <td>{{= task.get('description') }}</td>
                <td>{{= moment(task.get('date_created').date).format('L') }}</td>
                <td><span class="pull-right"><a class="" href="#edit/{{= task.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>

</script>

<script id="taskForm" type="text/template">
    <div class="form-horizontal" id="postEvent">
    <fieldset class="span12">
        <legend>Reporting Task</legend>
        <div class="control-group">
            <label class="control-label">Title</label>
            <div class="controls">
                <input type="text" value="{{= title }}"class="span12" id="taskTitle" name="taskTitle" placeholder="Enter a title for your task" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Description</label>
            <div class="controls">
                <textarea rows="3" class="span12" id="taskDescription" name="taskDescription" placeholder="Enter a description for your task" >{{= description }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
    </fieldset>
    </div>
</script>

<script id="taskDetail" type="text/template">
	<div class="modal" id="taskModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Reporting Task Detail</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span10">
					<ul class="details">
						<li><strong>Title:</strong> {{= title }}</li>
						<li><strong>Description:</strong> {{= description }}</li>
					</ul>
					<span class="pull-right">&nbsp;<a class="" href="#">Delete <i class="icon-minus-sign icon-white"></i></a>&nbsp;<a id="{{= id }}" href="#task/edit/{{= id }}">Edit <i class="icon-edit icon-white"></i></a></span>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/javascript" src="/views/templates/page/scripts/shared/taskModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/tasks.js"></script>
