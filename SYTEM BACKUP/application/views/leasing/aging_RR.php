
<div class="container" ng-controller="transactionController">
    <div class="well" ng-controller="tableController">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-calendar"></i> Aging - Rent Receivable</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="general_ledger">
                                <div class="row"> <!-- table wrapper  -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-4">
                                                <div class="row pull-left">
                                                    <div class="form-group">
                                                        <div class="col-md-8 text-left">
                                                            <div class="input-group">
                                                                <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                <datepicker date-format="yyyy-MM-dd">
                                                                    <input
                                                                        type="text"
                                                                        required
                                                                        readonly
                                                                        placeholder="Date Created"
                                                                        class="form-control"
                                                                        ng-model="date_created"
                                                                        id="date_created"
                                                                        name = "date_created"
                                                                        autocomplete="off">
                                                                </datepicker>
                                                            </div>
                                                        </div>
                                                        <button type="button" ng-click = "ajaxLoadList('<?php echo base_url(); ?>index.php/leasing_reports/get_agingRR/', date_created)" class="btn btn-medium btn-info" name="button">Generate</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered" id="RRaging_table"  ng-table = "ajaxTableParams" >
                                        <tbody id = "RRaging_tbody">
                                            <tr class="ng-cloak"ng-repeat= "dt in ajaxData">
                                                <td title = "'Trade Name'" sortable = "'trade_name'">{{ dt.trade_name }}</td>
                                                <td title = "'Current'" sortable = "'current'" align = "right">{{ dt.current | currency : '' }}</td>
                                                <td title = "'7 Days'" sortable = "'days7'" align = "right">{{ dt.days7 | currency : '' }}</td>
                                                <td title = "'15 Days'" sortable = "'days15'" align = "right">{{ dt.days15 | currency : '' }}</td>
                                                <td title = "'30 Days'" sortable = "'days30'" align = "right">{{ dt.days30 | currency : '' }}</td>
                                                <td title = "'60 Days'" sortable = "'days60'" align = "right">{{ dt.days60 | currency : '' }}</td>
                                                <td title = "'90 Days'" sortable = "'days90'" align = "right">{{ dt.days90 | currency : '' }}</td>
                                                <td title = "'180 Days'" sortable = "'days180'" align = "right">{{ dt.days180 | currency : '' }}</td>
                                                <td title = "'360 Days & Above'" sortable = "'days360'" align = "right">{{ dt.days360 | currency : '' }}</td>
                                            </tr>
                                            <tr class="ng-cloak" ng-show="!ajaxData.length && !isLoading">
                                                <td colspan="10"><center>No Data Available.</center></td>
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
                                    <div class="col-md-12" ng-if = "ajaxData.length!=0">
                                        <a class="btn btn-default btn-medium pull-right" onclick="generate_aging('<?php echo base_url(); ?>index.php/leasing_reports/export_agingRR/')"  ><i class="fa fa-download"></i> Export Excel</a>
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
