<div class="container" id="transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Other Charges - AR Non Trade
                Reports</div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="AR_reports"
                                    role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <table class="table">
                                <thead>
                                    <th>JAN</th>
                                    <th>FEB</th>
                                    <th>MAR</th>
                                    <th>APR</th>
                                    <th>MAY</th>
                                    <th>JUN</th>
                                    <th>JUL</th>
                                    <th>AUG</th>
                                    <th>SEP</th>
                                    <th>OCT</th>
                                    <th>NOV</th>
                                    <th>DEC</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $JAN; ?>
                                        </td>
                                        <td>
                                            <?php echo $FEB; ?>
                                        </td>
                                        <td>
                                            <?php echo $MAR; ?>
                                        </td>
                                        <td>
                                            <?php echo $APR; ?>
                                        </td>
                                        <td>
                                            <?php echo $MAY; ?>
                                        </td>
                                        <td>
                                            <?php echo $JUN; ?>
                                        </td>
                                        <td>
                                            <?php echo $JUL; ?>
                                        </td>
                                        <td>
                                            <?php echo $AUG; ?>
                                        </td>
                                        <td>
                                            <?php echo $SEP; ?>
                                        </td>
                                        <td>
                                            <?php echo $OCT; ?>
                                        </td>
                                        <td>
                                            <?php echo $NOV; ?>
                                        </td>
                                        <td>
                                            <?php echo $DEC; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-10 col-md-offset-3">
                                    <div class="row">
                                        <!-- <form action="<?php echo base_url() ?>index.php/leasing_reports/generate_ARreports"  method="post" id="frm_AR_reports" name = "frm_AR_reports"> -->
                                        <form ng-submit="generate_ARreports($event)" method="post" id="frm_AR_reports"
                                            name="frm_AR_reports">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="upload_by_type"
                                                                class="col-md-4 control-label text-right">
                                                                <i class="fa fa-asterisk"></i>Search By
                                                            </label>
                                                            <div class="col-md-8 text-left">
                                                                <select class="form-control" name="upload_by_type"
                                                                    id="upload_by_type" ng-model="upload_by_type"
                                                                    required>
                                                                    <option value="" disabled="" selected=""
                                                                        style="display:none">Please Select One</option>
                                                                    <option>All</option>
                                                                    <option>Tenant ID</option>
                                                                    <option>Document No.</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row"
                                                        ng-show="upload_by_type == 'Tenant ID' || upload_by_type == 'Document No.'">
                                                        <div class="form-group">
                                                            <label for="upload_by_type"
                                                                class="col-md-4 control-label text-right">
                                                                <!-- <i class="fa fa-asterisk"></i>Search By -->
                                                            </label>
                                                            <div class="col-md-8 text-left">
                                                                <input type="text" class="form-control"
                                                                    name="searchInput" ng-model="searchInput"
                                                                    placeholder="Search">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="AR_document_type"
                                                                class="col-md-4 control-label text-right">
                                                                <i class="fa fa-asterisk"></i>Document Type
                                                            </label>
                                                            <div class="col-md-8 text-left">
                                                                <select class="form-control" name="AR_document_type"
                                                                    id="AR_document_type" ng-model="AR_document_type"
                                                                    required>
                                                                    <option value="" disabled="" selected=""
                                                                        style="display:none">Please Select One</option>
                                                                    <option value="inv">Invoice</option>
                                                                    <option value="preop">Pre-Operational Charges
                                                                    </option>
                                                                    <!-- <option value="inv_adj">Invoice Adjustment</option> -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="AR_tenancy_type"
                                                                class="col-md-4 control-label text-right">
                                                                <i class="fa fa-asterisk"></i>Tenancy Type
                                                            </label>
                                                            <div class="col-md-8 text-left">
                                                                <select class="form-control" name="AR_tenancy_type"
                                                                    id="AR_tenancy_type" ng-model="AR_tenancy_type"
                                                                    required>
                                                                    <option value="" disabled="" selected=""
                                                                        style="display:none">Please Select One</option>
                                                                    <option value="ST">Short Term Tenant</option>
                                                                    <option value="LT">Long Term Tenant</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="beginning_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i>For the Month of</label>
                                                            <div class="col-md-8 text-left">
                                                                <input type="month" name="month" autocomplete="off"
                                                                    required ng-model="month" class="form-control" />
                                                                <div class="validation-Error">
                                                                    <span
                                                                        ng-show="frm_AR_reports.month.$dirty && frm_AR_reports.month.$error.required">
                                                                        <p class="error-display">This field is required.
                                                                        </p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12" style="margin-left:220px">
                                                        <button type="submit" ng-disabled="frm_AR_reports.$invalid"
                                                            class="btn btn-primary btn-lg button-vl"><i
                                                                class="fa fa-doc"></i> Generate Report</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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