
<div id="reportContext" class="row-fluid well well-small"></div>

<script id="reportingForms" type="text/template">
	<div class="span12">
		<h4>Reports<br/>
			<small>showing {{= forms.length}} of {{= count }}</small></h4>
		<table class="table table-condensed">
		<thead>
		  <tr>
			<th>Title</th>
			<th>Description</th>
			<th>Tag</th>
			<th>Date</th>
		  </tr>
		</thead>
		<tbody>
		{{ _(forms).each(function(form) { if(form.attributes.is_published === 1) }}
		  <tr>
			<td><a class="form" id="{{= form.attributes.id }}" href="#records/{{= form.attributes.id }}"><i class="icon-list icon-white"></i>&nbsp;{{= form.attributes.title }}</a></td>
			<td>{{= form.attributes.description }}</td>
			<td>{{= form.attributes.tags }}</td>
			<td>{{= moment(form.attributes.date_modified.date).format('L') }}</td>
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
<script id="reportRecords" type="text/template">
	<div class="span12">
	<!-- Display records -->
		<div class="btn-group pull-right" style="margin: 9px 0 5px;">
		<button id="navPrev" class="btn btn-mini">«</button><button id="navNext" class="btn btn-mini">»</button></li>

		</div>
		<h4>{{= report.title}} Reports for {{= filters.startDate.format('LL') }} to {{= filters.endDate.format('LL') }}<br/>
			<small>showing {{= records.length}} of {{= count }} records</small></h4>
		{{ if(!records.length){ }}<p> NO RECORDS FOUND </p> {{ } }}
		<table class="table sortable">
			<thead>
			  <!-- columns -->
			  <tr>
			  <th><button id='export' class='btn btn-mini'>Export</button></th>
			  {{_(headers).each(function(col){ if(col.name !== 'id') }}
				<th class="sortable">{{= col.name }} </th>
			  {{ }); }}
			  </tr>
			</thead>
			<tbody>
			  <!-- records (rows) -->
			  {{_(records).each(function(row){ }}
				<tr>
				<td><span class="pull-left"><a class="" href="#details/{{= row['id'] }}"><i class="icon-list-alt icon-white"></i>&nbsp;View</a></span></td>
				{{_(headers).each(function(col){ if(col.name !== 'id')  }}
				  <td>{{= formatColumnData(row[col.name], col.type) }} </td>
				{{ }); }}

				</tr>
			  {{ }); }}
			</tbody>
		</table>
		<div class="btn-group btn-group pull-right">
		<button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
		<button class="btn btn-mini">Page</button>
		<button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
		</div>
	{{_(headers).each(function(col){  }}
	  {{ if('object' === typeof col.values) { }}
	  <div class="span3">
	  <h5>Key: {{= col.name }}</h5>
	  <ul class="unstyled">
		{{ _(col.values).each(function(opt) { }}
				<li>{{= opt.value }} = {{= opt.label }}</li>
			{{ }); }}
		{{ } }}
	  </ul>
	  </div>
	{{ }); }}

	</div>
</script>
<script id="recordDetails" type="text/template">
	<div class="row-fluid">
		<div class="span12">
			<h4>{{= title }} Report <small>{{= moment(record.record_date.date).format('ll') }}</small></h4>

			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#record" data-toggle="tab">Record</a>
				</li>
				<li>
					<a href="#map" data-toggle="tab">Map</a>
				</li>
				<li>
					<a href="#media" data-toggle="tab">Photos</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="record">
					<table class="table table-bordered table-striped">
						{{_(headers[0].fields).each(function(col){ if(col !== 'id') }}
						<tr><td style="width:30%;"><strong>{{= col.display }}:</strong></td><td> {{= formatReportData(record.meta[col.name], col.type, col.values ) }}</td></tr>
						{{ }); }}
					</table>
					<table class="table table-bordered" style="margin-top: 8px;">
						{{ moment().zone(record.record_time_offset) }}
						<tr><td><strong>Submited:</strong></td><td> {{= moment(record.record_date.date).format('lll') }}</td><td><strong>Timezone:</strong></td><td> {{= record.record_date.timezone }}</td></tr>
						<tr><td><strong>User:</strong></td><td>{{= record.user }}</td><td><strong>Lon/Lat:</strong></td><td> {{= record.lon }}/{{= record.lat }}</td></tr>
					</table>

				</div>
				<div class="tab-pane" id="map">
				<div id="map_canvas" class="thumbnail" style="height:480px;"></div>
				</div>
				<div class="tab-pane" id="media">

					{{_(headers[0].fields).each(function(col){ if(col.type === 'media:image') }}
						{{= formatMedia(record.meta[col.name]) }}
					{{ }); }}

				</div>
			</div>
		</div>
	</div>
</script>
<script id="relatedReports" type="text/template">
	<div class="span12 well">
		<div class="btn-group pull-right" style="margin: 9px 0 5px;">
		<button id="navPrev" class="btn btn-mini">«</button>><button id="navNext" class="btn btn-mini">»</button></li>
		</div>
		<h4>{{=identity}} Reports for {{= filters.startDate.format('LL') }} to {{= filters.endDate.format('LL') }}<br/>
			<small>showing {{= relatedReports.length}} of {{= count }} records</small></h4>
		{{ if(!relatedReports.length){ }}<p> NO REPORTS FOUND </p> {{ } }}
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

		{{ _(relatedReports).each(function(record) { }}
		  <tr>
			<td><a class="form" href="/reports#details/{{= record.attributes.id}}"><i class="icon-list-alt icon-white"></i>&nbsp;{{= record.attributes.form_title }}</a></td>
			<td>{{= record.attributes.user }}</td>
			<td>{{= record.attributes.lon }}/{{= record.attributes.lat }}</td>
			<td>{{= moment(record.attributes.record_date.date).format('L') }}</td>
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
<script id="infoDetails" type="text/template">
  <div class="control-group ">
	<h4><a href="#records/{{= record.form_id }}">{{= title}} Report</a></h4>
	<p><strong>Report ID:</strong> {{= form_name }}</p>
	<p><strong>Report Version:</strong> {{= record.report_version }}</p>
	<p><strong>Identity:</strong><a href="reports#related/{{= record.identity }}"> {{= record.identity }}</a></p>
	<h5>Report Fields</h5>
	<ol>
	{{ _(columns[0].fields).each(function(col){ if(col !== 'id') }}
		<li>{{= col.name }}</li>
	{{ }); }}
	</ol>
  </div>

</script>


<script id="info" type="text/template">
  <div id="form-tags" class="control-group">
    <h4>Filter by Form Tag</h4>
	<a href="#" class="label label-important">x</a>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#tag/{{= tags[i] }}" class="label {{= select == tags[i] ? 'label-info':''}}">{{= tags[i] }}</a>
	{{ } }}

  </div>
</script>

<script id="filters" type="text/template">

  <div class="control-group">
    <h4>Quick Filters</h4>
    <div class="btn-group" style="margin: 9px 0 5px;">
      <button id="monthly" class="filters btn btn-mini">Monthly</button>
      <button id="every7" class="filters btn btn-mini">7 Day</button>
      <button id="every30" class="filters btn btn-mini">30 Day</button>
      <button id="every60" class="filters btn btn-mini">60 Day</button>
      <button id="every90" class="filters btn btn-mini">90 Day</button>
      <button id="custom" class="filters btn btn-mini">Custom</button>
    </div>
	<h4>Custom Filter</h4>
    <div class="controls">
      <label class="control-label">Start Date</label>
      <div class="input-append date" data-date-format="MM d yyyy">
          <input id="startDate" class="span11" type="text" value="{{= moment(filters.startDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
      <label class="control-label">End Date</label>
      <div class="input-append date" data-date-format="MM d yyyy">
          <input id="endDate" class="span11" type="text" value="{{= moment(filters.endDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
    </div>
	<div class="clearfix">
      <button id="applyRange" class="filters btn btn-mini pull-right">Apply Date Range</button>
	</div>
	  <hr/>

  </div>


	<!-- Bit of a Hack... but it works! -->
	<script type="text/javascript">
	  $(filters.selected).addClass('btn-info');
	  // initialize filter control
	  $('.input-append.date').datepicker({todayBtn: true, autoclose: true, forceParse: true}).on('changeDate', function(e){
			// set filter dates
			filters.endDate = moment($('#endDate').val());
			filters.startDate = moment($('#startDate').val());
			if(filters.navigate.on="custom") filters.navigate.index = filters.endDate.diff(filters.startDate, 'days');

	  });
	  // assign btn events
	  $('#applyRange').bind('click', function( event ){
		filters.navigate.on = 'days';
		filters.navigate.index = filters.endDate.diff(filters.startDate, 'days');
		resetDates($('#custom'));
	  });
			$('#custom').bind('click', function( event ){
			filters.selected = '#custom'
			filters.navigate.on = 'days';
			filters.navigate.index = filters.endDate.diff(filters.startDate, 'days');
			resetDates($('#custom'));
	  });
	  $('#monthly').bind('click', function( event ){
			filters.selected = '#monthly'
			filters.startDate = moment().startOf('month');
			filters.endDate = moment().endOf('month');
			filters.navigate.on = 'months';
			filters.navigate.index = 1;
			resetDates($('#monthly'));
	  });
	  $('#every7').bind('click', function( event ){
			filters.selected = '#every7'
			filters.startDate = moment().subtract('days', 7);
			filters.endDate = moment();
			filters.navigate.on = 'days';
			filters.navigate.index = 7;
			resetDates($('#every7'));
			return true;
	  });
	  $('#every30').bind('click', function( event ){
			filters.selected = '#every30'
			filters.startDate = moment().subtract('days', 30);
			filters.endDate = moment();
			filters.navigate.on = 'days';
			filters.navigate.index = 30;
			resetDates($('#every30'));
		return true;
	  });
	  $('#every60').bind('click', function( event ){
			filters.selected = '#every60'
			filters.startDate = moment().subtract('days', 60);
			filters.endDate = moment();
			filters.navigate.on = 'days';
			filters.navigate.index = 60;
			resetDates($('#every60'));
	  });
	  $('#every90').bind('click', function( event ){
			filters.selected = '#every90'
			filters.startDate = moment().subtract('days', 90);
			filters.endDate = moment();
			filters.navigate.on = 'days';
			filters.navigate.index = 90;
			resetDates($('#every90'));
	  });

	  function resetDates(sel){
			$('.filters').removeClass('btn-info');
			$('#startDate').val(filters.startDate.format('LL'));
			$('#endDate').val(filters.endDate.format('LL'));
			sel.addClass('btn-info');
			return true;
	  }

		$('#monthly').trigger('click');

	</script>

</script>

<script type="text/javascript" src="/views/templates/page/scripts/lib/openreport.export.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/recordModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/reports.js"></script>

<!-- need async load here -->
<script type="text/javascript">
	$(document).ready(function() {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initMaps';
		document.body.appendChild(script);
	});
	function initMaps(){
		app.maps = google.maps;
	}

</script>
