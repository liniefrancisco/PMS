
<div class="container" ng-controller="transactionController">
    <div class="well" ng-controller="tableController">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> General Ledger</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="general_ledger">
                                <div class="row"> <!-- table wrapper  -->
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                            </div>
                                        </div>
                                    </div>
                                    <table ng-table="tableParams" class="table table-bordered" show-filter="false" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_generalLedger')">
                                        <tr ng-repeat="dt in data">
                                            <td title="'Entry No.'" sortable="'id'">{{dt.id}}</td>
                                            <td title="'Trade Name'" sortable="'trade_name'">{{dt.trade_name}}</td>
                                            <td title="'Doc Type'" sortable="'document_type'">{{dt.document_type}}</td>
                                            <td title="'Doc No.'" sortable="'doc_no'">{{dt.doc_no}}</td>
                                            <td title="'GL Code'" sortable="'gl_code'">{{dt.gl_code}}</td>
                                            <td title="'Description'" sortable="'gl_account'">{{dt.gl_account}}</td>
                                            <td title="'Posting Date'" sortable="'posting_date'">{{dt.posting_date}}</td>
                                            <td title="'Debit'" sortable="'debit'">{{ dt.debit | currency : ''}}</td>
                                            <td title="'Credit'" sortable="'credit'">{{ dt.credit | currency : ''}}</td>
                                            <td title="'Running Balance'" sortable="'running_balance'">{{ dt.balance | currency : ''}}</td>
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
                                    <div class="col-md-12">
                                        <a style="margin-right: 10px" class="btn btn-default btn-medium pull-right" href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#export_CSV"><i class="fa fa-download"></i> Export CSV</a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->

    <!-- Add Selected Bank Modal -->
    <div class="modal fade" id = "export_CSV">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-file"></i> Generate CSV</h4>
                </div>
                <form action="<?php echo base_url() ?>index.php/leasing_reports/generate_GLCSV" method="post" onSubmit="closeModal('export_CSV')" id="frm_generatecsv" name="frm_generatecsv" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="beginning_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Beginning Date</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                    <datepicker date-min-limit="" date-format="yyyy-M-dd">
                                                        <input
                                                            type="text"
                                                            required
                                                            readonly
                                                            placeholder="Choose a date"
                                                            class="form-control"
                                                            ng-model="beginning_date"
                                                            id="beginning_date"
                                                            name = "beginning_date"
                                                            autocomplete="off">
                                                    </datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="end_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>End Date</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                    <datepicker date-min-limit="" date-format="yyyy-M-dd">
                                                        <input
                                                            type="text"
                                                            required
                                                            readonly
                                                            placeholder="Choose a date"
                                                            class="form-control"
                                                            ng-model="end_date"
                                                            id="end_date"
                                                            name = "end_date"
                                                            autocomplete="off">
                                                    </datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center" style="padding-top: 20px">
                                    <button type="submit" ng-disabled = "frm_generatecsv.$invalid" class="btn btn-lg btn-primary"> <i class="fa fa-cog"></i> Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Add Selected Bank Modal -->





</div> <!-- End of Container -->
</body>
