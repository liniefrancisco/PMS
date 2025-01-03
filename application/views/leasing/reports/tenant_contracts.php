<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Tenants Contract
                                    Report</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active" id="active_w">
                                                    <div class="row">
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <form
                                                                action="<?= base_url() ?>index.php/leasing/generateTextFilesForCas"
                                                                target="_blank" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="contract_status"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>To
                                                                                    Generate</label>
                                                                                <div class="col-md-7">
                                                                                    <select class="form-control"
                                                                                        name="tenantID"
                                                                                        ng-model="tenantID" required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <?php foreach ($ledger as $l): ?>
                                                                                            <option
                                                                                                value="<?php echo $l['tenant_id']; ?>">
                                                                                                <?php echo $l['tenant_id'] . '-' . $l['gl_account'] . '-' . $l['document_type'] . '--' . $l['posting_date'] ?>
                                                                                            </option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tenancy_type"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Export
                                                                                    Type</label>
                                                                                <div class="col-md-7">
                                                                                    <select class="form-control"
                                                                                        name="export_type"
                                                                                        ng-model="export_type" required>
                                                                                        <option value="" disabled=""
                                                                                            selected=""
                                                                                            style="display:none">Please
                                                                                            Select One</option>
                                                                                        <option>Rent</option>
                                                                                        <option>Others</option>
                                                                                        <option>Payment</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="start_date"
                                                                                    class="col-md-5 control-label text-right"><i
                                                                                        class="fa fa-asterisk"></i>Starting
                                                                                    Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div
                                                                                            class="input-group-addon input-date">
                                                                                            <strong><i
                                                                                                    class="fa fa-calendar"></i></strong>
                                                                                        </div>
                                                                                        <datepicker
                                                                                            date-format="yyyy-MM-dd">
                                                                                            <input type="text" required
                                                                                                readonly
                                                                                                placeholder="Choose a date"
                                                                                                class="form-control"
                                                                                                id="start_date"
                                                                                                name="start_date"
                                                                                                autocomplete="off"
                                                                                                value="<?php echo $current_date; ?>"
                                                                                                onchange="startingDate()">
                                                                                        </datepicker>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="corporate_name"
                                                                                    class="col-md-5 control-label text-right"></label>
                                                                                <div class="col-md-7">
                                                                                    <button
                                                                                        class="btn btn-primary btn-block button-b"
                                                                                        type="submit"
                                                                                        id="trade_name_button"
                                                                                        disabled><i
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
                                                </div>
                                                <div role="tabpanel" class="tab-pane active" id="active_wo"></div>
                                                <div role="tabpanel" class="tab-pane active" id="exp_w"></div>
                                                <div role="tabpanel" class="tab-pane active" id="exp_wo"></div>
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