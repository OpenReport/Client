<!--


-->
<div class="container-fluid">
	<div id="dashboardContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>


<script id="details" type="text/template">
    <div class="span4 well">
		<h4>Total Reports: {{= stats.formCount }}
    </div>
    <div class="span4 well">
		<h4>Total Records: {{= stats.recordCount }}
    </div>
    <div class="span4 well">
		<h4>Date: {{= moment(localTime).format('LL') }}<h4>
    </div>
</script>



<script type="text/javascript" src="/views/templates/page/scripts/dashboard.js"></script>
