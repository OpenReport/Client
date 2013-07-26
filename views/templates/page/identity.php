<!--


-->
<style>
	img.capture-btn,
	img.capture-img{
		 border: solid 1px black;height: 64px; width:64px;
	margin: 2px;
	padding: 1px;
	}
	ul.capture-list {
	  list-style-type: none;
	  margin: 0;
	  padding: 0;
	  float: none;
	}
	ul.capture-list > li {
	 float: left;
	}
	span.error{
		float: left;
		clear: both;
	}
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
</style>
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
        <th>Description</th>
        <th>Column</th>
        <th><button class="add btn btn-mini btn-primary pull-right">New&nbsp;<i class="icon icon-flag"></i></button></th>
        </tr>
      </thead>
      <tbody>
      {{ _(records).each(function(identity) { }}
        <tr>
        <td><a class="identity" id="{{= identity.get('identity') }}" href="reports#related/{{= identity.get('identity') }}" ><i class="icon-info-sign icon-white"></i>&nbsp;{{= identity.get('identity') }}</a></td>
        <td>{{= identity.get('description') }}</td>
        <td>({{= identity.get('identity_name') }})</td>
        <td><span class="pull-right"><button data-for="{{= identity.get('id') }}" class="edit btn btn-mini btn-info">Edit <i class="icon-edit icon-white"></i></button></span></td>
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


<script id="identityEditDialog" type="text/template">
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
            <label class="control-label">Column Name</label>
            <div class="controls">
                <input type="text" value="{{= identity_name }}"class="span12" id="identity_name" name="identity_name" placeholder="Enter Column Name for Identity" readonly >
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Idenity Label</label>
            <div class="controls">
                <input type="text" value="{{= identity }}"class="span12" id="identity" name="identity" placeholder="Enter an Idenity Label" readonly>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Description</label>
            <div class="controls">
              <textarea class="span12" id="description" name="description" placeholder="Enter a Description" >{{= description }}</textarea>
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





<script id="idenityFilters" type=="text/template">
  <div id="form-tags" class="control-group">
    <h4>Filter by Columns</h4>
	<a href="#" class="label label-important">x</a>
	{{ for (var i = 0; i < tags.length; i++) { }}
		<a href="#filter/{{= tags[i] }}" class="label {{= select == tags[i] ? 'label-info':''}}">{{= tags[i] }}</a>
	{{ } }}
  </div>
</script>
<script type="text/javascript" src="/assets/js/vendor/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/app.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/shared/identityModel.js"></script>
<script type="text/javascript" src="/views/templates/page/scripts/identity.js"></script>
