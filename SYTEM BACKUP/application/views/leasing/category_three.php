
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Third Level Category</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" ng-click="populate_categoryOne('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_category_one')" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>    
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_category_three/')">
                            
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "category in data">
                                    <td title = "'First Level Category'" sortable = "'first_level'">{{ category.first_level }}</td>
                                    <td title = "'Second Level Category'" sortable = "'second_level'">{{ category.second_level }}</td>
                                    <td title = "'Third Level Category'" sortable = "'third_level'">{{ category.third_level }}</td>
                                    <td title = "'Description'" sortable = "'description'">{{ category.description }}</td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <td title = "'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_categoryThree_data/' + category.id); populate_categoryOne('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_category_one')"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_categoryThree/' + category.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>    
                                    <?php endif ?>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="5"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

    <!-- Update Store Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Category</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form action = "{{ 'update_categoryThree/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="first_level"><i class = "fa fa-asterisk"></i>First Level Category</label>
                                    <select 
                                        class="form-control" 
                                        required 
                                        ng-model="data.first_level" 
                                        name = "first_level" 
                                        id = "first_level"
                                        ng-change = "populate_categoryTwo('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryTwo/' + data.first_level)"
                                        onchange = "deleteFirstIndex('update_second_level')">
                                        <option ng-repeat="one in categoryOne">{{one.category_name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="second_level"><i class = "fa fa-asterisk"></i>Second Level Category</label>
                                    <select 
                                        class="form-control" 
                                        required 
                                        ng-model="data.second_level" 
                                        name = "second_level" 
                                        id = "update_second_level">
                                        <option>{{data.second_level}}</option>
                                        <option ng-repeat="two in categoryTwo">{{two.second_level}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="third_level"><i class = "fa fa-asterisk"></i>Third Level Category</label>
                                    <input 
                                        type="text" 
                                        required 
                                        ng-minlength="3" 
                                        class="form-control" 
                                        ng-model="data.third_level" 
                                        id="third_level"
                                        name = "third_level"
                                        is-unique-update
                                        is-unique-id = "{{data.id}}"
                                        is-unique-api="../ctrl_validation/verify_categoryThree_update/"
                                        autocomplete="off" >
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span ng-show="frm_update.third_level.$dirty && frm_update.third_level.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                        <span ng-show="frm_update.third_level.$dirty && frm_update.third_level.$error.minlength">
                                            <p class="error-display">Minimum of 3 characters</p>
                                        </span>
                                        <span ng-show="frm_update.third_level.$dirty && frm_update.third_level.$error.unique">
                                            <p class="error-display">Data already exist.</p>
                                        </span>
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label for="second_level"><i class = "fa fa-asterisk"></i>Description</label>
                                    <textarea 
                                        required
                                        rows="3" 
                                        class="form-control" 
                                        ng-model="data.description"
                                        ng-minlength="3" 
                                        name = "description">
                                    </textarea>
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span ng-show="frm_update.description.$dirty && frm_update.description.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                        <span ng-show="frm_update.description.$dirty && frm_update.description.$error.minlength">
                                            <p class="error-display">Minimum of 3 characters</p>
                                        </span>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class = "fa fa-close"></i> Close</button>
                            <button type="submit" ng-disabled = "frm_update.$invalid" class="btn btn-primary"> <i class = "fa fa-save"></i> Save Changes</button>
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
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add Category</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_category_three" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="first_level"><i class = "fa fa-asterisk"></i>First Level Category</label>
                                    <select 
                                        class="form-control" 
                                        required 
                                        ng-model="first_level" 
                                        name = "first_level" 
                                        id = "first_level"
                                        ng-change = "populate_categoryTwo('<?php echo base_url(); ?>index.php/leasing_mstrfile/populate_categoryTwo/' + first_level)">
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="one in categoryOne">{{one.category_name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="first_level"><i class = "fa fa-asterisk"></i>Second Level Category</label>
                                    <select 
                                        class="form-control" 
                                        required 
                                        ng-model="second_level" 
                                        name = "second_level" 
                                        id = "second_level">
                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                        <option ng-repeat="two in categoryTwo">{{two.second_level}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="third_level"><i class = "fa fa-asterisk"></i>Third Level Category</label>
                                    <input 
                                        type="text" 
                                        required 
                                        ng-minlength="5" 
                                        class="form-control" 
                                        ng-model="third_level" 
                                        id="third_level"
                                        name = "third_level"
                                        is-unique
                                        is-unique-api="../ctrl_validation/verify_category_three/"
                                        autocomplete="off" >
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span ng-show="add_form.third_level.$dirty && add_form.third_level.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                        <span ng-show="add_form.third_level.$dirty && add_form.third_level.$error.minlength">
                                            <p class="error-display">Minimum of 5 characters</p>
                                        </span>
                                        <span ng-show="add_form.third_level.$dirty && add_form.third_level.$error.unique">
                                            <p class="error-display">Data already exist.</p>
                                        </span>
                                    </div>  
                                </div>

                                <div class="form-group">
                                    <label for="description"><i class = "fa fa-asterisk"></i>Description</label>
                                    <textarea 
                                        required
                                        rows="3" 
                                        class="form-control" 
                                        ng-model="description"
                                        ng-minlength="3" 
                                        name = "description">
                                    </textarea>
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span ng-show="add_form.description.$dirty && add_form.description.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                        <span ng-show="add_form.description.$dirty && add_form.description.$error.minlength">
                                            <p class="error-display">Minimum of 3 characters</p>
                                        </span>
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