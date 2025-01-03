<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-history"></i> Long Term Tenants History</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/Leasing_reports/get_longtermHistory')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat="dt in data">
                                    <td title="'Tenant ID'" sortable="'tenant_id'">{{dt.tenant_id}}</td>
                                    <td title="'Trade Name'" sortable="'trade_name'">{{dt.trade_name}}</td>
                                    <td title="'Corporate Name'" sortable="'corporate_name'">{{dt.corporate_name}}</td>
                                    <td title="'Contact Person/Owner'" sortable="'contact_person'">{{dt.contact_person}}
                                    </td>
                                    <td title="'Contact Number'" sortable="'contact_number'">{{dt.contact_number}}</td>
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
                                                <li>
                                                    <a data-toggle="modal" data-target="#view" href="#"
                                                        ng-click="loadList2('<?php echo base_url(); ?>index.php/leasing_reports/view_history/' + dt.tenant_id)">
                                                        <i class="fa fa-eye"></i> View History
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="6">
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


    <!-- Add Data view -->
    <div class="modal fade" id="view" ng-controller="tableController">
        <div class="modal-dialog modal-xl" style="overflow-y: initial !important">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> Tenant History</h4>
                </div>
                <div class="modal-body" style="height: 500px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type="text" class="form-control search-query" placeholder="Search Here..."
                                ng-model="query2" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><a href="#" data-ng-click="sortBy = 'trade_name'; reverse = !reverse">Trade
                                                Name</a></th>
                                        <th><a href="#"
                                                data-ng-click="sortBy = 'contract_no'; reverse = !reverse">Contract
                                                No.</a></th>
                                        <th><a href="#"
                                                data-ng-click="sortBy = 'location_code'; reverse = !reverse">Location
                                                Code</a></th>
                                        <th><a href="#" data-ng-click="sortBy = 'slots'; reverse = !reverse">Slots</a>
                                        </th>
                                        <th><a href="#" data-ng-click="sortBy = 'floor_area'; reverse = !reverse">Area
                                                per SQ.</a></th>
                                        <th><a href="#"
                                                data-ng-click="sortBy = 'rental_type'; reverse = !reverse">Rental
                                                Type</a></th>
                                        <th><a href="#" data-ng-click="sortBy = 'rental_type'; reverse = !reverse">Rent
                                                %</a></th>
                                        <th><a href="#"
                                                data-ng-click="sortBy = 'contract_date'; reverse = !reverse">Contract
                                                Date</a></th>
                                        <th><a href="#" data-ng-click="sortBy = 'Baic Rent'; reverse = !reverse">Basic
                                                Rent</a></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak"
                                        ng-repeat="data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2"
                                        ng-show="dataList2.length!=0">
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.trade_name}}
                                        </td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.contract_no}}
                                        </td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.location_code}}
                                        </td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.slots}}</td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}" align="right">
                                            {{data.floor_area | currency : '' }}</td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.rental_type}}
                                        </td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}" align="right">
                                            {{data.rent_percentage | currency : ''}}%</td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}">{{data.contract_date}}
                                        </td>
                                        <td class="{{data.status=='Active' ? 'bg-success': ''}}" align="right">
                                            {{data.basic_rental | currency : '&#8369;' }}</td>
                                    </tr>
                                    <tr class="ng-cloak"
                                        ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
                                        <td colspan="8">
                                            <center>No Data Available.</center>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="ng-cloak">
                                        <td colspan="9" style="padding: 5px;">
                                            <div>
                                                <ul class="pagination">
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                        ng-class="prevPageDisabled2()">
                                                        <a href ng-click="prevPage2()" style="border-radius: 0px;"><i
                                                                class="fa fa-angle-double-left"></i> Prev</a>
                                                    </li>
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                        ng-repeat="n in range2()" ng-class="{active: n == currentPage2}"
                                                        ng-click="setPage2(n)">
                                                        <a href="#">{{n+1}}</a>
                                                    </li>
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                        ng-class="nextPageDisabled2()">
                                                        <a href ng-click="nextPage2()" style="border-radius: 0px;">Next
                                                            <i class="fa fa-angle-double-right"></i></a>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger button-r" data-dismiss="modal"><i
                            class="fa fa-close"></i>
                        Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data view -->

</div> <!-- /.container -->
</body>