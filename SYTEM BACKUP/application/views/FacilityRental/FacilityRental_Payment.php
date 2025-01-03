
	<div class="container" ng-controller="FacilityRentalController" ng-init="get_storeBankCode('<?php echo base_url();?>index.php/leasing_facilityrental/get_storeBankCode/'); get_paymentsoaNo('<?php echo base_url()?>index.php/leasing_facilityrental/populate_soano');">
	    <div class="well">
	        <div class="panel panel-default">
	            <div  class="panel-heading panel-leasing" ><i class="fa fa-pencil-square white" style="color:whitesmoke"></i> <span class="white" style="color:whitesmoke">Facility Rental Payment</span></div>
	            <div class="panel-body">
	                <div class="col-md-12">
	                    <div class="row">
	                        <ul class="nav nav-tabs" role="tablist">
	                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
	                        </ul>
	                        <div class="tab-content ng-cloak" ng-controller="CCMController" ng-init="populate_ccm_customer('<?php echo base_url(); ?>index.php/leasing_facilityrental/populate_ccm_customer/'); get_ccm_banks('<?php echo base_url(); ?>index.php/leasing_facilityrental/ccm_banks/')">
	                            <form name = "frm_payment" id = "frm_payment">
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
									                                      
									                                        id="receipt_no"
									                                      	readonly
									                                        name = "receipt_no"
									                                        class = "form-control emphasize currency"
									                                      	value="<?php echo $receipt_no;?>"
							                                                autocomplete="off">
									                                </div>
					                                            </div>
					                                        </div>
					                                    </div>

                                                        <div class="row">
					                                        <div class="form-group">
					                                            <label for="soa_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>SOA No.</label>
					                                            <div class="col-md-7">
                                                                    <select
                                                                        id = "soa_no"
                                                                        ng-model = "soa_no"
                                                                        name = "soa_no"
                                                                        class="form-control"
                                                                        ng-change = "get_soadetailspayment(soa_no,'<?php echo base_url(); ?>index.php/leasing_facilityrental/get_soadetailspayment/')"
                                                                        onchange="get_paymentsoatable('<?php echo base_url() ?>index.php/leasing_facilityrental/get_paymentsoatable','<?php echo base_url() ?>index.php/leasing_facilityrental/get_paymentdiscounttable')">
                                                                        <option ng-repeat = "soa in SoaNo">{{soa.soa_no}}</option>
                                                                    </select>
																	<input type="text" class="hidden" ng-model="facilityrental_docno" id="facilityrental_docno" name="facilityrental_docno">
                                                                </div>
                                                            </div>
					                                    </div>

					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Customer Name</label>
					                                            <div class="col-md-7">
					                                                <input
                                                                        type = "text"
					                                                    readonly
					                                                    name = "frcustomername"
					                                                    ng-model = "frcustomername"
					                                                    class = "form-control"
					                                                    id = "frcustomername"
																	>
																	<input class="hidden" type="text" id="customer_id" name="customer_id" ng-model="customer_id">
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
	                                                                            placeholder="Choose a date"
	                                                                            class="form-control"
	                                                                            ng-model="payment_date"
	                                                                            id="payment_date"
	                                                                            name = "payment_date"
	                                                                            autocomplete="off">
	                                                                    </datepicker>
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
																		autocomplete="off"
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
													</div>
													<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
					                                    <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="total" class="col-md-5 control-label text-right">Expected Amount</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                              
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="expected_amount"
		                                                                    readonly
		                                                                    id="expected_amount"
		                                                                    name = "expected_amount"
		                                                                    autocomplete="off">
		                                                            </div>
		                                                        </div>
		                                                    </div>
		                                                </div>
                                                        <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="total" class="col-md-5 control-label text-right">Total Discount</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                                
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="total_discount"
		                                                                    readonly
		                                                                    id="total_discount"
		                                                                    name = "total_discount"
		                                                                    autocomplete="off">
		                                                            </div>
		                                                        </div>
		                                                    </div>
		                                                </div>
                                                        <div class="row">
		                                                    <div class="form-group">
		                                                        <label for="total" class="col-md-5 control-label text-right">Actual Amount</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                            
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="actual_amount"
		                                                                    readonly
		                                                                    id="actual_amount"
		                                                                    name = "actual_amount"
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
		                                                            
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="soa_amountPaid"
		                                                                    readonly
		                                                                    id="soa_amountPaid"
		                                                                    name = "soa_amountPaid"
		                                                                    autocomplete="off">
		                                                            </div>
		                                                        </div>
		                                                    </div>
		                                                </div>
														<div class="row">
		                                                    <div class="form-group">
		                                                        <label for="total" class="col-md-5 control-label text-right">Balance</label>
		                                                        <div class="col-md-7">
		                                                            <div class="input-group">
		                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
		                                                                <input
		                                                                    type="text"
		                                                            
		                                                                    ui-number-mask="2"
		                                                                    class="form-control currency"
		                                                                    ng-model="balance"
		                                                                    readonly
		                                                                    id="balance"
		                                                                    name = "balance"
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
                                                    <div class="col-md-11">
                                                        <table class="table table-bordered" id="soa_table" style="margin-left:50px">
                                                            <thead>
                                                                <tr>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'doc_type'; reverse = !reverse">Document No.</a></td>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Facility Name</a></td>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'desc'; reverse = !reverse">Date of Use</a></td>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Facility Rate</a></td>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'due_date'; reverse = !reverse">Hours Used</a></td>
                                                                    <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'actual_amt'; reverse = !reverse">Amount</a></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- EDITABLE GRID END ROW -->

                                                    <div class="col-md-11">
                                                        <table class="table table-bordered" id="discount_table" style="margin-left:50px">
                                                            <thead>
                                                                <tr>
																	<td style="text-align:center;"><a href="#" >Document No.</a></td>
                                                                    <td style="text-align:center;"><a href="#" >Discount type</a></td>
                                                                    <td style="text-align:center;"><a href="#" >Percent/Amount</a></td>
                                                                    <td style="text-align:center;"><a href="#" >Discount Amount</a></td>
                                                                    <td style="text-align:center;"><a href="#" >Description</a></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- EDITABLE GRID END ROW -->
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
									                                 
									                                        class = "form-control">
						                                            </div>
						                                        </div>
						                                    </div>
						                                    <div class="row">
			                                                    <div class="form-group">
			                                                        <label for="amount_paid" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Amount Tendered</label>
			                                                        <div class="col-md-7">
			                                                            <div class="input-group">
			                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
			                                                                <input
			                                                                    type="text"
			                                                                
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
									                                        	ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_bankName/' + code.bank_code)">
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
									                                        ng-model = "frcustomername"
									                                        id="payor"
									                                        name = "payor"
									                                    
									                                        readonly
									                                        class = "form-control">
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
									                                  
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
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
									                                   
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
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
									                                   
									                                        ng-disabled = "tender_typeCode != '2'"
								                                        	ng-required = "tender_typeCode == '2'"
									                                        class = "form-control">
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
										                                 
										                                        autocomplete="on"
										                                        ng-disabled = "tender_typeCode != '2'"
									                                        	ng-required = "tender_typeCode == '2'"
										                                        class = "form-control">
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
		                                		<button  id="frsavepaymentbtn" onclick="save_facilityrentalpayment('<?php echo base_url();?>index.php/leasing_facilityrental/save_facilityrentalpayment')"  class="btn btn-large btn-primary col-md-1 col-md-offset-10"><i class="fa fa-save"></i> Submit</button>
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


	   


	