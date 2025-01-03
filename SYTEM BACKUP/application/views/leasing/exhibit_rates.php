
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Exhibit Rate Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>    
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_exhibit_rates')">
                            <thead>
                                <tr>
                                    <th ><a href="#" data-ng-click="sortField = 'location_code'; reverse = !reverse">Location Code</a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'category'; reverse = !reverse">Category</a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Location</a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'price'; reverse = !reverse">Price Per m<sup>2</sup></a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor Area m<sup>2</sup></a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'rate_per_day'; reverse = !reverse">Rate Per Day</a></th>
                                    <th ><a href="#" data-ng-click="sortField = 'status'; reverse = !reverse">Status</a></th>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <th width="8%">Action</th>    
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "exhibit in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ exhibit.location_code }}</td>
                                    <td>{{ exhibit.category }}</td>
                                    <td>{{ exhibit.store_name }}</td>
                                    <td>{{ exhibit.floor_name }}</td>
                                    <td>{{ exhibit.price | currency : '&#8369;' }}</td>
                                    <td>{{ exhibit.floor_area | currency : '' }}</td>
                                    <td>{{ exhibit.rate_per_day | currency : '&#8369;' }}</td>
                                    <td>
                                        <div ng-if = "exhibit.status == 'Occupied'"><span class="red">Occupied</span></div>
                                        <div ng-if = "exhibit.status != 'Occupied'"><span class="green">Vacant</span></div>
                                    </td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_exhibitRate_data/' + exhibit.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_exhibit_rate/' + exhibit.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>    
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="10"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="10" style="padding: 5px;">
                                        <div >
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

        <!-- Update Store Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Exhibit Rate</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "{{ 'update_exhibitRate/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class = "col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store/Property</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                <select 
                                                    name = "store_name" 
                                                    required
                                                    class = "form-control" 
                                                    ng-model="data.store_name"
                                                    id = "store_name"
                                                    onchange="deleteFirstIndex('floor_nameID')"
                                                    ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floor_price/' + data.store_name);">
                                                    <?php foreach ($stores as $value): ?>
                                                        <option><?php echo $value['store_name']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <?php else: ?>
                                                    <input type = "text" name = "store_name" class="form-control" value="<?php echo $user_group; ?>" readonly>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                <select
                                                    class = "form-control" 
                                                    name = "floor_name" 
                                                    required
                                                    ng-model="data.floor_name"
                                                    id = "floor_nameID"
                                                    ng-change = "get_floorPriceForUpdate('floor_priceID', '<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floorPrice/' + data.store_name + '/' + data.floor_name)"
                                                    ng-blur = "compute_rates_update('floor_priceID', data.floor_area, 'rate_per_dayID', 'rate_per_weekID', 'rate_per_monthID')">
                                                    <option selected>{{ data.floor_name }}</option>
                                                    <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                </select>

                                                <?php else: ?>

                                                <select
                                                    class = "form-control" 
                                                    name = "floor_name" 
                                                    required
                                                    ng-model="data.floor_name"
                                                    id = "floor_nameID"
                                                    ng-change = "get_floorPriceForUpdate('floor_priceID', '<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floorPrice/' + '<?php echo $user_group; ?>' + '/' + data.floor_name)"
                                                    ng-blur = "compute_rates_update('floor_priceID', data.floor_area, 'rate_per_dayID', 'rate_per_weekID', 'rate_per_monthID')">

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
                                            <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                            <div class="col-md-8">
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control currency" 
                                                    ng-model="data.location_code"
                                                    id="location_code"
                                                    name = "location_code"
                                                    autocomplete="off" 
                                                    is-unique-update
                                                    is-unique-id = "{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_exhibitLocationCode_update/">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.location_code.$dirty && frm_update.location_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
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
                                            <label for="category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Category</label>
                                            <div class="col-md-8">
                                                <input 
                                                    type="text" 
                                                    required 
                                                    class="form-control" 
                                                    ng-model="data.category"
                                                    id="category"
                                                    name = "category"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.category.$dirty && frm_update.category.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Price</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input 
                                                            type="text" 
                                                            required
                                                            readonly 
                                                            class="form-control currency" 
                                                            ng-model="data.price"
                                                            format="number"
                                                            id="floor_priceID"
                                                            name = "floor_price"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_update.floor_price.$dirty && frm_update.floor_price.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>  
                                                </div>
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
                                                            format="number"
                                                            id="floor_area"
                                                            name = "floor_area"
                                                            autocomplete="off" 
                                                            ng-keyup="compute_rates_update('floor_priceID', data.floor_area, 'rate_per_dayID', 'rate_per_weekID', 'rate_per_monthID')">
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
                                            <label for="rate_per_day" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rate Per Day</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input 
                                                            type="text" 
                                                            required
                                                            readonly 
                                                            class="form-control currency" 
                                                            ng-model="data.rate_per_day"
                                                            format="number"
                                                            id="rate_per_dayID"
                                                            name = "rate_per_day"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_update.rate_per_day.$dirty && frm_update.rate_per_day.$error.required">
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

    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Exhibit Rate</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_exhibit_rate" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class = "col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store/Property</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select 
                                                        name = "store_name" 
                                                        required
                                                        class = "form-control" 
                                                        ng-model="store_name"
                                                        id = "store_name"
                                                        ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floor_price/' + store_name); clear_price()">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($stores as $value): ?>
                                                            <option><?php echo $value['store_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input type = "text" name = "store_name" readonly class="form-control" value="<?php echo $user_group; ?>"/>
                                                <?php endif ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                            <div class="col-md-8">
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        class = "form-control" 
                                                        name = "floor_name" 
                                                        required
                                                        ng-model="floor_name"
                                                        id = "floor_name"
                                                        ng-change = "get_floorPrice('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floorPrice/' + store_name + '/' + floor_name);compute_basicRental(floor_area, floorPrice)"
                                                        ng-blur = "compute_rates_update('floor_price', floor_area, 'rate_per_day', 'rate_per_week', 'rate_per_month')">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                    </select>
                                                <?php else: ?>
                                                    <select
                                                        class = "form-control" 
                                                        name = "floor_name" 
                                                        required
                                                        ng-model="floor_name"
                                                        id = "floor_name"
                                                        ng-change = "get_floorPrice('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floorPrice/' + '<?php echo $user_group; ?>' + '/' + floor_name);compute_basicRental(floor_area, floorPrice)"
                                                        ng-blur = "compute_rates_update('floor_price', floor_area, 'rate_per_day', 'rate_per_week', 'rate_per_month')">
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
                                            <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                            <div class="col-md-8">
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control currency" 
                                                    ng-model="location_code"
                                                    id="location_code"
                                                    name = "location_code"
                                                    autocomplete="off" 
                                                    is-unique
                                                    is-unique-api="../ctrl_validation/verify_exhibit_locationCode/">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.location_code.$dirty && add_form.location_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="add_form.location_code.$dirty && add_form.location_code.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Category</label>
                                            <div class="col-md-8">
                                                <input 
                                                    type="text" 
                                                    required 
                                                    class="form-control" 
                                                    ng-model="category"
                                                    id="category"
                                                    name = "category"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.category.$dirty && add_form.category.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="floor_price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Price</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input 
                                                            type="text" 
                                                            required
                                                            readonly 
                                                            class="form-control currency" 
                                                            ng-model="floorPrice"
                                                            format="number"
                                                            id="floor_price"
                                                            name = "floor_price"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="add_form.floor_price.$dirty && add_form.floor_price.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>  
                                                </div>
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
                                                            format="number"
                                                            id="floor_area"
                                                            name = "floor_area"
                                                            autocomplete="off" 
                                                            ng-keyup="compute_rates(floorPrice, floor_area, 'rate_per_day', 'rate_per_week', 'rate_per_month')">
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
                                            <label for="rate_per_day" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rate Per Day</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input 
                                                            type="text" 
                                                            required
                                                            readonly 
                                                            class="form-control currency" 
                                                            ng-model="rate_per_day"
                                                            format="number"
                                                            id="rate_per_day"
                                                            name = "rate_per_day"
                                                            autocomplete="off" >
                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="add_form.rate_per_day.$dirty && add_form.rate_per_day.$error.required">
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
                        <button type="submit" ng-disabled = "add_form.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- /.container -->

</body>