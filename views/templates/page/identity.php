<!--


-->

<div id="identityContext" class="row-fluid well well-small"></div>
<!-- Modals -->
<div id="dialog"></div>

<!-- Templates -->
<script id="identities" type="text/template">
  <div class="span12">
		<h4>Identity List</h4>
    <table class="table table-condensed">
      <thead>
        <tr>
        <th>Identity</th>
        <th>Field</th>
        <th>Description</th>
        <th><button class="add btn btn-mini btn-primary pull-right">New&nbsp;<i class="icon icon-flag"></i></button></th>
        </tr>
      </thead>
      <tbody>
      {{ _(records).each(function(identity) { }}
        <tr>
        <td><a class="identity" id="{{= identity.get('id') }}" href="#" ><i class="icon-info-sign icon-white"></i>&nbsp;{{= identity.get('identity') }}</a></td>
        <td>{{= identity.get('identity_name') }}</td>
        <td>{{= identity.get('description') }}</td>
        <td><span class="pull-right"><button data-for="{{= identity.get('id') }}" class="edit btn btn-mini btn-info">Edit <i class="icon-edit icon-white"></i></button></span></td>
        </tr>
      {{ }); }}
      </tbody>
    </table>

    <div class="btn-group btn-group pull-right">
    <button id="prevPage" class="btn btn-mini" type="button"><i class="icon-chevron-up"></i></button>
    <button class="btn btn-mini">Page</button>
    <button id="nextPage" class="btn btn-mini" type="button"><i class="icon-chevron-down"></i></button>
    </div>
  </div>
</script>


<script id="identityForm" type="text/template">
<div class="modal" id="formModal">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">✕</button>
  <h3>Record Identities</h3>
  </div>
  <div class="modal-body" style="text-align:left;">
    <div class="row-fluid">
      <div class="span12">
        <div class="well">
          <legend>Identity</legend>
          <div class="control-group">
            <label class="control-label">Record Field Name</label>
            <div class="controls">
                <input type="text" value="{{= identity_name }}"class="span12" id="identity_name" name="identity_name" readonly >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Idenity Label</label>
            <div class="controls">
                <input type="text" value="{{= identity }}"class="span12" id="identity" name="identity" placeholder="" >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Description</label>
            <div class="controls">
              <textarea class="span12" id="description" name="description" placeholder="" >{{= description }}</textarea>
            </div>
          </div>
        </div>
        <div class="control-group pull-right">
          <button id='identitySubmit' data-for="{{= id }}" class="btn btn-mini btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>

</script>




<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/identityModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/identity.js"></script>
