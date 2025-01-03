
<div class="container" ng-controller="transactionController">
    <div class="well" >
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Credit Memo</div>
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
                                        <form action="#"  method="post" id="frm_creditMemo" name = "frm_creditMemo">
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
                                                                    <span ng-show="frm_creditMemo.contract_no.$dirty && frm_creditMemo.contract_no.$error.required">
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
                                                                    <span ng-show="frm_creditMemo.trade_name.$dirty && frm_creditMemo.trade_name.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>

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
                                                                    <span ng-show="frm_creditMemo.rental_type.$dirty && frm_creditMemo.rental_type.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="date" class="col-md-5 control-label text-right">Posting Date</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control" 
                                                                        ng-model="posting_date"
                                                                        id="posting_date"
                                                                        name = "posting_date"
                                                                        autocomplete="off" >
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
                                                                        <span ng-show="frm_creditMemo.due_date.$dirty && frm_creditMemo.due_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF COL-MD-6 WRAPPER -->
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Reason</label>
                                                            <div class="col-md-7">  
                                                                <textarea 
                                                                    required
                                                                    name = "reason"
                                                                    class="form-control" 
                                                                    id = "reason"
                                                                    rows="3"></textarea>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_creditMemo.trade_name.$dirty && frm_creditMemo.trade_name.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Original Amount</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input 
                                                                        type="text" 
                                                                        required 
                                                                        class="form-control currency" 
                                                                        ng-model="total"
                                                                        readonly 
                                                                        format="number" 
                                                                        id="orig_amount"
                                                                        name = "orig_amount"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_creditMemo.total.$dirty && frm_creditMemo.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right"> <i class = "fa fa-plus"></i> Positive Amount</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input 
                                                                        type="text"
                                                                        class="form-control currency" 
                                                                        ng-model="positive_amount" 
                                                                        format="number" 
                                                                        id="positive_amount"
                                                                        name = "positive_amount"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_creditMemo.total.$dirty && frm_creditMemo.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right"> <i class = "fa fa-minus"></i> Negative Amount</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input 
                                                                        type="text"
                                                                        class="form-control currency" 
                                                                        ng-model="negative_amount" 
                                                                        format="number" 
                                                                        id="negative_amount"
                                                                        name = "negative_amount"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_creditMemo.total.$dirty && frm_creditMemo.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="total" class="col-md-5 control-label text-right">Total Amount</label>
                                                            <div class="col-md-7">  
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input 
                                                                        type="text" 
                                                                        required 
                                                                        class="form-control currency" 
                                                                        ng-model="total"
                                                                        readonly 
                                                                        format="number" 
                                                                        id="total_amount"
                                                                        name = "total"
                                                                        autocomplete="off">
                                                                </div>
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_creditMemo.total.$dirty && frm_creditMemo.total.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                            </div>
                                            
                                            <div class="row"> <!-- EDITABLE GRID ROW -->
                                                <div class = "row text-center" style="margin-left:50px" ng-if = "charges_type == 'Other'">
                                                    <a 
                                                        ng-if = "tenancy_type == 'Long Term'"
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#preop_charges" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "charges_description('<?php echo base_url(); ?>index.php/leasing_transaction/charges_description/Pre Operation Charges')"><i class  = "fa fa-plus-circle"></i> Pre Operation Charges
                                                    </a>
                                                    <a 
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#penalty_charges"  
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "charges_description('<?php echo base_url(); ?>index.php/leasing_transaction/charges_description/Penalty Charges')"><i class  = "fa fa-plus-circle"></i> Penalty Charges
                                                    </a>
                                                    <a 
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#monthly_charges"
                                                        ng-click = "get_monthlyCharges('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthlyCharges/<?php echo $tenant_id; ?>')" 
                                                        class = "btn btn-tiny btn-fresh"><i class  = "fa fa-plus-circle"></i> Monthly Charges
                                                    </a>
                                                    <a 
                                                        ng-if = "tenancy_type == 'Long Term'"
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#penalty_charges" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "charges_description('<?php echo base_url(); ?>index.php/leasing_transaction/charges_description/Overtime Works Charges')"><i class  = "fa fa-plus-circle"></i> Ovetime Works Charges
                                                    </a>
                                                    <a 
                                                        href="#" 
                                                        data-backdrop="static" data-keyboard="false" 
                                                        data-toggle="modal" 
                                                        data-target="#penalty_charges" 
                                                        class = "btn btn-tiny btn-fresh"
                                                        ng-click = "charges_description('<?php echo base_url(); ?>index.php/leasing_transaction/charges_description/Other Charges')"><i class  = "fa fa-plus-circle"></i> Other Charges
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
                                                       		<td><a ng-if = "charge.charges_type == 'Other'" class = 'btn-sm btn-danger' onClick='deleteme(this)'  href = '#'><i class = 'fa fa-trash'></i></a></td>
                                                       </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- EDITABLE GRID END ROW -->
                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class = "col-md-12 text-right">
                                                    <button type = "button" data-toggle="modal" data-target="#managerkey_modal" ng-disabled = "frm_creditMemo.$invalid" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Post</button>
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
                                        <option ng-repeat = "desc in chargeDesc">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in chargeDetails">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">  
                                        <input type = "
                                        text" ng-model = "detail.charges_code" id = "preOp_chargeCode" name = "preOp_chargeCode" class = "form-control" readonly >
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
                                            format = "number" 
                                            ng-model = "basic_rental" 
                                            id="basic_rental" 
                                            name = "basic_rental" 
                                            class = "form-control currency" 
                                            ng-keyup = "preOp_actualAmt(detail.uom, basic_rental)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">  
                                        <input type = "text" readonly format = "number" ng-model = "actual_amount" id="preOp_actualAmt" name = "actual_amount" class = "form-control currency" >
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



