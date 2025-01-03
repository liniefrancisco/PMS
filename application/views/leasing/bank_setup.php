<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Accredited Banks</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                        <div class="col-md-3 pull-left">
                            <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                data-target="#add_data" class="btn btn-success btn-medium  button-g"><i
                                    class="fa fa-plus-circle"></i>
                                Add Data</a>
                        </div>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_accreditedBanks'); getStores('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_stores')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat="bank in data">
                                    <td title="'Bank Code'" sortable="'bank_code'">{{ bank.bank_code }}</td>
                                    <td title="'Bank Name'" sortable="'bank_name'">{{ bank.bank_name }}</td>
                                    <td title="'Store'" sortable="'store_name'">{{ bank.store_name }}</td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                        <td title="'Action'" align="center">
                                            <!-- Split button -->
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-xs btn-danger button-caret">Action</button>
                                                <button type="button"
                                                    class="btn btn-xs btn-danger dropdown-toggle button-caret"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" data-backdrop="static" data-keyboard="false"
                                                            data-toggle="modal" data-target="#update_modal"
                                                            ng-click="update_accreditedBank('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_accreditedBankData/' + bank.id)">
                                                            <i class="fa fa-edit"></i> Update</a></li>
                                                    <li><a href="#" data-backdrop="static" data-keyboard="false"
                                                            data-toggle="modal" data-target="#confirmation_modal"
                                                            ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_accreditedBank/' + bank.id)">
                                                            <i class="fa fa-trash"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="5">
                                        <center>No Data Available.</center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

    <!-- Update Store Modal -->
    <div class="modal fade" id="update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Bank Details</h4>
                </div>
                <div class="modal-body" ng-repeat="data in bankData">
                    <form action="{{ 'update_bankDetails/' + data.id }}" method="post" name="frm_update"
                        id="frm_update">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Code</label>
                                        <div class="col-md-8">
                                            <input type="text" required ng-minlength="3" class="form-control"
                                                ng-model="data.bank_code" id="bank_code" name="bank_code"
                                                is-unique-update is-unique-id="{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_bankData_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update.bank_code.$dirty && frm_update.bank_code.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.bank_code.$dirty && frm_update.bank_code.$error.minlength">
                                                    <p class="error-display">Minimum of 3 characters</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.bank_code.$dirty && frm_update.bank_code.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Name</label>
                                        <div class="col-md-8">
                                            <textarea ng-model="data.bank_name" ng-minlength="3" required
                                                class="form-control" name="bank_name" id="bank_name" rows="3">
                                            </textarea>

                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update.bank_name.$dirty && frm_update.bank_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.description.$dirty && frm_update.description.$error.minlength">
                                                    <p class="error-display">Minimum of 3 characters</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Store</label>
                                        <div class="col-md-8">
                                            <select name="store_code" class="form-control">
                                                <option value="" ng-selected="data.store_code == ''"></option>
                                                <option ng-repeat="store in stores" ng-value="store.store_code"
                                                    ng-selected="data.store_code == store.store_code">
                                                    {{store.store_name}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_update.$invalid" class="btn btn-primary button-b"> <i
                                    class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->

    <!-- Add Data Modal -->
    <div class="modal fade" id="add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Bank Details</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_bank" name="add_form"
                    method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="category_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Code</label>
                                        <div class="col-md-8">
                                            <input type="text" required ng-minlength="3" class="form-control"
                                                ng-model="bank_code" id="bank_code" name="bank_code" is-unique
                                                is-unique-api="../ctrl_validation/verify_bank_code/" autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.bank_code.$dirty && add_form.bank_code.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.bank_code.$dirty && add_form.bank_code.$error.minlength">
                                                    <p class="error-display">Minimum of 3 characters</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.bank_code.$dirty && add_form.bank_code.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Name</label>
                                        <div class="col-md-8">
                                            <textarea ng-model="bank_name" ng-minlength="3" required
                                                class="form-control" name="bank_name" id="bank_name"
                                                rows="3"></textarea>

                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.bank_name.$dirty && add_form.bank_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.bank_name.$dirty && add_form.bank_name.$error.minlength">
                                                    <p class="error-display">Minimum of 3 characters</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Store</label>
                                        <div class="col-md-8">
                                            <select name="store_code" ng-model="store_code" class="form-control">
                                                <option value="" selected=""></option>
                                                <option ng-value="store.store_code" ng-repeat="store in stores">
                                                    {{store.store_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="add_form.$invalid" class="btn btn-primary button-b"> <i
                                    class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- /.container -->
</body>