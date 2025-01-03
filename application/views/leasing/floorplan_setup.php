<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Floor Plan Model Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="query" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_floorplan')">
                            <thead>
                                <tr>
                                    <th width="15%"><a href="#"
                                            data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property
                                            Name</a></th>
                                    <th width="10%"><a href="#"
                                            data-ng-click="sortField = 'store_code'; reverse = !reverse">Company
                                            Code</a></th>
                                    <th width="25%"><a href="#"
                                            data-ng-click="sortField = 'store_address'; reverse = !reverse">Floor
                                            Name</a></th>
                                    <th width="25%"><a href="#"
                                            data-ng-click="sortField = 'store_address'; reverse = !reverse">File
                                            Name</a></th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0"
                                    ng-repeat="floor in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ floor.store_name }}</td>
                                    <td>{{ floor.company_code }}</td>
                                    <td>{{ floor.floor_name }}</td>
                                    <td>{{ floor.model }}</td>
                                    <td align="center">
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
                                                <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <li>
                                                        <a href="#" data-toggle="modal" data-backdrop="static"
                                                            data-keyboard="false" data-target="#add_3DModel"
                                                            ng-click="create_onsubmit('<?php echo base_url(); ?>index.php/leasing_mstrfile/setup_3DModel/' + floor.id)">
                                                            <i class="fa fa-plus-circle"></i> Setup Floor Plan Model
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <li>
                                                    <a target="_blank"
                                                        href="<?php echo base_url() ?>index.php/leasing_mstrfile/view_3D/{{floor.model}}">
                                                        <i class="fa fa-street-view"></i> View Floor Plan Model
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak"
                                    ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="6">
                                        <center>No Data Available.</center>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="6" style="padding: 5px;">
                                        <div>
                                            <ul class="pagination">
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0"
                                                    ng-class="prevPageDisabled()">
                                                    <a href ng-click="prevPage()" style="border-radius: 0px;"><i
                                                            class="fa fa-angle-double-left"></i> Prev</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0"
                                                    ng-repeat="n in range()" ng-class="{active: n == currentPage}"
                                                    ng-click="setPage(n)">
                                                    <a href="#">{{n+1}}</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0"
                                                    ng-class="nextPageDisabled()">
                                                    <a href ng-click="nextPage()" style="border-radius: 0px;">Next <i
                                                            class="fa fa-angle-double-right"></i></a>
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
    <div class="modal fade" id="update_store_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Store</h4>
                </div>
                <div class="modal-body" ng-repeat="data in storeData">
                    <form action="{{ 'update_store/' + data.id }}" method="post" name="frm_update" id="frm_update">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_name" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Store Name</label>
                                            <div class="col-md-8">
                                                <input type="text" required ng-minlength="5" class="form-control"
                                                    ng-model="data.store_name" id="store_name" name="store_name"
                                                    is-unique-update is-unique-id="{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_storeupdate/">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span
                                                        ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                    <span
                                                        ng-show="frm_update.store_name.$dirty && frm_update.store_name.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="company_code" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Company Code</label>
                                            <div class="col-md-8">
                                                <input type="text" required ng-minlength="5" class="form-control"
                                                    ng-model="data.company_code" id="company_code" name="company_code"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.company_code.$dirty && frm_update.company_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="store_code" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Store Code</label>
                                            <div class="col-md-8">
                                                <input type="text" ng-model="data.store_code" ng-minlength="2" required
                                                    class="form-control" name="store_code" autocomplete="off"
                                                    id="store_code" is-unique-update is-unique-id="{{data.id}}"
                                                    is-unique-api="../ctrl_validation/verify_storeCode_update/">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span
                                                        ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.minlength">
                                                        <p class="error-display">Minimum of 2 characters</p>
                                                    </span>
                                                    <span
                                                        ng-show="frm_update.store_code.$dirty && frm_update.store_code.$error.unique">
                                                        <p class="error-display">Data already exist.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="address" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Address</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" ng-minlength="5" required
                                                    ng-model="data.store_address" name="store_address"
                                                    id="store_address">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.address.$dirty && frm_update.address.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span
                                                        ng-show="frm_update.address.$dirty && frm_update.address.$error.minlength">
                                                        <p class="error-display">Minimum of 5 characters</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_person" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Contact Person</label>
                                            <div class="col-md-8">
                                                <input type="text" ng-model="data.contact_person" required
                                                    autocomplete="off" class="form-control" name="contact_person"
                                                    id="contact_person">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.contact_person.$dirty && frm_update.contact_person.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="contact_number" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Contact Number</label>
                                            <div class="col-md-8">
                                                <input type="text" ng-model="data.contact_no" required
                                                    autocomplete="off" class="form-control" name="contact_number"
                                                    id="contact_number">

                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.contact_number.$dirty && frm_update.contact_number.$error.required">
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
                                            <label for="email" class="col-md-4 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Email Address</label>
                                            <div class="col-md-8">
                                                <input type="email" ng-model="data.email" autocomplete="off"
                                                    class="form-control" name="email" id="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div ng-repeat="floor in floorData">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i
                                                        class="fa fa-asterisk"></i>Floor Name</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="floor_name[]"
                                                            value="{{ floor.floor_name }}" required
                                                            class="form-control">
                                                        <span class="input-group-addon" style="padding:0px"><button
                                                                type="button" class="btn btn-danger" data-toggle="modal"
                                                                data-target="#confirmation_modal"
                                                                ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_floor/' + floor.id)">
                                                                <i class="fa fa-remove"></i></button></span>
                                                        <input type="hidden" name="floor_id[]" value="{{ floor.id }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div ng-repeat="bank in selectedBanks">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i
                                                        class="fa fa-asterisk"></i>Accredited Bank</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="bank_name[]"
                                                            value="{{ bank.bank_name }}" required class="form-control">
                                                        <span class="input-group-addon" style="padding:0px"><button
                                                                type="button" class="btn btn-danger" data-toggle="modal"
                                                                data-target="#confirmation_modal"
                                                                ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_selectedBank/' + bank.id)">
                                                                <i class="fa fa-remove"></i></button></span>
                                                        <input type="hidden" name="selectedBank_id[]"
                                                            value="{{ bank.id }}" />
                                                    </div>
                                                </div>
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




    <!-- Add 3D Model Modal -->
    <div class="modal fade" id="add_3DModel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Upload 3D Model</h4>
                </div>
                <form action="#" id="frm_3DModel" name="frm_3DModel" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-3 control-label text-right"><i
                                                class="fa fa-asterisk"></i>X3D File</label>
                                        <div class="col-md-9">
                                            <input type="file" name="x3d" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled="add_floor.$invalid" class="btn btn-primary button-b"> <i
                                class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                class="fa fa-close"></i>
                            Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Add Floor Modal -->




</div> <!-- /.container -->
</body>