<link rel="stylesheet" href="/assets/css/bootstrap-sortable.css">
<div class="container-fluid">
	<div id="reportContext" class="row-fluid well">	</div>
</div>


<script id="reportingForms" type="text/template">
	<div class="span12">
	<div>
		<span class="pull-left"><a id="prevMo" href="#">Prev</a></span>
		<span class="pull-right"><a id="nextMo" href="#">Next</a></span>
	</div>
		<table class="table">
		<thead>
		  <tr>
			<th colspan="5"><h3>Reports</h3></th>
		  </tr>
		  <tr>
			<th>Title</th>
			<th>Description</th>
			<th>Date</th>
		  </tr>
		</thead>
		<tbody>
		{{ _(records).each(function(form) { if(form.get('is_published') === 1) }}
		  <tr>
			<td><a class="form" id="{{= form.get('id') }}" href=""><i class="icon-info-sign icon-white"></i>&nbsp;{{= form.get('title') }}</a></td>
			<td>{{= form.get('description') }}</td>
			<td>{{= moment(form.get('date_created').date).format('L') }}</td>
			<td><span class="pull-right"><a class="" href="#records/{{= form.get('id') }}">View <i class="icon-bar-chart icon-white"></i></a></span></td>
		  </tr>

		{{ }); }}
		</tbody>
		</table>
	</div>

</script>
<script id="reportRecords" type="text/template">
	<div class="span12">
	<div><span class="pull-left"><a id="prevMo"href="#">Prev</a></span><span class="pull-right"><a id="nextMo" href="#">Next</a></span></div>
	<!-- Display records -->
		<table class="table sortable">
			<thead>
			  <tr>
				<th colspan="4"><h3>Reports for {{= navTime.format('MMMM YYYY') }}</h3></th>
			  </tr>
			  <!-- columns -->
			  <tr>
			  {{_(headers).each(function(col){ if(col !== 'id') }}
				<th class="sortable">{{= col }} </th>
			  {{ }); }}
			  <th></th>
			  </tr>
			</thead>
			<tbody>
			  <!-- records (rows) -->
			  {{_(records).each(function(row){ }}
				<tr>
				{{_(headers).each(function(col){ if(col !== 'id')  }}
				  <td>{{= row[col] }} </td>
				{{ }); }}
				<td><span class="pull-right"><a class="" href="#details/{{= row['id'] }}">View <i class="icon-bar-chart icon-white"></i></a></span></td>
				</tr>
			  {{ }); }}
			</tbody>
		</table>
	</div>
</script>
<script id="recordDetails" type="text/template">
	<div class="row-fluid">
		<div class="span10">
			<ul class="details">
				<li><strong>Submited:</strong> {{= moment(record.record_date.date).format('L') }}</li>
				<li><strong>User:</strong> {{= record.user }}</li>
				<li><strong>Lon/Lat:</strong> {{= record.lon }}/{{= record.lat }}</li>
				<li><strong>Data Collected:</strong></li>
				<ul>
				{{_(columns).each(function(col){ if(col !== 'id') }}
				<li><strong>{{= col }}:</strong> {{= record.meta[col] }}</li>
				{{ }); }}
				</ul>
			</ul>
		</div>
	</div>
</script>

<script type="text/javascript" src="/assets/js/lib/bootstrap-sortable.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/recordModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/reports.js"></script>
