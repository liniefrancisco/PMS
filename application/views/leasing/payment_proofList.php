
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Payment Proof List</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment_proofList">
                            	<div class="row">
                                	<form action="" onsubmit="generate_paymentProofList('<?php echo base_url(); ?>index.php/leasing_reports/generate_paymentProofList/'); return false;"  method="post" name="frm_paymentProofList" id="frm_paymentProofList">
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
				                                                            id = "tenant_id">
				                                                    </div>
				                                                    <span class="input-group-btn">
				                                                        <button
				                                                            class = "btn btn-info"
				                                                            type = "button"
				                                                            ng-click = "get_paymentProofList(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
				                                                        </button>
				                                                    </span>
				                                                </div>
				                                            </div>
				                                        </div>
				                                    </div>
				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        required
							                                        ng-model = "tenant_id"
							                                        id="tenant_id"
							                                        name = "tenant_id"
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
							                                        required
							                                        id="address"
							                                        name = "address"
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
							                                        required
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
                                                            <label for="from" class="col-md-5 control-label text-right"><i class = "fa fa-asterisk"></i>From </label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                        <datepicker  date-format="yyyy-M-dd">
                                                                            <input
                                                                                type="text"
                                                                                required
                                                                                placeholder="Choose a date"
                                                                                class="form-control"
                                                                                ng-model="from"
                                                                                id="from"
                                                                                name = "from"
                                                                                autocomplete="off">
                                                                        </datepicker>
                                                                        <!-- FOR ERRORS -->
                                                                        <div class="validation-Error">
                                                                            <span ng-show="frm_paymentProofList.from.$dirty && frm_paymentProofList.from.$error.required">
                                                                                <p class="error-display">This field is required.</p>
                                                                            </span>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="to" class="col-md-5 control-label text-right"><i class = "fa fa-asterisk"></i>To </label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                        <datepicker  date-format="yyyy-M-dd">
                                                                            <input
                                                                                type="text"
                                                                                required
                                                                                placeholder="Choose a date"
                                                                                class="form-control"
                                                                                ng-model="to"
                                                                                id="to"
                                                                                name = "to"
                                                                                autocomplete="off">
                                                                        </datepicker>
                                                                        <!-- FOR ERRORS -->
                                                                        <div class="validation-Error">
                                                                            <span ng-show="frm_paymentProofList.to.$dirty && frm_paymentProofList.to.$error.required">
                                                                                <p class="error-display">This field is required.</p>
                                                                            </span>
                                                                        </div>
                                                                </div>
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
				                                            <label for="tenant_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant Type</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        required
							                                        ng-model = "tenant_type"
							                                        id = "tenant_type"
							                                        name = "tenant_type"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>

				                                    <div class="row">
				                                        <div class="form-group">
				                                            <label for="date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Date</label>
				                                            <div class="col-md-7">
							                                    <input
							                                        type = "text"
							                                        readonly
							                                        value="<?php echo date('Y-m-d'); ?>"
							                                        id="date"
							                                        name = "date"
							                                        class = "form-control">
				                                            </div>
				                                        </div>
				                                    </div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-center">
													<button type = "submit" ng-disabled = "frm_paymentProofList.$invalid" class = "btn btn-primary btn-lg button-vl"><i class = "fa fa-doc"></i> Generate Report</button>
												</div>
											</div>
		                                </div>
		                                
                                	</form>
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
