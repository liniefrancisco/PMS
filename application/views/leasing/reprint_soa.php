<div class="container" ng-controller="tableController">
    <div class="well" ng-controller="transactionController">
        <div class="panel panel-default">
            <?php if ($this->session->userdata('leasing_logged_in') == 'TRUE'): ?>
                <div class="panel-heading panel-leasing"><i class="fa fa-print"></i> Re-print SOA</div>
            <?php elseif ($this->session->userdata('cfs_logged_in') == 'TRUE'): ?>
                <div class="panel-heading panel-cfs"><i class="fa fa-print"></i> Tenant SOA</div>
            <?php elseif ($this->session->userdata('portal_logged_in') == 'TRUE'): ?>
                <div class="panel-heading panel-portal"><i class="fa fa-print"></i> My SOA</div>
            <?php endif; ?>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab"
                                    data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">

                                    <?php if ($this->session->userdata('portal_logged_in') != 'TRUE'): ?>
                                        <div class="row" style="margin-left:2%">
                                            <div class="form-group">
                                                <label for="tenancy_type" class="col-md-2 control-label text-right"><i
                                                        class="fa fa-asterisk"></i>Tenancy Type</label>
                                                <div class="col-md-4">
                                                    <select class="form-control" name="tenancy_type" ng-model="tenancy_type"
                                                        ng-change="populate_tradeName('<?php echo base_url(); ?>index.php/leasing_transaction/populate_tradeName/' + tenancy_type)"
                                                        required>
                                                        <option value="" disabled="" selected="" style="display:none">Please
                                                            Select One</option>
                                                        <option>Short Term Tenant</option>
                                                        <option>Long Term Tenant</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="row" style="margin-left:2%">
                                        <div class="form-group">
                                            <label for="tenant_id" class="col-md-2 control-label text-right"><i
                                                    class="fa fa-asterisk"></i>Trade Name</label>
                                            <div class="col-md-4">

                                                <?php if ($this->session->userdata('cfs_logged_in')): ?>
                                                    <div class="input-group">
                                                        <div mass-autocomplete>
                                                            <input name="trade_name" ng-model="dirty.value"
                                                                class="form-control" readonly="" id="trade_name">
                                                        </div>
                                                        <span class="input-group-btn">
                                                            <button ng-disabled="!tenancy_type || tenancy_type.length == 0"
                                                                class="btn btn-info" type="button" data-backdrop="static"
                                                                data-keyboard="false" data-toggle="modal"
                                                                data-target="#lookup"
                                                                ng-click="loadList2('<?php echo base_url(); ?>index.php/ctrl_cfs/tenant_lookup/' + tenancy_type)"><i
                                                                    class="fa fa-search"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                <?php elseif ($this->session->userdata('leasing_logged_in')): ?>

                                                    <div class="input-group">
                                                        <div mass-autocomplete>
                                                            <input required name="tenant_id" ng-model="dirty.value"
                                                                mass-autocomplete-item="autocomplete_options"
                                                                class="form-control" id="tenant_id">
                                                        </div>
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info" type="button"
                                                                ng-click="ajaxLoadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_tenantSoa/', dirty.value); check(dirty.value)"><i
                                                                    class="fa fa-search"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                <?php elseif ($this->session->userdata('portal_logged_in')): ?>

                                                    <!-- <input type="text" name="tenant_id" value="<?php echo $this->session->userdata('tenant_id'); ?>" id="tenant_id"> -->
                                                    <div class="input-group">
                                                        <div>
                                                            <input required name="trade_name" id="trade_name"
                                                                class="form-control" style="width: 300px;"
                                                                value="<?php echo $this->session->userdata('trade_name'); ?>"
                                                                readonly>
                                                        </div>

                                                        <!-- <span class="input-group-btn">
                                                            <button 
                                                                class = "btn btn-info" 
                                                                type = "button"
                                                                ng-click = "ajaxLoadList('<?php echo base_url(); ?>index.php/leasing_transaction/get_tenantSoa/', dirty.trade_name)">
                                                                <i class = "fa fa-search"></i>
                                                                GENERATE
                                                            </button>
                                                        </span> -->

                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info" type="button"
                                                                ng-click="generateTenantSOA('<?php echo base_url(); ?>index.php/leasing_transaction/get_tenantSoa/')">
                                                                <i class="fa fa-search"></i>
                                                                GENERATE
                                                            </button>
                                                        </span>
                                                    </div>

                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($this->session->userdata('cfs_logged_in')): ?>
                                        <div class="row" style="margin-left:2%">
                                            <div class="form-group">
                                                <label for="tenancy_type" class="col-md-2 control-label text-right"><i
                                                        class="fa fa-asterisk"></i>Corporate Name</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" ng-value="dirty.corporate_name"
                                                        readonly="">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="row">
                                        <table class="table table-bordered" ng-table="ajaxTableParams"
                                            id="charges_table" style="margin-left:50px">

                                            <tbody id="soa_tbody">
                                                <tr ng-repeat="dt in ajaxData">
                                                    <td title="'Tenant ID'" sortable="'tenant_id'">{{dt.tenant_id}}</td>
                                                    <td title="'SOA No.'" sortable="'soa_no'">{{dt.soa_no}}</td>
                                                    <td title="'File Name'" sortable="'file_name'">{{dt.file_name}}</td>
                                                    <td title="'Collection Date'" sortable="'collection_date'">
                                                        {{dt.collection_date}}</td>
                                                    <td title="'Action'" align="center">
                                                        <?php if (!in_array($this->session->userdata('user_type'), ['IAD'])): ?>
                                                            <!-- gwaps addition -->
                                                            <button class="btn btn-info small-print-button" type="button"
                                                                data-toggle="modal" data-target="#manager_modal_soa"
                                                                ng-click="reprintSoaNew('<?php echo base_url(); ?>leasing/soaReprintNew/' + dt.file_name + '/' + dt.soa_no)">
                                                                Print
                                                            </button>
                                                        <?php endif; ?> <!-- gwaps addition -->
                                                        <button class="btn btn-success small-print-button" type="button"
                                                            ng-click="reprint_soa('<?php echo base_url(); ?>assets/pdf/' + dt.file_name)">
                                                            View
                                                        </button>

                                                        <?php if (!in_array($this->session->userdata('user_type'), ['cfs_logged_in', 'IAD'])): ?>
                                                            <!-- gwaps addition -->
                                                            <a class="btn btn-danger small-print-button" type="button"
                                                                href="<?php echo base_url(); ?>index.php/leasing_transaction/cancel_soa/{{dt.soa_no}}/{{dt.collection_date}}">
                                                                Delete
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr class="ng-cloak" ng-show="!ajaxData.length && !isLoading">
                                                    <td colspan="5">
                                                        <center>No Data Available.</center>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> <!-- EDITABLE GRID END ROW -->
                                </div>
                            </div>
                        </div> <!-- End of tab-content -->
                    </div>
                </div>
            </div> <!-- End of panel-body -->
        </div> <!-- End of panel -->
        <?php if ($this->session->userdata('cfs_logged_in')): ?>
            <!-- Tenant Lookup Modal -->
            <div class="modal fade" id="lookup">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-tv"></i> Tenant Look Up</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3 pull-right">
                                    <input type="text" class="form-control search-query" placeholder="Search Here..."
                                        ng-model="query2" ng-keydown="currentPage2 = 0" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><a href="#"
                                                        data-ng-click="sortBy = 'tenant_id'; reverse = !reverse">Tenant
                                                        ID</a></th>
                                                <th><a href="#"
                                                        data-ng-click="sortBy = 'trade_name'; reverse = !reverse">Trade
                                                        Name</a></th>
                                                <th><a href="#"
                                                        data-ng-click="sortBy = 'store_name'; reverse = !reverse">Store
                                                        Location</a></th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="ng-cloak" ng-show="dataList2.length!=0"
                                                ng-repeat="data in dataList2 | filter:query2 | orderBy:sortBy:reverse | offset: currentPage2*itemsPerPage2 | limitTo: itemsPerPage2">
                                                <td>{{data.tenant_id}}</td>
                                                <td>{{data.trade_name}}</td>
                                                <td>{{data.store_name}}</td>
                                                <td align="right">
                                                    <!-- Split button -->
                                                    <div class="btn-group" style="width:70% !important">
                                                        <button type="button" style="width:70% !important" ng-click="ajaxLoadList('<?= base_url(); ?>index.php/leasing_transaction/get_tenantSoa/',data.trade_name); 
                                                            check(data.trade_name); 
                                                            dirty.value =  data.trade_name;
                                                            dirty.corporate_name = data.corporate_name"
                                                            class="btn btn-xs btn-primary"
                                                            data-dismiss="modal">Select</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="ng-cloak"
                                                ng-show="dataList2.length==0 || (dataList2 | filter:query2).length == 0">
                                                <td colspan="6">
                                                    <center>No Data Available.</center>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="ng-cloak">
                                                <td colspan="6" style="padding: 5px;">
                                                    <div>
                                                        <ul class="pagination">
                                                            <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                                ng-class="prevPageDisabled2()">
                                                                <a href ng-click="prevPage2()"
                                                                    style="border-radius: 0px;"><i
                                                                        class="fa fa-angle-double-left"></i> Prev</a>
                                                            </li>
                                                            <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                                ng-repeat="n in range2()"
                                                                ng-class="{active: n == currentPage2}"
                                                                ng-click="setPage2(n)">
                                                                <a href="#">{{n+1}}</a>
                                                            </li>
                                                            <li ng-show="dataList2.length!=0 && (dataList2 | filter:query2).length != 0"
                                                                ng-class="nextPageDisabled2()">
                                                                <a href ng-click="nextPage2()"
                                                                    style="border-radius: 0px;">Next <i
                                                                        class="fa fa-angle-double-right"></i></a>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"><i
                                    class="fa fa-close"></i>
                                Close</button>
                        </div>
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
            <!-- End Tenant Lookup Modal -->
        <?php endif; ?>

        <!-- Manager's Key Modal -->
        <div class="modal fade" id="manager_modal_soa">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-key"></i> Manager's Key</h4>
                    </div>
                    <form action="" method="post" name="frm_manager_soa" id="frm_manager_soa">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><i class="fa fa-user"></i>
                                                    </div>
                                                    <input type="text" required class="form-control" ng-model="username"
                                                        id="username" name="username" autocomplete="off">
                                                </div>
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_manager_soa.username.$dirty && frm_manager_soa.username.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-addon squared"><i class="fa fa-key"></i>
                                                    </div>
                                                    <input type="password" required class="form-control"
                                                        ng-model="password" id="password" name="password"
                                                        autocomplete="off">
                                                </div>
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_manager_soa.password.$dirty && frm_manager_soa.password.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" ng-disabled="frm_manager_soa.$invalid"
                                    class="btn btn-primary button-b"> <i class="fa fa-unlock"></i> Submit</button>
                                <button type="button" class="btn btn-alert button-w" data-dismiss="modal"> <i
                                        class="fa fa-close"></i> Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </form>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>