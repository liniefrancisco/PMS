<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Short Term Leasable Area</div>
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
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_exhibit_rates')">
                            <thead>
                                <tr>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'location_code'; reverse = !reverse">Location
                                            Code</a></th>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'category'; reverse = !reverse">Category</a></th>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a>
                                    </th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor
                                            Location</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'price'; reverse = !reverse">Price Per
                                            m<sup>2</sup></a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor
                                            Area m<sup>2</sup></a></th>
                                    <th><a href="#" data-ng-click="sortField = 'rate_per_day'; reverse = !reverse">Rate
                                            Per Day</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'rate_per_week'; reverse = !reverse">Rate
                                            Per Week</a></th>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'rate_per_month'; reverse = !reverse">Rate Per
                                            Month</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'status'; reverse = !reverse">Status</a>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0"
                                    ng-repeat="exhibit in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ exhibit.location_code }}</td>
                                    <td>{{ exhibit.category }}</td>
                                    <td>{{ exhibit.store_name }}</td>
                                    <td>{{ exhibit.floor_name }}</td>
                                    <td>{{ exhibit.price | currency : '&#8369;' }}</td>
                                    <td>{{ exhibit.floor_area | currency : '' }}</td>
                                    <td>{{ exhibit.rate_per_day | currency : '&#8369;' }}</td>
                                    <td>{{ exhibit.rate_per_week | currency : '&#8369;' }}</td>
                                    <td>{{ exhibit.rate_per_month | currency : '&#8369;' }}</td>
                                    <td>
                                        <div ng-if="exhibit.status == 'Occupied'"><span class="red">Occupied</span>
                                        </div>
                                        <div ng-if="exhibit.status != 'Occupied'"><span class="green">Vacant</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak"
                                    ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="10">
                                        <center>No Data Available.</center>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="10" style="padding: 5px;">
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
                <div class="row">
                    <div class="col-md-12">
                        <a href="#"
                            onclick="print('<?php echo base_url(); ?>index.php/leasing_reports/print_shortTerm_leasableArea')"
                            class="btn btn-primary btn-lg pull-right button-b"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

</div> <!-- /.container -->

</body>