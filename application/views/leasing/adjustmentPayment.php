<script type="text/javascript">var chargez = <?php echo json_encode($charges); ?>;</script>
<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Payment Adjustment
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop"
                                                        aria-controls="preop" role="tab" data-toggle="tab">General</a>
                                                </li>
                                            </ul>

                                            <form action="" method="post" name="paymentFormADJ"
                                                ng-submit="submitPaymentADJ($event)">
                                                <div class="tab-content ng-cloak">
                                                    <div role="tabpanel" class="tab-pane active" id="payment">
                                                        <div class="row">
                                                            <div class="col-md-10 col-md-offset-1">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <input type="hidden" name="managers_keyID"
                                                                            id="managers_keyID"
                                                                            value="<?php echo $managers_keyID->id; ?>">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tenancy_type"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Tenancy
                                                                                    Type</label>
                                                                                <div class="col-md-7">
                                                                                    <select name="tenancy_type"
                                                                                        class="form-control"
                                                                                        ng-model="tenancy_type"
                                                                                        ng-change="populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
                                                                                        required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <option>Short Term Tenant
                                                                                        </option>
                                                                                        <option>Long Term Tenant
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="trade_name"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Trade
                                                                                    Name</label>
                                                                                <div class="col-md-7">
                                                                                    <div mass-autocomplete>
                                                                                        <input id="trade_name"
                                                                                            name="trade_name"
                                                                                            class="form-control"
                                                                                            ng-model="dirty.value"
                                                                                            ng-change="tenantInfo2(dirty.value, tenancy_type)"
                                                                                            mass-autocomplete-item="autocomplete_options"
                                                                                            required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tenant_id"
                                                                                    class="col-md-5 control-label text-right">Tenant
                                                                                    ID</label>
                                                                                <div class="col-md-7">
                                                                                    <input id="tenant_id" type="text"
                                                                                        name="tenant_id"
                                                                                        class="form-control" readonly
                                                                                        ng-model="tenant_id">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="corporate_name"
                                                                                    class="col-md-5 control-label text-right">Corporate
                                                                                    Name</label>
                                                                                <div class="col-md-7">
                                                                                    <div mass-autocomplete>
                                                                                        <input id="corporate_name"
                                                                                            name="corporate_name"
                                                                                            class="form-control"
                                                                                            ng-model="corporate_name">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="ORnumber"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>OR#</label>
                                                                                <div class="col-md-7">
                                                                                    <input id="ORnumber" type="text"
                                                                                        name="ORnumber"
                                                                                        class="form-control" value=""
                                                                                        onkeyup="adjustmentKeyup(this, this.value)"
                                                                                        ng-model="ORnumber"
                                                                                        placeholder="Enter OR#"
                                                                                        autocomplete="off">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="corporate_name"
                                                                                    class="col-md-5 control-label text-right"></label>
                                                                                <div class="col-md-7">
                                                                                    <button id="trade_name_button"
                                                                                        type="button"
                                                                                        class="btn btn-primary btn-block button-b"
                                                                                        ng-click="adjustmentsPayment(dirty.value, tenancy_type)"
                                                                                        disabled>
                                                                                        <i class="fa fa-cog"
                                                                                            aria-hidden="true"></i>
                                                                                        Generate</i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row"> <!-- table wrapper  -->
                                                            <div class="container">
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <!-- <div class="col-md-6"> -->
                                                                        <div class="col-md-2">
                                                                            <label for="adj_code">Adjustment
                                                                                Code</label>
                                                                            <input id="adj_code" type="text"
                                                                                name="adj_code" class="form-control"
                                                                                value="" ng-model="adj_code" readonly>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label for="PReason"><i
                                                                                    class="fa fa-asterisk"></i>
                                                                                Reason</label>
                                                                            <select class="form-control" id="PReason"
                                                                                name="PReason" ng-model="PReason"
                                                                                required>
                                                                                <option value="" selected="">--</option>
                                                                                <option>Wrong Invoice</option>
                                                                                <option>Wrong Amount</option>
                                                                                <option>To Balance out</option>
                                                                                <option>Wrong application of entry
                                                                                </option>
                                                                                <option>Others</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-2"
                                                                            ng-if="PReason == 'Others'">
                                                                            <label
                                                                                for="Pparticulars">Particulars:</label>
                                                                            <input id="Pparticulars" type="text"
                                                                                name="Pparticulars" class="form-control"
                                                                                value="" ng-model="Pparticulars">
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label for="p_doc"><i
                                                                                    class="fa fa-asterisk"></i>
                                                                                Supporting Document:</label>
                                                                            <input id="p_doc" type="file" name="p_doc[]"
                                                                                class="form-control" multiple>
                                                                        </div>
                                                                        <!-- </div> end of col-md-6 -->
                                                                    </div> <!-- end of row -->

                                                                    <table class="table table-bordered"
                                                                        id="adjustment_table"
                                                                        ng-controller="tableController">
                                                                        <thead>
                                                                            <tr>
                                                                                <td title="'Applied To'"
                                                                                    sortable="'document_type'">Applied
                                                                                    To</td>
                                                                                <td title="'Document Applied'"
                                                                                    sortable="'doc_no'">Document Applied
                                                                                </td>
                                                                                <td title="'Account'"
                                                                                    sortable="'account'">Account</td>
                                                                                <td title="'Amount Paid'"
                                                                                    sortable="'amount'" width="15%">
                                                                                    Amount Paid</td>
                                                                                <td title="'Balance/Variance'"
                                                                                    sortable="'balance'">
                                                                                    Balance/Variance</td>
                                                                                <td title="'Adjustment'" width="15%">
                                                                                    Adjustment</td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="adjustmentTable_body">
                                                                            <tr class="ng-cloak" ng-repeat="p in Pdata">
                                                                                <td>
                                                                                    <span
                                                                                        ng-if="p.applied_to === '' || !p.applied_to">-</span>
                                                                                    <span ng-if="p.applied_to !== ''">{{
                                                                                        p.applied_to }}</span>
                                                                                </td>

                                                                                <td>
                                                                                    <span
                                                                                        ng-if="p.doc_applied === '' || !p.doc_applied">-</span>
                                                                                    <span
                                                                                        ng-if="p.doc_applied !== ''">{{
                                                                                        p.doc_applied }}</span>
                                                                                </td>

                                                                                <td>{{ p.gl_account }}</td>

                                                                                <td class="currency-align">
                                                                                    <span
                                                                                        ng-if="p.pmt_amount === '0' || !p.pmt_amount"></span>
                                                                                    <span
                                                                                        ng-if="p.pmt_amount !== ''">&#8369
                                                                                        {{ p.pmt_amount | currency : ''
                                                                                        }}</span>
                                                                                </td>

                                                                                <td class="currency-align">
                                                                                    <span
                                                                                        ng-if="p.inv_balance === '0' || !p.inv_balance"></span>
                                                                                    <span
                                                                                        ng-if="p.inv_balance !== '0'">&#8369
                                                                                        {{ p.inv_balance | currency : ''
                                                                                        }}</span>
                                                                                </td>

                                                                                <td>
                                                                                    <input id="adjAmount" type="text"
                                                                                        name="adjAmount"
                                                                                        class="form-control text-right currency"
                                                                                        ng-model="p.adjAmount"
                                                                                        ng-keyup="validateAmount(p, $event)"
                                                                                        placeholder="0.00"
                                                                                        ng-disabled="PReason == null"
                                                                                        autocomplete="off">

                                                                                    <!-- FOR ERRORS -->
                                                                                    <div class="validation-Error">
                                                                                        <span>
                                                                                            <p class="error-display"
                                                                                                ng-model="p.errorDisplayP1">
                                                                                            </p>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="col-md-3 pull-right">
                                                                    <label for="PTotal">Total:</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon input-date">
                                                                            <strong>&#8369</strong>
                                                                        </div>
                                                                        <input id="PTotal" type="text"
                                                                            class="form-control text-right currency"
                                                                            style="width: 90%"
                                                                            ng-value="ptotalValue() | currency : ''"
                                                                            placeholder="0.00" autocomplete="off"
                                                                            readonly>

                                                                        <input type="hidden" name="PTotal"
                                                                            ng-value="ptotalValue()">
                                                                    </div>
                                                                    <div class="validation-Error">
                                                                        <span>
                                                                            <p class="error-display"
                                                                                ng-bind="errorDisplayP"></p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 pull-right">
                                                        <div class="row">
                                                            <div class="col-md-10 text-right">
                                                                <button id="applyAdjP" type="submit"
                                                                    class="btn btn-primary btn-medium  button-b"
                                                                    disabled>
                                                                    <i class="fa fa-save"></i> Apply Adjustment
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- End of tab-content -->
                                            </form><!-- END OF FORM -->
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