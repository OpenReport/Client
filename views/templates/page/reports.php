<link rel="stylesheet" href="/assets/css/bootstrap-sortable.css">
<div class="container-fluid">
	<div id="reportContext" class="row-fluid well">	</div>
</div>

<script id="reportingForms" type="text/template">
	<div class="span12">
		<h4>Reports</h4>
		<table class="table">
		<thead>
		  <tr>
			<th>Title</th>
			<th>Description</th>
			<th>Date</th>
		  </tr>
		</thead>
		<tbody>
		{{ _(records).each(function(form) { if(form.get('is_published') === 1) }}
		  <tr>
			<td><a class="form" id="{{= form.get('id') }}" href="#records/{{= form.get('id') }}"><i class="icon-info-sign icon-white"></i>&nbsp;{{= form.get('title') }}</a></td>
			<td>{{= form.get('description') }}</td>
			<td>{{= moment(form.get('date_created').date).format('L') }}</td>
		  </tr>

		{{ }); }}
		</tbody>
		</table>
	</div>

</script>
<script id="reportRecords" type="text/template">
	<div class="span12">
	<!-- Display records -->
	<div class="btn-group pull-right" style="margin: 9px 0 5px;">
            <button id="navPrev" class="btn btn-mini">«</button>><button id="navNext" class="btn btn-mini">»</button></li>

            </div>
			<h4>Reports for {{= filters.startDate.format('LL') }} to {{= filters.endDate.format('LL') }}</h4>
		<table class="table sortable">
			<thead>
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
				<td><span class="pull-right"><a class="" href="#details/{{= row['id'] }}">Details <i class="icon-info-sign icon-white"></i></a></span></td>
				</tr>
			  {{ }); }}
			</tbody>
		</table>
	</div>
</script>
<script id="recordDetails" type="text/template">
	<div class="row-fluid">
		<div class="span12">
			<ul class="details">
				<li><strong>Data Collected: {{= headers.fieldset[0].legend }}</strong></li>
				<li><dl class="dl-horizontal">
				{{_(headers.fieldset[0].fields).each(function(col){ if(col !== 'id') }}
				<dt><strong>{{= col.display }}:</strong></dt><dd> {{= record.meta[col.name] }}</dd>
				{{ }); }}
				</dl></li>
				<li><strong>Submited:</strong> {{= moment(record.record_date.date).format('L') }}</li>
				<li><strong>User:</strong> {{= record.user }}</li>
				<li><strong>Lon/Lat:</strong> {{= record.lon }}/{{= record.lat }}</li>
			</ul>
			{{ _(headers.fieldset[0].fields).each(function(hrd) {console.log(hrd.name)}) }}
		</div>
	</div>
</script>



<script id="info" type="text/template">
	<h5>Info</h5>
</script>

<script id="filters" type="text/template">
  <div class="control-group ">
    <label class="control-label">Date Range</label>
    <div class="btn-group" style="margin: 9px 0 5px;">
      <button id="monthly" class="filters btn btn-primary btn-mini">Monthly</button>
      <button id="every30" class="filters btn btn-mini">30 Days</button>
      <button id="every60" class="filters btn btn-mini">60 Days</button>
      <button id="every90" class="filters btn btn-mini">90 Days</button>
      <button id="custom" class="btn btn-mini">Custom</button>
    </div>
    <hr/>
    <div class="controls">
      <label class="control-label">Start Date</label>
      <div class="input-append date" data-date-format="dd M yyyy">
          <input id="startDate" class="span11" type="text" value="{{= moment(filters.startDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
      <label class="control-label">End Date</label>
      <div class="input-append date" data-date-format="dd M yyyy">
          <input id="endDate" class="span11" type="text" value="{{= moment(filters.endDate).format('LL') }}" disabled><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
    </div>
      <button id="applyRange" class="filters btn btn-mini pull-right">Apply Date Range</button>
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
  // assign btn events
  $('#custom').bind('click', function( event ){
    filters.navigate.on = 'custom';
    filters.navigate.index = filters.endDate.diff(filters.startDate, 'days');
    resetDates();
  });
  $('#monthly').bind('click', function( event ){
    filters.startDate = moment().startOf('month');
    filters.endDate = moment().endOf('month');
    filters.navigate.on = 'months';
    filters.navigate.index = 1;
    resetDates();
  });
  $('#every30').bind('click', function( event ){
    filters.startDate = moment().subtract('days', 30);
    filters.endDate = moment();
    filters.navigate.on = 'days';
    filters.navigate.index = 30;
    resetDates();
    return true;
  });
  $('#every60').bind('click', function( event ){
    filters.startDate = moment().subtract('days', 60);
    filters.endDate = moment();
    filters.navigate.on = 'days';
    filters.navigate.index = 60;
    resetDates();
  });
  $('#every90').bind('click', function( event ){
    filters.startDate = moment().subtract('days', 90);
    filters.endDate = moment();
    filters.navigate.on = 'days';
    filters.navigate.index = 90;
    resetDates();
  });

  function resetDates(){
    $('#startDate').val(filters.startDate.format('LL'));
    $('#endDate').val(filters.endDate.format('LL'));
	return true;
  }

</script>

</script>

<script type="text/javascript" src="/assets/js/lib/bootstrap-sortable.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/formModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/recordModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/reports.js"></script>
