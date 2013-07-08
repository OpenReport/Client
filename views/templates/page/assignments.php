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
            <table class="table">
            <thead>
              <tr>
                <th>Form</th>
                <th>User</th>
                <th>Date Assigned</th>
				<th><a href="#add" class="btn btn-mini btn-primary pull-right">New Assignment</a></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(assignment) { }}
              <tr>
                <td>{{= assignment.get('form_title') }}</td>
                <td>{{= assignment.get('user') }}</td>
				<td>{{= moment(assignment.get('date_assigned').date).format('L') }}</td>
                <td><span class="pull-right"><a class="" href="#edit/{{= assignment.get('id') }}">Delete <i class="icon-edit icon-white"></i></a></span></td>
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


  <div id="form-tags" class="control-group">
    <h4>Tag Filters</h4>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#tag/{{= tags[i] }}" class="label {{= select == tags[i] ? 'label-info':''}}">{{= tags[i] }}</a>
	{{ } }}
	<a href="#" class="label label-important">x</a>
  </div>

</script>

<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/assignment.js"></script>
