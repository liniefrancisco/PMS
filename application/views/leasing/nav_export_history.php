<div class="container" id="transactionController" ng-controller="transactionController">

    <div class="well">
        <div class="panel panel-default row">
            <div class="panel-heading panel-leasing"><i class="fa fa-history"></i> Exportation History</div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="collection_reports"
                                    role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active well" id="preop">
                                <div class=" row ">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3 pull-right">
                                                    <input type="text" class="form-control search-query"
                                                        placeholder="Search Here..." ng-model="searchedKeyword" />
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered" ng-table="tableParams"
                                            ng-controller="tableController">
                                            <tbody
                                                ng-init="loadList('<?= base_url('leasing_reports/get_nav_export_data') ?>')">
                                                <tr class="ng-cloak" ng-repeat="dt in data">
                                                    <td title="'Batch Code'" sortable="'batch_code'" align="center">{{
                                                        dt.batch_code }}</td>
                                                    <td title="'Doc. Type'" sortable="'type'" align="center">{{ dt.type
                                                        }}</td>
                                                    <td title="'Filter'" sortable="'filter'" align="center">{{ dt.filter
                                                        }}</td>
                                                    <td title="'Store'" sortable="'store'" align="center">{{ dt.store }}
                                                    </td>
                                                    <td title="'Exported By'" sortable="'full_name'">{{ dt.full_name }}
                                                    </td>
                                                    <td title="'Date Exported'" sortable="'exported_at'" align="center">
                                                        {{ dt.exported_at }}</td>
                                                    <td title="'Action'" align="center">
                                                        <a href="<?= base_url('leasing_reports/redownloadNavTextfile') ?>/{{dt.id}}"
                                                            target="_blank" class="btn btn-sm button-caretb"
                                                            style="padding: 3px 7px;" title="Download Textfile">
                                                            <i class="fa fa-download"></i>
                                                        </a>

                                                        <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                            <button type="button"
                                                                ng-click="removeExportationTag('<?= base_url('leasing_reports/removeExportationTag') ?>/' + dt.batch_code)"
                                                                class="btn btn-sm btn-danger button-caret"
                                                                style="padding: 3px 7px;" title="Untag">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody ng-show="isLoading">
                                                <tr>
                                                    <td colspan="10">
                                                        <div class="table-loader"><img
                                                                src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                        <div class="loader-text">
                                                            <center><b>Collecting Data. Please Wait...</b></center>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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