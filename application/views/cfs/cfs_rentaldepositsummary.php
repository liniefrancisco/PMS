<style type="text/css">
    .center {
        margin: auto;
        width: 50%;
        padding: 10px;
    }
</style>
<div class="container center" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading" style="background-color:#125821; color:whitesmoke"><i class="fa fa-list"></i> Rental Deposit Summary</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="rental_deposit_summary">
                                <form action="" method="post" enctype="multipart/form-data" name="rental_deposit_summary_form" ng-submit="submitsummary($event)">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12"> <!-- COL-MD-6 DIVIDER -->
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="beginning_date" class="col-md-3 control-label text-right"><i class="fa fa-asterisk"></i>Sales Date</label>
                                                            <div class="col-md-7 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type         = "text"
                                                                            placeholder  = "Choose a date"
                                                                            class        = "form-control"
                                                                            ng-model     = "beginning_date"
                                                                            id           = "beginning_date"
                                                                            name         = "beginning_date"
                                                                            autocomplete = "off"
                                                                            required
                                                                            readonly>
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="rental_deposit_summary_form.beginning_date.$dirty && rental_deposit_summary_form.beginning_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="end_date" class="col-md-3 control-label text-right"><i class="fa fa-asterisk"></i>End Date</label>
                                                            <div class="col-md-7 text-left">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-format="yyyy-M-dd">
                                                                        <input
                                                                            type         = "text"
                                                                            placeholder  = "Choose a date"
                                                                            class        = "form-control"
                                                                            ng-model     = "end_date"
                                                                            id           = "end_date"
                                                                            name         = "end_date"
                                                                            autocomplete = "off"
                                                                            required
                                                                            readonly>
                                                                    </datepicker>
                                                                    <!-- FOR ERRORS -->
                                                                    <div class="validation-Error">
                                                                        <span ng-show="rental_deposit_summary_form.end_date.$dirty && rental_deposit_summary_form.end_date.$error.required">
                                                                            <p class="error-display">This field is required.</p>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="end_date" class="col-md-3 control-label text-right"><i class="fa fa-asterisk"></i>Tender Type</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <select class="form-control" name="tender_type" id="tender_type" ng-model="tender_type" ng-change="showdenomitable()" required>
                                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>    
                                                                        <option>Without Cash</option>
                                                                        <option>With Cash</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="row">
                                                        <div class="form-group">
                                                            <label for="end_date" class="col-md-3 control-label text-right"></label>
                                                            <div class="col-md-7 text-left">
                                                                <button type = "submit" ng-disabled = "frm_paymentList.$invalid" class = "btn btn-primary btn-lg btn-block"><i class = "fa fa-doc"></i> Generate Report</button>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="row" ng-show="denomitable" ng-cloak>
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">NO. OF PIECES</th>
                                                                <th class="text-center">DENOMINATION</th>
                                                                <th class="text-center">AMOUNT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="d in denomination">
                                                                <td>
                                                                    <input
                                                                        type         = "text"
                                                                        name         = "pieces_txtbox"
                                                                        class        = "form-control text-right currency"
                                                                        ng-model     = "d.p_txtbox"
                                                                        ng-keyup     = "computetotal()"
                                                                        placeholder  = "0"
                                                                        autocomplete = "off"
                                                                        ui-number-mask = "0"
                                                                        ng-readonly    = "existing">
                                                                    <div class="validation-Error">
                                                                        <span><p class="error-display" ng-model="p.errorDisplayP1"></p></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input
                                                                        type         = "text"
                                                                        name         = "denomination_txtbox"
                                                                        class        = "form-control text-right currency"
                                                                        ng-model     = "d.denomi"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input
                                                                        type         = "text"
                                                                        name         = "amount_txtbox"
                                                                        class        = "form-control text-right currency"
                                                                        placeholder  = "0"
                                                                        ng-model     = "d.amount_txtbox"
                                                                        readonly>

                                                                    <div class="validation-Error">
                                                                        <span><p class="error-display" ng-model="p.errorDisplayP1"></p></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td><input type="text" name="totalDenomi" ng-value="totalAmount()" class = "form-control text-right currency"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button 
                                                        role        = "submit" 
                                                        ng-disabled = "rental_deposit_summary_form.$invalid" 
                                                        class       = "btn btn-large btn-primary col-md-3 col-md-offset-9  button-vl"
                                                        type        = "submit">
                                                        <i class="fa fa-save"></i> Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>
