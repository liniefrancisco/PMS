<!DOCTYPE html>
<html ng-app="myApp">
<head>
    <title>AGC-PMS</title>
    <script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/3D.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-buttons.css"> --> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/noty-animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
</head>
<body id = "leasing_login" ng-controller="appController">
    <div class="jumbotron jumbotron_leasing" style="background-color:#455d63">

    </div>
    <div class="container leasingLogin_form">
        <div class="row">
            <div class="col-md-4 col-ms-4 col-lg-4 col-centered">
                <div class="account-wall">
                    <img class="profile-img" src="<?php echo base_url(); ?>img/agc-leasing-logo.png"alt="">
                    <form class="form-signin" action="<?php echo base_url(); ?>index.php/ctrl_leasing/admin_login" method="post">
                        <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" ng-model="username" required autofocus>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Log in</button>
                        <a href="#" class="pull-right need-help">Administrator Login </a><span class="clearfix"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/bootstrap.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.noty.packaged.min.js"></script>
<script type="text/javascript" src = "<?php echo base_url(); ?>js/notification.js"></script> 


<script type="text/javascript">
    <?php switch($flashdata): 
        case "Invalid Login": ?>
            generate('error', '<div class="activity-item"> <i class="fa fa-ban text-success"></i> Invalid Login</div>');
        <?php break;?>

        <?php case "Added": ?>
            generate('success', 'Successfully Added');
        <?php break;?>
    <?php endswitch;?> 
</script>

</html>