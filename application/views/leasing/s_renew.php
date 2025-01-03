
    <div class="container">
        <div class="well">
            <div class="panel panel-default">
                <div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Renew Contract(Short Term)</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                           <span class = "note">Note: <i class="fa fa-asterisk"></i> Required Fields</span>
                        </div>
                    </div>
                    <?php foreach ($prospectData as $data): ?> 
                        <form action="" method="post" onsubmit="add_stenant('<?php echo base_url(); ?>index.php/leasing_transaction/add_stenant'); return false;" enctype="multipart/form-data" id="frm_contract" name = "frm_contract">
                            <div class="col-md-11">
                                <div class="row">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Primary Details</a></li>
                                        <li role="presentation" class=""><a href="#terms" aria-controls="terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                                        <li role="presentation" class=""><a href="#discounts" aria-controls="discounts" role="tab" data-toggle="tab">Discounts</a></li>
                                        <li role="presentation"><a href="#Attachment" aria-controls="Attachment" role="tab" data-toggle="tab">Attachment</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="details">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="tenant_id"
                                                                        value="<?php echo $tenant_id; ?>"
                                                                        name = "tenant_id"
                                                                        autocomplete="off" >
                                                                    
                                                                        <!-- Hidden prospect ID -->
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
                                                                        id="trade_name"
                                                                        value="<?php echo $data['trade_name']; ?>"
                                                                        name = "trade_name"
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
                                                                        id="contact_person1"
                                                                        value="<?php echo $data['contact_person1']; ?>"
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
                                                                        value="<?php echo $data['contact_person2']; ?>" 
                                                                        class="form-control"
                                                                        id="contact_person2"
                                                                        name = "contact_person2"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number 1</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="contact_number1"
                                                                        name = "contact_number1"
                                                                        value="<?php echo $data['contact_number1']; ?>"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number 2</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="contact_number2"
                                                                        name = "contact_number2"
                                                                        value="<?php echo $data['contact_number2']; ?>"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="store_location" class="col-md-4 control-label text-right">Store Location</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control"
                                                                        value="<?php echo $data['store_name']; ?>"
                                                                        id="store_location"
                                                                        name = "store_location"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_location" class="col-md-4 control-label text-right">Floor Location</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control"
                                                                        value="<?php echo $data['floor_name']; ?>"
                                                                        id="floor_location"
                                                                        name = "floor_location"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> <!-- End of col-md-6 -->
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="location_code" class="col-md-4 control-label text-right">Location Code</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="location_code"
                                                                        value="<?php echo $data['location_code']; ?>"
                                                                        name = "location_code"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="category" class="col-md-4 control-label text-right">Category</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="category"
                                                                        value="<?php echo $data['category']; ?>"
                                                                        name = "category"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="price_persq" class="col-md-4 control-label text-right">Price Per Sq.</label>
                                                                <div class="col-md-8">  
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            required
                                                                            readonly 
                                                                            class="form-control currency"
                                                                            id="price_persq"
                                                                            value="<?php echo number_format($data['price'], 2); ?>"
                                                                            name = "price_persq"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="floor_area" class="col-md-4 control-label text-right">Floor Area</label>
                                                                <div class="col-md-8">  
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            required
                                                                            readonly 
                                                                            class="form-control currency"
                                                                            id="floor_area"
                                                                            value="<?php echo number_format($data['floor_area'], 2); ?>"
                                                                            name = "floor_area"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="per_day" class="col-md-4 control-label text-right">Rate Per Day</label>
                                                                <div class="col-md-8">  
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            required
                                                                            readonly 
                                                                            class="form-control currency"
                                                                            value="<?php echo $data['rate_per_day']; ?>"
                                                                            id="per_day"
                                                                            name = "per_day"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="per_week" class="col-md-4 control-label text-right">Rate Per Week</label>
                                                                <div class="col-md-8">  
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            required
                                                                            readonly
                                                                            value="<?php echo $data['rate_per_week']; ?>" 
                                                                            class="form-control currency"
                                                                            id="per_week"
                                                                            name = "per_week"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="per_month" class="col-md-4 control-label text-right">Rate Per Month</label>
                                                                <div class="col-md-8">  
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                        <input 
                                                                            type="text" 
                                                                            required
                                                                            readonly 
                                                                            class="form-control currency"
                                                                            id="per_month"
                                                                            value="<?php echo $data['rate_per_month']; ?>"
                                                                            name = "per_month"
                                                                            autocomplete="off" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> <!-- End of Second col-md-6 -->
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="terms">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="contract_no" class="col-md-4 control-label text-right">Contract No.</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        value="<?php echo $contract_no; ?>" 
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="contract_no"
                                                                        name = "contract_no"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="tin" class="col-md-4 control-label text-right"><i class="fa fa-asterisk"></i>TIN</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        value="<?php echo $data['tin']; ?>" 
                                                                        class="form-control"
                                                                        id="tin"
                                                                        name = "tin"
                                                                        autocomplete="off" >
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
                                                                                    autocomplete="off">
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
                                                                    <div class="input-group">
                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                            <datepicker date-min-limit="{{opening_date}}" date-format="yyyy-M-dd">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required 
                                                                                    placeholder="Choose a date" 
                                                                                    class="form-control" 
                                                                                    ng-model="expiry_date"
                                                                                    id="expiry_date"
                                                                                    name = "expiry_date"
                                                                                    autocomplete="off"
                                                                                    ng-change = "days_diff(opening_date, expiry_date)">
                                                                            </datepicker>
                                                                            <!-- FOR ERRORS -->
                                                                            <div class="validation-Error">
                                                                                <span ng-show="frm_contract.expiry_date.$dirty && frm_contract.expiry_date.$error.required">
                                                                                    <p class="error-display">This field is required.</p>
                                                                                </span>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label for="num_days" class="col-md-4 control-label text-right">No. of Days</label>
                                                                <div class="col-md-8">  
                                                                    <input 
                                                                        type="text" 
                                                                        readonly 
                                                                        class="form-control"
                                                                        id="num_days"
                                                                        name = "num_days"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <div class="col-md-12">
                                                <div class="row">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type = "submit" ng-disabled = "frm_contract.$invalid" class="btn btn-medium btn-primary button-b"><i class = "fa fa-save"></i> Submit</button>
                                    <a href="<?php echo base_url(); ?>index.php/leasing_transaction/lst_Sforcontract" class = "btn btn-medium btn-default button-w"><i class = "fa fa-arrow-circle-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    <?php endforeach ?>   
                </div>
            </div>
        </div>
    </div>
</body>