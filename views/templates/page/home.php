<!--


-->

<div id="dashboardContext" class="well well-small"></div>
<!-- Modals -->
<div id="dialog"></div>


<script id="details" type="text/template">
	<div class="row-fluid">
    <div class="span4 well">
		<h4>Total Reports: {{= stats.formCount }}</h4>
    </div>
    <div class="span4 well">
		<h4>Total Records: {{= stats.recordCount }}</h4>
    </div>
    <div class="span4 well">
		<h4>Total Users: {{= stats.totalUsers }}</h4>
    </div>

	</div>
	<div  class="row-fluid">
	<div class="span12 well">
		<h4>Recent Submissions</h4>
		<table class="table table-condensed">
		<thead>
		  <tr>
			<th>Report</th>
			<th>By</th>
			<th>Location</th>
			<th>Date</th>
		  </tr>
		</thead>
		<tbody>

		{{ if(stats.recentReports.length > 0) _(stats.recentReports).each(function(report) { }}
		  <tr>
			<td><a class="form" href="/reports#details/{{= report.id}}"><i class="icon-list-alt icon-white"></i>&nbsp;{{= report.form_title }} {{= report.identity }}</a></td>
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

<script id="info" type="text/template">
	<h4>Quick Links</h4>
	<ul class="nav nav-pills nav-stacked">
	<li class="active"><a href="/forms#add"><i class="icon icon-list-alt"></i>&nbsp;New Report Form</a></li>
	<li class="active"><a href="/users#add"><i class="icon icon-user"></i>&nbsp;New Report User</a></li>
	<li class="active"><a href="/assignment#add"><i class="icon icon-check"></i>&nbsp;New Report Assignment</a></li>
	</ul>
	<ul class="unstyled">
	<li><strong>Top Users</strong></li>
	<ol>
	{{ _(stats.topUsers).each(function(topUser) { }}
		<li>{{= topUser.user }} <span class="badge badge-info pull-right">{{= topUser.user_count }}</span></li>
	{{ }); }}
	</ol>
	<li><strong>Top Reports</strong></li>

	<ol>
	{{ _(stats.topReports).each(function(topReport) { }}
		<li><a href="reports#records/{{= topReport.form_id }}">{{= topReport.form_title }}</a><span class="badge badge-info pull-right">{{= topReport.form_count }}</span></li>
	{{ }); }}
	</ol>
	</ul>
	<ul class="nav nav-pills nav-stacked">
	<li class="active"><a href="<?php echo $account['mobile_url'] ?>" target="top"><i class="icon icon-mobile-phone"></i>&nbsp;View Mobile</a></li>
	</ul>
</script>

<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/dashboard.js"></script>
