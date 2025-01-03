<div class="container" ng-controller="tableController">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Approved Long Term Prospects
                <a href="<?php echo base_url(); ?>index.php/leasing_transaction/Lcontract_management"
                    class="pull-right back-link"><i class="fa fa-arrow-circle-left"></i>
                    Back</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_approveLprospect')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat="prospect in data">
                                    <td title="'Trade Name'" sortable="'trade_name'">{{ prospect.trade_name }}</td>
                                    <td title="'Corporate Name'" sortable="'corporate_name'">{{ prospect.corporate_name
                                        }}</td>
                                    <td title="'Store/Property'" sortable="'store_name'">{{ prospect.store_name }}</td>
                                    <td title="'Contact Person'" sortable="'contact_person'">{{ prospect.contact_person
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
                                    <td title="'Approved Date'" sortable="'approved_date'">{{ prospect.approved_date }}
                                    </td>
                                    <td title="'Action'" align="center">
                                        <?php if ($this->session->userdata('user_type') != 'Accounting Staff' && $this->session->userdata('user_type') != 'Legal'): ?>
                                            <button class="btn btn-xs btn-info button-caretb" type="button"
                                                data-toggle="modal" data-backdrop="static" data-keyboard="false"
                                                data-target="#view_modal"
                                                ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id)">
                                                <i class="fa fa-file-text-o"></i> Contract Request
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-xs btn-info button-caretb" type="button"
                                                data-toggle="modal" data-backdrop="static" data-keyboard="false"
                                                data-target="#view_modal"
                                                ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id)">
                                                <i class="fa fa-search" aria-hidden="true"></i> View Details
                                            </button>
                                        <?php endif ?>

                                        <!-- <div class="btn-group">
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
                                                        data-backdrop = "static" data-keyboard = "false"
                                                        data-target="#view_modal"
                                                        ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id)">
                                                        <i class = "fa fa-file-text-o"></i> Contract Request
                                                    </a>
                                                </li>
                                                Hidden from accounting users
                                                <?php if ($this->session->userdata('user_type') != 'Accounting Staff'): ?>
                                                    <li>
                                                        <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Supervisor'): ?>
                                                        <a
                                                            data-toggle="modal" data-target="#confirmation1_modal"
                                                            href="#"
                                                            ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/deny_lprospect2/' + prospect.id)">
                                                            <i class = "fa fa-thumbs-down"></i> Deny Request
                                                        </a>
                                                        <?php else: ?>
                                                        <a
                                                            href="#"
                                                            data-toggle="modal"
                                                            ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/deny_lprospect2/' + prospect.id + '/' + prospect.store_name)"
                                                            data-target="#manager_modal">
                                                            <i class = "fa fa-thumbs-down"></i> Deny Request
                                                        </a>
                                                        <?php endif ?>
                                                    </li>

                                                    <li>

                                                        <a
                                                            data-toggle="modal" data-target="#confirmation1_modal"
                                                            href="#"
                                                            ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/create_contract/' + prospect.id + '/' + 'Long Term')">
                                                            <i class = "fa fa-pencil-square"></i> Create Contract
                                                        </a>

                                                    </li>
                                                <?php endif ?>
                                            </ul>

                                        </div> -->
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
    </div> <!-- END OF WELL DIV  -->.

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
                                        <div ng-if="!data.second_category"><span class="details">None</span></div>
                                        <div ng-if="data.second_category"><span class="details">{{ data.second_category
                                                }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Third Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if="!data.third_category"><span class="details">None</span></div>
                                        <div ng-if="data.third_category"><span class="details">{{ data.third_category
                                                }}</span></div>
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
                                    <label class="col-md-4 control-label text-right">Contact Person</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.contact_person }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Email</label>
                                    <div class="col-md-8">
                                        <span ng-if="data.email != '' || data.email != null" class="details">{{
                                            data.email }}</span>
                                        <span ng-if="data.email == '' || data.email == null" class="details">None</span>
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
                                    <label class="col-md-4 control-label text-right">Remarks:</label>
                                    <div class="col-md-8">
                                        <div ng-if="!data.remarks"> <span class="details">None</span></div>
                                        <div ng-if="data.remarks"> <span class="details">{{ data.remarks }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Approved Date:</label>
                                    <div class="col-md-8">
                                        <span class="details">{{ data.approved_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Status:</label>
                                    <div class="col-md-8">
                                        <div ng-if="data.status == 'Pending'"> <span class="details red">{{ data.status
                                                }}</span></div>
                                        <div ng-if="data.status == 'Approved'"> <span class="details green">{{
                                                data.status }}</span></div>
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
                                    <div class="container-fluid">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Files</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Letter of Intent</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + data.id, '<?php echo base_url(); ?>assets/intent_letter/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Company Profile</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_comprof/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>DTI Business Registration</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_dtibusireg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Picture/Brochures of Products</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_brochures/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Colored Perspective</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_perspective/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Gross Sales(Percentage rent.)</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_gsalesImg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Price/Menu List(for food tenants)</td>
                                                    <td><button class="btn btn-sm button-w" data-toggle="modal"
                                                            data-target="#carousel_modal"
                                                            ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_pricemenuList/' + data.id, '<?php echo base_url(); ?>assets/other_img/')">View</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- <div class="row">
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Letter of Intent</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Letter of Intent" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + data.id, '<?php echo base_url(); ?>assets/intent_letter/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Company Profile</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Company Profile" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_comprof/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">DTI Business Registration</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="DTI Business Registration" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_dtibusireg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Pictures/Brochures of Products</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Pictures/Brochures of Products" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_brochures/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Colored Perspective</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Pictures/Brochures of Products" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_perspective/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Gross Sales(Percentage rent.)</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Company Profile" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_gsalesImg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class = "thumbnail-header text-center">Price/Menu List(for food tenants)</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Company Profile" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_pricemenuList/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"><i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div> <!-- end of thumbnails row -->
                    <div class="modal-footer">
                        <!-- Hidden from accounting users -->
                        <?php if ($this->session->userdata('user_type') != 'Accounting Staff' && $this->session->userdata('user_type') != 'Legal'): ?>
                            <button type="button" class="btn btn-alert button-w" data-dismiss="modal" href="#"
                                data-toggle="modal"
                                ng-click="confirm('<?php echo base_url(); ?>index.php/leasing_transaction/create_contract/' + data.id + '/' + 'Long Term')"
                                data-target="#confirmation1_modal">
                                <i class="fa fa-pencil-square"></i> Create Contract
                            </button>

                            <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Supervisor'): ?>

                                <button type="button" class="btn btn-alert button-w" data-dismiss="modal" data-toggle="modal"
                                    data-target="#confirmation1_modal" href="#"
                                    ng-click="confirm('<?php echo base_url(); ?>index.php/leasing_transaction/deny_lprospect2/' + data.id)">
                                    <i class="fa fa-thumbs-down"></i> Revoke
                                </button>

                            <?php else: ?>

                                <button type="button" class="btn btn-alert button-w" data-dismiss="modal" href="#"
                                    data-toggle="modal"
                                    ng-click="decline_remarks(data.id, data.store_name, data.trade_name, '<?php echo base_url(); ?>index.php/leasing_transaction/deny_lprospect2_decline/')"
                                    data-target="#remarks_modal">
                                    <i class="fa fa-thumbs-down"></i> Revoke
                                </button>

                            <?php endif ?>
                        <?php endif ?>

                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                class="fa fa-close"></i>
                            Close</button>
                    </div>
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
                        <!-- Set as default active item to avoid bootsrap error -->
                        <div ng-if="imgList.length == 0" class="item active">
                            <img src="<?php echo base_url(); ?>img/thumbnail1.png" alt="Default">
                        </div>

                        <div ng-class="{item: true, 'active' : ($index === 0)}" ng-repeat="img in imgList">
                            <img ng-src="{{imgPath}}{{img.file_name}}" alt="Slide number {{img}}">
                        </div>
                        <!-- Controls -->
                        <a ng-if="imgList.length > 1" class="left carousel-control" href="#carousel-example-generic"
                            role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a ng-if="imgList.length > 1" class="right carousel-control" href="#carousel-example-generic"
                            role="button" data-slide="next">
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