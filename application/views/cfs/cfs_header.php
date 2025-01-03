<!DOCTYPE html>
<html ng-app="myApp">

<head>
    <title>AGC-PMS |
        <?php echo $title; ?>
    </title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
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
    <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>css/animate.css'>
</head>

<body ng-controller="appController">

    <nav class="navbar navbar-default navbar-static-top cfs-navbar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img class="brand-img"
                        src="<?php echo base_url(); ?>img/pms-brand.png"></a>
            </div>
            <div class="nav-container" style="margin-left: 150px;">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-left">

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-credit-card"></i> Payment <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                <!-- <li>
                                    <a href="<?= base_url(); ?>index.php/ctrl_cfs/cfs_payment"> <i class="fa fa-credit-card"></i>  Regular Payment (Old)</a>
                                </li> -->
                                <li>
                                    <a href="<?= base_url(); ?>index.php/ctrl_cfs/payment"><i
                                            class="fa fa-credit-card"></i> Regular Payment</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/ctrl_cfs/advance_payment"> <i
                                            class="fa fa-tags"></i> Advance Payment</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>index.php/ctrl_cfs/closing_internal_payment"><i
                                            class="fa fa-money"></i> Close Internal Payment</a>
                                </li>

                            </ul>
                        </li>

                        <!--  <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_payment" ><i class="fa fa-credit-card"></i> Payment</a> </li> -->


                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_paymentHistory"><i
                                    class="fa fa-history"></i> Payment History</a> </li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_accountabilityReport"><i
                                    class="fa fa-money"></i> Accountability Report</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/paymentList"><i
                                    class="fa fa-list"></i> Payment List</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>rentaldepositsummary"><i class="fa fa-list"></i>
                                Rental Deposit Summary</a></li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/tenant_soa"><i
                                    class="fa fa-print"></i> Tenant SOA</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <!-- <li class=""><a class="" style="pointer-events:none;">
                                <?php echo $this->session->userdata('user_type'); ?>
                            </a></li> -->
                        <li class="">
                            <a style="display: block;line-height: .1;margin: 0;pointer-events:none;font-weight:600;">

                                Welcome:
                                <u>
                                    <?php echo $this->session->userdata('name'); ?>
                                </u>

                            </a>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false"
                                style="display: block;line-height: .0;margin-top:-10px;pointer-events:none;color:#fb6404;">
                                <center>
                                    <?php
                                    $store_code = $this->session->userdata('store_code');
                                    $user_type = $this->session->userdata('user_type');

                                    if ($user_type == 'CFS') {
                                        if ($store_code == 'ICM') {
                                            echo "ISLAND CITY MALL ";
                                        } elseif ($store_code == 'AM') {
                                            echo " ALTURAS MALL";
                                        } elseif ($store_code == 'PM') {
                                            echo "PLAZA MARCELA";
                                        } elseif ($store_code == 'ACT') {
                                            echo "ALTA CITTA";
                                        } elseif ($store_code == 'ASCT') {
                                            echo "ALTURAS TALIBON";
                                        } elseif ($store_code == 'ATT') {
                                            echo "ALTURAS TUBIGON";
                                        } elseif ($store_code == 'COL-C ') {
                                            echo "COLONNADE COLON";
                                        } elseif ($store_code == 'COL-M') {
                                            echo "COLONNADE MANDAUE";
                                        }
                                    }
                                    ?>
                                </center>
                            </a>
                        </li>
                        <li class=""><a href="<?php echo base_url(); ?>index.php/ctrl_cfs/logout"><i
                                    class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </nav>
    <!-- Loading Modal -->
    <div class="modal" id="spinner_modal">
        <!-- <h3 class="spin"><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></h3>
        <br>
        <span class = "process">Processing...</span> -->
        <div class="spin">
            <div id="fountainTextG">
                <div id="fountainTextG_1" class="fountainTextG">L</div>
                <div id="fountainTextG_2" class="fountainTextG">o</div>
                <div id="fountainTextG_3" class="fountainTextG">a</div>
                <div id="fountainTextG_4" class="fountainTextG">d</div>
                <div id="fountainTextG_5" class="fountainTextG">i</div>
                <div id="fountainTextG_6" class="fountainTextG">n</div>
                <div id="fountainTextG_7" class="fountainTextG">g</div>
                <div id="fountainTextG_8" class="fountainTextG">.</div>
                <div id="fountainTextG_9" class="fountainTextG">.</div>
                <div id="fountainTextG_10" class="fountainTextG">.</div>
            </div>
        </div>
    </div>
    <!-- End Confirmation Modal -->