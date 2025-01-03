<div class="container" ng-controller="transactionController">
	<div class="row">
		<div class="main-page" style="margin-top:20px;">
			<div class="content-main">
				<div class="row">
					<div class="well">
						<div class="panel panel-default">
							<div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Payment History</div>
							<div class="panel-body">
								<div class="col-md-12">
									<div class="row">
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
										</ul>
										<div class="tab-content ng-cloak">
											<div role="tabpanel" class="tab-pane active" id="payment_history">
												<div class="row">
													<div class="col-md-10 col-md-offset-1">
														<div class="row">
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
																		<div class="col-md-7">
																			<select
																				class = "form-control"
																				name = "tenancy_type"
																				ng-model = "tenancy_type"
																				ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
																				onchange = "clear_paymentHistory()"
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
																		<label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
																		<div class="col-md-7">
																			<div class="input-group">
																				<div mass-autocomplete>
																					<input
																						required
																						name = "tenant_id"
																						ng-model = "dirty.value"
																						mass-autocomplete-item = "autocomplete_options"
																						class = "form-control"
																						id = "tenant_id">
																				</div>
																				<span class="input-group-btn">
																					<button
																						class = "btn btn-info"
																						type = "button"
																						ng-click = "get_paymentScheme(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
																					</button>
																				</span>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
																		<div class="col-md-7">
																			<input
																				type = "text"
																				readonly
																				ng-model = "trade_name"
																				id="trade_name"
																				name = "trade_name"
																				class = "form-control">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label for="address" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Address</label>
																		<div class="col-md-7">
																			<input
																				type = "text"
																				readonly
																				ng-model = "address"
																				id="address"
																				name = "address"
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
																				readonly
																				ng-model = "contract_no"
																				id="contract_no"
																				name = "contract_no"
																				class = "form-control">
																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="form-group">
																		<label for="tin" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>TIN</label>
																		<div class="col-md-7">
																			<input
																				type = "text"
																				readonly
																				ng-model = "tin"
																				id="tin"
																				name = "tin"
																				class = "form-control">
																		</div>
																	</div>
																</div>


																<div class="row">
																	<div class="form-group">
																		<label for="rental_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Rental Type</label>
																		<div class="col-md-7">
																			<input
																				type = "text"
																				readonly
																				ng-model = "rental_type"
																				id = "rental_type"
																				name = "rental_type"
																				class = "form-control">
																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="form-group">
																		<label for="curr_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Date</label>
																		<div class="col-md-7">
																			<input
																				type = "text"
																				readonly
																				value="<?php echo date('Y-m-d'); ?>"
																				id="curr_date"
																				name = "curr_date"
																				class = "form-control">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<hr>
												<div class="row"> <!-- table wrapper  -->
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-3 pull-right">
																<input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
															</div>
														</div>
														<table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList()">
															
															<tbody id = "paymentHistory_tbody">
																<tr class="ng-cloak" ng-repeat= "dt in data">
																	<td title="'Receipt No.'" sortable = "'receipt_no'">{{ dt.receipt_no }}</td>
																	<td title="'Billing Period'" sortable = "'billing_period'">{{ dt.billing_period }}</td>
																	<td title="'Description'" sortable = "'tender_typeDesc'">{{ dt.tender_typeDesc }}</td>
																	<td title="'Amount Paid'" sortable = "'amount_paid'">{{ dt.amount_paid | currency : '' }}</td>
																	<td title="'Check No.'" sortable = "'check_no'">
																		<div ng-if = "!dt.check_no">N/A</div>
																		<div ng-if = "dt.check_no">{{ dt.check_no }}</div>
																	</td>
																	<td title="'Check Date'" sortable = "'check_date'">
																		<div ng-if = "dt.check_date == '0000-00-00'">N/A</div>
																		<div ng-if = "dt.check_date != '0000-00-00'">{{ dt.check_date }}</div>
																	</td>
																	<td title="'Payee'" sortable = "'payee'">{{ dt.payee }}</td>
																	<td><a href="<?php echo base_url(); ?>assets/pdf/{{dt.receipt_doc}}" target="_blank" class="btn btn-small btn-primary"><i class="fa fa-print"></i></a></td>
																</tr>
															</tbody>
															
															<tr class="ng-cloak" ng-show="!data.length">
																<td colspan="10"><center>No Data Available.</center></td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div> <!-- End of tab-content -->
									</div>
								</div>
							</div> <!-- End of panel-body -->
						</div> <!-- End of panel -->
					</div> <!-- End of Well -->
				</div> <!-- row -->
			</div> <!-- .content-main -->
		</div> <!-- .main-page -->
	</div> <!-- .row -->
	<footer class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
			<p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
		</div>
	</footer>  <!-- .row -->
</div> <!-- .container -->
