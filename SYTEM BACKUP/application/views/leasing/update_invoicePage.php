
<div class="container" ng-controller="transactionController">
    <div class="well" >
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Update Invoice</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#updateInvoice" aria-controls="preop" role="tab" data-toggle="tab">General</a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="updateInvoice">
                                <div class="col-md-11" ng-init = "get_invoiceForUpdate('<?php echo base_url(); ?>index.php/leasing_transaction/invoice_primaryDetails/<?php echo $tenant_id ;?>/<?php echo $doc_no; ?>')">
                                    <div class="row">
                                        <form action="#"  method="post" id="frm_invoice" name = "frm_invoice">
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
                                                                    ng-model="doc_no"
                                                                    id="doc_no"
                                                                    name = "doc_no"
                                                                    autocomplete="off">
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
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-5 control-label text-right">Contract No.</label>
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
                                                                    <span ng-show="frm_invoice.contract_no.$dirty && frm_invoice.contract_no.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-5 control-label text-right">Trade Name</label>
                                                            <div class="col-md-7">  
                                                                <input 
                                                                    type="text" 
                                                                    required
                                                                    readonly 
                                                                    class="form-control" 
                                                                    ng-model="trade_name"
                                                                    id="trade_name"
                                                                    name = "trade_name"
                                                                    autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_invoice.trade_name.$dirty && frm_invoice.trade_name.$error.required">
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
                                                                    ng-required="tenancy_type == 'Long Term Tenant'"
                                                                    readonly 
                                                                    class="form-control" 
                                                                    ng-model="rental_type" 
                                                                    id="rental_type"
                                                                    name = "rental_type"
                                                                    autocomplete="off" >
                                                                    <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_invoice.rental_type.$dirty && frm_invoice.rental_type.$error.required">
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
                                                                        ng-model = "transaction_date"
                                                                        id="transaction_date"
                                                                        name = "transaction_date"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="due_date" class="col-md-5 control-label text-right">Posting Date</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-min-limit="<?php echo date('Y-m-d'); ?>" date-format="yyyy-M-dd">
                                                                        <input 
                                                                            type="text" 
                                                                            required 
                                                                            readonly
                                                                            class="form-control" 
                                                                            ng-model="posting_date"
                                                                            name = "posting_date"
                                                                            autocomplete="off">
                                                                     </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_invoice.posting_date.$dirty && frm_invoice.posting_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="due_date" class="col-md-5 control-label text-right">Due Date</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-min-limit="<?php echo date('Y-m-d'); ?>" date-format="yyyy-M-dd">
                                                                        <input 
                                                                            type="text" 
                                                                            required 
                                                                            readonly
                                                                            placeholder="Choose a date" 
                                                                            class="form-control" 
                                                                            ng-model="due_date"
                                                                            name = "due_date"
                                                                            autocomplete="off">
                                                                     </datepicker>

                                                                     <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="frm_invoice.due_date.$dirty && frm_invoice.due_date.$error.required">
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
                                                                        required 
                                                                        class="form-control currency" 
                                                                        ng-model="total"
                                                                        readonly 
                                                                        ui-number-mask="2" 
                                                                        id="total_amount"
                                                                        name = "total"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_invoice.total.$dirty && frm_invoice.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            
                                            <div class="row" > <!-- EDITABLE GRID ROW -->
                                                <div class = "row text-center" style="margin-left:50px">
                                                    <a 
                                                        ng-if = "tenancy_type == 'Long Term'"
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#preop_charges" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_preopCharges('<?php echo base_url(); ?>index.php/leasing_transaction/get_preopCharges/')"><i class  = "fa fa-plus-circle"></i> Pre Operation Charges
                                                    </a>
                                                    <a 
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#constMat" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_constMat('<?php echo base_url(); ?>index.php/leasing_transaction/get_constMat/')"><i class  = "fa fa-plus-circle"></i> Contruction Materials
                                                    </a>
                                                    <a 
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#other_charges" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "get_otherCharges('<?php echo base_url(); ?>index.php/leasing_transaction/get_otherCharges/')"><i class  = "fa fa-plus-circle"></i> Other Charges
                                                    </a>
                                                    
                                                </div>
                                                <table class="table table-bordered" ng-init = "draft_invoiceCharges('<?php echo base_url(); ?>index.php/leasing_transaction/draft_invoiceCharges/<?php echo $tenant_id; ?>/<?php echo $doc_no; ?>')" id="charges_table" style="margin-left:50px">
                                                    <thead>
                                                        <tr>
                                                            <th ><a href="#" data-ng-click="sortField = 'charges_type'; reverse = !reverse">Charges Type</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'charges_code'; reverse = !reverse">Charges Code</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Description</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">UOM</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = 'unit_price'; reverse = !reverse">Unit Price</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Total Unit</a></th>
                                                            <th ><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Amount</a></th>
                                                            <th width="8%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat = "charge in chargeList">
                                                       		<td> <input type = "text" style="display:none" name = "charges_type[]" ng-model = "charge.charges_type" />  {{ charge.charges_type }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "charges_code[]" ng-model = "charge.charges_code" />  {{ charge.charges_code }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "description[]" ng-model = "charge.description" />  {{ charge.description }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "uom[]" ng-model = "charge.uom" />  {{ charge.uom }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "unit_price[]" ng-model = "charge.unit_price" />  {{ charge.unit_price | currency : '' }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "total_unit[]" ng-model = "charge.total_unit" />  {{ charge.total_unit | currency : '' }}</td>
                                                       		<td> <input type = "text" style="display:none" name = "actual_amount[]" ng-model = "charge.amount" />  {{ charge.amount | currency : '&#8369;' }}</td>
                                                       		<td><a ng-if = "charge.charges_type == 'Other' || charge.charges_type == 'Construction Materials' ||  charge.charges_type == 'Pre Operation Charges'" class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>
                                                       </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "button" onclick="check_invoiceTag()" ng-disabled = "frm_invoice.$invalid" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Save Invoice</button>
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
                                        <input type = "text" readonly ui-number-mask="2" ng-model = "preOp_amount" id="preOp_actualAmt" name = "actual_amount" class = "form-control currency" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_preOp_charges" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Pre Operation Modal -->



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
                    <a href="#" onclick="append_otherCharges()" ng-click = "clear_totalUnit_actualAmount()" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Monthly Charges Modal -->



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
                    <a href="#" id="append_constMat"  ng-click = "clear_totalUnit_actualAmount()" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Other Charges Modal -->


    
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
                                        <label for="inlineRadio1"> Draft </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="Posted" value="Posted" name="tag">
                                        <label for="inlineRadio2"> Posted </label>
                                    </div>
                                </div>
                            </div>
                        </div>        
                    </div>
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button type="button" onclick="save_draftInvoice('<?php echo base_url(); ?>index.php/leasing_transaction/save_draftInvoice/')" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-save"></i> Proceed</button>
                </div>
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Tag as Modal -->




    
</div> <!-- End of Container -->
</body>