<div class="container">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well" ng-controller="tableController">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-folder-open"></i> Unclosed PDC</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active">
                                                    <div class="col-md-12">
                                                        <div class="row"> <!-- table wrapper  -->
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-3 pull-right">
                                                                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_unclosedPDC')" ng-table="tableParams" class="table" >
                                                                <tbody>
                                                                    <tr ng-repeat="dt in data">
                                                                        <td title = "'Trade Name'" sortable = "'trade_name'">{{ dt.trade_name }}</td>
                                                                        <td title = "'Document No.'" sortable = "'doc_no'">{{ dt.doc_no }}</td>
                                                                        <td title = "'Description'" sortable = "'gl_account'">{{ dt.gl_account }}</td>
                                                                        <td title = "'Check Date'" sortable = "'check_date'">{{ dt.check_date }}</td>
                                                                        <td title = "'Posting Date'" sortable = "'posting_date'">{{ dt.posting_date }}</td>
                                                                        <td title = "'G/L Code'" sortable = "'gl_code'">{{ dt.gl_code }}</td>
                                                                        <td title = "'Amount'" sortable = "'amount'" class = "currency-align">{{ dt.amount | currency : '' }}</td>
                                                                        <td align="center" title="'Action'">
                                                                            <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#closingPDC_modal" ng-click = "closingPDC('<?php echo base_url(); ?>index.php/leasing_transaction/closePDC/' + dt.id, dt.trade_name, dt.check_date, dt.amount)" class="btn btn-small btn-primary"><i class="fa fa-close"></i> Close PDC</a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
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
                                                                <a style="margin-right: 10px" class="btn btn-default btn-medium pull-right" href="<?php echo base_url(); ?>index.php/ctrl_recon/recon_exportCSV/{{ month }}"><i class="fa fa-download"></i> Export CSV</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End of tab-content -->
                                        </div>
                                    </div>
                                </div> <!-- End of panel-body -->
                            </div> <!-- End of panel -->
                        </div> <!-- End of Well -->
                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->




    <!-- PDC Closing Modal -->
<div class="modal fade" id = "closingPDC_modal">
    <div class="modal-dialog modal-md" style="width:45%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-check"></i> PDC Closing</h4>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="frm_closingPDC" name="frm_closingPDC">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label for="doc_no" class="col-md-4 control-label text-right invoice-label">Document No.</label>
                                <div class="col-md-8 pull-right">
                                    <input
                                        type = "text"
                                        class = "form-control currency"
                                        name="doc_no"
                                        ng-model = "doc_no"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <label for="trade_name" class="col-md-4 control-label text-right invoice-label">Trade Name</label>
                                <div class="col-md-8 pull-right">
                                    <input
                                        type = "text"
                                        class = "form-control currency"
                                        name="trade_name"
                                        ng-model = "trade_name"
                                        readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="check_date" class="col-md-4 control-label text-right invoice-label">Check Date</label>
                                <div class="col-md-8 pull-right">
                                    <input
                                        type = "text"
                                        class = "form-control currency"
                                        name="check_date"
                                        ng-model = "check_date"
                                        readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="amount" class="col-md-4 control-label text-right invoice-label">Amount</label>
                                <div class="col-md-8 pull-right">
                                    <input
                                        type = "text"
                                        class = "form-control currency"
                                        ng-model = "amount"
                                        ui-number-mask="2"
                                        readonly
                                        id = "amount"
                                        name = "amount"
                                        autocomplete = "off">
                                </div>
                            </div>
                            <div class="row">
                                <label for="amount" class="col-md-4 control-label text-right invoice-label">Posting Date</label>
                                <div class="col-md-8 pull-right">
                                    <div class="input-group">
                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                        <datepicker date-format="yyyy-M-dd" >
                                            <input
                                                type="text"
                                                required
                                                placeholder="Choose a date"
                                                class="form-control currency"
                                                ng-model="posting_date"
                                                id="posting_date"
                                                name = "posting_date"
                                                autocomplete="off">
                                         </datepicker>

                                         <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_closingPDC.posting_date.$dirty && frm_closingPDC.posting_date.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type = "submit" ng-disabled = "frm_closingPDC.$invalid" class = "btn btn-primary btn-medium"><i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End PDC Closing Modal -->






    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->
