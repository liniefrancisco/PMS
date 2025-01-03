<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title>AGC-PMS</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
	<script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/3D.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-buttons.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-chart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/multilevel-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-datepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/angular-clock.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/fileinput.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/checkboxes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/massautocomplete.theme.css">
</head>
<body ng-controller = "appController">

	<nav class="navbar navbar-default navbar-static-top cfs-navbar">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
         
                <a class="navbar-brand" href="#" ><img class="brand-img" src="<?php echo base_url(); ?>img/leasing-brand.png"></a>
            </div>
            <div class="nav-container">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-left">
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_payment" ><i class="fa fa-credit-card"></i> Payment</a> </li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_advancePayment" ><i class="fa fa-tags"></i> Advance Payment</a> </li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/closing_internal_payment" ><i class="fa fa-money"></i> Close Internal Payment</a> </li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_paymentHistory" ><i class="fa fa-history"></i> Payment History</a> </li>
					    <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_accountabilityReport" ><i class="fa fa-money"></i> Accountability Report</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/paymentList" ><i class="fa fa-list"></i> Payment List</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                       <li class=""><a href="#" ><i></i> Welcome: <?php echo $this->session->userdata('first_name'); ?></a></li>
                       <li class=""><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#usettings_modal" ng-click = "user_credentials('<?php echo base_url(); ?>index.php/ctrl_leasing/user_credentials/')"><i class="fa fa-cog"></i> Settings</a></li>
                       <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/logout" ><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </nav>
    <!-- Loading Modal -->
    <div class="modal" id = "spinner_modal">
        <!-- <h3 class="spin"><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></h3>
        <br>
        <span class = "process">Processing...</span> -->
        <div class="spin">
            <div id="fountainTextG"><div id="fountainTextG_1" class="fountainTextG">L</div><div id="fountainTextG_2" class="fountainTextG">o</div><div id="fountainTextG_3" class="fountainTextG">a</div><div id="fountainTextG_4" class="fountainTextG">d</div><div id="fountainTextG_5" class="fountainTextG">i</div><div id="fountainTextG_6" class="fountainTextG">n</div><div id="fountainTextG_7" class="fountainTextG">g</div><div id="fountainTextG_8" class="fountainTextG">.</div><div id="fountainTextG_9" class="fountainTextG">.</div><div id="fountainTextG_10" class="fountainTextG">.</div></div>
        </div>
    </div>
    <!-- End Confirmation Modal -->