    <div class="container">
        <div class="well">
            <div class="panel panel-default">
                <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Contract Signing</div>
                <div class = "panel-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                           <span class = "note">Note: <i class="fa fa-asterisk"></i> Required Fields</span>
                        </div>
                    </div>    
                    <?php foreach ($prospectData as $data): ?>
                        <form action="" method="post" onsubmit="add_ltenant('<?php echo base_url(); ?>index.php/leasing_transaction/add_ltenant'); return false;"  enctype="multipart/form-data" id="frm_contract" name = "frm_contract">
                        

                            <div class="col-md-11">
                                <div class="row">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Primary Details</a></li>
                                        <li role="presentation" class=""><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
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
                                                                <label for="tin" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>TIN</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly
                                                                        class="form-control" 
                                                                        value="<?php echo $data['tin']; ?>"
                                                                        id="tin"
                                                                        name = "tin"
                                                                        autocomplete="off" >
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
                                                                        ng-model = "rental_type" 
                                                                        class = "form-control">
                                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                        <option>Basic Rental plus VAT less withholding Taxes</option>
                                                                        <option>Basic plus Percentage plus VAT less withholding Taxes</option>
                                                                        <option>Percentage Rental plus VAT less withholding Taxes</option>
                                                                        <option>Basic Rental plus VAT</option>
                                                                        <option>Basic plus Percentage plus VAT</option>
                                                                        <option>Percentage Rental plus VAT</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" ng-hide = "rental_type != 'Basic plus Percentage plus VAT less withholding Taxes' && rental_type != 'Percentage Rental plus VAT less withholding Taxes' && rental_type != 'Percentage Rental plus VAT' && rental_type != 'Basic plus Percentage plus VAT'">
                                                            <div class="form-group">
                                                                <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Percentage</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>%</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            class="form-control currency" 
                                                                            ng-model="rent_percentage"
                                                                            format="number"
                                                                            id="rent_percentage"
                                                                            name = "rent_percentage"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="lease_period" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Lease Period</label>
                                                                <div class="col-md-8">  
                                                                    <select 
                                                                        name = "lease_period" 
                                                                        id = "lease_period" 
                                                                        ng-model = "lease_period" 
                                                                        required 
                                                                        class = "form-control"
                                                                        ng-change = "calculate_balance(lease_period)">
                                                                        <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                        <option>1 Year</option>
                                                                        <option>2 Years</option>
                                                                        <option>3 Years</option>
                                                                        <option>4 Years</option>
                                                                        <option>5 Years</option>
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
                                                                            <datepicker date-min-limit="<?php echo date('Y-m-d'); ?>" date-format="yyyy-M-dd">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required 
                                                                                    placeholder="Choose a date" 
                                                                                    class="form-control" 
                                                                                    ng-model="opening_date"
                                                                                    id="opening_date"
                                                                                    name = "opening_date"
                                                                                    autocomplete="off" 
                                                                                    ng-change = "compute_contract_expiration(lease_period, opening_date);">
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
                                                                <label for="opening_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Expiry Date </label>
                                                                <div class="col-md-8">  
                                                                    <div class = "input-group">
                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                        <input 
                                                                            type = "text" 
                                                                            class = "form-control" 
                                                                            ng-model = "expiry_date" 
                                                                            name = "expiry_date"
                                                                            placeholder = "Autogenerated" 
                                                                            readonly />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    
                                                    </div>
                                                    <!-- DIVIDER -->
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="montly_wvat" class="col-md-4 control-label text-right"></label>
                                                                <div class="col-md-8 text-right">
                                                                   <label class="control-label">Plus VAT</label> <input type = "checkbox"  value = "added"  name = "plus_vat" id = "plus_vat" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id = "doc_holder">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>
                                                                    <div class="col-md-8">
                                                                       <input type = "file" name = "bir_doc" id = "bir_doc" class = "form-control" /> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   
                                                        <!-- <div class="row">
                                                        <div class="form-group">
                                                            <label for="balance" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Balance</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input 
                                                                        type="text" 
                                                                            class="form-control currency"
                                                                            readonly
                                                                            ng-model = "current_balance" 
                                                                            format="number"
                                                                            id="balance"
                                                                            name = "balance"
                                                                            autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    
                                                    
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="actual_balance" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Actual Balance</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            class="form-control currency"
                                                                            readonly
                                                                            ng-model = "current_balance" 
                                                                            format="number"
                                                                            id="actual_balance"
                                                                            name = "actual_balance"
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
                                                                        value="<?php echo $tenant_id; ?>"
                                                                        id="tenant_id"
                                                                        name = "tenant_id"
                                                                        autocomplete="off" >
                                                                        <input type = "hidden" name = "prospect_id" value="<?php echo $data['id']; ?>">
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
                                                                        value="<?php echo $data['category_one']; ?>"
                                                                        id="first_category"
                                                                        name = "first_category"
                                                                        autocomplete="off">
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
                                                                        value="<?php echo $data['category_two']; ?>"
                                                                        id="second_category"
                                                                        name = "second_category"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="third_category" class="col-md-4 control-label text-right">Third Category</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['category_three']; ?>"
                                                                        id="third_category"
                                                                        name = "third_category"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_person1" class="col-md-4 control-label text-right">Contact Person 1</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['contact_person1']; ?>"
                                                                        id="contact_person1"
                                                                        name = "contact_person1"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_person2" class="col-md-4 control-label text-right">Contact Person 2</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['contact_person2']; ?>"
                                                                        id="contact_person2"
                                                                        name = "contact_person2"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- End of col-md-6 -->
                                                    <!-- DIVIDER -->
                                                    <div class = "col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number 1</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['contact_number1']; ?>"
                                                                        id="contact_number1"
                                                                        name = "contact_number1"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_number2" class="col-md-4 control-label text-right">Contact Number 2</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['contact_number2']; ?>"
                                                                        id="contact_number2"
                                                                        name = "contact_number2"
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
                                                                        value="<?php echo $data['store_name']; ?>"
                                                                        id="store_name"
                                                                        name = "store_name"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_name" class="col-md-4 control-label text-right">Floor Location</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control" 
                                                                        value="<?php echo $data['floor_name']; ?>"
                                                                        id="floor_name"
                                                                        name = "floor_name"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_price" class="col-md-4 control-label text-right"></i>Price Per Sq.</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text"
                                                                            readonly 
                                                                            class="form-control currency" 
                                                                            value="<?php echo number_format($data['price'], 2); ?>"
                                                                            format="number"
                                                                            id="floor_price"
                                                                            name = "floor_price"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_area" class="col-md-4 control-label text-right"></i>Floor Area</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                                        <input 
                                                                            type="text"
                                                                            readonly 
                                                                            class="form-control currency" 
                                                                            value="<?php echo number_format($data['floor_area'], 2); ?>"
                                                                            format="number"
                                                                            id="floor_area"
                                                                            name = "floor_area"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_price" class="col-md-4 control-label text-right"></i>Suggst. Monthly/Rental</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text"
                                                                            readonly 
                                                                            class="form-control currency" 
                                                                            value="<?php echo number_format($data['basic_rental'], 2); ?>"
                                                                            format="number"
                                                                            id="floor_price"
                                                                            name = "floor_price"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- End of the 2nd col-md-6 -->
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="discounts">
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
                                                            <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_tenant_type')">
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
                                                                    <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "type in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
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
                                                                    <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                                                        <td colspan="5"><center>No Data Available.</center></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr class="ng-cloak">
                                                                        <td colspan="5" style="padding: 5px;">
                                                                            <div>
                                                                                <ul class="pagination">
                                                                                    <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="prevPageDisabled()">
                                                                                        <a href ng-click="prevPage()" style="border-radius: 0px;"><i class="fa fa-angle-double-left"></i> Prev</a>
                                                                                    </li>
                                                                                    <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-repeat="n in range()" ng-class="{active: n == currentPage}" ng-click="setPage(n)">
                                                                                    <a href="#">{{n+1}}</a>
                                                                                    </li>
                                                                                    <li ng-show="dataList.length!=0 && (dataList | filter:query).length != 0" ng-class="nextPageDisabled()">
                                                                                        <a href ng-click="nextPage()" style="border-radius: 0px;">Next <i class="fa fa-angle-double-right"></i></a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="Attachment">
                                            <div class="col-md-10 col-md-offset-1">
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
                                    <button type = "submit" ng-disabled = "frm_contract.$invalid" class="btn btn-medium btn-primary button-b"><i class = "fa fa-save"></i> Submit</button>
                                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Lforcontract" class = "btn btn-medium btn-default button-w"><i class = "fa fa-arrow-circle-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</body>