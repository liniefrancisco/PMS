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
                                    onsubmit="save_closingRentDue('<?php echo base_url(); ?>index.php/leasing_transaction/save_closingRentDue'); return false"
                                    enctype="multipart/form-data" method="post" name="frm_closingRentDue"
                                    id="frm_closingRentDue">
                                    <div role="tabpanel" class="tab-pane active" id="payment">
                                        <div class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="JV_no"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>JV No.</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" ng-model="JV_no" id="JV_no" required
                                                                        name="JV_no" class="form-control emphasize currency"
                                                                        is-unique
                                                                        is-unique-api="../ctrl_validation/verify_receiptNo/"
                                                                        autocomplete="off">
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span
                                                                            ng-show="frm_closingRentDue.JV_no.$dirty && frm_closingRentDue.JV_no.$error.required">
                                                                            <p class="error-display">This field is required.
                                                                            </p>
                                                                        </span>
                                                                        <span
                                                                            ng-show="frm_closingRentDue.JV_no.$dirty && frm_closingRentDue.JV_no.$error.unique">
                                                                            <p class="error-display">Receipt No already in
                                                                                use.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                                <label for="JV_doc"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>JV Document</label>
                                                                <div class="col-md-7">
                                                                    <input type="file" required id="JV_doc" name="JV_doc"
                                                                        accept="image/*" class="form-control">
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
                                                                                ng-show="frm_closingRentDue.transaction_date.$dirty && frm_closingRentDue.transaction_date.$error.required">
                                                                                <p class="error-display">This field is
                                                                                    required.</p>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="tender_amount"
                                                                    class="col-md-5 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Tender Amount</label>
                                                                <div class="col-md-7">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon">
                                                                            <strong>&#8369;</strong></div>
                                                                        <input type="text" required
                                                                            class="form-control currency"
                                                                            ng-model="tender_amount" format="number"
                                                                            id="tender_amount" name="tender_amount"
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
                                                <div class="row">
                                                    <div class="col-md-12"
                                                        ng-init="get_unCloseRentDue('<?php echo base_url(); ?>index.php/leasing_transaction/get_unCloseRentDue/' + '<?php echo $value['tenant_id']; ?>')">
                                                        <table class="table table-bordered" id="payment_table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="display:none">ID</th>
                                                                    <th>Doc. No.</th>
                                                                    <th>Description</th>
                                                                    <th>Ref. No.</th>
                                                                    <th>Posting Date</th>
                                                                    <th>Amount</th>
                                                                    <th>Amount Paid</th>
                                                                    <th>Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="payment_tbody">
                                                                <tr ng-repeat="data in unCloseRentDue">
                                                                    <td style="display:none"><input type="text"
                                                                            style="display:none" name="entry_id[]"
                                                                            ng-model="data.id" /> {{data.id}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="doc_no[]" ng-model="data.doc_no" />
                                                                        {{data.doc_no}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="desc[]" value="" /> Rent Due</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="ref_no[]" ng-model="data.posting_date" />
                                                                        {{data.ref_no}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="posting_date[]"
                                                                            ng-model="data.posting_date" />
                                                                        {{data.posting_date}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="amount[]" ng-model="data.amount" />
                                                                        {{data.amount | currency : ''}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="amount_paid[]"
                                                                            ng-model="data.amount_paid" />
                                                                        {{data.amount_paid | currency : ''}}</td>
                                                                    <td><input type="text" style="display:none"
                                                                            name="balance[]"
                                                                            ng-model="data.balance" />{{data.balance |
                                                                        currency : ''}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button role="submit" ng-disabled="frm_closingRentDue.$invalid"
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