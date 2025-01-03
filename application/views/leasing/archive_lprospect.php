<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Revoked Long Term Prospects</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_archive/get_deniedLprospect')">

                            <tbody>
                                <tr class="ng-cloak" ng-repeat="prospect in data">
                                    <td title="'Trade Name'" sortable="'trade_name'">{{ prospect.trade_name }}</td>
                                    <td title="'Corporate Name'" sortable="'corporate_name'">{{ prospect.corporate_name
                                        }}</td>
                                    <td title="'Store/Property'" sortable="'store_name'">{{ prospect.store_name }}</td>
                                    <td title="'Contact Person'" sortable="contact_person''">{{ prospect.contact_person
                                        }}</td>
                                    <td title="'Contact Number'" sortable="'contact_number'">{{ prospect.contact_number
                                        }}</td>
                                    <td title="'Status'" sortable="'status'">
                                        <div ng-if="prospect.status == 'Approved'">
                                            <span class="green"><i class="fa fa-check"></i> {{ prospect.status }}</span>
                                        </div>
                                        <div ng-if="prospect.status != 'Approved'">
                                            <span class="red"> {{ prospect.status }}</span>
                                        </div>
                                    </td>
                                    <td title="'Request Date'" sortable="'request_date'">{{ prospect.request_date}}</td>
                                    <td title="'Remarks'" sortable="'remarks'">{{ prospect.remarks }}</td>
                                    <td title="'Action'" align="center" width="100px">
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-xs btn-danger button-caret">Action</button>
                                            <button type="button"
                                                class="btn btn-xs btn-danger dropdown-toggle button-caret"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" data-toggle="modal" data-target="#view_modal"
                                                        ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id)">
                                                        <i class="fa fa-search-plus"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager'): ?>
                                                        <a data-toggle="modal" data-target="#confirmation1_modal" href="#"
                                                            ng-click="confirm('<?php echo base_url(); ?>index.php/leasing_archive/restore_lprospect/' + prospect.id)">
                                                            <i class="fa fa-recycle green"></i> Restore
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="#" data-toggle="modal"
                                                            ng-click="managers_key('<?php echo base_url(); ?>index.php/leasing_archive/restore_lprospect/' + prospect.id + '/' + prospect.store_name)"
                                                            data-target="#manager_modal">
                                                            <i class="fa fa-recycle green"></i> Restore
                                                        </a>
                                                    <?php endif ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="9">
                                        <center>No Data Available.</center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->






    <div class="modal fade" id="changeImg_modal" style="background:#000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{header}}</h4>
                </div>
                <form action="{{url}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="thumbnail">
                            <img src="{{imgpath}}" id="previewImg">
                            <input type="hidden" name="old_file" value="{{imgpath}}" />
                        </div>
                        <input type="file" required name="img" id="selectedImg" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary button-b"><i class="fa fa-save"></i> Save
                            changes</button>
                        <button type="button" class="btn btn-default button-w" data-dismiss="modal"><i
                                class="fa fa-close"></i>
                            Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="addImg_modal" style="background:#000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{header}}</h4>
                </div>
                <form action="{{url}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="thumbnail">
                            <img src="<?php echo base_url(); ?>img/thumbnail.png" id="add_previewImg">
                        </div>
                        <input type="file" required name="img" id="addImg" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary button-b"><i class="fa fa-save"></i>
                            Submit</button>
                        <button type="button" class="btn btn-default button-w" data-dismiss="modal"><i
                                class="fa fa-close"></i>
                            Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- View Modal -->
    <div class="modal fade" id="view_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Prospect's Complete Details</h4>
                </div>
                <div class="modal-body ng-cloak" ng-repeat="data in viewList">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Trade Name:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.trade_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Corporate Name:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.corporate_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Address:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.address }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Leasee Type:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.leasee_type }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">First Category:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.first_category }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Second Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if="!data.category_two"><span class="details">None</span></div>
                                        <div ng-if="data.category_two"><span class="details">{{ data.second_category
                                                }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Third Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if="!data.category_three"><span class="details">None</span></div>
                                        <div ng-if="data.category_three"><span class="details">{{ data.third_category
                                                }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Contact Person</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.contact_person }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Contact Number</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.contact_number }}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Store Location:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.store_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Floor Location:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.floor_name }}</span>
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
                                        <span class="details">{{data.location_code}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Area Classification:</label>
                                    <div class="col-md-8">
                                        <span class="details"> {{ data.area_classification }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Area Type:</label>
                                    <div class="col-md-8">
                                        <span class="details"> {{ data.area_type }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Rent Period:</label>
                                    <div class="col-md-8">
                                        <span class="details"> {{ data.rent_period }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Mode of Payment:</label>
                                    <div class="col-md-8">
                                        <span class="details"> {{ data.payment_mode }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Floor Area:</label>
                                    <div class="col-md-8">
                                        <span class="details"><strong>m<sup>2</sup></strong> {{ data.floor_area |
                                            currency: '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Basic Rental:</label>
                                    <div class="col-md-8">
                                        <span class="details"><strong>&#8369;</strong> {{ data.basic_rental | currency:
                                            '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Remarks:</label>
                                    <div class="col-md-8">
                                        <div ng-if="!data.remarks"> <span class="details">None</span></div>
                                        <div ng-if="data.remarks"> <span class="details">{{ data.remarks }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Request Date:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.request_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Status:</label>
                                    <div class="col-md-8">

                                        <span class="details red">{{ data.status }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Prepared By:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.prepared_by }} </span>
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
                                            <div class="thumbnail-header text-center">Letter of Intent</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Letter of Intent" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + data.id, '<?php echo base_url(); ?>assets/intent_letter/')"
                                                            class="btn btn-primary btn-block" role="button"> <i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">Company Profile</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Company Profile" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_comprof/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">DTI Business Registration</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="DTI Business Registration" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_dtibusireg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">Pictures/Brochures of Products
                                            </div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Pictures/Brochures of Products" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_brochures/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">Colored Perspective</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Pictures/Brochures of Products" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_perspective/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">Gross Sales(Percentage rent.)
                                            </div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Company Profile" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_gsalesImg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="thumbnail-header text-center">Price/Menu List(for food tenants)
                                            </div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png"
                                                    alt="Company Profile" style="height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_pricemenuList/' + data.id, '<?php echo base_url(); ?>assets/other_img/')"
                                                            class="btn btn-primary btn-block" role="button"><i
                                                                class="glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of thumbnails row -->
                </div> <!-- end of modal body-->
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
                        <div ng-class="{item: true, active : ($index === 0)}" ng-repeat="img in imgList">
                            <img ng-src="{{imgPath}}{{img.file_name}}" alt="Slide numder {{img}}">
                        </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!--End Carousel Modal -->


</div> <!-- End of container -->
</body>