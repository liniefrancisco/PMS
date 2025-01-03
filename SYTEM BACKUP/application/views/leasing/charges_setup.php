
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Charges Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_charges_setup')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "charges in data">

                                    <td title="'Charge Type'" sortable = "'charges_type'">{{ charges.charges_type }}</td>
                                    <td title="'Charge Code'" sortable = "'charges_code'">{{ charges.charges_code }}</td>
                                    <td title="'Description'" sortable = "'description'">{{ charges.description }}</td>
                                    <td title="'UOM'" sortable = "'uom'">
                                        <div ng-if = "charges.charges_type == 'Pre Operation Charges'">
                                            {{ charges.uom }} <span>mo(s).Basic/fixed monthly rent</span>
                                        </div>
                                        <div ng-if = "charges.charges_type != 'Pre Operation Charges'">
                                            {{ charges.uom }}
                                        </div>
                                    </td>
                                    <td title="'Unit Price'" sortable = "'unit_price'" align="right">{{ charges.unit_price | currency : '&#8369;' }}</td>
                                    <td title="'With Penalty'" align="center">
                                        <span ng-if = "charges.with_penalty == 'Yes'" class = "green"><i class  = "fa fa-check"></i></span>
                                        <span ng-if = "charges.with_penalty != 'Yes'" class = "red"><i class  = "fa fa-close"></i></span>
                                    </td>

                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                    <td title="'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_chargesSetup_data/' + charges.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_charges/' + charges.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="7"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->



    <!-- Update Store Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Charges</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "{{ 'update_charges/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Type</label>
                                        <div class="col-md-8">
                                            <select class = "form-control" required name = "charges_type" ng-model = "data.charges_type">
                                                <option>Monthly Charges</option>
                                                <option>Pre Operation Charges</option>
                                                <option>Overtime Works Charges</option>
                                                <option>Construction Materials</option>
                                                <option>Penalty Charges</option>
                                                <option>Other Charges</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                readonly
                                                class="form-control"
                                                ng-model = "data.charges_code"
                                                id="charges_code"
                                                name = "charges_code">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="data.description"
                                                id="description"
                                                name = "description"
                                                autocomplete="off"
                                                is-unique-update
                                                is-unique-id = "{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_Charges_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.description.$dirty && frm_update.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_update.description.$dirty && frm_update.description.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="uom" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8" ng-show = "data.charges_type != 'Pre Operation Charges'">
                                            <select
                                                class = "form-control"
                                                name = "uom"
                                                ng-model="data.uom"
                                                id = "uom">
                                                <option>Per Kilowatt Hour</option>
                                                <option>Per Kilogram</option>
                                                <option>Per Cubic Meter</option>
                                                <option>Per Square Meter</option>
                                                <option>Per Grease Trap</option>
                                                <option>Per Feet</option>
                                                <option>Per Ton</option>
                                                <option>Per Hour</option>
                                                <option>Per Piece</option>
                                                <option>Per Contract</option>
                                                <option>Per Linear</option>
                                                <option>Per Page</option>
                                                <option>Fixed Amount</option>
                                                <option>Per Meters</option>
                                                <option>Per Unit</option>
                                                <option>Per Day</option>
                                                <option>Inputted</option>
                                            </select>
                                        </div>
                                        <div class = "row" ng-show = "data.charges_type == 'Pre Operation Charges'">
                                            <div class="col-md-2 pull-left">
                                                <input type = "number" ng-model = "data.uom" name = "numberOfmons" id="numberOfmons" class = "form-control" />
                                            </div>
                                                <span>mo(s).Basic/fixed monthly rent</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control currency"
                                                        ng-model="data.unit_price"
                                                        format="number"
                                                        id="unit_price"
                                                        name = "unit_price"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.unit_price.$dirty && frm_update.unit_price.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="unit_price" class="col-md-4 control-label text-right"></i>With Penalty</label>
                                    <div class="col-md-8">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label>
                                                <input type = "checkbox" ng-checked="data.with_penalty == 'Yes'" name = "with_penalty" value="Yes" autocomplete = "off" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->



    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Charges</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_charges" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Type</label>
                                        <div class="col-md-8">
                                            <select class = "form-control" required ng-model = "charges_type" name = "charges_type">
                                                <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                <option>Monthly Charges</option>
                                                <option>Pre Operation Charges</option>
                                                <option>Overtime Works Charges</option>
                                                <option>Construction Materials</option>
                                                <option>Penalty Charges</option>
                                                <option>Other Charges</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Charges Code</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                readonly
                                                class="form-control"
                                                value="<?php echo $charge_code; ?>"
                                                id="charges_code"
                                                name = "charges_code">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="description"
                                                id="description"
                                                name = "description"
                                                autocomplete="off"
                                                is-unique
                                                is-unique-api="../ctrl_validation/verify_Charges/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.description.$dirty && add_form.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.description.$dirty && add_form.description.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="uom" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8" ng-show = "charges_type != 'Pre Operation Charges'">
                                            <select
                                                class = "form-control"
                                                name = "uom"
                                                ng-model="uom"
                                                id = "uom">
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option>Per Kilowatt Hour</option>
                                                <option>Per Kilogram</option>
                                                <option>Per Cubic Meter</option>
                                                <option>Per Square Meter</option>
                                                <option>Per Grease Trap</option>
                                                <option>Per Feet</option>
                                                <option>Per Ton</option>
                                                <option>Per Hour</option>
                                                <option>Per Piece</option>
                                                <option>Per Contract</option>
                                                <option>Per Linear</option>
                                                <option>Per Page</option>
                                                <option>Fixed Amount</option>
                                                <option>Per Meters</option>
                                                <option>Per Unit</option>
                                                <option>Per Day</option>
                                                <option>Inputted</option>
                                            </select>
                                        </div>
                                        <div class = "row" ng-show = "charges_type == 'Pre Operation Charges'">
                                            <div class="col-md-2 pull-left">
                                                <input type = "number" name = "numberOfmons" class = "form-control" />
                                            </div>
                                                <span>mo(s).Basic/fixed monthly rent</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="unit_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Unit Price</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control currency"
                                                        ng-model="unit_price"
                                                        format="number"
                                                        id="unit_price"
                                                        name = "unit_price"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.unit_price.$dirty && add_form.unit_price.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="unit_price" class="col-md-4 control-label text-right"></i>With Penalty</label>
                                    <div class="col-md-8">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label>
                                                <input type = "checkbox" name = "with_penalty" value="Yes" autocomplete = "off" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "add_form.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- End of Container -->
</body>
