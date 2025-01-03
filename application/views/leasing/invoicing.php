  
  
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Billing</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General</a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">
                                    <div class="row">
                                        <form action="#"  method="post" id="frm_invoice" name = "frm_charges">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="doc_no" class="col-md-5 control-label text-right">Document No.</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $doc_no; ?>"
                                                                    id="doc_no"
                                                                    name = "doc_no"
                                                                    autocomplete="off">
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
                                                                    ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type); clear_invocingData()"
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
                                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div mass-autocomplete>
                                                                        <input
                                                                            required
                                                                            name = "trade_name"
                                                                            ng-model="dirty.value"
                                                                            mass-autocomplete-item="autocomplete_options"
                                                                            class = "form-control"
                                                                            id = "trade_name">
                                                                    </div>
                                                                    <span class="input-group-btn">
                                                                        <button
                                                                            class="btn btn-info"
                                                                            type="button"
                                                                            ng-click = "generate_invoicingCredentials(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
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
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model="contract_no"
                                                                    id="contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.contract_no.$dirty && frm_charges.contract_no.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
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
                                                                    class="form-control"
                                                                    ng-model="tenant_id"
                                                                    id="tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.tenant_id.$dirty && frm_charges.tenant_id.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF COL-MD-6 WRAPPER -->
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="rental_type" class="col-md-5 control-label text-right">Rental Type</label>
                                                            <div class="col-md-7">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model="rental_type"
                                                                    id="rental_type"
                                                                    name = "rental_type"
                                                                    autocomplete="off" >
                                                                    <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_charges.rental_type.$dirty && frm_charges.rental_type.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="date" class="col-md-5 control-label text-right">Transaction Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        readonly
                                                                        class="form-control"
                                                                        value="<?php echo $current_date; ?>"
                                                                        id="transaction_date"
                                                                        name = "transaction_date"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="posting_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Posting Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd" >
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            ng-change = "calculate_postingDate(posting_date)"
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="posting_date"
                                                                            id="posting_date"
                                                                            name = "posting_date"
                                                                            autocomplete="off">
                                                                     </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_charges.posting_date.$dirty && frm_charges.posting_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="due_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Due Date</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd" date-min-limit="{{min_dueDate}}">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="due_date"
                                                                            id="due_date"
                                                                            name = "due_date"
                                                                            autocomplete="off">
                                                                     </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_charges.due_date.$dirty && frm_charges.due_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Total</label>
                                                            <div class="col-md-7">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ng-model="total"
                                                                        readonly
                                                                        ui-number-mask="2"
                                                                        id="total_amount"
                                                                        name = "total"
                                                                        autocomplete="off">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!--//   Hidden Total Gross Sales for rent percentage related Tenants   //-->
                                                    <input type = "text" style = "display:none" ui-number-mask="2" ng-model = "total_gross" name = "total_gross" id = "total_gross" />
                                                    <input type = "text" style = "display:none"  name = "rental_increment" id = "rental_increment" ng-model = "increment_percentage" />

                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="row"> <!-- EDITABLE GRID ROW -->
                                                <div class = "row text-center" style="margin-left:50px" >
                                                    <a
                                                        ng-if = "dirty.value && contract_no != ''"
                                                        href="#"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "datafor_basicRent(tenant_primaryKey, tenant_id, is_incrementable, tenant_type, vat_agreement);openBasic(tenancy_type, tenant_type, opening_date)"
                                                        ><i class  = "fa fa-plus-circle"></i> Basic Rental
                                                    </a>
                                                    <a
                                                        ng-if = "dirty.value && contract_no != ''"
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false"
                                                        data-toggle="modal"
                                                        data-target="#basic_manual"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = ""
                                                        ><i class  = "fa fa-plus-circle"></i> Basic Rental(Manual Input)
                                                    </a>
                                                    <a
                                                    <a
                                                        ng-if = "dirty.value && tenancy_type == 'Long Term Tenant' && contract_no != ''"
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false"
                                                        data-toggle="modal"
                                                        data-target="#preop_charges"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_preopCharges('<?php echo base_url(); ?>index.php/leasing_transaction/get_preopCharges/')"><i class  = "fa fa-plus-circle"></i> Pre Operation Charges
                                                    </a>
                                                    <a
                                                        ng-if = "dirty.value && tenancy_type == 'Long Term Tenant' && contract_no != ''"
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false"
                                                        data-toggle="modal"
                                                        data-target="#constMat"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_constMat('<?php echo base_url(); ?>index.php/leasing_transaction/get_constMat/')"><i class  = "fa fa-plus-circle"></i> Contruction Materials
                                                    </a>
                                                    <a
                                                        href="#"
                                                        ng-if = "dirty.value && contract_no != ''"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_monthly_charges(tenant_id);openOther(tenancy_type,opening_date)"><i class  = "fa fa-plus-circle"></i> Monthly Charges
                                                    </a>
                                                    <a
                                                        href="#"
                                                        ng-if = "dirty.value && contract_no != ''"
                                                        data-backdrop="static" data-keyboard="false"
                                                        data-toggle="modal"
                                                        data-target="#other_charges"
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_otherCharges('<?php echo base_url(); ?>index.php/leasing_transaction/get_otherCharges/' + tenant_id)"><i class  = "fa fa-plus-circle"></i> Other Charges
                                                    </a>
                                                </div>
                                                <table class="table table-bordered" id="charges_table" style="margin-left:50px">
                                                    <thead>
                                                        <tr>
                                                            <th ><a href="#" data-ng-click="sortField = 'charges_type'; reverse = !reverse">Charges Type</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'charges_code'; reverse = !reverse">Charges Code</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Description</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">UOM</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'unit_price'; reverse = !reverse">Unit Price</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Total Unit</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Actual Amount</a></th>
                                                            <th width="4px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="charges_tbody">
                                                       <tr></tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "button" onclick="check_invoiceTag()" ng-disabled = "frm_charges.$invalid" class = "btn btn-primary btn-medium button-b"><i class = "fa fa-save"></i> Save Invoice</button>
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


<!-- Add Pre Operation Modal -->
<div class="modal fade" id = "preop_charges">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Pre Operation Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id = "charges_wrapper">
                        <div class = "row">
                            <div class="form-group">
                                <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                <div class="col-md-8">
                                    <select required name = "preOp_desc" id="preOp_desc" ng-model = "desc.description" class = "form-control" ng-change = "get_chargeDetails('<?php echo base_url(); ?>index.php/leasing_transaction/chargeDetails/' + desc.description)">
                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                        <option ng-repeat = "desc in preopDesc">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in chargeDetails">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">
                                        <input type = "text" ng-model = "detail.charges_code" id = "preOp_chargeCode" name = "preOp_chargeCode" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>

                                    <div class = "row">
                                        <div class="col-md-2 pull-left">
                                            <input type = "text" readonly ng-model = "detail.uom" id="preOp_uom" name = "preOp_uom" class = "form-control" />
                                        </div>
                                        <span>mo(s).Basic/fixed basic rent</span>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Basic Rental</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            ui-number-mask="2"
                                            ng-model = "basicRental"
                                            id="basic_rental"
                                            name = "basic_rental"
                                            class = "form-control currency"
                                            ng-keyup = "preOp_actualAmt(detail.uom, basicRental)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">
                                        <input type = "text" ui-number-mask="2" ng-model = "preOp_amount" id="preOp_actualAmt" name = "actual_amount" class = "form-control currency" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_preOp_charges" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Pre Operation Modal -->


