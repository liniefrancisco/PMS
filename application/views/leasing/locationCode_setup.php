
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-ticket"></i> Location Code Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                            <div class="col-md-3 pull-left">
                                <a href="#" data-backdrop="static" ng-click = "generate_locationCodeID()" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium button-g"><i class = "fa fa-plus-circle"></i> Add Data</a>
                            </div>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationCode')">
                            <thead>
                                <tr>
                                    <th><a href="#" data-ng-click="sortField = 'location_code'; reverse = !reverse">Location Code</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'tenancy_type'; reverse = !reverse">Tenancy Type</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Location</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor Area</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'area_classification'; reverse = !reverse">Classification</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'area_Type'; reverse = !reverse">Type</a></th>
                                    <th><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Rent Period</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'rental_rate'; reverse = !reverse">Rental Rate</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'status'; reverse = !reverse">Status</a></th>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                            <th>Action</th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "data in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ data.location_code }}</td>
                                    <td>{{ data.tenancy_type }}</td>
                                    <td>{{ data.store_name }}</td>
                                    <td>{{ data.floor_name }}</td>
                                    <td>{{ data.floor_area | currency : '' }}</td>
                                    <td>{{ data.area_classification }}</td>
                                    <td>{{ data.area_type }}</td>
                                    <td>{{ data.rent_period }} </td>
                                    <td>{{ data.rental_rate | currency : '&#8369;' }}</td>
                                    <td>
                                        <div ng-if = "data.status == 'Vacant'"><span class = "green">{{ data.status }}</span></div>
                                         <div ng-if = "data.status != 'Vacant'"><span class = "red">{{ data.status }}</span></div>
                                    </td>

                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                            <td align="center">
                                                <!-- Split button -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-danger button-caret">Action</button>
                                                    <button type="button" class="btn btn-xs btn-danger dropdown-toggle button-caret" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#view_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationCode_data/' + data.id)"> <i class = "fa fa-eye"></i> VIew Details</a></li>
                                                        <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_locationCode_data/' + data.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                        <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/view_3D/{{data.model}}" target="_blank"  > <i class = "fa fa-3D"></i> View Location</a></li>
                                                        <li>
                                                            <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                                        <a
                                                                            href="#"
                                                                            data-toggle="modal"
                                                                            ng-click = "delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_locationCode/' + data.id)"
                                                                            data-target="#confirmation_modal">
                                                                            <i class = "fa fa-trash"></i> Delete
                                                                        </a>
                                                            <?php else: ?>
                                                                        <a
                                                                            href="#"
                                                                            data-toggle="modal"
                                                                            ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_locationCode/' + data.id)"
                                                                            data-target="#manager_modal">
                                                                            <i class = "fa fa-trash"></i> Delete
                                                                        </a>
                                                            <?php endif ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="12"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="12" style="padding: 5px;">
                                        <div>
                                            <ul class="pagination">
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="prevPageDisabled()">
                                                    <a href ng-click="prevPage()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)">
                                                    <a href="#">{{n+1}}</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="nextPageDisabled()">
                                                    <a href ng-click="nextPage()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
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
        </div>
    </div> <!-- END OF WELL DIV  -->


    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Location Code</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_locationCode" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><strong>{{prefix_storeCode}} <input type = "text" style="display:none" name = "prefix" ng-model = "prefix_storeCode" /></strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            readonly
                                                            class="form-control emphasize"
                                                            ng-model="locationCodeID"
                                                            id="location_code"
                                                            name = "location_code"
                                                            autocomplete="off" >
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Classification</label>
                                            <div class="col-md-8">
                                                <select name = "area_classification" required class="form-control">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Type</label>
                                            <div class="col-md-8">
                                                <select name = "area_type" required class="form-control">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <?php foreach ($area_type as $value): ?>
                                                                <option><?php echo $value['type']; ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Period</label>
                                            <div class="col-md-8">
                                                <select name = "rent_period" id="add_rent_period" required class="form-control">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option ng-repeat = "data in periodList">{{ data.rent_period }}</option>
                                                </select>
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Mode of Payment</label>
                                            <div class="col-md-8">
                                                <select name = "payment_mode" required class="form-control">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Monthly Basis</option>
                                                    <option>Full Payment</option>
                                                </select>

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
                        <button type="submit" ng-disabled = "add_form.$invalid" class="btn btn-primary button-b"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
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
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Location Code</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form  action = "{{ 'update_locationCode/'}}" method="post" name = "frm_update" id = "frm_update">
                        <input type = "text" style = "display:none" ng-model = "data.id" = name = "id_to_update" />
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><strong><span id = "prefix">{{data.location_code.split('-')[0]}}</span> <input type = "text" ng-model = "data.location_code.split('-')[0]" style="display:none" name = "prefix" id= "txt_prefix" ng-model = "prefix_storeCode" /></strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            readonly
                                                            class="form-control emphasize"
                                                            ng-model="data.location_code.split('-')[1]"
                                                            id="location_code"
                                                            name = "location_code"
                                                            autocomplete="off" >
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
                                                    class="form-control"
                                                    ng-click = "populate_rentPeriod('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_rentPeriod/' + data.tenancy_type)"
                                                    onchange = "deleteFirstIndex('update_rent_period')">
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Classification</label>
                                            <div class="col-md-8">
                                                <select name = "area_classification" ng-model = "data.area_classification" required class="form-control">
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Type</label>
                                            <div class="col-md-8">
                                                <select name = "area_type" required class="form-control" ng-model = "data.area_type">
                                                    <?php foreach ($area_type as $value): ?>
                                                                <option><?php echo $value['type']; ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Period</label>
                                            <div class="col-md-8">
                                                <select name = "rent_period" id = "update_rent_period" required class="form-control">
                                                    <option>{{data.rent_period}}</option>
                                                    <option ng-repeat = "period in periodList">{{ period.rent_period }}</option>
                                                </select>
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
                                            <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Mode of Payment</label>
                                            <div class="col-md-8">
                                                <select name = "payment_mode" ng-model = "data.payment_mode" required class="form-control">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Monthly Basis</option>
                                                    <option>Full Payment</option>
                                                </select>

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
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary button-b"> <i class = "fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
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
                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
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
                    <button type="button" onclick="update_locationCode('<?php echo base_url(); ?>index.php/leasing_mstrfile/update_locationCode/')" class="btn btn-primary button-b" data-dismiss="modal"> <i class="fa fa-key"></i> Submit</button>
                    <button type="button" class="btn btn-alert button-w" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Manager's Key Modal -->


</div> <!-- /.container -->
</body>
