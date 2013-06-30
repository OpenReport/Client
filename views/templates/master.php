<!DOCTYPE html>
<html class="no-js">
<head>
  <?php
    $pageMeta = $this->getData('meta');
    $account = $this->getData('account');
  ?>
  <meta charset="utf-8">
  <title>OpenReport</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/datetimepicker.css">
  <link rel="stylesheet" href="/assets/css/main.css">

  <script src="/assets/js/vendor/jquery-1.8.3.min.js"></script>
  <script src="/assets/js/vendor/jquery-ui.min.js"></script>
  <script src="/assets/js/vendor/underscore-min.js"></script>
  <script src="/assets/js/vendor/backbone-min.js"></script>

  <script src="/assets/js/vendor/bootstrap.min.js"></script>
  <script src="/assets/js/vendor/bootstrap-datetimepicker.min.js"></script>
  <script src="/assets/js/vendor/moment.min.js"></script>
  <script>
      // enable tooltips
      $(".tip").tooltip();
      var localTime = moment();
      var navTime = localTime;
      var timeZone = "PDT";
      var curMonth = localTime.format('M');
      var curYear = localTime.format('YYYY');
      var apiKey = "<?php echo $account['api_key'] ?>";  // this is a global account key

$(function(){ // document ready

  var stickyTop = $('.sticky').offset().top; // returns number

  $(window).scroll(function(){ // scroll event

    var windowTop = $(window).scrollTop(); // returns number

    if (stickyTop < windowTop) {
      $('.sticky').css({ position: 'fixed', top: 0 });
    }
    else {
      $('.sticky').css('position','static');
    }

  });

});
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
                <span class="brand">Open Report</span>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li class=""><a href="/"><i class="icon-home icon-black"></i> Dashboard</a></li>
                        <li class=""><a href="/reports"><i class="icon-bar-chart icon-black"></i> Reports</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                               class="icon-edit icon-black"></i>
                                Manage <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/forms">Reporting Forms</a></li>
                                <li><a href="/users">User Accounts</a></li>
                            </ul>
                        </li>

                    </ul>
                    <ul class="nav pull-right settings">
                        <li><a href="/account/settings" class="tip icon logout" data-original-title="Settings"
                               data-placement="bottom"><i class="icon-large icon-cog"></i></a></li>
                        <li class="divider-vertical"></li>
                        <li><a href="/a/logout" class="tip icon logout" data-original-title="Logout" data-placement="bottom"><i
                           class="icon-large icon-off"></i></a></li>
                    </ul>
                    <ul class="nav pull-right settings">
                        <li class="divider-vertical"></li>
                    </ul>
                    <p class="navbar-text pull-right">
                        Welcome <strong><?php echo $this->user(); ?></strong>
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

    <div class="span3 well sticky"></div>
  </div>

</div> <!--/container-fluid-->

<hr>

<footer align="center">
    <p>Copyright &copy; 2013 <strong>The Austin Conner Group</strong></p>
</footer>

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
</body>
</html>
