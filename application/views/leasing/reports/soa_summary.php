<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> SOA Summary</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                                            </ul>
                                            
                                            <div class="tab-content ng-cloak">
                                                <div role="tabpanel" class="tab-pane active" id="payment">
                                                    <div class="row">
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenancy Type</label>
                                                                                <div class="col-md-7">
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

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="store" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Store</label>
                                                                                <div class="col-md-7">
                                                                                    <select
                                                                                        class = "form-control"
                                                                                        name = "store"
                                                                                        ng-model = "store"
                                                                                        required>
                                                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                                        <option>Alta Citta</option>
                                                                                        <option>Alturas Mall</option>
                                                                                        <option>Island City Mall</option>
                                                                                        <option>Plaza Marcela</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="start_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Starting Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                            <datepicker date-format="yyyy-MM-dd" >
                                                                                                <input
                                                                                                    type="text"
                                                                                                    required
                                                                                                    readonly
                                                                                                    placeholder="Choose a date"
                                                                                                    class="form-control"
                                                                                                    id="start_date"
                                                                                                    name = "start_date"
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
                                                                            <label for="end_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>End Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                            <datepicker date-format="yyyy-MM-dd" >
                                                                                                <input
                                                                                                    type="text"
                                                                                                    required
                                                                                                    readonly
                                                                                                    placeholder="Choose a date"
                                                                                                    class="form-control"
                                                                                                    id="end_date"
                                                                                                    name = "end_date"
                                                                                                    autocomplete="off"
                                                                                                    value="<?php echo $current_date; ?>">
                                                                                            </datepicker>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="corporate_name" class="col-md-5 control-label text-right"></label>
                                                                            <div class="col-md-7">
                                                                                <button
                                                                                        class    = "btn btn-primary btn-block  button-b"
                                                                                        type     = "button"
                                                                                        id       = "trade_name_button"
                                                                                        ng-click = "ledger_tenant(dirty.value, tenancy_type)" disabled><i class = "fa fa-search"> Generate</i>
                                                                                </button>

                                                                                <a
                                                                                    class    = "btn btn-primary btn-block  button-b"
                                                                                    type     = "button"
                                                                                    id       = "testing_button"
                                                                                    href     = "<?php echo base_url(); ?>create_soa_summary"><i class = "fa fa-search"> Testing Report</i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
            <p class="copyright">Copyright © <?php echo date('Y') ?> AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->
