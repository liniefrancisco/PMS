
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Account Receivable</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment">

                                <div class="row"> <!-- table wrapper  --> 
                                	<table class="table table-bordered" id="subsidiaryLedger_table" ng-controller="tableController">
                                		<thead>
                                			<div class="col-md-12">
	                                			<div class="row">
								                    <div class="col-md-3 pull-right">
								                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
								                    </div>
								                </div>
	                                		</div>
                                            <tr>
                                                <th ><a href="#" data-ng-click="sortField = 'trade_name'; reverse = !reverse">Trade Name</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'doc_no'; reverse = !reverse">Document No.</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'ref_no'; reverse = !reverse">Ref. No.</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'desc'; reverse = !reverse">Description</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Posting Date</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'due_date'; reverse = !reverse">Due Date</a></th>
                                                <th ><a href="#" data-ng-click="sortField = 'amount'; reverse = !reverse">Amount</a></th>
                                            </tr>
                                        </thead>
                                        <tbody id = "subsidiaryLedger_tbody" ng-init = "get_accountReceivable()">
                                        	<tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "data in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                        		<td>{{ data.trade_name }}</td>
                                        		<td>{{ data.doc_no }}</td>
                                        		<td>{{ data.ref_no }}</td>
                                        		<td>{{ data.gl_account }}</td>
                                        		<td>{{ data.posting_date }}</td>
                                        		<td>{{ data.due_date }}</td>
                                        		<td class = "currency-align">{{ data.amount | currency : '' }}</td>
                                        	</tr>
                                        </tbody>
                                        <tfoot>
			                                <tr class="ng-cloak">
			                                    <td colspan="9" style="padding: 5px;">
			                                        <div>
			                                            <ul class="pagination">
			                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="prevPageDisabled()">
			                                                    <a href ng-click="prevPage()" style="border-radius: 0px;"><i class = "fa fa-angle-double-left"></i> Prev</a>
			                                                </li>
			                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)">
			                                                <a href="#">{{n+1}}</a>
			                                                </li>
			                                                <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="nextPageDisabled()">
			                                                    <a href ng-click="nextPage()" style="border-radius: 0px;">Next <i class = "fa fa-angle-double-right"></i></a>
			                                                </li>
			                                            </ul>
			                                        </div>
			                                    </td>
			                                </tr>
			                            </tfoot>
                                	</table>
                                	<div class="col-md-12">
                                    	<a class="btn btn-default btn-medium pull-right" href="<?php echo base_url(); ?>index.php/leasing_reports/export_accountReceivable/{{date_from}}/{{date_to}}"  ><i class="fa fa-download"></i> Export Excel</a>
                                    	<!-- <a style="margin-right: 10px" onclick="print_subsidiaryLedger('<?php echo base_url(); ?>index.php/leasing_transaction/print_tenantLedger/')" class="btn btn-primary btn-medium pull-right"><i class="fa fa-print"></i> Print PDF</a> -->
                                    </div>
                                </div>				
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->


</div> <!-- End of Container -->

</body>