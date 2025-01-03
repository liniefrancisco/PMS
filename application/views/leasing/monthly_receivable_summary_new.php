<div class="container" id="transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Monthly Receivable Summary NEW
            </div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="RR_reports"
                                    role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-10 col-md-offset-3" style="padding-top: 4%;">
                                    <div class="row">
                                        <form
                                            action="<?php echo base_url() ?>index.php/leasing_reports/generate_monthly_receivable_summary_new"
                                            method="post" id="frm_RR_reports" name="frm_RR_reports">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="beginning_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i>For the Month of</label>
                                                            <div class="col-md-8 text-left">
                                                                <input type="text" name="month" autocomplete="off"
                                                                    required ng-model="month" class="form-control" />
                                                                <div class="validation-Error">
                                                                    <span
                                                                        ng-show="frm_RR_reports.month.$dirty && frm_RR_reports.month.$error.required">
                                                                        <p class="error-display">This field is required.
                                                                        </p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pull-right" style="padding-right: 13%">
                                                    <div class="col-md-12">
                                                        <button type="submit" ng-disabled="frm_RR_reports.$invalid"
                                                            class="btn btn-primary btn-lg button-vl"><i
                                                                class="fa fa-doc"></i> Generate Report</button>
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
</div> <!-- End of Container -->