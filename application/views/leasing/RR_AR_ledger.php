<div class="container" id = "transactionController" ng-controller="transactionController" >
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Rent & Other Charges Ledger</div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="RR_reports" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-10 col-md-offset-3" style="padding-top: 4%;">
                                    <div class="row">
                                        <form action="<?php echo base_url() ?>index.php/leasing_reports/generate_RR_ARLedger"  method="post" id="frm_rr_ar_ledger" name = "frm_rr_ar_ledger">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="beginning_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>For the Month of</label>
                                                            <div class="col-md-8 text-left">
                                                                <input type = "text" name = "month" autocomplete="off" required ng-model = "month" class = "form-control"/>
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_rr_ar_ledger.month.$dirty && frm_rr_ar_ledger.month.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pull-right" style="padding-right: 13%">
                                                    <div class = "col-md-12">
                                                        <button type = "submit" ng-disabled = "frm_rr_ar_ledger.$invalid" class = "btn btn-primary btn-lg button-vl"><i class = "fa fa-doc"></i> Generate Report</button>
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
