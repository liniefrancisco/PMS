<div class="container" id="transactionController" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Statement of Account(Per Batch)
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
                                <div class="col-md-10 col-md-offset-1 ">
                                    <div class="row">
                                        <form action=""
                                            onsubmit="save_soa('<?php echo base_url(); ?>index.php/leasing_transaction/generate_batchSOA/'); return false;"
                                            method="post" id="frm_soa" name="frm_soa">
                                            <div class="row">
                                                <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="billing_period"
                                                                class="col-md-5 control-label text-right">Billing
                                                                Period</label>
                                                            <div class="col-md-7">
                                                                <select required class="form-control"
                                                                    name="billing_period">
                                                                    <option value="" disabled="" selected=""
                                                                        style="display:none">Please Select One</option>
                                                                    <option>Upon Signing of Notice</option>
                                                                    <?php for ($i = 0; $i < count($billing_period); $i++): ?>
                                                                        <option>
                                                                            <?php echo $billing_period[$i] . " " . $current_year; ?>
                                                                        </option>
                                                                    <?php endfor ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- END OF THE 1ST COL-MD-6 WRAPPER -->
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="collection_date"
                                                                    class="col-md-3 control-label text-right">Collection
                                                                    Date</label>
                                                                <div class="col-md-7 text-left">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon input-date">
                                                                            <strong><i
                                                                                    class="fa fa-calendar"></i></strong>
                                                                        </div>
                                                                        <datepicker date-format="yyyy-M-dd">
                                                                            <input type="text" required readonly
                                                                                placeholder="Choose a date"
                                                                                class="form-control"
                                                                                ng-model="collection_date"
                                                                                id="collection_date"
                                                                                name="collection_date"
                                                                                autocomplete="off">
                                                                        </datepicker>
                                                                        <!-- FOR ERRORS -->
                                                                        <div class="validation-Error">
                                                                            <span
                                                                                ng-show="frm_soa.collection_date.$dirty && frm_soa.collection_date.$error.required">
                                                                                <p class="error-display">This field is
                                                                                    required.</p>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row"> <!-- ROW FOR BUTTONS -->
                                                <div class="col-md-12 text-center">
                                                    <button type="submit" ng-disabled="frm_soa.$invalid"
                                                        class="btn btn-primary btn-lg button-vl"><i
                                                            class="fa fa-doc"></i> Generate SOA</button>
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