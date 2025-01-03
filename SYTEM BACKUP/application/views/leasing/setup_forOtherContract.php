
<div class="container" ng-controller = "appController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Setup Profile for another Store </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach ($prospectData as $data): ?>
                        <form action="#" onsubmit="save_setForOtherStore('<?php echo base_url(); ?>index.php/leasing_transaction/save_setForOtherContract/<?php echo $data['id']; ?>'); return false" method="post" enctype="multipart/form-data" id="frm_prospect" name = "frm_prospect">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="trade_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Trade Name</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        class="form-control" 
                                                        value="<?php echo $data['trade_name']; ?>" 
                                                        readonly 
                                                        id="trade_name"
                                                        name = "trade_name"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="corp_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Corporate Name</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['corporate_name']; ?>" 
                                                        id="corp_name"
                                                        name = "corp_name"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="lessee_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Leesee Type</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['leasee_type']; ?>" 
                                                        id="lessee_type"
                                                        name = "lessee_type"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="first_category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>First Category</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['first_category']; ?>" 
                                                        id="first_category"
                                                        name = "first_category"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="second_category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Second Category</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text"
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['second_category']; ?>" 
                                                        id="second_category"
                                                        name = "second_category"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="third_category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Third Category</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text"
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['third_category']; ?>" 
                                                        id="third_category"
                                                        name = "third_category"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_person" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Person</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['contact_person']; ?>" 
                                                        id="contact_person"
                                                        name = "contact_person"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_number" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Number</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['contact_number']; ?>" 
                                                        id="contact_number"
                                                        name = "contact_number"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Address</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        value="<?php echo $data['address']; ?>" 
                                                        id="address"
                                                        name = "address"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="store_location" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Location</label>
                                                <div class="col-md-8">
                                                    <select 
                                                        class="form-control" 
                                                        name = "store_location" 
                                                        ng-model = "store_name"
                                                        ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + store_name)"
                                                        onchange = "prospect_formDefault('floor_location', 'location_code', 'area_classification', 'area_type', 'payment_mode', 'rent_period', 'add_floor_area', 'add_basic_rental')">
                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                    <?php foreach ($stores as $store): ?>
                                                        <option><?php echo $store['store_name']; ?></option>
                                                    <?php endforeach ?>
                                                    </select>   
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="none">
                                        
                                    </div>
                                    <!-- Divider -->
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="floor_location" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                                <div class="col-md-8">
                                                    <select 
                                                        class="form-control" 
                                                        name = "floor_location"
                                                        ng-model = "floor_location" 
                                                        id = "floor_location"
                                                        ng-change = "populate_locationCode('<?php echo base_url(); ?>index.php/leasing_transaction/populate_ltlocationCode/' + store_name + '/' + floor_location);"
                                                        onchange = "prospect_formDefault('none', 'location_code', 'area_classification', 'area_type', 'payment_mode', 'rent_period', 'add_floor_area', 'add_basic_rental')">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="location_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                                <div class="col-md-8">
                                                    <select 
                                                        class="form-control" 
                                                        name = "location_code" 
                                                        ng-model = "location_code"
                                                        id = "location_code"
                                                        ng-change = "get_locationCodeInfo('<?php echo base_url(); ?>index.php/leasing_transaction/get_locationCodeInfo/' + location_code)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="code in codeList">{{ code.location_code }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="area_classification" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Classification</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        ng-model="area_classification"
                                                        id="area_classification"
                                                        name = "area_classification"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="area_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Type</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        ng-model="area_type"
                                                        id="area_type"
                                                        name = "area_type"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="rent_period" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Period</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        ng-model="rent_period"
                                                        id="rent_period"
                                                        name = "rent_period"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="payment_mode" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Type of Payment</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        readonly
                                                        class="form-control" 
                                                        ng-model="payment_mode"
                                                        id="payment_mode"
                                                        name = "payment_mode"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Area</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                        <input 
                                                            type="text"
                                                            readonly
                                                            ui-number-mask="2" 
                                                            class="form-control currency" 
                                                            ng-model="floor_area"
                                                            id="add_floor_area"
                                                            name = "floor_area"
                                                            autocomplete="off" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="basic_rental" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Basic Rental</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input 
                                                            type="text"
                                                            readonly
                                                            ui-number-mask="2" 
                                                            class="form-control currency" 
                                                            ng-model="basic_rental"
                                                            id="add_basic_rental"
                                                            name = "basic_rental"
                                                            autocomplete="off" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="remarks" class="col-md-4 control-label text-right">Remarks</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text"  
                                                        class="form-control" 
                                                        id="remarks"
                                                        name = "remarks"
                                                        autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="request_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Request Date</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker  date-format="yyyy-mM-dd">
                                                            <input 
                                                                type="text" 
                                                                required 
                                                                readonly
                                                                placeholder="Choose a date" 
                                                                class="form-control" 
                                                                ng-model="request_date"
                                                                id="request_date"
                                                                name = "request_date"
                                                                autocomplete="off">
                                                        </datepicker>

                                                     <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_prospect.request_date.$dirty && frm_prospect.request_date.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#attchment" class="btn btn-sky" aria-controls="letter" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i> Attachments</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="attchment">
                                            <div class="row">
                                                <div class="col-md-10 col-md-offset-1">
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Letter of Intent <span class = "red">(SR04.01.09.02)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="intent_letter" name = "intent_letter[]" required type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Colored Perspective<span class = "red">(SR04.01.09.05)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="perspective" name = "perspective[]" required type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Ltenants" class="btn btn-danger pull-right"> <i class = "fa fa-arrow-left"></i> Back</a>
                                    <button type="submit"  style="margin-right:10px"  class="btn btn-primary pull-right"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                                </div>
                            </div>          
                        </form>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->


</div> <!-- /.container -->
</body>
