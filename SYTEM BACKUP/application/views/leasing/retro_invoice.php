
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Retro Invoicing</div>
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
                                        <form action="" onSubmit="save_retro('<?php echo base_url() ?>index.php/leasing_transaction/save_retro/'); return false"   method="post" id="frm_retro" name = "frm_retro">
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
                                                                            id = "tenant_id">
                                                                    </div>
                                                                    <span class="input-group-btn">
                                                                        <button
                                                                            class="btn btn-info"
                                                                            type="button"
                                                                            ng-click = "generate_invoicingRetro(dirty.value)"><i class = "fa fa-search"></i>
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
                                                                    <span ng-show="frm_retro.contract_no.$dirty && frm_retro.contract_no.$error.required">
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
                                                                    <span ng-show="frm_retro.tenant_id.$dirty && frm_retro.tenant_id.$error.required">
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
                                                                    <span ng-show="frm_retro.rental_type.$dirty && frm_retro.rental_type.$error.required">
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
                                                    <button type = "submit"  ng-disabled = "frm_charges.$invalid" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Save Invoice</button>
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


    <!-- Short Term Modal -->
    <div class="modal fade" id = "retro_charges">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Retro Charges</h4>
                </div>
                <div class="modal-body">
                    <div class="row no-padding">
                        <div class="col-md-12">
                            <div class="row no-padding">
                                <div class="col-md-12">
                                    <label for="tenant_id" class="col-md-4 control-label text-right invoice-label">Retro Amount</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-addon"><strong>&#8369;</strong></div>
                                            <input
                                                type = "text"
                                                class="form-control currency"
                                                name = "retro_amount"
                                                ng-model = "retro_amount"
                                                ng-keyup = "inputted_retro(retro_amount)"
                                                ui-number-mask="2">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <br>
                            <div class="row no-padding" ng-if="is_vat == 'Added'">
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
                                    <label class="col-md-4 control-label text-left invoice-label">{{ vat | currency : '%' }}({{added_vat | currency : '&#8369;'}})</label>
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
                                    <label class="col-md-4 control-label text-left invoice-label">{{ wht | currency : '%' }}({{less_witholding | currency : '&#8369;'}})</label>
                                </div>
                            </div>
                            <br>
                            <div class = "row no-padding">
                                <div class="col-md-12">
                                    <label for="sTerm_totalRental" class="col-md-4 control-label text-right invoice-total">Total Retro</label>
                                    <div class="col-md-4">
                                        <input
                                            type = "text"
                                            style = "display:none"
                                            ng-model = "total_retro"
                                            id="total_retro"
                                            name = "total_retro"
                                            class = "form-control currency">
                                            <input type = "text" style = "display:none" name = "added_vat" id = "added_vat" ng-model = "added_vat" />
                                            <input type = "text" style = "display:none" name = "less_witholding" id = "less_witholding" ng-model = "less_witholding" />
                                    </div>
                                    <label class="col-md-4 control-label text-left invoice-total"><u>{{ total_retro | currency : '&#8369;' }}</u></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="append_retro_charges" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- Short Term Modal -->


</div> <!-- End of Container -->
</body>
