
	<div class="container" ng-controller="transactionController">
	    <div class="well">
	        <div class="panel panel-default">
	            <div  class="panel-heading" style="background-color:#125821"><i class="fa fa-pencil-square white" style="color:whitesmoke"></i> <span class="white" style="color:whitesmoke">Payment</span></div>
	            <div class="panel-body">
	                <div class="col-md-12">
	                    <div class="row">
	                        <ul class="nav nav-tabs" role="tablist">
	                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
	                        </ul>
	                        <div class="tab-content ng-cloak" ng-controller="CCMController" ng-init="populate_ccm_customer('<?php echo base_url(); ?>index.php/ctrl_cfs/populate_ccm_customer/'); get_ccm_banks('<?php echo base_url(); ?>index.php/ctrl_cfs/ccm_banks/')">
	                            <form action="#" onsubmit="save_payment('<?php echo base_url(); ?>index.php/ctrl_cfs/save_payment/'); return false"  method="post"  name = "frm_payment" id = "frm_payment">
	                            	<div role="tabpanel" class="tab-pane active" id="payment">
		                                <div class="row">
		                                	<div class="col-md-10 col-md-offset-1" style = "margin-top: 20px">
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
					                                                            readonly
					                                                            name = "trade_name"
					                                                            ng-model = "trade_name"
					                                                            class = "form-control"
					                                                            id = "trade_name">
					                                                    </div>
					                                                    <span class="input-group-btn">
					                                                        <button
					                                                            class = "btn btn-info"
					                                                            type = "button"
					                                                            data-backdrop="static"
					                                                            data-keyboard="false"
					                                                            data-toggle="modal"
					                                                            data-target="#lookup"
					                                                            ng-click = "loadList2('<?php echo base_url(); ?>index.php/ctrl_cfs/tenant_lookup/' + tenancy_type)"><i class = "fa fa-search"></i>
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
													</div>
													<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->

														<div class="row">
					                                        <div class="form-group">
					                                            <label for="soa_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>SOA No.</label>
					                                            <div class="col-md-7">
								                                    <input
								                                        type = "text"
								                                        ng-model = "soa_no"
								                                        readonly
								                                        id = "soa_no"
								                                        name = "soa_no"
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="billing_period" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Billing Period</label>
					                                            <div class="col-md-7">
								                                    <input
								                                        type = "text"
								                                        ng-model = "billing_period"
								                                        readonly
								                                        id = "billing_period"
								                                        name = "billing_period"
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                    <div class="row">
	                                                        <div class="form-group">
	                                                            <label for="curr_date" class="col-md-5 control-label text-right">Payment Date</label>
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
	                                                                        <span ng-show="frm_payment.curr_date.$dirty && frm_payment.curr_date.$error.required">
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
					                                    <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="total" class="col-md-5 control-label text-right">Total Payable Amount</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                                    required
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="total_amount"
		                                                                    readonly
		                                                                    id="total_amount"
		                                                                    name = "total"
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
									 				<div class="col-md-12">
														<table class="table table-bordered" id="payment_table">
				                                            <thead>
				                                                <tr>
				                                                	<th style="display:none">ID</th>
				                                                    <th>Doc. Type</th>
				                                                    <th>Description</th>
				                                                    <th>Doc. No.</th>
				                                                    <th>Posting Date</th>
				                                                    <th>Due Date</th>
				                                                    <th>Amount</th>
				                                                    <th>Amount Paid</th>
				                                                    <th>Balance</th>
				                                                    <th></th>
				                                                </tr>
				                                            </thead>
				                                            <tbody id="payment_tbody" ng-show = "!isLoading">
				                            					<tr ng-repeat = "data in paymentBasic">
				                            						<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{data.id}}</td>
				                            						<td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Payment</td>
				                            						<td><input type = "text" style = "display:none" name = "desc[]" value="<?php echo "Basic-"; ?>{{trade_name}}" /> Basic-{{trade_name}}</td>
				                            						<td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
				                            						<td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "amount[]" ng-model = "data.amount"  /> {{data.amount | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "basic_amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "balance[]" ng-model = "data.balance"  />{{data.balance | currency : ''}}</td>
				                            						<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
				                            					</tr>
				                            					<tr ng-repeat = "data in preopCharges">
				                            						<td style="display:none"><input type = "text" style = "display:none" name = "preop_charge_id[]" ng-model="data.id" /> {{data.id}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_doc_type[]" value="" /> Payment</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_desc[]" value="{{data.description}}" /> {{data.description}}-{{trade_name}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_amount[]" ng-model = "data.amount"  /> {{data.amount | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_amount_paid[]" ng-model = "data.amount_paid"  /> 0.00</td>
				                            						<td><input type = "text" style = "display:none" name = "preop_balance[]" ng-model = "data.balance"  />{{data.amount | currency : ''}}</td>
				                            						<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.amount)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
				                            					</tr>
				                            					<tr ng-repeat = "other in paymentOther">
				                            						<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{other.id}}</td>
				                            						<td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Payment</td>
				                            						<td><input type = "text" style = "display:none" name = "desc[]" value="<?php echo "Other-"; ?>{{trade_name}}" /> Other-{{trade_name}}</td>
				                            						<td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "other.doc_no" /> {{other.doc_no}}</td>
				                            						<td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "other.posting_date"  /> {{other.posting_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "other.due_date"  /> {{other.due_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "amount[]" ng-model = "other.amount" /> {{ other.amount| currency : '' }} </td>
				                            						<td><input type = "text" style = "display:none" name = "other_amount_paid[]" ng-model = "other.amount_paid"  /> {{other.amount_paid | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "balance[]"  ng-model = "other.balance" /> {{other.balance | currency : ''}}</td>
				                            						<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(other.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
				                            					</tr>

				                            					<tr ng-repeat = "data in invoicePenalty">
				                            						<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{data.id}}</td>
				                            						<td>Payment</td>
				                            						<td>{{data.description}}</td>
				                            						<td><input type = "text" style = "display:none" name = "penalty_doc_no[]" /> {{data.doc_no}}</td>
				                            						<td>{{data.posting_date}}</td>
				                            						<td></td>
				                            						<td>{{data.begbal | currency : ''}}</td>
				                            						<td>{{data.amount_paid | currency : ''}} </td>
				                            						<td>{{data.balance | currency : ''}}</td>
				                            						<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
				                            					</tr>

																<tr ng-repeat = "data in paymentRetro">
				                            						<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{data.id}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_doc_type[]" value="" /> Payment</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_desc[]" value="<?php echo "Retro-"; ?>{{trade_name}}" /> Retro-{{trade_name}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_amount[]" ng-model = "data.amount"  /> {{data.amount | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : ''}}</td>
				                            						<td><input type = "text" style = "display:none" name = "retro_balance[]" ng-model = "data.balance"  />{{data.balance | currency : ''}}</td>
				                            						<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
				                            					</tr>
				                                            </tbody>
				                                            <tbody ng-show = "isLoading">
				                                            	<tr>
								                                    <td colspan="9">
								                                    	<div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
								                                    	<div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
								                                    </td>
								                                </tr>
				                                            </tbody>
				                                        </table>
													</div>
												</div>
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
						                                            <label for="payee" class="col-md-5 control-label text-right">Payee</label>
						                                            <div class="col-md-7">
								                                        <input
								                                        	type = "text"
			                                                                class = "form-control"
			                                                                name = "payee"
			                                                                value = "<?php echo $store[0]['store_name'] ?>"
			                                                                readonly
			                                                                id="payment_payee"
			                                                                required />
						                                            </div>
						                                        </div>
						                                    </div>
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="tender_typeCode" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type Code</label>
						                                            <div class="col-md-7">
									                                    <select
									                                    	class="form-control"
									                                    	name = "tender_typeCode"
									                                    	ng-model = "tender_typeCode"
									                                    	required
									                                    	ng-change = "populat_tenderTypeDesc(tender_typeCode);is_cash(tender_typeCode, <?php echo $store[0]['id'] ?>)"
									                                    	id = "payment_tender_type">
									                                    	<option>1</option>
									                                    	<option>2</option>
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
									                                        multiple
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
			                                                <div id = "_cash_bank">
																<div class="row">
							                                        <div class="form-group">
							                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Bank Code</label>
							                                            <div class="col-md-7">
									                                        <select
									                                        	id = "payment_bank_code"
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
									                                        <select id = "payment_bank_name"  name = "bank" class="form-control">
									                                        	<option ng-repeat = "data in itemList">{{data.bank_name}}</option>
									                                        </select>
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
														</div> <!-- col-md-6 divider -->
														<div class="col-md-6">
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="account_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Account Number</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "account_no"
									                                        id="account_no"
									                                        name = "account_no"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.account_no.$dirty && frm_payment.account_no.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="account_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Account Name</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "account_name"
									                                        id="account_name"
									                                        name = "account_name"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.account_name.$dirty && frm_payment.account_name.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
															<div class="row">
						                                        <div class="form-group">
						                                            <label for="check_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Number</label>
						                                            <div class="col-md-7">
								                                        <input
									                                        type = "text"
									                                        ng-model = "check_no"
									                                        id="check_no"
									                                        name = "check_no"
									                                        required
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.check_no.$dirty && frm_payment.check_no.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
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
		                                                                    <datepicker  date-format="yyyy-M-dd"> <!-- date-min-limit="<?php echo date('Y-m-d'); ?>" -->
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
						                                            <label for="expiry_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Expiry Date</label>
						                                            <div class="col-md-7">
								                                        <div class="input-group">
		                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
		                                                                    <datepicker  date-format="yyyy-M-dd">
		                                                                        <input
		                                                                            type="text"
		                                                                            readonly
		                                                                            ng-disabled = "tender_typeCode != '2'"
		                                                                            placeholder="Choose a date"
		                                                                            class="form-control"
		                                                                            ng-model="expiry_date"
		                                                                            id="expiry_date"
		                                                                            name = "expiry_date"
		                                                                            autocomplete="off">
		                                                                    </datepicker>
		                                                                </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Class</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	id = "check_class"
								                                        	ng-model = "check_class"
								                                        	name = "check_class"
								                                        	required
								                                        	class="form-control"
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<option>COMPANY</option>
								                                        	<option>GOVERNMENT</option>
								                                        	<option>PERSONAL</option>
								                                        	<option>SUPPLIER</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Category</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	id = "check_category"
								                                        	ng-model = "check_category"
								                                        	name = "check_category"
								                                        	class="form-control"
								                                        	required
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<option>LOCAL</option>
								                                        	<option>MANILA</option>
								                                        	<option>NATIONAL</option>
								                                        	<option>REGIONAL</option>
								                                        </select>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="customer_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Customer Name</label>
						                                            <div class="col-md-7">
								                                        <div mass-autocomplete>
								                                        	<input
										                                        type = "text"
										                                        ng-model="dirty.value"
	                                                                            mass-autocomplete-item="autocomplete_options"
										                                        id="customer_name"
										                                        name = "customer_name"
										                                        required
										                                        autocomplete="on"
										                                        ng-disabled = "tender_typeCode != '2'"
									                                        	ng-required = "tender_typeCode == '2'"
										                                        class = "form-control">
								                                        </div>
									                                    <!-- FOR ERRORS -->
	                                                                    <div class="validation-Error">
	                                                                        <span ng-show="frm_payment.customer_name.$dirty && frm_payment.customer_name.$error.required">
	                                                                            <p class="error-display">This field is required.</p>
	                                                                        </span>
	                                                                    </div>
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
						                                        <div class="form-group">
						                                            <label for="bank" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Check Bank</label>
						                                            <div class="col-md-7">
								                                        <select
								                                        	id = "check_bank"
								                                        	ng-model = "bank.bankbranchname"
								                                        	name = "check_bank"
								                                        	required
								                                        	class="form-control"
								                                        	ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'">
								                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
								                                        	<option ng-repeat="bank in ccm_banks" value="{{ bank.bank_id }}">{{ bank.bankbranchname }}</option>
								                                        </select>
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


	    <!-- Tenant Lookup Modal -->
	    <div class="modal fade" id = "lookup" >
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                    <h4 class="modal-title"><i class="fa fa-tv"></i> Tenant Look Up</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                        <div class="col-md-3 pull-right">
	                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query2" ng-keydown = "currentPage2 = 0" />
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-12">
	                            <table class="table table-bordered">
	                                <thead>
	                                    <tr>
	                                        <th ><a href="#" data-ng-click="sortBy = 'tenant_id'; reverse = !reverse">Tenant ID</a></th>
	                                        <th ><a href="#" data-ng-click="sortBy = 'trade_name'; reverse = !reverse">Trade Name</a></th>
	                                        <th ><a href="#" data-ng-click="sortBy = 'store_name'; reverse = !reverse">Store Location</a></th>
	                                        <th width="15%">Action</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr class="ng-cloak" ng-show="dataList2.length!=0" ng-repeat= "data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
	                                        <td>{{data.tenant_id}}</td>
	                                        <td>{{data.trade_name}}</td>
	                                        <td>{{data.store_name}}</td>
	                                        <td align="right">
	                                            <!-- Split button -->
	                                            <div class="btn-group" style="width:70% !important">
	                                                <button type="button" style="width:70% !important" ng-click = "get_dataForPayment(data.tenant_id)" class="btn btn-xs btn-primary" data-dismiss = "modal">Select</button>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                    <tr class="ng-cloak" ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
	                                        <td colspan="6"><center>No Data Available.</center></td>
	                                    </tr>
	                                </tbody>
	                                <tfoot>
	                                    <tr class="ng-cloak">
	                                        <td colspan="6" style="padding: 5px;">
	                                            <div>
	                                                <ul class="pagination">
	                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="prevPageDisabled2()">
	                                                        <a href ng-click="prevPage2()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
	                                                    </li>
	                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-repeat="n in range2()" ng-class="{active: n == currentPage2}" ng-click="setPage2(n)">
	                                                        <a href="#">{{n+1}}</a>
	                                                    </li>
	                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="nextPageDisabled2()">
	                                                        <a href ng-click="nextPage2()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                </tfoot>
	                            </table>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-danger" data-dismiss = "modal"><i class = "fa fa-close"></i> Close</button>
	                </div>
	            </div><!-- /.modal-dialog -->
	        </div><!-- /.modal -->
	    </div>
    	<!-- End Tenant Lookup Modal -->
	</div> <!-- End of Container -->









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
                <div class="modal-header cfs-background">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-cog"></i> User Settings</h4>
                </div>
                <form action="{{ '../ctrl_cfs/update_usettings/' + user.id }}" method="post" name = "frm_usettings" id = "frm_usettings">
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
