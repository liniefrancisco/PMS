
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Tenant Ledger</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment">
                                <div class="row">
                                	<div class="col-md-10 col-md-offset-1">
										<div class="row">
											<div class="col-md-6">
			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
			                                            <div class="col-md-7">
			                                                <select
			                                                    class = "form-control"
			                                                    name = "tenancy_type"
			                                                    ng-model = "tenancy_type"
			                                                    ng-change = "populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
			                                                    required>
			                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
			                                                    <option>Short Term Tenant</option>
			                                                    <option>Long Term Tenant</option>
			                                                </select>
			                                            </div>
			                                        </div>
			                                    </div>
			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
			                                            <div class="col-md-7">
			                                                <div class="input-group">
			                                                    <div mass-autocomplete>
			                                                        <input
			                                                            required
			                                                            name = "trade_name"
			                                                            ng-model = "trade_name"
			                                                            class = "form-control"
			                                                            id = "trade_name"
			                                                            readonly>
			                                                    </div>
			                                                    <span class="input-group-btn">
			                                                        <button
			                                                            class = "btn btn-info"
			                                                            type = "button"
			                                                            data-backdrop="static"
			                                                            data-keyboard="false"
			                                                            data-toggle="modal"
			                                                            data-target="#lookup"
			                                                            ng-click = "loadList2('<?php echo base_url(); ?>index.php/ctrl_cfs/tenant_lookup/' + tenancy_type)"><i class = "fa fa-search"></i>
			                                                        </button>
			                                                    </span>
			                                                </div>
			                                            </div>
			                                        </div>
			                                    </div>

			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "tenant_id"
						                                        id="tenant_id"
						                                        name = "tenant_id"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="corporate_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Corporate Name</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "corporate_name"
						                                        id="corporate_name"
						                                        name = "corporate_name"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
												<div class="row">
			                                        <div class="form-group">
			                                            <label for="address" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Address</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "address"
						                                        id="address"
						                                        name = "address"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
												<div class="row">
			                                        <div class="form-group">
			                                            <label for="tin" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>TIN</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "tin"
						                                        id="tin"
						                                        name = "tin"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

												<div class="row">
			                                        <div class="form-group">
			                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contract No.</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "contract_no"
						                                        id="contract_no"
						                                        name = "contract_no"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

												<div class="row">
			                                        <div class="form-group">
			                                            <label for="rental_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Rental Type</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "rental_type"
						                                        id = "rental_type"
						                                        name = "rental_type"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
											</div>
										</div>
	                                </div>
                                </div>

                                <hr>

                                <div class="row" ng-controller = "tableController"> <!-- table wrapper  -->
                                	<div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                            </div>
                                        </div>
                                    </div>
                                	<table ng-table="tableParams" class="table table-bordered" show-filter="false">
                                        <tr ng-repeat="dt in data">
                                            <td title="'Entry No.'" sortable="'entry_no'">{{dt.entry_no}}</td>
                                            <td title="'Doc Type'" sortable="'doc_type'">{{dt.document_type}}</td>
                                            <td title="'Doc No.'" sortable="'doc_no'">{{dt.doc_no}}</td>
                                            <td title="'Due Date'" sortable="'due_date'">{{dt.due_date}}</td>
                                            <td title="'GL Code'" sortable="'gl_code'">{{dt.gl_code}}</td>
                                            <td title="'Description'" sortable="'gl_account'">{{dt.gl_account}}</td>
                                            <td title="'Posting Date'" sortable="'posting_date'">{{dt.posting_date}}</td>
                                            <td class = "currency-align" title="'Debit'" sortable="'debit'">
                                    			<span ng-if = "dt.debit === '0' || !dt.debit">-</span>
                                    			<span ng-if = "dt.debit !== '0'">{{ dt.debit | currency : '' }}</span>
                                    		</td>
                                    		<td class = "currency-align" title="'Credit'" sortable="'credit'">
                                    			<span ng-if = "dt.credit === '0' || !dt.credit">-</span>
                                    			<span ng-if = "dt.credit !== '0'">{{ dt.credit | currency : '' }}</span>
                                    		</td>
                                            <td class = "currency-align" title="'Running Balance'" sortable="'balance'">
                                            	<span ng-if = "dt.balance === '0' || !dt.balance">-</span>
                                        		<span ng-if = "dt.balance !== '0'">{{ dt.balance | currency : '' }}</span>
                                            </td>
                                        </tr>
                                        <tbody ng-show = "isLoading">
                                            <tr>
                                                <td colspan="10">
                                                    <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                    <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                            <td colspan="10"><center>No Data Available.</center></td>
                                        </tr>
                                    </table>
                                	<div class="col-md-12" ng-if = "contract_no">
                                    	<a class="btn btn-default btn-medium pull-right" href="<?php echo base_url(); ?>index.php/leasing_transaction/export_tenantLedger/{{tenant_id}}"  ><i class="fa fa-download"></i> Export Excel</a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->



<!-- Tenant Lookup Modal -->
    <div class="modal fade" id = "lookup" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-tv"></i> Tenant Look Up</h4>
                </div>
                <div class="modal-body">
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
                                        <th ><a href="#" data-ng-click="sortBy = 'tenant_id'; reverse = !reverse">Tenant ID</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'trade_name'; reverse = !reverse">Trade Name</a></th>
                                        <th ><a href="#" data-ng-click="sortBy = 'store_name'; reverse = !reverse">Store Location</a></th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-show="dataList2.length!=0" ng-repeat= "data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
                                        <td>{{data.tenant_id}}</td>
                                        <td>{{data.trade_name}}</td>
                                        <td>{{data.store_name}}</td>
                                        <td align="right">
                                            <!-- Split button -->
                                            <div class="btn-group" style="width:70% !important">
                                                <button type="button" style="width:70% !important" ng-click = "admin_TL(data.tenant_id)" class="btn btn-xs btn-primary" data-dismiss = "modal">Select</button>
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
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
	<!-- End Tenant Lookup Modal -->






</div> <!-- End of Container -->
</body>