<!-- Add Basic Rental Modal -->
<div class="modal fade" id = "less_than_30Days_FixedPercentage_tenant">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="row no-padding">
                            <div class="form-group">
                                <label for="less30_FixedPercentage_gross_sales" class="col-md-4 control-label text-right invoice-label">Gross Sales </label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input
                                            type="text"
                                            class="form-control currency"
                                            ng-model = "less30_FixedPercentage_gross_sales"
                                            ui-number-mask="2"
                                            id="less30_FixedPercentage_gross_sales"
                                            name = "less30_FixedPercentage_gross_sales"
                                            autocomplete="off"
                                            ng-keyup="inputted_gross_less30_FixedPercentage(tenant_primaryKey, less30_FixedPercentage_gross_sales, is_incrementable)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">(X 12%) 1.12%</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding">
                            <div class="form-group">
                                <label for="total_gross" class="col-md-4 control-label text-right invoice-label">Total Gross Sales</label>
                                <label ng-if = "less30_FixedPercentage_total_gross" class="col-md-4 pull-right control-label text-right invoice-label"> {{ less30_FixedPercentage_total_gross | currency : '&#8369;' }}</label>
                                <label ng-if = "!less30_FixedPercentage_total_gross" class="col-md-4 pull-right control-label text-right invoice-label">&#8369;0.00</label>
                            </div>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <label for="rent_percentage" class="col-md-4 control-label text-right invoice-label">Rent Percentage</label>
                                <div class="col-md-4"></div>
                                <label class="col-md-4 control-label text-right invoice-label"> {{rent_percentage | currency : '%' }}</label>
                            </div>
                        </div>
                        <div class="row no-padding" >
                            <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type = "text"
                                    style = "display:none"
                                    class = "form-control currency"
                                    readonly
                                    ng-model = "basic_rental"
                                    ui-number-mask="2"
                                    id = "basic_rental"
                                    name = "basic_rental"
                                    autocomplete = "off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ basic_rental | currency : '&#8369;' }}</label>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <label for="num_days" class="col-md-4 control-label text-right invoice-label">Number of Days</label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control currency"
                                            ng-model = "num_days"
                                            ui-number-mask="2"
                                            id="num_days"
                                            name = "num_days"
                                            autocomplete="off"
                                            ng-keyup="calculate_currentRental_fixedPercentage(basic_rental, less30_FixedPercentage_total_gross, rent_percentage, month_days, num_days, tenant_primaryKey)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-8 col-md-offset-4 control-label invoice-label text-right red">({{less30_FixedPercentage_total_gross | currency : '&#8369;'}} * {{rent_percentage | currency : '%' }}) + ({{basic_rental | currency : '&#8369;'}}/{{month_days}} * {{num_days}})</label>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding">
                            <label for="less_30_fixedPercentage_current_rental" class="col-md-4 control-label text-right invoice-total">Current Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "less_30_fixedPercentage_current_rental"
                                    id="less_30_fixedPercentage_current_rental"
                                    name = "less_30_fixedPercentage_current_rental"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total"><u>{{ less_30_fixedPercentage_current_rental | currency : '&#8369;' }}</u></label>
                        </div>

                        <div class="row no-padding">
                            <div class="col-md-12">
                                <div class="row no-padding">
                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                            <div class="col-md-12" style="margin-left:100px;">
                                <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                <input type = "text" style = "display:none" name="less30_fixedPercentage_tenant_type[]"  ng-model = "discount.tenant_type" />
                                <input type = "text" style = "display:none" name="less30_fixedPercentage_discount_type[]"  ng-model = "discount.discount_type" />
                                <input type = "text" style = "display:none"  name="less30_fixedPercentage_discount[]"  ng-model = "discount.discount" />

                                <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="is_vat == 'Added'">
                            <label for="less_vat" class="col-md-4 control-label invoice-label text-right">VAT(+)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "vat"
                                    ui-number-mask="2"
                                    id="less_vat"
                                    name = "less_vat"
                                    autocomplete="off">
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }} ({{ vat_fixedPercentage_less_30Days | currency: '&#8369;'}})</label>
                        </div>
                        <div class="row no-padding">
                            <label for="wht" class="col-md-4 control-label text-right invoice-label">Withholding Tax(-)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "wht"
                                    ui-number-mask="2"
                                    id="wht"
                                    name = "wht"
                                    autocomplete="off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ({{ wht_fixedPercentage_less_30Days | currency: '&#8369;'}})</label>
                        </div>

                        <br>
                        <div class="row no-padding">
                            <label for="basic_fixedPercentage_less_30Days" class="col-md-4 control-label text-right invoice-total">Total Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "basic_fixedPercentage_less_30Days"
                                    id="basic_fixedPercentage_less_30Days"
                                    name = "basic_fixedPercentage_less_30Days"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total"><u>{{ basic_fixedPercentage_less_30Days | currency : '&#8369;' }}</u></label>
                        </div>
                        <div>
                            <input type = "text" style="display:none" ng-model = "vat_fixedPercentage_less_30Days" id = "vat_fixedPercentage_less_30Days" name = "vat_fixedPercentage_less_30Days"  />
                            <input type = "text" style="display:none" ng-model = "wht_fixedPercentage_less_30Days" id = "wht_fixedPercentage_less_30Days" name = "wht_fixedPercentage_less_30Days"  />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_less30_basic_fixedPercentage" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Basic Rental Modal -->


