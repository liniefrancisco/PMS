<div class="container" ng-controller = "transactionController">
	<div class="well">
		<div class="panel panel-default">
			<div class="panel-heading panel-leasing"><i class="fa fa-pencil-square"></i> Draft Invoice</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-bordered" ng-table = "tableParams" ng-controller = "tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_draftInvoice')">
							
							<tbody>
								 <tr class="ng-cloak" ng-repeat= "dt in data">
									<td title = "'Tenant ID'" sortable = "'tenant_id'">{{dt.tenant_id}}</td>
									<td title = "'Description'" sortable = "'charges_type'">
										<span ng-if = "data.charges_type == 'Other'">{{dt.charges_type}}-{{dt.trade_name}}</span>	
										<span ng-if = "data.charges_type != 'Other'">Basic-{{dt.trade_name}}</span>	
									</td>
									<td title = "'Doc No.'" sortable = "'doc_no'">{{dt.doc_no}}</td>
									<td title = "'Contract No.'" sortable = "'contract_no'">{{dt.contract_no}}</td>
									<td title = "'Posting Date'" sortable = "'posting_date'">{{dt.posting_date}}</td>
									<td title = "'Due Date'" sortable = "'due_date'">{{dt.due_date}}</td>
									<td title = "'Total'" sortable = "'total'">{{dt.total | currency : '&#8369;'}}</td>
									<td title = "'Action'">
										<!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                            	<!-- <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/post_invoice/{{data.tenant_id}}/{{data.doc_no}}" > <i class = "fa fa-save"></i> Post</a></li> -->
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing_transaction/update_invoicePage/{{dt.tenant_id}}/{{dt.doc_no}}" > <i class = "fa fa-edit"></i> Update</a></li>
                                                <li>
                                                	<?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                		<a 
	                                                        href="#" 
	                                                        data-toggle="modal"
	                                                        ng-click = "delete('<?php echo base_url(); ?>index.php/leasing_transaction/delete_invoice/' + dt.tenant_id + '/' + dt.doc_no)" 
	                                                        data-target="#confirmation_modal"> 
	                                                        <i class = "fa fa-trash"></i> Delete
	                                                    </a>
                                                	<?php else: ?>
                                                		<a 
	                                                        href="#" 
	                                                        data-toggle="modal"
	                                                        ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/delete_invoice/' + dt.tenant_id + '/' + dt.doc_no)" 
	                                                        data-target="#manager_modal"> 
	                                                        <i class = "fa fa-trash"></i> Delete
	                                                    </a>
                                                	<?php endif ?>
                                                </li>
                                            </ul>
                                        </div>
									</td>
								</tr>
								<tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="8"><center>No Data Available.</center></td>
                                </tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>