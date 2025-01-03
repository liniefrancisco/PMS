<div class="container" ng-controller="transactionController">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Payment History</div>
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
															<input type="text" ng-model="tenant_id" id="tenant_id"
																name="tenant_id" class="form-control" readonly>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="address"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Address</label>
														<div class="col-md-7">
															<input type="text" ng-model="address" id="address"
																name="address" class="form-control" readonly>
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
															<input type="text" ng-model="contract_no" id="contract_no"
																name="contract_no" class="form-control" readonly>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="tin" class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>TIN</label>
														<div class="col-md-7">
															<input type="text" ng-model="tin" id="tin" name="tin"
																class="form-control" readonly>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="rental_type"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Rental Type</label>
														<div class="col-md-7">
															<input type="text" ng-model="rental_type" id="rental_type"
																name="rental_type" class="form-control" readonly>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="curr_date"
															class="col-md-5 control-label text-right"><i
																class="fa fa-asterisk"></i>Date</label>
														<div class="col-md-7">
															<input type="text" value="<?php echo date('Y-m-d'); ?>"
																id="curr_date" name="curr_date" class="form-control"
																readonly>
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
													<td title="'Payment Slip No.'" sortable="'receipt_no'">{{
														dt.receipt_no }}</td>
													<td title="'Description'" sortable="'tender_typeDesc'">{{
														dt.tender_typeDesc }}</td>
													<td title="'Amount Paid'" sortable="'amount_paid'">{{ dt.amount_paid
														| currency : '' }}</td>
													<td title="'Check No.'" sortable="'check_no'">
														<div ng-if="!dt.check_no">N/A</div>
														<div ng-if="dt.check_no">{{ dt.check_no }}</div>
													</td>
													<td title="'Check Date'" sortable="'check_date'">
														<div ng-if="dt.check_date == '0000-00-00'">N/A</div>
														<div ng-if="dt.check_date != '0000-00-00'">{{ dt.check_date }}
														</div>
													</td>
													<td title="'Payee'" sortable="'payee'">{{ dt.payee }}</td>
													<td title="'Action'" class="text-center">

														<a href="<?php echo base_url(); ?>assets/pdf/{{dt.receipt_doc}}"
															target="_blank"
															class="btn small-print-button btn-primary">View</a>
														<?php
														if (
															$this->session->userdata('user_type') == 'Accounting Staff' &&
															in_array(
																$this->session->userdata('username'),
																['Icmaccounting1', 'Pmaccounting1', 'am_accounting', 'alta_accounting', 'tubigon', 'at_accounting', 'col_accounting', 'man_accounting']
															)
														):
															?>

															<!-- <a class="btn btn-info small-print-button" type="button"
																target="_blank"
																href="{{ $base_url }}leasing/paymentReprintPdf/{{ dt.receipt_doc }}/{{ dt.receipt_no }}">
																<i class="fa fa-print"></i> Reprint
															</a> -->
															<!---------------------------------------- gwapss -------------------------------------->
															<button class="btn btn-info small-print-button" type="button"
																data-toggle="modal" data-target="#manager_modal_payment"
																ng-click="reprintPaymentNewTest('<?php echo base_url(); ?>leasing/paymentReprintNew/' + dt.receipt_doc + '/' + dt.receipt_no)">
																Print
															</button>
															<!---------------------------------------- gwapss ends ---------------------------------->
														<?php endif ?>
														<!-- <a class="btn btn-info small-print-button" type="button"
															data-toggle="modal" data-target="#manager_modal_payment"
															ng-click="reprintPaymentNew(dt)">
															<i class="fa fa-print"></i> Re-print
														</a> -->
														<a href="#" ng-if="dt.tender_typeDesc != 'Cash'"
															data-toggle="modal" data-target="#carousel_modal"
															ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_paymentDocs/' + dt.receipt_no, '<?php echo base_url(); ?>assets/payment_docs/')"
															class="btn btn-success small-print-button"><i
																class="fa fa-tv"></i> View Document</a>

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

		<!-- Manager's Key Modal -->
		<div class="modal fade" id="manager_modal_payment">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
					</div>
					<form action="" method="post" name="frm_manager_payment" id="frm_manager_payment">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<div class="input-group">
													<div class="input-group-addon squared"><i class="fa fa-user"></i>
													</div>
													<input type="text" required class="form-control" ng-model="username"
														id="username" name="username" autocomplete="off">
												</div>
												<!-- FOR ERRORS -->
												<div class="validation-Error">
													<span
														ng-show="frm_manager_payment.username.$dirty && frm_manager_payment.username.$error.required">
														<p class="error-display">This field is required.</p>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<div class="input-group">
													<div class="input-group-addon squared"><i class="fa fa-key"></i>
													</div>
													<input type="password" required class="form-control"
														ng-model="password" id="password" name="password"
														autocomplete="off">
												</div>
												<!-- FOR ERRORS -->
												<div class="validation-Error">
													<span
														ng-show="frm_manager_payment.password.$dirty && frm_manager_payment.password.$error.required">
														<p class="error-display">This field is required.</p>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" ng-disabled="frm_manager_payment.$invalid"
									class="btn btn-primary button-b"> <i class="fa fa-unlock"></i> Submit</button>
								<button type="button" class="btn btn-alert button-w" data-dismiss="modal"> <i
										class="fa fa-close"></i> Close</button>
							</div>
						</div><!-- /.modal-content -->
					</form>
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</div>
	</div> <!-- End of Well -->
</div> <!-- End of Container -->


<!-- Carousel Modal -->
<div class="modal fade" id="carousel_modal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<div ng-if="!imgList" class="item active">
						<img src="<?php echo base_url(); ?>img/thumbnail.png" alt="Default">
					</div>
					<div ng-class="{item: true, 'active' : ($index === 0)}" ng-repeat="img in imgList">
						<img ng-src="{{imgPath}}{{img.file_name}}" alt="{{img.file_name}}">
					</div>
					<!-- Controls -->
					<a ng-if="imgList.length > 1" class="left carousel-control" href="#carousel-example-generic"
						role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a ng-if="imgList.length > 1" class="right carousel-control" href="#carousel-example-generic"
						role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!--End Carousel Modal -->


</body>