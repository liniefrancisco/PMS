
<div class="container" ng-controller="FacilityRentalController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Facility Rental </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" style = "height:400px; text-align:center;">
                        <div id ="calendar" style="max-width: 1100px; max-height: 385px; margin: 0 auto;   margin: 10px 10px; padding: 0;  font-size: 14px;"></div>
                    </div>
                    <div class="col-md-4 pull-left">
                
                    </div>
                    <div class="col-md-2 pull-left">
                        <a href="#" data-toggle="modal" data-target="#add_frschedule" ng-click="populate_facilityrentalcustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/populate_facilityrentalcustomer/'); clear_frcustomerdata()" data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Reserve A Schedule</a>
                    </div>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-toggle="modal" data-target="#reserve_list" ng-click="" data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-medium"><i class = "fa fa-list"></i> Reservation List</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

     <!-- Add Data Modal -->
     <div class="modal fade" id = "fr_calendardatetimemodal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Reserve Schedule </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">
                <form action="#"   enctype="multipart/form-data" id = "frm_frcalendardatetime" name="frm_frcalendardatetime">
                    <div class="row">
                        <div class="form-group">
                            <label for="reserve_date" class="col-md-3 control-label text-right">Date: </label>
                            <div class="col-md-3">
                                <input
                                    type="date"
                                    required
                                    class="form-control"
                                    readonly
                                    id="frreserve_date_calendar"
                                    name = "frreserve_date_calendar"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <br>
                        <div class="form-group">                              
                            <div class="col-md-12">
                            <label for="" class="col-md-12 control-label text-center" style = "font-size:16px;">Time</label>
                            </div>                                   
                        </div>

                        <div class="form-group">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">                              
                                <table class="table table-bordered text-center" ng-table = "tableParams" ng-controller="FacilityRentalController">
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <tbody>
                                        <tr>
                                            <td id="td700-800AM_calendar" class="tdclass_calendar">
                                                7:00-8:00 AM
                                            </td>
                                            <td id="td800-900AM_calendar" class="tdclass_calendar">
                                                8:00-9:00 AM
                                            </td>
                                            <td id="td900-1000AM_calendar" class="tdclass_calendar">
                                                9:00-10:00 AM
                                            </td>
                                            <td id="td1000-1100AM_calendar" class="tdclass_calendar">
                                                10:00-11:00 AM
                                            </td>
                                            <td id="td1100-1200PM_calendar" class="tdclass_calendar">
                                               11:00-12:00 PM
                                            </td>
                                            <td id="td1200-100PM_calendar" class="tdclass_calendar">
                                               12:00-1:00 PM
                                            </td>
                                            <td id="td100-200PM_calendar" class="tdclass_calendar">
                                               1:00-2:00 PM
                                            </td>
                                            <td id="td200-300PM_calendar" class="tdclass_calendar">
                                                2:00-3:00 PM
                                            </td>
                                        </tr>
                                        <tr>
                                            <td id="td300-400PM_calendar" class="tdclass_calendar">
                                                3:00-4:00 PM
                                            </td>
                                            <td id="td400-500PM_calendar" class="tdclass_calendar">
                                                 4:00-5:00 PM
                                            </td>
                                            <td id="td500-600PM_calendar" class="tdclass_calendar">
                                               5:00-6:00 PM
                                            </td>
                                            <td id="td600-700PM_calendar" class="tdclass_calendar">
                                                6:00-7:00 PM
                                            </td>
                                            <td id="td700-800PM_calendar" class="tdclass_calendar">
                                                7:00-8:00 PM
                                            </td>
                                            <td id="td800-900PM_calendar" class="tdclass_calendar">
                                                8:00-9:00 PM
                                            </td>
                                            <td id="td900-1000PM_calendar" class="tdclass_calendar">
                                                9:00-10:00 PM
                                            </td>       
                                            <td></td>                              
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-1"></div>                                     
                        </div>   
                    </div>
                </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <!-- Add Data Modal -->
    <div class="modal fade" id = "reserve_list" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Reservation List</h4>
                </div>
                
                        <div class="modal-body" style="padding-right:30px;">
                            <div class="row">
                            <div class="col-md-3 pull-right">
                                <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="container-fluid">                           
                                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController"  ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_reservationtable')"> 
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
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                        <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a id="{{reserve.facilityrental_no}}" href="#" data-toggle="modal"  onclick="cancel_reservation_modal(this.id)"> <i class = "fa fa-trash"></i>Cancel Reservation</a></li>
                                                        </ul>
                                                    </div>
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

    <!-- Add Schedule Modal -->
    <div class="modal fade" id = "add_frschedule" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Schedule</h4>
                </div>
                <form action="#"   enctype="multipart/form-data" id = "frm_addfacilityrental" name="frm_addfacilityrental">
                    <div class="modal-body" style="padding-right:30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- nav menu -->
                                <ul class="nav nav-tabs" role="tablist"> 
                                <li role="presentation" class="active"><a href="#add_frschedulenav" aria-controls="add_frschedulenav" role="tab" data-toggle="tab">Add Schedule</a></li>
                                <li role="presentation" class=""><a href="#add_frdatanav" aria-controls="add_frdatanav" role="tab" data-toggle="tab" >Add Data</a></li>
                                </ul>
                                 <!-- nav menu end-->
                                <br>
                                <!-- TAB CONTENT -->
                                <div class="tab-content">
                                    <!-- ADD SCHEDULE TAB -->
                                    <div class="tab-pane active" role="tabpanel"  id="add_frschedulenav">
                                        <div class="form-group">                              
                                            <div class="col-md-12">
                                                <label for="trade_name" class="col-md-12 control-label text-center" style = "font-size:16px;">Requested Schedule</label>
                                            </div>                                   
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">                              
                                                <table class="table table-bordered text-center" ng-table = "tableParams" ng-controller="FacilityRentalController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_tmpreserve')">
                                                    <tbody>
                                                    <tr class="ng-cloak" ng-repeat= "type in data">
                                                        <td width="20%" title = "'Date'" sortable = "''">{{ type.tmp_date}}</td>
                                                        <td width="65%" title = "'Time'" sortable = "''">{{ type.tmp_time}}</td>
                                                        <td>
                                                        <!-- Split button -->
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                                <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span class="caret"></span>
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete_tmpreservation('<?php echo base_url(); ?>index.php/leasing_facilityrental/delete_tmpreservation/' + type.tmp_date)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>    
                                                    </tr>
                                                    <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                                        <td colspan="5"><center>No Data Available.</center></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-2"></div>                                     
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align:center;"><a href="#" data-toggle="modal" data-target="#fr_scheduledatetimemodal" ng-click="" data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-medium"> Add Schedule</a> </div>
                                        </div>                                                                          
                                                       
                                    </div>
                                    <!-- ADD SCHEDULE TAB END -->
                                    <!-- ADD DATA TAB  -->
                                    <div role="tabpanel" class="tab-pane" id="add_frdatanav">
                                  
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="row" style="height:25px;">
                                                            <div class="col-md-3">
                                                                Customer Details
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12">    
                                                            <div class="row">
                                                                <div class = "col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group"> 
                                                                            <label for="contract_no" class="col-md-4 control-label text-right">Facility Rental No.</label>
                                                                            <div class="col-md-8">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required
                                                                                    readonly 
                                                                                    class="form-control"
                                                                                    id="frno"
                                                                                    name="frno"
                                                                                    autocomplete="off"
                                                                                    value='<?php echo $frno; ?>'
                                                                                    >
                                                                            </div>                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class = "col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group"> 
                                                                            <label for="contract_no" class="col-md-4 control-label text-right">Contact Person</label>
                                                                            <div class="col-md-8">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required
                                                                                    readonly 
                                                                                    class="form-control"
                                                                                    id="frcontactperson"
                                                                                    name="frcontactperson"
                                                                                    ng-model = "frcontactperson"
                                                                                    autocomplete="off">
                                                                            </div>                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">    
                                                            <div class="row">
                                                                <div class = "col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group"> 
                                                                            <label for="contract_no" class="col-md-4 control-label text-right">Customer Name</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <div mass-autocomplete>
                                                                                        <input 
                                                                                        type="text" 
                                                                                        required
                                                                                        ng-model = "dirty.value"
                                                                                        mass-autocomplete-item="autocomplete_options"
                                                                                        class="form-control"
                                                                                        id="frcustomername"
                                                                                        name="frcustomername"
                                                                                        autocomplete="off">
                                                                                    </div>
                                                                                    <span class="input-group-btn">
                                                                                        <a 
                                                                                        href="#" 
                                                                                        data-toggle="modal" 
                                                                                        data-target="#add_frcustomer" 
                                                                                        ng-click="" 
                                                                                        data-backdrop="static" 
                                                                                        data-keyboard="false" 
                                                                                        class = "btn btn-success btn-medium">
                                                                                        <i class = "fa fa-plus-circle"></i></a>
                                                                                    </span>
                                                                                    <span class="input-group-btn">
                                                                                        <button
                                                                                            class = "btn btn-info"
                                                                                            type = "button"
                                                                                            ng-click = "generate_frcustomerdetails(dirty.value,'<?php echo base_url() ?>index.php/leasing_facilityrental/generate_frcustomerdetails/')"><i class = "fa fa-search"></i>
                                                                                        </button>
                                                                                    </span>  
                                                                                </div>
                                                                            </div>                                             
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class = "col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group"> 
                                                                            <label for="contract_no" class="col-md-4 control-label text-right">Contact Number</label>
                                                                            <div class="col-md-8">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required
                                                                                    readonly        
                                                                                    class="form-control"
                                                                                    id="frcontactnumber"
                                                                                    name="frcontactnumber"
                                                                                    ng-model = "frcontactnumber"
                                                                                    autocomplete="off">
                                                                            </div>                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">    
                                                            <div class="row">
                                                                <div class = "col-md-6">
                                                                    <div class="row">
                                                                        <div class="form-group"> 
                                                                            <label for="contract_no" class="col-md-4 control-label text-right">Address</label>
                                                                            <div class="col-md-8">
                                                                                <input 
                                                                                    type="text" 
                                                                                    required
                                                                                    readonly 
                                                                                    class="form-control"
                                                                                    id="fraddress"
                                                                                    name="fraddress"
                                                                                    ng-model = "fraddress"
                                                                                    autocomplete="off">
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
                                        
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="row" style="height:25px;">
                                                            <div class="col-md-3">
                                                                Facility Requested
                                                            </div>
                                                            <div class="col-md-9">
                                                                <span class="input-group-btn pull-left">
                                                                    <a 
                                                                    href="#" 
                                                                    data-toggle="modal" 
                                                                    data-target="#add_frfacility" 
                                                                    ng-click="" 
                                                                    data-backdrop="static" 
                                                                    data-keyboard="false" 
                                                                    class = "btn btn-success btn-medium">
                                                                    <i class = "fa fa-plus-circle"></i> Add Facilities</a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <div class="row" id="facilityrequestdiv">
                                                            <?php foreach ($facilities as $facility): ?>
                                                                <input type='checkbox' name='RequestedFacilities' id='<?php echo $facility['id']; ?>' class='RequestedFacilities' value='<?php echo $facility['FacilityRental_rate']; ?>' onclick = "check_facilityrequested(this);"> <label><?php echo $facility['facility_name']; ?></label>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <?php endforeach ?>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <label for="contract_no" class="col-md-2 control-label text-left">Hours of Usage:</label>
                                                                <div class="col-md-1">
                                                                    <input 
                                                                        type="text" 
                                                                        value=<?php echo $hours_reserved;  ?>
                                                                        required
                                                                        readonly
                                                                        class="form-control"
                                                                        id="frhoursusage"
                                                                        name="frhoursusage"
                                                                        style="text-align:center;"
                                                                        autocomplete="off">
                                                                </div>  
                                                                <div class="col-md-1">
                                                                    <input 
                                                                        type="text" 
                                                                        value="00"
                                                                        required
                                                                        readonly
                                                                        class="form-control"
                                                                        id="frminutesusage"
                                                                        name="frminutesusage"
                                                                        style="text-align:center;"
                                                                        autocomplete="off">
                                                                </div>  
                                                                <label for="contract_no" class="col-md-2 control-label text-left">Rental Price:</label>
                                                                <div class="col-md-3">
                                                                    <input 
                                                                        type="text"
                                                                        required
                                                                        readonly
                                                                        style="text-align:right"
                                                                        class="form-control"
                                                                        id="frrentalprice"
                                                                        name="frrentalprice"
                                                                        value="0.00"
                                                                    
                                                                        autocomplete="off">
                                                                </div>  
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="row">
                                               <div class="panel panel-default">
                                                    <!-- Default panel contents -->
                                                    <div class="panel-heading panel-leasing"><i class="fa fa-image"></i> Approved Intent Letter </div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <input type="file" id="frapprovedintentletter" name = "frapprovedintentletter[]"  multiple="multiple" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- ADD DATA TAB END -->
                                    </div>
                                <!-- TAB CONTENT END -->
                                </div>                                                             
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id ="add_FacilityRentalTransactionBtn" type="button" onclick="add_FacilityRentalTransaction('<?php echo base_url(); ?>index.php/leasing_facilityrental/add_FacilityRentalTransaction/','<?php echo base_url(); ?>index.php/leasing_facilityrental/add_FacilityRentalTransactionFile/')" class="btn btn-primary"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

     <!-- Add Data Modal -->
     <div class="modal fade" id = "fr_scheduledatetimemodal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Reserve Schedule </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">
                <form action="#"   enctype="multipart/form-data" id = "frm_frscheduledatetime" name="frm_frscheduledatetime">
                    <div class="row">
                        <div class="form-group">
                            <label for="reserve_date" class="col-md-3 control-label text-right">Select Date: </label>
                            <div class="col-md-3">
                                <input
                                    type="date"
                                    required
                                    class="form-control"
                                    onchange="disable_reserve_time('<?php echo base_url(); ?>index.php/leasing_facilityrental/disable_reserve_time/','<?php echo base_url(); ?>index.php/leasing_facilityrental/disable_tmpreserve_time/')"
                                    id="frreserve_date"
                                    name = "frreserve_date"
                                    autocomplete="off"
                                >
                                <!-- FOR ERRORS -->
                                <div class="validation-Error">
                                    <span ng-show="frm_update.trade_name.$dirty && frm_update.trade_name.$error.required">
                                        <p class="error-display">This field is required.</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">                              
                            <div class="col-md-12">
                            <label for="trade_name" class="col-md-12 control-label text-center" style = "font-size:16px;">Select Time</label>
                            </div>                                   
                        </div>

                        <div class="form-group">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">                              
                                <table class="table table-bordered text-center" ng-table = "tableParams" ng-controller="FacilityRentalController">
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <th width="12%"></th>
                                    <tbody>
                                        <tr>
                                            <td id="td700-800AM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_700-800AM' class='frtimecheck_box' value='7:00-8:00AM' onclick="check_frtimecheck_box(this)"> 7:00-8:00 AM</td>
                                            <td id="td800-900AM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_800-900AM' class='frtimecheck_box' value='8:00-9:00AM'  onclick="check_frtimecheck_box(this)"> 8:00-9:00 AM</td>
                                            <td id="td900-1000AM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_900-1000AM' class='frtimecheck_box' value='9:00-10:00AM'  onclick="check_frtimecheck_box(this)"> 9:00-10:00 AM</td>
                                            <td id="td1000-1100AM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_1000-1100AM' class='frtimecheck_box' value='10:00-11:00AM'  onclick="check_frtimecheck_box(this)"> 10:00-11:00 AM</td>
                                            <td id="td1100-1200PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_1100-1200PM' class='frtimecheck_box' value='11:00-12:00PM'  onclick="check_frtimecheck_box(this)"> 11:00-12:00 PM</td>
                                            <td id="td1200-100PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_1200-100PM' class='frtimecheck_box' value='12:00-1:00PM'  onclick="check_frtimecheck_box(this)"> 12:00-1:00 PM</td>
                                            <td id="td100-200PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_100-200PM' class='frtimecheck_box' value='1:00-2:00PM'  onclick="check_frtimecheck_box(this)"> 1:00-2:00 PM</td>
                                            <td id="td200-300PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_200-300PM' class='frtimecheck_box' value='2:00-3:00PM'  onclick="check_frtimecheck_box(this)"> 2:00-3:00 PM</td>
                                        </tr>
                                        <tr>
                                            <td id="td300-400PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_300-400PM' class='frtimecheck_box' value='3:00-4:00PM' onclick="check_frtimecheck_box(this)">  3:00-4:00 PM</td>
                                            <td id="td400-500PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_400-500PM' class='frtimecheck_box' value='4:00-5:00PM'  onclick="check_frtimecheck_box(this)"> 4:00-5:00 PM</td>
                                            <td id="td500-600PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_500-600PM' class='frtimecheck_box' value='5:00-6:00PM'  onclick="check_frtimecheck_box(this)"> 5:00-6:00 PM</td>
                                            <td id="td600-700PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_600-700PM' class='frtimecheck_box' value='6:00-7:00PM'  onclick="check_frtimecheck_box(this)"> 6:00-7:00 PM</td>
                                            <td id="td700-800PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_700-800PM' class='frtimecheck_box' value='7:00-8:00PM'  onclick="check_frtimecheck_box(this)"> 7:00-8:00 PM</td>
                                            <td id="td800-900PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_800-900PM' class='frtimecheck_box' value='8:00-9:00PM'  onclick="check_frtimecheck_box(this)"> 8:00-9:00 PM</td>
                                            <td id="td900-1000PM" class="tdclass"><input type='checkbox' name='frtimecheck_box' id='frtimecheck_box_900-1000PM' class='frtimecheck_box' value='9:00-10:00PM' onclick="check_frtimecheck_box(this)"> 9:00-10:00 PM</td>       
                                            <td></td>                              
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-1"></div>                                     
                        </div>   
                    </div>
                </form>
                </div>

                <div class="modal-footer">
                    <button id="add_frtmpreservedatetimeBtn" type="button" onclick="add_frtmpreservedatetime('<?php echo base_url(); ?>index.php/leasing_facilityrental/add_frtmpreservedatetime/')" class="btn btn-primary"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

     <!-- Add Data Modal -->
    <div class="modal fade" id = "add_frcustomer" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Customer </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">

                    <form action="#"   enctype="multipart/form-data" id = "frm_addcustomer" name="frm_addcustomer">
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Customer Name</label>
                                                <div class="col-md-8">                                              
                                                    <input 
                                                    type="text" 
                                                    required
                                              
                                                    class="form-control"
                                                    id="fr_addcustomername"
                                                    name="fr_addcustomername"
                                                    autocomplete="off">                                         
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Contact Person</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                
                                                        class="form-control"
                                                        id="fr_addcustomercontactperson"
                                                        name="fr_addcustomercontactperson"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Contact Number</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="number" 
                                                        required
                                                        
                                                        class="form-control"
                                                        id="fr_addcustomercontactnum"
                                                        name="fr_addcustomercontactnum"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Address</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                
                                                        class="form-control"
                                                        id="fr_addcustomeraddress"
                                                        name="fr_addcustomeraddress"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>          
                                <button type="button" onclick="add_FacilityRentalCustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/add_FacilityRentalCustomer/')" class="btn btn-primary pull-right" id="add_FacilityRentalCustomerbtn"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="container-fluid">                           
                                    <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_customertable')">
                                        <tbody>
                                            <tr class="ng-cloak" ng-repeat= "type in data">
                                                <td title = "'Customer Name'" sortable = "'FacilityRental_Cusname'">{{ type.FacilityRental_Cusname}}</td>
                                                <td title = "'Address'" sortable = "'FacilityRental_CustomerAddress'">{{ type.FacilityRental_CustomerAddress }}</td>
                                                <td title = "'Contact Person'" sortable = "'FacilityRental_ContactPerson'">{{ type.FacilityRental_ContactPerson }}</td>
                                                <td title = "'Contact Number'" sortable = "'FacilityRental_ContactNumber'">{{ type.FacilityRental_ContactNumber }}</td>
                                                <td>
                                                <!-- Split button -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                        <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_frcustomer_id/' + type.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                            <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="deletefrCustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/delete_frcustomer/' + type.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
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

                <div class="modal-footer">
                    
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <div class="modal fade" id = "update_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Customer Details </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;" ng-repeat = "data in updateData">
                    <form action="#"   enctype="multipart/form-data" id = "frm_updatecustomer" name="frm_updatecustomer">
                        <input class="hidden" type="text" id="frcus_id" name="frcus_id" ng-model = "data.id">
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Customer Name</label>
                                                <div class="col-md-8">                                              
                                                    <input 
                                                    type="text" 
                                                    required
                                                    ng-model = "data.FacilityRental_Cusname"
                                                    class="form-control"
                                                    id="fr_updatecustomername"
                                                    name="fr_updatecustomername"
                                                    autocomplete="off">                                         
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Contact Person</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        ng-model = "data.FacilityRental_ContactPerson"
                                                        class="form-control"
                                                        id="fr_updatecustomercontactperson"
                                                        name="fr_updatecustomercontactperson"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Contact Number</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        ng-model = "data.FacilityRental_ContactNumber"
                                                        class="form-control"
                                                        id="fr_updatecustomercontactnum"
                                                        name="fr_updatecustomercontactnum"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Address</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        ng-model = "data.FacilityRental_CustomerAddress"
                                                        class="form-control"
                                                        id="fr_updatecustomeraddress"
                                                        name="fr_updatecustomeraddress"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>          
                                <button type="button" onclick="update_FacilityRentalCustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/update_FacilityRentalCustomer/')" class="btn btn-primary pull-right" id="update_FacilityRentalCustomerbtn"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">                   
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

   <!-- Add Data Modal -->
   <div class="modal fade" id = "add_frfacility" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Facility </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;">

                    <form action="#"   enctype="multipart/form-data" id = "frm_fraddfacility" name="frm_fraddfacility">
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Facility Name</label>
                                                <div class="col-md-8">                                              
                                                    <input 
                                                    type="text" 
                                                    required
                                              
                                                    class="form-control"
                                                    id="fr_facilityname"
                                                    name="fr_facilityname"
                                                    autocomplete="off">                                         
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Description</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required

                                                        class="form-control"
                                                        id="fr_facilitydescription"
                                                        name="fr_facilitydescription"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Facility Rate</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="number" 
                                                        required
                                                        style="text-align:right;"
                                                        class="form-control"
                                                        id="fr_facilityrate"
                                                        name="fr_facilityrate"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>          
                                <button type="button" onclick="add_FacilityRentalFacility('<?php echo base_url(); ?>index.php/leasing_facilityrental/add_FacilityRentalFacility/')" class="btn btn-primary pull-right" id="add_FacilityRentalFacilitybtn"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="container-fluid">                           
                                    <table class="table table-bordered" ng-table = "tableParams" ng-controller="FacilityRentalController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_facilitytable')">
                                        <tbody>
                                            <tr class="ng-cloak" ng-repeat= "type in data">
                                                <td title = "'Facility Name'" sortable = "'facility_name'">{{ type.facility_name}}</td>
                                                <td title = "'Description'" sortable = "'description'">{{ type.description }}</td>
                                                <td title = "'Rate'" sortable = "'FacilityRental_rate'">{{ type.FacilityRental_rate }}</td>
                                   
                                                <td>
                                                <!-- Split button -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-danger">Action</button>
                                                        <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_facility_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_frfacility_id/' + type.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                            <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="deletefrFacility('<?php echo base_url(); ?>index.php/leasing_facilityrental/delete_frFacility/' + type.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
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

                <div class="modal-footer">
                    
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <div class="modal fade" id = "update_facility_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Facility Details </h4>
                </div>
                
                <div class="modal-body" style="padding-right:30px;" ng-repeat = "data in updateData">
                    <form action="#"   enctype="multipart/form-data" id = "frm_updatefacility" name="frm_updatefacility">
                        <input class="hidden" type="text" id="frfacility_id" name="frfacility_id" ng-model = "data.id">
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Facility Name</label>
                                                <div class="col-md-8">                                              
                                                    <input 
                                                    type="text" 
                                                    required
                                                    ng-model = "data.facility_name"
                                                    class="form-control"
                                                    id="fr_updatefacilityname"
                                                    name="fr_updatefacilityname"
                                                    autocomplete="off">                                         
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Descrption</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        ng-model = "data.description"
                                                        class="form-control"
                                                        id="fr_updatefacilitydescription"
                                                        name="fr_updatefacilitydescription"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">    
                                <div class="row">
                                    <div class = "col-md-6">
                                        <div class="row">
                                            <div class="form-group"> 
                                                <label for="contract_no" class="col-md-4 control-label text-right">Facility Rate</label>
                                                <div class="col-md-8">
                                                    <input 
                                                        type="text" 
                                                        required
                                                        ng-model = "data.FacilityRental_rate"
                                                        class="form-control"
                                                        id="fr_updatefacilityrate"
                                                        name="fr_updatefacilityrate"
                                                        style="text-align:right;"
                                                        autocomplete="off">
                                                </div>                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>          
                                <button type="button" onclick="update_FacilityRentalFacility('<?php echo base_url(); ?>index.php/leasing_facilityrental/update_FacilityRentalFacility/')" class="btn btn-primary pull-right" id="update_FacilityRentalFacilitybtn"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">                   
                </div>
              
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

    <!-- Manager's Key Modal -->
