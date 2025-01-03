<div class="container" id = "transactionController" ng-controller="transactionController" >
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Payment Report</div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-10 col-md-offset-3" style="padding-top: 4%;">
                                    <div class="row">
                                        <form action="" onSubmit="payment_report('<?php echo base_url() ?>index.php/Leasing_reports/generate_paymentReport'); return false"  method="post" id="frm_paymentReport" name = "frm_paymentReport">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="from_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>From Date</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="from_date"
                                                                            id="from_date"
                                                                            name = "from_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_paymentReport.from_date.$dirty && frm_paymentReport.from_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="to_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>As Of Date</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="to_date"
                                                                            id="to_date"
                                                                            name = "to_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_paymentReport.to_date.$dirty && frm_paymentReport.to_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-12 col-md-offset-4">
                                                                <input type = "checkbox" name = "description[]" value = "Cash" id = "cash"/> <label for="cash">Cash</label>
                                                                <input type = "checkbox" name = "description[]" value = "Check" id = "check"/> <label for="check">Check</label>
                                                                <input type = "checkbox" name = "description[]" value = "Bank to Bank" id = "otc"/> <label for="otc">OTC Payment</label>
                                                                <input type = "checkbox" name = "description[]" value = "JV" id = "jv"/> <label for="jv">Journal Voucher</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pull-right" style="padding-right: 13%">
                                                    <div class = "col-md-12">
                                                        <div class="text-center">
                                                            <label>Document Type</label> <br>
                                                            <label><input type="radio" name="doc_type" value="pdf"> PDF</label>
                                                            <label><input type="radio" name="doc_type" value="excel"> Excel</label>
                                                            
                                                        </div>
                                                        <button type = "submit" ng-disabled = "frm_paymentReport.$invalid" class = "btn btn-primary btn-lg"><i class = "fa fa-doc"></i> Generate Report</button>
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
