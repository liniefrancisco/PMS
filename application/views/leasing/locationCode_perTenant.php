<div class="container" ng-controller="transactionController">
    <div class="well" ng-controller="tableController">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Location Code Per Tenant</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab"
                                    data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="general_ledger">
                                <div class="row"> <!-- table wrapper  -->
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <input type="text" class="form-control search-query"
                                                    placeholder="Search Here..." ng-model="searchedKeyword" />
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered" ng-table="tableParams" id="generalLedger_table"
                                        ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_reports/get_locationCodePerTenant')">

                                        <tbody id="subsidiaryLedger_tbody">
                                            <tr class="ng-cloak" ng-repeat="dt in data">
                                                <td title="'Store Name'" sortable="'store_name'">{{ dt.store_name }}
                                                </td>
                                                <td title="'Floor Location'" sortable="'floor_name'">{{ dt.floor_name }}
                                                </td>
                                                <td title="'Location Code'" sortable="'location_code'">{{
                                                    dt.location_code }}</td>
                                                <td title="'Description'" sortable="'description'">{{ dt.description }}
                                                </td>
                                                <td title="'Trade Name'" sortable="'trade_name'">{{ dt.trade_name }}
                                                </td>
                                                <td title="'Tenancy Type'" sortable="'tenancy_type'">{{ dt.tenancy_type
                                                    }}</td>
                                                <td title="'Contract Date'" sortable="'contract_date'">{{
                                                    dt.contract_date }}</td>
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
                                            href="<?php echo base_url(); ?>index.php/leasing_reports/export_locationCodePerTenant"><i
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


</div> <!-- End of Container -->
</body>