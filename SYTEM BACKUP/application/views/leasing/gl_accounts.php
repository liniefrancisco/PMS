
<div class="container">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-clipboard"></i> G/L Accounts Setup</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                    <div class="col-md-3 pull-left">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#add_data" class = "btn btn-success btn-medium"><i class = "fa fa-plus-circle"></i> Add Data</a>
                    </div>                        
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_GLaccounts')">
                            
                            <tbody>
                                <tr class="ng-cloak"  ng-repeat= "dt in data">
                                    <td title="'G/L Code'" sortable = "'gl_code'">{{ dt.gl_code }}</td>
                                    <td title="'G/L Account'" sortable = "'gl_account'">{{ dt.gl_account }}</td>
                                    <?php if ($this->session->userdata('user_type') == 'Administrator'): ?>
                                    <td title="'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update_modal" ng-click="update('<?php echo base_url(); ?>index.php/leasing_mstrfile/get_glAccount_data/' + dt.id)"> <i class = "fa fa-edit"></i> Update</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#confirmation_modal" ng-click="delete('<?php echo base_url(); ?>index.php/leasing_mstrfile/delete_GLaccount/' + dt.id)"> <i class = "fa fa-trash"></i> Delete</a></li>
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

    
    <!-- Add Data Modal -->
    <div class="modal fade" id = "add_data">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Add G/L Account</h4>
                </div>
                <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/add_GLaccount" name="add_form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 ">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="gl_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>G/L Code</label>
                                        <div class="col-md-8">
                                            <input 
                                                type="text" 
                                                required 
                                                class="form-control" 
                                                ng-model="gl_code" 
                                                id="gl_code"
                                                name = "gl_code"
                                                is-unique
                                                is-unique-api="../ctrl_validation/verify_glCode/"
                                                autocomplete="off" >
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.gl_code.$dirty && add_form.gl_code.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.gl_code.$dirty && add_form.gl_code.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="gl_account" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>G/L Account</label>
                                        <div class="col-md-8">
                                            <textarea 
                                                ng-model="gl_account"
                                                required 
                                                class="form-control" 
                                                name = "gl_account" 
                                                id="gl_account"
                                                is-unique
                                                is-unique-api="../ctrl_validation/verify_glAccount/"
                                                rows="3"></textarea>
                                            
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="add_form.gl_account.$dirty && add_form.gl_account.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="add_form.gl_account.$dirty && add_form.gl_account.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>  
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




        <!-- Update Store Modal -->
    <div class="modal fade" id = "update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update G/L Account</h4>
                </div>
                <div class="modal-body" ng-repeat = "data in updateData">
                    <form  action = "{{ 'update_GLaccount/' + data.id }}" method="post" name = "frm_update" id = "frm_update">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="gl_code" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>G/L Code</label>
                                        <div class="col-md-8">
                                            <input 
                                                type="text" 
                                                required 
                                                class="form-control" 
                                                ng-model="data.gl_code" 
                                                id="gl_code" 
                                                name = "gl_code"
                                                is-unique-update
                                                is-unique-id = "{{data.id}}"
                                                is-unique-api="../ctrl_validation/verify_glCode_update/">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.gl_code.$dirty && frm_update.gl_code.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="frm_update.gl_code.$dirty && frm_update.gl_code.$error.unique">
                                                    <p class="error-display">Data already exist.</p>
                                                </span>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="gl_account" class="col-md-4 control-label text-right"><i class = "fa fa-asterisk"></i>G/L Account</label>
                                        <div class="col-md-8">
                                            <textarea 
                                                ng-model="data.gl_account"
                                                required 
                                                class="form-control" 
                                                name = "gl_account" 
                                                id="gl_account"
                                                rows="3">
                                            </textarea>
                                            
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span ng-show="frm_update.gl_account.$dirty && frm_update.gl_account.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>  
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


</div> <!-- /.container -->
</body>