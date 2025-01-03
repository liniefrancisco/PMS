
<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> LOGS</div>
            <div class="panel-body">
                <form action = "<?php echo base_url(); ?>index.php/leasing_archive/delete_logs/" method = "post">
                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
                        </div>
                        <div class="col-md-3 pull-left">
                            <button type = "submit" class = "btn btn-medium btn-danger"><i class = "fa fa-trash"></i> Delete Selected Items</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_archive/get_logs')">
                                <thead>
                                    <tr>
                                        <th><input type = "checkbox" name = "checkall" id = "checkall"  ng-click = "checkall('checkall', 'checkbox')"/></th>
                                        <th><a href="#" data-ng-click="sortField = 'user'; reverse = !reverse">User</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'action'; reverse = !reverse">Action</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'date'; reverse = !reverse">Date</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "log in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                        <td><input type = "checkbox" name = "checkbox[]" value="{{log.id}}" id = "checkbox"></td>
                                        <td>{{ log.user }}</td>
                                        <td>{{ log.action }}</td>
                                        <td>{{ log.date }}</td>
                                    </tr>
                                    <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                        <td colspan="9"><center>No Data Available.</center></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="ng-cloak">
                                        <td colspan="9" style="padding: 5px;">
                                            <div >
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
                </form>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

</div> <!-- End of container -->
</body>

