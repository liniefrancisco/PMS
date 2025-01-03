<div class="container" ng-controller="paymentCntrl">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing"><i class="fa fa-list"></i> OR Number Entry</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab"
									data-toggle="tab">General </a></li>
						</ul>
						<div class="tab-content ng-cloak">
							<div role="tabpanel" class="tab-pane active" id="payment_history">
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<div class="row">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label for="tenancy_type"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Tenancy Type</label>
														<div class="col-md-7">
															<select class="form-control" name="tenancy_type"
																ng-model="tenancy_type"
																ng-change="populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
																onchange="clear_paymentHistory()" required>
																<option value="" disabled="" selected=""
																	style="display:none">Please Select One</option>
																<option>Short Term Tenant</option>
																<option>Long Term Tenant</option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="trade_name"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Trade Name</label>
														<div class="col-md-7">
															<div class="input-group">
																<div mass-autocomplete>
																	<input required name="trade_name"
																		ng-model="dirty.value"
																		mass-autocomplete-item="autocomplete_options"
																		class="form-control" id="trade_name">
																</div>
																<span class="input-group-btn">
																	<button class="btn btn-info" type="button"
																		ng-click="get_paymentScheme(dirty.value, tenancy_type)"><i
																			class="fa fa-search"></i>
																	</button>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="tenant_id"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Tenant ID</label>
														<div class="col-md-7">
															<input type="text" readonly ng-model="tenant_id"
																id="tenant_id" name="tenant_id" class="form-control">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="address"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Address</label>
														<div class="col-md-7">
															<input type="text" readonly ng-model="address" id="address"
																name="address" class="form-control">
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
															<input type="text" readonly ng-model="contract_no"
																id="contract_no" name="contract_no"
																class="form-control">
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group">
														<label for="tin" class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>TIN</label>
														<div class="col-md-7">
															<input type="text" readonly ng-model="tin" id="tin"
																name="tin" class="form-control">
														</div>
													</div>
												</div>


												<div class="row">
													<div class="form-group">
														<label for="rental_type"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Rental Type</label>
														<div class="col-md-7">
															<input type="text" readonly ng-model="rental_type"
																id="rental_type" name="rental_type"
																class="form-control">
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group">
														<label for="curr_date"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Date</label>
														<div class="col-md-7">
															<input type="text" readonly
																value="<?php echo date('Y-m-d'); ?>" id="curr_date"
																name="curr_date" class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<hr>

								<div class="row"> <!-- table wrapper  -->
									<div class="col-md-12" ng-controller="tableController">
										<div class="row">
											<div class="col-md-3 pull-right">
												<input type="text" class="form-control search-query"
													placeholder="Search Here..." ng-model="searchedKeyword" />
											</div>
										</div>
										<table class="table table-bordered" ng-table="tableParams">
											<tbody id="paymentHistory_tbody">
												<tr class="ng-cloak" ng-repeat="dt in data">
													<td title="'Transaction No.'" sortable="'receipt_no'">{{
														dt.receipt_no }}</td>
													<td title="'Description'" sortable="'tender_typeDesc'">{{
														dt.tender_typeDesc }}</td>
													<td title="'Amount Paid'" sortable="'amount_paid'">{{ dt.amount_paid
														| currency : '' }}</td>
													<td title="'Action'">
														<a href="" data-toggle="modal" data-target="#add_or"
															class="btn btn-xs btn-primary"
															ng-click="getTransactionNo2(dt)">Add OR Number</a>
													</td>
												</tr>
												<tr class="ng-cloak" ng-show="!data.length && !isLoading">
													<td colspan="8">
														<center>No Data Available.</center>
													</td>
												</tr>
											</tbody>
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

	<!-- Add Data Modal -->
	<div class="modal fade" id="add_or">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-pencil"></i> Add OR Number</h4>
				</div>
				<!-- <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_leasing_user" name="add_form" method="post"> -->
				<form action="" name="addOR" method="post" enctype="multipart/form-data"
					ng-submit="addORNumber($event)">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-11">
								<div class="row">
									<div class="form-group">
										<label for="first_name" class="col-md-4 control-label text-right"><i
												class="fa fa-asterisk"></i>OR Number</label>
										<div class="col-md-8">
											<input type="text" required class="form-control" ng-model="or_number"
												id="or_number" name="or_number" autocomplete="off">
											<!-- FOR ERRORS -->
											<div class="validation-Error">
												<span
													ng-show="addOR.or_number.$dirty && addOR.or_number.$error.required">
													<p class="error-display">This field is required.</p>
												</span>
											</div>
										</div>

										<input type="hidden" name="transaction_number" id="transaction_number">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" ng-disabled="addOR.$invalid" class="btn btn-primary button-b"> <i
								class="fa fa-save"></i> Submit</button>
						<button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
								class="fa fa-close"></i> Close</button>
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Data Modal -->
</div> <!-- End of Container -->

</body>