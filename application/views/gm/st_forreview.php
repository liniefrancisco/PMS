
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Short Term For Review </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="query" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_stforreview')">
                            <thead>
                                <tr>
                                    <th><a href="#" data-ng-click="sortField = 'location_code'; reverse = !reverse">Location Code</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'trade_name'; reverse = !reverse">Trade Name</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'floor_name'; reverse = !reverse">Floor Location</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'contact_person1'; reverse = !reverse">Contact Person</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'status'; reverse = !reverse">Status</a></th>
                                    <th><a href="#" data-ng-click="sortField = 'contact_number'; reverse = !reverse">Request Date</a></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "prospect in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                    <td>{{ prospect.location_code }}</td>
                                    <td>{{ prospect.trade_name }}</td>
                                    <td>{{ prospect.store_name }}</td>
                                    <td>{{ prospect.floor_name }}</td>
                                    <td>{{ prospect.contact_person }}</td>
                                    <td>{{ prospect.status }}</td>
                                    <td>{{ prospect.request_date}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a 
                                                        href="#" 
                                                        data-toggle="modal"
                                                        data-backdrop="static" data-keyboard="false"  
                                                        data-target="#view_modal" 
                                                        ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id)"> 
                                                        <i class = "fa fa-search-plus"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'General Manager') : ?>
                                                    <a 
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/approve_sprospect/' + prospect.id)"> 
                                                        <i class = "fa fa-thumbs-up"></i> Approve Request
                                                    </a>
                                                    <?php endif ?>
                                                </li>
                                                <li>
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'General Manager') : ?>
                                                    <a 
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/deny_sprospect/' + prospect.id)"> 
                                                        <i class = "fa fa-thumbs-down"></i> Deny Request
                                                    </a>
                                                    <?php endif ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                    <td colspan="9"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="ng-cloak">
                                    <td colspan="9" style="padding:5px;">
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
    </div> <!-- END OF WELL DIV  -->



    <!-- View Modal -->
    <div class="modal fade" id = "view_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Prospect's Complete Details</h4>
                </div>
                <div class="modal-body ng-cloak" ng-repeat = "data in viewList">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Trade Name:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.trade_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Corporate Name:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.corporate_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Address:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.address }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Leasee Type:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.leasee_type }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">First Category:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.first_category }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Second Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "!data.category_two"><span class = "details">None</span></div>
                                        <div ng-if = "data.category_two"><span class = "details">{{ data.second_category }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Third Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "!data.category_three"><span class = "details">None</span></div>
                                        <div ng-if = "data.category_three"><span class = "details">{{ data.third_category }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Contact Person</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.contact_person }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Contact Number</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.contact_number }}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Store Location:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.store_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Floor Location:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.floor_name }}</span>
                                    </div>
                                </div>
                            </div>

                           <!--  <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Status:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "data.status == 'Pending'"> <span class = "details red" >{{ data.status }}</span></div>
                                        <div ng-if = "data.status == 'Approved'"> <span class = "details green">{{ data.status }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Prepared By:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.first_name }} {{ data.last_name }}</span>
                                    </div>
                                </div>
                            </div> -->
                        </div> <!-- end of col-md-6 -->
                        <!-- DIVIDER -->
                       
                        <div class="col-md-6">
                            
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Location Code:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{data.location_code}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Area Classification:</label>
                                    <div class="col-md-8">
                                        <span class = "details"> {{ data.area_classification }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Area Type:</label>
                                    <div class="col-md-8">
                                        <span class = "details"> {{ data.area_type }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Rent Period:</label>
                                    <div class="col-md-8">
                                        <span class = "details"> {{ data.rent_period }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Type of Payment:</label>
                                    <div class="col-md-8">
                                        <span class = "details"> {{ data.payment_mode }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Floor Area:</label>
                                    <div class="col-md-8">
                                        <span class = "details"><strong>m<sup>2</sup></strong> {{ data.floor_area | currency: '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Basic Rental:</label>
                                    <div class="col-md-8">
                                        <span class = "details"><strong>&#8369;</strong> {{ data.basic_rental | currency: '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Remarks:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "!data.remarks"> <span class = "details" >None</span></div>
                                        <div ng-if = "data.remarks"> <span class = "details">{{ data.remarks }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Request Date:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.request_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Status:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "data.status == 'Pending'"> <span class = "details red" >{{ data.status }}</span></div>
                                        <div ng-if = "data.status == 'Approved'"> <span class = "details green">{{ data.status }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Prepared By:</label>
                                    <div class="col-md-8">
                                        <span class = "details">{{ data.prepared_by }} </span>
                                    </div>
                                </div>
                            </div> 
                           <!--  <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Approved Date:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "data.approved_date == '0000-00-00'"> <span class = "details" >TBD</span></div>
                                        <div ng-if = "data.approved_date != '0000-00-00'"> <span class = "details">{{ data.approved_date }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Approved By:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "!data.approved_by"> <span class = "details" >TBD</span></div>
                                        <div ng-if = "data.approved_date"> <span class = "details">{{ data.approved_by }}</span></div>
                                    </div>
                                </div>
                            </div> -->
                        </div><!-- endo of col-md-6 -->
                    </div> <!-- end of row -->
                    <hr>
                    <div class="row"> <!-- row for thumbnails -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="title-header"><i class="fa fa-paperclip"></i> Attachments</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Proposal Letter</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Letter of Intent" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_proposal/' + data.id, '<?php echo base_url(); ?>assets/exhibitor_attachements/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Company Profile</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Company Profile" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_excomprof/' + data.id, '<?php echo base_url(); ?>assets/exhibitor_attachements/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Booth Layout</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Booth Layout" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_boothlayout/' + data.id, '<?php echo base_url(); ?>assets/exhibitor_attachements/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 col-md-offset-2">
                                            <div class = "thumbnail-header text-center">Booth Perpspective</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Booth Perpspective" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_experspective/' + data.id, '<?php echo base_url(); ?>assets/exhibitor_attachements/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">DTI Business Registration</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="DTI Business Registration" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_exdti_busireg/' + data.id, '<?php echo base_url(); ?>assets/exhibitor_attachements/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of thumbnails row -->
                </div> <!-- end of modal body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End View Modal -->



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
</div> <!-- End of Container -->
</body>

