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
            <label class="control-label">Name</label>
            <div class="controls">
                <input type="text" value="{{= name }}"class="span12" id="acctname" name="acctname" placeholder="Enter a title for your task" >
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-right"><button id="close" class="btn">Cancel</button> <button class="btn btn-primary" id="submit">Save changes</button> </span>
        </div>
    </fieldset>
    </div>
</script>


<script type="text/javascript" src="/views/templates/page/scripts/shared/accountModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/account.js"></script>
