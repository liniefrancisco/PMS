
<div class="container" ng-controller="transactionController">
    <div class="well" ng-controller="tableController">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-close"></i> Waived Penalties</div>
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
                                    <table class="table table-bordered" id="generalLedger_table" ng-table = "tableParams" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_reports/get_waivedPenalties')">
                                        
                                        <tbody id = "subsidiaryLedger_tbody">
                                            <tr class="ng-cloak" ng-repeat= "dt in data">
                                                <td title = "'Tenant ID'" sortable = "'tenant_id'">{{ dt.tenant_id }}</td>
                                                <td title = "'Trade name'" sortable = "'trade_name'">{{ dt.trade_name }}</td>
                                                <td title = "'Posting Date'" sortable = "'posting_date'">{{ dt.posting_date }}</td>
                                                <td title = "'Description'" sortable = "'description'">{{ dt.description }}</td>
                                                <td title = "'Amount'" sortable = "'amount'">{{ dt.amount }}</td>
                                                <td title = "'Prepared By'" sortable = "'prepared_by'">{{ dt.prepared_by }}</td>
                                                <td class = "currency-align">
                                                    <!-- Split button -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                        <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" ng-click = "previewImg('<?php echo base_url(); ?>assets/other_img/' + dt.attachment)" data-target="#preview_img" ng-click = ""> <i class = "fa fa-eye"></i> View Attachment</a></li>
                                                        </ul>
                                                    </div>
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
                                    </table>
                                </div>              
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->



    <!-- Preview Image Modal -->
    <div class="modal fade" id = "preview_img">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-image"></i> Waiver Attachment</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class = "thumbnail">
                                <img src="{{ImgData}}" alt="Waiver">
                            </div>
                        </div>        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Preview Image Modal -->


</div> <!-- End of Container -->
</body>