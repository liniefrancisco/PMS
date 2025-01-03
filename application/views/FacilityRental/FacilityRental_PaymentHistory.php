
<div class="container" ng-controller="FacilityRentalController">
    <div class="well">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i>Facility Rental Payment History</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak" ng-init="populate_facilityrentalcustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/populate_facilityrentalcustomer/');">
                            <div role="tabpanel" class="tab-pane active" id="payment_history">
                            	<div class="row">
                                	<div class="col-md-10 col-md-offset-1">
										<div class="row">
											<div class="col-md-6">
			                                    <div class="row">
			                                        <div class="form-group">
			                                            <label for="trade_name" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Customer Name</label>
			                                            <div class="col-md-7">
			                                                <div class="input-group">
			                                                    <div mass-autocomplete>
			                                                        <input
			                                                            required
			                                                            name = "frcustomername"
			                                                            ng-model = "dirty.value"
			                                                            mass-autocomplete-item = "autocomplete_options"
			                                                            class = "form-control"
			                                                            id = "frcustomername">
                                                                    <input class="hidden" type="text" ng-model="customer_id" id="customer_id" name ="customer_id">
			                                                    </div>
			                                                    <span class="input-group-btn">
			                                                        <button
			                                                            class = "btn btn-info"
			                                                            type = "button"
			                                                            ng-click = "get_paymentHistory(dirty.value,'<?php echo base_url()?>index.php/leasing_facilityrental/generate_frcustomerdetails/','<?php echo base_url();?>index.php/leasing_facilityrental/get_paymentdata/')"><i class = "fa fa-search"></i>
			                                                        </button>
			                                                    </span>
			                                                </div>
			                                            </div>
			                                        </div>
			                                    </div>   
                                                <div class="row">
			                                        <div class="form-group">
			                                            <label for="contract_person" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contact Person</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "contact_person"
						                                        id="contact_person"
						                                        name = "contact_person"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>  
											</div>
											<div class="col-md-6"> <!-- COL-MD-6 DIVIDER -->
                                                <div class="row">
			                                        <div class="form-group">
			                                            <label for="contract_no" class="col-md-5 control-label text-right"><i class="fa fa-asterisk"></i>Contact No.</label>
			                                            <div class="col-md-7">
						                                    <input
						                                        type = "text"
						                                        readonly
						                                        ng-model = "contact_no"
						                                        id="contact_no"
						                                        name = "contact_no"
						                                        class = "form-control">
			                                            </div>
			                                        </div>
			                                    </div>
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
											</div>
										</div>
	                                </div>
                                </div>

                                <hr>

                                <div class="row"> <!-- table wrapper  -->
                                	<div class="col-md-12" ng-controller="tableController">
                                		<div class="row">
						                    <div class="col-md-3 pull-right">
						                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
						                    </div>
						                </div>
				                        <table class="table table-bordered" ng-table = "tableParams">
				                            <tbody id = "paymentHistory_tbody">
				                                <tr class="ng-cloak" ng-repeat= "dt in data">
				                                    <td title="'Receipt No.'" sortable="'receipt_no'">{{ dt.facilityrental_receiptno }}</td>
				                                    <td title="'Payment Type'" sortable="'tender_typeDesc'">{{ dt.tender_type }}</td>
				                                    <td title="'Amount Paid'" sortable="'amount_paid'">{{ dt.amount_paid | currency : '' }}</td>
				                                    <td title="'Check No.'" sortable="'check_no'">
				                                    	<div ng-if = "!dt.check_no">N/A</div>
				                                    	<div ng-if = "dt.check_no">{{ dt.check_no }}</div>
				                                    </td>
				                                    <td title="'Check Date'" sortable="'check_date'">
				                                    	<div ng-if = "dt.check_date == null">N/A</div>
				                                    	<div ng-if = "dt.check_date != '0000-00-00'">{{ dt.check_date }}</div>
				                                    </td>
				                                    <td title="'Payee'" sortable="'payee'">{{ dt.payee }}</td>
				                                    <td title="'Action'">
				                                    	<a href="<?php echo base_url(); ?>assets/facilityrental_pdf/{{dt.receipt_file}}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-print"></i></a>
				                                    	<a href="#" ng-if="dt.tender_typeDesc != 'Cash'" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_paymentDocs/' + dt.facilityrental_receiptno, '<?php echo base_url(); ?>assets/facilityrental_suppdocs/')" class="btn btn-success btn-xs"><i class="fa fa-tv"></i></a>
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
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
    </div> <!-- End of Well -->
</div> <!-- End of Container -->


	<!-- Carousel Modal -->
    <div class="modal fade" id="carousel_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div ng-if = "!imgList" class = "item active">
                            <img src="<?php echo base_url(); ?>img/thumbnail.png" alt="Default">
                        </div>
                        <div ng-class="{item: true, 'active' : ($index === 0)}" ng-repeat="img in imgList">
                            <img ng-src="{{imgPath}}{{img.file_name}}" alt="{{img.file_name}}">
                        </div>
                        <!-- Controls -->
                        <a ng-if = "imgList.length > 1" class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a ng-if = "imgList.length > 1" class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!--End Carousel Modal -->


</body>
