
    <div class="container" ng-controller = "transactionController">
        <div class="well" ng-controller="tableController">
            <div class="panel panel-default">
                <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Contract Signing</div>
                <div class = "panel-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                           <span class = "note">Note: <i class="fa fa-asterisk"></i> Required Fields</span>
                        </div>
                    </div>
                    <?php foreach ($details as $data): ?>
                    <form action="#"  method="post"   enctype="multipart/form-data" id="frm_contract" name = "frm_contract">
                        <div class="col-md-11">
                            <div class="row">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Primary Details</a></li>
                                    <li role="presentation" class=""><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                                    <li role="presentation" class=""><a href="#charges" aria-controls="charges" role="tab" data-toggle="tab">Charges</a></li>
                                    <li role="presentation" class=""><a href="#discounts" aria-controls="discounts" role="tab" data-toggle="tab">Discounts</a></li>
                                    <li role="presentation"><a href="#Attachment" aria-controls="Attachment" role="tab" data-toggle="tab">Attachment</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane" id="terms">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contract_code" class="col-md-4 control-label text-right">Contract No.</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    value="<?php echo $contract_no; ?>"
                                                                    class="form-control"
                                                                    id="contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="lease_period" class="col-md-4 control-label text-right">Tenancy Type</label>
                                                            <div class="col-md-8">
                                                                <input type = "text" id = "tenancy_type" name = "tenancy_type"  readonly class="form-control" value="<?php echo $data['flag']; ?>" />
                                                                <input type = "text" id = "tenant_incrementID" name = "tenant_incrementID"  style="display:none" value="<?php echo $tenant_incrementID; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tin" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>TIN</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    id="tin"
                                                                    name = "tin"
                                                                    value = "<?php echo $data['tin']; ?>"
                                                                    autocomplete="off">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="form-group">
                                                            <label for="rental_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rental Type</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "rental_type"
                                                                    id="rental_type"
                                                                    required
                                                                    ng-model = "rental_type"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>Fixed</option>
                                                                    <option>Percentage</option>
                                                                    <option>Fixed Plus Percentage</option>
                                                                    <option>Fixed/Percentage w/c Higher</option>
                                                                    <option>WOF</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" ng-show = "(rental_type != 'Fixed' && rental_type != 'WOF') && rental_type">
                                                        <div class="form-group">
                                                            <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Percentage</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>%</strong></div>
                                                                    <input
                                                                        type = "t ext"
                                                                        ui-number-mask="2"
                                                                        name = "rent_percentage"
                                                                        ng-model = "rent_percentage"
                                                                        class="form-control currency"
                                                                        ng-required = "rental_type != 'Fixed' && rental_type != 'WOF'"
                                                                        required >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" ng-show = "(rental_type != 'Fixed' && rental_type != 'WOF') && rental_type">
                                                        <div class="form-group">
                                                            <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Percentage Base</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="sales" ng-required = "rental_type != 'Fixed' && rental_type != 'WOF'">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>SALES</option>
                                                                    <option>GROSS</option>
                                                                    <option>NET</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="increment_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Incrementation</label>
                                                            <div class="col-md-4 pull-left">
                                                                <select
                                                                    name = "increment_percentage"
                                                                    id="increment_percentage"
                                                                    required
                                                                    ng-model = "increment_percentage"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>None</option>
                                                                    <option value="2">2%</option>
                                                                    <option value="3">3%</option>
                                                                    <option value="5">5%</option>
                                                                    <option value="6">6%</option>
                                                                    <option value="7">7%</option>
                                                                    <option value="8">8%</option>
                                                                    <option value="10">10%</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 pull-right">
                                                                <select
                                                                    name = "increment_frequency"
                                                                    id="increment_frequency"
                                                                    ng-required = "increment_percentage != 'None'"
                                                                    ng-model = "increment_frequency"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>Annual</option>
                                                                    <option>Biennial</option>
                                                                    <option>Triennial</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="rent_period" class="col-md-4 control-label text-right">Rent Period</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "rent_period"
                                                                    id="rent_period"
                                                                    required
                                                                    ng-model = "rent_period"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <?php foreach ($rent_period as $value): ?>
                                                                        <option><?php echo $value['number'] . ' ' . $value['uom']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="opening_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Opening Date </label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                        <datepicker  date-format="yyyy-M-dd">
                                                                            <input
                                                                                type="text"
                                                                                required
                                                                                placeholder="Choose a date"
                                                                                class="form-control"
                                                                                ng-model="opening_date"
                                                                                id="opening_date"
                                                                                name = "opening_date"
                                                                                autocomplete="off"
                                                                                ng-change = "generate_expiryDate('rent_period', opening_date)">
                                                                        </datepicker>
                                                                        <!-- FOR ERRORS -->
                                                                        <div class="validation-Error">
                                                                            <span ng-show="frm_contract.opening_date.$dirty && frm_contract.opening_date.$error.required">
                                                                                <p class="error-display">This field is required.</p>
                                                                            </span>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="expiry_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Expiry Date </label>
                                                            <div class="col-md-8">
                                                                <div class = "input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-min-limit="{{opening_date}}" date-format="yyyy-M-dd">
                                                                        <input
                                                                            type = "text"
                                                                            class = "form-control"
                                                                            ng-model = "expiry_date"
                                                                            required
                                                                            name = "expiry_date"
                                                                            id = "expiry_date"
                                                                            placeholder = "Choose a date" />
                                                                    </datepicker>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- DIVIDER -->
                                                <div class="col-md-6">

                                                    <div class="row" >
                                                        <div class="form-group">
                                                            <label for="floor_location" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "floor_location"
                                                                    id="floor_location"
                                                                    required
                                                                    ng-model = "floor_location"
                                                                    class = "form-control"
                                                                    ng-change = "get_availableSlot('<?php echo base_url(); ?>index.php/leasing_transaction/get_locationSlots/')">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <?php foreach ($floor_location as $floor): ?>
                                                                        <option value="<?php echo $floor['id']; ?>"><?php echo $floor['floor_name']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="location_code" class="col-md-4 control-label text-right">Location Code</label>
                                                            <div class="col-md-8">
                                                                <input type = "text" readonly id = "location_code" value="<?php echo $location_code ?>" name = "location_code"  class="form-control" />
                                                                <input type = "text" style="display:none" id = "slots_id" name = "slots_id" ng-model = "selectedSlots"  class="form-control" />
                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_contract.location_code.$dirty && frm_contract.location_code.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="location_desc" class="col-md-4 control-label text-right">Location Description</label>
                                                            <div class="col-md-8">
                                                                <input type = "text" required id = "location_desc" ng-model = "location_desc" name = "location_desc"  class="form-control" />
                                                                <!-- FOR ERRORS -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_contract.location_desc.$dirty && frm_contract.location_desc.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="floor_area" class="col-md-4 control-label text-right">Floor Area</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        readonly
                                                                        ui-number-mask="2"
                                                                        ng-model="total_floorArea"
                                                                        id="floor_area"
                                                                        name = "floor_area"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="form-group">
                                                            <label for="rental_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Classification</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "area_classification"
                                                                    id="area_classification"
                                                                    required
                                                                    ng-model = "area_classification"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <?php foreach ($classification as $value): ?>
                                                                        <option><?php echo $value['classification']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="form-group">
                                                            <label for="rental_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Type</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "area_type"
                                                                    id="area_type"
                                                                    required
                                                                    ng-model = "area_type"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <?php foreach ($area_type as $value): ?>
                                                                        <option><?php echo $value['type']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" >
                                                        <div class="form-group">
                                                            <label for="tenant_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Tenant Type</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "tenant_type"
                                                                    id="tenant_types"
                                                                    required
                                                                    ng-model = "tenant_type"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>AGC-Subsidiary</option>
                                                                    <option>Cooperative</option>
                                                                    <option>Government Agencies(w/ Basic)</option>
                                                                    <option>Government Agencies(w/o Basic)</option>
                                                                    <option>Private Entities</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id = "suppDoc_holder" ng-if="tenant_type == 'AGC-Subsidiary' || tenant_type == 'Private Entities'" ng-init="plus_vat=true; less_wht=true">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="increment_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Vat</label>
                                                                <div class="col-md-1 pull-left">
                                                                    <input type="checkbox" class="form-control" name="plus_vat" id = "plus_vat" ng-model = "plus_vat">
                                                                </div>
                                                                <label for="increment_percentage" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>Vat Percentage</label>
                                                           
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>%</strong></div>
                                                                        <input
                                                                            type="text"
                                                                            class="form-control currency"
                                                                            ui-number-mask="2"
                                                                            ng-disabled="!plus_vat"
                                                                            ng-required="plus_vat"
                                                                            ng-model="vat_percentage"
                                                                            id="vat_percentage"
                                                                            name = "vat_percentage"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="increment_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Less WHT</label>
                                                                    <div class="col-md-1 pull-left">
                                                                        <input type="checkbox" class="form-control" name="less_wht" id = "less_wht" ng-model = "less_wht">
                                                                    </div>
                                                                    <label for="increment_percentage" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>WHT Percentage</label>
                                                               
                                                                    <div class="col-md-4">
                                                                        <div class="input-group">
                                                                            <div class="input-group-addon"><strong>%</strong></div>
                                                                            <input
                                                                                type="text"
                                                                                class="form-control currency"
                                                                                ui-number-mask="2"
                                                                                ng-disabled="!less_wht"
                                                                                ng-required="less_wht"
                                                                                ng-model="wht_percentage"
                                                                                id="wht_percentage"
                                                                                name = "wht_percentage"
                                                                                autocomplete="off" >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" ng-if="plus_vat">
                                                            <div class="form-group">
                                                                <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Vat Agreement</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" name="vat_agreement" ng-required = "plus_vat" required ng-model="vat_agreement">
                                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                        <option value="Inclusive">Inclusive</option>
                                                                        <option value="Exclusive">Exclusive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id = "doc_holder" ng-if="!plus_vat">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>
                                                                    <div class="col-md-8">
                                                                       <input type = "file" name = "bir_doc" required id = "bir_doc" class = "form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="montly_wvat" class="col-md-4 control-label text-right">Penalty Exempt?</label>
                                                            <div class="col-md-1">
                                                               <input type = "checkbox" name = "penalty_exempt" id = "penalty_exempt" value="1" class = "form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="actual_balance" class="col-md-4 control-label text-right">Suggst. Monthly/Rental</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        readonly
                                                                        ui-number-mask="2"
                                                                        ng-model="total_rent"
                                                                        id="basic_rental"
                                                                        name = "basic_rental"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div role="tabpanel" class="tab-pane active" id="details">
                                        <div class = "col-md-12">
                                            <div class="row">
                                                <div class = "col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['tenant_id']; ?>"
                                                                    id="tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete="off" >
                                                                    <input type = "text" style="display:none" name = "prospect_id" value="<?php echo $data['id']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-4 control-label text-right">Trade Name</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['trade_name']; ?>"
                                                                    id="trade_name"
                                                                    name = "trade_name"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="leasee_type" class="col-md-4 control-label text-right">Leasee Type</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['leasee_type']; ?>"
                                                                    id="leasee_type"
                                                                    name = "leasee_type"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="first_category" class="col-md-4 control-label text-right">Firt Category</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['first_category']; ?>"
                                                                    id="first_category"
                                                                    name = "first_category"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="second_category" class="col-md-4 control-label text-right">Second Category</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['second_category']; ?>"
                                                                    id="second_category"
                                                                    name = "second_category"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- End of col-md-6 -->
                                                <!-- DIVIDER -->
                                                <div class = "col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="third_category" class="col-md-4 control-label text-right">Third Category</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['third_category']; ?>"
                                                                    id="third_category"
                                                                    name = "third_category"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contact_person1" class="col-md-4 control-label text-right">Contact Person</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['contact_person']; ?>"
                                                                    id="contact_person1"
                                                                    name = "contact_person1"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    value="<?php echo $data['contact_number']; ?>"
                                                                    id="contact_number1"
                                                                    name = "contact_number1"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="store_name" class="col-md-4 control-label text-right">Store Location</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    class="form-control"
                                                                    name = "store_name"
                                                                    value="<?php echo $data['store_name']; ?>"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- End of the 2nd col-md-6 -->
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="discounts">
                                        <div class="col-md-11 col-md-offset-1">
                                            <div class = "row">
                                                <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> To choose discount(s) just check the appropriate discount types.
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-minus-circle"></i> Discounts</div>
                                                    <div class="panel-body">
                                                        <table class="table table-bordered"  ng-init="get_discounts('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_tenant_type');loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthly_charges/')">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%"></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'tenant_type'; reverse = !reverse">Discount Type</a></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'discount_type'; reverse = !reverse">Percent/Amount</a></th>
                                                                    <th width="15%"><a href="#" data-ng-click="sortField = 'discount'; reverse = !reverse">Discount</a></th>
                                                                    <th width="30%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Description</a></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="ng-cloak" ng-show="discountList.length!=0" ng-repeat= "type in discountList">
                                                                    <td>
                                                                        <div class="btn-group" data-toggle="buttons">
                                                                            <label class="btn btn-default">
                                                                                <input type = "checkbox" name = "sdiscount[]" value = "{{type.id}}" autocomplete = "off" />
                                                                                <span class="glyphicon glyphicon-ok"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ type.tenant_type }}</td>
                                                                    <td>{{ type.discount_type }}</td>
                                                                    <td>
                                                                        <div ng-if = "type.discount_type == 'Fixed Amount'">
                                                                            {{ type.discount | currency : '&#8369;' }}
                                                                        </div>
                                                                        <div ng-if = "type.discount_type != 'Fixed Amount'">
                                                                            {{ type.discount | currency : '% ' }}
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ type.description }}</td>
                                                                </tr>
                                                                <tr class="ng-cloak" ng-show="!discountList.length">
                                                                    <td colspan="5"><center>No Data Available.</center></td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="charges">
                                        <div class="col-md-11 col-md-offset-1">
                                            <div class="row">
                                                 <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> Please select appropriate tenant charges.
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-money"></i> Charges</div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-3 pull-right">
                                                                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table class="table table-bordered" ng-table = "tableParams">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="6%"></th>
                                                                        <th>Description</th>
                                                                        <th>Charges Code</th>
                                                                        <th>UOM</th>
                                                                        <th>Unit Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr ng-repeat = "charge in data">
                                                                        <td>
                                                                            <div class="btn-group" data-toggle="buttons">
                                                                                <label class="btn btn-default">
                                                                                    <input type = "checkbox" name = "monthly_charges[]" value = "{{charge.id}}_{{charge.unit_price}}_{{charge.uom}}" autocomplete = "off" />
                                                                                    <span class="glyphicon glyphicon-ok"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td title = "'Description'" sortable = "'description'">{{charge.description}}</td>
                                                                        <td title = "'Charge Code'" sortable = "'charges_code'">{{charge.charges_code}}</td>
                                                                        <td title = "'UOM'" sortable = "'uom'">
                                                                            <select class="form-control" name="uom" id="uom" ng-model = "charge.uom">
                                                                                <option>Per Kilowatt Hour</option>
                                                                                <option>Per Kilogram</option>
                                                                                <option>Per Cubic Meter</option>
                                                                                <option>Per Square Meter</option>
                                                                                <option>Per Grease Trap</option>
                                                                                <option>Per Feet</option>
                                                                                <option>Per Ton</option>
                                                                                <option>Per Hour</option>
                                                                                <option>Per Piece</option>
                                                                                <option>Per Contract</option>
                                                                                <option>Per Linear</option>
                                                                                <option>Per Page</option>
                                                                                <option>Fixed Amount</option>
                                                                                <option>Per Meters</option>
                                                                                <option>Per Unit</option>
                                                                                <option>Inputted</option>
                                                                            </select>
                                                                        </td>
                                                                        <td align="right" title = "'Unit Price'" sortable = "'unit_price'"> <input class = "form-control currency" ui-number-mask="2" type = "text" ng-model = "charge.unit_price"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="Attachment">
                                        <div class="col-md-11 col-md-offset-1">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><i class="fa fa-paperclip"></i> Contract Document</div>
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <input id="contract_docs" name = "contract_docs[]" required type="file" multiple="multiple">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <?php if ($this->session->userdata('user_group') == '0' && $this->session->userdata('user_group') == null): ?>
                                    <button type = "button" onclick="add_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/renew/');" ng-disabled = "frm_contract.$invalid" class="btn btn-medium btn-primary"><i class = "fa fa-save"></i> Submit</button>
                                <?php else: ?>
                                    <button type = "button" data-toggle="modal" data-target="#managerkey_modal" ng-disabled = "frm_contract.$invalid" class="btn btn-medium btn-primary"><i class = "fa fa-save"></i> Submit</button>
                                <?php endif ?>
                                <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Lforcontract" class = "btn btn-medium btn-default"><i class = "fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                        </div>
                    </form>
                    <?php endforeach ?>
                </div>
            </div>
        </div>






    <!-- Manager's Key Modal -->
    <div class="modal fade" id = "managerkey_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
                </div>
                <form action="#"  method="post" id="frm_managerKey" name = "frm_managerKey">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><i class = "fa fa-user"></i></div>
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="username"
                                                        id="username"
                                                        name = "username"
                                                        autocomplete="off" >
                                            </div>
                                             <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_managerKey.username.$dirty && frm_managerKey.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-addon squared"><i class = "fa fa-key"></i></div>
                                                    <input
                                                        type="password"
                                                        required
                                                        class="form-control"
                                                        ng-model="password"
                                                        id="password"
                                                        name = "password"
                                                        autocomplete="off" >
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_managerKey.password.$dirty && frm_managerKey.password.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button type="button" onclick="add_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/renew/');" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-key"></i> Submit</button>
                        <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Manager's Key Modal -->

    <div class="modal fade" id = "slot_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Select Location Slot</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="height:500px; overflow:auto;">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10%"><a href="#" data-ng-click="sortField = 'id'; reverse = !reverse"></a></th>
                                        <th width="30%"><a href="#" data-ng-click="sortField = 'slot_no'; reverse = !reverse">Slot No.</a></th>
                                        <th width="30%"><a href="#" data-ng-click="sortField = 'floor_area'; reverse = !reverse">Floor Area</a></th>
                                        <th width="40%"><a href="#" data-ng-click="sortField = 'rental_rate'; reverse = !reverse">Rental Rate</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-show="location_slots.length!=0" ng-repeat= "slot in location_slots | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                        <td><input type = "checkbox" name = "checkbox[]" ng-change = "chosen_slotNo(slot.id)" ng-model="is_SlotSelected[slot.id]"></td>
                                        <td>{{ slot.slot_no }}</td>
                                        <td align="right">{{ slot.floor_area | currency : '' }}</td>
                                        <td align="right">{{ slot.rental_rate | currency : '&#8369;' }}</td>
                                    </tr>
                                    <tr class="ng-cloak" ng-show="location_slots.length==0 || (location_slots | filter:query).length == 0">
                                        <td colspan="5"><center>No Data Available.</center></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            Total Floor Area: {{ total_floorArea | currency : '' }}
                        </div>
                        <div class="col-md-4">
                            Total Rental: {{ total_rent | currency : '&#8369;' }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    </div>
</body>