<!-- Add Basic Rental Modal -->
<div class="modal fade" id = "basicRental_modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Gross Sales </label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input
                                            type="text"
                                            class="form-control currency"
                                            ng-model = "gross_sales"
                                            ui-number-mask="2"
                                            id="gross_sales"
                                            name = "gross_sales"
                                            autocomplete="off"
                                            ng-keyup="inputted_gross(tenant_primaryKey, gross_sales, is_incrementable)">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">(X 12%) 1.12%</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="tenant_id == 'ICM-LT000008'">
                            <div class="form-group">
                                <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Tax Exempt </label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input
                                            type="text"
                                            class="form-control currency"
                                            ng-model = "tax_exempt"
                                            ui-number-mask="2"
                                            id="tax_exempt"
                                            name = "tax_exempt"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <label for="total_gross" class="col-md-4 control-label text-right invoice-label">Total Gross Sales</label>
                                <label ng-if = "total_gross" class="col-md-4 pull-right control-label text-right invoice-label"> {{total_gross | currency : '&#8369;' }}</label>
                                <label ng-if = "!total_gross" class="col-md-4 pull-right control-label text-right invoice-label">&#8369;0.00</label>
                            </div>
                        </div>

                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <label for="rent_percentage" class="col-md-4 control-label text-right invoice-label">Rent Percentage</label>
                                <div class="col-md-4"></div>
                                <label class="col-md-4 control-label text-right invoice-label"> {{rent_percentage | currency : '%' }}</label>
                            </div>
                        </div>

                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Fixed'">
                            <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type = "text"
                                    style = "display:none"
                                    class = "form-control currency"
                                    readonly
                                    ng-model = "basic_rental"
                                    ui-number-mask="2"
                                    id = "basic_rental"
                                    name = "basic_rental"
                                    autocomplete = "off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ basic_rental | currency : '&#8369;' }}</label>
                        </div>



                        <div class="row no-padding" ng-if="is_incrementable > 0">
                            <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Rental Increment({{percent_increment}}%)</label>
                            <input type = "text" name = "percent_increment" id = "percent_increment" style = "display:none" ng-model = "percent_increment" />
                            <div class="col-md-4">
                                <input
                                    type = "text"
                                    style = "display:none"
                                    class = "form-control currency"
                                    readonly
                                    ng-model = "increment_value"
                                    ui-number-mask="2"
                                    id = "increment_value"
                                    name = "increment_value"
                                    autocomplete = "off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ increment_value | currency : '&#8369;' }}</label>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Rentable Sales</label>
                                <label ng-if = "rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">{{ rent_sale | currency : '&#8369;' }}</label>
                                <input type = "text" style = "display:none" name = "rent_sale" id = "rent_sale" ng-model = "rent_sale" />
                                <label ng-if = "!rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">&#8369; 0.00</label>
                            </div>
                        </div>
                        <div class="row no-padding">
                            <div class="col-md-12">
                                <div class="row no-padding">
                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                            <div class="col-md-12" style="margin-left:100px;">
                                <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                <input type = "text" style = "display:none" name="tenant_type[]"  ng-model = "discount.tenant_type" />
                                <input type = "text" style = "display:none" name="discount_type[]"  ng-model = "discount.discount_type" />
                                <input type = "text" style = "display:none"  name="discount[]"  ng-model = "discount.discount" />

                                <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="(rental_type == 'Fixed Plus Percentage' || rental_type == 'Fixed' || rental_type == 'Fixed/Percentage w/c Higher' || rental_type == 'Fixed/Percentage/Minimum w/c Higher' || rental_type == 'Percentage' || rental_type == 'Percentage Base Tenant') && is_vat == 'Added'">
                            <label for="less_vat" class="col-md-4 control-label invoice-label text-right">VAT(+)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "vat"
                                    ui-number-mask="2"
                                    id="less_vat"
                                    name = "less_vat"
                                    autocomplete="off">
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }} ({{ added_vat | currency: '&#8369;'}})</label>
                        </div>
                        <div class="row no-padding" ng-if = "is_wht == 'Added'">
                            <label for="wht" class="col-md-4 control-label text-right invoice-label">Withholding Tax(-)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "wht"
                                    ui-number-mask="2"
                                    id="wht"
                                    name = "wht"
                                    autocomplete="off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ({{ less_witholding | currency: '&#8369;'}})</label>
                        </div>

                        <br>

                        <div class="row" ng-if="rental_type == 'Fixed Plus Percentage' || rental_type == 'Percentage'">
                            <div class="form-group">
                                <label for="total_rentable" class="col-md-4 control-label text-right invoice-total">Total Rentable Sales</label>
                                <div class="col-md-4">
                                    <input
                                        type="text"
                                        style = "display:none"
                                        class="form-control currency"
                                        readonly
                                        ng-model = "total_rentable"
                                        id="total_rentable"
                                        name = "total_rentable"
                                        autocomplete="off">
                                </div>
                                <label ng-if = "!total_rentable" class="col-md-4 control-label text-right invoice-total">&#8369;0.00</label>
                                <label ng-if = "total_rentable" class="col-md-4 control-label text-right invoice-total">{{ total_rentable | currency : '&#8369;' }}</label>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Fixed' || rental_type == 'Percentage Base Tenant'">
                            <label for="total_basicRental" class="col-md-4 control-label text-right invoice-total">Total Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "total_basicRental"
                                    id="total_basicRental"
                                    name = "total_basicRental"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total"><u>{{ total_basicRental | currency : '&#8369;' }}</u></label>
                        </div>
                        <div>
                            <input type = "text" style="display:none" ng-model = "added_vat" id = "added_vat" name = "added_vat"  />
                            <input type = "text" style="display:none" ng-model = "less_witholding" id = "less_witholding" name = "less_witholding"  />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_basicRental" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Basic Rental Modal -->


<!-- Add Basic Rental Modal -->
<div class="modal fade" id = "less_than_30Days_Fixed_tenant">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="row no-padding" ng-if="rental_type == 'Fixed'">
                            <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type = "text"
                                    style = "display:none"
                                    class = "form-control currency"
                                    readonly
                                    ng-model = "basic_rental"
                                    ui-number-mask="2"
                                    id = "basic_rental"
                                    name = "basic_rental"
                                    autocomplete = "off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ basic_rental | currency : '&#8369;' }}</label>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <label for="num_days" class="col-md-4 control-label text-right invoice-label">Number of Days</label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control currency"
                                            ng-model = "num_days"
                                            ui-number-mask="2"
                                            id="num_days"
                                            name = "num_days"
                                            autocomplete="off"
                                            ng-keyup="calculate_currentRental_fixedTenant(basic_rental, month_days, num_days, tenant_primaryKey, vat_agreement)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">{{basic_rental | currency : '&#8369;'}}/{{month_days}} * {{num_days}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding" ng-if="rental_type == 'Fixed'">
                            <label for="current_rental" class="col-md-4 control-label text-right invoice-total">Current Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "current_rental"
                                    id="current_rental"
                                    name = "current_rental"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total"><u>{{ current_rental | currency : '&#8369;' }}</u></label>
                        </div>

                        <div class="row no-padding">
                            <div class="col-md-12">
                                <div class="row no-padding">
                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                            <div class="col-md-12" style="margin-left:100px;">
                                <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                <input type = "text" style = "display:none" name="less30_fixed_tenant_type[]"  ng-model = "discount.tenant_type" />
                                <input type = "text" style = "display:none" name="less30_fixed_discount_type[]"  ng-model = "discount.discount_type" />
                                <input type = "text" style = "display:none"  name="less30_fixed_discount[]"  ng-model = "discount.discount" />

                                <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="rental_type == 'Fixed' && is_vat == 'Added'">
                            <label for="less_vat" class="col-md-4 control-label invoice-label text-right">VAT(+)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "vat"
                                    ui-number-mask="2"
                                    id="less_vat"
                                    name = "less_vat"
                                    autocomplete="off">
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }} ({{ vat_fixed_less_30Days | currency: '&#8369;'}})</label>
                        </div>
                        <div class="row no-padding" ng-if="is_wht == 'Added'">
                            <label for="wht" class="col-md-4 control-label text-right invoice-label">Withholding Tax(-)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "wht"
                                    ui-number-mask="2"
                                    id="wht"
                                    name = "wht"
                                    autocomplete="off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ({{ wht_fixed_less_30Days | currency: '&#8369;'}})</label>
                        </div>

                        <br>
                        <div class="row no-padding" ng-if="rental_type == 'Fixed'">
                            <label for="basic_fixed_less_30Days" class="col-md-4 control-label text-right invoice-total">Total Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "basic_fixed_less_30Days"
                                    id="basic_fixed_less_30Days"
                                    name = "basic_fixed_less_30Days"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total"><u>{{ basic_fixed_less_30Days | currency : '&#8369;' }}</u></label>
                        </div>
                        <div>
                            <input type = "text" style="display:none" ng-model = "vat_fixed_less_30Days" id = "vat_fixed_less_30Days" name = "vat_fixed_less_30Days"  />
                            <input type = "text" style="display:none" ng-model = "wht_fixed_less_30Days" id = "wht_fixed_less_30Days" name = "wht_fixed_less_30Days"  />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_less30_basic" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Basic Rental Modal -->



