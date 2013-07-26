<!--


-->

<div id="assignmentContext" class="row-fluid well well-small"></div>
<!-- Modals -->
<div id="dialog"></div>

<!-- Templates -->
<script id="assignments" type="text/template">
        <div class="span12">
			<h4>Report Assignments<br/>
			<small>showing {{= records.length}} of {{= count }} assignments</small></h4>
            <table class="table table-condensed">
            <thead>
              <tr>
                <th>Report</th>
                <th>User</th>
                <th>Assigned</th>
                <th>Schedule</th>
                <th>Expires</th>
				<th><a href="#add" class="btn btn-mini btn-primary pull-right">New&nbsp;<i class="icon icon-check"></i></a></li></th>
              </tr>
            </thead>
            <tbody>
            {{ _(records).each(function(assignment) { }}
              <tr>
                <td>{{= assignment.get('form_title') }} {{= assignment.get('identity') }}</td>
                <td>{{= assignment.get('user_name') }}</td>
                <td>{{= moment(assignment.get('date_assigned').date).format('L') }}</td>
                <td>{{= assignment.get('schedule') }}</td>
                <td>{{= moment(assignment.get('date_expires').date).format('L') }}</td>
                <td><span class="pull-right"><button data-for="{{= assignment.get('id') }}" class="edit btn btn-mini btn-info">Edit <i class="icon-edit icon-white"></i></button></span></td>
              </tr>

            {{ }); }}
            </tbody>
            </table>

			<div class="btn-group btn-group pull-right">
			<button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
			<button class="btn btn-mini">Page</button>
			<button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
			</div>
        </div>
</script>

<script id="scheduleDialog" type="text/template">
	<div class="modal" id="formModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✕</button>
			<h3>Assignment Schedule</h3>
		</div>
		<div class="modal-body" style="text-align:left;">
			<div class="row-fluid">
				<div class="span12">
				<div class="well">
					<legend>Schedule</legend>
					<div class="control-group">
					  <div class='row-fluid'>
					  <div class="span6">
						<label>Schedule</label>
						<select id="schedule" class="span12">
							<option value="daily" {{= schedule =='daily' ? 'selected':'' }}>Daily</option>
							<option value="weekly" {{= schedule =='weekly' ? 'selected':'' }}>Weekly</option>
							<option value="monthly" {{= schedule =='monthly' ? 'selected':'' }}>Monthly</option>
						</select>
					  </div>
					  <div class="span6">
						<label>Repeat</label>
						<input type="text" id="repeat_schedule" value="{{= repeat_schedule }}" class="span12">
					  </div>
					  </div>
					</div>
					<div class="control-group">
						<label class="control-label">Start Assignment</label>
						<div class="input-append date" data-date-format="MM d yyyy">
							<input id="date_assigned" class="span11" type="text" value="{{= moment(date_assigned.date).format('LL') }}"><span class="add-on"><i class="icon-calendar"></i></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Expires</label>
						<div class="input-append date" data-date-format="MM d yyyy">
							<input id="date_expires" class="span11" type="text" value="{{= moment(date_expires.date).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
						</div>
					</div>
				</div>
				</div>
				<div class="control-group pull-right">
				<button id='assignSubmit' data-for="{{= id }}" class="btn btn-mini btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
				</div>
			</div>

		</div>
	</div>
		<!-- Bit of a Hack... but it works! -->
		<script type="text/javascript">

			// initialize date controls
			$('.input-append.date').datepicker({todayBtn: true, autoclose: true, forceParse: true}).on('changeDate', function(e){
				setExpire($('#date_assigned').val());
			});
			$('#schedule, #repeat_schedule').on('change', function(e){
				// update date_expires
				setExpire($('#date_assigned').val());

			});

		</script>
</script>



<script id="assignmentForm" type="text/template">
    <div class="row-fluid">


        <div class="span6">
            <legend>Assignment</legend>
            <div class="control-group">
                <label>Assign to User</label>
                <select id="userList" name="reportForms" class="span12">
                <option value="">Select A User</option>
                </select>
            </div>
            <div class="control-group">
                <label>Report Form</label>
                <select id="reportList" class="span12">
								<option data-identity="" value="">Select A Report Form</option>
                </select>
            </div>
            <div class="identity control-group" style="display:none;">
                <label>Report For (select all that apply)</label>
                <select id="identityList" class="span12" multiple>
                </select>
            </div>
						</div>


<div class="span6">
            <legend>Schedule</legend>
            <div class="control-group">
			  <div class='row-fluid'>
			  <div class="span6">
                <label>Schedule</label>
                <select id="schedule" class="span12">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
			  </div>
			  <div class="span6">
				<label>Repeat</label>
            <input type="text" id="repeat_schedule" value="1" class="span12">
        </div>
			  </div>
			</div>
			<div class="control-group">
			  <div class='row-fluid'>
            <div class="span6">
                <label class="control-label">Start Assignment</label>
                <div class="input-append date" data-date-format="MM d yyyy">
                    <input id="date_assigned" class="span10" type="text" value="{{= moment().format('LL') }}"><span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
            <div class="span6">
                <label class="control-label">Expires</label>
                <div class="input-append date" data-date-format="MM d yyyy">
                    <input id="date_expires" class="span10" type="text" value="{{= moment().format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
						</div></div>
        </div>

    </div>
    <div class="row-fluid">
        <div class="clearfix">
            <div class="control-group pull-right">
            <button id='submit' class="btn btn-mini btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
            </div>
        </div>
    </div>
		<!-- Bit of a Hack... but it works! -->
		<script type="text/javascript">

			// initialize date controls
			$('.input-append.date').datepicker({todayBtn: true, autoclose: true, forceParse: true}).on('changeDate', function(e){
				setExpire($('#date_assigned').val());
			});
			$('#schedule, #repeat_schedule').on('change', function(e){
			// update date_expires
			setExpire($('#date_assigned').val());

			});

		</script>

</script>

<script id="filters" type="text/template">

  <div id="form-list" class="control-group">
    <h4>Filter by Report</h4>
	<a href="#" class="label label-important">x</a>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#forms/{{= tags[i].id }}" class="label {{= select == tags[i].id ? 'label-info':''}}">{{= tags[i].title }}</a>
	{{ } }}
  </div>

</script>

<script type="text/javascript">
			  // expire logic
	  function setExpire(date){
		var startDate = moment(date);
		var endDate = startDate;
		var cnt = $('#repeat_schedule').val();
		cnt = cnt > 1 ? cnt:1;
		switch($('#schedule').val()){
			case 'daily':
				endDate = startDate.add('days', cnt-1);
			break;
			case 'weekly':
				endDate = startDate.add('weeks', cnt-1);
			break;
			case 'monthly':
				endDate = startDate.add('months', cnt-1);
			break;
		}
		$('#date_expires').val(endDate.format('LL'));
	  }

</script>

<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/assignmentModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/assignment.js"></script>
