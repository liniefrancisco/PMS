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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/ng-table.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/massautocomplete.theme.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/treeview.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/index_style.css">


</head>
<body ng-controller="appController">
    <nav class="navbar navbar-default navbar-static-top" id="leasing_navbar">
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
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/IAD_home"><i class="fa fa-home"></i> Home</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/general_ledger"><i class="fa fa-balance-scale"></i> General Ledger</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/tenant_ledger"><i class="fa fa-book"></i> Tenant Ledger</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/soa"><i class="fa fa-columns"></i> SOA</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/payment_history"><i class="fa fa-history"></i> Payment History</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/rr_ar_summary"><i class="fa fa-newspaper-o"></i> Monthly RR/AR Summary</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/monthly_payable"><i class="fa fa-list-alt"></i> Monthly Payable & Amount Paid</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/monthly_receivable_summary"><i class="fa fa-list-alt"></i> Monthly Receivable Summary</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/CFS_remittance"><i class="fa fa-list-alt"></i> CFS Remittance</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/payment_report"><i class="fa fa-list-alt"></i> Payment Report</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome: <u><?php echo $this->session->userdata('first_name'); ?></u><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#usettings_modal" ng-click = "user_credentials('<?php echo base_url(); ?>index.php/ctrl_leasing/user_credentials/')"><i class="fa fa-cog"></i> Account Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url(); ?>index.php/ctrl_leasing/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- /.container-fluid -->
    </nav>


    <!-- Confirmation Modal -->
    <div class="modal fade" id = "confirmation_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="confirm_msg">You wish to delete this data?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="anchor_delete" class="btn btn-danger"><i class="fa fa-trash"></i> Proceed</a>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->


    <!-- Confirmation Modal -->
    <div class="modal fade" id = "rentIncremention_modal">
        <div class="modal-dialog" style="width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Annual Rental Incrementation</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_rentalIncrement" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Incrementation</label>
                                        <div class="col-md-8">
                                            <?php foreach ($rental_increment as $value): ?>
                                                <div class="input-group">
                                                    <input type="number" id = "rent_increment" required name = "rent_increment" value="<?php echo $value['amount']; ?>" readonly class="form-control currency" aria-describedby="basic-addon2">
                                                    <span class="input-group-addon" id="basic-addon2" style = "padding:0px"><button type="button" id="toggleEdit" class="btn btn-info" style="line-height: 1.9;"> <i class="fa fa-edit"></i> Edit</button></span>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type = "submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->


    <!-- Confirmation1 Modal -->
    <div class="modal fade" id = "confirmation1_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="confirm_msg">You wish to continue this action?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="anchor_confirm" class="btn btn-primary"><i class="fa fa-thumbs-up"></i> Proceed</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Confirmation Modal -->


    <!-- Print Preview Modal -->
    <div class="modal fade" id = "print_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-tv"></i> Print Preview</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id = "print_preview"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" onclick="callPrint('printPreview_frame')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Print Preview Modal -->





    <!-- User Setting Modal -->
    <div class="modal fade" id = "usettings_modal">
        <div class="modal-dialog modal-medium">
            <div class="modal-content" ng-repeat = "user in details">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-cog"></i> User Settings</h4>
                </div>
                <form action="{{ '../ctrl_leasing/update_usettings/' + user.id }}" method="post" name = "frm_usettings" id = "frm_usettings">
                    <div class="modal-body">
                        <div class="row">
                            <div class = "col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Username</label>
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="user.username"
                                                id="username"
                                                name = "username"
                                                ng-minlength="5"
                                                autocomplete="off"
                                                ng-pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/"
                                                is-unique-update
                                                is-unique-id = "{{user.id}}"
                                                is-unique-api = "<?php echo base_url(); ?>index.php/ctrl_validation/verify_username_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                                <span ng-show="frm_usettings.username.$dirty && frm_usettings.username.$error.unique">
                                                    <p class="error-display">Username already taken.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Old Password</label>
                                        <div class="col-md-6">
                                            <input
                                                type="password"
                                                required
                                                class="form-control"
                                                ng-model="old_pass"
                                                id="old_pass"
                                                name = "old_pass"
                                                autocomplete="off"
                                                is-unique-update
                                                is-unique-id = "{{user.id}}"
                                                is-unique-api = "<?php echo base_url(); ?>index.php/ctrl_validation/check_oldpass/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.old_pass.$dirty && frm_usettings.old_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.old_pass.$dirty && frm_usettings.old_pass.$error.unique">
                                                    <p class="error-display">Old password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>New Password</label>
                                        <div class="col-md-6">
                                            <input
                                                type="password"
                                                required
                                                class="form-control"
                                                ng-model="new_pass"
                                                id="new_pass"
                                                name = "new_pass"
                                                autocomplete="off"
                                                ng-pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/"
                                                ng-minlength = "5">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="frm_usettings.new_pass.$dirty && frm_usettings.new_pass.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Confirm Password</label>
                                        <div class="col-md-6">
                                            <input
                                                type="password"
                                                required
                                                class="form-control"
                                                ng-model="confirm_pass"
                                                id="confirm_pass"
                                                name = "confirm_pass"
                                                autocomplete="off"
                                                data-password-verify="new_pass">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_usettings.confirm_pass.$dirty && frm_usettings.confirm_pass.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_usettings.confirm_pass.$dirty && frm_usettings.confirm_pass.$error.passwordVerify">
                                                    <p class="error-display">Password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_usettings.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End User Setting Modal -->

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


    <!-- Contract Termination Modal -->
    <div class="modal fade" id = "contract_termination">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-remove"></i> Terminate Contract</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" ng-submit = "terminate_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/terminate_tenant/' + reason, '<?php echo $this->session->userdata('user_type'); ?>')" name = "frm_termination" id = "frm_termination">
                                <div class="form-group col-md-12">
                                    <label for="reason_termination">Reason</label>
                                    <input type = "text" style = "display:none" id = "tenantID" ng-model = "tenantID" name = "tenantID">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="reason_termination"
                                        name = "reason"
                                        autocomplete="off"
                                        ng-model = "reason"
                                        placeholder="Reason for termination."
                                        required>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <div class="validation-Error">
                                        <span ng-show="frm_termination.reason.$dirty && frm_termination.reason.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Contract Termination Modal -->
