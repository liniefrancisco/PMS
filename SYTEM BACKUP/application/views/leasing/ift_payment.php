<div class="container" ng-controller = "transactionController">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing"><i class="fa fa-registered"></i> Identified Fund Transfer/OTC Payments</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<ul class="nav nav-tabs bot-margin" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                        	<form action="#" onsubmit="save_reg_fundTransfer(); return false" method="post" name = "frm_clearing" id = "frm_clearing">
                        		<div role="tabpanel" class="tab-pane active" id="rr_clearing">
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
				                                                            ng-click = "get_unclearedPayment(dirty.value)"><i class = "fa fa-search"></i>
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

                        						<div class="col-md-6">
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="account_num" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Bank Code</label>
				                                            <div class="col-md-7">
							                                   	<select
							                                   		name="bank_code"
							                                   		ng-model = "code.bank_code"
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
				                                            <label for="account_num" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Bank Name</label>
				                                            <div class="col-md-7">
							                                   	<select name="bank_name" id="bank_name" class="form-control">
							                                   		<option ng-repeat = "data in itemList">{{data.bank_name}}</option>
							                                   	</select>
				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row" >
				                                        <div class="form-group">
				                                            <label for="deposit_slip" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Deposit Slip</label>
				                                            <div class="col-md-7">
						                                        <input
							                                        type = "file"
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
				                                            <label for="ds_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Deposit Slip No.</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
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
                        						</div>
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
	                                            		<td ng-if="data.selected" style="display: none;">
	                                            			<input type = "hidden" name = "ufts[{{$index}}][id]" ng-value = "data.id"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][document_type]" ng-value = "data.document_type"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][doc_no]" ng-value = "data.doc_no"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][ref_no]" ng-value = "data.ref_no"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][ft_ref]" ng-value = "data.ft_ref"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][gl_accountID]" ng-value = "data.gl_accountID"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][status]" ng-value = "data.status"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][posting_date]" ng-value = "data.posting_date"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][due_date]" ng-value = "data.due_date"/>
	                                            			<input type = "hidden" name = "ufts[{{$index}}][amount]" ng-value = "data.amount"/>
	                                            		</td>
	                                            
	                                            		<td>{{data.document_type}}</td>
	                                            		<td>{{data.ref_no}}</td>
	                                            		<td>{{data.doc_no}}</td>
	                                            		<td>
	                                            			<span ng-if = "data.status == 'RR Clearing'">Basic-{{trade_name}}</span> 
	                                            			<span  ng-if="data.status != 'RR Clearing'">Other-{{trade_name}}</span>
	                                            		</td>
	                                            		<td>{{data.posting_date}}</td>
	                                            		<td class="currency">{{data.amount | currency : ''}}</td>
	                                            		<td align="center" width="5%">
	                                            			<input style="zoom: 1.5" type="checkbox" ng-model="data.selected">
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
	                                		<button role = "submit" ng-disabled = "frm_clearing.$invalid" class="btn btn-large btn-primary col-md-1 col-md-offset-10"><i class="fa fa-save"></i> Submit</button>
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
</div>
