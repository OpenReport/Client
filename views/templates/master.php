<!DOCTYPE html>
<html class="no-js">
<head>
  <?php
    $account = $this->getData('account');
  ?>
  <meta charset="utf-8">
  <title>OpenReport</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/datepicker.css">
  <link rel="stylesheet" href="/assets/css/main.css">

  <script src="/assets/js/vendor/jquery-1.8.3.min.js"></script>
  <script src="/assets/js/vendor/jquery-ui.min.js"></script>
  <script src="/assets/js/vendor/underscore-min.js"></script>
  <script src="/assets/js/vendor/backbone-min.js"></script>

  <script src="/assets/js/vendor/backbone.paginator.min.js"></script>
  <script src="/assets/js/vendor/bootstrap.min.js"></script>
  <script src="/assets/js/vendor/bootstrap-datepicker.js"></script>
  <script src="/assets/js/vendor/moment.min.js"></script>
  <script>
      // enable tooltips
      $(".tip").tooltip();
      // set filter date windows
      // default navigation month to month
      var filters = {
        'navigate':{'on':'month','index':1},
        'startDate':moment().startOf('month'),
        'endDate':moment().endOf('month'),
        'selected':'#monthly'
      };

      var paging = {'items':10};

      var localTime = moment();
      var navTime = localTime;
      var curMonth = localTime.format('M');
      var curYear = localTime.format('YYYY');
      // set account data
      var apiKey = "<?php echo $account['api_key'] ?>";  // this is a global account key
      var acctNo = "<?php echo $account['id'] ?>";  // this is a global account No
      var map_api_key = "<?php echo $account['map_api_key'] ?>";  // this is a global map key
  </script>

</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <ul class="nav">
                  <li><a onclick="window.history.back();"><i class="icon-large icon-arrow-left"></i></a></li>
                  <li class="divider-vertical"></li>
                </ul>
                <span class="brand" style="color: rgb(42, 35, 231);"><i class="icon-bar-chart icon-black" style="font-size: 20px;color: rgb(238, 126, 23);"></i>&nbsp;OpenReport</span>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li class=""><a href="/"><i class="icon-home icon-black"></i> Dashboard</a></li>
                        <li class=""><a href="/reports"><i class="icon-bar-chart icon-black"></i> Reports</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-edit icon-black"></i>&nbsp;Manage&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/assignment"><i class="icon icon-check"></i>&nbsp;Report Assignments</a></li>
                                <li><a href="/distribution"><i class="icon icon-share"></i>&nbsp;Report Distribution</a></li>
                                <li><a href="/forms"><i class="icon icon-list-alt"></i>&nbsp;Reporting Forms</a></li>
                                <li><a href="/users"><i class="icon icon-user"></i>&nbsp;User Accounts</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav pull-right settings">
                        <li><a href="/account/settings" class="tip icon logout" data-original-title="Settings"
                               data-placement="bottom"><i class="icon-large icon-cog"></i></a></li>
                        <li class="divider-vertical"></li>
                        <li><a href="/a/logout" class="tip icon logout" data-original-title="Logout" data-placement="bottom"><i class="icon-large icon-off"></i></a></li>
                    </ul>
                    <ul class="nav pull-right settings">
                        <li class="divider-vertical"></li>
                    </ul>
                    <p class="navbar-text pull-right">
                        <strong><?php echo $account['name'] ?>: <?php echo $this->user(); ?></strong>
                        <span class="" id="localDate"><span>
                    </p>
                    <ul class="nav pull-right settings">
                        <li class="divider-vertical"></li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
</div>


<div class="container-fluid content">
  <div class="row-fluid">
    <div class="span9">
      <?php $this->partial($childView, $this->getData())?>
    </div>

    <div class="span3 well well-small">

      <div id="infoBox"></div>
    </div>
  </div>

</div> <!--/container-fluid-->

<hr>

<footer>
    <p style="margin: auto;text-align: center;">OpenReport v1.0 Copyright &copy; 2013 <strong>The Austin Conner Group</strong></p>
</footer>


<!-- Global Templates -->
<script id="errorModal" type="text/template">
<div class="modal" id="errorModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">✕</button>
		<h3>ERROR!</h3>
	</div>
	<div class="modal-body" style="text-align:left;">
		<div class="row-fluid">
          <div class="span12">
              <div class="" id="errorText">
                <ul>
                <li>{{= caption }}</li>
                {{ errors.forEach(function(value, index) { }}
                <li>{{= value }}</li>
                {{ }) }}
                </ul>
              </div>
              <div class="pull-right">
              <button type="button" class="btn btn-primary" data-dismiss="modal">OK<i class="icon-minus-sign icon-white"></i></button>
              </div>
          </div>
		</div>
	</div>
</div>
</script>


<script type="text/javascript">


  /**
   * Helper: hyphenFormat()
   *
   * Replaces whitespaces with hyphens
   *
   */
  function hyphenFormat(propertyName)
  {
    return trim(propertyName).replace(/[\s]+/g, '-');
  }
  /**
   * Helper: underscoreFormat()
   *
   * Replaces whitespaces with underscores
   *
   */
  function underscoreFormat(propertyName)
  {
    return trim(propertyName).replace(/[\s]+/g, '_');
  }
  /**
   * Helper: trim()
   *
   * Trims whitespace from leading and trailing ends of a string
   *
   */
  function trim(property){
     return property.replace(/^\s+|\s+$/g, '');
  }


  /**
   *
   * Set Local Time
   */
  $(document).ready(function(){
    $('#localDate').append(localTime.format('LL'));
  });

</script>


</body>
</html>