<div class="modal fade" id = "managerkey_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
            </div>
                <div class="modal-body">
                <form action="#"  method="post" id="frm_managerKey" name = "frm_managerKey">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-addon squared"><i class = "fa fa-user"></i></div>
                                                <input 
                                                    type="text" 
                                                    required
                                                    class="form-control" 
                                                    id="username_key"
                                                    name = "username_key"
                                                    autocomplete="off" 
                                                >
                                                <input id="managerkey_frno" name = "managerkey_frno" type="hidden">
                                         </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-addon squared"><i class = "fa fa-key"></i></div>
                                                <input 
                                                    type="password" 
                                                    required
                                                    class="form-control" 
                                                    id="password_key"
                                                    name = "password_key"
                                                    autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                             
                    </div>
                </form>
                </div><!-- /.modal-content -->
                <div class="modal-footer">
                    <button type="button" onclick="cancel_reservation('<?php echo base_url(); ?>index.php/leasing_facilityrental/cancel_reservation/')" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
                    <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Tag as Modal -->


</div> <!-- End of Container -->
</body>



<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },
    
 
      selectable: true,

      select: function(arg) {
        var date = arg.startStr;
        $('.tdclass_calendar').removeAttr('style');

        //DISABLE RESERVED TIME
        $.ajax({
            type:'POST',
            url:'<?php echo base_url(); ?>index.php/leasing_facilityrental/get_reserved_datetimecalendar/',
            data:{date:date},
            success:function(data)
            {
                $('#frreserve_date_calendar').val(date);

                var data = $.parseJSON(data);  
                var count = data.length;
            
                for(var index = 0; index<=count-1; index++)
                {
                    $('#td'+data[index].time.replace(/:/g, '')+'_calendar').css({ 'background-color': '#b3645f' });

                    //Disable date after start time
                    if(data[index].time.replace(/:/g, '') == '700-800AM')
                    {
                        $('#td800-900AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '800-900AM')
                    {
                        $('#td900-1000AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '900-1000AM')
                    {
                        $('#td1000-1100AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '1000-1100AM')
                    {
                        $('#td1100-1200PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '1100-1200PM')
                    {
                        $('#td1200-100PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '1200-100PM')
                    {
                        $('#td100-200PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '100-200PM')
                    {
                        $('#td200-300PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '200-300PM')
                    {
                        $('#td300-400PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '300-400PM')
                    {
                        $('#td400-500PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '400-500PM')
                    {
                        $('#td500-600PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '500-600PM')
                    {
                        $('#td600-700PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '600-700PM')
                    {
                        $('#td700-800PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '700-800PM')
                    {
                        $('#td800-900PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].time.replace(/:/g, '') == '800-900PM')
                    {
                        $('#td900-1000PM_calendar').css({ 'background-color': '#838a85' });
                    }
                }

                $('#fr_calendardatetimemodal').modal('show');
            },
            error: function (e, status)
            {
                alert('Connection Error.');
            }
        });

        //DISABLE TEMPORARY RESERVATION
        $.ajax({
            type:'POST',
            url:'<?php echo base_url(); ?>index.php/leasing_facilityrental/get_tmpreserved_datetimecalendar/',
            data:{date:date},
            success:function(data)
            {
                $('#frreserve_date_calendar').val(date);

                var data = $.parseJSON(data);  
                var count = data.length;
            
                for(var index = 0; index<=count-1; index++)
                {
                    $('#td'+data[index].tmp_time.replace(/:/g, '')+'_calendar').css({ 'background-color': '#b3645f' });

                    //Disable date after start time
                    if(data[index].tmp_time.replace(/:/g, '') == '700-800AM')
                    {
                        $('#td800-900AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '800-900AM')
                    {
                        $('#td900-1000AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '900-1000AM')
                    {
                        $('#td1000-1100AM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '1000-1100AM')
                    {
                        $('#td1100-1200PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '1100-1200PM')
                    {
                        $('#td1200-100PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '1200-100PM')
                    {
                        $('#td100-200PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '100-200PM')
                    {
                        $('#td200-300PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '200-300PM')
                    {
                        $('#td300-400PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '300-400PM')
                    {
                        $('#td400-500PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '400-500PM')
                    {
                        $('#td500-600PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '500-600PM')
                    {
                        $('#td600-700PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '600-700PM')
                    {
                        $('#td700-800PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '700-800PM')
                    {
                        $('#td800-900PM_calendar').css({ 'background-color': '#838a85' });
                    }
                    else if(data[index].tmp_time.replace(/:/g, '') == '800-900PM')
                    {
                        $('#td900-1000PM_calendar').css({ 'background-color': '#838a85' });
                    }
                }

                $('#fr_calendardatetimemodal').modal('show');
            },
            error: function (e, status)
            {
                alert('Connection Error.');
            }
        });
      }
    });

    calendar.render();
  });

</script>