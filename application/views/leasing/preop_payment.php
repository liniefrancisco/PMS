
<div class="container" ng-controller="transactionController">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Payment Using Preoperation Charges</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<ul class="nav nav-tabs bot-margin" role="tablist">
							<li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
						</ul>
						<div class="tab-content ng-cloak">
							<form action="" onsubmit="save_payment('<?php echo base_url(); ?>index.php/leasing_transaction/save_preopPayment/'); return false"    method="post"  name = "frm_payment" id = "frm_payment">
								<div role="tabpanel" class="tab-pane active" id="payment">
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="row">
												<div class="col-md-6">
													<div class="row">
														<div class="form-group">
															<label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Document No.</label>
															<div class="col-md-7">
																<input
																	type = "text"
																	readonly
																	value = "<?php echo $payment_docNo; ?>"
																	id="receipt_no"
																	name = "receipt_no"
																	class = "form-control">
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
																			ng-click = "generate_soaForPayment(dirty.value)"><i class = "fa fa-search"></i>
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
													<div class="row">
														<div class="form-group">
															<label for="tender_typeDesc" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type Description</label>
															<div class="col-md-7">
																<select class="form-control" name="tender_typeDesc" required>
																	<option value="" disabled="" selected="" style = "display:none">Please Select One</option>
																	<option>Security Deposit</option>
																	<option>Construction Bond</option>
																</select>
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
													<div class="row">
														<div class="form-group">
															<label for="total" class="col-md-5 control-label text-right">Amount Paid</label>
															<div class="col-md-7">
																<div class="input-group">
																	<div class="input-group-addon"><strong>&#8369;</strong></div>
																	<input
																		type="text"
																		required
																		ui-number-mask="2"
																		class="form-control currency"
																		ng-model="tender_amount"
																		id="tender_amount"
																		name = "tender_amount"
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
														<tbody id="payment_tbody" ng-show="!isLoading">
															<tr ng-repeat = "data in paymentBasic">
																<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{data.id}}</td>
																<td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Payment</td>
																<td><input type = "text" style = "display:none" name = "desc[]" value="<?php echo "Basic-"; ?>{{trade_name}}" /> Basic-{{trade_name}}</td>
																<td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
																<td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
																<td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
																<td><input type = "text" style = "display:none" name = "amount[]" ng-model = "data.amount"  /> {{data.amount | currency : ''}}</td>
																<td><input type = "text" style = "display:none" name = "amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : ''}}</td>
																<td><input type = "text" style = "display:none" name = "balance[]" ng-model = "data.balance"  />{{data.balance | currency : ''}}</td>
																<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
															</tr>
															<tr ng-repeat = "data in paymentRetro">
																<td style="display:none"><input type = "text" style = "display:none" name = "retro_charge_id[]" ng-model="data.id" /> {{data.id}}</td>
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
															<tr ng-repeat = "other in paymentOther">
																<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{other.id}}</td>
																<td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Payment</td>
																<td><input type = "text" style = "display:none" name = "desc[]" value="<?php echo "Other-"; ?>{{trade_name}}" /> Other-{{trade_name}}</td>
																<td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "other.doc_no" /> {{other.doc_no}}</td>
																<td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "other.posting_date"  /> {{other.posting_date}}</td>
																<td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "other.due_date"  /> {{other.due_date}}</td>
																<td><input type = "text" style = "display:none" name = "amount[]" ng-model = "other.amount" /> {{ other.amount| currency : '' }} </td>
																<td><input type = "text" style = "display:none" name = "amount_paid[]" ng-model = "other.amount_paid"  /> {{other.amount_paid | currency : ''}}</td>
																<td><input type = "text" style = "display:none" name = "balance[]"  ng-model = "other.balance" /> {{other.balance | currency : ''}}</td>
																<td><a class = "btn-sm btn-danger" ng-click = "get_paymentTotal(data.balance)" onClick="deletefromPayment(this)"  href = "#"><i class = "fa fa-trash"></i></a></td>
															</tr>
															
															<tr ng-repeat = "data in invoicePenalty">
																<td style="display:none"><input type = "text" style = "display:none" name = "charge_id[]" ng-model="data.id" /> {{data.id}}</td>
																<td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Payment</td>
																<td><input type = "text" style = "display:none" name = "desc[]" ng-model = "data.description" /> {{data.description}}</td>
																<td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "" /> {{data.doc_no}}</td>
																<td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "data.posting_date"  />{{data.posting_date}}</td>
																<td><input type = "text" style = "display:none" name = "due_date[]"  />{{data.due_date}}</td>
																<td><input type = "text" style = "display:none" name = "amount[]" ng-model = "data.begbal"/>{{data.begbal | currency : ''}}</td>
																<td><input type = "text" style = "display:none" name = "amount_paid[]" ng-model = "data.amount_paid" /> {{data.amount_paid | currency : ''}} </td>
																<td><input type = "text" style = "display:none" name = "balance[]"  ng-model = "data.balance" />{{data.balance | currency : ''}}</td>
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
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button role = "submit" ng-disabled = "frm_payment.$invalid" class="btn btn-large btn-primary col-md-1 col-md-offset-10 button-b"><i class="fa fa-save"></i> Submit</button>
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
