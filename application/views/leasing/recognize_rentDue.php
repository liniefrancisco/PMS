<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Recognize Rentable Due</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab"
                                    data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <?php foreach ($details as $value): ?>
                                <form action=""
                                    onsubmit="save_recognizeRentDue('<?php echo base_url(); ?>index.php/leasing_transaction/save_recognizeRentDue'); return false"
                                    method="post" name="frm_recognizeRent" id="frm_recognizeRent">
                                    <div role="tabpanel" class="tab-pane active" id="payment">
                                        <div class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="doc_no"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Document No.</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" id="doc_no" readonly required
                                                                        value="<?php echo $doc_no; ?>" name="doc_no"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="trade_name"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Trade Name</label>
                                                                <div class="col-md-7">
                                                                    <input required
                                                                        value="<?php echo $value['trade_name']; ?>"
                                                                        name="trade_name" class="form-control"
                                                                        id="trade_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="tenant_id"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Tenant ID</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" readonly
                                                                        value="<?php echo $value['tenant_id']; ?>"
                                                                        id="tenant_id" name="tenant_id"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contract_no"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Contract No.</label>
                                                                <div class="col-md-7">
                                                                    <input type="text"
                                                                        value="<?php echo $value['contract_no']; ?>"
                                                                        readonly id="contract_no" name="contract_no"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="tenant_type"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Tenant Type</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" readonly id="tenant_type"
                                                                        value="<?php echo $value['tenant_type']; ?>"
                                                                        name="tenant_type" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="transaction_date"
                                                                    class="col-md-5 control-label text-right">Transaction
                                                                    Date</label>
                                                                <div class="col-md-7">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon input-date"><strong><i
                                                                                    class="fa fa-calendar"></i></strong>
                                                                        </div>
                                                                        <datepicker date-format="yyyy-M-dd">
                                                                            <input type="text" required
                                                                                placeholder="Choose a date"
                                                                                class="form-control"
                                                                                ng-model="transaction_date"
                                                                                id="transaction_date"
                                                                                name="transaction_date" autocomplete="off">
                                                                        </datepicker>
                                                                        <!-- FOR ERRORS -->
                                                                        <div class="validation-Error">
                                                                            <span
                                                                                ng-show="frm_recognizeRent.transaction_date.$dirty && frm_recognizeRent.transaction_date.$error.required">
                                                                                <p class="error-display">This field is
                                                                                    required.</p>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="large-label">Entries :</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8 col-md-offset-2">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="RI_amount"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Rent Income</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" required
                                                                            class="form-control currency"
                                                                            ng-model="RI_amount" format="number"
                                                                            id="RI_amount" name="RI_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="VAT_amount"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Output VAT</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" required
                                                                            class="form-control currency"
                                                                            ng-model="VAT_amount" format="number"
                                                                            id="VAT_amount" name="VAT_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="WHT_amount"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Creditable Withholding
                                                                    Tax</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" required
                                                                            class="form-control currency"
                                                                            ng-model="WHT_amount" format="number"
                                                                            id="WHT_amount" name="WHT_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="CC_amount"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Cashier Charges</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" required
                                                                            class="form-control currency"
                                                                            ng-model="CC_amount" format="number"
                                                                            id="CC_amount" name="CC_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="EC_amount"
                                                                    class="col-md-5 control-label text-right">Electricty
                                                                    Charges</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="EC_amount" format="number"
                                                                            id="EC_amount" name="EC_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="OT_amount"
                                                                    class="col-md-5 control-label text-right">Overtime
                                                                    Charges</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="OT_amount" format="number"
                                                                            id="OT_amount" name="OT_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="penalty_amount"
                                                                    class="col-md-5 control-label text-right">Penalty</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="penalty_amount" format="number"
                                                                            id="penalty_amount" name="penalty_amount"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="service_req"
                                                                    class="col-md-5 control-label text-right">Service
                                                                    Request</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="service_req" format="number"
                                                                            id="service_req" name="service_req"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="notary_fee"
                                                                    class="col-md-5 control-label text-right">Notary
                                                                    Fee</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="notary_fee" format="number"
                                                                            id="notary_fee" name="notary_fee"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="cp_adjustment"
                                                                    class="col-md-5 control-label text-right">Cashier
                                                                    Posting Adjustment</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" class="form-control currency"
                                                                            ng-model="cp_adjustment" format="number"
                                                                            id="cp_adjustment" name="cp_adjustment"
                                                                            autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button role="submit" ng-disabled="frm_recognizeRent.$invalid"
                                                    class="btn btn-large btn-primary col-md-1 col-md-offset-10 button-vl"><i
                                                        class="fa fa-save"></i> Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php endforeach ?>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>