<!-- Add Other Charges Modal -->
<div class="modal fade" id = "other_charges">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Other Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id = "charges_wrapper">
                        <div class = "row">
                            <div class="form-group">
                                <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                <div class="col-md-8">
                                    <select required name = "monthly_charges" id="charges_desc" ng-model = "desc.description" class = "form-control" ng-change="get_monthlyCharges_details('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthlyCharges_details/' + desc.description)">
                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                        <option ng-repeat = "desc in chargeDesc">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in monthly_details">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">
                                        <input type = "text" ng-model = "detail.charges_code" id = "charges_code" name = "monthly_chargeCode" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                    <div class="col-md-8">
                                        <input type = "text" readonly ng-model = "detail.uom" id="charges_uom" name = "monthly_uom" class = "form-control" />
                                    </div>
                                </div>
                            </div>



                            <div class = "row">
                                <div class="form-group">
                                    <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                    <div class="col-md-8">
                                        <input
                                        type = "text"
                                            ui-number-mask="2"
                                            ng-model = "detail.unit_price"
                                            id="charges_unitPrice"
                                            name = "unit_price"
                                            class = "form-control currency"
                                            readonly>
                                    </div>
                                </div>
                            </div>


                            <div ng-if="desc.description == 'Water' || desc.description == 'Electricity'">
                                <div class = "row" >
                                    <div class="form-group">
                                        <label for="prev_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Previous Reading </label>
                                        <div class="col-md-8" ng-if = "desc.description == 'Electricity'">
                                            <input
                                                type = "text"
                                                    ui-number-mask="2"
                                                    ng-model = "electricReading"
                                                    id="electricReading"
                                                    name = "electricReading"
                                                    readonly
                                                    class = "form-control currency"
                                                    ng-keyup = "calculate_consumed(electricReading, curr_reading, detail.unit_price)">
                                        </div>
                                        <div class="col-md-8" ng-if = "desc.description == 'Water'">
                                            <input
                                                type = "text"
                                                    ui-number-mask="2"
                                                    ng-model = "waterReading"
                                                    id="waterReading"
                                                    name = "waterReading"
                                                    class = "form-control currency"
                                                    readonly
                                                    ng-keyup = "calculate_consumed(waterReading, curr_reading, detail.unit_price)">
                                        </div>
                                    </div>
                                </div>

                                <div class = "row">
                                    <div class="form-group">
                                        <label for="curr_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Current Reading</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "curr_reading"
                                                min="prev_reading"
                                                id="curr_reading"
                                                name = "curr_reading"
                                                class = "form-control currency"
                                                ng-keyup = "calculate_consumed(prev_reading, curr_reading, detail.unit_price)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->

                            <div class = "row" ng-if = "detail.uom != 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="total_unit" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            ui-number-mask="6"
                                            ng-model = "total_unit"
                                            id="charges_totalUnit"
                                            name = "total_unit"
                                            class = "form-control currency"
                                            ng-keyup = "calculate_otherCharges(detail.unit_price, total_unit, detail.uom)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom != 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            readonly
                                            ui-number-mask="2"
                                            ng-model = "other_amount"
                                            id="otherCharges_actualAmt"
                                            name = "actual_amount"
                                            class = "form-control currency">


                                    </div>
                                </div>
                            </div>


                            <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->


                            <div class = "row" ng-if = "detail.uom == 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            readonly
                                            ui-number-mask="2"
                                            ng-model = "detail.unit_price"
                                            id="otherCharges_actualAmt"
                                            name = "actual_amount"
                                            class = "form-control currency">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" onclick="append_otherCharges()" ng-click = "clear_totalUnit_actualAmount()" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Other Charges Modal -->


<!-- Add Other Charges Modal -->
<div class="modal fade" id = "less30_monthly_charges">
    <div class="modal-dialog modal-xl">
        <form action="#" method="post" id="frm_less30_monthlyCharges"  name = "frm_less30_monthlyCharges" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Selected Monthly Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 pull-left">
                            <div class="row">
                                <div class="form-group">
                                    <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>No. of Days since Opening Date:</label>
                                    <div class="col-md-3">
                                        <input type = "text" format = "number" required name = "num_of_days" ng-model = "num_of_days" class="form-control currency" />
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_less30_monthlyCharges.num_of_days.$dirty && frm_less30_monthlyCharges.num_of_days.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>No. of Days in a Month:</label>
                                    <div class="col-md-3">
                                        <input type = "text" format = "number" required name = "month_days" ng-model = "month_days" class="form-control currency" />
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_less30_monthlyCharges.month_days.$dirty && frm_less30_monthlyCharges.month_days.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="23%">Description</th>
                                        <th width="11%">Charges Code</th>
                                        <th width="11%">UOM</th>
                                        <th width="11%">Unit Price</th>
                                        <th width="11%">Previous Reading</th>
                                        <th width="11%">Current Reading</th>
                                        <th width="11%">Total Unit</th>
                                        <th width="11%">Actual Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat = "charge in selected_monthly_charges track by $index">
                                        <td>
                                            {{ charge.description }} <input type = "text" style="display:none" name = "less30_monthly_description[]" id = "less30_monthly_description" ng-model = "charge.description" />
                                        </td>
                                        <td>
                                            {{ charge.charges_code }} <input type = "text" style="display:none" name = "less30_monthly_charges_code[]" id = "less30_monthly_charges_code" ng-model = "charge.charges_code" />
                                        </td>
                                        <td>
                                            {{ charge.uom }} <input type = "text" style="display:none" name = "less30_monthly_uom[]" id = "less30_monthly_uom" ng-model = "charge.uom" />
                                        </td>
                                        <td align="right">
                                            {{ charge.unit_price | currency : '' }} <input type = "text" style="display:none" name = "less30_monthly_unit_price[]" id = "less30_monthly_unit_price{{$index}}" ng-model = "charge.unit_price" />
                                        </td>
                                        <td>
                                            <div ng-if = "charge.description == 'Electricity'"><input type = "text"  ui-number-mask = "2" name = "less30_prev_reading[]" ng-model = "electricReading" id="less30_electricReading{{$index}}" class="form-control currency" /></div>
                                            <div ng-if = "charge.description == 'Water'"><input type = "text"  ui-number-mask = "2" name = "less30_prev_reading[]" ng-model = "waterReading" id="less30_waterReading{{$index}}" class="form-control currency" /></div>
                                            <div ng-if = "charge.description != 'Water' && charge.description != 'Electricity'"><input type = "text" name = "less30_prev_reading[]"  value="0.00" class="form-control currency" /></div>
                                        </td>
                                        <td>
                                            <div ng-if = "charge.description == 'Electricity'"><input type = "text" name = "less30_curr_reading[]" format = "number" autocomplete = "off" id = "less30_monthly_curr_reading{{$index}}"  ng-model = "monthly_curr_reading" ng-change = "less30_compute_currentReading('less30_monthly_unit_price' + $index, 'less30_electricReading' + $index, 'less30_monthly_curr_reading' + $index, 'less30_monthly_actual_amount' + $index, num_of_days)" class="form-control currency" /></div>
                                            <div ng-if = "charge.description == 'Water'"><input type = "text" name = "less30_curr_reading[]" format = "number" autocomplete = "off" id = "less30_monthly_curr_reading{{$index}}" ng-model = "monthly_curr_reading" ng-change = "less30_compute_currentReading('less30_monthly_unit_price' + $index, 'less30_waterReading' + $index, 'less30_monthly_curr_reading' + $index, 'less30_monthly_actual_amount' + $index, num_of_days)" class="form-control currency" /></div>
                                            <div ng-if = "charge.description != 'Water' && charge.description != 'Electricity'"><input type = "text" name = "less30_curr_reading[]"  value="0.00" class="form-control currency" /></div>
                                        </td>
                                        <td>
                                            <input type = "text"format="number" autocomplete = "off" ng-readonly = "charge.description == 'Water' || charge.description == 'Electricity' || charge.uom == 'Fixed Amount'" name = "less30_monthly_total_unit[]" ng-model = "monthly_total_unit" ng-change="less30_compute_actual_amount(charge.description, 'less30_monthly_unit_price' + $index, 'less30_monthly_total_unit' + $index, 'less30_monthly_actual_amount' + $index, charge.uom, num_of_days, month_days)" id = "less30_monthly_total_unit{{$index}}"  class="form-control currency" />
                                        </td>
                                        <td>
                                            <div ng-if = "charge.uom != 'Fixed Amount'"><input type = "text" name = "less30_monthly_actual_amount[]"  ng-model = "monthly_actual_amount" id = "less30_monthly_actual_amount{{$index}}" class="form-control currency" /></div>
                                            <div ng-if = "charge.uom == 'Fixed Amount'"><input type = "text" name = "less30_monthly_actual_amount[]" ui-number-mask = "2"  ng-model = "charge.unit_price" id = "less30_monthly_actual_amount{{$index}}" class="form-control currency" /></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" onclick="append_less30_monthlyCharges()" ng-disabled = "frm_less30_monthlyCharges.$invalid" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- End Other Charges Modal -->


