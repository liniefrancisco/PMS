<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> MONTHLY CHARGES</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="query" />
                    </div>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-toggle="modal" data-target="#add_data"
                            class="btn btn-success btn-medium button-g"><i class="fa fa-plus-circle"></i> Add Data</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_monthly_charges')">
                            <thead>
                                <tr>
                                    <th width="15%"><a href="#"
                                            data-ng-click="sortField = 'description'; reverse = !reverse">Description</a>
                                    </th>
                                    <th width="15%"><a href="#"
                                            data-ng-click="sortField = 'uom'; reverse = !reverse">Unit of Measure</a>
                                    </th>
                                    <th width="15%"><a href="#"
                                            data-ng-click="sortField = 'amount'; reverse = !reverse">Amount</a></th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0"
                                    ng-repeat="charges in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">

                                    <td>{{ charges.description }}</td>
                                    <td>{{ charges.uom }}</td>
                                    <td>{{ charges.amount | currency : '&#8369;' }}</td>
                                    <td>
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
                                                <li><a href="#" data-toggle="modal" data-target="#update_modal"
                                                        ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_monthlyCharges_data/' + charges.id)">
                                                        <i class="fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal"
                                                        ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_monthlyCharges/' + charges.id)">
                                                        <i class="fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak"
                                    ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="5">
                                        <center>No Data Available.</center>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="5" style="padding: 5px;">
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
    <div class="modal fade" id="update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Monthly Charges</h4>
                </div>
                <div class="modal-body" ng-repeat="data in updateData">
                    <form action="{{ 'update_monthlyCharges/' + data.id }}" method="post" name="frm_update"
                        id="frm_update">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control" ng-model="data.description"
                                                id="description" name="description" autocomplete="off" is-unique-update
                                                is-unique-id="{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_monthlyCharges_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update.description.$dirty && frm_update.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update.description.$dirty && frm_update.description.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <label for="uom" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="uom" required ng-model="data.uom"
                                                id="uom">
                                                <option>Per Kilowatt Hour</option>
                                                <option>Per Cubic Meter</option>
                                                <option>Per Square Meter</option>
                                                <option>Per Ton</option>
                                                <option>Per Hour</option>
                                                <option>Per Piece</option>
                                                <option>Per Page</option>
                                                <option>Fixed Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="amount" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Amount</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                <input type="text" required class="form-control currency"
                                                    ng-model="data.amount" format="number" id="amount" name="amount"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_update.amount.$dirty && frm_update.amount.$error.required">
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
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Monthly Charges</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_monthly_charges" name="add_form"
                    method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control" ng-model="description"
                                                id="description" name="description" autocomplete="off" is-unique
                                                is-unique-api="../ctrl_validation/verify_monthlyCharges/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="add_form.description.$dirty && add_form.description.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="add_form.description.$dirty && add_form.description.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <label for="uom" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Unit of Measure</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="uom" required ng-model="uom" id="uom">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option>Per Kilowatt Hour</option>
                                                <option>Per Cubic Meter</option>
                                                <option>Per Square Meter</option>
                                                <option>Per Ton</option>
                                                <option>Per Hour</option>
                                                <option>Per Piece</option>
                                                <option>Per Page</option>
                                                <option>Fixed Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="amount" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Amount</label>
                                        <div class="col-md-8">

                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                <input type="text" required class="form-control currency"
                                                    ng-model="amount" format="number" id="amount" name="amount"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="add_form.amount.$dirty && add_form.amount.$error.required">
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

</div> <!-- End of Container -->
</body>