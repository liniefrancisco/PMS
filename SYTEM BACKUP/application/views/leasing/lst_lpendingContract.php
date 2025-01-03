
<div class="container" ng-controller = "transactionController">
    <div class="well" ng-controller="tableController">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Long Term Pending Contracts </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/pending_lcontracts')">
                            
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "tenant in data">
                                    <td title = "'Tenant ID'" sortable = "'tenant_id'">{{ tenant.tenant_id }}</td>
                                    <td title = "'Trade Name'" sortable = "'trade_name'">{{ tenant.trade_name }}</td>
                                    <td title = "'Store/Property'" sortable = "'store_name'">{{ tenant.store_name }}</td>
                                    <td title = "'Location Code'" sortable = "'location_code'">{{ tenant.location_code }}</td>
                                    <td title = "'Lessee Type'" sortable = "'leasee_type'">{{ tenant.leasee_type }}</td>
                                    <td title = "'Contact Person'" sortable = "'contact_person'">{{ tenant.contact_person }}</td>
                                    <td title = "'Contract Date'" sortable = "'opening_date'">{{ tenant.opening_date }} Until {{ tenant.expiry_date }}</td>
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
                                                        data-backdrop = "static" data-keyboard = "false"
                                                        data-toggle="modal"
                                                        data-target="#view_modal"
                                                        ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/tenant_details/' + tenant.id)">
                                                        <i class = "fa fa-eye"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a
                                                        href="#"
                                                        data-backdrop = "static" data-keyboard = "false"
                                                        data-toggle="modal"
                                                        data-target="#update_primaryDetails"
                                                        ng-click="viewing('<?php echo base_url(); ?>index.php/leasing_transaction/tenant_details/' + tenant.id)">
                                                        <i class = "fa fa-edit"></i> Update Primary Details
                                                    </a>
                                                </li>
                                                <li ng-if = "'<?php echo $this->session->userdata('user_type'); ?>' != 'Store Manager' && '<?php echo $this->session->userdata('user_type'); ?>' != 'Supervisor' && '<?php echo $this->session->userdata('user_type'); ?>' != 'Coporate Leasing Supervisor'">
                                                    <a
                                                        href="#"
                                                        data-toggle="modal"
                                                        data-backdrop="static" data-keyboard="false"
                                                        ng-click = "managers_key('<?php echo base_url(); ?>index.php/leasing_transaction/post_lpendingContract/' + tenant.id)"
                                                        data-target="#manager_modal">
                                                        <i class = "fa fa-save"></i> Post
                                                    </a>
                                                </li>
                                                <li ng-if = "'<?php echo $this->session->userdata('user_type'); ?>' == 'Store Manager' || '<?php echo $this->session->userdata('user_type'); ?>' == 'Supervisor' || '<?php echo $this->session->userdata('user_type'); ?>' == 'Coporate Leasing Supervisor'">
                                                    <a
                                                        href="<?php echo base_url(); ?>index.php/leasing_transaction/post_lpendingContract/{{tenant.id}}">
                                                        <i class = "fa fa-save"></i> Post
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

    <!-- View Modal -->
    <div class="modal fade" id = "view_modal" style="overflow-y: scroll;" ng-controller = "transactionController">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Tenant's Complete Details</h4>
                </div>
                <div class="modal-body ng-cloak" ng-repeat = "detail in viewList">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Primary Details</a></li>
                                <li role="presentation" class=""><a href="#terms" ng-click = "details('<?php echo base_url(); ?>index.php/leasing_transaction/tenant_terms/' + detail.id)" aria-controls="terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                                <li role="presentation"><a href="#charges" aria-controls="charges" ng-click = "get_monthly_charges(detail.tenant_id)" role="tab" data-toggle="tab">Charges</a></li>
                                <li role="presentation"><a href="#discounts" aria-controls="discounts" ng-click="details('<?php echo base_url(); ?>index.php/leasing_transaction/get_discounts/' + detail.id)" role="tab" data-toggle="tab">Discounts</a></li>
                                <li role="presentation"><a href="#Attachment" ng-click = "details('<?php echo base_url(); ?>index.php/leasing_transaction/get_prospectID/' + detail.tenant_id)" aria-controls="Attachment" role="tab" data-toggle="tab">Attachment</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active ng-cloak" id="details">
                                    <div class = "col-md-12">
                                        <div class="row">
                                            <div class = "col-md-6">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                class="form-control"
                                                                ng-model = "detail.tenant_id"
                                                                id="tenant_id"
                                                                name = "tenant_id"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="trade_name" class="col-md-4 control-label text-right">Trade Name</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.trade_name"
                                                                class="form-control"
                                                                id="trade_name"
                                                                name = "trade_name"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="corp_name" class="col-md-4 control-label text-right">Corporate Name</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.corporate_name"
                                                                class="form-control"
                                                                id="corp_name"
                                                                name = "corp_name"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="tenant_type" class="col-md-4 control-label text-right">Tenant Type</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.tenant_type"
                                                                class="form-control"
                                                                id="tenant_type"
                                                                name = "tenant_type"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="address" class="col-md-4 control-label text-right">Tenant Address</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.address"
                                                                class="form-control"
                                                                id="address"
                                                                name = "address"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="leasee_type" class="col-md-4 control-label text-right">Leasee Type</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.leasee_type"
                                                                class="form-control"
                                                                id="leasee_type"
                                                                name = "leasee_type"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="category_one" class="col-md-4 control-label text-right">First Category</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.first_category"
                                                                class="form-control"
                                                                id="category_one"
                                                                name = "category_one"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="category_two" class="col-md-4 control-label text-right">Second Category</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.second_category"
                                                                class="form-control"
                                                                id="category_two"
                                                                name = "category_two"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="category_three" class="col-md-4 control-label text-right">Third Category</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.third_category"
                                                                class="form-control"
                                                                id="category_three"
                                                                name = "category_three"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DIVIDER COL-MD-6 -->
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contact_person1" class="col-md-4 control-label text-right">Contact Person</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.contact_person"
                                                                class="form-control"
                                                                id="contact_person1"
                                                                name = "contact_person1"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number </label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.contact_number"
                                                                class="form-control"
                                                                id="contact_number1"
                                                                name = "contact_number1"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="store_name" class="col-md-4 control-label text-right">Store Location</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.store_name"
                                                                class="form-control"
                                                                id="store_name"
                                                                name = "store_name"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="floor_name" class="col-md-4 control-label text-right">Floor Location</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.floor_name"
                                                                class="form-control"
                                                                id="floor_name"
                                                                name = "floor_name"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="location_code" class="col-md-4 control-label text-right">Location Code</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.location_code"
                                                                class="form-control"
                                                                id="location_code"
                                                                name = "location_code"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="location_desc" class="col-md-4 control-label text-right">Location Description</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.location_desc"
                                                                class="form-control"
                                                                id="location_desc"
                                                                name = "location_desc"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contact_number2" class="col-md-4 control-label text-right">Area Classification</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.area_classification"
                                                                class="form-control"
                                                                id="contact_number2"
                                                                name = "contact_number2"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contact_number2" class="col-md-4 control-label text-right">Area Type</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model = "detail.area_type"
                                                                class="form-control"
                                                                id="contact_number2"
                                                                name = "contact_number2"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="floor_area" class="col-md-4 control-label text-right"></i>Floor Area</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    ng-model = "detail.floor_area"
                                                                    class="form-control currency"
                                                                    ui-number-mask="2"
                                                                    id="floor_area"
                                                                    name = "floor_area"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="sugg_rental" class="col-md-4 control-label text-right"></i>Suggst. Monthly/Rental</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    ng-model = "detail.basic_rental"
                                                                    class="form-control currency"
                                                                    ui-number-mask="2"
                                                                    id="sugg_rental"
                                                                    name = "sugg_rental"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="terms">
                                    <div class="col-md-12" ng-repeat="data in dataList">
                                        <div class="row ng-cloak">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="contract_no" class="col-md-4 control-label text-right">Contract No.</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.contract_no"
                                                                class="form-control"
                                                                id="contract_no"
                                                                name = "contract_no"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="tin" class="col-md-4 control-label text-right">TIN</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.tin"
                                                                class="form-control"
                                                                id="tin"
                                                                name = "tin"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="rental_type" class="col-md-4 control-label text-right">Rental Type</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.rental_type"
                                                                class="form-control"
                                                                id="rental_type"
                                                                name = "rental_type"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" ng-if = "data.rental_type != 'Fixed'">
                                                    <div class="form-group">
                                                        <label for="rental_type" class="col-md-4 control-label text-right">Rent Percentage</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ui-number-mask="2"
                                                                ng-model="data.rent_percentage"
                                                                class="form-control"
                                                                id="rental_type"
                                                                name = "rental_type"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="montly_wvat" class="col-md-4 control-label text-right"></label>
                                                        <div class="col-md-8 text-right">
                                                           <label class="control-label">Vatable</label> <input type = "checkbox" ng-checked="data.is_vat == 'Added'"  disabled="disabled" value = "Added"  name = "plus_vat" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="montly_wvat" class="col-md-4 control-label text-right"></label>
                                                        <div class="col-md-8 text-right">
                                                           <label class="control-label">Less Withholding Tax</label> <input type = "checkbox" ng-checked="data.wht == 'Added'"  disabled="disabled" value = "Added"  name = "less_wht" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DIVIDER -->
                                            <div class="col-md-6">
                                                <div class="row" ng-if = "!data.increment_percentage || data.increment_percentage == 'None'">
                                                    <div class="form-group">
                                                        <label for="rent_increment" class="col-md-4 control-label text-right">Rental Incrementation</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                value="None"
                                                                class="form-control"
                                                                id="rent_increment"
                                                                name = "rent_increment"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" ng-if = "!data.increment_percentage || data.increment_percentage != 'None'">
                                                    <div class="form-group">
                                                        <label for="rent_increment" class="col-md-4 control-label text-right">Rental Incrementation</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.rent_increment"
                                                                class="form-control"
                                                                id="rent_increment"
                                                                name = "rent_increment"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" ng-if = "data.is_vat != 'Added'">
                                                    <div class="form-group">
                                                        <label for="rental_type" class="col-md-4 control-label text-right">BIR Document</label>
                                                        <div class="col-md-8">
                                                            <a href="#" data-toggle="modal" ng-click = "previewImg('<?php echo base_url(); ?>assets/bir/' + data.bir_doc)" data-target="#preview_img" class = "click-to-preview">Click to Preview</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="lease_period" class="col-md-4 control-label text-right">Lease Period</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.rent_period"
                                                                class="form-control"
                                                                id="lease_period"
                                                                name = "lease_period"
                                                                autocomplete="off" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="opening_date" class="col-md-4 control-label text-right"></i>Opening Date</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    ng-model="data.opening_date"
                                                                    class="form-control"
                                                                    id="opening_date"
                                                                    name = "opening_date"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="expiry_date" class="col-md-4 control-label text-right"></i>Expiry Date</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    ng-model="data.expiry_date"
                                                                    class="form-control"
                                                                    id="expiry_date"
                                                                    name = "expiry_date"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="charges" >
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><i class="fa fa-minus-circle"></i> Charges </div>
                                                <div class="panel-body">
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th width="30%"><a href="#" data-ng-click="sortField = 'Description'; reverse = !reverse">Description</a></th>
                                                                <th width="20%"><a href="#" data-ng-click="sortField = 'Charges Code'; reverse = !reverse">Charges Code</a></th>
                                                                <th width="15%"><a href="#" data-ng-click="sortField = 'uom'; reverse = !reverse">OUM</a></th>
                                                                <th width="10%"><a href="#" data-ng-click="sortField = 'unit_price'; reverse = !reverse">Unit Price</a></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="ng-cloak" ng-repeat= "charge in selected_monthly_charges">
                                    
                                                                <td>{{ charge.description }}</td>
                                                                <td>{{ charge.charges_code }}</td>
                                                                <td>{{ charge.uom }}</td>
                                                                <td align="right">{{ charge.unit_price | currency : '&#8369;' }}</td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="discounts" >
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><i class="fa fa-minus-circle"></i> Discounts</div>
                                                <div class="panel-body">
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th width="20%"><a href="#" data-ng-click="sortField = 'tenant_type'; reverse = !reverse">Discount Type</a></th>
                                                                <th width="20%"><a href="#" data-ng-click="sortField = 'discount_type'; reverse = !reverse">Percent/Amount</a></th>
                                                                <th width="15%"><a href="#" data-ng-click="sortField = 'discount'; reverse = !reverse">Discount</a></th>
                                                                <th width="30%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Description</a></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="ng-cloak" ng-show="dataList.length!=0" ng-repeat= "type in dataList | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                                                <td>{{ type.tenant_type }}</td>
                                                                <td>{{ type.discount_type }}</td>
                                                                <td>
                                                                    <div ng-if = "type.discount_type == 'Fixed Amount'">
                                                                        {{ type.discount | currency : '&#8369;' }}
                                                                    </div>
                                                                    <div ng-if = "type.discount_type != 'Fixed Amount'">
                                                                        {{ type.discount | currency : '% ' }}
                                                                    </div>
                                                                </td>
                                                                <td>{{ type.description }}</td>
                                                            </tr>
                                                            <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                                                <td colspan="5"><center>No Data Available.</center></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="Attachment">
                                    <div class="col-md-12" ng-repeat="data in dataList">
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Letter of Intent</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Letter of Intent" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_letter/' + data.prospect_id, '<?php echo base_url(); ?>assets/intent_letter/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Company Profile</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Company Profile" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_comprof/' + data.prospect_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">DTI Business Registration</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="DTI Business Registration" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_dtibusireg/' + data.prospect_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Pictures/Brochures of Products</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Pictures/Brochures of Products" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_brochures/' + data.prospect_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Gross Sales(Percentage rent.)</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Gross Sales(Percentage rent.)" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_gsalesImg/' + data.prospect_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Price/Menu List(for food tenants)</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Price/Menu List(for food tenants)" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_pricemenuList/' + data.prospect_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Contract Documents</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Contract Documents" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_contractDocs/' + detail.tenant_id, '<?php echo base_url(); ?>assets/contract_docs/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class = "thumbnail-header text-center">Supporting Documents</div>
                                            <div class="thumbnail">
                                                <img src="<?php echo base_url(); ?>img/Folder-icon.png" alt="Contract Documents" style = "height:150px;">
                                                <div class="caption">
                                                    <p><a href="#" data-toggle="modal" data-target="#carousel_modal" ng-click="get_img('<?php echo base_url(); ?>index.php/leasing_transaction/get_suppDocs/' + detail.tenant_id, '<?php echo base_url(); ?>assets/other_img/')" class="btn btn-primary btn-block" role="button"> <i class = "glyphicon glyphicon-zoom-in"></i> View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <!-- Preview Image Modal -->
    <div class="modal fade" id = "preview_img">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-image"></i> BIR Document</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class = "thumbnail">
                                <img src="{{ImgData}}" alt="BIR Document">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Preview Image Modal -->



    <!-- Amendment of Contract Modal -->
    <div class="modal fade" id = "amendment_modal" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Amendment of Contract</h4>
                </div>
                <div class="modal-body ng-cloak" ng-repeat = "data in viewList">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#amend_terms" aria-controls="amend_terms" role="tab" data-toggle="tab">Terms & Condition</a></li>
                                <li role="presentation" class=""><a href="#amend_discounts"  aria-controls="amend_discounts" role="tab" data-toggle="tab" ng-click="get_pickedDiscounts('<?php echo base_url(); ?>index.php/leasing_transaction/get_discounts/' + data.id); get_allDiscounts('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_tenant_type')">Discounts</a></li>
                                <li role="presentation" class=""><a href="#amend_attachments" aria-controls="amend_attachments" role="tab" data-toggle="tab">Attachments</a></li>
                            </ul>
                            <form action="#" method = "post" onsubmit="ltenant_amendment('<?php echo base_url(); ?>index.php/leasing_transaction/ltenant_amendment/'); return false;"  enctype="multipart/form-data" name = "frm_amendment" id="frm_amendment">
                                <div class="tab-content" >
                                    <div role="tabpanel" class="tab-pane active" id="amend_terms" >
                                        <div class = "col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contract_no" class="col-md-4 control-label text-right">Contract No.</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model = "data.contract_no"
                                                                    id="contract_no"
                                                                    name = "contract_no"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model = "data.tenant_id"
                                                                    id="tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-4 control-label text-right">Trade Name</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model = "data.trade_name"
                                                                    id="trade_name"
                                                                    name = "trade_name"
                                                                    autocomplete="off" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="floor_name" class="col-md-4 control-label text-right">Floor Location</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type = "text"
                                                                    name = "location_code"
                                                                    class = "form-control"
                                                                    readonly
                                                                    ng-model = "data.floor_name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="location_code" class="col-md-4 control-label text-right">Location Code</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type = "text"
                                                                    name = "location_code"
                                                                    class = "form-control"
                                                                    readonly
                                                                    ng-model = "data.location_code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="price_persq" class="col-md-4 control-label text-right">Price Per Sq.</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        format="number"
                                                                        id="amendment_price_persq"
                                                                        name = "price_persq"
                                                                        ng-model = "data.price_persq"
                                                                        ng-keyup = "compute_basicRental_update(data.floor_area, 'amendment_price_persq', 'amendment_basic_rental')"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="floor_area" class="col-md-4 control-label text-right">Floor Area</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon squared"><strong>m<sup>2</sup></strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ui-number-mask="2"
                                                                        id="floor_area"
                                                                        ng-keyup = "compute_basicRental_update(data.floor_area, 'amendment_price_persq', 'amendment_basic_rental')"
                                                                        name = "floor_area"
                                                                        ng-model = "data.floor_area"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- divider -->
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="rental_type" class="col-md-4 control-label text-right">Rental Type</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    required
                                                                    name = "rental_type"
                                                                    id="rental_type"
                                                                    ng-model = "data.rental_type"
                                                                    class = "form-control">
                                                                    <option value="" disabled="" selected="" style = "display:none">Please Select One</option>
                                                                    <option>Fixed</option>
                                                                    <option>Fixed Plus Percentage</option>
                                                                    <option>Fixed/Percentage w/c Higher</option>
                                                                    <option>Fixed/Percentage/Minimum w/c Higher</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" ng-hide = "data.rental_type != 'Basic plus Percentage plus VAT less withholding Taxes' && data.rental_type != 'Percentage Rental plus VAT less withholding Taxes' && data.rental_type != 'Percentage Rental plus VAT' && data.rental_type != 'Basic plus Percentage plus VAT'">
                                                        <div class="form-group">
                                                            <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Percentage</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>%</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ng-model="data.rent_percentage"
                                                                        format="number"
                                                                        id="rent_percentage"
                                                                        name = "rent_percentage"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" ng-hide = "rental_type != 'Basic plus Percentage plus VAT less withholding Taxes' && rental_type != 'Percentage Rental plus VAT less withholding Taxes' && rental_type != 'Percentage Rental plus VAT' && rental_type != 'Basic plus Percentage plus VAT'">
                                                        <div class="form-group">
                                                            <label for="rent_percentage" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Rent Percentage</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>%</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        ng-model="data.rent_percentage"
                                                                        format="number"
                                                                        id="rent_percentage"
                                                                        name = "rent_percentage"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="lease_period" class="col-md-4 control-label text-right">Lease Period</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "lease_period"
                                                                    id = "lease_period"
                                                                    ng-model = "data.lease_period"
                                                                    required
                                                                    class = "form-control">
                                                                    <option>1 Year</option>
                                                                    <option>2 Years</option>
                                                                    <option>3 Years</option>
                                                                    <option>4 Years</option>
                                                                    <option>5 Years</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="opening_date" class="col-md-4 control-label text-right"></i>Opening Date</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class = "fa fa-calendar"></i></strong></div>
                                                                    <input
                                                                        type="text"
                                                                        readonly
                                                                        ng-model="data.opening_date"
                                                                        class="form-control"
                                                                        id="opening_date"
                                                                        name = "opening_date"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="expiry_date" class="col-md-4 control-label text-right">Expiry Date </label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon input-date"><strong><i class="fa fa-calendar"></i></strong></div>
                                                                    <datepicker date-min-limit="<?php echo date('Y-m-d'); ?>" date-format="yyyy-M-dd">
                                                                        <input
                                                                            type="text"
                                                                            required
                                                                            placeholder="Choose a date"
                                                                            class="form-control"
                                                                            ng-model="data.expiry_date"
                                                                            id="expiry_date"
                                                                            name = "expiry_date"
                                                                            autocomplete="off">
                                                                    </datepicker>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="plus_vat" class="col-md-4 control-label text-right"></label>
                                                            <div class="col-md-8 text-right">
                                                               <label class="control-label">Plus VAT</label> <input type = "checkbox" onclick="isvat()" ng-checked = "data.is_vat"  value = "added"  name = "plus_vat" id = "amendment_plus_vat" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id = "doc_holder">
                                                        <div class="row" ng-if = "!data.is_vat">
                                                            <div class="form-group">
                                                                <label for="montly_wvat" class="col-md-4 control-label text-right">BIR Document</label>
                                                                <div class="col-md-8">
                                                                   <input type = "file" name = "bir_doc" id = "bir_doc" class = "form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="basic_rental" class="col-md-4 control-label text-right">Basic Rental</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control currency"
                                                                        readonly
                                                                        format="number"
                                                                        id="amendment_basic_rental"
                                                                        name = "basic_rental"
                                                                        ng-model = "data.basic_rental"
                                                                        autocomplete="off" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="amend_discounts" >
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class = "row">
                                                <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> To choose discount(s) just check the appropriate discount types.
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-minus-circle"></i> Discounts</div>
                                                    <div class="panel-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%"></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'tenant_type'; reverse = !reverse">Discount Type</a></th>
                                                                    <th width="20%"><a href="#" data-ng-click="sortField = 'discount_type'; reverse = !reverse">Percent/Amount</a></th>
                                                                    <th width="15%"><a href="#" data-ng-click="sortField = 'discount'; reverse = !reverse">Discount</a></th>
                                                                    <th width="30%"><a href="#" data-ng-click="sortField = 'description'; reverse = !reverse">Description</a></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="ng-cloak" ng-repeat= "disc in allDiscounts">
                                                                    <td>
                                                                        <input type = "checkbox" class="form-control" name = "sdiscount[]" value = "{{disc.id}}"  ng-checked="is_discountExist(disc)" />
                                                                    </td>
                                                                    <td>{{ disc.tenant_type }}</td>
                                                                    <td>{{ disc.discount_type }}</td>
                                                                    <td>
                                                                        <div ng-if = "disc.discount_type == 'Fixed Amount'">
                                                                            {{ disc.discount | currency : '&#8369;' }}
                                                                        </div>
                                                                        <div ng-if = "disc.discount_type != 'Fixed Amount'">
                                                                            {{ disc.discount | currency : '% ' }}
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ disc.description }}</td>
                                                                </tr>
                                                                <tr class="ng-cloak" ng-show="dataList.length==0 || (dataList | filter:query).length == 0">
                                                                    <td colspan="5"><center>No Data Available.</center></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end of amendment discount panel -->
                                    <div role="tabpanel" class="tab-pane" id="amend_attachments" >
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class = "row">
                                                <div class="alert alert-info fade in">
                                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                                    <strong>Note:</strong> Upload the amended contract
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><i class="fa fa-image"></i> Contract Documents</div>
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <input id="amended_contract_docs" class = "form-control" name = "contract_docs[]" required type="file" multiple="multiple">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type = "submit" ng-disabled = "frm_amendment.$invalid" class="btn btn-medium btn-primary"><i class = "fa fa-save"></i> Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end of modal body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Amendment of Contract Modal -->

    <!-- update_primaryDetails Modal -->
    <div class="modal fade" id = "update_primaryDetails" style="overflow-y: scroll;" ng-controller = "transactionController">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ng-cloak" ng-repeat = "detail in viewList">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Tenant's Primary Details</h4>
                </div>
                <form action = "{{ 'update_primaryDetails/' + detail.prospect_id + '/Long Term' }}"  id = "frm_updateDetails" name="frm_updateDetails" method="post">
                    <div class="modal-body" >
                        <div class="row">
                            <div class="col-md-12">
                                    <div role="tabpanel" class="tab-pane active ng-cloak" id="details">
                                        <div class = "col-md-12">
                                            <div class="row">
                                                <div class = "col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="tenant_id" class="col-md-4 control-label text-right">Tenant ID</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    readonly
                                                                    class="form-control"
                                                                    ng-model = "detail.tenant_id"
                                                                    id="tenant_id"
                                                                    name = "tenant_id"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="trade_name" class="col-md-4 control-label text-right">Trade Name</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.trade_name"
                                                                    class="form-control"
                                                                    id="trade_name"
                                                                    name = "trade_name"
                                                                    autocomplete="off">
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_updateDetails.trade_name.$dirty && frm_updateDetails.trade_name.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="corp_name" class="col-md-4 control-label text-right">Corporate Name</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.corporate_name"
                                                                    class="form-control"
                                                                    id="corp_name"
                                                                    name = "corp_name"
                                                                    autocomplete="off">
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_updateDetails.corp_name.$dirty && frm_updateDetails.corp_name.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="address" class="col-md-4 control-label text-right">Tenant Address</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.address"
                                                                    class="form-control"
                                                                    id="address"
                                                                    name = "address"
                                                                    autocomplete="off">
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_updateDetails.address.$dirty && frm_updateDetails.address.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="leasee_type" class="col-md-4 control-label text-right">Lessee Type</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    required
                                                                    ng-model = "detail.leasee_type"
                                                                    class="form-control"
                                                                    id="lessee_type"
                                                                    name = "lessee_type">

                                                                    <?php foreach ($leasee_types as $value): ?>
                                                                        <option><?php echo $value['leasee_type']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <!-- DIVIDER COL-MD-6 -->
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="category_one" class="col-md-4 control-label text-right">First Category</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.first_category"
                                                                    class="form-control"
                                                                    id="category_one"
                                                                    name = "first_category"
                                                                    ng-click = "populate_categoryTwo('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryTwo/' + detail.first_category)"
                                                                    onchange = "deleteFirstIndex('update_secondCategory')">
                                                                    <?php foreach ($category_one as $value): ?>
                                                                        <option><?php echo $value['category_name']; ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="category_two" class="col-md-4 control-label text-right">Second Category</label>
                                                            <div class="col-md-8">
                                                                <select
                                                                    name = "second_category"
                                                                    id="update_secondCategory"
                                                                    class="form-control"
                                                                    ng-model="detail.second_category"
                                                                    ng-click = "populate_categoryThree('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryThree/' + detail.second_category)"
                                                                    onchange = "deleteFirstIndex('third_category')">
                                                                    <option>{{ detail.second_category  }}</option>
                                                                    <option ng-repeat="two in categoryTwo">{{two.second_level}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="category_three" class="col-md-4 control-label text-right">Third Category</label>
                                                            <div class="col-md-8">
                                                                <select name = "third_category" id="third_category"  class="form-control">
                                                                    <option>{{ detail.third_category  }}</option>
                                                                    <option ng-repeat="three in categoryThree">{{three.third_level}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contact_person1" class="col-md-4 control-label text-right">Contact Person</label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.contact_person"
                                                                    class="form-control"
                                                                    id="contact_person1"
                                                                    name = "contact_person1"
                                                                    autocomplete="off" >
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_updateDetails.contact_person1.$dirty && frm_updateDetails.contact_person1.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="contact_number1" class="col-md-4 control-label text-right">Contact Number </label>
                                                            <div class="col-md-8">
                                                                <input
                                                                    type="text"
                                                                    required
                                                                    ng-model = "detail.contact_number"
                                                                    class="form-control"
                                                                    id="contact_number1"
                                                                    name = "contact_number1"
                                                                    autocomplete="off" >
                                                                <div class="validation-Error">
                                                                    <span ng-show="frm_updateDetails.contact_number1.$dirty && frm_updateDetails.contact_number1.$error.required">
                                                                        <p class="error-display">This field is required.</p>
                                                                    </span>
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
                            <button type="submit" ng-disabled = "frm_updateDetails.$invalid"  class="btn btn-primary"> <i class = "fa fa-save"></i> <span id = "adding">Submit</span> </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                </form>
                </div> <!-- end of modal body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Primary Details Modal -->

    <!-- Manager's Key Modal -->
    <div class="modal fade" id = "managerkey_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
                </div>
                <form action="#"  method="post" id="frm_managerKey" name = "frm_managerKey">
                    <div class="modal-body">
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
                                                        ng-model="username"
                                                        id="username"
                                                        name = "username"
                                                        autocomplete="off" >
                                            </div>
                                             <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_managerKey.username.$dirty && frm_managerKey.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
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
                                                        ng-model="password"
                                                        id="password"
                                                        name = "password"
                                                        autocomplete="off" >
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_managerKey.password.$dirty && frm_managerKey.password.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <a href="#"  class="btn btn-primary" data-dismiss="modal"> <i class="fa fa-key"></i> Submit</a>
                        <button type="button" class="btn btn-alert" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Manager's Key Modal -->




</div> <!-- /.container -->
</body>
