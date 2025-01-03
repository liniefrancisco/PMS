<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> PRE OPERATION CHARGES</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-controller="tableController">
                            <thead>
                                <tr>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'description'; reverse = !reverse">Description</a>
                                    </th>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'uom'; reverse = !reverse">Computation</a></th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($security_bond as $value): ?>
                                    <tr>
                                        <td>Security Bond</td>
                                        <td>
                                            <?php echo $value['months']; ?>.
                                            <?php echo $value['description']; ?>
                                        </td>
                                        <td><a href="#" ng-click="security_bond();" data-toggle="modal"
                                                data-target="#secbond_modal" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-xs btn-warning button-caret"><i class="fa fa-edit">
                                                    Update</i></a></td>
                                    </tr>
                                <?php endforeach ?>

                                <?php foreach ($advance_rent as $value): ?>
                                    <tr>
                                        <td>Advance Rent</td>
                                        <td>
                                            <?php echo $value['months']; ?>.
                                            <?php echo $value['description']; ?>
                                        </td>
                                        <td><a href="#" ng-click="advance_rent();" data-toggle="modal"
                                                data-target="#adrent_modal" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-xs btn-warning button-caret"><i class="fa fa-edit">
                                                    Update</i></a></td>
                                    </tr>
                                <?php endforeach ?>

                                <?php foreach ($construction_bond as $value): ?>
                                    <tr>
                                        <td>Construction Bond</td>
                                        <td>
                                            <?php echo $value['months']; ?>.
                                            <?php echo $value['description']; ?>
                                        </td>
                                        <td><a href="#" ng-click="cons_bond();" data-toggle="modal"
                                                data-target="#consbond_modal" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-xs btn-warning button-caret"><i class="fa fa-edit">
                                                    Update</i></a></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

    <!-- Update Security Bond Modal -->
    <div class="modal fade" id="secbond_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Security Bond</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_secbond" method="post">
                        <div class="row">
                            <div class="col-md-10">

                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>No. of Months</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="security_bond"
                                                ng-model="yourSecurity_bond">
                                                <option>1 mo</option>
                                                <option>2 mos</option>
                                                <option>3 mos</option>
                                                <option>4 mos</option>
                                                <option>5 mos</option>
                                                <option>6 mos</option>
                                                <option>7 mos</option>
                                                <option>8 mos</option>
                                                <option>9 mos</option>
                                                <option>10 mos</option>
                                                <option>11 mos</option>
                                                <option>12 mos</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary button-b"> <i class="fa fa-save"></i> Save
                                Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Security Bond Modal -->



    <!-- Update Advance Rent Modal -->
    <div class="modal fade" id="adrent_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Advance Rent</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_adrent" method="post">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>No. of Months</label>
                                        <div class="col-md-8">
                                            <select class="form-control" required name="advance_rent"
                                                ng-model="yourAdvance_rent">
                                                <option>1 mo</option>
                                                <option>2 mos</option>
                                                <option>3 mos</option>
                                                <option>4 mos</option>
                                                <option>5 mos</option>
                                                <option>6 mos</option>
                                                <option>7 mos</option>
                                                <option>8 mos</option>
                                                <option>9 mos</option>
                                                <option>10 mos</option>
                                                <option>11 mos</option>
                                                <option>12 mos</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary button-b"> <i class="fa fa-save"></i> Save
                                Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Advance Rent Modal -->


    <!-- Update Construction Bond Modal -->
    <div class="modal fade" id="consbond_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Construction Bond</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_cons_bond" method="post">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="description" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>No. of Months</label>
                                        <div class="col-md-8">
                                            <select class="form-control" required name="cons_bond"
                                                ng-model="yourCons_bond">
                                                <option>1 mo</option>
                                                <option>2 mos</option>
                                                <option>3 mos</option>
                                                <option>4 mos</option>
                                                <option>5 mos</option>
                                                <option>6 mos</option>
                                                <option>7 mos</option>
                                                <option>8 mos</option>
                                                <option>9 mos</option>
                                                <option>10 mos</option>
                                                <option>11 mos</option>
                                                <option>12 mos</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary button-b"> <i class="fa fa-save"></i> Save
                                Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Construction Bond Modal -->



    <!-- Update Plywood Enclosure Modal -->
    <div class="modal fade" id="plywoodenc_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Plywood Enclosure</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_plywood_enc" method="post"
                        name="frm_update_plyenc">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="addon" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Additional</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control currency"
                                                ng-model="yourAddon" ng-pattern="/^[0-9]*\.?[0-9]*$/" id="addon"
                                                name="addon" autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update_plyenc.addon.$dirty && frm_update_plyenc.addon.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_update_plyenc.addon.$dirty && frm_update_plyenc.addon.$error.pattern">
                                                    <p class="error-display">Only allows numeric value</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="lmeter" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Per Linear Meter</label>
                                        <div class="col-md-8">
                                            <input type="text" required class="form-control currency"
                                                ng-model="yourLmeter" format="number" id="lmeter" name="lmeter"
                                                autocomplete="off">
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_update_plyenc.lmeter.$dirty && frm_update_plyenc.lmeter.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="lmeter" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Computation</label>
                                        <div class="col-md-8">
                                            <label class="control-label">Lease line + {{ yourAddon }} x &#8369; {{
                                                yourLmeter }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_update_plyenc.$invalid"
                                class="btn btn-primary button-b"> <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Plywood Enclosure Modal -->


    <!-- Update Construction Bond Modal -->
    <div class="modal fade" id="doorlock_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update PVC Door & Lock Set</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_door_lock"
                        name="frm_door_lock" method="post">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="per_set" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Per Set</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                <input type="text" required class="form-control currency"
                                                    ng-model="yourPer_set" format="number" id="per_set" name="per_set"
                                                    autocomplete="off">
                                                <!-- FOR ERRORS -->
                                                <div class="validation-Error">
                                                    <span
                                                        ng-show="frm_door_lock.per_set.$dirty && frm_door_lock.per_set.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_door_lock.$invalid" class="btn btn-primary button-b">
                                <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Construction Bond Modal -->


</div> <!-- /.container -->
</body>