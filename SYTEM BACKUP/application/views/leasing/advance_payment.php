
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Advance Payment</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <form action="" onsubmit="save_payment('<?php echo base_url(); ?>index.php/leasing_transaction/save_advancePayment/'); return false"    method="post"  name = "frm_payment" id = "frm_payment">
                            	<div role="tabpanel" class="tab-pane active" id="payment">
	                                <div class="row">
	                                	<div class="col-md-10 col-md-offset-1">
											<div class="row">
												<div class="col-md-6">
													<div class="row">
				                                        <div class="form-group">
				                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Payment Receipt No.</label>
				                                            <div class="col-md-7">
							                                    <div class="input-group">
							                                    	<div class="input-group-addon input-date"><b>PR</b></div>
							                                    	<input
								                                        type = "text"
								                                        ng-model="receipt_no"
								                                        id="receipt_no"
								                                        required
								                                        name = "receipt_no"
								                                        class = "form-control emphasize currency"
								                                        is-unique
						                                                is-unique-api="../ctrl_validation/verify_receiptNo/"
						                                                autocomplete="off">
								                                    </div>

							                                    <!-- FOR ERRORS -->
					                                            <div class="validation-Error">
					                                                <span ng-show="frm_payment.receipt_no.$dirty && frm_payment.receipt_no.$error.required">
					                                                    <p class="error-display">This field is required.</p>
					                                                </span>
					                                                <span ng-show="frm_payment.receipt_no.$dirty && frm_payment.receipt_no.$error.unique">
					                                                    <p class="error-display">Receipt No already in use.</p>
					                                                </span>
					                                            </div>
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
	                                                        <div class="col-md-7">
	                                                            <select
	                                                                class = "form-control"
	                                                                name = "tenancy_type"
	                                                                id="tenancy_type"
	                                                                ng-model = "tenancy_type"
	                                                                ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
	                                                                onchange = "clear_paymentData()"
	                                                                required>
	                                                                <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
	                                                                <option>Short Term Tenant</option>
	                                                                <option>Long Term Tenant</option>
	                                                            </select>
	                                                        </div>
	                                                    </div>
	                                                </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
				                                            <div class="col-md-7">
				                                                <div class="input-group">
				                                                    <div mass-autocomplete>
				                                                        <input
				                                                            required
				                                                            name = "trade_name"
				                                                            ng-model = "dirty.value"
				                                                            mass-autocomplete-item = "autocomplete_options"
				                                                            class = "form-control"
				                                                            id = "trade_name">
				                                                    </div>
				                                                    <span class="input-group-btn">
				                                                        <button
				                                                            class = "btn btn-info"
				                                                            type = "button"
				                                                            ng-click = "generate_invoicingCredentials(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
				                                                        </button>
				                                                    </span>
				                                                </div>
				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        ng-model = "tenant_id"
							                                        id="tenant_id"
							                                        name = "tenant_id"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>


												</div>
												<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->

													<div class="row">
				                                        <div class="form-group">
				                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contract No.</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-model = "contract_no"
							                                        readonly
							                                        id = "contract_no"
							                                        name = "contract_no"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="curr_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Payment Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="curr_date"
                                                                            id="curr_date"
                                                                            name = "curr_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_soa.curr_date.$dirty && frm_soa.curr_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="remarks" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Remarks</label>
				                                            <div class="col-md-7">
						                                        <input
							                                        type = "text"
							                                        ng-model = "remarks"
							                                        id="remarks"
							                                        name = "remarks"
							                                        class = "form-control">
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
													<label class="large-label">Payment Scheme :</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-10 col-md-offset-1">
													<div class="col-md-6">
														<div class="row">
					                                        <div class="form-group">
					                                            <label for="tender_typeCode" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type Code</label>
					                                            <div class="col-md-7">
								                                    <select
								                                    	class="form-control"
								                                    	name = "tender_typeCode"
								                                    	ng-model = "tender_typeCode"
								                                    	required
								                                    	ng-change = "populat_tenderTypeDesc(tender_typeCode);is_cash(tender_typeCode, '<?php echo $store_id; ?>')">
								                                    	<option>1</option>
								                                    	<option>2</option>
								                                    	<option>3</option>
								                                    	<option>80</option>
								                                    	<option>81</option>
								                                    </select>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type Description</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "text"
								                                        ng-model = "tender_typeDesc"
								                                        id="tender_typeDesc"
								                                        name = "tender_typeDesc"
								                                        required
								                                        readonly
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row" ng-if = "tender_typeCode != '1'">
					                                        <div class="form-group">
					                                            <label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Supporting Document</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "file"
								                                        id="supp_doc"
								                                        name = "supp_doc[]"
								                                        accept="image/*"
								                                        required
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="amount_paid" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Amount Paid</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                                    required
		                                                                    class="form-control currency"
		                                                                    ng-model="amount_paid"
		                                                                    format="number"
		                                                                    id="amount_paid"
		                                                                    name = "amount_paid"
		                                                                    autocomplete="off">
		                                                            </div>
		                                                        </div>
		                                                    </div>
		                                                </div>
					                                    <div id="_cash_bank">
					                                    	<div class="row">
						                                        <div class="form-group">
						                                            <label for="bank_code" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Bank Code</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	ng-required = "tender_typeCode == '1' && tender_typeCode == '2'"
								                                        	ng-model = "code.bank_code"
								                                        	name = "bank_code"
								                                        	class="form-control"
								                                        	ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_transaction/get_bankName/' + code.bank_code)">
								                                        	<option ng-repeat = "code in storeBankCode">{{code.bank_code}}</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Bank Name</label>
						                                            <div class="col-md-7">
								                                        <select  ng-required = "tender_typeCode == '1' && tender_typeCode == '2'" id = "payment_bank_name" name = "bank" class="form-control">
								                                        	<option ng-repeat = "data in itemList">{{data.bank_name}}</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
					                                    </div>
													</div> <!-- col-md-6 divider -->
													<div class="col-md-6">
														<div class="row">
					                                        <div class="form-group">
					                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check No./ DS No.</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "text"
								                                        ng-model = "check_no"
								                                        id="check_no"
								                                        name = "check_no"
								                                        required
                                                                        is-unique
						                                                is-unique-api="../ctrl_validation/verify_checkNo/"
						                                                autocomplete="off"
								                                        ng-disabled = "tender_typeCode != '2' && tender_typeCode != '3'"
							                                        	ng-required = "tender_typeCode == '2' && tender_typeCode == '3'"
								                                        class = "form-control"
								                                        ng-pattern = "/^\d+$/">

								                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.unique">
                                                                            <p class="error-display">Check No. already in use.</p>
                                                                        </span>
                                                                    </div>
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.pattern">
                                                                            <p class="error-display">Numeric value only</p>
                                                                        </span>
                                                                    </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Date</label>
					                                            <div class="col-md-7">
							                                        <div class="input-group">
	                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
	                                                                    <datepicker  date-format="yyyy-M-dd"> <!--date-min-limit="<?php echo date('Y-m-d'); ?>" -->
	                                                                        <input
	                                                                            type="text"
	                                                                            required
	                                                                            readonly
	                                                                            ng-disabled = "tender_typeCode != '2'"
							                                        			ng-required = "tender_typeCode == '2'"
	                                                                            placeholder="Choose a date"
	                                                                            class="form-control"
	                                                                            ng-model="check_date"
	                                                                            id="check_date"
	                                                                            name = "check_date"
	                                                                            autocomplete="off">
	                                                                    </datepicker>
	                                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="payor" class="col-md-5 control-label text-right">Payor</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "text"
								                                        ng-model = "trade_name"
								                                        id="payor"
								                                        name = "payor"
								                                        required
								                                        readonly
								                                        class = "form-control">

								                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_payment.payor.$dirty && frm_payment.payor.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="payee" class="col-md-5 control-label text-right">Payee</label>
					                                            <div class="col-md-7">
							                                        <input
								                                        type = "text"
								                                        value="<?php echo $payee; ?>"
								                                        id="payee"
								                                        name = "payee"
								                                        required
								                                        readonly
								                                        class = "form-control">

								                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_payment.payee.$dirty && frm_payment.payee.$error.required">
                                                                            <p class="error-display">This field is required.</p>
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
	                                		<button role = "submit" ng-disabled = "frm_payment.$invalid" class="btn btn-large btn-primary col-md-1 col-md-offset-10"><i class="fa fa-save"></i> Submit</button>
	                                	</div>
	                                </div>
	                            </div>
                            </form>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
