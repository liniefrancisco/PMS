<?php 

$user_type = $this->session->userdata('leasing_logged_in') ? 'leasing' : 'cfs';


?>
<div class="container" ng-controller = "transactionController as tc">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing" style="<?= $user_type == 'leasing' ? '' : 'background-color:#125821' ?>">
				<i class="fa fa-registered"></i> Closing of Internal Payments
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak" ng-controller="CCMController" ng-init="populate_ccm_customer('<?php echo base_url(); ?>index.php/ctrl_cfs/populate_ccm_customer/'); get_ccm_banks('<?php echo base_url(); ?>index.php/ctrl_cfs/ccm_banks/')">
                        	<form action="#" onsubmit="closeInternalPayment('<?= base_url()?>index.php/ctrl_cfs/closeInternalPayment'); return false" method="post" name = "frm_clearing" id = "frm_clearing">
                        		<div role="tabpanel" class="tab-pane active" id="rr_clearing">
                        			<div class="row">
                        				<div class="col-md-10 col-md-offset-1">
                        					<div class="row">
                        						<div class="col-md-6">
                        							<!-- <div class="row">
                        								<label class="col-md-5 control-label text-right" style="font-size: 12px;"> PAYMENT DETAILS:</label>
                        							</div> -->

                        							<div class="row">
				                                        <div class="form-group">
				                                            <label for="receipt_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Payment Receipt No.</label>
				                                            <div class="col-md-7">
				                                            	<div class="input-group">
							                                    	<div class="input-group-addon input-date"><b>PR</b></div>
							                                    	<input
								                                        type = "text"
								                                        id="receipt_no"
								                                        required
								                                        name = "receipt_no"
								                                        class = "form-control emphasize"
								                                        is-unique
						                                                is-unique-api="../ctrl_validation/verify_receiptNo/"
						                                                ng-model="receipt_no"
						                                                autocomplete="off">
								                                </div>
					                                            <!-- FOR ERRORS -->
					                                            <div class="validation-Error">
					                                                <span ng-show="frm_clearing.receipt_no.$dirty && frm_clearing.receipt_no.$error.required">
					                                                    <p class="error-display">This field is required.</p>
					                                                </span>
					                                                <span ng-show="frm_clearing.receipt_no.$dirty && frm_clearing.receipt_no.$error.unique">
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

                        							<?php if ($user_type == 'leasing'): ?>
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
					                                                            ng-click = "get_internalUnclearedPayment(dirty.value)"><i class = "fa fa-search"></i>
					                                                        </button>
					                                                    </span>
					                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
					                                <?php else: ?>
	                        							<div class="row">
					                                        <div class="form-group">
					                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
					                                            <div class="col-md-7">
					                                                <div class="input-group">
					                                                    <div mass-autocomplete>
					                                                        <input
					                                                            required
					                                                            name = "trade_name"
					                                                            ng-model = "trade_name"
					                                                            class = "form-control"
					                                                            readonly=""
					                                                            id = "trade_name">
					                                                    </div>
					                                                    <span class="input-group-btn">
					                                                        <!-- <button
					                                                            class = "btn btn-info"
					                                                            type = "button"
					                                                            ng-click = "get_internalUnclearedPayment(dirty.value)"><i class = "fa fa-search"></i>
					                                                        </button> -->

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
					                              	<?php endif; ?>

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
				                                            <label for="account_num" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Tender Type</label>
				                                            <div class="col-md-7">
							                                   	<select
							                                   		name="tender_type"
							                                   		ng-model = "tender_type"
							                                   		id="tender_type"
							                                   		required
							                                   		class="form-control">
							                                   		<option value="" disabled="" selected="" style = "display:none">Please Select One</option>
							                                   		<!-- <option>Cash</option> -->
							                                   		<option>Check</option>
							                                   		<!-- <option>Bank to Bank</option> -->
							                                   		<option>JV payment - Business Unit</option>
							                                   		<option>JV payment - Subsidiary</option>

							                                   	</select>

				                                            </div>
				                                        </div>
				                                    </div>
                        						</div>
                        						<div class="col-md-6">

                        							<div class="row" >
				                                        <div class="form-group">
				                                            <label for="deposit_slip" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Deposit Slip/Check</label>
				                                            <div class="col-md-7">
						                                        <input
							                                        type = "file"
							                                        ng-disabled = "tender_type == 'Cash' || !tender_type"
							                                        ng-required = "tender_type != 'Cash'"
							                                        id="deposit_slip"
							                                        name = "deposit_slip"
							                                        accept="image/*"
							                                        required
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="account_num" class="col-md-5 control-label text-right">
				                                            	<i class="fa fa-asterisk" ng-if="tender_type == 'Check'"></i> Bank Code
				                                            </label>
				                                            <div class="col-md-7">
							                                   	<select
							                                   		name="bank_code"
							                                   		ng-model = "code.bank_code"
							                                   		ng-required="tender_type == 'Check'"
							                                   		ng-disabled="tender_type != 'Check'"
							                                   		id="bank_code"
							                                   		class="form-control"
							                                   		ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_transaction/get_bankName/' + code.bank_code)">
							                                   		<option ng-repeat = "code in storeBankCode">{{code.bank_code}}</option>

							                                   	</select>

				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="account_num" class="col-md-5 control-label text-right">
				                                            	<i class="fa fa-asterisk" ng-if="tender_type == 'Check'"></i> Bank Name
				                                            </label>
				                                            <div class="col-md-7">
							                                   	<select name="bank_name" 
							                                   		id="bank_name" 
							                                   		class="form-control"
							                                   		ng-required="tender_type == 'Check'"
							                                   		ng-disabled="tender_type != 'Check'">
							                                   		<option ng-repeat = "data in itemList">{{data.bank_name}}</option>
							                                   	</select>
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="check_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Posting Date</label>
				                                            <div class="col-md-7">
						                                        <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-min-limit="" date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="posting_date"
                                                                            id="posting_date"
                                                                            name = "posting_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                </div>
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
	                                                                    ui-number-mask="2"
	                                                                    class="form-control currency"
	                                                                    ng-model="amount_paid"
	                                                                    id="amount_paid"
	                                                                    name = "amount_paid"
	                                                                    autocomplete="off">
	                                                            </div>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                                <div class="row">
	                                                    <div class="form-group">
	                                                        <label for="amount_paid" class="col-md-5 control-label text-right">Remarks</label>
	                                                        <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    ng-model="remarks"
                                                                    id="remarks"
                                                                    name = "remarks"
                                                                    autocomplete="off">
	                                                        </div>
	                                                    </div>
	                                                </div>
                        						</div>

                        						
                        					</div>

                        					<div class="row" ng-if="tender_type == 'Check'">
                        						<div class="row">
                        							<div class="col-md-6">
                    									<label class="col-md-5 control-label text-right" style="font-size: 12px;"> CHECK DETAILS:</label>
                    								</div>
                    							</div>
                        						<div class="col-md-6">

                        							<?php if($user_type == 'cfs'): ?>
                        							
	                        							<div class="row">
					                                        <div class="form-group">
					                                            <label for="account_no" class="col-md-5 control-label text-right"><i ng-if="tender_type == 'Check'" class="fa fa-asterisk"></i>Account No.</label>
					                                            <div class="col-md-7">
								                                    <input
								                                        type = "text"
								                                        ng-disabled = "tender_type != 'Check' && tender_type != 'Bank to Bank'"
								                                        ng-required = "tender_type == 'Check' || tender_type == 'Bank to Bank'"
								                                        ng-model = "account_no"
								                                        id = "account_no"
								                                        required
								                                        name = "account_no"
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>

					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="account_name" class="col-md-5 control-label text-right"><i ng-if="tender_type == 'Check'" class="fa fa-asterisk"></i>Account Name</label>
					                                            <div class="col-md-7">
								                                    <input
								                                        type = "text"
								                                        ng-disabled = "tender_type != 'Check' && tender_type != 'Bank to Bank'"
								                                        ng-required = "tender_type == 'Check' || tender_type == 'Bank to Bank'"
								                                        ng-model = "account_name"
								                                        id = "account_name"
								                                        required
								                                        name = "account_name"
								                                        class = "form-control">
					                                            </div>
					                                        </div>
					                                    </div>
					                                <?php endif; ?>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="ds_no" class="col-md-5 control-label text-right"><i ng-if="tender_type == 'Check'" class="fa fa-asterisk"></i>DS No./Check No.</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        ng-disabled = "tender_type != 'Check' && tender_type != 'Bank to Bank'"
							                                        ng-required = "tender_type == 'Check' || tender_type == 'Bank to Bank'"
							                                        ng-model = "ds_no"
							                                        id = "ds_no"
							                                        required
							                                        name = "ds_no"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>
				                                    

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="check_date" class="col-md-5 control-label text-right"><i ng-if="tender_type == 'Check'" class="fa fa-asterisk"></i>Check Date</label>
				                                            <div class="col-md-7">
						                                        <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker  date-format="yyyy-M-dd"> 
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            ng-disabled = "tender_type != 'Check'"
						                                        			ng-required = "tender_type == 'Check'"
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

				                                    <?php if($user_type == 'cfs'): ?>

					                                    <div class="row">
					                                        <div class="form-group">
					                                            <label for="expiry_date" class="col-md-5 control-label text-right">Expiry Date</label>
					                                            <div class="col-md-7">
							                                        <div class="input-group">
	                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
	                                                                    <datepicker  date-format="yyyy-M-dd"> 
	                                                                        <input
	                                                                            type="text"
	                                                                            ng-disabled = "tender_type != 'Check'"
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
					                                <?php endif; ?>
				                                </div>

				                                <?php if($user_type == 'cfs'): ?>

					                                <div class="col-md-6">

					                                    <div class="row">
							                                        <div class="form-group">
							                                            <label for="bank" class="col-md-5 control-label text-right">Check Class</label>
							                                            <div class="col-md-7">
									                                        <select
									                                        	id = "check_class"
									                                        	ng-model = "check_class"
									                                        	name = "check_class"
									                                        	class="form-control"
									                                        	ng-disabled = "tender_type != 'Check'">
									                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
									                                        	<option></option>
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
					                                            <label for="bank" class="col-md-5 control-label text-right">Check Category</label>
					                                            <div class="col-md-7">
							                                        <select
							                                        	id = "check_category"
							                                        	ng-model = "check_category"
							                                        	name = "check_category"
							                                        	class="form-control"
							                                        	ng-disabled = "tender_type != 'Check'">
							                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
							                                        	<option></option>
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
		                                                        <label for="amount_paid" class="col-md-5 control-label text-right"><i ng-if="tender_type == 'Check'" class="fa fa-asterisk"></i>Customer Name</label>
		                                                        <div class="col-md-7">
	                                                                <input
	                                                                    type="text"
	                                                                    class="form-control"
	                                                                    ng-model="customer_name"
	                                                                    id="customer_name"
	                                                                    name = "customer_name"
	                                                                    autocomplete="off"
	                                                                    ng-disabled = "tender_type != 'Check'"
									                                    ng-required = "tender_type == 'Check'">
		                                                        </div>
		                                                    </div>
		                                                </div>

		                                                <div class="row">
					                                        <div class="form-group">
					                                            <label for="bank" class="col-md-5 control-label text-right">Check Bank</label>
					                                            <div class="col-md-7">
							                                        <select
							                                        	id = "check_bank"
							                                        	ng-model = "bank.bankbranchname"
							                                        	name = "check_bank"
							                                        	class="form-control"
							                                        	ng-disabled = "tender_type != 'Check'">
							                                        	<option value="" disabled="" selected="" style = "display:none">Please Select One if Check</option>
							                                        	<option></option>
							                                        	<option ng-repeat="bank in ccm_banks" value="{{ bank.bank_id }}">{{ bank.bankbranchname }}</option>
							                                        </select>
					                                            </div>
					                                        </div>
					                                    </div>
					                                    
	                        						</div>
	                        					<?php endif; ?>
                        						
                        					</div>
                        				</div>
                        			</div>

                        			<div class="row">
                        				<div class="col-md-12">
                        					<table class="table table-bordered" id="payment_table">
	                                            <thead>
	                                                <tr>
	                                                	<th style = "display:none">ID</th>
	                                                    <th>Doc. Type</th>
	                                                    <th>Ref. No.</th>
	                                                    <th>Document No.</th>
	                                                    <th>Description</th>
	                                                    <th>Status</th>
	                                                    <th>Posting Date</th>
	                                                    <th class="currency">Amount</th>
	                                                    <th> 
	                                                    	<div align="center" width="5%">
	                                                    		<input type="checkbox" 
	                                                    			style="zoom: 1.5; margin: 0px; padding: 0px;" 
	                                                    			title="Select All" 
	                                                    			ng-model = "selectAll(unclearedPayments)"
	                                                    			ng-model-options="{ getterSetter: true }">
	                                                    	</div>
	                                                    </th>
	                                                </tr>
	                                            </thead>
	                                            <tbody id="payment_tbody">
	                                            	<tr ng-repeat = "data in unclearedPayments" ng-if = "data.amount > 0" class="{{data.selected ? 'bg-success' : ''}}">
	                                            		<td style = "display:none">
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][id]" ng-value = "data.id" />
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][ft_ref]" ng-value = "data.ft_ref" />
	                                            		</td>
	                                            		<td>
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][doc_type]" ng-value = "data.document_type" /> {{data.document_type}}
	                                            		</td>
	                                            		<td>
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][ref_no]" ng-value = "data.ref_no" /> {{data.ref_no}}
	                                            		</td>
	                                            		<td>
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][doc_no]" ng-value = "data.doc_no" /> {{data.doc_no}}
	                                            		</td>
	                                            		<td>
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][gl_accountID]" ng-value = "data.gl_accountID" />
	                                            			<span >{{data.gl_accountID == 4 ? 'Basic' : (data.gl_accountID == 7 ? 'Advance' : 'Other')}} - {{trade_name}}</span>
	                                            		</td>
	                                            		<td>
	                                            			<span>{{data.status2}} - {{data.status}}</span>
	                                            		</td>
	                                            		<td>
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][posting_date]" ng-value = "data.posting_date" /> {{data.posting_date}}
	                                            		</td>
	                                            		<td class="currency">
	                                            			<input ng-if="data.selected" type = "hidden" name = "ips[{{$index}}][amount]" ng-value = "data.amount" /> {{data.amount | currency : ''}}
	                                            		</td>
	                                            		<td align="center" width="5%">

	                                            			<input type="checkbox" ng-model="data.selected" style="zoom: 1.5; margin: 0px; padding: 0px;">
	                                            			<!-- <button class = "btn btn-sm btn-danger" ng-click = "get_totalUncleared(data.amount)" onClick="deletefromPayment(this)" style="padding: 0px 5px;"><i class = "fa fa-trash"></i></button> -->
	                                            		</td>
	                                            	</tr>
	                                            </tbody>
	                                            <tbody ng-show = "isLoading">
	                                            	<tr>
					                                    <td colspan="8">
					                                    	<div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
					                                    	<div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
					                                    </td>
					                                </tr>
	                                            </tbody>
	                                        </table>
	                                        <div class="row" ng-if="unclearedPayments">
	                                        	<div class="col-md-12">
	                                        		<div class="pull-right"><b>Total Amount: <u>{{selectedIPTotal(unclearedPayments) | currency : ''}}</u></b> 
	                                        			<input type = "hidden" name = "total_payable" ng-value="selectedIPTotal(unclearedPayments)" />
	                                        		</div>
	                                        	</div>
	                                        </div>
                        				</div>
                        			</div>
                        			<div class="row">
	                                	<div class="col-md-12">
	                                		<button role = "submit" 
		                                		ng-disabled="
		                                			frm_clearing.$invalid || 
		                                			selectedIPTotal(unclearedPayments) == 0 || 
		                                			amount_paid == 0 || amount_paid  == '' || 
		                                			toNumber(toNumber(amount_paid).toFixed(2)) > toNumber(selectedIPTotal(unclearedPayments).toFixed(2))"
		                                			style="width: 200px;" 

		                                		class="btn btn-large btn-primary pull-right">
	                                			<i class="fa fa-save"></i> Submit
	                                		</button>
	                                	</div>
	                                </div>
                        		</div>
                        	</form>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>

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
	                                            <button type="button" style="width:70% !important" ng-click = "get_internalUnclearedPayment(data.trade_name); " class="btn btn-xs btn-primary" data-dismiss = "modal">Select</button>
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
</div>



<script>
	/*$(document).on('keypress', 'input,select', function (e) {
	    if (e.which == 13) {
	        e.preventDefault();
	        var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
	        console.log($next.length);
	        if (!$next.length) {
	            $next = $('[tabIndex=1]');
	        }
	        $next.focus();
	    }
	});*/


function closeInternalPayment(url) {
    var formData = new FormData($('form#frm_clearing')[0]);
    $.ajax({
        type:'POST',
        url: url,
        data: formData,
        enctype: 'multipart/form-data',
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#spinner_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        success: function(res)
        {
        	$('#spinner_modal').modal('toggle');

        	console.log(res);

            if(res.type == 'success'){
            	window.open(res.file_dir);
                location.reload();
            }
            else{
            	generate('error', res.msg);
            }
        }
    });
}
</script>	
