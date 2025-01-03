
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-users"></i> User Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium button-g"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm" ng-table = "tableParams" width="100%" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_leasing_users')">
                            <tbody>
                                <tr class="ng-cloak"  ng-repeat= "user in data">
                                    <!-- <td title="Check All">
                                        <input type="checkbox" name="check_{{user.id}}" value="{{user.id}}">
                                    </td> -->
                                    <td title="'Full Name'" sortable = "'name'">
                                        {{ user.name }}
                                    </td>
                                    <td title="'Username'" sortable = "'username'">{{ user.username }}</td>
                                    <td title="'User Type'" sortable = "'user_type'">{{ user.user_type }}</td>
                                    <td title="'User Group'" sortable = "'store_name'">
                                        <div ng-if = "user.store_name">
                                            {{ user.store_name }}
                                        </div>
                                        <div ng-if = "!user.store_name">
                                            {{ user.user_type }}
                                        </div>
                                    </td>
                                    <td title="'Status'" sortable = "'status'">
                                        <span ng-if="user.status !== 'Active'" style="color: red;"><i>{{ user.status }}</i></span>
                                        <span ng-if="user.status === 'Active'" style="color: green;"><strong>{{ user.status }}</strong></span>
                                    </td>
                                    <td title="'Action'" class="text-center">
                                        <a type="button" class="btn btn-xs btn-info button-caretb" href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_leasingUser_data/' + user.id)"><i class = "fa fa-edit"></i> Update</a>
                                        <a type="button" class="btn btn-xs btn-warning button-carety" href="<?php echo base_url(); ?>index.php/leasing_mstrfile/reset_password/{{user.id}}" > <i class = "fa fa-refresh"></i> Reset Password</a>
                                        <a type="button" class="btn btn-xs btn-success button-caretg" ng-if = "user.status == 'Blocked'" href="<?php echo base_url(); ?>index.php/leasing_mstrfile/activate_user/{{user.id}}"><i class = "fa fa-check-square"></i> Activate User</a>
                                        <a type="button" class="btn btn-xs btn-danger button-caret" ng-if = "user.status == 'Active'" href="<?php echo base_url(); ?>index.php/leasing_mstrfile/block_user/{{user.id}}"><i class = "fa fa-ban"></i> Block User</a>
                                        <a type="button" class="btn btn-xs btn-danger button-caret" href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_user/' + user.id)"><i class = "fa fa-trash"></i> Delete</a>


                                        <!-- <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li ng-if = "user.status == 'Active'"><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/block_user/{{user.id}}"> <i class = "fa fa-ban"></i> Block User</a></li>
                                                <li ng-if = "user.status == 'Blocked'"><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/activate_user/{{user.id}}" > <i class = "fa fa-check-square"></i> Activate User</a></li>
                                                <li><a href="<?php echo base_url(); ?>index.php/leasing_mstrfile/reset_password/{{user.id}}" > <i class = "fa fa-refresh"></i> Reset Password</a></li>
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_leasingUser_data/' + user.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_user/' + user.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div> -->
                                    </td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="7"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- END OF WELL DIV  -->

    <!-- Update Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Leasing User</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "{{ 'update_leasingUser/' + data.id }}" method="post" novalidate name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-11">
                                <!-- <div class="row">
                                    <div class="form-group">
                                        <label for="name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type     ="text"
                                                required
                                                class    ="form-control"
                                                ng-model ="data.name"
                                                id       ="name"
                                                name     ="name"
                                                autocomplete="off">
                                                <div class="validation-Error">
                                                    <span ng-show="frm_update.name.$dirty && frm_update.name.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row">
                                    <div class="form-group">
                                        <label for="u_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Name</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="u_name" name="u_name" ng-model="data.name" ng-keyup="searchEmpUpdate($event)" placeholder="Name" autocomplete="off" required>
                                            <!-- <input type="hidden" class="form-control" id="emp_id" ng-model="model_empid"> -->
                                            <div class="search-results" ng-repeat="n in u_names" ng-if="hasResults_u == 1">
                                                <a href="#" ng-repeat="n in u_names track by $index" ng-click="getnameUpdate(n)">
                                                    {{n.employee_name}}<br>
                                                </a>
                                            </div>
                                            <div class="search-results" ng-repeat="n in u_names" ng-if="hasResults_u == 0">
                                                <a href="#" ng-repeat="n in u_names">
                                                    {{n.employee_name}} <br>
                                                </a>
                                            </div>
                                            <div class="validation-Error">
                                                <span ng-show="add_form.u_name.$dirty && add_form.u_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="username" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Username</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="data.username"
                                                id="username"
                                                name = "username"
                                                autocomplete="off"
                                                ng-minlength="5"
                                                is-unique-update
                                                is-unique-id = "{{data.id}}"
                                                is-unique-api = "<?php echo base_url(); ?>index.php/ctrl_validation/verify_username_update/"
                                                autocomplete = "off"/>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.username.$dirty && frm_update.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_update.username.$dirty && frm_update.username.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                                <span ng-show="frm_update.username.$dirty && frm_update.username.$error.unique">
                                                    <p class="error-display">Username is already taken.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="user_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>User Type</label>
                                        <div class="col-md-8">
                                            <select
                                                class = "form-control"
                                                name = "user_type"
                                                required
                                                ng-model="data.user_type"
                                                id = "user_type">
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option>Accounting Staff</option>
                                                <option>Administrator</option>
                                                <option>Bank Recon</option>
                                                <option>CFS</option>
                                                <option>Corporate Accounting Staff</option>
                                                <option>Corporate CFS</option>
                                                <option>Corporate Documentation Officer</option>
                                                <option>Corporate Leasing Supervisor</option>
                                                <option>Documentation Officer</option>
                                                <option>General Manager</option>
                                                <option>IAD</option>
                                                <option>Legal</option>
                                                <option>Supervisor</option>
                                                <option>Super Admin</option>
                                                <option>Store Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-show="data.user_type != 'Administrator' && data.user_type != 'Corporate Documentation Officer' && data.user_type != 'Corporate CFS' && data.user_type != 'Corporate Accounting Staff' && data.user_type != 'Corporate Leasing Supervisor'">
                                    <div class="form-group">
                                        <label for="user_group" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>User Group</label>
                                        <div class="col-md-8">
                                            <select class = "form-control" name = "user_group" required id = "user_group">
                                                <option selected="selected">{{data.store_name}}</option>
                                                <?php foreach ($stores as $value): ?>
                                                    <option><?php echo $value['store_name']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary button-b"> <i class = "fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->

    <style type="text/css">
        .search-results {
            box-shadow: 5px 5px 5px #ccc;
            margin-top: 1px;
            margin-left: 0px;
            background-color: #ffffff;
            width: 98%;
            border-radius: 3px 3px 3px 3px;
            font-size: 18x;
            padding: 8px 10px;
            display: block;
            position: absolute;
            z-index: 9999;
            max-height: 300px;
            overflow-y: scroll;
            overflow: auto;
            border-color: gray;
        }
    </style>

    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Leasing User</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_leasing_user" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="last_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Name</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="last_name" name="last_name" ng-model="last_name" ng-keyup="searchEmp($event)" placeholder="Search Name" autocomplete="off" required>
                                            <input type="hidden" class="form-control" id="emp_id" ng-model="model_empid">
                                            <div class="search-results" ng-repeat="n in names" ng-if="hasResults == 1">
                                                <a href="#" ng-repeat="n in names track by $index" ng-click="getname(n)">
                                                    {{n.employee_name}}<br>
                                                </a>
                                            </div>
                                            <div class="search-results" ng-repeat="n in names" ng-if="hasResults == 0">
                                                <a href="#" ng-repeat="n in names">
                                                    {{n.employee_name}} <br>
                                                </a>
                                            </div>
                                            <div class="validation-Error">
                                                <span ng-show="add_form.last_name.$dirty && add_form.last_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="username" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Username</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="username"
                                                id="username"
                                                name = "username"
                                                autocomplete="off"
                                                ng-minlength="5"
                                                is-unique
                                                is-unique-api = "<?php echo base_url(); ?>index.php/ctrl_validation/verify_username/"
                                                autocomplete = "off"/>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.username.$dirty && add_form.username.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.username.$dirty && add_form.username.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                                <span ng-show="add_form.username.$dirty && add_form.username.$error.unique">
                                                    <p class="error-display">Username is already taken.</p>
                                                </span>
                                                <span ng-show="add_form.username.$dirty && add_form.username.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="password" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Password</label>
                                        <div class="col-md-8">
                                            <input
                                                type="password"
                                                required
                                                class="form-control"
                                                ng-model="password"
                                                id="password"
                                                name = "password"
                                                ng-minlength = "5"
                                                autocomplete="off"
                                                ng-pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.password.$dirty && add_form.password.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.password.$dirty && add_form.password.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="add_form.password.$dirty && add_form.password.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="cpassword" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Re-Password</label>
                                        <div class="col-md-8">
                                            <input
                                                type="password"
                                                required
                                                class="form-control"
                                                ng-model="cpassword"
                                                id="cpassword"
                                                name = "cpassword"
                                                data-password-verify="password">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.cpassword.$dirty && add_form.cpassword.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.cpassword.$dirty && add_form.cpassword.$error.passwordVerify">
                                                    <p class="error-display">Password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <label for="user_type" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>User Type</label>
                                        <div class="col-md-8">
                                            <select
                                                class = "form-control"
                                                name = "user_type"
                                                required
                                                ng-model="user_type"
                                                id = "user_type">
                                                <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                <option>Accounting Staff</option>
                                                <option>Administrator</option>
                                                <option>Bank Recon</option>
                                                <option>CFS</option>
                                                <option>Corporate Accounting Staff</option>
                                                <option>Corporate CFS</option>
                                                <option>Corporate Documentation Officer</option>
                                                <option>Corporate Leasing Supervisor</option>
                                                <option>Consolidator Admin</option>
                                                <option>Documentation Officer</option>
                                                <option>General Manager</option>
                                                <option>IAD</option>
                                                <option>Legal</option>
                                                <option>Supervisor</option>
                                                <option>Super Admin</option>
                                                <option>Store Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-show="user_type != 'Administrator' && user_type != 'Corporate Documentation Officer' && user_type != 'Corporate CFS' && user_type != 'Corporate Accounting Staff' && user_type != 'Corporate Leasing Supervisor'">
                                    <div class="form-group">
                                        <label for="user_group" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>User Group</label>
                                        <div class="col-md-8">
                                            <select
                                                class = "form-control"
                                                name = "user_group"
                                                required
                                                id = "user_group">
                                                <?php foreach ($stores as $value): ?>
                                                    <option><?php echo $value['store_name']; ?></option>
                                                <?php endforeach ?>
                                                <option>ALL USER GROUP</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "add_form.$invalid" class="btn btn-primary button-b"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- /.container -->

</body>
