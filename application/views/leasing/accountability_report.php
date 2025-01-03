<div class="container" id="transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Accountability Report</div>
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
                                        <form action=""
                                            onSubmit="generate_accountabilityReport('<?php echo base_url(); ?>index.php/Leasing_reports/generate_accountabilityReport');return false"
                                            method="post" id="frm_accountability" name="frm_accountability">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="billing_period"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i>User Name</label>
                                                            <div class="col-md-8">
                                                                <select required class="form-control" name="username">
                                                                    <option value="" disabled="" selected=""
                                                                        style="display:none">Please Select One</option>
                                                                    <?php foreach ($users as $user): ?>
                                                                        <option value="<?php echo $user['id'] ?>"><?php echo $user['username']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="beginning_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i>Beginning Date</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i
                                                                                class="fa fa-calendar"></i></strong>
                                                                    </div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input type="text" required readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="beginning_date"
                                                                            id="beginning_date" name="beginning_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span
                                                                            ng-show="frm_accountability.beginning_date.$dirty && frm_accountability.beginning_date.$error.required">
                                                                            <p class="error-display">This field is
                                                                                required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="end_date"
                                                                class="col-md-4 control-label text-right"><i
                                                                    class="fa fa-asterisk"></i>End Date</label>
                                                            <div class="col-md-8 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i
                                                                                class="fa fa-calendar"></i></strong>
                                                                    </div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input type="text" required readonly
                                                                            placeholder="Choose a date"
                                                                            class="form-control" ng-model="end_date"
                                                                            id="end_date" name="end_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span
                                                                            ng-show="frm_accountability.end_date.$dirty && frm_accountability.end_date.$error.required">
                                                                            <p class="error-display">This field is
                                                                                required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row pull-right" style="padding-right: 13%">
                                                    <div class="col-md-12">
                                                        <button type="submit" ng-disabled="frm_accountability.$invalid"
                                                            class="btn btn-primary btn-lg button-b"><i
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