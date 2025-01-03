<?php //var_dump($this->session->userdata) ?>
<div class="container" id="transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> For System vs Navision Recon
            </div>
            <div class="panel-body" style="height: 30em">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab"
                                    data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-10 col-md-offset-3" style="padding-top: 4%;">
                                    <div class="row">
                                        <form action="<?= base_url() ?>leasing/generate_recon_sys_vs_nav_report"
                                            target="_blank" method="post">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <?php if ($this->session->userdata('user_group') == 0): ?>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="from_date"
                                                                    class="col-md-4 control-label text-right"><i
                                                                        class="fa fa-asterisk"></i>Store</label>
                                                                <div class="col-md-8 text-left">
                                                                    <select name="store" class="form-control" required="">
                                                                        <?php foreach ($stores as $key => $store):
                                                                            $store = (object) $store; ?>
                                                                            <option value="<?= $store->store_code ?>">
                                                                                <?= $store->store_name ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="to_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i> GL Accounts</label>
                                                            <div class="col-md-8 text-left">
                                                                <select name="gl_ids[]" multiple class="form-control">
                                                                    <?php foreach ($gl_accounts as $key => $account): ?>
                                                                        <option value="<?= $account->id ?>">
                                                                            <?= $account->gl_account ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="to_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i> Date From</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date">
                                                                        <strong><i class="fa fa-calendar"></i></strong>
                                                                    </div>
                                                                    <input type="date" required
                                                                        placeholder="Choose a date" class="form-control"
                                                                        name="date_from">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="to_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i> As Of Date</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date">
                                                                        <strong><i class="fa fa-calendar"></i></strong>
                                                                    </div>
                                                                    <input type="date" required
                                                                        placeholder="Choose a date" class="form-control"
                                                                        name="date_to">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pull-right" style="padding-right: 13%">
                                                    <div class="col-md-12">
                                                        <button type="submit" ng-disabled="frm_paymentReport.$invalid"
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