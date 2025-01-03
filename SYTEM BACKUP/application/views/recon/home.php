<div class="container">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well" ng-controller="tableController">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-newspaper-o"></i> Bank Recon</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active">
                                                    <div class="col-md-12">
                                                        <div class="row" ng-init = "populate_combobox('<?php echo base_url() ?>index.php/ctrl_recon/get_stores')">
                                                            <form action="#"  method="post" id="frm_recon" name = "frm_recon">
                                                                <div class="row">
                                                                    <div class="col-md-4"> <!-- SECOND COL-MD-4 WRAPPER -->
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="beginning_date" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>Month & Year</label>
                                                                                <div class="col-md-8 text-left">
                                                                                    <input type = "text" name = "month" autocomplete="off" required ng-model = "month" class = "form-control"/>
                                                                                    <div class="validation-Error">
                                                                                        <span ng-show="frm_summary.month.$dirty && frm_summary.month.$error.required">
                                                                                            <p class="error-display">This field is required.</p>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- END OF THE 1ST COL-MD-4 WRAPPER -->
                                                                    <div class="col-md-4"> <!-- SECOND COL-MD-4 WRAPPER -->
                                                                        <div class="row">
                                                                            <a href="#" ng-click = "loadList('<?php echo base_url() ?>index.php/ctrl_recon/recon_data/' + month)" style="line-height: 1" class="btn btn-primary btn-lg">Filter</a>
                                                                        </div>
                                                                    </div> <!-- END OF THE 1ST COL-MD-4 WRAPPER -->
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="row"> <!-- table wrapper  -->
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-3 pull-right">
                                                                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table ng-table="tableParams" class="table" >
                                                                <tbody>
                                                                    <tr ng-repeat="dt in data">
                                                                        <td title="'Posting Date'" sortable="'posting_date'">{{dt.posting_date}}</td>
                                                                        <td title="'Payor'" sortable="'payor'">{{dt.payor}}</td>
                                                                        <td title="'Description'" sortable="'tender_typeDesc'">{{dt.tender_typeDesc}}</td>
                                                                        <td title="'Amount'" sortable="'amount_paid'">{{dt.amount_paid}}</td>
                                                                        <td title="'OR #'" sortable="'receipt_no'">{{dt.receipt_no}}</td>
                                                                        <td title="'Check No.'" sortable="'check_no'">{{dt.check_no}}</td>
                                                                        <td title="'Check Date'" sortable="'check_date'">{{dt.check_date}}</td>
                                                                        <td title="'Bank'" sortable="'Bank'">{{dt.bank}}</td>
                                                                        <td align="center" title="'Attachment'"><a href="<?php echo base_url(); ?>index.php/ctrl_recon/view_attachement/{{dt.tender_typeDesc}}/{{dt.receipt_no}}" target="_blank" class="btn btn-small btn-success"><i class="fa fa-view"></i>View</a></td>
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
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->
