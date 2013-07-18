<!--


-->
<div class="container-fluid">
	<div id="userContext" class="row-fluid well"></div>
    <!-- Modals -->
    <div id="dialog"></div>
</div>

<script id="accountForm" type="text/template">
    <div class="form-horizontal" id="postEvent">
    <fieldset class="span12">
        <legend>Account Info</legend>
        <div class="control-group">
            <label class="control-label">Account Name</label>
            <div class="controls">
                <input type="text" value="{{= name }}"class="span12" id="acctname" name="acctname" placeholder="Enter a title for your task" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Admin Email</label>
            <div class="controls">
                <input type="text" value="{{= admin_email }}"class="span12" id="admin_email" name="admin_email" placeholder="Enter a title for your task" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Map Api Key</label>
            <div class="controls">
                <input type="text" value="{{= map_api_key }}"class="span12" id="map_api_key" name="map_api_key" placeholder="Enter a title for your task" >
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
    </fieldset>
    </div>
</script>
<script id="accountInfo" type="text/template">

	<legend>Account Limits</legend>
	<ul class="unstyled">
	<li><strong>Records:&nbsp;</strong>{{= account_limits.records == 0 ? "unlimited" : account_limits.records + ' K' }}</li>
	<li><strong>Media:&nbsp;</strong>{{= account_limits.media == 0 ? "unlimited" : account_limits.media + ' GB' }}</li>
	<li><strong>Forms:&nbsp;</strong>{{= account_limits.forms == 0 ? "unlimited" : account_limits.forms }}</li>
	<li><strong>User:&nbsp;</strong>{{= account_limits.users == 0 ? "unlimited" : account_limits.users }}</li>
	</ul>
</script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/accountModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/account.js"></script>
