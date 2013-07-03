<!--


-->
<div class="container-fluid">
	<div id="dashboardContext" class=""></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>


<script id="details" type="text/template">
	<div class="row-fluid">
    <div class="span4 well">
		<h4>Total Reports: {{= stats.formCount }}
    </div>
    <div class="span4 well">
		<h4>Total Records: {{= stats.recordCount }}
    </div>
    <div class="span4 well">
		<h4>Date: {{= moment(localTime).format('LL') }}<h4>
    </div>
	</div>
	<div  class="row-fluid">
	<div class="span12 well">
		<table class="table">
		<thead>
		  <tr>
			<th colspan="5"><h3>Recent Reports</h3></th>
		  </tr>
		  <tr>
			<th>Title</th>
			<th>Submitted By</th>
			<th>Location</th>
			<th>Date</th>
		  </tr>
		</thead>
		<tbody>

		{{ console.log(stats) }}

		{{ _(stats.recentReports).each(function(report) { }}
		  <tr>
			<td><a class="form" href="/reports#records/{{= report.form_id}}"><i class="icon-info-sign icon-white"></i>&nbsp;{{= report.form_title }}</a></td>
			<td>{{= report.user }}</td>
			<td>{{= report.lon }}/{{= report.lat }}</td>
			<td>{{= moment(report.record_date.date).format('L') }}</td>
		  </tr>

		{{ }); }}
		</tbody>
		</table>
	</div>
	</div>
</script>



<script type="text/javascript" src="/views/templates/page/scripts/dashboard.js"></script>
