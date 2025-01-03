
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Credit Memo</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment">
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
			                                                            ng-click = "get_invforCreditMemo(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
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
                                                                readonly 
                                                                ng-model = "contract_no" 
                                                                id="contract_no" 
                                                                name = "contract_no" 
                                                                class = "form-control">
                                                        </div>
                                                    </div>
                                                </div>

											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="transaction_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Transaction No.</label>
                                                        <div class="col-md-7">  
                                                            <input 
                                                                type = "text"
                                                                readonly 
                                                                value="<?php echo $transaction_no; ?>" 
                                                                id="transaction_no" 
                                                                name = "transaction_no" 
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
											</div>
										</div>
	                                </div>
                                </div>
                                <div class="row"> <!-- table wrapper  --> 
                                	<table class="table table-bordered" id="creditmemo_table">
                                		<thead>
                                            <tr>
                                                <th ><a href="#" data-ng-click="sortField = 'id'; reverse = !reverse">Entry No.</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'doc_type'; reverse = !reverse">Doc. Type</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'doc_no'; reverse = !reverse">Document No.</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'desc'; reverse = !reverse">Description</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Posting Date</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'due_date'; reverse = !reverse">Due Date</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'total'; reverse = !reverse">Total Amount Due</a></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id = "creditmemo_tbody">
                                        	<tr ng-repeat = "data in basicData">
                                                <td>{{data.id}}</td>
                                        		<td>Invoice</td>
                                        		<td>{{data.doc_no}}</td>
                                        		<td>{{data.description}}</td>
                                        		<td>{{data.posting_date}}</td>
                                        		<td>{{data.due_date}}</td>
                                        		<td align="right">{{data.balance | currency : '&#8369;'}}</td>
                                        		<td>
                                        			<button type="button" ng-click = "apply_CreditMemo(data.id, data.description, data.balance)" class="btn btn-xs btn-danger">Apply Credit</button>
                                        		</td>
                                        	</tr>
                                        </tbody>
                                        <tbody ng-show = "isLoading">
                                            <tr>
                                                <td colspan="10">
                                                    <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                    <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tr class="ng-cloak" ng-show="!basicData.length && !isLoading">
                                            <td colspan="10"><center>No Data Available.</center></td>
                                        </tr>
                                	</table>
                                </div>				
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->






    <!-- Other's Credit Memo Modal -->
    <div class="modal fade" id = "modal_creditMemo" style="top: 70px">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-credit-card"></i> Credit Amount</h4>
                </div>
                <form action = "<?php echo base_url(); ?>index.php/leasing_transaction/other_creditMemo/" method="post" name = "frm_creditMemo" id = "frm_creditMemo">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><b>&#8369;</b></div>
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control currency" 
                                                    ng-model="credit_amount"
                                                    id="credit_amount"
                                                    max = "max_credit"
                                                    ui-number-mask="2"
                                                    name = "credit_amount"
                                                    autocomplete="off" >
                                            </div>

                                            <input type = "text" style = "display:none" ng-model = "ledger_id" name = "ledger_id" id = "ledger_id" />
                                             <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_creditMemo.credit_amount.$dirty && frm_creditMemo.credit_amount.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                 <span ng-show="frm_creditMemo.credit_amount.$dirty && frm_creditMemo.credit_amount.$error.max">
                                                    <p class="error-display">Credit amount must be less or equal to amount due.</p>
                                                </span>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
               
                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_creditMemo.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Other's Credit Memo Modal -->



    <!-- Basic Credit Memo Modal -->
    <div class="modal fade" id = "basic_creditMemo_modal" style="top: 70px">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-credit-card"></i> Credit Amount</h4>
                </div>
                <form action = "<?php echo base_url(); ?>index.php/leasing_transaction/basic_creditMemo/" method="post" name = "frm_creditMemo" id = "frm_creditMemo">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><b>&#8369;</b></div>
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control currency" 
                                                    ng-model="credit_amount"
                                                    id="credit_amount"
                                                    max = "max_credit"
                                                    ui-number-mask="2"
                                                    name = "credit_amount"
                                                    autocomplete="off" >
                                            </div>

                                            <input type = "text" style = "display:none" ng-model ="ledger_id" name = "ledger_id" id = "ledger_id" />
                                             <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_creditMemo.credit_amount.$dirty && frm_creditMemo.credit_amount.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                 <span ng-show="frm_creditMemo.credit_amount.$dirty && frm_creditMemo.credit_amount.$error.max">
                                                    <p class="error-display">Credit amount must be less or equal to amount due.</p>
                                                </span>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
               
                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_creditMemo.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Other's Credit Memo Modal -->



</div> <!-- End of Container -->
</body>