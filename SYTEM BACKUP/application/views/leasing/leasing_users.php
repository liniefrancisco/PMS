
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
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" width="100%" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_leasing_users')">

                            <tbody>
                                <tr class="ng-cloak"  ng-repeat= "user in data">
                                    <td title="'Full Name'" sortable = "'first_name'">
                                        <div ng-if = "user.middle_name != ''">
                                            {{ user.first_name }} {{ user.middle_name.charAt(0) }}. {{ user.last_name }}
                                        </div>
                                        <div ng-if = "user.middle_name == ''">
                                            {{ user.first_name }} {{ user.last_name }}
                                        </div>
                                    </td>
                                    <td title="'Username'" sortable = "'username'">{{ user.username }}</td>
                                    <td title="'User Type'" sortable = "'user_type'">{{ user.user_type }}</td>
                                    <td title="'Full Group'" sortable = "'store_name'">
                                        <div ng-if = "user.store_name">
                                            {{ user.store_name }}
                                        </div>
                                        <div ng-if = "!user.store_name">
                                            {{ user.user_type }}
                                        </div>
                                    </td>
                                    <td title="'Status'" sortable = "'status'">{{ user.status }}</td>
                                    <td title="'Action'">
                                        <div class="btn-group">
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
                                        </div>
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
    </div> <!-- END OF WELL DIV  -->

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
                                <div class="row">
                                    <div class="form-group">
                                        <label for="first_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>First Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="data.first_name"
                                                id="first_name"
                                                name = "first_name"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.first_name.$dirty && frm_update.first_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="middle_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Middle Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                class="form-control"
                                                ng-model="data.middle_name"
                                                id="middle_name"
                                                name = "middle_name"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="last_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Last Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="data.last_name"
                                                id="last_name"
                                                name = "last_name"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.last_name.$dirty && frm_update.last_name.$error.required">
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
                                                <option>Bank Recon</option>
                                                <option>Super Admin</option>
                                                <option>Administrator</option>
                                                <option>CFS</option>
                                                <option>Store Manager</option>
                                                <option>General Manager</option>
                                                <option>Supervisor</option>
                                                <option>Documentation Officer</option>
                                                <option>Accounting Staff</option>
                                                <option>IAD</option>
                                                <option>Corporate Documentation Officer</option>
                                                <option>Corporate CFS</option>
                                                <option>Corporate Accounting Staff</option>
                                                <option>Corporate Leasing Supervisor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row" ng-show="data.user_type != 'Administrator' && data.user_type != 'Corporate Documentation Officer' && data.user_type != 'Corporate CFS' && data.user_type != 'Corporate Accounting Staff' && data.user_type != 'Corporate Leasing Supervisor'">
                                    <div class="form-group">
                                        <label for="user_group" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>User Group</label>
                                        <div class="col-md-8">
                                            <select
                                                class = "form-control"
                                                name = "user_group"
                                                required
                                                id = "user_group">
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
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Update Store Modal -->

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
                                        <label for="first_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>First Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="first_name"
                                                id="first_name"
                                                name = "first_name"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.first_name.$dirty && add_form.first_name.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="middle_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Middle Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                class="form-control"
                                                ng-model="middle_name"
                                                id="middle_name"
                                                name = "middle_name"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label for="last_name" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>Last Name</label>
                                        <div class="col-md-8">
                                            <input
                                                type="text"
                                                required
                                                class="form-control"
                                                ng-model="last_name"
                                                id="last_name"
                                                name = "last_name"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
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
                                                <option>Bank Recon</option>
                                                <option>Super Admin</option>
                                                <option>Administrator</option>
                                                <option>CFS</option>
                                                <option>Store Manager</option>
                                                <option>General Manager</option>
                                                <option>Supervisor</option>
                                                <option>Documentation Officer</option>
                                                <option>Accounting Staff</option>
                                                <option>IAD</option>
                                                <option>Corporate Documentation Officer</option>
                                                <option>Corporate CFS</option>
                                                <option>Corporate Accounting Staff</option>
                                                <option>Corporate Leasing Supervisor</option>
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
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" ng-disabled = "add_form.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Data Modal -->

</div> <!-- /.container -->

</body>
