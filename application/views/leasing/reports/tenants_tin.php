<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Tenants TIN
                                    Monitoring</div>
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
                                                            <form ng-submit="generateTenantWithTIN($event)"
                                                                target="_blank" method="post" name="add_form">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tin_status"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>TIN
                                                                                    Status</label>
                                                                                <div class="col-md-7">
                                                                                    <select class="form-control"
                                                                                        name="tin_status"
                                                                                        ng-model="tin_status" required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <option>With TIN</option>
                                                                                        <option>On Process</option>
                                                                                        <option>No TIN</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tenancy_type"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Tenancy
                                                                                    Type</label>
                                                                                <div class="col-md-7">
                                                                                    <select class="form-control"
                                                                                        name="tenancy_type"
                                                                                        ng-model="tenancy_type"
                                                                                        required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <option>All Terms</option>
                                                                                        <option>Long Term</option>
                                                                                        <option>Short Term</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="store"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Store</label>
                                                                                <div class="col-md-7">
                                                                                    <select class="form-control"
                                                                                        name="store" ng-model="store"
                                                                                        required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <option>All</option>
                                                                                        <option value="ACT">Alta Citta
                                                                                        </option>
                                                                                        <option value="AM">Alturas Mall
                                                                                        </option>
                                                                                        <option value="ICM">Island City
                                                                                            Mall</option>
                                                                                        <option value="PM">Plaza Marcela
                                                                                        </option>
                                                                                        <option value="ATT">Alturas
                                                                                            Tubigon</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="corporate_name"
                                                                                    class="col-md-5 control-label text-right"></label>
                                                                                <div class="col-md-7">
                                                                                    <button
                                                                                        class="btn btn-primary btn-block  button-b"
                                                                                        type="submit"
                                                                                        id="trade_name_button"
                                                                                        ng-disabled="add_form.$invalid"><i
                                                                                            class="fa fa-search">
                                                                                            Generate</i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="row" ng-show="tenantwhttable"> <!-- table wrapper  -->
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
                                                                <tr class="ng-cloak" ng-repeat="t in data">
                                                                    <td title="'Tenant ID'" sortable="'entry_no'">{{
                                                                        t.tenant_id }}</td>
                                                                    <td title="'Company Name'"
                                                                        sortable="'document_type'">{{ t.corporate_name
                                                                        }}</td>
                                                                    <td title="'Trade Name'" sortable="'doc_no'">{{
                                                                        t.trade_name }}</td>
                                                                    <td title="'TIN'" sortable="'ref_no'">{{ t.tin }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show="isLoading">
                                                                <tr>
                                                                    <td colspan="4">
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
                                                        <div class="col-md-12" ng-if="tenantwhttable">
                                                            <a class="btn btn-default btn-medium pull-right button-w"
                                                                href="<?php echo base_url(); ?>index.php/leasing_reports/export_tenanttin/{{tenancy_type}}/{{store}}/{{tin_status}}"><i
                                                                    class="fa fa-download"></i> Export Excel</a>
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
            <p class="copyright">Copyright ©
                <?php echo date('Y') ?> AGC | Design: <a rel="nofollow" href="#">Cyril Andrew</a>
            </p>
        </div>
    </footer> <!-- .row -->
</div> <!-- .container -->