<!-- Add Penalty Charges Modal -->
<div class="modal fade" id = "penalty_charges">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Add Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id = "charges_wrapper">
                        <div class = "row">
                            <div class="form-group">
                                <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                <div class="col-md-8">  
                                    <select required name = "preOp_desc" id="penalty_desc" ng-model = "desc.description" class = "form-control" ng-change = "get_chargeDetails('<?php echo base_url(); ?>index.php/leasing_transaction/chargeDetails/' + desc.description)">
                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                        <option ng-repeat = "desc in chargeDesc">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in chargeDetails">
                            <input type = "text" style="display:none" id = "charges_type" ng-model = "detail.charges_type">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">  
                                        <input type = "text" ng-model = "detail.charges_code" id = "penalty_chargeCode" name = "preOp_chargeCode" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>

                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>UOM</label>
                                    <div class="col-md-8">  
                                        <input type = "text" ng-model = "detail.uom" id = "penalty_oum" name = "penalty_oum" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom != 'Inputted'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                    <div class="col-md-8">  
                                        <input 
                                            type = "text" 
                                            format = "number" 
                                            ng-model = "detail.unit_price" 
                                            id="penalty_unitPrice"
                                            readonly 
                                            name = "unit_price" 
                                            class = "form-control currency" 
                                            ng-keyup = "penalty_actualAmt(detail.total_unit, detail.unit_price)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom != 'Inputted'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                    <div class="col-md-8">  
                                        <input 
                                            type = "text" 
                                            format = "number" 
                                            ng-model = "detail.total_unit" 
                                            id="penalty_totalUnit" 
                                            name = "penalty_totalUnit" 
                                            class = "form-control currency" 
                                            ng-keyup = "penalty_actualAmt(detail.total_unit, detail.unit_price)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom != 'Inputted'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">  
                                        <input type = "text" readonly format = "number" ng-model = "actual_amount" id="penalty_actualAmt" name = "actual_amount" class = "form-control currency" >
                                    </div>
                                </div>
                            </div>
                            <div class = "row" ng-if = "detail.uom == 'Inputted'">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">  
                                        <input type = "text" format = "number" ng-model = "actual_amount" id="penalty_inputtedAmt" name = "actual_amount" class = "form-control currency" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_penalty_charges" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Penalty Charges Modal -->


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
                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
                            <div class="form-group">
                                <label for="gross_sales" class="col-md-4 control-label text-right invoice-label">Gross Sales</label>
                                <div class="col-md-5 pull-right">
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input 
                                            type="text" 
                                            class="form-control currency"
                                            ng-model = "gross_sales" 
                                            format="number"
                                            id="gross_sales"
                                            name = "gross_sales"
                                            autocomplete="off" 
                                            ng-keyup="inputted_gross(gross_sales)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-4 col-md-offset-8 control-label invoice-label text-right red">(X 12%) 1.12%</label>
                                </div>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
                            <div class="form-group">
                                <label for="total_gross" class="col-md-4 control-label text-right invoice-label">Total Gross Sales</label>   
                                <label ng-if = "total_gross" class="col-md-4 pull-right control-label text-left invoice-label"> {{total_gross | currency : '&#8369;' }}</label> 
                                <label ng-if = "!total_gross" class="col-md-4 pull-right control-label text-left invoice-label">&#8369;0.00</label>       
                            </div>
                        </div>

                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
                            <div class="form-group">
                                <label for="rent_percentage" class="col-md-4 control-label text-right invoice-label">Rent Percentage</label>
                                <div class="col-md-4"></div>
                                <label class="col-md-4 control-label text-left invoice-label"> {{rent_percentage | currency : '%' }}</label> 
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Basic Rental plus VAT'">
                            <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Basic Rental</label>
                            <div class="col-md-4">  
                                <input 
                                    type = "text"
                                    style = "display:none"
                                    class = "form-control currency"
                                    readonly
                                    ng-model = "basic_rental" 
                                    format = "number"
                                    id = "basic_rental"
                                    name = "basic_rental"
                                    autocomplete = "off" >
                            </div>
                            <label class="col-md-4 control-label text-left invoice-label">{{ monthly_rental | currency : '&#8369;' }}</label>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
                            <div class="form-group">
                                <label for="rent_sale" class="col-md-4 control-label text-right invoice-label">Rentable Sales</label>
                                <label ng-if = "rent_sale" class="col-md-4 pull-right control-label text-left invoice-label">{{ rent_sale | currency : '&#8369;' }}</label>
                                <input type = "text" style = "display:none" name = "rent_sale" id = "rent_sale" ng-model = "rent_sale" />
                                <label ng-if = "!rent_sale" class="col-md-4 pull-right control-label text-left invoice-label">&#8369; 0.00</label>
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

                                <label class="col-md-4 control-label text-left invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="(rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT' || rental_type == 'Basic Rental plus VAT') && is_vat == 'added'">
                            <label for="less_vat" class="col-md-4 control-label invoice-label text-right">VAT(+)</label>
                            <div class="col-md-4">
                                <input 
                                    type="text" 
                                    style = "display:none" 
                                    class="form-control currency"
                                    readonly
                                    ng-model = "vat" 
                                    format="number"
                                    id="less_vat"
                                    name = "less_vat"
                                    autocomplete="off"> 
                            </div>
                            <label class="col-md-4 control-label text-left invoice-label">{{ vat | currency : '%' }}</label>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic Rental plus VAT less withholding Taxes'">
                            <label for="wht" class="col-md-4 control-label text-right invoice-label">Withholding Tax(-)</label>
                            <div class="col-md-4">
                                <input 
                                    type="text" 
                                    style = "display:none" 
                                    class="form-control currency"
                                    readonly
                                    ng-model = "wht" 
                                    format="number"
                                    id="wht"
                                    name = "wht"
                                    autocomplete="off" >
                            </div>
                            <label class="col-md-4 control-label text-left invoice-label">{{ wht | currency : '%' }}</label>
                        </div>
                        
                        <br>              
        
                        <div class="row" ng-if="rental_type == 'Percentage Rental plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT less withholding Taxes' || rental_type == 'Basic plus Percentage plus VAT' || rental_type == 'Percentage Rental plus VAT'">
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
                                <label ng-if = "!total_rentable" class="col-md-4 control-label text-left invoice-total">&#8369;0.00</label>
                                <label ng-if = "total_rentable" class="col-md-4 control-label text-left invoice-total">{{ total_rentable | currency : '&#8369;' }}</label>
                            </div>
                        </div>
                        <div class="row no-padding" ng-if="rental_type == 'Basic Rental plus VAT less withholding Taxes' || rental_type == 'Basic Rental plus VAT'">
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
                            
                            <label class="col-md-4 control-label text-left invoice-total"><u>{{ total_basicRental | currency : '&#8369;' }}</u></label>
                        </div>
                        <div>
                            <input type = "text" style="display:none" ng-model = "added_vat" id = "added_vat" name = "added_vat"  />
                            <input type = "text" style="display:none" ng-model = "less_witholding" id = "less_witholding" name = "less_witholding"  />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_basicRental" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Basic Rental Modal -->


<!-- Add Monthly Charges Modal -->
<div class="modal fade" id = "monthly_charges">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Monthly Charges</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id = "charges_wrapper">
                        <div class = "row">
                            <div class="form-group">
                                <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                <div class="col-md-8">  
                                    <select required name = "monthly_charges" id="monthly_desc" ng-model = "desc.description" class = "form-control" ng-change="get_monthlyCharges_details('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthlyCharges_details/' + desc.description)">
                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                        <option ng-repeat = "desc in monthly_charges">{{ desc.description }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id = "chargeDetail_holder" ng-repeat = "detail in monthly_details">
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                    <div class="col-md-8">  
                                        <input type = "text" ng-model = "detail.charges_code" id = "monthly_chargeCode" name = "monthly_chargeCode" class = "form-control" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                    <div class="col-md-8">
                                        <input type = "text" readonly ng-model = "detail.uom" id="monthly_uom" name = "monthly_uom" class = "form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                    <div class="col-md-8">  
                                        <input 
                                        type = "text" 
                                            format = "number" 
                                            ng-model = "detail.unit_price" 
                                            id="monthly_unit_price" 
                                            name = "unit_price" 
                                            class = "form-control currency" 
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="total_unit" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Total Unit</label>
                                    <div class="col-md-8">  
                                        <input 
                                            type = "text" 
                                            format = "number" 
                                            ng-model = "total_unit" 
                                            id="monthly_total_unit" 
                                            name = "total_unit" 
                                            class = "form-control currency"
                                            ng-keyup = "monthly_actualAmt(detail.unit_price, total_unit)">
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Amount</label>
                                    <div class="col-md-8">  
                                        <input type = "text" readonly format = "number" ng-model = "actual_amount" id="monthly_actualAmt" name = "actual_amount" class = "form-control currency" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
                <div class="modal-footer">
                    <a href="#" id="append_monthly_charges" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Monthly Charges Modal -->



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
                                <label class="col-md-4 control-label text-left invoice-label" style="padding-left: 105px;">
                                    <span ng-if = "discount.discount_type == 'Percentage'">{{ discount.discount | currency : '%' }}</span>
                                    <span ng-if = "discount.discount_type != 'Percentage'">{{ discount.discount | currency : '&#8369;' }}</span>
                                </label>
                            </div>
                        </div>
                        <br>
                        <div class="row no-padding" ng-if="sTerm_isvat == 'added'">
                            <div class="col-md-12">
                                <label for="vat" class="col-md-4 control-label text-right invoice-label">VAT(+)</label>
                                <div class="col-md-4">  
                                    <input 
                                        type = "text"
                                        style = "display:none"
                                        format = "number" 
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
                                        format = "number" 
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
                    <a href="#" id="append_shortTerm_charges" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- Short Term Modal -->

    
<!-- Manager's Key Modal -->
<div class="modal fade" id = "managerkey_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
            </div>
            <form action="#"  method="post" id="frm_managerKey" name = "frm_managerKey">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-addon squared"><i class = "fa fa-user"></i></div>
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control" 
                                                    ng-model="username"
                                                    id="username"
                                                    name = "username"
                                                    autocomplete="off" >
                                        </div>
                                         <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_managerKey.username.$dirty && frm_managerKey.username.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-addon squared"><i class = "fa fa-key"></i></div>
                                                <input 
                                                    type="password" 
                                                    required
                                                    class="form-control" 
                                                    ng-model="password"
                                                    id="password"
                                                    name = "password"
                                                    autocomplete="off" >
                                        </div>
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_managerKey.password.$dirty && frm_managerKey.password.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                    </div>
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button type="button" onclick="save_creditMemo('<?php echo base_url(); ?>index.php/leasing_transaction/save_creditMemo/')" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-save"></i> Submit</button>
                    <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Tag as Modal -->




    
</div> <!-- End of Container -->
</body>