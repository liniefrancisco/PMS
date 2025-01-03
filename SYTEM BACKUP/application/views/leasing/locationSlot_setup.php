
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-ticket"></i> Location Slot Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator') : ?>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationSlot')">
                            <thead>
                                <tr>
                                    <th><a href="#" data-ng-click="sortField = 'id'; reverse = !reverse">ID</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'slot_no'; reverse = !reverse">Slot No.</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'tenancy_type'; reverse = !reverse">Tenancy Type</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Location</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor Area</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'rental_rate'; reverse = !reverse">Rental Rate</a></th>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                    <th>Action</th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "dt in data">
                                     <td title = "'ID'" sortable = "'id'" align="center">{{ dt.id }}</td>
                                    <td title = "'Slot No.'" sortable = "'slot_no'">{{ dt.slot_no }}</td>
                                    <td title = "'Tenancy Type'" sortable = "'tenancy_type'">{{ dt.tenancy_type }}</td>
                                    <td title = "'Store/Property'" sortable = "'store_name'">{{ dt.store_name }}</td>
                                    <td title = "'Floor Location'" sortable = "'floor_name'">{{ dt.floor_name }}</td>
                                    <td title = "'Floor Area'" sortable = "'floor_area'" align="right">{{ dt.floor_area | currency : '' }}</td>
                                    <td title = "'Rental Rate'" sortable = "'rental_rate'" align="right">{{ dt.rental_rate | currency : '&#8369;' }}</td>

                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                    <td align="right" title = "'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationSlot_data/' + dt.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/view_3D/{{data.model}}" target="_blank"  > <i class = "fa fa-3D"></i> View Location</a></li> -->
                                                <li>
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                        <a
                                                            href="#"
                                                            data-toggle="modal"
                                                            ng-click = "delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_locationSlot/' + dt.id)"
                                                            data-target="#confirmation_modal">
                                                            <i class = "fa fa-trash"></i> Delete
                                                        </a>
                                                    <?php else: ?>
                                                        <a
                                                            href="#"
                                                            data-toggle="modal"
                                                            ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_locationSlot/' + dt.id)"
                                                            data-target="#manager_modal">
                                                            <i class = "fa fa-trash"></i> Delete
                                                        </a>
                                                    <?php endif ?>
                                                        <a
                                                            href="#"
                                                            data-toggle="modal"
                                                            ng-click = "loadList2('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationCodeSlotHistory/' + dt.id)"
                                                            data-target="#locationHistory">
                                                            <i class = "fa fa-history"></i> View History
                                                        </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="12"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->


    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Location Slot</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_locationSlot" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Slot No.</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    class="form-control emphasize"
                                                    ng-model="slot_no"
                                                    id="slot_no"
                                                    name = "slot_no"    
                                                    autocomplete="off" 
                                                    is-unique
                                                    is-unique-api="../ctrl_validation/verify_slot/">

                                                    <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.slot_no.$dirty && add_form.slot_no.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    <span ng-show="add_form.slot_no.$dirty && add_form.slot_no.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Tenancy Type</label>
                                            <div class="col-md-8">
                                                <select
                                                    name = "tenancy_type"
                                                    ng-model = "tenancy_type"
                                                    required
                                                    class="form-control"
                                                    ng-change = "populate_rentPeriod('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_rentPeriod/' + tenancy_type)"
                                                    onchange = "deleteFirstIndex('add_rent_period')">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Long Term</option>
                                                    <option>Short Term</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store/Property</label>
                                            <div class="col-md-8">

                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        name = "store_name"
                                                        required
                                                        ng-model = "store_name"
                                                        class="form-control"
                                                        ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + store_name);get_prefix(store_name)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($stores as $store): ?>
                                                            <option><?php echo $store['store_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input type = "text" readonly name = "store_name" required class="form-control" value="<?php echo $stores; ?>">
                                                <?php endif ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Divider -->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        name = "floor_name"
                                                        required
                                                        ng-model = "floor_name"
                                                        class="form-control">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="floor in itemList">{{ floor.floor_name }}</option>
                                                    </select>
                                                <?php else: ?>
                                                    <select name = "floor_name" ng-model = "floor_name" required class="form-control">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($store_floors as $floor): ?>
                                                            <option><?php echo $floor['floor_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Area</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ng-model="floor_area"
                                                            id="floor_area"
                                                            ui-number-mask="2"
                                                            name = "floor_area"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="add_form.floor_area.$dirty && add_form.floor_area.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="rental_rate" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rental Rate</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ng-model="rental_rate"
                                                            id="rental_rate"
                                                            ui-number-mask="2"
                                                            name = "rental_rate"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="add_form.rental_rate.$dirty && add_form.rental_rate.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
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




    <!-- Update Store Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Location Slot</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form  action = "{{ 'update_locationSlot/'}}" method="post" name = "frm_update" id = "frm_update">
                        <input type = "text" style = "display:none" ng-model = "data.id" = name = "id_to_update" />
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="slot_no" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Slot No.</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    class="form-control emphasize"
                                                    ng-model="data.slot_no"
                                                    id="slot_no"
                                                    name = "slot_no"
                                                    autocomplete="off" 
                                                    is-unique-update
                                                    is-unique-id = "{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_slotUpdate/">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.slot_no.$dirty && frm_update.slot_no.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="frm_update.slot_no.$dirty && frm_update.slot_no.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Tenancy Type</label>
                                            <div class="col-md-8">
                                                <select
                                                    name = "tenancy_type"
                                                    ng-model = "data.tenancy_type"
                                                    required
                                                    class="form-control">
                                                    <option>Long Term</option>
                                                    <option>Short Term</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store/Property</label>
                                            <div class="col-md-8">

                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        name = "store_name"
                                                        required
                                                        ng-model = "data.store_name"
                                                        class="form-control"
                                                        onchange="deleteFirstIndex('floor_name')"
                                                        ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + data.store_name);get_prefix2(data.store_name)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($stores as $store): ?>
                                                            <option><?php echo $store['store_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input type = "text" readonly name = "store_name" required class="form-control" value="<?php echo $stores; ?>">
                                                <?php endif ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Divider -->
                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        name = "floor_name"
                                                        id="floor_name"
                                                        required
                                                        class="form-control">
                                                        <option>{{ data.floor_name }}</option>
                                                        <option ng-repeat="floor in itemList">{{ floor.floor_name }}</option>
                                                    </select>
                                                <?php else: ?>
                                                    <select name = "floor_name" id = "floor_name" ng-model = "data.floor_name" required class="form-control">
                                                        <?php foreach ($store_floors as $floor): ?>
                                                            <option><?php echo $floor['floor_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php endif ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Area</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ng-model="data.floor_area"
                                                            id="floor_area"
                                                            ui-number-mask="2"
                                                            name = "floor_area"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_update.floor_area.$dirty && frm_update.floor_area.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="rental_rate" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rental Rate</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ng-model="data.rental_rate"
                                                            id="rental_rate"
                                                            ui-number-mask="2"
                                                            name = "rental_rate"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_update.rental_rate.$dirty && frm_update.rental_rate.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>
                                            </div>
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





    <!-- View Store Modal -->
    <div class="modal fade" id = "view_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i>Location Code Details</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="location_code" class="col-md-4 control-label text-right"></i>Location Code</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                readonly
                                                class="form-control emphasize"
                                                ng-model="data.location_code"
                                                id="location_code"
                                                name = "location_code"
                                                is-unique-update
                                                is-unique-id = "{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_locationCode_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.location_code.$dirty && frm_update.location_code.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_update.location_code.$dirty && frm_update.location_code.$error.minlength">
                                                    <p class="error-display">Minimum of 5 characters</p>
                                                </span>
                                                <span ng-show="frm_update.location_code.$dirty && frm_update.location_code.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Tenancy Type</label>
                                        <div class="col-md-8">
                                            <select name = "tenancy_type" ng-model = "data.tenancy_type" disabled required class="form-control">
                                                <option>Long Term</option>
                                                <option>Short Term</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Store/Property</label>
                                        <div class="col-md-8">

                                            <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                <select
                                                    name = "store_name"
                                                    required
                                                    disabled
                                                    ng-model = "data.store_name"
                                                    class="form-control"
                                                    onchange="deleteFirstIndex('floor_name')"
                                                    ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + data.store_name)">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <?php foreach ($stores as $store): ?>
                                                        <option><?php echo $store['store_name']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php else: ?>
                                                <input type = "text" readonly name = "store_name" required class="form-control" value="<?php echo $stores; ?>">
                                            <?php endif ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right"></i>Floor Location</label>
                                        <div class="col-md-8">
                                            <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                <select
                                                    disabled
                                                    name = "floor_name"
                                                    id="floor_name"
                                                    required
                                                    class="form-control">
                                                    <option>{{ data.floor_name }}</option>
                                                    <option ng-repeat="floor in itemList">{{ floor.floor_name }}</option>
                                                </select>
                                            <?php else: ?>
                                                <select disabled name = "floor_name" id = "floor_name" ng-model = "data.floor_name" required class="form-control">
                                                    <?php foreach ($store_floors as $floor): ?>
                                                        <option><?php echo $floor['floor_name']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php endif ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Classification</label>
                                        <div class="col-md-8">
                                            <select name = "area_classification" disabled ng-model = "data.area_classification" required class="form-control">
                                                <?php foreach ($area_classification as $value): ?>
                                                    <option><?php echo $value['classification']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Divider -->
                            <div class="col-md-6">

                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Area Type</label>
                                        <div class="col-md-8">
                                            <select name = "area_type" disabled required class="form-control" ng-model = "data.area_type">
                                                <?php foreach ($area_type as $value): ?>
                                                    <option><?php echo $value['type']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Rent Period</label>
                                        <div class="col-md-8">
                                            <input name = "rent_period" readonly ng-model = "data.rent_period" required class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="floor_area" class="col-md-4 control-label text-right">Floor Area</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                    <input
                                                        type="text"
                                                        required
                                                        readonly
                                                        class="form-control currency"
                                                        ng-model="data.floor_area"
                                                        id="floor_area"
                                                        ui-number-mask="2"
                                                        name = "floor_area"
                                                        autocomplete="off" >
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.floor_area.$dirty && frm_update.floor_area.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="" class="col-md-4 control-label text-right">Mode of Payment</label>
                                        <div class="col-md-8">
                                            <select name = "payment_mode" disabled ng-model = "data.payment_mode" required class="form-control">
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option>Monthly Basis</option>
                                                <option>Full Payment</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="rental_rate" class="col-md-4 control-label text-right">Rental Rate</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input
                                                        type="text"
                                                        required
                                                        readonly
                                                        class="form-control currency"
                                                        ng-model="data.rental_rate"
                                                        id="rental_rate"
                                                        ui-number-mask="2"
                                                        name = "rental_rate"
                                                        autocomplete="off" >
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.rental_rate.$dirty && frm_update.rental_rate.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->

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
                    <button type="button" onclick="update_locationCode('<?php echo base_url(); ?>index.php/leasing_mstrfile/update_locationCode/')" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-key"></i> Submit</button>
                    <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Manager's Key Modal -->



<!-- Tenant Lookup Modal -->
    <div class="modal fade" id = "locationHistory" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> Location Slot History</h4>
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
                                        <th><a href="#" data-ng-click="sortField = 'slot_no'; reverse = !reverse">Slot No.</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'tenancy_type'; reverse = !reverse">Tenancy Type</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Location</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor Area</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'rental_rate'; reverse = !reverse">Rental Rate</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'modified_date'; reverse = !reverse">Date Modified</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-show="dataList2.length!=0" ng-repeat= "data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
                                        <td>{{data.slot_no}}</td>
                                        <td>{{data.tenancy_type}}</td>
                                        <td>{{data.store_name}}</td>
                                        <td>{{data.floor_name}}</td>
                                        <td align="right">{{ data.floor_area | currency : '' }}</td>
                                        <td align="right">{{ data.rental_rate | currency : '&#8369;' }}</td>
                                        <td>{{data.modified_date}}</td>
                                    </tr>
                                    <tr class="ng-cloak" ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
                                        <td colspan="7"><center>No Data Available.</center></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="ng-cloak">
                                        <td colspan="7" style="padding: 5px;">
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


</div> <!-- /.container -->
</body>
