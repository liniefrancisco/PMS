<div class="container">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well" ng-controller="tableController">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> General Ledger</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop"
                                                        aria-controls="preop" role="tab" data-toggle="tab">General </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active" id="general_ledger">
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
                                                        <table ng-table="tableParams" class="table"
                                                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_generalLedger')">
                                                            <tbody>
                                                                <tr ng-repeat="dt in data">
                                                                    <td title="'Entry No.'" sortable="'id'">{{dt.id}}
                                                                    </td>
                                                                    <td title="'Trade Name'" sortable="'trade_name'">
                                                                        {{dt.trade_name}}</td>
                                                                    <td title="'Doc Type'" sortable="'doc_type'">
                                                                        {{dt.doc_type}}</td>
                                                                    <td title="'Doc No.'" sortable="'doc_no'">
                                                                        {{dt.doc_no}}</td>
                                                                    <td title="'GL Code'" sortable="'gl_code'">
                                                                        {{dt.gl_code}}</td>
                                                                    <td title="'Description'" sortable="'gl_account'">
                                                                        {{dt.gl_account}}</td>
                                                                    <td title="'Posting Date'"
                                                                        sortable="'posting_date'">{{dt.posting_date}}
                                                                    </td>
                                                                    <td title="'Debit'" sortable="'debit'">{{ dt.debit |
                                                                        currency : ''}}</td>
                                                                    <td title="'Credit'" sortable="'debit'">{{ dt.credit
                                                                        | currency : ''}}</td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show="isLoading">
                                                                <tr>
                                                                    <td colspan="10">
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
                                                            <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                                                <td colspan="10">
                                                                    <center>No Data Available.</center>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="col-md-12">
                                                            <a style="margin-right: 10px"
                                                                class="btn btn-default btn-medium pull-right button-w"
                                                                href="#" data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal" data-target="#export_CSV"><i
                                                                    class="fa fa-download"></i> Export CSV</a>
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
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#">Cyril Andrew</a></p>
        </div>
    </footer> <!-- .row -->
</div> <!-- .container -->