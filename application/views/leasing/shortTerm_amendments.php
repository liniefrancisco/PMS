
<div class="container" >
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Short Term Contract Amendments</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_reports/get_amendments/Short Term')">
                            <thead>
                                <tr>
                                    <th width="10%"><a href="#" data-ng-click="sortField = 'tenant_id'; reverse = !reverse">Tenant ID</a></th>
                                    <th width="8%"><a href="#" data-ng-click="sortField = 'contract_no'; reverse = !reverse">Contract No.</a></th>
                                    <th width="10%"><a href="#" data-ng-click="sortField = 'trade_name'; reverse = !reverse">Trade Name</a></th>
                                    <th width="10%"><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store Location</a></th>
                                    <th width="30%"><a href="#" data-ng-click="sortField = 'reason'; reverse = !reverse">Reason</th>
                                    <th width="8%"><a href="#" data-ng-click="sortField = 'date_modified'; reverse = !reverse">Date Modified</th>
                                    <th width="15%"><a href="#" data-ng-click="sortField = 'modified_by'; reverse = !reverse">Modified By</a></th>
            
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "data in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ data.tenant_id }}</td>
                                    <td>{{ data.contract_no }}</td>
                                    <td>{{ data.trade_name }}</td>
                                    <td>{{ data.store_name }}</td>
                                    <td>{{ data.reason }}</td>
                                    <td>{{ data.date_modified }}</td>
                                    <td>{{ data.modified_by }}</td>
                                </tr>
                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="8"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="8" style="padding: 5px;">
                                        <div>
                                            <ul class="pagination">
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="prevPageDisabled()">
                                                    <a href ng-click="prevPage()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)">
                                                <a href="#">{{n+1}}</a>
                                                </li>
                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="nextPageDisabled()">
                                                    <a href ng-click="nextPage()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
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
                        <a href="#" onclick="print('<?php echo base_url(); ?>index.php/leasing_reports/print_shortTerm_amendments')" class="btn btn-primary btn-lg pull-right button-b"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->


</div> <!-- End of Container -->

</body>