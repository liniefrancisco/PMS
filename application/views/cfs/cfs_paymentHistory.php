
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading" style="background-color:#125821; color:whitesmoke"><i class="fa fa-list"></i> Payment History</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="payment_history">

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
			                                                    onchange = "clear_paymentHistory()"
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
			                                                            ng-model = "dirty.value"
			                                                            mass-autocomplete-item = "autocomplete_options"
			                                                            class = "form-control"
			                                                            id = "trae_name">
			                                                    </div>
			                                                    <span class="input-group-btn">
			                                                        <button
			                                                            class = "btn btn-info"
			                                                            type = "button"
			                                                            ng-click = "get_paymentScheme(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
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
											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
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

			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="curr_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Date</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        value="<?php echo date('Y-m-d'); ?>"
						                                        id="curr_date"
						                                        name = "curr_date"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
											</div>
										</div>
	                                </div>
                                </div>

                                <hr>

                                <div class="row"> <!-- table wrapper  -->
                                	<div class="col-md-12">
                                		<div class="row">
						                    <div class="col-md-3 pull-right">
						                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
						                    </div>
						                </div>
				                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList()">
				                            <thead>
				                                <tr>
				                                    <th><a href="#" data-ng-click="sortField = 'receipt_no'; reverse = !reverse">Receipt  No.</a></th>
				                                    <th><a href="#" data-ng-click="sortField = 'tender_typeCode'; reverse = !reverse">Billing Period</a></th>
				                                   	<th><a href="#" data-ng-click="sortField = 'tender_typeDesc'; reverse = !reverse">Description</a></th>
				                                   	<th><a href="#" data-ng-click="sortField = 'amount_paid'; reverse = !reverse">Amount Paid</a></th>
				                                   	<th><a href="#" data-ng-click="sortField = 'check_no'; reverse = !reverse">Check No.</a></th>
				                                   	<th><a href="#" data-ng-click="sortField = 'check_date'; reverse = !reverse">Check Date</a></th>
				                                   	<th><a href="#" data-ng-click="sortField = 'payee'; reverse = !reverse">Payee</a></th>
				                                   	<th width="6%">Action</th>
				                                </tr>
				                            </thead>
				                            <tbody id = "paymentHistory_tbody">
				                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "data in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
				                                    <td>{{ data.receipt_no }}</td>
				                                    <td>{{ data.billing_period }}</td>
				                                    <td>{{ data.tender_typeDesc }}</td>
				                                    <td>{{ data.amount_paid | currency : '' }}</td>
				                                    <td>
				                                    	<div ng-if = "!data.check_no">N/A</div>
				                                    	<div ng-if = "data.check_no">{{ data.check_no }}</div>
				                                    </td>
				                                    <td>
				                                    	<div ng-if = "data.check_date == '0000-00-00'">N/A</div>
				                                    	<div ng-if = "data.check_date != '0000-00-00'">{{ data.check_date }}</div>
				                                    </td>
				                                    <td>{{ data.payee }}</td>
				                                    <td><a href="<?php echo base_url(); ?>assets/pdf/{{data.receipt_doc}}" target="_blank" class="btn btn-small btn-primary"><i class="fa fa-print"></i></a></td>
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
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
