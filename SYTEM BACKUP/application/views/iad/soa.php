<div class="container" ng-controller = "tableController" >
    <div class="row" ng-controller = "transactionController">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well" >
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-eye"></i> View SOA</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                                            </ul>
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active" id="preop">
                                                    <div class="col-md-11">
                                                        <div class="row" style="margin-left:2%">
                                                            <div class="form-group">
                                                                <label for="tenancy_type" class="col-md-2 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
                                                                <div class="col-md-4">
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
                                                        <div class="row" style="margin-left:2%">
                                                            <div class="form-group">
                                                                <label for="tenant_id" class="col-md-2 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div mass-autocomplete>
                                                                            <input
                                                                                required
                                                                                name = "tenant_id"
                                                                                ng-model = "dirty.value"
                                                                                mass-autocomplete-item = "autocomplete_options"
                                                                                class = "form-control"
                                                                                id = "tenant_id">
                                                                        </div>
                                                                        <span class="input-group-btn">
                                                                            <button
                                                                                class = "btn btn-info"
                                                                                type = "button"
                                                                                ng-click = "ajaxLoadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_tenantSoa/', dirty.value); check(dirty.value)"><i class = "fa fa-search"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <table  class="table table-bordered" ng-table = "ajaxTableParams" id="charges_table" style="margin-left:50px" >
                                                                <tbody id="soa_tbody">
                                                                    <tr ng-repeat = "dt in ajaxData">
                                                                        <td title = "'Tenant ID'" sortable = "'tenant_id'">{{dt.tenant_id}}</td>
                                                                        <td title = "'SOA No.'" sortable = "'soa_no'">{{dt.soa_no}}</td>
                                                                        <td title = "'File Name'" sortable = "'file_name'">{{dt.file_name}}</td>
                                                                        <td title = "'Collection Date'" sortable = "'collection_date'">{{dt.collection_date}}</td>
                                                                        <td>
                                                                            <button
                                                                                class = "btn btn-success small-print-button"
                                                                                type = "button"
                                                                                ng-click = "reprint_soa('<?php echo base_url(); ?>assets/pdf/' + dt.file_name)">
                                                                                <i class = "fa fa-print"></i>
                                                                            </button>
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
                                                                <tr class="ng-cloak" ng-show="!ajaxData.length && !isLoading">
                                                                    <td colspan="10"><center>No Data Available.</center></td>
                                                                </tr>
                                                            </table>
                                                        </div> <!-- EDITABLE GRID END ROW -->
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