<!-- Add Other Charges Modal -->
<div class="modal fade" id = "monthly_charges">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Selected Monthly Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="23%">Description</th>
                                    <th width="11%">Charges Code</th>
                                    <th width="11%">UOM</th>
                                    <th width="11%">Unit Price</th>
                                    <th width="11%">Previous Reading</th>
                                    <th width="11%">Current Reading</th>
                                    <th width="11%">Total Unit</th>
                                    <th width="11%">Actual Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat = "charge in selected_monthly_charges track by $index">
                                    <td>
                                        {{ charge.description }} <input type = "text" style="display:none" name = "monthly_description[]" id = "monthly_description" ng-model = "charge.description" />
                                    </td>
                                    <td>
                                        {{ charge.charges_code }} <input type = "text" style="display:none" name = "monthly_charges_code[]" id = "monthly_charges_code" ng-model = "charge.charges_code" />
                                    </td>
                                    <td>
                                        {{ charge.uom }} <input type = "text" style="display:none" name = "monthly_uom[]" id = "monthly_uom" ng-model = "charge.uom" />
                                    </td>
                                    <td align="right">
                                        {{ charge.unit_price | currency : '' }} <input type = "text" style="display:none" name = "monthly_unit_price[]" id = "monthly_unit_price{{$index}}" ng-model = "charge.unit_price" />
                                    </td>
                                    <td>
                                        <div ng-if = "charge.description == 'Electricity'"><input type = "text" ui-number-mask = "2" name = "prev_reading[]" ng-model = "electricReading" id="electricReading{{$index}}" class="form-control currency" /></div>
                                        <div ng-if = "charge.description == 'Water'"><input type = "text" ui-number-mask = "2" name = "prev_reading[]" ng-model = "waterReading" id="waterReading{{$index}}" class="form-control currency" /></div>
                                        <div ng-if = "charge.description != 'Water' && charge.description != 'Electricity'"><input type = "text" name = "prev_reading[]" readonly value="0.00" class="form-control currency" /></div>
                                    </td>
                                    <td>
                                        <div ng-if = "charge.description == 'Electricity'"><input type = "text" name = "curr_reading[]" format = "number" autocomplete = "off" id = "monthly_curr_reading{{$index}}"  ng-model = "monthly_curr_reading" ng-change = "compute_currentReading('monthly_unit_price' + $index, 'electricReading' + $index, 'monthly_curr_reading' + $index, 'monthly_actual_amount' + $index)" class="form-control currency" /></div>
                                        <div ng-if = "charge.description == 'Water'"><input type = "text" name = "curr_reading[]" format = "number" autocomplete = "off" id = "monthly_curr_reading{{$index}}" ng-model = "monthly_curr_reading" ng-change = "compute_currentReading('monthly_unit_price' + $index, 'waterReading' + $index, 'monthly_curr_reading' + $index, 'monthly_actual_amount' + $index)" class="form-control currency" /></div>
                                        <div ng-if = "charge.description != 'Water' && charge.description != 'Electricity'"><input type = "text" name = "curr_reading[]" readonly value="0.00" class="form-control currency" /></div>
                                    </td>
                                    <td>
                                        <input type = "text"format="number" autocomplete = "off" ng-readonly = "charge.description == 'Water' || charge.description == 'Electricity' || charge.uom == 'Fixed Amount'" name = "monthly_total_unit[]" ng-model = "monthly_total_unit" ng-change="compute_actual_amount(charge.description, 'monthly_unit_price' + $index, 'monthly_total_unit' + $index, 'monthly_actual_amount' + $index, charge.uom)" id = "monthly_total_unit{{$index}}"  class="form-control currency" />
                                    </td>
                                    <td>
                                        <div ng-if = "charge.uom != 'Fixed Amount'"><input type = "text" name = "monthly_actual_amount[]"  ng-model = "monthly_actual_amount" id = "monthly_actual_amount{{$index}}" class="form-control currency" /></div>
                                        <div ng-if = "charge.uom == 'Fixed Amount'"><input type = "text" name = "monthly_actual_amount[]" ui-number-mask = "2"  ng-model = "charge.unit_price" id = "monthly_actual_amount{{$index}}" class="form-control currency" /></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="append_monthlyCharges()"  class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- End Other Charges Modal -->




