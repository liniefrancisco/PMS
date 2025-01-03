<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> OVERTIME/OVERNIGHT WORKS CHARGES</div>
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
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_overtime_charges')">
                            <thead>
                                <tr>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'description'; reverse = !reverse">Type</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">Time Range</a>
                                    </th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">Per Hour</a>
                                    </th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">For the first
                                            3 hrs.</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">For 3 hrs and
                                            1min. to 6 hrs</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">For 6 hrs and
                                            1min. to 9 hrs</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">For 9 hrs and
                                            1min. to 11 hrs</a></th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0"
                                    ng-repeat="charges in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">

                                    <td>{{ charges.type }}</td>
                                    <td>{{ charges.time_begin }}-{{ charges.time_end }}</td>
                                    <td>{{ charges.per_hour | currency : '&#8369;' }}</td>
                                    <td>{{ charges.first_three | currency : '&#8369;' }}</td>
                                    <td>{{ charges.three_to_six | currency : '&#8369;' }}</td>
                                    <td>{{ charges.six_to_nine | currency : '&#8369;' }}</td>
                                    <td>{{ charges.nine_to_eleven | currency : '&#8369;' }}</td>
                                    <td align="center">
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
                                                        ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_overtimeCharges_data/' + charges.id)">
                                                        <i class="fa fa-edit"></i> Update</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak"
                                    ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="8">
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
            <div class="modal-content" style="top: -20px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Overtime Works Charges</h4>
                </div>
                <div class="modal-body" ng-repeat="data in updateData">
                    <form action="{{ 'update_overtime_charges/' + data.id }}" method="post" name="frm_update"
                        id="frm_update">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="type"><i class="fa fa-asterisk"></i>Type</label>
                                    <input type="text" name="type" ng-model="data.type" readonly class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="time_begin"><i class="fa fa-asterisk"></i>Overtime Range</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control" required ng-model="data.time_begin"
                                                name="time_begin" id="time_begin">
                                                <option>{{data.time_begin}}</option>
                                                <option>1:00 AM</option>
                                                <option>2:00 AM</option>
                                                <option>3:00 AM</option>
                                                <option>4:00 AM</option>
                                                <option>5:00 AM</option>
                                                <option>6:00 AM</option>
                                                <option>7:00 AM</option>
                                                <option>8:00 AM</option>
                                                <option>9:00 AM</option>
                                                <option>10:00 AM</option>
                                                <option>11:00 AM</option>
                                                <option>12:00 AM</option>
                                                <option>1:00 PM</option>
                                                <option>2:00 PM</option>
                                                <option>3:00 PM</option>
                                                <option>4:00 PM</option>
                                                <option>5:00 PM</option>
                                                <option>6:00 PM</option>
                                                <option>7:00 PM</option>
                                                <option>8:00 PM</option>
                                                <option>9:00 PM</option>
                                                <option>10:00 PM</option>
                                                <option>11:00 PM</option>
                                                <option>12:00 PM</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control" required ng-model="data.time_end"
                                                name="time_end" id="time_end">
                                                <option>{{data.time_end}}</option>
                                                <option>1:00 AM</option>
                                                <option>2:00 AM</option>
                                                <option>3:00 AM</option>
                                                <option>4:00 AM</option>
                                                <option>5:00 AM</option>
                                                <option>6:00 AM</option>
                                                <option>7:00 AM</option>
                                                <option>8:00 AM</option>
                                                <option>9:00 AM</option>
                                                <option>10:00 AM</option>
                                                <option>11:00 AM</option>
                                                <option>12:00 AM</option>
                                                <option>1:00 PM</option>
                                                <option>2:00 PM</option>
                                                <option>3:00 PM</option>
                                                <option>4:00 PM</option>
                                                <option>5:00 PM</option>
                                                <option>6:00 PM</option>
                                                <option>7:00 PM</option>
                                                <option>8:00 PM</option>
                                                <option>9:00 PM</option>
                                                <option>10:00 PM</option>
                                                <option>11:00 PM</option>
                                                <option>12:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="per_hour"><i class="fa fa-asterisk"></i>Per Hour</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input type="text" class="form-control currency" ng-model="data.per_hour"
                                            format="number" id="per_hour" name="per_hour" autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span
                                                ng-show="frm_update.per_hour.$dirty && frm_update.per_hour.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_three"><i class="fa fa-asterisk"></i>First (3) Hours</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input type="text" class="form-control currency" ng-model="data.first_three"
                                            format="number" id="first_three" name="first_three" autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span
                                                ng-show="frm_update.first_three.$dirty && frm_update.first_three.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="three_to_six"><i class="fa fa-asterisk"></i>For 3 hrs and 1min. to 6
                                        hrs</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input type="text" class="form-control currency" ng-model="data.three_to_six"
                                            format="number" id="three_to_six" name="three_to_six" autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span
                                                ng-show="frm_update.three_to_six.$dirty && frm_update.three_to_six.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="six_to_nine"><i class="fa fa-asterisk"></i>For 6 hrs and 1min. to 9
                                        hrs</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input type="text" class="form-control currency" ng-model="data.six_to_nine"
                                            format="number" id="six_to_nine" name="six_to_nine" autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span
                                                ng-show="frm_update.six_to_nine.$dirty && frm_update.six_to_nine.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nine_to_eleven"><i class="fa fa-asterisk"></i>For 9 hrs and 1min. to 11
                                        hrs</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                        <input type="text" class="form-control currency" ng-model="data.nine_to_eleven"
                                            format="number" id="nine_to_eleven" name="nine_to_eleven"
                                            autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span
                                                ng-show="frm_update.nine_to_eleven.$dirty && frm_update.nine_to_eleven.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
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