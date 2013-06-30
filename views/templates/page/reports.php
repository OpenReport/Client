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
		{{ _(records).each(function(form) { }}
		  <tr>
			<td><a class="form" id="{{= form.get('id') }}" href="#records/{{= form.get('id') }}"><i class="icon-info-sign icon-white"></i>&nbsp;{{= form.get('title') }}</a></td>
			<td>{{= form.get('description') }}</td>
			<td>{{= moment(form.get('date_created').date).format('L') }}</td>
			<td><span class="pull-right"><a class="" href="#form/edit/{{= form.get('id') }}">Edit <i class="icon-edit icon-white"></i></a></span></td>
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
			  {{_(headers).each(function(col){ }}
				<th class="sortable">{{= col }} </th>
			  {{ }); }}
			  </tr>
			</thead>
			<tbody>
			  <!-- records (rows) -->
			  {{_(records).each(function(row){ }}
				<tr>
				{{_(headers).each(function(col){ }}
				  <td>{{= row[col] }} </td>
				{{ }); }}
				</tr>
			  {{ }); }}
			</tbody>
		</table>
	</div>
</script>

<script type="text/javascript" src="/assets/js/lib/bootstrap-sortable.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/recordModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/reports.js"></script>
