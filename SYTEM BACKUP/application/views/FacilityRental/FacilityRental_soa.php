
<div class="container" ng-controller="FacilityRentalController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Facility Rental > Statement of Account</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" style = "height:400px; text-align:center;">
                    <div class="row">
                            <div class="col-md-3 pull-right">
                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="container-fluid">                           
                                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController"  ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_reservationtableforSoa')"> 
                                        <tbody>
                                            <tr class="ng-cloak" ng-repeat= "reserve in data">
                                                <td style ="text-align:center;" title = "'FR No.'" sortable = "'facilityrental_no'">{{reserve.facilityrental_no}}</td>
                                                <td style ="text-align:center;" title = "'Doc No.'" sortable = "'facilityrental_docno'">{{reserve.facilityrental_docno}}</td>
                                                <td style ="text-align:center;" title = "'Customer Name'" sortable = "'FacilityRental_Cusname'">{{ reserve.FacilityRental_Cusname }}</td>
                                                <td style ="text-align:center;" title = "'Reserve Date'" sortable = "'reserve_dates_clean'">
                                                <a id="{{reserve.facilityrental_no}}" href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#reserve_list_reservedetails" class="btn btn-info btn-xs"
                                                onclick="reservation_datedetails(this.id,'<?php echo base_url(); ?>index.php/leasing_facilityrental/reservation_datedetails/')"> Reserve Details <i class = "fa fa-eye"></i></a>
                                                </td>
                                                <td style ="text-align:center;" title = "'Facilities Requested'" sortable = "'facility_reserved_clean'">{{reserve.facility_reserved_clean}}</td>
                                                <td title ="'Approved Intent Letter'" style ="text-align:center;">
                                                    <a id="{{reserve.facilityrental_no}}"  href="#" data-toggle="modal" data-target="#carousel_modal" class="btn btn-info btn-xs" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_frintentletter/' + reserve.facilityrental_no, '<?php echo base_url(); ?>assets/facilityrental_intentletter/')"> Approved Intent Letter 
                                                    <i class = "fa fa-eye"></i>
                                                    </a> 
                                            
                                                </td>  
                                                <td style ="text-align:center;" title = "'Action'" >
                                                    <a id="{{reserve.facilityrental_no}}" href="#"   data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-xs" onclick="showfrsoamodal(this.id,'<?php echo base_url(); ?>index.php/leasing_facilityrental/get_customerdetailsSoa/','<?php echo base_url(); ?>index.php/leasing_facilityrental/get_soaInvoicing/','<?php echo base_url(); ?>index.php/leasing_facilityrental/get_soaDiscount/')"><i class = "fa fa-folder-open"></i> Generate SOA</a>
                                                </td>
                                            </tr>
                                            <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                                <td colspan="5"><center>No Data Available.</center></td>
                                            </tr>
                                        </tbody>
                                        </table>                             
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

      <!-- Add Data Modal -->
      <div class="modal fade" id = "reserve_list_reservedetails" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Reservation Date</h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="container-fluid">                           
                                    <table class="table table-bordered" id="reservationdate_details_tbl"> 
                                        <thead>
											<tr>
												<td style="text-align:center;">Reserve Date</td>
												<td style="text-align:center;">Reserve Time</td>
											</tr>
										</thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>                             
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <!-- Carousel Modal -->
    <div class="modal fade" id="carousel_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">

                        <!-- Set as default active item to avoid bootsrap error -->
                        <div ng-if = "!imgList" class = "item active">
                            <img src="<?php echo base_url(); ?>img/thumbnail.png" alt="Default">
                        </div>
                        <div ng-class="{item: true, 'active' : ($index === 0)}" ng-repeat="img in imgList">
                            <img ng-src="{{imgPath}}{{img.file_name}}" alt="Slide numder {{img}}">
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

         <!-- Add Data Modal -->
    <div class="modal fade" id = "facilityrental_soa" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> GENERATE SOA</h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">
                    
                        <div class="row">
                            <form action="" onsubmit=""  method="post" id="frm_soa" name = "frm_soa">
                                <div class="col-md-11">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="facilityrental_no" class="col-md-5 control-label text-right">Facility Rental No.</label>
                                                <div class="col-md-7">
                                                    <input
                                                    type="text"
                                                    class = "form-control"
                                                    name = "facilityrental_no"
                                                    id="facilityrental_no"
                                                    required
                                                    readonly
                                                    >  
                                                    <input type="text" class="hidden" id="facilityrental_docno" name="facilityrental_docno">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="facilityrental_no" class="col-md-5 control-label text-right">Soa No.</label>
                                                <div class="col-md-7">
                                                    <input
                                                    type="text"
                                                    class = "form-control"
                                                    name = "facilityrental_soano"
                                                    id="facilityrental_soano"
                                                    value="<?php echo $soa_no; ?>"
                                                    required
                                                    readonly
                                                    >  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="frcustomername" class="col-md-5 control-label text-right">Customer Name</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="hidden" name="frcustomerid" id="frcustomerid">
                                                    <input
                                                    type="text"
                                                    required
                                                    name = "frcustomername"
                                                    class = "form-control"
                                                    id = "frcustomername"
                                                    readonly
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="frcontactnum" class="col-md-5 control-label text-right">Contact Number</label>
                                                <div class="col-md-7">
                                                    <input
                                                        type = "text"
                                                        required
                                                        readonly
                                                        class = "form-control"
                                                        id = "frcontactnum"
                                                        name = "frcontactnum"
                                                        autocomplete = "off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="frcontactperson" class="col-md-5 control-label text-right">Contact Person</label>
                                                <div class="col-md-7">
                                                    <input
                                                        type="text"
                                                        required
                                                        readonly
                                                        class = "form-control"
                                                        id = "frcontactperson"
                                                        name = "frcontactperson"
                                                        autocomplete = "off" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="frcontactaddress" class="col-md-5 control-label text-right">Address</label>
                                                <div class="col-md-7">
                                                    <input
                                                        type="text"
                                                        required
                                                        readonly
                                                        class = "form-control"
                                                        id = "frcontactaddress"
                                                        name = "frcontactaddress"
                                                        autocomplete = "off" >
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- END OF COL-MD-6 WRAPPER -->
                                    <div class="col-md-6"> <!-- SECOND COL-MD-6 WRAPPER -->

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="billing_period" class="col-md-5 control-label text-right" ng-init = "b_period='Upon Signing of Notice'"></label>
                                                <input type = "radio" name = "radio" id = "radio1" checked="checked" ng-model = "b_period" value="Upon Signing of Notice" required/><label> Upon Signing of Notice </label>
                                                <input type = "radio" name = "radio" id = "radio2" ng-model = "b_period" value="Input Billing Period" /> <label>Input Billing Period</label>
                                            </div>
                                        </div>

                                        <div class="row" ng-if = "b_period == 'Input Billing Period'">
                                            <div class="form-group">
                                                <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                <div class="col-md-7">
                                                    <input
                                                        type = "text"
                                                        required
                                                        class = "form-control"
                                                        ng-model = "billing_period"
                                                        id = "billing_period"
                                                        name = "billing_period"
                                                        autocomplete = "off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" ng-if = "b_period == 'Upon Signing of Notice'">
                                            <div class="form-group">
                                                <label for="billing_period" class="col-md-5 control-label text-right">Billing Period</label>
                                                <div class="col-md-7">
                                                    <input
                                                        readonly
                                                        type = "text"
                                                        class = "form-control"
                                                        value = "Upon Signing of Notice"
                                                        id = "billing_period"
                                                        name = "billing_period"
                                                        autocomplete = "off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="curr_date" class="col-md-5 control-label text-right">Date Created</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker date-format="yyyy-M-dd">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                placeholder="Choose a date"
                                                                class="form-control"
                                                                id="date_created"
                                                                name = "date_created"
                                                                autocomplete="off">
                                                        </datepicker>
                                                        <!-- FOR ERRORS -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="collection_date" class="col-md-5 control-label text-right">Collection Date</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker date-format="yyyy-M-dd">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                placeholder="Choose a date"
                                                                class="form-control"
                                                                id="collection_date"
                                                                name = "collection_date"
                                                                autocomplete="off">
                                                        </datepicker>
                                                        <!-- FOR ERRORS -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="total" class="col-md-5 control-label text-right">Expected Amount</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ui-number-mask="2"
                                                            ng-model="expected_amount"
                                                            readonly
                                                            id="ExpectedAmount"
                                                            name = "ExpectedAmount"
                                                            autocomplete="off">
                                                    </div>
                                                    <!-- Error -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_charges.total.$dirty && frm_charges.total.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="total" class="col-md-5 control-label text-right">Total Discount</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ui-number-mask="2"
                                                            ng-model="total_discount"
                                                            readonly
                                                            id="TotalDiscount"
                                                            name = "TotalDiscount"
                                                            autocomplete="off">
                                                    </div>
                                                    <!-- Error -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_charges.total.$dirty && frm_charges.total.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="total" class="col-md-5 control-label text-right">Actual Amount</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            required
                                                            class="form-control currency"
                                                            ui-number-mask="2"
                                                            ng-model="actual_amount"
                                                            readonly
                                                            id="ActualAmount"
                                                            name = "ActualAmount"
                                                            autocomplete="off">
                                                    </div>
                                                    <!-- Error -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_charges.total.$dirty && frm_charges.total.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- END OF THE SECOND COL-MD-6 WRAPPER -->
                                </div>
                                <div class="col-md-11">
                                    <table class="table table-bordered" id="soa_table" style="margin-left:50px">
                                        <thead>
                                            <tr>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'doc_type'; reverse = !reverse">Document No.</a></td>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = ''; reverse = !reverse">Facility Name</a></td>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'desc'; reverse = !reverse">Date of Use</a></td>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'posting_date'; reverse = !reverse">Facility Rate</a></td>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'due_date'; reverse = !reverse">Hours Used</a></td>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'actual_amt'; reverse = !reverse">Amount</a></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div> <!-- EDITABLE GRID END ROW -->

                                <div class="col-md-11">
                                    <table class="table table-bordered" id="Soadiscount_table" style="margin-left:50px">
                                        <thead>
                                            <tr>
                                                <td style="text-align:center;"><a href="#" >Document No.</a></td>
                                                <td style="text-align:center;"><a href="#" >Discount type</a></td>
                                                <td style="text-align:center;"><a href="#" >Percent/Amount</a></td>
                                                <td style="text-align:center;"><a href="#" >Discount Amount</a></td>
                                                <td style="text-align:center;"><a href="#" >Description</a></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div> <!-- EDITABLE GRID END ROW -->
                            </form>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type = "button" id="savefrsoabtn" class = "btn btn-primary btn-medium" onclick="savefrsoa('<?php echo base_url(); ?>index.php/leasing_facilityrental/savefrsoa')"><i class = "fa fa-save"></i> Generate Soa</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->
</div> <!-- End of Container -->
</body>