<!-- Add Construction Materials Modal -->
<div class="modal fade" id = "constMat">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Construction Materials</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id = "charges_wrapper">
                        <div class = "row">
                            <div class="form-group">
                                <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                <div class="col-md-8">
                                    <select required name = "monthly_charges" id="constMat_desc" ng-model = "desc.description" class = "form-control" ng-change="get_monthlyCharges_details('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthlyCharges_details/' + desc.description)">
                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                        <option ng-repeat = "desc in constMat">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in monthly_details">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">
                                        <input type = "text" ng-model = "detail.charges_code" id = "charges_code" name = "monthly_chargeCode" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                    <div class="col-md-8">
                                        <input type = "text" readonly ng-model = "detail.uom" id="charges_uom" name = "monthly_uom" class = "form-control" />
                                    </div>
                                </div>
                            </div>



                            <div class = "row">
                                <div class="form-group">
                                    <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                    <div class="col-md-8">
                                        <input
                                        type = "text"
                                            ui-number-mask="2"
                                            ng-model = "detail.unit_price"
                                            id="charges_unitPrice"
                                            name = "unit_price"
                                            class = "form-control currency"
                                            readonly>
                                    </div>
                                </div>
                            </div>


                            <div ng-if="desc.description == 'Water' || desc.description == 'Electricity'">
                                <div class = "row" >
                                    <div class="form-group">
                                        <label for="prev_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Previous Reading </label>
                                        <div class="col-md-8">
                                            <input
                                            type = "text"
                                                ui-number-mask="2"
                                                ng-model = "prev_reading"
                                                id="prev_reading"
                                                name = "prev_reading"
                                                class = "form-control currency"
                                                ng-keyup = "calculate_consumed(prev_reading, curr_reading, detail.unit_price)">
                                        </div>
                                    </div>
                                </div>

                                <div class = "row">
                                    <div class="form-group">
                                        <label for="curr_reading" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Current Reading</label>
                                        <div class="col-md-8">
                                            <input
                                                type = "text"
                                                ui-number-mask="2"
                                                ng-model = "curr_reading"
                                                min="prev_reading"
                                                id="curr_reading"
                                                name = "curr_reading"
                                                class = "form-control currency"
                                                ng-keyup = "calculate_consumed(prev_reading, curr_reading, detail.unit_price)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->

                            <div class = "row" ng-if = "detail.uom != 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="total_unit" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            ui-number-mask="2"
                                            ng-model = "total_unit"
                                            id="cosntMat_totalUnit"
                                            name = "total_unit"
                                            class = "form-control currency"
                                            ng-keyup = "calculate_otherCharges(detail.unit_price, total_unit)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom != 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            readonly
                                            ui-number-mask="2"
                                            ng-model = "other_amount"
                                            id="constMat_actualAmt"
                                            name = "actual_amount"
                                            class = "form-control currency">


                                    </div>
                                </div>
                            </div>


                            <!-- If UOM is equal to 'Fixed Amount', hide total_unit and actual amount is equal to unit_price -->


                            <div class = "row" ng-if = "detail.uom == 'Fixed Amount'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">
                                        <input
                                            type = "text"
                                            readonly
                                            ui-number-mask="2"
                                            ng-model = "detail.unit_price"
                                            id="constMat_actualAmt"
                                            name = "actual_amount"
                                            class = "form-control currency">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_constMat"  ng-click = "clear_totalUnit_actualAmount()" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Construction Material Charges Modal -->



<!-- Short Term Modal -->
<div class="modal fade" id = "shortTerm_charges">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Short Term Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row no-padding">
                    <div class="col-md-12">
                        <div class="row no-padding">
                            <div class="col-md-12">
                                <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                <div class="col-md-4">
                                    <input
                                        type = "text"
                                        style="display:none"
                                        ng-model = "sTerm_rental"
                                        id="sTerm_rental"
                                        name = "sTerm_rental"
                                        class = "form-control currency">
                                </div>
                                <label class="col-md-4 control-label text-left invoice-label">{{ sTerm_rental | currency : '&#8369;' }}</label>
                            </div>
                        </div>
                        <div class="row no-padding">
                            <div class="col-md-12">
                                <div class="row no-padding">
                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                            <div class="col-md-12" style="margin-left:100px;">
                                <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                <input type = "text" style = "display:none"  name = "tenant_type[]"  ng-model = "discount.tenant_type" />
                                <input type = "text" style = "display:none"  name = "discount_type[]" ng-model = "discount.discount_type" />
                                <input type = "text" style = "display:none"  name = "discount[]" ng-model = "discount.discount" />
                                <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="sTerm_isvat == 'Added'">
                            <div class="col-md-12">
                                <label for="vat" class="col-md-4 control-label text-right invoice-label">VAT(+)</label>
                                <div class="col-md-4">
                                    <input
                                        type = "text"
                                        style = "display:none"
                                        ui-number-mask="2"
                                        ng-model = "vat"
                                        id="vat"
                                        name = "vat"
                                        class = "form-control currency">
                                </div>
                                <label class="col-md-4 control-label text-left invoice-label">{{ vat | currency : '%' }}</label>
                            </div>
                        </div>
                        <div class="row no-padding">
                            <div class="col-md-12">
                                <label for="wht" class="col-md-4 control-label text-right invoice-label">WHT(-)</label>
                                <div class="col-md-4">
                                    <input
                                        type = "text"
                                        style = "display:none"
                                        ui-number-mask="2"
                                        ng-model = "wht"
                                        id="wht"
                                        name = "wht"
                                        class = "form-control currency">
                                </div>
                                <label class="col-md-4 control-label text-left invoice-label">{{ wht | currency : '%' }}</label>
                            </div>
                        </div>
                        <br>
                        <div class = "row no-padding">
                            <div class="col-md-12">
                                <label for="sTerm_totalRental" class="col-md-4 control-label text-right invoice-total">Total Rental</label>
                                <div class="col-md-4">
                                    <input
                                        type = "text"
                                        style = "display:none"
                                        ng-model = "sTerm_totalRental"
                                        id="sTerm_totalRental"
                                        name = "sTerm_totalRental"
                                        class = "form-control currency">
                                        <input type = "text" style = "display:none" name = "added_vat" id = "added_vat" ng-model = "added_vat" />
                                        <input type = "text" style = "display:none" name = "less_witholding" id = "less_witholding" ng-model = "less_witholding" />
                                </div>
                                <label class="col-md-4 control-label text-left invoice-total"><u>{{ sTerm_totalRental | currency : '&#8369;' }}</u></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_shortTerm_charges" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- Short Term Modal -->


<!-- Tag as Modal -->
<div class="modal fade" id = "tagAs_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> Tag As</h4>
            </div>
            <form action="#"  method="post" id="frm_tag" name = "frm_tag">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-3">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="Draft" value="Draft" name="tag" checked>
                                        <label for="Draft"> Draft </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="Posted" value="Posted" name="tag">
                                        <label for="Posted"> Posted </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button type="button" onclick="save_invoice('<?php echo base_url(); ?>index.php/leasing_transaction/save_invoice/')" class="btn btn-primary button-b" data-dismiss="modal"> <i class="fa fa-save"></i> Proceed</button>
                </div>
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Tag as Modal -->


<!-- charges_typeModal as Modal -->
<div class="modal fade" id = "charges_typeModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> Select Charges Type</h4>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-2">
                                        <select
                                            name = "charges_type"
                                            class = "form-control"
                                            ng-model = "charges_type"
                                            id = "charges_type"
                                            onchange="closeModal('charges_typeModal'); openBasic()"
                                            ng-change="datafor_basicRent(dirty.value, tenancy_type); shortTerm_charges(dirty.value, tenancy_type)">
                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                            <option>Basic Charges</option>
                                            <option>Other Charges</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End charges_typeModal as Modal -->



