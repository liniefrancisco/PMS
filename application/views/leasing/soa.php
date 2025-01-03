
<div class="container" id = "transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Statement of Account</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">
                                    <div class="row">
                                        <form action="" onsubmit="save_soa('<?php echo base_url(); ?>index.php/leasing_transaction/save_soa/'); return false;"  method="post" id="frm_soa" name = "frm_soa">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
                                                            <div class="col-md-7">
                                                                <select
                                                                    class = "form-control"
                                                                    name = "tenancy_type"
                                                                    id="tenancy_type"
                                                                    ng-model = "tenancy_type"
                                                                    ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type); clear_soaForm()"
                                                                    required
                                                                    onchange="clear_soaForm()">
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
                                                                            ng-click = "generate_soaCredentials(dirty.value);"><i class = "fa fa-search"></i>
                                                                        </button>
                                                                    </span>
                                                                </div><!-- /input-group -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contract_no" class="col-md-5 control-label text-right">Contract No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "contract_no"
                                                                    id = "contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-5 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "tenant_id"
                                                                    id = "tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete = "off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_address" class="col-md-5 control-label text-right">Tenant Address</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "tenant_address"
                                                                    id = "tenant_address"
                                                                    name = "tenant_address"
                                                                    autocomplete = "off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF COL-MD-6 WRAPPER -->
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="soa_no" class="col-md-5 control-label text-right">SOA No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    required
                                                                    readonly
                                                                    class = "form-control"
                                                                    ng-model = "soa_no"
                                                                    id = "soa_no"
                                                                    name = "soa_no"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right" ng-init = "b_period='Upon Signing of Notice'"></label>
                                                            <input type = "radio" name = "radio" id = "radio1" checked="checked" ng-model = "b_period" value="Upon Signing of Notice" required/><label> Upon Signing of Notice </label>
                                                            <input type = "radio" name = "radio" id = "radio2" ng-model = "b_period" value="Input Billing Period" /> <label>Input Billing Period</label>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-if = "b_period == 'Input Billing Period'">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type = "text"
                                                                    required
                                                                    class = "form-control"
                                                                    ng-model = "billing_period"
                                                                    id = "billing_period"
                                                                    name = "billing_period"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-if = "b_period == 'Upon Signing of Notice'">
                                                        <div class="form-group">
                                                            <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    readonly
                                                                    type = "text"
                                                                    class = "form-control"
                                                                    value = "Upon Signing of Notice"
                                                                    id = "billing_period"
                                                                    name = "billing_period"
                                                                    autocomplete = "off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="curr_date" class="col-md-5 control-label text-right">Date Created</label>
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
                                                            <label for="collection_date" class="col-md-5 control-label text-right">Collection Date</label>
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
                                                                            ng-model="collection_date"
                                                                            id="collection_date"
                                                                            name = "collection_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_soa.collection_date.$dirty && frm_soa.collection_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Total Amount Due</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        class="form-control currency"
                                                                        ui-number-mask="2"
                                                                        ng-model="total_amountDue"
                                                                        readonly
                                                                        id="totalAmount"
                                                                        name = "totalAmount"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.total.$dirty && frm_charges.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="row">
                                                <table class="table table-bordered" id="soa_table" style="margin-left:50px">
                                                    <thead>
                                                        <tr>
                                                            <th ><a href="#" data-ng-click="sortField = 'doc_type'; reverse = !reverse">Doc. Type</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'doc_no'; reverse = !reverse">Document No.</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'desc'; reverse = !reverse">Description</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Posting Date</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'due_date'; reverse = !reverse">Due Date</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'actual_amt'; reverse = !reverse">Amount</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'amount_due'; reverse = !reverse">Amount Paid</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'balance'; reverse = !reverse">Balance</a></th>
                                                            <th >Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="soa_tbody" ng-show = "!isLoading">
                                                        <tr ng-repeat = "data in invoiceBasic">
                                                            <td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Invoice</td>
                                                            <td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
                                                            <td><input type = "text" style = "display:none" name = "desc[]" value="<?php echo "Basic-"; ?>{{trade_name}}" /> Basic-{{trade_name}}</td>
                                                            <td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "amount[]" ng-model = "data.actual_amt"  /> {{data.actual_amt | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "balance[]" ng-model = "data.balance"  /> {{data.balance | currency : '&#8369;'}}</td>
                                                            <td><a class = "btn-sm btn-danger" ng-click = "getTotalSOA(data.balance)" onClick="deleteFormSOA(this)"  href = "javascript:void(0)"><i class = "fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <tr ng-repeat = "data in invoiceOther" ng-if="data.balance">
                                                            <td><input type = "text" style = "display:none" name = "doc_type[]" value="" /> Invoice</td>
                                                            <td><input type = "text" style = "display:none" name = "doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
                                                            <td><input type = "text" style = "display:none" name = "desc[]"  value="{{data.description}}-{{trade_name}}"/> Other-{{trade_name}}</td>
                                                            <td><input type = "text" style = "display:none" name = "posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "amount[]" ng-model = "data.actual_amt"  /> {{data.actual_amt | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "balance[]" ng-model = "data.balance"  /> {{data.balance | currency : '&#8369;'}}</td>
                                                            <td><a class = "btn-sm btn-danger" ng-click = "getTotalSOA(data.balance)" onClick="deleteFormSOA(this)"  href = "javascript:void(0)"><i class = "fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <tr ng-repeat = "data in invoicePenalty" ng-if="data.balance">
                                                            <td>Invoice</td>
                                                            <td>{{data.doc_no}}</td>
                                                            <td>{{data.description}}</td>
                                                            <td>{{data.posting_date}}</td>
                                                            <td>{{data.due_date}}</td>
                                                            <td>{{data.begbal | currency : '&#8369;' }}</td>
                                                            <td>{{data.amount_paid | currency : '&#8369;' }}</td>
                                                            <td>{{data.balance | currency : '&#8369;'}}</td>
                                                            <td><a class = "btn-sm btn-danger" ng-click = "getTotalSOA(data.balance)" onClick="deleteFormSOA(this)"  href = "javascript:void(0)"><i class = "fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <tr ng-repeat = "data in preopCharges">
                                                            <td><input type = "text" style = "display:none" name = "preop_doc_type[]" value="" /> Invoice</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_desc[]"  value="{{data.description}}-{{trade_name}}"/> {{data.description}}-{{trade_name}}</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_amount[]" ng-model = "data.amount"  /> {{data.amount | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_amount_paid[]"   /> &#8369; 0.00</td>
                                                            <td><input type = "text" style = "display:none" name = "preop_balance[]" ng-model = "data.amount"  /> {{data.amount | currency : '&#8369;'}}</td>
                                                            <td><a class = "btn-sm btn-danger" ng-click = "getTotalSOA(data.balance)" onClick="deleteFormSOA(this)"  href = "javascript:void(0)"><i class = "fa fa-trash"></i></a></td>
                                                        </tr>
                                                        <tr ng-repeat = "data in retroCharges">
                                                            <td><input type = "text" style = "display:none" name = "retro_doc_type[]" value="" /> Invoice</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_doc_no[]" ng-model = "data.doc_no" /> {{data.doc_no}}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_desc[]"  value="{{data.description}}-{{trade_name}}"/>{{data.description}}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_posting_date[]" ng-model = "data.posting_date"  /> {{data.posting_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_due_date[]" ng-model = "data.due_date"  /> {{data.due_date}}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_amount[]" ng-model = "data.actual_amt"  /> {{data.actual_amt | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_amount_paid[]" ng-model = "data.amount_paid"  /> {{data.amount_paid | currency : '&#8369;' }}</td>
                                                            <td><input type = "text" style = "display:none" name = "retro_balance[]" ng-model = "data.balance"  /> {{data.balance | currency : '&#8369;'}}</td>
                                                            <td><a class = "btn-sm btn-danger" ng-click = "getTotalSOA(data.balance)" onClick="deleteFormSOA(this)"  href = "javascript:void(0)"><i class = "fa fa-trash"></i></a></td>
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
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "submit" ng-disabled = "frm_soa.$invalid" class = "btn btn-primary btn-medium button-b"><i class = "fa fa-save"></i> Generate SOA</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
