
<script type="text/javascript">var banks_array =<?php echo json_encode($banks); ?>;</script>
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Store Setup</div>
            <div class="panel-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 pull-right">
                                    <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                </div>
                                <div class="col-md-3 pull-left">
                                    <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                                </div>
                            </div>

                        </div>
                        <table ng-table="tableParams" ng-controller="tableController" class="table table-bordered" show-filter="false" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_stores')">
                            <tr ng-repeat="dt in data">
                                <td title="'Company Code'" sortable="'company_code'">{{dt.company_code}}</td>
                                <td title="'Store Name'" sortable="'store_name'">{{dt.store_name}}</td>
                                <td title="'Store Code'" sortable="'store_code'">{{dt.store_code}}</td>
                                <td title="'Store Address'" sortable="'store_address'">{{dt.store_address}}</td>
                                <td title="'Floors(s)'">
                                    <div ng-repeat = "floor in floorList" ng-if = "floor.store_id == dt.id" style="float:left">
                                         {{ floor.floor_name }},
                                    </div>
                                </td>
                                <td title="'Action'">
                                    <!-- Split button -->
                                    
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-danger">Action</button>
                                        <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-backdrop="static" data-keyboard="false"
                                                    data-target="#add_floor_modal"
                                                    ng-click="form_action('<?php echo base_url(); ?>index.php/leasing_mstrfile/add_floor/' + dt.id)">
                                                        <i class = "fa fa-plus-circle"></i> Add Floor
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-backdrop="static" data-keyboard="false"
                                                    data-target="#add_selectedBank"
                                                    ng-click="form_action_addSelectedBank('<?php echo base_url(); ?>index.php/leasing_mstrfile/add_selectedBank/' + dt.id)">
                                                        <i class = "fa fa-plus-square"></i> Add Accredited Bank
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    data-toggle="modal"
                                                    data-backdrop="static" data-keyboard="false"
                                                    data-target="#update_store_modal"
                                                    ng-click="update_store('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_storedata/', dt.id)">
                                                    <i class = "fa fa-edit"></i> Update
                                                </a>
                                            </li>
                                            <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_store/' + dt.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tbody ng-show = "isLoading">
                                <tr>
                                    <td colspan="10">
                                        <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                        <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                    </td>
                                </tr>
                            </tbody>
                            <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                <td colspan="10"><center>No Data Available.</center></td>
                            </tr>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

        <!-- Update Store Modal -->
    <div class="modal fade" id = "update_store_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Store</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in storeData">
                    <form action = "{{ 'update_store/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Name</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="data.store_name"
                                                    id="store_name"
                                                    name = "store_name"
                                                    is-unique-update
                                                    is-unique-id = "{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_storeupdate/">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                    <span ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="company_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Company Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="data.company_code"
                                                    id="company_code"
                                                    name = "company_code"
                                                    autocomplete="off" >
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.company_code.$dirty && frm_update.company_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dept_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Leasing Dept. Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="data.dept_code"
                                                    id="dept_code"
                                                    name = "dept_code"
                                                    autocomplete="off" >
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.dept_code.$dirty && frm_update.dept_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="data.store_code"
                                                    ng-minlength="2"
                                                    required
                                                    class="form-control"
                                                    name = "store_code"
                                                    autocomplete = "off"
                                                    id="store_code"
                                                    is-unique-update
                                                    is-unique-id = "{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_storeCode_update/">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.minlength">
                                                        <p class="error-display">Minimum of 2 characters</p>
                                                    </span>
                                                     <span ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Address</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" ng-minlength="5" required ng-model="data.store_address" name = "store_address" id="store_address">
                                                 <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.address.$dirty && frm_update.address.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="frm_update.address.$dirty && frm_update.address.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_person" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Person</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="data.contact_person"
                                                    required
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "contact_person"
                                                    id="contact_person">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.contact_person.$dirty && frm_update.contact_person.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_number" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Number</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="data.contact_no"
                                                    required
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "contact_number"
                                                    id="contact_number">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.contact_number.$dirty && frm_update.contact_number.$error.required">
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
                                            <label for="email" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Email Address</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="email"
                                                    ng-model="data.email"
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "email"
                                                    id="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div ng-repeat = "floor in floorData">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Name</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type = "text" class="form-control" name="floor_name[]" value="{{ floor.floor_name }}" required class="form-control">
                                                        <span class="input-group-addon"  style = "padding:0px"><button type="button"  class="btn btn-danger" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_floor/' + floor.id)"> <i class="fa fa-remove"></i></button></span>
                                                        <input type = "hidden" name = "floor_id[]" value="{{ floor.id }}"  />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div ng-repeat = "bank in selectedBanks">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Accredited Bank</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type = "text" class="form-control" name="bank_name[]" value="{{ bank.bank_name }}" required class="form-control">
                                                        <span class="input-group-addon"  style = "padding:0px"><button type="button"  class="btn btn-danger" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_selectedBank/' + bank.id)"> <i class="fa fa-remove"></i></button></span>
                                                        <input type = "hidden" name = "selectedBank_id[]" value="{{ bank.id }}"  />
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
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Store</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_store" enctype="multipart/form-data" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Name</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="store_name"
                                                    id="store_name"
                                                    name = "store_name"
                                                    is-unique
                                                    is-unique-api="../ctrl_validation/verify_store/"
                                                    autocomplete="off" >
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.store_name.$dirty && add_form.store_name.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="add_form.store_name.$dirty && add_form.store_name.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                    <span ng-show="add_form.store_name.$dirty && add_form.store_name.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="company_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Company Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="company_code"
                                                    id="company_code"
                                                    name = "company_code"
                                                    autocomplete="off" >
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.company_code.$dirty && add_form.company_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dept_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Leasing Dept. Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    required
                                                    ng-minlength="5"
                                                    class="form-control"
                                                    ng-model="dept_code"
                                                    id="dept_code"
                                                    name = "company_code"
                                                    autocomplete="off" >
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.dept_code.$dirty && add_form.dept_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Code</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="store_code"
                                                    ng-minlength="2"
                                                    required
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "store_code"
                                                    id="store_code"
                                                    is-unique
                                                    is-unique-api="../ctrl_validation/verify_storeCode/">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.store_code.$dirty && add_form.store_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="add_form.store_code.$dirty && add_form.store_code.$error.minlength">
                                                        <p class="error-display">Minimum of 2 characters</p>
                                                    </span>
                                                     <span ng-show="add_form.store_code.$dirty && add_form.store_code.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Address</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" ng-minlength="5" required ng-model="address" name = "address" id="address">
                                                 <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.address.$dirty && add_form.address.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="add_form.address.$dirty && add_form.address.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_person" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Person</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="contact_person"
                                                    required
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "contact_person"
                                                    id="contact_person">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.contact_person.$dirty && add_form.contact_person.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_number" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Number</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="text"
                                                    ng-model="contact_number"
                                                    required
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "contact_number"
                                                    id="contact_number">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span ng-show="add_form.contact_number.$dirty && add_form.contact_number.$error.required">
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
                                            <label for="email" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Email Address</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="email"
                                                    ng-model="email"
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "email"
                                                    id="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-right" for="floor_name"> <i class = "fa fa-asterisk"></i> Floor Name</label>
                                            <div class="col-md-7">
                                                <div id="field">
                                                    <input
                                                        autocomplete="off"
                                                        class="form-control"
                                                        id="field1"
                                                        required
                                                        name="floor_name[]"
                                                        type="text"
                                                        data-items="8"/><button id="b1" class="btn add-more" type="button">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-right" > <i class = "fa fa-asterisk"></i> Accredited Bank</label>
                                            <div class="col-md-7">
                                                <div id="select">
                                                    <select class="form-control" id = "select1" name = "accre_bank[]">
                                                        <?php foreach ($banks as $bank): ?>
                                                            <option><?php echo $bank['bank_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select><button id="b1" class="btn add-bank" type="button">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="email" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Logo</label>
                                            <div class="col-md-8">
                                                <input
                                                    type="file"
                                                    autocomplete = "off"
                                                    class="form-control"
                                                    name = "store_logo"
                                                    id="store_logo">
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


    <!-- Add Floor Modal -->
    <div class="modal fade" id = "add_floor_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Floor</h4>
                </div>
                <form action="#" id="frm_addfloor" name="add_floor" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Name</label>
                                        <div class="col-md-9">
                                            <input
                                                type="text"
                                                required
                                                ng-minlength="2"
                                                class="form-control"
                                                ng-model="floor_name"
                                                id="floor_name"
                                                name = "floor_name"
                                                is-unique-update
                                                is-unique-id = "{{store_id}}"
                                                is-unique-api="../ctrl_validation/verify_added_floor/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_floor.floor_name.$dirty && add_floor.floor_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_floor.floor_name.$dirty && add_floor.floor_name.$error.minlength">
                                                    <p class="error-display">Minimum of 2 characters</p>
                                                </span>
                                                <span ng-show="add_floor.floor_name.$dirty && add_floor.floor_name.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "add_floor.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Add Floor Modal -->

    <!-- Add Selected Bank Modal -->
    <div class="modal fade" id = "add_selectedBank">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i>Add Accredited Bank</h4>
                </div>
                <form action="#" id="frm_addselectedBank" name="frm_addselectedBank" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Name</label>
                                        <div class="col-md-9">

                                           <select class="form-control" name = "bank_name" required>
                                                <?php foreach ($banks as $value): ?>
                                                    <option><?php echo $value['bank_name']; ?></option>
                                                <?php endforeach ?>
                                           </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "frm_addselectedBank.$invalid" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Add Selected Bank Modal -->


</div> <!-- /.container -->
</body>