<!-- fixedORPercentage as Modal -->
<div class="modal fade" id = "fixedORPercentage">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
                <div class="modal-body" style="max-height: 620px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 side-divider">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Gross Sales</label>
                                            <div class="col-md-8 pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type = "text"
                                                        class="form-control currency"
                                                        name = "gsales"
                                                        ng-model = "gsales"
                                                        ng-keyup = "inputted_gross(tenant_primaryKey, gsales, is_incrementable)"
                                                        ui-number-mask="2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">(X 12%) 1.12%</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <label for="total_gross" class="col-md-4 control-label text-right invoice-label">Total Gross Sales</label>
                                            <label ng-if = "total_gross" class="col-md-4 pull-right control-label text-right invoice-label"> {{total_gross | currency : '&#8369;' }}</label>
                                            <label ng-if = "!total_gross" class="col-md-4 pull-right control-label text-right invoice-label">&#8369;0.00</label>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <label for="rent_percentage" class="col-md-4 control-label text-right invoice-label">Rent Percentage</label>
                                            <div class="col-md-4"></div>
                                            <label class="col-md-4 control-label text-right invoice-label"> {{rent_percentage | currency : '%' }}</label>
                                        </div>
                                    </div>
                                    <div class="row no-padding" >
                                        <div class="form-group">
                                            <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Rentable Sales</label>
                                            <label ng-if = "rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">{{ rent_sale | currency : '&#8369;' }}</label>
                                            <input type = "text" style = "display:none" name = "rent_sale" id = "rent_sale" ng-model = "rent_sale" />
                                            <label ng-if = "!rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">&#8369; 0.00</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                            <div class="col-md-8 pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type = "text"
                                                        class="form-control currency"
                                                        name = "fixed_rental"
                                                        ng-model = "basic_rental + increment_value"
                                                        ui-number-mask="2"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 pull-right">
                                            <button type = "button" ng-click = "evaluate_rental(tenant_primaryKey, rent_sale, basic_rental, is_incrementable)" class = "btn btn-info btn-medium pull-right button-b"><i class = "fa fa-cog"></i> Evaluate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row" ng-hide = "!higher_rental">
                                        <div class="col-md-8 col-md-offset-2" >
                                            <div class="row">
                                                <center><h3 class="header-title">Evaluation Result</h3></center>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="form-group">
                                                    <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                                    <label class="col-md-4 pull-right control-label text-right invoice-label">{{ higher_rental | currency : '&#8369;' }}</label>
                                                    <input type = "text" style = "display:none" name = "higher_rental" id = "higher_rental" ng-model = "higher_rental"/>
                                                </div>
                                            </div>
                                            <div class="row no-padding" ng-if="is_incrementable > 0 && !hide_increment">
                                                <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Rental Increment({{percent_increment}}%)</label>
                                                <input type = "text" name = "percent_increment" id = "fixedORPercentage_percent_increment" style = "display:none" ng-model = "percent_increment" />
                                                <div class="col-md-4">
                                                    <input
                                                        type = "text"
                                                        style = "display:none"
                                                        class = "form-control currency"
                                                        readonly
                                                        ng-model = "increment_value"
                                                        ui-number-mask="2"
                                                        id = "fixedORPercentage_increment_value"
                                                        name = "increment_value"
                                                        autocomplete = "off" >
                                                </div>
                                                <label class="col-md-4 control-label text-right invoice-label">{{ increment_value | currency : '&#8369;' }}</label>
                                            </div>

                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <div class="row no-padding">
                                                        <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                                                <div class="col-md-12" style="margin-left:100px;">
                                                    <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                                    <input type = "text" style = "display:none"  name = "tenant_type[]"  ng-model = "discount.tenant_type" />
                                                    <input type = "text" style = "display:none"  name = "discount_type[]" ng-model = "discount.discount_type" />
                                                    <input type = "text" style = "display:none"  name = "discount[]" ng-model = "discount.discount" />
                                                    <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                                        <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                                        <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row no-padding" ng-if = "is_vat == 'Added'">
                                                <div class="col-md-12">
                                                    <label for="vat" class="col-md-4 control-label text-right invoice-label">VAT(+)</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "added_vat"
                                                            id="fixedPercentage_addedVat"
                                                            name = "vat"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }}( {{ added_vat | currency : '&#8369;' }} )</label>
                                                </div>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">WHT(-)</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "less_witholding"
                                                            id="fixedPercentage_lessWHT"
                                                            name = "wht"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ( {{ less_witholding | currency : '&#8369;' }} )</label>
                                                </div>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Total Rental</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "evaluated_rental"
                                                            id="evaluated_rental"
                                                            name = "evaluated_rental"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label"><b>{{ evaluated_rental | currency : '&#8369;' }}</b></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 pull-right">
                                                    <button type = "button" id="append_fixedORPercentage" class = "btn btn-primary btn-medium pull-right button-b"><i class = "fa fa-plus"></i> Append</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End fixedORPercentage as Modal -->


