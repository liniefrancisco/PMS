
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS -->

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/index_style.css">

    <title>AGC-PMS</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/logo-ico.ico" />
</head>
<body>
    <div class="main-body"> 
        <div class="container">
            <div class="row">               
                <div class="main-page">
                    <div class="content-main">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="box bottom-main">
                                    <div class="info float-container">
                                        <div class="col-sm-12 bottom-title">
                                            <h3 class="text-uppercase">ALTURAS GROUP OF COMPANIES</h3>
                                            <h4 class="text-uppercase">Property Management System</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                                <div class="bottom-img">
                                                    <a href="<?php echo base_url(); ?>index.php/ctrl_leasing">
                                                        <img src="<?php echo base_url(); ?>img/lease.jpg" alt="Image" class="menu-img">
                                                        <p class="first">Leasing Module</p>  
                                                    </a>  
                                                </div>                                      
                                            </div>
                                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                                <div class="bottom-img">
                                                    <a href="<?php echo base_url(); ?>index.php/ctrl_cfs/cfs_login">
                                                        <img src="<?php echo base_url(); ?>img/cfs.jpg" alt="Image" class="menu-img">
                                                        <p class="cfs" style="background-color: #125821;">CFS Module</p>  
                                                    </a>  
                                                </div>                                      
                                            </div>  
                                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                                <div class="bottom-img">
                                                    <a href="<?= base_url('../AGC-TSMS'); ?>">
                                                        <img src="<?php echo base_url(); ?>img/accounting.jpg" alt="Image" class="menu-img">
                                                        <p class="second" >TSMS Module</p>    
                                                    </a>
                                                </div>                                      
                                            </div>
                                            <!-- <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-4 col-lg-3">
                                                <div class="bottom-img">
                                                    <a href="#">
                                                        <img src="<?php echo base_url(); ?>img/maintenance.jpg" alt="Image" class="menu-img">
                                                        <p class="third">Other Module</p>  
                                                    </a>  
                                                </div>                                      
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- row -->
                    </div> <!-- .content-main -->
                </div> <!-- .main-page -->
            </div> <!-- .row -->           
            <footer class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
                    <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
                </div>    
            </footer>  <!-- .row -->      
        </div> <!-- .container -->
    </div> <!-- .main-body -->

    <!-- JavaScript -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
</body>
</html>
