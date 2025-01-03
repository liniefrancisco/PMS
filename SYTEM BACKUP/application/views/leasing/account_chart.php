
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-clipboard"></i> Chart of Accounts</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_reports/get_accountChart/')">
                            <thead>
                                <tr>
                                    <th width="30%"><a href="#" data-ng-click="sortField = 'classification'; reverse = !reverse">G/L Code</a></th>
                                    <th width="40%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">G/L Accounts</a></th>
                                    <th width="20%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Amount</a></th>
                                    <th width="10%">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "dt in data">
                                    <td title = "'G/L Code'" sortable = "'gl_code'">{{ dt.gl_code }}</td>
                                    <td title = "'G/L Account'" sortable = "'gl_account'">{{ dt.gl_account }}</td>
                                    <td title = "'Amount'" sortable = "'amount'" align = "right">{{ dt.amount | currency : '' }}</td>
                                    <td>
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#view" ng-click="loadList2('<?php echo base_url(); ?>index.php/leasing_reports/get_GLaccount/' + dt.gl_code)"> <i class = "fa fa-eye"></i> View</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length">
                                    <td colspan="5"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->


    <!-- Add Data view -->
    <div class="modal fade" id = "view" ng-controller="tableController">
        <div class="modal-dialog modal-xl" style = "overflow-y: initial !important">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Chart of Accounts</h4>
                </div>
                <div class="modal-body" style="height: 500px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query2" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th ><a href="#" data-ng-click="sortBy = 'posting_date'; reverse = !reverse">Posting Date</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'doc_no'; reverse = !reverse">Document No.</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'ref_no'; reverse = !reverse">Ref No.</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'gl_code'; reverse = !reverse">GL Code</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'amount'; reverse = !reverse">Amount</a></th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-show="dataList2.length!=0" ng-repeat= "data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
                                        <td>{{data.posting_date}}</td>
                                        <td>{{data.doc_no}}</td>
                                        <td>{{data.ref_no}}</td>
                                        <td>{{data.gl_code}}</td>
                                        <td align="right">{{ data.amount | currency : '' }}</td>
                                        <td align="right">
                                            <!-- Split button -->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#navigate" ng-click="navigate('<?php echo base_url(); ?>index.php/leasing_reports/navigate/' + data.id)"> <i class = "fa fa-eye"></i> Navigate</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="ng-cloak" ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
                                        <td colspan="6"><center>No Data Available.</center></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="ng-cloak">
                                        <td colspan="6" style="padding: 5px;">
                                            <div>
                                                <ul class="pagination">
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="prevPageDisabled2()">
                                                        <a href ng-click="prevPage2()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
                                                    </li>
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-repeat="n in range2()" ng-class="{active: n == currentPage2}" ng-click="setPage2(n)">
                                                        <a href="#">{{n+1}}</a>
                                                    </li>
                                                    <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0" ng-class="nextPageDisabled2()">
                                                        <a href ng-click="nextPage2()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
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
                    <button type="button" class="btn btn-danger" data-dismiss = "modal"><i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data view -->




        <!-- Update navigate Modal -->
    <div class="modal fade" id = "navigate" style="z-index: 1080 !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Navigate GL Account</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entry No.</th>
                                        <th>Trade Name</th>
                                        <th>Posting Date</th>
                                        <th>Document No.</th>
                                        <th>GL Code</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-show="navigateData.length!=0" ng-repeat= "data in navigateData">
                                        <td>{{data.id}}</td>
                                        <td>{{data.trade_name}}</td>
                                        <td>{{data.posting_date}}</td>
                                        <td>{{data.doc_no}}</td>
                                        <td>{{data.gl_code}}</td>
                                        <td>{{data.gl_account}}</td>

                                        <td class = "currency-align">
                                            <span ng-if = "!data.debit">-</span>
                                            <span ng-if = "data.debit">{{ data.debit | currency : '' }}</span>
                                        </td>

                                        <td class = "currency-align">
                                            <span ng-if = "!data.credit">-</span>
                                            <span ng-if = "data.credit">{{ data.credit | currency : '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="ng-cloak" ng-show="navigateData.length==0 || (navigateData | filter:query2).length == 0">
                                        <td colspan="8"><center>No Data Available.</center></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss = "modal"><i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->


</div> <!-- /.container -->
</body>
