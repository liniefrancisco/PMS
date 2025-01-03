
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-users"></i> Facility Rental Discount Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_discount')">
                            
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "type in data">
                                    <td title="'Discount Type'" sortable = "'discount_type'">{{ type.discount_type }}</td>
                                    <td title="'Percent/Amount'" sortable = "'discount_option'">{{ type.discount_option }}</td>
                                    <td title="'Discount'" sortable = "'discount_amount'">
                                        <div ng-if = "type.discount_option == 'Fixed Amount'">
                                            {{ type.discount_amount | currency : '&#8369;' }}
                                        </div>
                                        <div ng-if = "type.discount_option != 'Fixed Amount'">
                                            {{ type.discount_amount | currency : '% ' }}
                                        </div>
                                    </td>
                                    <td title="'Description'" sortable = "'description'">{{ type.discount_description }}</td>
                               
                                    <td title="'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_discount_data/' + type.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_facilityrental/delete_frdiscount/' + type.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                              
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="5"><center>No Data Available.</center></td>
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
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Discount Type</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "" method="post" name = "frm_updatefrdiscount" id = "frm_updatefrdiscount">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="tenant_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount Type</label>
                                        <div class="col-md-8">
                                            <input type="text" ng-model="data.id" id="discount_idupdate" name ="discount_idupdate" class="hidden">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="data.discount_type"
                                                id="discount_typeupdate"
                                                name = "discount_typeupdate"
                                                autocomplete="off" >
                                            <!-- FOR ERRORS -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="discount_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Percent/Amount</label>
                                        <div class="col-md-8">
                                            <select
                                                required
                                                ng-model="data.discount_option"
                                                class="form-control"
                                                name = "discount_optionupdate"
                                                id ="discount_optionupdate">
                                                <option>Percentage</option>
                                                <option>Fixed Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="discount" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control currency"
                                                ng-model="data.discount_amount"
                                                format="number"
                                                id="discount_amountupdate"
                                                name = "discount_amountupdate"
                                                autocomplete="off" >
                                            <!-- FOR ERRORS -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input
                                                ng-model="data.discount_description"
                                                ng-minlength="5"
                                                required
                                                class="form-control"
                                                name = "discount_descriptionupdate"
                                                id="discount_descriptionupdate">
                                            <!-- FOR ERRORS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" id="update_frdiscountbtn" onclick="update_frdiscount('<?php echo base_url(); ?>index.php/leasing_facilityrental/update_frdiscount/')" class="btn btn-primary"> <i class = "fa fa-save"></i> Save Changes</button>
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
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Discount Type</h4>
                </div>
                <form id="add_frdiscount" name="add_frdiscount" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="tenant_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount Type</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                id="discount_type"
                                                name = "discount_type"
                                                autocomplete="off" >
                                            <!-- FOR ERRORS -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="discount_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Percent/Amount</label>
                                        <div class="col-md-8">
                                            <select
                                                required
                                                class="form-control"
                                                name = "discount_option"
                                                id ="discount_option">
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option>Percentage</option>
                                                <option>Fixed Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="discount" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control currency"
                                                format="number"
                                                ng-model="data.discount_amount"
                                                id="discount_amount"
                                                name = "discount_amount"
                                                autocomplete="off" >
                                            <!-- FOR ERRORS -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input
                                                required
                                                class="form-control"
                                                name = "discount_description"
                                                id="discount_description">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="insert_frdiscountbtn" onclick="insert_frdiscount('<?php echo base_url(); ?>index.php/leasing_facilityrental/insert_frdiscount/')" class="btn btn-primary"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div> <!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- /.container -->
</body>
