<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> First Level Category</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                        <div class="col-md-3 pull-left">
                            <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                data-target="#add_data" class="btn btn-success btn-medium button-g"><i
                                    class="fa fa-plus-circle"></i>
                                Add Data</a>
                        </div>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_category_one')">

                            <tbody>
                                <tr class="ng-cloak" ng-repeat="category in data">
                                    <td title="'ID'" sortable="'id'">{{ category.id }}</td>
                                    <td title="'Category Name'" sortable="'category_name'">{{ category.category_name }}
                                    </td>
                                    <td title="'Description'" sortable="'description'">{{ category.description }}</td>
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
                                                            ng-click="update_category_one('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_categoryOne_data/' + category.id)">
                                                            <i class="fa fa-edit"></i> Update</a></li>
                                                    <li><a href="#" data-backdrop="static" data-keyboard="false"
                                                            data-toggle="modal" data-target="#confirmation_modal"
                                                            ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_categoryOne/' + category.id)">
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
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Category</h4>
                </div>
                <div class="modal-body" ng-repeat="data in categoryData">
                    <form action="{{ 'update_categoryOne/' + data.id }}" method="post" name="frm_update"
                        id="frm_update">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Category Name</label>
                                        <div class="col-md-8">
                                            <input type="text" required ng-minlength="5" class="form-control"
                                                ng-model="data.category_name" id="category_name" name="category_name"
                                                is-unique-update is-unique-id="{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_categoryOne_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update.category_name.$dirty && frm_update.category_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.category_name.$dirty && frm_update.category_name.$error.minlength">
                                                    <p class="error-display">Minimum of 5 characters</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.category_name.$dirty && frm_update.category_name.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <textarea ng-model="data.description" ng-minlength="5" required
                                                class="form-control" name="description" id="description" rows="3">
                                            </textarea>

                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update.description.$dirty && frm_update.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.description.$dirty && frm_update.description.$error.minlength">
                                                    <p class="error-display">Minimum of 5 characters</p>
                                                </span>
                                            </div>
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
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Category</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_category_one" name="add_form"
                    method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="category_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Category Name</label>
                                        <div class="col-md-8">
                                            <input type="text" required ng-minlength="5" class="form-control"
                                                ng-model="category_name" id="category_name" name="category_name"
                                                is-unique is-unique-api="../ctrl_validation/verify_category_one/"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.category_name.$dirty && add_form.category_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.category_name.$dirty && add_form.category_name.$error.minlength">
                                                    <p class="error-display">Minimum of 5 characters</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.category_name.$dirty && add_form.category_name.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <textarea ng-model="description" ng-minlength="5" required
                                                class="form-control" name="description" id="description"
                                                rows="3"></textarea>

                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.description.$dirty && add_form.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.description.$dirty && add_form.description.$error.minlength">
                                                    <p class="error-display">Minimum of 5 characters</p>
                                                </span>
                                            </div>
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