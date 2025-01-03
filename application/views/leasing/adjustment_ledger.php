<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Adjustments History
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop"
                                                        aria-controls="preop" role="tab" data-toggle="tab">General </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active" id="payment">
                                                    <div class="row">
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenancy_type"
                                                                                class="col-md-5 control-label text-right"><i
                                                                                    class="fa fa-asterisk"></i>Tenancy
                                                                                Type</label>
                                                                            <div class="col-md-7">
                                                                                <select name="tenancy_type"
                                                                                    class="form-control"
                                                                                    ng-model="tenancy_type"
                                                                                    ng-change="populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
                                                                                    required>
                                                                                    <option value="" disabled=""
                                                                                        selected=""
                                                                                        style="display:none">Please
                                                                                        Select One</option>
                                                                                    <option>Short Term Tenant</option>
                                                                                    <option>Long Term Tenant</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="trade_name"
                                                                                class="col-md-5 control-label text-right"><i
                                                                                    class="fa fa-asterisk"></i>Trade
                                                                                Name</label>
                                                                            <div class="col-md-7">
                                                                                <div mass-autocomplete>
                                                                                    <input id="trade_name"
                                                                                        name="trade_name"
                                                                                        class="form-control"
                                                                                        ng-model="dirty.value"
                                                                                        ng-change="adjustment_ledger(dirty.value, tenancy_type)"
                                                                                        mass-autocomplete-item="autocomplete_options"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- COL-MD-6 DIVIDER -->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenant_id"
                                                                                class="col-md-5 control-label text-right"><i
                                                                                    class="fa fa-asterisk"></i>Tenant
                                                                                ID</label>
                                                                            <div class="col-md-7">
                                                                                <input id="tenant_id" name="tenant_id"
                                                                                    type="text" class="form-control"
                                                                                    ng-model="tenant_id" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="corporate_name"
                                                                                class="col-md-5 control-label text-right">Corporate
                                                                                Name</label>
                                                                            <div class="col-md-7">
                                                                                <input id="corporate_name" type="text"
                                                                                    name="corporate_name"
                                                                                    class="form-control"
                                                                                    ng-model="corporate_name" readonly>
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
                                                                    <input type="text" class="form-control search-query"
                                                                        placeholder="Search Here..."
                                                                        ng-model="searchedKeyword" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered" ng-table="tableParams"
                                                            id="subsidiaryLedger_table" ng-controller="tableController">
                                                            <tbody id="subsidiaryLedger_tbody">
                                                                <tr class="ng-cloak" ng-repeat="al in data">
                                                                    <td title="'Adjustment No.'" sortable="'adj_no'">{{
                                                                        al.adj_no }}</td>
                                                                    <td title="'Doc No.'" sortable="'doc_no'">{{
                                                                        al.doc_no }}</td>
                                                                    <td title="'Adjustment Date'"
                                                                        sortable="'transaction_date'">{{
                                                                        al.transaction_date }}</td>
                                                                    <td title="'Adjusted By'" sortable="'modified_by'">
                                                                        {{ al.modified_by }}</td>
                                                                    <td title="'Approved By'" sortable="'approved_by'">
                                                                        {{ al.approved_by }}</td>
                                                                    <td title="'Total Amount'" sortable="'amount'"
                                                                        class="currency-align">{{ al.amount | currency :
                                                                        '' }}</td>
                                                                    <td title="'File'" sortable="'adj_document'">{{
                                                                        al.adj_document }}</td>

                                                                    <td title="'Action'" class="text-center">
                                                                        <button title="Print Document"
                                                                            class="btn btn-xs btn-info" type="button"
                                                                            ng-click="printlog(al)"
                                                                            ng-if="al.doc_type == 'Invoice Adjustment'"><i
                                                                                class="fa fa-print"
                                                                                aria-hidden="true"></i></button>
                                                                        <a type="button"
                                                                            href="<?php echo base_url(); ?>assets/pdf/{{ al.adj_document }}"
                                                                            title="Print Document"
                                                                            class="btn btn-xs btn-info"
                                                                            ng-if="al.doc_type == 'Payment Adjustment'"><i
                                                                                class="fa fa-print" aria-hidden="true"
                                                                                target="_blank"></i>
                                                                        </a>
                                                                        <button type="button"
                                                                            title="Supporting Document"
                                                                            class="btn btn-xs btn-success"
                                                                            ng-click="fetchSuppDoc(al)"
                                                                            data-target="#adj_supp_doc"
                                                                            data-toggle="modal" data-backdrop="static">
                                                                            <i class="fa fa-file-archive-o"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show="isLoading">
                                                                <tr>
                                                                    <td colspan="11">
                                                                        <div class="table-loader"><img
                                                                                src="<?php echo base_url(); ?>img/spinner2.svg">
                                                                        </div>
                                                                        <div class="loader-text">
                                                                            <center><b>Collecting Data. Please
                                                                                    Wait...</b></center>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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

    <!-- ADJUSTMENT DOCUMENT PREVIEW -->
    <div class="modal fade" id="adj_supp_doc" style="overflow-y: scroll;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Supporting Document</h4>
                </div>
                <div class="modal-body ng-cloak">
                    <div class="container">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li ng-repeat="i in image" data-target="#myCarousel" data-slide-to="{{$index}}"
                                    ng-class="{ active: ($index === 0)}"></li>
                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox" style="min-height: 400px;">
                                <div ng-if="!image || image.length == 0" class="item active center-block">
                                    <img src="<?= base_url(); ?>img/thumbnail.png" alt="Default" class="img-responsive">
                                </div>

                                <div ng-repeat="i in image" ng-class="{item: true, active: ($index === 0)}">
                                    <img src="<?= base_url('assets/adj_supp_doc') ?>/{{ i.file_name }}"
                                        alt="{{ i.file_name }}" style="width:100%;">
                                </div>
                            </div>

                            <!-- Left and right controls -->
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"
                                ng-click="imageClose()">
                                <i class="fa fa-close"></i> Close
                            </button>
                        </div>
                    </div>
                </div> <!-- end of modal body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- ADJUSTMENT DOCUMENT PREVIEW END -->

    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#">Cyril Andrew</a></p>
        </div>
    </footer> <!-- .row -->
</div> <!-- .container -->