
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Amendment of Contract </div>
            <div class="panel-body" ng-init = "viewing('<?php echo base_url(); ?>index.php/leasing_transaction/sterms_amendment/<?php echo $tenant_id; ?>')">
                <div class="row" ng-repeat = "data in viewList">
                    <div class="col-md-10 col-md-offset-1">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist"> 
                            <li role="presentation" class="active"><a href="#amend_terms" aria-controls="amend_terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                            <li role="presentation" class=""><a href="#amend_discounts"  aria-controls="amend_discounts" role="tab" data-toggle="tab" ng-init="get_pickedDiscounts('<?php echo base_url(); ?>index.php/leasing_transaction/get_discounts/<?php echo $tenant_id; ?>'); get_allDiscounts('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_tenant_type')">Discounts</a></li>
                            <li role="presentation" class=""><a href="#reason" aria-controls="reason" role="tab" data-toggle="tab">Reason</a></li>
                            <li role="presentation" class=""><a href="#amend_attachments" aria-controls="amend_attachments" role="tab" data-toggle="tab">Attachments</a></li>
                        </ul>
                        <form action="#" method = "post" onsubmit="amend_stenant('<?php echo base_url(); ?>index.php/leasing_transaction/amend_stenant/'); return false;"  enctype="multipart/form-data" name = "frm_amendment" id="frm_amendment">
                            <div class="tab-content" >
                                <div role="tabpanel" class="tab-pane active" id="amend_terms" >
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
                                                                autocomplete="off" >
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
                                                        <label for="floor_name" class="col-md-4 control-label text-right">Floor Location</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type = "text"
                                                                name = "location_code" 
                                                                class = "form-control"
                                                                readonly
                                                                ng-model = "data.floor_name">
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
                                                                class = "form-control"
                                                                readonly
                                                                ng-model = "data.location_code">
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
                                                                    class="form-control currency"
                                                                    format="number"
                                                                    id="amendment_price_persq"
                                                                    name = "price_persq"
                                                                    ng-model = "data.price_persq"
                                                                    ng-keyup = "recalculate_rental(data.price_persq, data.floor_area, data.opening_date, data.expiry_date)"
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
                                                                    class="form-control currency"
                                                                    format="number"
                                                                    id="floor_area"
                                                                    name = "floor_area"
                                                                    ng-model = "data.floor_area"
                                                                    ng-keyup = "recalculate_rental(data.price_persq, data.floor_area, data.opening_date, data.expiry_date)"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- divider -->
                                            <div class="col-md-6">
                                            	 <div class="row">
                                                    <div class="form-group">
                                                        <label for="plus_vat" class="col-md-4 control-label text-right"></label>
                                                        <div class="col-md-8 text-right">
                                                           <label class="control-label">Plus VAT</label> <input type = "checkbox" onclick="isvat()" ng-checked = "data.is_vat"  value = "added"  name = "plus_vat" id = "amendment_plus_vat" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id = "doc_holder">
                                                    <div class="row" ng-if = "!data.is_vat">
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
                                                        <label for="opening_date" class="col-md-4 control-label text-right">Opening Date </label>
                                                        <div class="col-md-8">  
                                                            <div class="input-group">
                                                                <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                <datepicker date-format="yyyy-M-dd">
                                                                    <input 
                                                                        type="text" 
                                                                        required 
                                                                        placeholder="Choose a date" 
                                                                        class="form-control"
                                                                        ng-change = "recalculate_rental(data.price_persq, data.floor_area, data.opening_date, data.expiry_date)"
                                                                        ng-model="data.opening_date"
                                                                        id="opening_date"
                                                                        name = "opening_date"
                                                                        autocomplete="off">
                                                                </datepicker>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="expiry_date" class="col-md-4 control-label text-right">Expiry Date </label>
                                                        <div class="col-md-8">  
                                                            <div class="input-group">
                                                                <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                <datepicker date-min-limit="<?php echo date('Y-m-d'); ?>" date-format="yyyy-M-dd">
                                                                    <input 
                                                                        type="text" 
                                                                        required
                                                                        ng-change = "recalculate_rental(data.price_persq, data.floor_area, data.opening_date, data.expiry_date)" 
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
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="no_of_days" class="col-md-4 control-label text-right">No. of Days</label>
                                                        <div class="col-md-8">  
                                                            <input 
                                                                type="text" 
                                                                required
                                                                readonly 
                                                                class="form-control"
                                                                ng-model = "data.num_days"
                                                                id="amendment_numdays"
                                                                name = "num_days"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="rate_perday" class="col-md-4 control-label text-right">Rate Per Day</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                <input 
                                                                    type="text" 
                                                                    class="form-control currency"
                                                                    id="amendment_rate_perday"
                                                                    name = "rate_perday"
                                                                    ng-model = "data.rate_perday"
                                                                    ng-keyup = ""
                                                                    autocomplete="off" >
                                                            </div>
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
                                                                    readonly
                                                                    id="amendment_basic_rental"
                                                                    name = "basic_rental"
                                                                    ng-model = "data.basic_rental"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   	</div>
                                </div>
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
                                                            <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
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

                                <div role="tabpanel" class="tab-pane" id="reason" >
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="row">   
                                            <label for="reason" class="control-label text-left"><i class="fa fa-asterisk"></i>Reason of Amendment</label>
                                        </div>
                                        <div class="row">
                                            <textarea 
                                                name = "reason"
                                                required
                                                id="reason" 
                                                class="form-control"
                                                rows="4"></textarea>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type = "submit" ng-disabled = "frm_amendment.$invalid" class="btn btn-medium btn-primary button-b"><i class = "fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

</div> <!-- /.container -->
</body>