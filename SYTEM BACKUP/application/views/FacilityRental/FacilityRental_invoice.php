
<div class="container" ng-controller="FacilityRentalController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Facility Rental > Invoicing</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" style = "height:400px; text-align:center;">
                    <div class="row">
                            <div class="col-md-3 pull-right">
                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                                <input class="hidden" id="base_url" value="<?php echo base_url(); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="container-fluid">                           
                                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController"  ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_reservationtableforInvoice')"> 
                                        <tbody>
                                            <tr class="ng-cloak" ng-repeat= "reserve in data">
                                                <td style ="text-align:center;" title = "'FR No.'" sortable = "'facilityrental_no'">{{reserve.facilityrental_no}}</td>
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
                                                    <a id="{{reserve.facilityrental_no}}" href="#"   data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-xs" onclick="showfr_invoicemodal(this.id,'<?php echo base_url(); ?>index.php/leasing_facilityrental/get_customerdetailsInvoice/','<?php echo base_url(); ?>index.php/leasing_facilityrental/get_facilitiesreservedInvoice/','<?php echo base_url(); ?>index.php/leasing_facilityrental/get_invoiceAppendedDiscount/')"><i class = "fa fa-folder-open"></i> Create Invoice</a>
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
    <div class="modal fade" id = "facilityrental_invoice" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i>Invoicing</h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">
                    
                        <div class="row">
                            <form action="" onsubmit=""  method="post" id="frm_invoice" name = "frm_invoice">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="facilityrental_no" class="col-md-5 control-label text-right">Document No.</label>
                                                <div class="col-md-7">
                                                    <input
                                                    type="text"
                                                    class = "form-control"
                                                    name = "facilityrental_invoiceno"
                                                    id="facilityrental_invoiceno"
                                                    value="<?php echo $frinvoiceno; ?>"
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
                                                <label for="curr_date" class="col-md-5 control-label text-right">Posting Date</label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker date-format="yyyy-M-dd">
                                                            <input
                                                                type="text"
                                                                required
                                                                placeholder="Choose a date"
                                                                class="form-control"
                                                                ng-model="posting_date"
                                                                id="posting_date"
                                                                name = "posting_date"
                                                                autocomplete="off">
                                                        </datepicker>
                                                        <!-- FOR ERRORS -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="curr_date" class="col-md-5 control-label text-right">Transaction Date</label>
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
                                                                id="curr_date"
                                                                name = "curr_date"
                                                                autocomplete="off">
                                                        </datepicker>
                                                        <!-- FOR ERRORS -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="collection_date" class="col-md-5 control-label text-right">Due Date</label>
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
                                                                id="due_date"
                                                                name = "due_date"
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
                                    <table class="table table-bordered" id="invoice_table" style="margin-left:50px">
                                        <thead>
                                            <tr>
                                                <td style="text-align:center;"><a href="#" data-ng-click="sortField = 'doc_type'; reverse = !reverse">Facility Name</a></td>
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
                                    <div class = "row text-left" style="margin-left:50px" >
                                        <a
                                           
                                            href="#"
                                            data-backdrop="static" data-keyboard="false"
                                            data-toggle="modal"
                                            data-target="#add_invoicediscount"
                                            class = "btn btn-tiny btn-fresh"
                                            ng-click = "get_discountforinvoice('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_discountforinvoice/')"
                                            ><i class  = "fa fa-plus-circle"></i> Discount
                                        </a>
                                    </div>
                                    <table class="table table-bordered" id="discount_table" style="margin-left:50px">
                                        <thead>
                                            <tr>
                                                <td style="text-align:center;"><a href="#" >Discount type</a></td>
                                                <td style="text-align:center;"><a href="#" >Percent/Amount</a></td>
                                                <td style="text-align:center;"><a href="#" >Discount Amount</a></td>
                                                <td style="text-align:center;"><a href="#" >Description</a></td>
                                                <td style="text-align:center;"><a href="#" ></a></td>
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
                    <button type = "button" id="savefrinvoicebtn" class = "btn btn-primary btn-medium" onclick="savefrinvoice('<?php echo base_url(); ?>index.php/leasing_facilityrental/savefrinvoice')"><i class = "fa fa-save"></i> Save Invoice</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <div class="modal fade" id = "add_invoicediscount">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-money"></i>Discount</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10" id = "charges_wrapper"><!---->
                            <div class = "row">
                                <div class="form-group">
                                    <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount Type</label>
                                    <div class="col-md-8">
                                        <select required name = "discount_type" id="discount_type" ng-model = "desc.discount_type" class = "form-control" ng-change="get_discountdetails('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_discountdetails/' + desc.discount_type)">
                                            <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                            <option ng-repeat = "desc in DiscountOptions">{{ desc.discount_type }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id = "discountDetail_holder" ng-repeat = "detail in discount_details">
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="tenant_id" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Percent/Amount</label>
                                        <div class="col-md-8">
                                            <input type="text" class="hidden" ng-model ="detail.id" id = "discount_id" name="discount_id">
                                            <input type = "text" ng-model = "detail.discount_option" id = "discount_option" name = "discount_option" class = "form-control" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class="form-group">
                                        <label for="discount_amount" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Discount Amount</label>
                                        <div class="col-md-8">
                                            <input type = "text" style="text-align:right;" readonly ng-model = "detail.discount_amount" id="discount_amount" name = "discount_amount"  ui-number-mask="2" class = "form-control" />
                                        </div>
                                    </div>
                                </div>



                                <div class = "row">
                                    <div class="form-group">
                                        <label for="discount_description" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Description</label>
                                        <div class="col-md-8">
                                            <input
                                            type = "text"
                                               
                                                ng-model = "detail.discount_description"
                                                id="discount_description"
                                                name = "discount_description"
                                                class = "form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" onclick="append_InvoiceDiscount('<?php echo base_url(); ?>index.php/leasing_facilityrental/append_InvoiceDiscount')" class="btn btn-primary"><i class="fa fa-save"></i> Append</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>     

</div> <!-- End of Container -->
</body>

