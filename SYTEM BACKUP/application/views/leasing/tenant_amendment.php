
<div class="container" ng-controller = "transactionController">
    <div class="well" ng-controller = "tableController">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Amendment of Contract</div>
            <div class="panel-body" ng-init = "viewing('<?php echo base_url(); ?>index.php/leasing_transaction/tenant_details/<?php echo $id; ?>')">
                <div class="row" ng-repeat = "data in viewList">
                    <div class="col-md-10 col-md-offset-1">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist"> 
                            <li role="presentation" class="active"><a href="#amend_terms" aria-controls="amend_terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                            <li role="presentation" class=""><a href="#amend_charges" aria-controls="amend_charges" role="tab" data-toggle="tab" >Charges</a></li>
                            <li role="presentation" class=""><a href="#amend_discounts"  aria-controls="amend_discounts" role="tab" data-toggle="tab" ng-init="get_pickedDiscounts('<?php echo base_url(); ?>index.php/leasing_transaction/get_discounts/' + data.id); get_allDiscounts('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_tenant_type')">Discounts</a></li>
                            <li role="presentation" class=""><a href="#amend_attachments" aria-controls="amend_attachments" role="tab" data-toggle="tab">Attachments</a></li>
                        </ul>
                        <form action="#" method = "post"  enctype="multipart/form-data" name = "frm_amendment" id="frm_amendment">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="amend_terms">
                                    <div class = "col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contract_no" class="col-md-4 control-label text-right">Contract No.</label>
                                                        <div class="col-md-8">
                                                            <input 
                                                                type="text" 
                                                                required
                                                                readonly 
                                                                class="form-control"
                                                                ng-model = "data.contract_no"
                                                                id="contract_no"
                                                                name = "contract_no"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type="text" 
                                                                required
                                                                readonly 
                                                                class="form-control"
                                                                ng-model = "data.tenant_id"
                                                                id="tenant_id"
                                                                name = "tenant_id"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type = "text" style = "display:none" name = "prospect_id" ng-model = "data.prospect_id" />
                                                <input type = "text" style = "display:none" name = "tenancy_type" ng-model = "data.tenancy_type" />
                                                <input type = "text" style = "display:none" name = "id" ng-model = "data.id" />
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="trade_name" class="col-md-4 control-label text-right">Trade Name</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type="text" 
                                                                required
                                                                readonly 
                                                                class="form-control"
                                                                ng-model = "data.trade_name"
                                                                id="trade_name"
                                                                name = "trade_name"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="store_name" class="col-md-4 control-label text-right">Store/Property</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type = "text"
                                                                name = "store_name" 
                                                                class = "form-control"
                                                                readonly
                                                                ng-model = "data.store_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="" class="col-md-4 control-label text-right"></i><i class = "fa fa-asterisk"></i>Floor Location</label>
                                                        <div class="col-md-8">
                                                            <select 
                                                                ng-if = "data.tenancy_type == 'Long Term'"
                                                                name = "floor_name" 
                                                                id = "floor_location" 
                                                                ng-model = "data.floor_name" 
                                                                required class="form-control"
                                                                ng-change = "get_availableSlot_for_amendents('<?php echo base_url(); ?>index.php/leasing_transaction/get_availableSlot_for_amendents/' + data.floor_name + '/' + data.store_name)">
                                                                <?php foreach ($floors as $floor): ?>
                                                                    <option><?php echo $floor['floor_name']; ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            <select 
                                                                ng-if = "data.tenancy_type == 'Short Term'"
                                                                name = "floor_name" 
                                                                id = "floor_location"   
                                                                ng-model = "data.floor_name" 
                                                                required class="form-control"
                                                                ng-change = "get_availableSlot_for_amendents('<?php echo base_url(); ?>index.php/leasing_transaction/get_availableSlot_for_amendents/' + data.floor_name + '/' + data.store_name)">
                                                                <?php foreach ($floors as $floor): ?>
                                                                    <option><?php echo $floor['floor_name']; ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                            <input type = "text" id = "none" style = "display:none" />
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="location_code" class="col-md-4 control-label text-right">Location Code</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type = "text"
                                                                name = "location_code"
                                                                readonly
                                                                id="location_code"
                                                                required 
                                                                ng-model = "data.location_code"
                                                                class = "form-control">
                                                                <input type = "text" id = "slots_id" style = "display:none" name = "slots_id" ng-model = "data.slots_id"  class="form-control" />
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="location_desc" class="col-md-4 control-label text-right">Location Description</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type = "text"
                                                                name = "location_desc"
                                                                id="location_desc"
                                                                required 
                                                                ng-model = "data.location_desc"
                                                                class = "form-control">
                                                                <input type = "text" id = "slots_id" style = "display:none" name = "slots_id" ng-model = "data.slots_id"  class="form-control" />
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="area_classification" class="col-md-4 control-label text-right">Area Classification</label>
                                                        <div class="col-md-8">  
                                                            <select 
                                                                required
                                                                class="form-control"
                                                                id="area_classification"
                                                                name = "area_classification"
                                                                ng-model = "data.area_classification">
                                                                <?php foreach ($classification as $value): ?>
                                                                    <option><?php echo $value['classification']; ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="area_type" class="col-md-4 control-label text-right">Area Type</label>
                                                        <div class="col-md-8"> 
                                                            <select 
                                                                required
                                                                class="form-control"
                                                                id="area_type"
                                                                name = "area_type"
                                                                ng-model = "data.area_type">
                                                                <?php foreach ($area_type as $value): ?>
                                                                    <option><?php echo $value['type']; ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="rent_period" class="col-md-4 control-label text-right">Rent Period</label>
                                                        <div class="col-md-8">  
                                                            <select 
                                                                required
                                                                class="form-control"
                                                                id="rent_period"
                                                                name = "rent_period"
                                                                ng-model = "data.rent_period">
                                                                <?php foreach ($rent_period as $value): ?>
                                                                    <option><?php echo $value['number'] . " " . $value['uom']; ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="opening_date" class="col-md-4 control-label text-right"></i>Opening Date</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon input-date"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                <datepicker  date-format="yyyy-M-dd">
                                                                    <input 
                                                                        type="text" 
                                                                        required 
                                                                        class="form-control" 
                                                                        ng-model="data.opening_date"
                                                                        id="opening_date"
                                                                        name = "opening_date"
                                                                        autocomplete="off"
                                                                        ng-change="data.expiry_date = ''">
                                                                </datepicker>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="expiry_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Expiry Date </label>
                                                        <div class="col-md-8">  
                                                            <div class="input-group">
                                                                <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                <datepicker date-min-limit="{{data.opening_date}}" date-format="yyyy-M-dd">
                                                                    <input 
                                                                        type="text" 
                                                                        required 
                                                                        placeholder="Choose a date" 
                                                                        class="form-control" 
                                                                        ng-model="data.expiry_date"
                                                                        id="expiry_date"
                                                                        name = "expiry_date"
                                                                        autocomplete="off">
                                                                </datepicker>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- divider -->
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="floor_area" class="col-md-4 control-label text-right">Floor Area</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                                <input 
                                                                    type="text" 
                                                                    class="form-control currency"
                                                                    readonly
                                                                    ui-number-mask="2"
                                                                    id="add_floor_area"
                                                                    name = "floor_area"
                                                                    ng-model = "data.floor_area"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" >
                                                    <div class="form-group">
                                                        <label for="increment_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Incrementation</label>
                                                        <div class="col-md-4 pull-left">
                                                            <select
                                                                name = "increment_percentage"
                                                                id="increment_percentage"
                                                                required
                                                                ng-model = "data.increment_percentage"
                                                                class = "form-control">
                                                                <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                <option>None</option>
                                                                <option value="2">2%</option>
                                                                <option value="3">3%</option>
                                                                <option value="4">4%</option>
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
                                                                ng-required = "data.increment_percentage != 'None'"
                                                                ng-model = "data.increment_frequency"
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
                                                        <label for="tin" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>TIN</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type="text" 
                                                                class="form-control"
                                                                id="tin"
                                                                name = "tin"
                                                                ng-model="data.tin"
                                                                name = "tin"
                                                                autocomplete="off" >
                                                                <!-- Error -->
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_contract.frm_amendment.$dirty && frm_amendment.tin.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="rental_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rental Type</label>
                                                        <div class="col-md-8">  
                                                            <select 
                                                                required 
                                                                name = "rental_type" 
                                                                id="rental_type" 
                                                                ng-model = "data.rental_type" 
                                                                class = "form-control">
                                                                <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                <option>Fixed</option>
                                                                <option>Percentage</option>
                                                                <option>Fixed Plus Percentage</option>
                                                                <option>Fixed/Percentage w/c Higher</option>
                                                                <option>Fixed/Percentage/Minimum w/c Higher</option>
                                                                <option>WOF</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" ng-if = "(data.rental_type != 'Fixed' && data.rental_type != 'WOF')">
                                                    <div class="form-group">
                                                        <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Percentage</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong>%</strong></div>
                                                                <input 
                                                                    type="text" 
                                                                        class="form-control currency" 
                                                                        ng-model="data.rent_percentage"
                                                                        required
                                                                        ui-number-mask="2"
                                                                        id="rent_percentage"
                                                                        name = "rent_percentage"
                                                                        autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" ng-show = "(data.rental_type != 'Fixed' && data.rental_type != 'WOF')">
                                                    <div class="form-group">
                                                        <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Percentage Base</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="sales" ng-model="data.sales" ng-required = "data.rental_type != 'Fixed' && data.rental_type != 'WOF'">
                                                                <option>SALES</option>
                                                                <option>GROSS</option>
                                                                <option>NET</option>
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
                                                                id="tenant_type" 
                                                                required
                                                                ng-model = "data.tenant_type" 
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

                                                <div id = "suppDoc_holder" ng-if="data.tenant_type == 'AGC-Subsidiary' || data.tenant_type == 'Private Entities'">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="increment_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Vat</label>
                                                            <div class="col-md-1 pull-left">
                                                                <input type="checkbox" class="form-control" ng-true-value="1" ng-false-value="0" ng-checked="data.is_vat == '1'" name="plus_vat" id = "plus_vat" ng-model="data.is_vat">
                                                            </div>
                                                            <label for="increment_percentage" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>Vat Percentage</label>
                                                       
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>%</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ui-number-mask="2"
                                                                        ng-disabled="data.is_vat != '1'"
                                                                        ng-required="data.is_vat == '1'"
                                                                        ng-model="data.vat_percentage"
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
                                                                    <input type="checkbox" class="form-control"  ng-true-value="1" ng-false-value="0" ng-checked="data.wht == '1'" name="less_wht" id = "less_wht" ng-model = "data.wht">
                                                                </div>
                                                                <label for="increment_percentage" class="col-md-3 control-label text-right"><i class = "fa fa-asterisk"></i>WHT Percentage</label>
                                                           
                                                                <div class="col-md-4">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>%</strong></div>
                                                                        <input
                                                                            type="text"
                                                                            class="form-control currency"
                                                                            ui-number-mask="2"
                                                                            ng-disabled="data.wht != '1'"
                                                                            ng-required="data.wht == '1'"
                                                                            ng-model="data.wht_percentage"
                                                                            id="wht_percentage"
                                                                            name = "wht_percentage"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" ng-if="data.is_vat == '1'">
                                                        <div class="form-group">
                                                            <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Vat Agreement</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="vat_agreement" ng-required = "data.is_vat == '1'" required ng-model="data.vat_agreement">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option value="Inclusive">Inclusive</option>
                                                                    <option value="Exclusive">Exclusive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id = "doc_holder" ng-if="data.is_vat != '1'">
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
                                                           <input type = "checkbox" name = "penalty_exempt" ng-checked="data.penalty_exempt == '1'" id = "penalty_exempt" value="1" class = "form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="basic_rental" class="col-md-4 control-label text-right">Basic Rental</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                <input 
                                                                    type="text" 
                                                                    class="form-control currency"
                                                                    readonly="" 
                                                                    ui-number-mask="2"
                                                                    id="add_basic_rental"
                                                                    name = "basic_rental"
                                                                    ng-model = "data.basic_rental"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="amend_charges" ng-controller = "tableController" ng-init = "loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_monthly_charges/'); get_selectedCharges('<?php echo base_url(); ?>index.php/leasing_transaction/selected_monthly_charges/' + data.tenant_id)">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class = "row">
                                                <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> Please select appropriate tenant charges.
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-money"></i> Charges</div>
                                                    <div class="panel-body">
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
                                                                    <th width="5%"></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'tenant_type'; reverse = !reverse">Description</a></th>
                                                                    <th width="15%"><a href="#" data-ng-click="sortField = 'discount_type'; reverse = !reverse">Charges Code</a></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'discount'; reverse = !reverse">UOM</a></th>
                                                                    <th width="15%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Unit Price</a></th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr class="ng-cloak" ng-repeat= "charge in data">
                                                                    <td>
                                                                        <div ng-if = "is_chargesExist(charge)" ng-repeat = "selected in selectedCharges"><span ng-if = "charge.id == selected.id"><input type = "checkbox" class="form-control" name = "scharges[]" value = "{{charge.id}}_{{selected.unit_price}}_{{selected.uom}}" ng-checked="is_chargesExist(charge)"  /></span></div>
                                                                        <div ng-if = "!is_chargesExist(charge)"><input type = "checkbox" class="form-control" name = "scharges[]" value = "{{charge.id}}_{{charge.unit_price}}_{{charge.uom}}"  /></div>
                                                                    </td>
                                                                    <td title = "'Description'" sortable = "'description'">{{ charge.description }}</td>
                                                                    <td title = "'Charge Code'" sortable = "'charges_code'">{{ charge.charges_code }}</td>
                                                                    <td title = "'UOM'" sortable = "'uom'">
                                                                        <div ng-if="is_chargesExist(charge)" ng-repeat = "selected in selectedCharges">
                                                                            <span ng-if = "charge.id == selected.id">
                                                                                <select class="form-control" ng-model = "selected.uom" name="uom" id="uom">
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
                                                                                    <option>Inputted</option>
                                                                                </select>
                                                                            </span>
                                                                        </div>
                                                                        <div ng-if="!is_chargesExist(charge)">
                                                                            <select class="form-control" ng-model = "charge.uom" name="uom" id="uom">
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
                                                                                <option>Inputted</option>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td title = "'Unit Price'" sortable = "'unit_price'" align="right">
                                                                        <div ng-if="is_chargesExist(charge)" ng-repeat = "selected in selectedCharges"><span ng-if = "charge.id == selected.id"><input class = "form-control currency" ui-number-mask="2" type = "text" ng-model = "selected.unit_price"></span></div>
                                                                        <div ng-if="!is_chargesExist(charge)"><input class = "form-control currency" ui-number-mask="2" type = "text" ng-model = "charge.unit_price"></div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="ng-cloak" ng-show="!data.length">
                                                                    <td colspan="5"><center>No Data Available.</center></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end of amendment discount panel -->

                                    <div role="tabpanel" class="tab-pane" id="amend_discounts" >
                                        <div class="col-md-10 col-md-offset-1">
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

                                                        <table class="table table-bordered">
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
                                                                <tr class="ng-cloak" ng-repeat= "disc in allDiscounts">
                                                                    <td>
                                                                        <input type = "checkbox" class="form-control" name = "sdiscount[]" value = "{{disc.id}}"  ng-checked="is_discountExist(disc)" />
                                                                    </td>
                                                                    <td>{{ disc.tenant_type }}</td>
                                                                    <td>{{ disc.discount_type }}</td>
                                                                    <td>
                                                                        <div ng-if = "disc.discount_type == 'Fixed Amount'">
                                                                            {{ disc.discount | currency : '&#8369;' }}
                                                                        </div>
                                                                        <div ng-if = "disc.discount_type != 'Fixed Amount'">
                                                                            {{ disc.discount | currency : '% ' }}
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ disc.description }}</td>
                                                                </tr>
                                                                <tr class="ng-cloak" ng-show="!allDiscounts.length">
                                                                    <td colspan="5"><center>No Data Available.</center></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end of amendment discount panel -->
                                    <div role="tabpanel" class="tab-pane" id="amend_attachments" >
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class = "row">
                                                <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> Upload the amended contract
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-image"></i> Contract Documents</div>
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
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type = "button" onclick="amend_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/amend_tenant/');"  ng-disabled = "frm_amendment.$invalid" class="btn btn-medium btn-primary"><i class = "fa fa-save"></i> Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                            <td><input type = "checkbox" name = "checkbox[]" ng-change = "chosen_slotNo_for_amendment(slot.id)" ng-model="is_SlotSelected[slot.id]"></td>
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


    </div> <!-- END OF WELL DIV  -->


    



     <!-- Manager's Key Modal -->
    <div class="modal fade" id = "managerkey_modal" >
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
                        <button type="button"  onclick="amend_tenant('<?php echo base_url(); ?>index.php/leasing_transaction/amend_tenant/');" class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-key"></i> Submit</button>
                        <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Manager's Key Modal -->







</div> <!-- /.container -->
</body>