<!-- fixedORPercentage as Modal -->
<div class="modal fade" id = "less_than_30Days_FixedORPercentage_tenant">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
                <div class="modal-body" style="max-height: 620px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 side-divider">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Gross Sales</label>
                                            <div class="col-md-8 pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type = "text"
                                                        class="form-control currency"
                                                        name = "gsales"
                                                        ng-model = "gsales"
                                                        ng-keyup = "inputted_gross(tenant_primaryKey, gsales, is_incrementable)"
                                                        ui-number-mask="2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">(X 12%) 1.12%</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <label for="total_gross" class="col-md-4 control-label text-right invoice-label">Total Gross Sales</label>
                                            <label ng-if = "total_gross" class="col-md-4 pull-right control-label text-right invoice-label"> {{total_gross | currency : '&#8369;' }}</label>
                                            <label ng-if = "!total_gross" class="col-md-4 pull-right control-label text-right invoice-label">&#8369;0.00</label>
                                        </div>
                                    </div>
                                    <div class="row no-padding">
                                        <div class="form-group">
                                            <label for="rent_percentage" class="col-md-4 control-label text-right invoice-label">Rent Percentage</label>
                                            <div class="col-md-4"></div>
                                            <label class="col-md-4 control-label text-right invoice-label"> {{rent_percentage | currency : '%' }}</label>
                                        </div>
                                    </div>
                                    <div class="row no-padding" >
                                        <div class="form-group">
                                            <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Rentable Sales</label>
                                            <label ng-if = "rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">{{ rent_sale | currency : '&#8369;' }}</label>
                                            <input type = "text" style = "display:none" name = "rent_sale" id = "rent_sale" ng-model = "rent_sale" />
                                            <label ng-if = "!rent_sale" class="col-md-4 pull-right control-label text-right invoice-label">&#8369; 0.00</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                            <div class="col-md-8 pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type = "text"
                                                        class="form-control currency"
                                                        name = "fixed_rental"
                                                        ng-model = "basic_rental"
                                                        ui-number-mask="2"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="num_days" class="col-md-4 control-label text-right invoice-label">Number of Days</label>
                                            <div class="col-md-5 pull-right">
                                                <div class="input-group">
                                                    <input
                                                        type="text"
                                                        class="form-control currency"
                                                        ng-model = "num_days"
                                                        ui-number-mask="2"
                                                        id="num_days"
                                                        name = "num_days"
                                                        autocomplete="off"
                                                        ng-keyup="calculate_currentRental_fixedTenant(basic_rental, month_days, num_days, tenant_primaryKey)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="col-md-8 col-md-offset-4 control-label invoice-label text-right red">{{basic_rental | currency : '&#8369;'}}/{{month_days}} * {{num_days}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <label  class="col-md-4 pull-right control-label text-right invoice-label">{{ current_rental | currency : '&#8369;' }}</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 pull-right">
                                            <button type = "button" ng-click = "evaluate_rental(tenant_primaryKey, rent_sale, current_rental, is_incrementable)" class = "btn btn-info btn-medium pull-right button-b"><i class = "fa fa-cog"></i> Evaluate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row" ng-hide = "!higher_rental">
                                        <div class="col-md-8 col-md-offset-2" >
                                            <div class="row">
                                                <center><h3 class="header-title">Evaluation Result</h3></center>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="form-group">
                                                    <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                                                    <label class="col-md-4 pull-right control-label text-right invoice-label">{{ higher_rental | currency : '&#8369;' }}</label>
                                                    <input type = "text" style = "display:none" name = "higher_rental" id = "higher_rental" ng-model = "higher_rental"/>
                                                </div>
                                            </div>
                                            <div class="row no-padding" ng-if="is_incrementable > 0">
                                                <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Rental Increment({{percent_increment}}%)</label>
                                                <input type = "text" name = "percent_increment" id = "percent_increment" style = "display:none" ng-model = "percent_increment" />
                                                <div class="col-md-4">
                                                    <input
                                                        type = "text"
                                                        style = "display:none"
                                                        class = "form-control currency"
                                                        readonly
                                                        ng-model = "increment_value"
                                                        ui-number-mask="2"
                                                        id = "increment_value"
                                                        name = "increment_value"
                                                        autocomplete = "off" >
                                                </div>
                                                <label class="col-md-4 control-label text-right invoice-label">{{ increment_value | currency : '&#8369;' }}</label>
                                            </div>

                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <div class="row no-padding">
                                                        <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                                                <div class="col-md-12" style="margin-left:100px;">
                                                    <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                                    <input type = "text" style = "display:none"  name = "fixedORPercentage_tenant_type[]"  ng-model = "discount.tenant_type" />
                                                    <input type = "text" style = "display:none"  name = "fixedORPercentage_discount_type[]" ng-model = "discount.discount_type" />
                                                    <input type = "text" style = "display:none"  name = "fixedORPercentage_discount[]" ng-model = "discount.discount" />
                                                    <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                                        <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                                        <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row no-padding" ng-if = "is_vat == 'Added'">
                                                <div class="col-md-12">
                                                    <label for="vat" class="col-md-4 control-label text-right invoice-label">VAT(+)</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "added_vat"
                                                            id="fixedPercentage_addedVat"
                                                            name = "vat"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }}( {{ added_vat | currency : '&#8369;' }} )</label>
                                                </div>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">WHT(-)</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "less_witholding"
                                                            id="fixedPercentage_lessWHT"
                                                            name = "wht"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ( {{ less_witholding | currency : '&#8369;' }} )</label>
                                                </div>
                                            </div>
                                            <div class="row no-padding">
                                                <div class="col-md-12">
                                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Total Rental</label>
                                                    <div class="col-md-4">
                                                        <input
                                                            type = "text"
                                                            style = "display:none"
                                                            ng-model = "evaluated_rental"
                                                            id="evaluated_rental"
                                                            name = "evaluated_rental"
                                                            class = "form-control">
                                                    </div>
                                                    <label class="col-md-4 control-label text-right invoice-label"><b>{{ evaluated_rental | currency : '&#8369;' }}</b></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 pull-right">
                                                    <button type = "button" id="append_less30_basic_fixedORPercentage" class = "btn btn-primary btn-medium pull-right button-b"><i class = "fa fa-plus"></i> Append</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End fixedORPercentage as Modal -->



<!-- Add Basic Rental Modal -->
<div class="modal fade" id = "basic_manual">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Basic Rental</h4>
            </div>
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="row no-padding">
                            <label for="basicRental_manual" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                            <div class="col-md-4 pull-right">
                                <input
                                    type = "text"
                                    class = "form-control currency"
                                    ng-model = "basicRental_manual"
                                    ui-number-mask="2"
                                    id = "basicRental_manual"
                                    name = "basicRental_manual"
                                    autocomplete = "off"
                                    ng-keyup="basic_manual_input(tenant_primaryKey, basicRental_manual)">
                            </div>
                        </div>

                        <div class="row no-padding">
                            <div class="col-md-12">
                                <div class="row no-padding">
                                    <label for="wht" class="col-md-4 control-label text-right invoice-label">Discount(-):</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-repeat = "discount in mydiscounts">
                            <div class="col-md-12" style="margin-left:100px;">
                                <label class="col-md-4 control-label text-right invoice-label"></i>{{ discount.tenant_type }}</label>
                                <input type = "text" style = "display:none" name="tenant_type_manual[]"  ng-model = "discount.tenant_type" />
                                <input type = "text" style = "display:none" name="discount_type_manual[]"  ng-model = "discount.discount_type" />
                                <input type = "text" style = "display:none"  name="discount_manual[]"  ng-model = "discount.discount" />

                                <label class="col-md-4 control-label text-right invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="(rental_type == 'Fixed Plus Percentage' || rental_type == 'Fixed' || rental_type == 'Fixed/Percentage w/c Higher' || rental_type == 'Fixed/Percentage/Minimum w/c Higher' || rental_type == 'Percentage' || rental_type == 'Percentage Base Tenant') && is_vat == 'Added'">
                            <label for="less_vat" class="col-md-4 control-label invoice-label text-right">VAT(+)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "vat"
                                    ui-number-mask="2"
                                    id="less_vat"
                                    name = "less_vat"
                                    autocomplete="off">
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ vat | currency : '%' }} ({{ added_vat | currency: '&#8369;'}})</label>
                        </div>
                        <div class="row no-padding" ng-if="(rental_type == 'Fixed Plus Percentage' || rental_type == 'Fixed' || rental_type == 'Fixed/Percentage w/c Higher' || rental_type == 'Fixed/Percentage/Minimum w/c Higher' || rental_type == 'Percentage' || rental_type == 'Percentage Base Tenant') && is_wht == 'Added'">
                            <label for="wht" class="col-md-4 control-label text-right invoice-label">Withholding Tax(-)</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style = "display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "wht"
                                    ui-number-mask="2"
                                    id="wht"
                                    name = "wht"
                                    autocomplete="off" >
                            </div>
                            <label class="col-md-4 control-label text-right invoice-label">{{ wht | currency : '%' }} ({{ less_witholding | currency: '&#8369;'}})</label>
                        </div>

                        <br>
                        <div class="row no-padding">
                            <label for="total_basicRental_manual" class="col-md-4 control-label text-right invoice-total">Total Basic Rental</label>
                            <div class="col-md-4">
                                <input
                                    type="text"
                                    style="display:none"
                                    class="form-control currency"
                                    readonly
                                    ng-model = "total_basicRental_manual"
                                    id="total_basicRental_manual"
                                    name = "total_basicRental_manual"
                                    autocomplete="off">
                            </div>

                            <label class="col-md-4 control-label text-right invoice-total">
                                <u ng-if = "!total_basicRental_manual">&#8369;0.00</u>
                                <u ng-if = "total_basicRental_manual">{{ total_basicRental_manual | currency : '&#8369;' }}</u>
                            </label>
                        </div>
                        <div>
                            <input type = "text" style="display:none" ng-model = "added_vat" id = "added_vat_manual" name = "added_vat"  />
                            <input type = "text" style="display:none" ng-model = "less_witholding" id = "less_witholding_manual" name = "less_witholding"  />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_basicRental_manual" class="btn btn-primary button-b"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Basic Rental Modal -->


</div> <!-- End of Container -->
</body>
