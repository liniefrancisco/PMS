
<div class="container" ng-controller="tableController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Long Term Prospects </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" ng-keydown = "currentPage = 0" />
                    </div>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-toggle="modal" data-target="#add_data" ng-click="rentalInc()" data-backdrop="static" data-keyboard="false" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_lprospect')">
                            
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "prospect in data">
                                    <td title = "'Trade Name'" sortable = "'trade_name'">{{ prospect.trade_name }}</td>
                                    <td title = "'Corporate Name'" sortable = "'corporate_name'">{{ prospect.corporate_name }}</td>
                                    <td title = "'store_name'" sortable = "'store_name'">{{ prospect.store_name }}</td>
                                    <td title = "'Contact Person'" sortable = "'contact_person'">{{ prospect.contact_person }}</td>
                                    <td title = "'Contact Number'" sortable = "'contact_number'">{{ prospect.contact_number }}</td>
                                    <td title = "'Status'" sortable = "'status'">
                                        <div ng-if="prospect.status == 'Approved'">
                                            <span class="green"><i class = "fa fa-check"></i> {{ prospect.status }}</span>
                                        </div>
                                        <div ng-if="prospect.status != 'Approved'">
                                            <span class="red"> {{ prospect.status }}</span>
                                        </div>
                                    </td>
                                    <td title = "'Request Date'" sortable = "'request_date'">{{ prospect.request_date}}</td>
                                    <td title = "'Action'">
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
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Supervisor' ) : ?>
                                                    <a
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/approve_prospect/' + prospect.id)">
                                                        <i class = "fa fa-thumbs-up"></i> Approve Request
                                                    </a>
                                                    <a
                                                        ng-show = "prospect.status == 'Pending'"
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/gm_review/' + prospect.id)">
                                                        <i class = "fa fa-share"></i> For GM Review
                                                    </a>
                                                    <?php else: ?>
                                                    <a
                                                        href="#"
                                                        data-toggle="modal"
                                                        ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/approve_prospect/' + prospect.id + '/' + prospect.store_name)"
                                                        data-target="#manager_modal">
                                                        <i class = "fa fa-thumbs-up"></i> Approve Request
                                                    </a>
                                                    <?php endif ?>
                                                </li>
                                                <li>
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Store Manager') : ?>
                                                    <a
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/deny_prospect/' + prospect.id)">
                                                        <i class = "fa fa-thumbs-down"></i> Deny Request
                                                    </a>
                                                    <?php else: ?>
                                                    <a
                                                        href="#"
                                                        data-toggle="modal"
                                                        ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/deny_prospect/' + prospect.id + '/' + prospect.store_name)"
                                                        data-target="#manager_modal">
                                                        <i class = "fa fa-thumbs-down"></i> Deny Request
                                                    </a>
                                                    <?php endif ?>
                                                </li>
                                                <li>
                                                    <a
                                                        href="#"
                                                        data-backdrop="static" data-keyboard="false"
                                                        data-toggle="modal"
                                                        data-target="#update_modal"
                                                        ng-click = "update('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospect_data/' + prospect.id); get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + prospect.id, '<?php echo base_url(); ?>assets/intent_letter/')">
                                                        <i class = "fa fa-edit"></i> Update
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="9"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->



    <!-- Update Modal -->
    <div class="modal fade" id = "update_modal" style="overflow-y:scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" ng-repeat = "data in updateData">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class = "fa fa-edit"></i> Update Prospect Tenant</h4>
                </div>
                <form action = "{{ 'update_lprospect/' + data.id }}"  enctype="multipart/form-data" id = "frm_updatelprospect" name="frm_update" method="post">
                    <div class="modal-body" style="padding-right:30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="trade_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Trade Name</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="data.trade_name"
                                                        id="trade_name"
                                                        name = "trade_name"
                                                        autocomplete="off">

                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.trade_name.$dirty && frm_update.trade_name.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="corp_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Corporate Name</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        ng-model="data.corporate_name"
                                                        id="corp_name"
                                                        required
                                                        name = "corp_name"
                                                        autocomplete="off">

                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.corp_name.$dirty && frm_update.corp_name.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Address</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        ng-model="data.address"
                                                        id="address"
                                                        required
                                                        name = "address"
                                                        autocomplete="off">

                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.address.$dirty && frm_update.address.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="leasee_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Lessee Type</label>
                                                <div class="col-md-8">
                                                    <select ng-model="data.leasee_type" name = "lessee_type" class="form-control" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($leasee_types as $type): ?>
                                                            <option><?php echo $type['leasee_type']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="first_category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>First Category</label>
                                                <div class="col-md-8">
                                                    <select
                                                        name = "first_category"
                                                        ng-model="data.first_category"
                                                        class="form-control" required
                                                        ng-click = "populate_categoryTwo('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryTwo/' + data.first_category)"
                                                        onchange = "deleteFirstIndex('update_secondCategory')">
                                                        <?php foreach ($category_one as $category): ?>
                                                            <option><?php echo $category['category_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="second_category" class="col-md-4 control-label text-right">Second Category</label>
                                                <div class="col-md-8">
                                                    <select
                                                        name = "second_category"
                                                        id="update_secondCategory"
                                                        class="form-control"
                                                        ng-model="data.second_category"
                                                        ng-click = "populate_categoryThree('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryThree/' + data.second_category)"
                                                        onchange = "deleteFirstIndex('update_thirdCategory')">
                                                        <option>{{ data.second_category  }}</option>
                                                        <option ng-repeat="two in categoryTwo">{{two.second_level}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!-- COL-MD-6 DIVIDER -->

                                    <div class="col-md-6">
                                      <div class="row">
                                            <div class="form-group">
                                                <label for="second_category" class="col-md-4 control-label text-right"> Third Category</label>
                                                <div class="col-md-8">
                                                    <select name = "third_category" id="update_secondCategory"  class="form-control">
                                                        <option>{{ data.third_category  }}</option>
                                                        <option ng-repeat="three in categoryThree">{{three.third_level}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_person" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Person</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="data.contact_person"
                                                        id="contact_person"
                                                        name = "contact_person"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.contact_person.$dirty && frm_update.contact_person.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_number" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Number</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="data.contact_number"
                                                        id="contact_number"
                                                        name = "contact_number"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.contact_number.$dirty && frm_update.contact_number.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="form-group">
                                                <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Location</label>
                                                <div class="col-md-8">
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer'): ?>
                                                    <select
                                                        name = "store_name"
                                                        required
                                                        ng-model="data.store_name"
                                                        class="form-control"
                                                        ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + data.store_name)"
                                                        onchange = "prospect_formDefault('update_floorName', 'update_locationCode', 'update_areaClassification', 'update_areaType', 'update_paymentMode', 'update_rentPeriod', 'update_floorArea', 'update_basicRental');deleteFirstIndex('update_floorName')">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($stores as $store): ?>
                                                            <option><?php echo $store['store_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <?php else: ?>
                                                        <input type = "text" readonly class="form-control" name = "store_name" ng-model = "data.store_name"  />
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>

                                        


                                        <div class="row">
                                            <div class="form-group">
                                                <label for="remarks" class="col-md-4 control-label text-right">Remarks</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="remarks"
                                                        ng-model = "data.remarks"
                                                        name = "remarks"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="request_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Request Date</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker  date-format="yyyy-mM-dd">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                placeholder="Choose a date"
                                                                class="form-control"
                                                                ng-model="data.request_date"
                                                                id="request_date"
                                                                name = "request_date"
                                                                autocomplete="off">
                                                        </datepicker>

                                                         <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="frm_update.request_date.$dirty && frm_update.request_date.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TABS BELOW -->
                                <hr />

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="title-header"><i class="fa fa-paperclip"></i> Attachments</span>
                                            </div>
                                        </div>
                                        <div>
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#letter" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + data.id, '<?php echo base_url(); ?>assets/intent_letter/')" class="btn btn-sky" aria-controls="letter" role="tab" data-toggle="tab">Letter of Intent</a></li>
                                                <li role="presentation"><a href="#profile" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_comprof/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="profile" role="tab" data-toggle="tab">Company Profile</a></li>
                                                <li role="presentation"><a href="#dti" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_dtibusireg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="dti" role="tab" data-toggle="tab">DTI Business Registration</a></li>
                                                <li role="presentation"><a href="#brochures" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_brochures/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="brochures" role="tab" data-toggle="tab">Brochures of Products</a></li>
                                                <li role="presentation"><a href="#colored_perspective" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_perspective/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="brochures" role="tab" data-toggle="tab">Colored Perspective</a></li>
                                                <li role="presentation"><a href="#gross" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_gsalesImg/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="gross" role="tab" data-toggle="tab">Gross Sales</a></li>
                                                <li role="presentation"><a href="#pricemenu" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_pricemenuList/' + data.id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-sky" aria-controls="pricemenu" role="tab" data-toggle="tab">Price/Menu List</a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="letter">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'LOI', 'Add Intent Letter')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img style = "height:200px" src="<?php echo base_url(); ?>assets/intent_letter/{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        {{ img.file_name }}
                                                                    </p>
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/intent_letter/' + img.file_name, 'Letter of Intent', '<?php echo base_url(); ?>index.php/leasing_transaction/update_intent/' + img.id)"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            data-target="#changeImg_modal" >
                                                                            <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="profile">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'CP', 'Add Company Profile')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        {{ img.file_name }}
                                                                    </p>
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'Company Profile', '<?php echo base_url(); ?>index.php/leasing_transaction/update_comprof/' + img.id)"
                                                                            data-target="#changeImg_modal">
                                                                            <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="dti">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'DTI', 'Add DTI Business Registration')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        {{ img.file_name }}
                                                                    </p>
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'DTI Business Registration', '<?php echo base_url(); ?>index.php/leasing_transaction/update_dti/' + img.id)"
                                                                            data-target="#changeImg_modal">
                                                                            <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="brochures">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'BR', 'Add Pictures/Brochures')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'Pictures/Brochures of Products', '<?php echo base_url(); ?>index.php/leasing_transaction/update_brochures/' + img.id)"
                                                                            data-target="#changeImg_modal"> <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="colored_perspective">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'PERSPECTIVE', 'Add Colored Perspective')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'Colored Perspective', '<?php echo base_url(); ?>index.php/leasing_transaction/update_perspective/' + img.id)"
                                                                            data-target="#changeImg_modal"> <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="gross">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'GR', 'Add Gross Sales File')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        {{ img.file_name }}
                                                                    </p>
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            data-target="#changeImg_modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'Gross Sales(Percentage rent.)', '<?php echo base_url(); ?>index.php/leasing_transaction/update_gsaleImg/' + img.id)" >
                                                                            <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="pricemenu">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <a
                                                                href="#"
                                                                class="btn btn-success btn-block"
                                                                ng-click = "addImg('<?php echo base_url(); ?>index.php/leasing_transaction/addImg/' + data.id + '/' + 'PM', 'Add Price/Menu List')"
                                                                data-backdrop="static" data-keyboard="false"
                                                                data-toggle="modal"
                                                                data-target="#addImg_modal">
                                                                <i class = "fa fa-file-image-o"></i> Add File
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4" ng-repeat = "img in imgList">
                                                            <div class="thumbnail">
                                                                <img src="{{imgPath}}{{img.file_name}}" alt="Thumbnail">
                                                                <div class="caption">
                                                                    <p class = "text-center">
                                                                        {{ img.file_name }}
                                                                    </p>
                                                                    <p class = "text-center">
                                                                        <a
                                                                            href="#"
                                                                            class="btn btn-primary"
                                                                            role="button"
                                                                            data-backdrop="static" data-keyboard="false"
                                                                            data-toggle="modal"
                                                                            data-target="#changeImg_modal"
                                                                            ng-click = "changeImg('<?php echo base_url(); ?>assets/other_img/' + img.file_name, 'Price/Menu List(for food tenants)', '<?php echo base_url(); ?>index.php/leasing_transaction/update_pricemenu/' + img.id)">
                                                                            <i class = "fa fa-edit"></i>Change
                                                                        </a>
                                                                    </p>
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Modal -->


    <div class="modal fade" id = "changeImg_modal" style = "background:#000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{header}}</h4>
                </div>
                <form action = "{{url}}" method = "post"  enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class = "thumbnail">
                            <img src="{{imgpath}}" id = "previewImg">
                            <input type = "hidden" name = "old_file" value = "{{imgpath}}" />
                        </div>
                        <input type = "file" required name = "img" id = "selectedImg"/>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class = "fa fa-save"></i> Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id = "addImg_modal" style = "background:#000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{header}}</h4>
                </div>
                <form action = "{{url}}" method = "post"  enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class = "thumbnail">
                            <img src="<?php echo base_url(); ?>img/thumbnail.png" id = "add_previewImg">
                        </div>
                        <input type = "file" required name = "img" id = "addImg"/>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


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
                                    <label class="col-md-4 control-label text-right">Lessee Type:</label>
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
                                        <div ng-if = "!data.second_category"><span class = "details">None</span></div>
                                        <div ng-if = "data.second_category"><span class = "details">{{ data.second_category }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label text-right">Third Category:</label>
                                    <div class="col-md-8">
                                        <div ng-if = "!data.third_category"><span class = "details">None</span></div>
                                        <div ng-if = "data.third_category"><span class = "details">{{ data.third_category }}</span></div>
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



    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Prospect Tenant</h4>
                </div>
                <form action="" onsubmit="add_lprospect(); return false"   enctype="multipart/form-data" id = "frm_addlprospect" name="add_form" method="post">
                    <div class="modal-body" style="padding-right:30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="trade_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Trade Name</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="trade_name"
                                                        id="trade_name"
                                                        name = "trade_name"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.trade_name.$dirty && add_form.trade_name.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="corp_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Corporate Name</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        ng-model="corp_name"
                                                        id="corp_name"
                                                        required
                                                        name = "corp_name"
                                                        autocomplete="off">

                                                    <div class="validation-Error">
                                                        <span ng-show="frm_update.corp_name.$dirty && frm_update.corp_name.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="address" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Address</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        ng-model="address"
                                                        id="address"
                                                        required
                                                        name = "address"
                                                        autocomplete="off">

                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.address.$dirty && add_form.address.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="leasee_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Lessee Type</label>
                                                <div class="col-md-8">
                                                    <select name = "lessee_type" class="form-control" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($leasee_types as $type): ?>
                                                            <option><?php echo $type['leasee_type']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="first_category" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>First Category</label>
                                                <div class="col-md-8">
                                                    <select
                                                        name = "first_category"
                                                        ng-model="first_category"
                                                        class="form-control" required
                                                        ng-change = "populate_categoryTwo('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryTwo/' + first_category)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($category_one as $category): ?>
                                                            <option><?php echo $category['category_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="second_category" class="col-md-4 control-label text-right">Second Category</label>
                                                <div class="col-md-8">
                                                    <select
                                                        name = "second_category"
                                                        class="form-control"
                                                        ng-model="second_category"
                                                        ng-change = "populate_categoryThree('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryThree/' + second_category)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="two in categoryTwo">{{two.second_level}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                            <!-- COL-MD-6 DIVIDER -->


                                    <input type = "text" id = "none" style = "display:none" />

                                    <div class="col-md-6">

                                        <!-- <div class="row">
                                            <div class="form-group">
                                                <label for="floor_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Location</label>
                                                <div class="col-md-8">
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                                    <select
                                                        name = "floor_name"
                                                        class="form-control"
                                                        ng-model="floor_name"
                                                        id = "floor_name"
                                                        required
                                                        ng-change = "populate_locationCode('<?php echo base_url(); ?>index.php/leasing_transaction/populate_ltlocationCode/' + store_name + '/' + floor_name)"
                                                        onchange = "prospect_formDefault('none', 'add_locationCode', 'area_classification', 'area_type', 'payment_mode', 'rent_period', 'add_floor_area', 'add_basic_rental')">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat = "item in itemList">{{item.floor_name}}</option>
                                                    </select>
                                                    <?php else: ?>
                                                    <select
                                                        name = "floor_name"
                                                        class="form-control"
                                                        id = "floor_name"
                                                        ng-model="floor_name"
                                                        required
                                                        ng-change = "populate_locationCode('<?php echo base_url(); ?>index.php/leasing_transaction/populate_ltlocationCode/' + '<?php echo $stores; ?>' + '/' + floor_name)"
                                                        onchange = "prospect_formDefault('none', 'none', 'area_classification', 'area_type', 'payment_mode', 'rent_period', 'add_floor_area', 'add_basic_rental')">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <?php foreach ($store_floors as $floor): ?>
                                                            <option><?php echo $floor['floor_name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row">
                                            <div class="form-group">
                                                <label for="floor_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Location Code</label>
                                                <div class="col-md-8">
                                                    <select
                                                        name = "location_code"
                                                        class="form-control"
                                                        required
                                                        id = "add_locationCode"
                                                        ng-model="location_code"
                                                        ng-click = "get_locationCodeInfo('<?php echo base_url(); ?>index.php/leasing_transaction/get_locationCodeInfo/' + location_code)">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="code in codeList">{{ code.location_code }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- <div class="row">
                                            <div class="form-group">
                                                <label for="area_classification" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Classification </label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        readonly
                                                        class="form-control"
                                                        ng-model="area_classification"
                                                        id="area_classification"
                                                        name = "area_classification"
                                                        autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="area_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Area Type  </label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        readonly
                                                        class="form-control"
                                                        ng-model="area_type"
                                                        id="area_type"
                                                        name = "area_type"
                                                        autocomplete="off" >
                                                </div>
                                            </div>
                                        </div> -->


                                        <!-- <div class="row">
                                            <div class="form-group">
                                                <label for="" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Period </label>
                                                <div class="col-md-8">
                                                    <input type = "text" class="form-control" id = "rent_period" name = "period" ng-model = "rent_period" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="payment_mode" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Type of Payment</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        readonly
                                                        class="form-control"
                                                        ng-model="payment_mode"
                                                        id="payment_mode"
                                                        name = "payment_mode"
                                                        autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="floor_area" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Floor Area</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                        <input
                                                            type="text"
                                                            readonly
                                                            ui-number-mask="2"
                                                            class="form-control currency"
                                                            ng-model="floor_area"
                                                            id="add_floor_area"
                                                            name = "floor_area"
                                                            autocomplete="off" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                       <!--  <div class="row">
                                            <div class="form-group">
                                                <label for="basic_rental" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Basic Rental</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                        <input
                                                            type="text"
                                                            ui-number-mask="2"
                                                            readonly
                                                            class="form-control currency"
                                                            ng-model="basic_rental"
                                                            id="add_basic_rental"
                                                            name = "basic_rental"
                                                            autocomplete="off" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
 -->

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="second_category" class="col-md-4 control-label text-right"> Third Category</label>
                                                <div class="col-md-8">
                                                    <select name = "third_category" class="form-control">
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="three in categoryThree">{{three.third_level}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_person1" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Person</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="contact_person"
                                                        id="contact_person"
                                                        name = "contact_person"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.contact_person.$dirty && add_form.contact_person.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="contact_number1" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Contact Number</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        required
                                                        class="form-control"
                                                        ng-model="contact_number"
                                                        id="contact_number"
                                                        name = "contact_number"
                                                        autocomplete="off">
                                                    <!-- FOR ERRORS -->
                                                    <div class="validation-Error">
                                                        <span ng-show="add_form.contact_number.$dirty && add_form.contact_number.$error.required">
                                                            <p class="error-display">This field is required.</p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="store_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Store Location</label>
                                                <div class="col-md-8">
                                                    <?php if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer'): ?>
                                                        <select
                                                            name = "store_name"
                                                            required
                                                            ng-model="store_name"
                                                            class="form-control"
                                                            ng-change = "populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_floors/' + store_name)"
                                                            onchange = "prospect_formDefault('floor_name', 'add_locationCode', 'area_classification', 'area_type', 'payment_mode', 'rent_period', 'add_floor_area', 'add_basic_rental')">
                                                            <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                            <?php foreach ($stores as $store): ?>
                                                                <option><?php echo $store['store_name']; ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    <?php else: ?>

                                                        <input type = "text" class = "form-control"  readonly name = "store_name" value="<?php echo $stores; ?>" >

                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="remarks" class="col-md-4 control-label text-right">Remarks</label>
                                                <div class="col-md-8">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="remarks"
                                                        name = "remarks"
                                                        autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="request_date" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Request Date</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                        <datepicker  date-format="yyyy-mM-dd">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                placeholder="Choose a date"
                                                                class="form-control"
                                                                ng-model="request_date"
                                                                id="request_date"
                                                                name = "request_date"
                                                                autocomplete="off">
                                                        </datepicker>

                                                     <!-- FOR ERRORS -->
                                                        <div class="validation-Error">
                                                            <span ng-show="add_form.request_date.$dirty && add_form.request_date.$error.required">
                                                                <p class="error-display">This field is required.</p>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- TABS BELOW -->
                            <hr />

                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#letter" class="btn btn-sky" aria-controls="letter" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i> Attachments</a></li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="letter">
                                            <!-- letter of intent tab -->
                                            <div class="row">
                                                <div class="col-md-10 col-md-offset-1">
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Letter of Intent <span class = "red">(SR04.01.09.02)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="intent_letter" name = "intent_letter[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Company Profile <span class = "red">(SR04.01.09.03)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="com_prof" name = "com_prof[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> DTI Business Registration<span class = "red">(SR04.01.09.04)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="dti_busireg" name = "dti_busireg[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Pictures/Brochures of Product</div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="brochures" name = "brochures[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Colored Perspective<span class = "red">(SR04.01.09.05)</span></div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="perspective" name = "perspective[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Monthly Gross Sales(for tenants based on percentage rent.)</div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="gross_sales" name = "gross_sales[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <!-- Default panel contents -->
                                                        <div class="panel-heading panel-leasing"><i class="fa fa-paperclip"></i> Price/Menu List(for food tenant)</div>
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <input id="pricemenu_list" name = "pricemenu_list[]" type="file" multiple="multiple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class = "col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><strong>Prepared By: </strong></div>
                                                        <input
                                                            type="text"
                                                            value="<?php echo $this->session->userdata('first_name'); ?> <?php echo $this->session->userdata('last_name'); ?>"
                                                            readonly
                                                            class="form-control"
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
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "add_form.$invalid"  class="btn btn-primary"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- End of Container -->
</body>
