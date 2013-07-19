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
			<h4>Report Assignments</h4>
            <table class="table table-condensed">
            <thead>
              <tr>
                <th>Report</th>
                <th>User</th>
                <th>Assigned</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Expires</th>
				<th><a href="#add" class="btn btn-mini btn-primary pull-right">New&nbsp;<i class="icon icon-check"></i></a></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(assignment) { }}
              <tr>
                <td>{{= assignment.get('form_title') }}</td>
                <td>{{= assignment.get('user_name') }}</td>
                <td>{{= moment(assignment.get('date_assigned').date).format('L') }}</td>
                <td>{{= assignment.get('schedule') }}</td>
                <td>{{= assignment.get('status') }}</td>
                <td>{{= moment(assignment.get('date_expires').date).format('L') }}</td>
                <td><span class="pull-right"><button data-for="{{= assignment.get('id') }}" class="btn btn-mini btn-info">Edit <i class="icon-edit icon-white"></i></button></span></td>
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





<script id="assignmentForm" type="text/template">
    <div class="">
        <div class="span6">
            <legend>Report Assignment</legend>
            <div class="control-group">
                <label>Report Form</label>
                <select id="reportList" class="span12">
                <option value="">Select Reporting Form</option>
                </select>
            </div>
            <div class="control-group">
                <label>Assign User(s)</label>
                <select id="userList" name="reportForms" class="span12" multiple="multiple" size='5'>
                </select>
            </div>
        </div>
        <div class="span6">
            <legend>Schedule</legend>
            <div class="control-group">
                <label class="control-label">Start Assignment</label>
                <div class="input-append date" data-date-format="MM d yyyy">
                    <input id="startDate" class="span11" type="text" value="{{= moment(filters.startDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
            <div class="control-group">
                <label>Schedule</label>
                <select id="schedule" class="span12">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <div class="control-group">
                <label class="control-label">Expires</label>
                <div class="input-append date" data-date-format="MM d yyyy">
                    <input id="expireDate" class="span11" type="text" value="{{= moment(filters.endDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="clearfix">
            <div class="control-group pull-right">
            <button id='assignSubmit' class="btn btn-mini btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
            </div>
        </div>
    </div>
	<!-- Bit of a Hack... but it works! -->
	<script type="text/javascript">

	  // initialize filter control
	  $('.input-append.date').datepicker({todayBtn: true, autoclose: true, forceParse: true}).on('changeDate', function(e){
		// set filter dates
		filters.endDate = moment($('#endDate').val());
		filters.startDate = moment($('#startDate').val());
		if(filters.navigate.on="custom") filters.navigate.index = filters.endDate.diff(filters.startDate, 'days');

	  });
    </script>

</script>

<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/assignment.js"></script>
