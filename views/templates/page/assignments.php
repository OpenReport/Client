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
                <th>User</th>
                <th>Form</th>
                <th>Date Assigned</th>
				<th></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(assignment) { }}
              <tr>
                <td><a class="assignment" id="{{= assignment.get('id') }}"><i class="icon-info icon-white"></i>&nbsp;{{= assignment.get('user') }}</a></td>
                <td>{{= assignment.get('form_title') }}</td>
				<td>{{= moment(assignment.get('date_assigned').date).format('L') }}</td>
                <td><span class="pull-right"><a class="" href="#edit/{{= assignment.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>
        </div>
</script>


<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/assignment.js"></script>
