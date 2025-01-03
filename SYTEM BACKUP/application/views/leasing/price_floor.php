
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Floor Pricing Setup</div>
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
                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_price_floor')">
                            <thead>
                                <tr>
                                    <th width="30%"><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store Name</a></th>
                                    <th width="30%"><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Name</a></th>
                                    <th width="30%"><a href="#" data-ng-click="sortField = 'price'; reverse = !reverse">Price</a></th>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <th width="10%">Action</th>    
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "price in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ price.store_name }}</td>
                                    <td>{{ price.floor_name }}</td>
                                    <td>{{ price.price | currency : '&#8369;' }}</td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <td>
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_priceFloor_data/' + price.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_price_floor/' + price.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>    
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="5"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="5" style="padding: 5px;">
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Floor Price</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "{{ 'update_priceFloor/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-10 ">
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
                                                    id = "update_store_name"
                                                    ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/floor_for_pricing/' + data.store_name)"
                                                    onchange="clearComboBox('update_floor_name', 'update_store_name', 'store_defaultValue')"  >
                                                
                                                    <?php foreach ($stores as $value): ?>
                                                        <option><?php echo $value['store_name']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <input type = "hidden" value="{{ data.store_name }}" name="store_defaultValue" id="store_defaultValue" />
                                            <?php else: ?>
                                                <input type = "text" class = "form-control" name = "store_name" readonly value="<?php echo $user_group; ?>">
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
                                                    id = "update_floor_name">
                                                    <option selected="selected">{{ data.floor_name }}</option>
                                                    <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                </select>
                                            <?php else: ?>
                                                <select
                                                    class = "form-control" 
                                                    name = "floor_name" 
                                                    required
                                                    ng-model="data.floor_name"
                                                    id = "update_floor_name">
                                                    <option selected="selected">{{ data.floor_name }}</option>
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
                                        <label for="price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Price</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input 
                                                        type="text" 
                                                        required 
                                                        class="form-control currency" 
                                                        ng-model="data.price"
                                                        format="number"  
                                                        id="price"
                                                        name = "price"
                                                        autocomplete="off" >
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.price.$dirty && add_form.price.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Floor Price</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_price_floor" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
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
                                                    ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/floor_for_pricing/' + store_name)">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <?php foreach ($stores as $value): ?>
                                                        <option><?php echo $value['store_name']; ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php else: ?>
                                                <input type = "text" class = "form-control" value="<?php echo $user_group; ?>" name = "store_name" readonly />
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
                                                    id = "floor_name">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                </select>
                                            <?php else: ?>
                                                <select
                                                    class = "form-control" 
                                                    name = "floor_name" 
                                                    required
                                                    ng-model="floor_name"
                                                    id = "floor_name">
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
                                        <label for="price" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Price</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                    <input 
                                                        type="text" 
                                                        required 
                                                        class="form-control currency" 
                                                        ng-model="price"
                                                        format="number"  
                                                        id="price"
                                                        name = "price"
                                                        autocomplete="off" >
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.price.$dirty && add_form.price.$error.required">
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

</div> <!-- End of Container -->

</body>