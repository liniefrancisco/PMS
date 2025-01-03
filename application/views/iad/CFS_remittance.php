<div class="container">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well" ng-controller="tableController">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-newspaper-o"></i> CFS
                                    Remittance</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop"
                                                        aria-controls="preop" role="tab" data-toggle="tab">General </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active">
                                                    <div class="col-md-10 col-md-offset-3" style="padding-top: 2%;">
                                                        <div class="row">
                                                            <form action=""
                                                                onSubmit="payment_report('<?php echo base_url() ?>index.php/Leasing_reports/generate_CFS_remittance'); return false"
                                                                method="post" id="frm_paymentReport"
                                                                name="frm_paymentReport">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <!-- SECOND COL-MD-6 WRAPPER -->
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="from_date"
                                                                                    class="col-md-4 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>From
                                                                                    Date</label>
                                                                                <div class="col-md-8 text-left">
                                                                                    <div class="input-group">
                                                                                        <div
                                                                                            class="input-group-addon input-date">
                                                                                            <strong><i
                                                                                                    class="fa fa-calendar"></i></strong>
                                                                                        </div>
                                                                                        <datepicker
                                                                                            date-format="yyyy-M-dd">
                                                                                            <input type="text" required
                                                                                                readonly
                                                                                                placeholder="Choose a date"
                                                                                                class="form-control"
                                                                                                ng-model="from_date"
                                                                                                id="from_date"
                                                                                                name="from_date"
                                                                                                autocomplete="off">
                                                                                        </datepicker>
                                                                                        <!-- FOR ERRORS -->
                                                                                        <div class="validation-Error">
                                                                                            <span
                                                                                                ng-show="frm_paymentReport.from_date.$dirty && frm_paymentReport.from_date.$error.required">
                                                                                                <p
                                                                                                    class="error-display">
                                                                                                    This field is
                                                                                                    required.</p>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="to_date"
                                                                                    class="col-md-4 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>As Of
                                                                                    Date</label>
                                                                                <div class="col-md-8 text-left">
                                                                                    <div class="input-group">
                                                                                        <div
                                                                                            class="input-group-addon input-date">
                                                                                            <strong><i
                                                                                                    class="fa fa-calendar"></i></strong>
                                                                                        </div>
                                                                                        <datepicker
                                                                                            date-format="yyyy-M-dd">
                                                                                            <input type="text" required
                                                                                                readonly
                                                                                                placeholder="Choose a date"
                                                                                                class="form-control"
                                                                                                ng-model="to_date"
                                                                                                id="to_date"
                                                                                                name="to_date"
                                                                                                autocomplete="off">
                                                                                        </datepicker>
                                                                                        <!-- FOR ERRORS -->
                                                                                        <div class="validation-Error">
                                                                                            <span
                                                                                                ng-show="frm_paymentReport.to_date.$dirty && frm_paymentReport.to_date.$error.required">
                                                                                                <p
                                                                                                    class="error-display">
                                                                                                    This field is
                                                                                                    required.</p>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row pull-right"
                                                                        style="padding-right: 13%">
                                                                        <div class="col-md-12">
                                                                            <button type="submit"
                                                                                ng-disabled="frm_paymentReport.$invalid"
                                                                                class="btn btn-primary btn-lg button-vl"><i
                                                                                    class="fa fa-doc"></i> Generate
                                                                                Report</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End of tab-content -->
                                        </div>
                                    </div>
                                </div> <!-- End of panel-body -->
                            </div> <!-- End of panel -->
                        </div> <!-- End of Well -->
                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#">Cyril Andrew</a></p>
        </div>
    </footer> <!-- .row -->
</div> <!-- .container -->