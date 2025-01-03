
<div class="container" ng-controller="transactionController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Tenant Penalties</div>
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
			                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Trade Name</label>
			                                            <div class="col-md-7">
			                                                <div class="input-group">
			                                                    <div mass-autocomplete>
			                                                        <input 
			                                                            required
			                                                            name = "trade_name"
			                                                            ng-model = "dirty.value"
			                                                            mass-autocomplete-item = "autocomplete_options"
			                                                            class = "form-control"
			                                                            id = "trade_name">
			                                                    </div>
			                                                    <span class="input-group-btn">
			                                                        <button 
			                                                            class = "btn btn-info" 
			                                                            type = "button"
			                                                            ng-click = "tenant_penalties(dirty.value, tenancy_type)"><i class = "fa fa-search"></i>
			                                                        </button>
			                                                    </span>
			                                                </div>
			                                            </div>
			                                        </div>
			                                    </div>
			                                    
			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="tenant_id" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Tenant ID</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "tenant_id" 
						                                        id="tenant_id" 
						                                        name = "tenant_id" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="corporate_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Corporate Name</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "corporate_name" 
						                                        id="corporate_name" 
						                                        name = "corporate_name" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
												<div class="row">
			                                        <div class="form-group">
			                                            <label for="address" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Address</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "address" 
						                                        id="address" 
						                                        name = "address" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
												<div class="row">
			                                        <div class="form-group">
			                                            <label for="tin" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>TIN</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "tin" 
						                                        id="tin" 
						                                        name = "tin" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

												<div class="row">
			                                        <div class="form-group">
			                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contract No.</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "contract_no" 
						                                        id="contract_no" 
						                                        name = "contract_no" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>

												<div class="row">
			                                        <div class="form-group">
			                                            <label for="rental_type" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Rental Type</label>
			                                            <div class="col-md-7">  
						                                    <input 
						                                        type = "text"
						                                        readonly 
						                                        ng-model = "rental_type" 
						                                        id = "rental_type" 
						                                        name = "rental_type" 
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
											</div>
										</div>
	                                </div>
                                </div>

                                <hr>

                                <div class="row" ng-controller="tableController"> <!-- table wrapper  --> 
                                	<div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                                            </div>
                                        </div>
                                    </div>
                                	<table ng-table="tableParams" class="table table-bordered">
                                        <tbody>
                                        	<tr ng-repeat="dt in data">
                                        		<td title="'Entry No.'" sortable="'id'">{{dt.id}}</td>
                                        		<td title="'Doc No.'" sortable="'doc_no'">{{ dt.doc_no }}</td>
                                        		<td title="'Ref No.'" sortable="'ref_no'">{{ dt.ref_no }}</td>
                                        		<td title="'Description'" sortable="'description'">{{ dt.description }}</td>
                                        		<td title="'Posting Date'" sortable="'posting_date'">{{ dt.posting_date }}</td>
                                        		<td title="'Amount'" sortable="'amount'" class = "currency-align">
                                        			{{ dt.amount | currency : '' }}
                                        		</td>
                                        		<td title="'Action'" class = "currency-align">
                                        			<!-- Split button -->
			                                        <div class="btn-group">
			                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
			                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                                                <span class="caret"></span>
			                                                <span class="sr-only">Toggle Dropdown</span>
			                                            </button>
			                                            <ul class="dropdown-menu">
			                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#attachment_modal" ng-click = "pass_URLtoManagerModal('<?php echo base_url(); ?>index.php/leasing_transaction/waive_penalty/' + dt.id)"> <i class = "fa fa-close"></i> Waive Penalty</a></li>
			                                            </ul>
			                                        </div>
                                        		</td>
                                        	</tr>
                                        </tbody>
                                        <tbody ng-show = "isLoading">
                                            <tr>
                                                <td colspan="10">
                                                    <div class="table-loader"><img src="<?php echo base_url(); ?>img/spinner2.svg"></div>
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


  
    <!-- Attachment Modal -->
    <div class="modal fade" id = "attachment_modal">
        <div class="modal-dialog modal-medium">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-question-circle"></i> Upload Attachment</h4>
                </div>
                <form action="#" onsubmit=""  method="post" id="frm_penalty" enctype="multipart/form-data" name = "frm_penalty">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Waiver</label>
                                        <div class="col-md-6 text-right">
                                            <input 
                                                type="file" 
                                                class="form-control"
                                                id="waiver"
                                                required
                                                name = "waiver"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Saved</button>
                    </div>
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Attachment Modal -->    







</div> <!-- End of Container -->
</body>