<div class="container" ng-app="myApp">
    <div class="row" ng-controller = "appController">
        <div class="main-page" style="margin-top:20px;">
            <div class="content-main">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="box bottom-main">
                            <div class="info float-container">
                                <div class="col-sm-12 bottom-title">
                                    <h3 class="text-uppercase">ALTURAS GROUP OF COMPANIES</h3>
                                    <h4 class="text-uppercase">Property Management System</h4>
                                </div>
                                <div class="row">
                                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-left:14%">
                                        <div class="bottom-img">
                                            <a href="<?php echo base_url() ?>index.php/ctrl_leasing/general_ledger">
                                                <img src="<?php echo base_url(); ?>img/gl.png" alt="Image" class="menu-img">
                                                <p class="first">General Ledger</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="bottom-img">
                                            <a href="<?php echo base_url() ?>index.php/ctrl_leasing/tenant_ledger">
                                                <img src="<?php echo base_url(); ?>img/sl.jpg" alt="Image" class="menu-img">
                                                <p class="second" >Tenant Ledger</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="bottom-img">
                                            <a href="<?php echo base_url() ?>index.php/ctrl_leasing/soa">
                                                <img src="<?php echo base_url(); ?>img/soa.jpg" alt="Image" class="menu-img">
                                                <p class="third">SOA</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-left:14%" >
                                        <div class="bottom-img">
                                            <a href="<?php echo base_url() ?>index.php/ctrl_leasing/payment_history">
                                                <img src="<?php echo base_url(); ?>img/payment_history.png" alt="Image" class="menu-img">
                                                <p class="cfs" style="background-color: #125821; opacity:.9">Payment History</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="bottom-img">
                                            <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#tenants_modal">
                                                <img src="<?php echo base_url(); ?>img/details.png" alt="Image" class="menu-img">
                                                <p class="cfs" style="background-color: #607d8b; opacity:.9">Tenant Details</p>
                                            </a>
                                        </div>
                                    </div>
                                     <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="bottom-img">
                                            <a href="<?php echo base_url() ?>index.php/ctrl_leasing/rr_ar_summary">
                                                <img src="<?php echo base_url(); ?>img/details.png" alt="Image" class="menu-img">
                                                <p class="cfs" style="background-color: #34343c; opacity:.9">Monthly RR/AR Summary</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- .content-main -->
        </div> <!-- .main-page -->
    </div> <!-- .row -->
    <footer class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer">
            <p class="copyright">Copyright © 2016 AGC | Design: <a rel="nofollow" href="#" >Cyril Andrew</a></p>
        </div>
    </footer>  <!-- .row -->



    <div class="modal fade" id = "tenants_modal" ng-controller="tableController">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> Active Tenants</h4>
                </div>
                <div class="modal-body" style="height: 500px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" ng-table = "tableParams"  ng-init="loadList('<?php echo base_url(); ?>index.php/ctrl_leasing/get_activeTenants')">
                                <thead>
                                    <tr>
                                        <th><a href="#" data-ng-click="sortField = 'tenant_id'; reverse = !reverse">Tenant ID</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'trade_name'; reverse = !reverse">Trade Name</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'store_name'; reverse = !reverse">Store/Property</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'location_code'; reverse = !reverse">Location Code</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'leasee_type'; reverse = !reverse">Lessee Type</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'contact_person1'; reverse = !reverse">Contact Person</a></th>
                                        <th><a href="#" data-ng-click="sortField = 'contact_number'; reverse = !reverse">Contract Date</a></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss = "modal"><i class = "fa fa-close"></i> Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data view -->


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
                                <li role="presentation" class=""><a href="#charges"  aria-controls="charges" role="tab" data-toggle="tab" ng-click = "get_monthly_charges(detail.tenant_id)">Charges</a></li>
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
                                                <div class="row" ng-if = "detail.is_incrementable > 0">
                                                    <div class="form-group">
                                                        <label for="rent_increment" class="col-md-4 control-label text-right"></i>Monthly Rental plus Rent Increment</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                                <input
                                                                    type="text"
                                                                    readonly
                                                                    ng-model = "(detail.basic_rental - (-detail.basic_rental * ((detail.increment_percentage * detail.is_incrementable) / 100)))"
                                                                    class="form-control currency"
                                                                    ui-number-mask="2"
                                                                    id="rent_increment"
                                                                    name = "rent_increment"
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
                                                        <label for="tenant_type" class="col-md-4 control-label text-right">Tenant Type</label>
                                                        <div class="col-md-8">
                                                            <input
                                                                type="text"
                                                                required
                                                                readonly
                                                                ng-model="data.tenant_type"
                                                                class="form-control"
                                                                id="tenant_type"
                                                                name = "tenant_type"
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
                                <div role="tabpanel" class="tab-pane" id="charges" >
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><i class="fa fa-minus-circle"></i> Charges</div>
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
                                                            <tr class="ng-cloak" ng-show="selected_monthly_charges.length!=0" ng-repeat= "charge in selected_monthly_charges | filter:query | orderBy:sortField:reverse | offset: currentPage*itemsPerPage | limitTo: itemsPerPage">
                                                                <td>{{ charge.description }}</td>
                                                                <td>{{ charge.charges_code }}</td>
                                                                <td>{{ charge.uom }}</td>
                                                                <td align="right">{{ charge.unit_price | currency : '&#8369;' }}</td>
                                                            </tr>
                                                            <tr class="ng-cloak" ng-show="selected_monthly_charges.length==0 || (selected_monthly_charges | filter:query).length == 0">
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content" >
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


</div> <!-- .container -->
