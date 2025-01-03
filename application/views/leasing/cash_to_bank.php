<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-money"></i> Cash To Bank Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="searchedKeyword" />
                    </div>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal"
                            data-target="#add_data" class="btn btn-success btn-medium  button-g"><i
                                class="fa fa-plus-circle"></i>
                            Add Data</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table="tableParams" ng-controller="tableController"
                            ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_cashtobank/')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat="bank in data">
                                    <td title="'Store Name'" sortable="'store_name'">{{ bank.store_name }}</td>
                                    <td title="'Bank Name'" sortable="'bank_name'">{{ bank.bank_name }}</td>
                                    <td title="'Bank Code'" sortable="'bank_code'">{{ bank.bank_code }}</td>
                                    <td title="'Action'" align="center">
                                        <!-- Split button -->
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
                                                    <a href="#" data-toggle="modal" data-backdrop="static"
                                                        data-keyboard="false" data-target="#update_modal"
                                                        ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_cashbankforupdat/' + bank.id); populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_selectedBanks/' + bank.store_id)">
                                                        <i class="fa fa-edit"></i> Update
                                                    </a>
                                                </li>
                                                <li>
                                                    <a
                                                        href="<?php echo base_url() ?>index.php/leasing_mstrfile/delete_cashbank/{{bank.id}}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="10">
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


    <!-- Add Cash Bank Modal -->
    <div class="modal fade" id="add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Add Data</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_cashtobank" method="post"
                        name="frm_add" id="frm_add">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Store Name</label>
                                        <div class="col-md-6">
                                            <select name="store_name" class="form-control" required
                                                ng-model="store_name"
                                                ng-change="populate_combobox('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_selectedBanks/' + store_name)">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <?php foreach ($stores as $store): ?>
                                                    <option value="<?php echo $store['id']; ?>"><?php echo $store['store_name']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="company_code" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Name</label>
                                        <div class="col-md-6">
                                            <select class="form-control" required name="bank_name" ng-model="bank_name"
                                                ng-change="viewing('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_selectedBankCode/' + bank_name)">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option ng-repeat="banks in itemList">{{banks.bank_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_code" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Code</label>
                                        <div class="col-md-6">
                                            <select class="form-control" required name="bank_code" ng-model="bank_code">
                                                <option value="" disabled="" selected="" style="display:none">Please
                                                    Select One</option>
                                                <option ng-repeat="code in viewList">{{code.bank_code}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_add.$invalid" class="btn btn-primary button-b"> <i
                                    class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Add Cash Bank Modal -->


    <!-- Update Cash Bank Modal -->
    <div class="modal fade" id="update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Area Type</h4>
                </div>
                <div class="modal-body" ng-repeat="cb in updateData">
                    <form action="{{ 'update_cashtobank/' + cb.id }}" method="post" name="frm_update" id="frm_update">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_name" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Store Name</label>
                                        <div class="col-md-6">
                                            <input type="text" name="store_name" ng-model="cb.store_name" readonly
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="company_code" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Name</label>
                                        <div class="col-md-6">
                                            <select class="form-control" required name="bank_name"
                                                ng-model="cb.bank_name"
                                                ng-change="viewing('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_selectedBankCode/' + cb.bank_name)"
                                                onchange="deleteFirstIndex('bank_code')">
                                                <option>{{cb.bank_name}}</option>
                                                <option ng-repeat="banks in itemList">{{banks.bank_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="store_code" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Bank Code</label>
                                        <div class="col-md-6">
                                            <select class="form-control" required id="bank_code" name="bank_code"
                                                ng-model="cb.bank_code">
                                                <option>{{cb.bank_code}}</option>
                                                <option ng-repeat="code in viewList">{{code.bank_code}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_update.$invalid" class="btn btn-primary button-b"> <i
                                    class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Cash Bank Modal -->


</div> <!-- /.container -->
</body>