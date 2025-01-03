<script type="text/javascript">var chargez =<?php echo json_encode($charges); ?>;</script>
<div class="container" ng-controller="transactionController">
    <div class="row">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-leasing"><i class="fa fa-edit"></i> Billing Adjustment</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General</a></li>
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
                                                                                    name      = "tenancy_type"
                                                                                    class     = "form-control"
                                                                                    ng-model  = "tenancy_type"
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
                                                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
                                                                                <div class="col-md-7">
                                                                                    <div mass-autocomplete>
                                                                                        <input
                                                                                            id        = "trade_name"
                                                                                            name      = "trade_name"
                                                                                            class     = "form-control"
                                                                                            ng-model  = "dirty.value"
                                                                                            ng-change = "tenantInfo(dirty.value, tenancy_type)"
                                                                                            mass-autocomplete-item = "autocomplete_options"
                                                                                            required>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenant_id" class="col-md-5 control-label text-right">Corporate Name</label>
                                                                            <div class="col-md-7">
                                                                                <input
                                                                                    id       = "corporate_name"
                                                                                    type     = "text"
                                                                                    name     = "corporate_name"
                                                                                    class    = "form-control"
                                                                                    ng-model = "corporate_name"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- COL-MD-6 DIVIDER -->
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenant_id" class="col-md-5 control-label text-right">Tenant ID</label>
                                                                            <div class="col-md-7">
                                                                                <input
                                                                                    id       = "tenant_id"
                                                                                    type     = "text"
                                                                                    name     = "tenant_id"
                                                                                    class    = "form-control"
                                                                                    ng-model = "tenant_id"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label for="tenancy_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i> Search By</label>
                                                                            <div class="col-md-7">
                                                                                <select
                                                                                    class     = "form-control"
                                                                                    name      = "tenancy_type"
                                                                                    ng-model  = "searchBy"
                                                                                    ng-change = "showAll()"
                                                                                    required>
                                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                                    <option value="Show All">Show All</option>
                                                                                    <option value="Doc No">Doc No</option>
                                                                                    <option value="Ref No">Ref No</option>
                                                                                    <option value="Posting Date">Posting Date</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" ng-if="searchBy == 'Doc No'">
                                                                        <div class="form-group">
                                                                            <label for="doc_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Doc No.</label>
                                                                            <div class="col-md-7">
                                                                                <input
                                                                                    id          = "doc_no"
                                                                                    type        = "text"
                                                                                    name        = "doc_no"
                                                                                    class       = "form-control"
                                                                                    value       = ""
                                                                                    onkeyup     = "adjustmentKeyup(this, this.value)"
                                                                                    ng-model    = "doc_no"
                                                                                    placeholder = "Enter Doc No.">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" ng-if="searchBy == 'Ref No'">
                                                                        <div class="form-group">
                                                                            <label for="ref_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Ref No.</label>
                                                                            <div class="col-md-7">
                                                                                <input
                                                                                    id          = "ref_no"
                                                                                    type        = "text"
                                                                                    name        = "ref_no"
                                                                                    class       = "form-control"
                                                                                    value       = ""
                                                                                    onkeyup     = "adjustmentKeyup(this, this.value)"
                                                                                    ng-model    = "ref_no"
                                                                                    placeholder = "Enter Ref No.">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" ng-if="searchBy == 'Posting Date'">
                                                                        <div class="form-group">
                                                                            <label for="posting_date" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Posting Date</label>
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                                            <datepicker date-format="yyyy-MM-dd" >
                                                                                                <input

                                                                                                    id           = "posting_date"
                                                                                                    name         = "posting_date"
                                                                                                    type         = "text"
                                                                                                    class        = "form-control"
                                                                                                    value        = "<?php echo $current_date; ?>"
                                                                                                    onchange     = "postingDate()"
                                                                                                    placeholder  = "Choose a date"
                                                                                                    autocomplete = "off"
                                                                                                    required
                                                                                                    readonly>
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
                                                                                    id       = "trade_name_button"
                                                                                    type     = "button"
                                                                                    class    = "btn btn-primary btn-block button-b"
                                                                                    ng-click = "adjustments(dirty.value, tenancy_type)" 
                                                                                    disabled>
                                                                                    <i class="fa fa-cog" aria-hidden="true"></i> Generate</i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                                
                                                    <div class="row"> <!-- table wrapper  -->
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3 pull-right">
                                                                    <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered" ng-table = "tableParams" id="adjustment_table" ng-controller="tableController">
                                                            <tbody id = "adjustmentTable_body">
                                                                <tr class="ng-cloak" ng-repeat="dt in data">
                                                                    <td title = "'Doc. Type'"    sortable = "'document_type'"   >{{ dt.document_type }}</td>
                                                                    <td title = "'Doc. No.'"     sortable = "'doc_no'"          >{{ dt.doc_no }}</td>
                                                                    <td title = "'Ref. No.'"     sortable = "'ref_no'"          >{{ dt.ref_no }}</td>
                                                                    <td title = "'Type'"         sortable = "'flag'"            >{{ dt.flag }}</td>
                                                                    <td title = "'Posting Date'" sortable = "'posting_date'"    >{{ dt.posting_date }}</td>
                                                                    <td title = "'Due Date'"     sortable = "'due_date'"        >{{ dt.due_date }}</td>

                                                                    <td title = "'Amount'" sortable = "'debit'" class = "currency-align">
                                                                        <span ng-if = "dt.debit === '0' || !dt.debit">-</span>
                                                                        <span ng-if = "dt.debit !== '0'">{{ dt.debit | currency : '' }}</span>
                                                                    </td>

                                                                    <td title = "'Paid Amount'" sortable = "'credit'" class = "currency-align">
                                                                        <span ng-if = "dt.credit === '0' || !dt.credit">-</span>
                                                                        <span ng-if = "dt.credit !== '0'">{{ dt.credit | currency : '' }}</span>
                                                                    </td>

                                                                    <td title = "'Balance'" sortable = "'balance'" class = "currency-align">
                                                                        <span ng-if = "dt.balance === '0' || !dt.balance">-</span>
                                                                        <span ng-if = "dt.balance !== '0'">{{ dt.balance | currency : '' }}</span>
                                                                    </td>

                                                                    <td title="'Action'" class="text-center">
                                                                        <button 
                                                                            type          = "button"
                                                                            class         = "btn btn-xs btn-info button-caretb"
                                                                            ng-show       = "dt.flag == 'Basic Rent'"
                                                                            ng-click      = "basicRent_adj(dt)"
                                                                            data-toggle   = "modal"
                                                                            data-target   = "#basicCharges_modal"
                                                                            data-backdrop = "static">
                                                                            <i class="fa fa-edit"></i> Adjust
                                                                        </button>
                                                                        
                                                                        <button 
                                                                                type = "button"
                                                                                class = "btn btn-xs btn-info button-caretb"
                                                                                ng-show = "dt.flag == 'Other Charges'"
                                                                                ng-click = "otherCharges_adj(dt)"
                                                                                data-toggle = "modal"
                                                                                data-target = "#otherCharges_modal"
                                                                                data-backdrop = "static">
                                                                                <i class="fa fa-edit"></i> Adjust
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody ng-show = "isLoading">
                                                                <tr>
                                                                    <td colspan = "11">
                                                                        <div class = "table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
                                                                        <div class = "loader-text"><center><b>Collecting Data. Please Wait...</b></center></div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> <!-- End of tab-content -->
                                        </div>
                                    </div>
                                </div> <!-- End of panel-body -->
                            </div> <!-- End of panel -->
                        </div> <!-- End of Well -->

    <!-- ==== ADJUSTMENT OTHER CHARGES Modal === -->
        <div class="modal fade" id = "otherCharges_modal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-info-circle"></i> Invoicing Adjustment</h4>
                    </div>

                    <div class="hidden"><input type="hidden" name="" id="flag" ng-model="flag"></div>

                    <div class="modal-body ng-cloak">
                        <form action="" method="post" name="other_chargesForm" id="other_chargesForm" enctype="multipart/form-data" ng-submit="OCSubmit($event)">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- DOCUMENT TYPE AND MANAGERS KEY HOLDER -->
                                        <input type="hidden" name="managers_keyID" id="managers_keyID" value="<?php echo $managers_keyID->id; ?>">
                                        <input type="hidden" name="OCdoc_tag" id="OCdoc_tag" ng-model="OCdoc_tag" value="">
                                    <!-- DOCUMENT TYPE AND MANAGERS KEY HOLDER END -->
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="adj_code">Adjustment Code</label>
                                                <input
                                                    id       = "adj_code"
                                                    name     = "adj_code"
                                                    type     = "text"
                                                    value    = ""
                                                    class    = "form-control"
                                                    ng-model = "adj_code"
                                                    readonly>
                                        </div>
        
                                        <div class="form-group col-md-4">
                                            <label for="adj_doc_no">Doc No.</label>
                                                <input
                                                    id       = "adj_doc_no"
                                                    name     = "adj_doc_no"
                                                    type     = "text"
                                                    class    = "form-control"
                                                    value    = ""
                                                    ng-model = "adj_doc_no"
                                                    readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="DOCposting_date"><i class="fa fa-asterisk"></i> Posting Date</label>
                                                <datepicker date-format="yyyy-MM-dd" >
                                                    <input
                                                        id           = "DOCposting_date"
                                                        type         = "text"
                                                        name         = "DOCposting_date"
                                                        class        = "form-control"
                                                        autocomplete = "off"
                                                        required
                                                        readonly>
                                                </datepicker>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                            </div> <!-- end of row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="OCReason"><i class="fa fa-asterisk"></i> Reason</label>
                                            <select class = "form-control" id="OCReason" name="OCReason" ng-model="OCReason" ng-change="reasonChangeOC()" required>
                                                <option value="" disabled="" selected="" style = "display:none"> -- </option>
                                                <option>Wrong Invoice</option>
                                                <option>Wrong Amount</option>
                                                <option>To Balance out</option>
                                                <option>Request for additional discounts</option>
                                                <option>EWT Adjustment</option>
                                                <option>Others</option>
                                            </select>
                                    </div>

                                    <div class="form-group col-md-4" ng-if="OCReason == 'Others'">
                                        <label for="OCparticulars">Particulars: </label>
                                            <input
                                               id    = "OCparticulars"
                                               type  = "text"
                                               name  = "OCparticulars"
                                               class = "form-control"
                                               value = "">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="OCtransactionDate">Date of adjustment</label>
                                            <input
                                                id    = "OCtransactionDate"
                                                type  = "text"
                                                name  = "OCtransactionDate"
                                                class = "form-control"
                                                value = "<?php echo $current_date; ?>"
                                                readonly>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="adjsupdocOC"><i class="fa fa-asterisk"></i> Supporting Document</label>
                                            <input
                                                    type  = "file"
                                                    name  = "adjsupdocOC[]"
                                                    class = "form-control"
                                                    multiple>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                            </div> <!-- end of row -->

                            <hr style="border: 0; clear:both; display:block; width: 96%; background-color:#ccc; height: 1px;">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col-md-4">
                                        <label for="amountOC">Invoice Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                    <input  
                                                        id       = "amountOC"
                                                        type     = "text" 
                                                        name     = "amountOC"  
                                                        value    = "" 
                                                        class    = "form-control text-right currency" 
                                                        ng-value = "amountOC | currency : ''"
                                                        readonly>                  
                                            </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="OCpaidAmount">Amount Paid</label>
                                            <div class="input-group">
                                                <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                    <input 
                                                        id       = "OCpaidAmount" 
                                                        type     = "text" 
                                                        name     = "OCpaidAmount" 
                                                        value    = "" 
                                                        class    = "form-control text-right currency" 
                                                        ng-value = "OCpaidAmount | absolute | currency : ''" 
                                                        readonly>                  
                                            </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="newAmount">New Invoice Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                    <input 
                                                        id          = "newAmount" 
                                                        type        = "text" 
                                                        name        = "newAmount" 
                                                        class       = "form-control text-right currency" 
                                                        value       = "{{ newOCAmount() | currency : ''}}" 
                                                        placeholder = "0.00"
                                                        readonly>                  
                                            </div>

                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span><p class="error-display" ng-bind="errorDisplay"></p></span>
                                            </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                            </div> <!-- end of row -->

                            <div class="row border border-primary" ng-show="OCReason">
                                <hr style="border: 0; clear:both; display:block; width: 96%; background-color:#ccc; height: 1px;">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="oc_adj">Description</label>
                                                <input
                                                    id       = "oc_adj"
                                                    class    = "form-control"
                                                    type     = "text"
                                                    name     = "oc_adj"
                                                    value    = ""
                                                    ng-model = "oc_adj"
                                                    readonly>
                                        </div>
                
                                        <div class="col-md-4">
                                            <label for="chr_amount">Amount</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                        <input 
                                                            id           = "chr_amount" 
                                                            type         = "text" 
                                                            class        = "form-control text-right currency"
                                                            style        = "width: 100%"
                                                            ng-value     = "OCAmountADJ() | currency : ''"
                                                            placeholder  = "0.00" 
                                                            autocomplete = "off"
                                                            readonly>

                                                            <input type="hidden" name="chr_amount" ng-value="OCAmountADJ()">                                   
                                                </div>
                                        </div>

                                        <div class="col-md-12" ng-init="OCData = [{}];">
                                            <div ng-repeat="data in OCData" class="row">
                                                <div class="col-md-4">
                                                    <label for="OCCharges"><i class="fa fa-asterisk"></i> Description</label>
                                                        <select class = "form-control" id="OCCharges" ng-model="data.OCCharges" required> 
                                                            <option value="" disabled="" selected="" style = "display:none">--</option>
                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Expanded Withholding Tax'): ?>
                                                                                                <optgroup label="Creditable WHT Receivable">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Common Usage Charges'): ?>
                                                                                                <optgroup label="MI - Common Utilites">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Electricity'): ?>
                                                                                                <optgroup label="MI - Light and Power">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Aircon'): ?>
                                                                                                <optgroup label="MI - Aircon Charges">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <optgroup label="MI - Penalties">
                                                                    <?php foreach ($charges as $charge): ?>
                                                                                    <?php if ($charge['description'] == 'Late submission of Deposit Slip' || $charge['description'] == 'Late Payment Penalty' || $charge['description'] == 'Penalty'): ?>
                                                                            
                                                                                                        <option><?php echo $charge['description']; ?></option>
                                                                            
                                                                                    <?php endif; ?>
                                                                    <?php endforeach ?>
                                                                </optgroup>

                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Chilled Water'): ?>
                                                                                                <optgroup label="MI - Chilled Water">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <?php foreach ($charges as $charge): ?>
                                                                                <?php if ($charge['description'] == 'Water'): ?>
                                                                                                <optgroup label="MI - Water">
                                                                                                    <option><?php echo $charge['description']; ?></option>
                                                                                                </optgroup>
                                                                                <?php endif; ?>
                                                                <?php endforeach ?>

                                                                <optgroup label="MI - Charges">
                                                                    <?php foreach ($charges as $charge): ?>
                                                                                    <?php if ($charge['description'] != 'Water' && $charge['description'] != 'Late submission of Deposit Slip' && $charge['description'] != 'Late Payment Penalty' && $charge['description'] != 'Penalty' && $charge['description'] != 'Aircon' && $charge['description'] != 'Electricity' && $charge['description'] != 'Common Usage Charges' && $charge['description'] != 'Expanded Withholding Tax'): ?>
                                                                                                        <option><?php echo $charge['description']; ?></option>
                                                                                    <?php endif; ?>
                                                                    <?php endforeach ?>
                                                                </optgroup>
                                                        </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="OCAmount"><i class="fa fa-asterisk"></i> Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                                <input 
                                                                    id           = "OCAmount" 
                                                                    step         = ".01"
                                                                    type         = "number" 
                                                                    class        = "form-control text-right currency" 
                                                                    style        = "width: 100%"  
                                                                    ng-model     = "data.OCAmount"
                                                                    placeholder  = "0.00" 
                                                                    autocomplete = "off"
                                                                    required>                                   
                                                        </div>

                                                        <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="other_chargesForm.OCAmount.$dirty && other_chargesForm.OCAmount.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="container">
                                                            <div class="row">
                                                                <button 
                                                                    type     = "button"  
                                                                    class    = "btn btn-md  button-b"
                                                                    ng-if    = "$index == 0" 
                                                                    ng-click = "OCData.push({})">
                                                                    <i class = "fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                                <button 
                                                                    type     = "button"  
                                                                    class    = "btn btn-danger  button-r"
                                                                    ng-if    = "$index > 0" 
                                                                    ng-click = "OCData.splice($index, 1)">
                                                                    <i class = "fa fa-minus" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                            </div> <!-- end of row -->

                            <div class="modal-footer">
                                <button 
                                    class         = "btn btn-info  button-b"
                                    type          = "button"
                                    data-toggle   = "modal"
                                    data-target   = "#reviewAdjustment"
                                    ng-click      = "review()"
                                    ng-disabled   = "OCAmountADJ() == 0 || !OCAmountADJ() || newOCAmount() < OCpaidAmount || newOCAmount() < 0"
                                    data-backdrop = "static">
                                    <i class = "fa fa-search" aria-hidden="true"></i> Review
                                </button>

                                <button 
                                    type        = "submit" 
                                    class       = "btn btn-primary button-b" 
                                    ng-disabled = "other_chargesForm.$invalid || BRDataIsInvalid()">
                                    <i class = "fa fa-save"></i> Submit
                                </button>

                                <button 
                                    type         = "button" 
                                    class        = "btn btn-danger button-r"
                                    ng-click     = "clearData()" 
                                    data-dismiss = "modal" >
                                    <i class = "fa fa-close"></i> Close
                                </button>
                            </div>
                        </form>
                    </div> <!-- end of modal body-->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!-- ==== ADJUSTMENT OTHER CHARGES Modal END -->

    <!-- ==== ADJUSTMENT BASIC CHARGES Modal === -->
        <div class="modal fade" id = "basicCharges_modal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-info-circle"></i> Invoicing Adjustment</h4>
                    </div>

                    <div class="modal-body ng-cloak">
                        <form action="" method="post" name="basicRentForm" id="basicRentForm" enctype="multipart/form-data" ng-submit="BRSubmit($event)">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <!-- DOCUMENT TYPE AND MANAGER KEY HOLDER -->
                                            <input type = "hidden" name="managers_keyID" id="managers_keyID" value="<?php echo $managers_keyID->id; ?>">
                                            <input type = "hidden" name="BRDoctag" id="BRDoctag" ng-model="BRDoctag">
                                        <!-- DOCUMENT TYPE AND MANAGER KEY HOLDER END -->
                                        <div class="form-group col-md-4">
                                            <label for="adj_code1">Adjustment Code</label>
                                                <input
                                                    id       = "adj_code1"
                                                    type     = "text"
                                                    name     = "adj_code1"
                                                    class    = "form-control"
                                                    value    = ""
                                                    ng-model = "adj_code1"
                                                    readonly>
                                        </div>
        
                                        <div class="form-group col-md-4">
                                            <label for="adj_doc_no1">Doc No.</label>
                                                <input
                                                    id       = "adj_doc_no1"
                                                    type     = "text"
                                                    name     = "adj_doc_no1"
                                                    value    = ""
                                                    class    = "form-control"
                                                    ng-model = "adj_doc_no1"
                                                    readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="DOCposting_date1"><i class="fa fa-asterisk"></i> Posting Date</label>
                                                <datepicker date-format="yyyy-MM-dd" >
                                                    <input
                                                        id           = "DOCposting_date1"
                                                        type         = "text"
                                                        name         = "DOCposting_date1"
                                                        class        = "form-control"
                                                        ng-model     = "DOCposting_date1"
                                                        placeholder  = "Choose a date"
                                                        autocomplete = "off"
                                                        required
                                                        readonly>
                                                </datepicker>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->

                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label for="BRReason"><i class="fa fa-asterisk"></i> Reason</label>
                                                <select class="form-control" id="BRReason" name="BRReason" ng-model="BRReason" ng-change="reasonChange()" required>
                                                    <option value="" selected="">--</option>
                                                    <option>VAT Output Adjustment</option>
                                                    <option>Creditable WHT Recievable Adjustment</option>
                                                    <option>Rent Income Adjustment</option>
                                                    <option>Others</option>
                                                </select>
                                        </div>

                                        <div class="form-group col-md-4" ng-if="BRReason == 'Others'">
                                            <label for="BRparticulars">Particulars: </label>
                                                 <input
                                                    id    = "BRparticulars"
                                                    type  = "text"
                                                    name  = "BRparticulars"
                                                    class = "form-control"
                                                    value = "">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="BRtransactionDate">Date Adjusted</label>
                                                 <input
                                                    id    = "BRtransactionDate"
                                                    type  = "text"
                                                    name  = "BRtransactionDate"
                                                    class = "form-control"
                                                    value = "<?php echo $current_date; ?>"
                                                    readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="adjsupdoc"><i class="fa fa-asterisk"></i> Supporting Document</label>
                                                 <input
                                                    id       = "adjsupdoc"
                                                    type     = "file"
                                                    name     = "adjsupdoc[]"
                                                    class    = "form-control"
                                                    multiple>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                                
                                <div class="col-md-12">

                                    <hr style="border: 0; clear:both; display:block; width: 96%; background-color:#ccc; height: 1px;">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group col-md-4">
                                                <label for="amount">Invoice Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                            <input 
                                                                id       = "amount" 
                                                                type     = "text" 
                                                                name     = "amount" 
                                                                class    = "form-control text-right currency" 
                                                                value    = ""  
                                                                ng-value = "amount | currency : ''" 
                                                                readonly>                  
                                                    </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="paidAmount1">Amount Paid</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                            <input 
                                                                id       = "paidAmount1" 
                                                                type     = "text" 
                                                                name     = "paidAmount1" 
                                                                class    = "form-control text-right currency" 
                                                                ng-value = "paidAmount1 | absolute | currency:''"
                                                                ng-model = "paidAmount1"  
                                                                readonly>                  
                                                    </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="BRnewAmount">New Invoice Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                            <input 
                                                                id          = "BRnewAmount" 
                                                                type        = "text" 
                                                                name        = "BRnewAmount" 
                                                                class       = "form-control text-right currency" 
                                                                value       = "{{ newBRAmount() | currency : ''}}" 
                                                                placeholder = "0.00" 
                                                                readonly>                  
                                                    </div>

                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span><p class="error-display" ng-bind="errorDisplay1"></p></span>
                                                    </div>
                                            </div>
                                        </div> <!-- end of col-md-6 -->
                                    </div> <!-- end of row -->

                                    <div class="form-row" ng-show="BRData2">
                                        <hr style="border: 0; clear:both; display:block; width: 96%; background-color:#ccc; height: 1px;">
                                        <div class="form-group col-md-4">
                                            <label for="rr_adj">Description</label>
                                                <input
                                                    id       = "rr_adj"
                                                    type     = "text"
                                                    name     = "rr_adj"
                                                    class    = "form-control"
                                                    value    = ""
                                                    ng-model = "rr_adj"
                                                    readonly>
                                        </div>
                
                                        <div class="col-md-4">
                                            <label for="rr_amount">Amount</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                        <input 
                                                            id           = "rr_amount" 
                                                            name         = "rr_amount" 
                                                            type         = "text" 
                                                            class        = "form-control text-right currency" 
                                                            style        = "width: 100%"
                                                            ng-value     = "RRAmount() | currency: ''"
                                                            placeholder  = "0.00"
                                                            autocomplete = "off"
                                                            readonly>                                   
                                                </div>
                                        </div>

                                        <div class="col-md-12" ng-if="BRData3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="BRdescription"><i class="fa fa-asterisk"></i> Description</label>
                                                        <input
                                                            id       = "BRdescription"
                                                            name     = "BRdescription"
                                                            class    = "form-control"
                                                            value    = ""
                                                            type     = "text"
                                                            ng-model = "BRdescription"
                                                            readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="BRAmount2"><i class="fa fa-asterisk"></i> Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                                <input 
                                                                    id           = "BRAmount2"
                                                                    step         = ".01"
                                                                    type         = "number"  
                                                                    name         = "BRAmount2" 
                                                                    class        = "form-control text-right currency"
                                                                    style        = "width: 100%" 
                                                                    ng-model     = "data.BRAmount2"  
                                                                    placeholder  = "0.00" 
                                                                    autocomplete = "off"
                                                                    required>                                   
                                                        </div>

                                                        <div class="validation-Error">
                                                            <span ng-show="basicRentForm.BRAmount2.$dirty && basicRentForm.BRAmount2.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12" ng-init="BRData = [{}];">    
                                            <div ng-repeat="data in BRData" class="row" ng-if="BRReason == 'Others'">

                                                <div class="col-md-4">
                                                    <label for="BRCharges"><i class="fa fa-asterisk"></i> Description</label>
                                                        <select class = "form-control" id="BRCharges" ng-model="data.BRCharges" required>
                                                            <option value="" selected="">--</option>
                                                            <option>VAT Output</option>
                                                            <option>Creditable WHT Receivable</option>
                                                            <option>Rent Income</option>
                                                        </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="BRAmount"><i class="fa fa-asterisk"></i> Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon input-date"><strong>&#8369</strong></div>
                                                                <input 
                                                                    id           = "BRAmount" 
                                                                    step         = ".01"
                                                                    type         = "number"  
                                                                    style        = "width: 100%" 
                                                                    class        = "form-control text-right currency"
                                                                    ng-model     = "data.BRAmount"  
                                                                    placeholder  = "0.00" 
                                                                    autocomplete = "off"
                                                                    required>                                   
                                                        </div>

                                                        <div class="validation-Error">
                                                            <span ng-show="basicRentForm.BRAmount.$dirty && basicRentForm.BRAmount.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="container">
                                                            <div class="row">
                                                                <button 
                                                                    type     = "button"
                                                                    ng-if    = "$index == 0"  
                                                                    class    = "btn btn-md button-b"
                                                                    ng-click = "BRData.push({})">
                                                                    <i class = "fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                                <button  
                                                                    class    = "btn btn-danger  button-r"
                                                                    ng-if    = "$index > 0" 
                                                                    ng-click = "BRData.splice($index, 1)">
                                                                    <i class = "fa fa-minus" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end of col-md-6 -->
                            </div> <!-- end of row -->

                            <div class="modal-footer">  
                                <button 
                                    type          = "button"
                                    class         = "btn btn-info  button-b"
                                    ng-click      = "review2()"
                                    ng-disabled   = "RRAmount() == 0 || !RRAmount() || newBRAmount() < paidAmount1 || newBRAmount() < 0"
                                    data-toggle   = "modal"
                                    data-target   = "#reviewAdjustment"
                                    data-backdrop = "static">
                                    <i class = "fa fa-search" aria-hidden="true"></i> Review
                                </button>

                                <button type="submit" class="btn btn-primary  button-b" ng-disabled="basicRentForm.$invalid || BRDataIsInvalid()">
                                    <i class = "fa fa-save"></i>Submit</span>
                                </button>

                                <button type="button" class="btn btn-danger  button-r" data-dismiss="modal" ng-click="BRClose()"> 
                                    <i class = "fa fa-close"></i> Close
                                </button>
                            </div>
                        </form>
                    </div> <!-- end of modal body-->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!-- ==== ADJSUTMENT BASIC CHARGES Modal END -->

    <!-- ==== REVIEW MODAL ===================== -->
        <div class="modal fade" id = "reviewAdjustment" style="overflow-y: scroll;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-info-circle"></i> Adjustment Review</h4>
                    </div>
                    <!-- DOCUMENT TAG CONTAINER -->
                    <input type="hidden" name="RDoc_tag" id="RDoc_tag" ng-model="RDoc_tag" value="">

                    <div class="modal-body ng-cloak">
                        <div class="container">
                            <form name="previewForm" id="previewForm">
                                <!-- OTHER CHARGES -->
                                <table class="table table-bordered" id="reviewAdjustmentTable1" ng-show="RDoc_tag == 'Other Charges'">
                                    <thead class="ng-cloak">
                                        <tr>
                                            <th class = "text-center">GL Accounts/Charges</th>
                                            <th class = "currency-align text-center">Debit</th>
                                            <th class = "currency-align text-center">Credit</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        <tr>
                                            <td>{{ Rcharges }}</td>
                                            <td class = "text-right">{{ OCTotalDebit | absolute | currency : '' }}</td>
                                            <td class = "text-right">-{{ OCTotalCredit | absolute | currency : '' }}</td>  
                                        </tr>

                                        <tr class = "ng-cloak" ng-repeat = "data in reviewData">
                                            <td ng-if = "data.OCCharges == 'Expanded Withholding Tax'">Creditable WHT Recievable</td>
                                            <td ng-if = "data.OCCharges == 'Common Usage Charges'"    >MI - Common Utilities</td>
                                            <td ng-if = "data.OCCharges == 'Electricity'"             >MI - Light and Power</td>
                                            <td ng-if = "data.OCCharges == 'Aircon'"                  >MI - Aircon Charges</td>
                                            <td ng-if = "data.OCCharges == 'Late submission of Deposit Slip' || data.OCCharges == 'Late Payment Penalty' || data.OCCharges == 'Penalty'">
                                                MI - Penalties
                                            </td>
                                            <td ng-if = "data.OCCharges == 'Chilled Water'">MI - Chilled Waters</td>
                                            <td ng-if = "data.OCCharges == 'Water'"        >MI - Water</td>
                                            <td ng-if = "data.OCCharges != 'Expanded Withholding Tax' && data.OCCharges != 'Common Usage Charges' && data.OCCharges != 'Electricity' && data.OCCharges != 'Late submission of Deposit Slip' && data.OCCharges != 'Late Payment Penalty' && data.OCCharges != 'Penalty' && data.OCCharges != 'Chilled Water' && data.OCCharges != 'Water' && data.OCCharges != 'Aircon'">
                                                MI - Charges
                                            </td>

                                            <td class = "text-right" ng-if="data.OCAmount > 0">{{ data.OCAmount | absolute | currency : '' }}</td>
                                            <td class = "text-right" ng-if="data.OCAmount < 0">-</td>
                                            <td class = "text-right" ng-if="data.OCAmount > 0">-</td>
                                            <td class = "text-right" ng-if="data.OCAmount < 0">-{{ data.OCAmount | absolute | currency : '' }}</td>
                                        </tr>

                                        <tr>
                                            <td style = "font-weight: bold;">Total</td>
                                            <td class = "text-right" style="font-weight: bold;">{{ totalDebitOC() | absolute | currency : ''}}</td>
                                            <td class = "text-right" style="font-weight: bold;">-{{ totalDebitOC() | absolute | currency : ''}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- BASIC CHARGES -->
                                <table class="table table-bordered" id="reviewAdjustmentTable2" ng-show="RDoc_tag == 'Basic Rent'">
                                    <thead>
                                        <tr>
                                            <th class = "text-center"               >GL Accounts/Charges</th>
                                            <th class = "currency-align text-center">Debit</th>
                                            <th class = "currency-align text-center">Credit</th>
                                        </tr>
                                    </thead>  
                                    <tbody>
                                        <tr>
                                            <td>{{ BRcharges }}</td>
                                            <td class = "text-right">{{ RRTotaldebit | absolute | currency : '' }}</td>
                                            <td class = "text-right">-{{ RRTotalcredit |  absolute | currency : '' }}</td>   
                                        </tr>

                                        <tr class="ng-cloak" ng-repeat="data in reviewData2">
                                            <td>{{ data.BRCharges }}</td>
                                            <td class = "text-right" ng-if = "data.BRAmount > 0">{{ data.BRAmount | absolute | currency : '' }}</td>
                                            <td class = "text-right" ng-if = "data.BRAmount < 0">-</td>
                                            <td class = "text-right" ng-if = "data.BRAmount > 0">-</td>
                                            <td class = "text-right" ng-if = "data.BRAmount < 0">-{{ data.BRAmount | absolute | currency : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style = "font-weight: bold;">Total</td>
                                            <td class = "text-right" style = "font-weight: bold;">{{ totalDebit() | absolute | currency : '' }}</td>
                                            <td class = "text-right" style = "font-weight: bold;">-{{ totalCredit() | absolute | currency : '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div> <!-- end of modal body-->

                    <div class="modal-footer">
                        <button type = "button" class="btn btn-danger  button-r" data-dismiss="modal" ng-click="closeReviewModal()"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <!-- ==== REVIEW MODAL ===================== -->

                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->
</div> <!-- .container -->
