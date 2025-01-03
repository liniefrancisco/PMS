<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-filter"></i> Rent Period Setup</div>
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
                                    class="fa fa-plus-circle"></i> Add Data</a>
                        </div>
                    <?php endif ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_rentPeriod')">

                            <tbody>
                                <tr class="ng-cloak" ng-repeat="dt in data">
                                    <td title="'Tenancy Type'" sortable="'tenancy_type'">{{ dt.tenancy_type }}</td>
                                    <td title="'Number'" sortable="'number'">{{ dt.number }}</td>
                                    <td title="'UOM'" sortable="'uom'">{{ dt.uom }}</td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                        <td width="10%" title="'Action'" align="center">
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
                                                    <li>
                                                        <a href="#" data-backdrop="static" data-keyboard="false"
                                                            data-toggle="modal" data-target="#update_modal"
                                                            ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_rentPeriod_data/' + dt.id)">
                                                            <i class="fa fa-edit"></i> Update
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#" data-toggle="modal" data-target="#confirmation1_modal"
                                                            ng-click="confirm('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_rentPeriod/' + dt.id)">
                                                            <i class="fa fa-times-circle-o"></i> Deactivate
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    <?php endif ?>
                                </tr>

                                <tr class="ng-cloak"
                                    ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
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


    <!-- Add Data Modal -->
    <div class="modal fade" id="add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Rent Period</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_rentPeriod" name="add_form"
                    method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Tenancy Type</label>
                                        <div class="col-md-8">
                                            <select required name="tenancy_type" class="form-control">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option>Long Term</option>
                                                <option>Short Term</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>UOM</label>
                                        <div class="col-md-8">
                                            <select required name="uom" ng-model="uom" class="form-control">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option>Hour</option>
                                                <option>Day</option>
                                                <option>Month</option>
                                                <option>Year</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Number of {{uom}}(s)</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control" ng-model="number"
                                                id="number" name="number" ui-number-mask="2">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.number.$dirty && add_form.number.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.number.$dirty && add_form.number.$error.unique">
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
                        <button type="submit" ng-disabled="add_form.$invalid" class="btn btn-primary button-b"> <i
                                class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                class="fa fa-close"></i>
                            Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->




    <!-- Update Store Modal -->
    <div class="modal fade" id="update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Rent Period</h4>
                </div>
                <div class="modal-body" ng-repeat="data in updateData">
                    <form action="{{ 'update_rentPeriod/' + data.id }}" method="post" name="frm_update" id="frm_update">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Tenancy Type</label>
                                        <div class="col-md-8">
                                            <select required name="tenancy_type" ng-model="data.tenancy_type"
                                                class="form-control">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option>Long Term</option>
                                                <option>Short Term</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>UOM</label>
                                        <div class="col-md-8">
                                            <select required name="uom" ng-model="data.uom" class="form-control">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option>Hour</option>
                                                <option>Day</option>
                                                <option>Month</option>
                                                <option>Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="type" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Number of {{data.uom}}(s)</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control" ng-model="data.number"
                                                id="number" name="number" ui-number-mask="2">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.number.$dirty && add_form.number.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.number.$dirty && add_form.number.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
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


</div> <!-- /.container -->
</body>