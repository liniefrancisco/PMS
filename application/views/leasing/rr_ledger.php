<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Receivables Ledger</div>
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
                                                                                
                                                                                    <div mass-autocomplete>
                                                                                        <input
                                                                                            required
                                                                                            name = "trade_name"
                                                                                            ng-model = "dirty.value"
                                                                                            mass-autocomplete-item = "autocomplete_options"
                                                                                            class = "form-control"
                                                                                            id = "trade_name"
                                                                                            ng-change="tenantInfo3(dirty.value, tenancy_type)">
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
                                                                            <label for="corporate_name" class="col-md-5 control-label text-right">Corporate Name</label>
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
                                                                            <label for="start_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Starting Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                            <datepicker date-format="yyyy-MM-dd" >
                                                                                                <input
                                                                                                    type="text"
                                                                                                    required
                                                                                                    readonly
                                                                                                    placeholder="Choose a date"
                                                                                                    class="form-control"
                                                                                                    id="start_date"
                                                                                                    name = "start_date"
                                                                                                    autocomplete="off"
                                                                                                    value="<?php echo $current_date; ?>"
                                                                                                    onchange="startingDate()">
                                                                                            </datepicker>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="end_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>End Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                            <datepicker date-format="yyyy-MM-dd" >
                                                                                                <input
                                                                                                    type="text"
                                                                                                    required
                                                                                                    readonly
                                                                                                    placeholder="Choose a date"
                                                                                                    class="form-control"
                                                                                                    id="end_date"
                                                                                                    name = "end_date"
                                                                                                    autocomplete="off"
                                                                                                    value="<?php echo $current_date; ?>">
                                                                                            </datepicker>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="corporate_name" class="col-md-5 control-label text-right"></label>
                                                                            <div class="col-md-7">
                                                                                <button
                                                                                        class = "btn btn-primary btn-block button-b"
                                                                                        type = "button"
                                                                                        id = "trade_name_button"
                                                                                        ng-click = "ledger_tenant(dirty.value, tenancy_type)" disabled><i class = "fa fa-search"> Generate</i>
                                                                                </button>
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
                                                                    <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                </div>
                                                                
                                                                <div class="col-md-3 pull-left input-group">
                                                                    <!-- <label for="forwarded_balance" class="col-md-9 control-label text-left">Forwarded Balance: </label> -->
                                                                    <div class="input-group-addon input-date">Forwarded Balance: </div>
                                                                    <input type="text" class="form-control text-right currency" placeholder="0.00" style="width: 100%" readonly name="" ui-number-mask="2" ng-model="forwardedBalance">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-md-12">
                                                            <div class="row">
                                                        
                                                            </div>
                                                        </div> -->

                                                        <table class="table table-bordered" ng-table = "tableParams" id="subsidiaryLedger_table" ng-controller="tableController">
                                                            <tbody id = "subsidiaryLedger_tbody">
                                                                <tr class="ng-cloak" ng-repeat="dt in data">
                                                                    <td title = "'Doc. Type'"    sortable = "'document_type'">{{ dt.document_type }}</td>
                                                                    <td title = "'Doc. No.'"     sortable = "'doc_no'">{{ dt.doc_no }}</td>
                                                                    <td title = "'Ref. No.'"     sortable = "'ref_no'">{{ dt.ref_no }}</td>
                                                                    <td title = "'Type'"         sortable = "'flag'">{{ dt.flag }}</td>
                                                                    <td title = "'Posting Date'" sortable = "'posting_date'">{{ dt.posting_date }}</td>
                                                                    <td title = "'Due Date'"     sortable = "'due_date'">{{ dt.due_date }}</td>

                                                                    <td title = "'Debit'" sortable = "'debit'" class = "currency-align">
                                                                        <span ng-if = "dt.debit === '0' || !dt.debit">-</span>
                                                                        <span ng-if = "dt.debit !== '0'">{{ dt.debit | currency : '' }}</span>
                                                                    </td>

                                                                    <td title = "'Credit'" sortable = "'credit'" class = "currency-align">
                                                                        <span ng-if = "dt.credit === '0' || !dt.credit">-</span>
                                                                        <span ng-if = "dt.credit !== '0'">{{ dt.credit | currency : '' }}</span>
                                                                    </td>

                                                                    <td title = "'Balance'" sortable = "'balance'" class = "currency-align">
                                                                        <span ng-if = "dt.balance === '0' || !dt.balance">-</span>
                                                                        <span ng-if = "dt.balance !== '0'">{{ dt.balance | currency : '' }}</span>
                                                                    </td>

                                                                    <td title = "'Running Balance'" sortable = "'runningBalance'" class = "currency-align">
                                                                        <span ng-if = "dt.runningBalance === '0' || !dt.runningBalance">-</span>
                                                                        <span ng-if = "dt.runningBalance !== '0'">{{ dt.runningBalance | currency : '' }}</span>
                                                                    </td align="center">

                                                                    <td title="'Action'">
                                                                        <button 
                                                                                class="btn btn-xs btn-info button-caret"
                                                                                type="button"
                                                                                data-toggle="modal"
                                                                                data-target="#payment_details_modal"
                                                                                data-backdrop = "static"
                                                                                ng-click="payment_details(dt.ref_no, dt.doc_no, dt.posting_date, dt.credit)"> Payment Details
                                                                        </button>

                                                                        <button 
                                                                                class="btn btn-xs btn-info button-caretb"
                                                                                type="button"
                                                                                data-toggle="modal"
                                                                                data-target="#details_modal"
                                                                                data-backdrop = "static"
                                                                                ng-click="Pdetails(dt)"> Details
                                                                        </button>
                                                                    </td>

                                                                    <!-- <td title = "'Entry No.'" sortable = "'entry_no'">{{ dt.entry_no }}</td>
                                                                    <td title = "'Ref. No.'" sortable = "'ref_no'">{{ dt.ref_no }}</td>
                                                                    <td title = "'Description'" sortable = "'gl_account'">{{ dt.gl_account }}</td>
                                                                    <td title = "'Bank Code'" sortable = "'bank_code'">{{ dt.bank_code }}</td> -->
                                                                    
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show = "isLoading">
                                                                <tr>
                                                                    <td colspan="11">
                                                                        <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                                        <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="col-md-12 mt-5" ng-if = "contract_no">
                                                            <a class="btn btn-default btn-medium pull-right" ng-click="exportExcel()"><i class="fa fa-download"></i> Export Excel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End of tab-content -->
                                        </div>
                                    </div>
                                </div> <!-- End of panel-body -->
                            </div> <!-- End of panel -->
                        </div> <!-- End of Well -->

    <!-- Payment Details Modal -->
    <div class="modal fade" id = "payment_details_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Payment Details</h4>
                </div>

                <div class="modal-body ng-cloak">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Doc no:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" style="width: 100%" readonly ng-model="doc_no">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Posting Date:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" style="width: 100%" readonly ng-model="posting_date">
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end of col-md-6 -->

                        <div class="container-fluid">
                            <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                        <th><a href="#" data-ng-click="sortField = 'doc_no'; reverse = !reverse">Doc No.</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Posting Date</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'payment_type'; reverse = !reverse">Payment Type</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'amount'; reverse = !reverse">Amount</a></th>
                                    </tr>
                                </thead>
                                <tbody id = "payment_details_table_tbody">
                                    <tr class="ng-cloak" ng-repeat="p in paymentDetails">
                                        <td>{{ p.doc_no }}</td>
                                        <td>{{ p.posting_date }}</td>
                                        <td>{{ p.payment_type}}</td>
                                        <td>{{ p.amount | currency : '' }}</td>
                                    </tr>
                                    <td ng-if="paymentDetails.length == 0" colspan="4"><center>No Data.</center></td>
                                </tbody>

                                <tbody ng-show = "isLoading">
                                    <tr>
                                        <td colspan="10">
                                            <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                            <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group pull-right">
                                    <label class="col-md-4 control-label text-right">Grand Total:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right" style="width: 100%" readonly value="&#8369 {{ grand_total | currency : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of row -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                </div> <!-- end of modal body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Payment Details Modal -->

    <!-- Details Modal -->
    <div class="modal fade" id = "details_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Applied Amount</h4>
                </div>

                <div class="modal-body ng-cloak">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Amount:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right" style="width: 100%" readonly value="&#8369 {{ d_amount | currency : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Basic Rent:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right" style="width: 100%" readonly value="&#8369 {{ basic_rent | currency : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Other Charges:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right" style="width: 100%" readonly  value="&#8369 {{ other_charges | currency : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Adjustments:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right" style="width: 100%" readonly value="&#8369 {{ adj_amount | currency : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Net Total:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-right red" style="width: 100%" readonly value="&#8369 {{ net_total | currency : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end of col-md-6 -->
                    </div> <!-- end of row -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                </div> <!-- end of modal body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Details Modal -->

                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->
