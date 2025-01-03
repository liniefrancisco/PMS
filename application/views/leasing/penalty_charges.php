<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> PENALTY CHARGES</div>
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
                                <?php foreach ($penalty_lateopen as $value): ?>
                                    <tr>
                                        <td>Penalty for late Opening and Early Closing</td>
                                        <td>&#8369;
                                            <?php echo number_format($value['per_hour'], 2); ?> per 60 min. per sqm
                                        </td>
                                        <td><a href="#" ng-click="penalty_lateopen()" data-toggle="modal"
                                                data-target="#lateopening_modal" data-backdrop="static"
                                                data-keyboard="false" class="btn btn-warning btn-xs"><i class="fa fa-edit">
                                                    Update</i></a></td>
                                    </tr>
                                <?php endforeach ?>
                                <?php foreach ($penalty_latepayment as $value): ?>
                                    <tr>
                                        <td>Penalty for late Payment</td>
                                        <td>Interest(Previous account balance x
                                            <?php echo $value['multiplier']; ?> x no. of days deliquent/30 days)
                                        </td>
                                        <td><a href="#" href="#" ng-click="penalty_latepayment()" data-toggle="modal"
                                                data-target="#latepayment_modal" data-backdrop="static"
                                                data-keyboard="false" class="btn btn-warning btn-xs"><i class="fa fa-edit">
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




    <!-- Update Penalty Late Opening Modal -->
    <div class="modal fade" id="lateopening_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Penalty for late Opening and Early Closing
                    </h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_penalty_lateopen"
                        name="frm_lateopen" method="post">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="penalty_latopen" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Per 60 mins. per sq.</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>&#8369;</strong></div>
                                                <input type="text" required class="form-control currency"
                                                    ng-model="yourPenalty_lateopen" id="penalty_lateopen"
                                                    name="penalty_lateopen" ng-pattern="/^[0-9]*\.?[0-9]*$/"
                                                    autocomplete="off">
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_lateopen.penalty_lateopen.$dirty && frm_lateopen.penalty_lateopen.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_lateopen.penalty_lateopen.$dirty && frm_lateopen.penalty_lateopen.$error.pattern">
                                                    <p class="error-display">Only allows numeric value.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_lateopen.$invalid" class="btn btn-primary button-b">
                                <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Penalty Late Opening Modal -->



    <!-- Update Penalty Late Payment Modal -->
    <div class="modal fade" id="latepayment_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update Penalty for late Payment</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_penalty_latepayment"
                        name="frm_latepayment" method="post">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="penalty_latepayment" class="col-md-4 control-label text-right"><i
                                                class="fa fa-asterisk"></i>Multiplier</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><strong>x</strong></div>
                                                <input type="text" required class="form-control currency"
                                                    ng-model="yourPenalty_latepayment" id="penalty_latepayment"
                                                    name="penalty_latepayment" ng-pattern="/^[0-9]*\.?[0-9]*$/"
                                                    autocomplete="off">
                                            </div>
                                            <!-- FOR ERRORS -->
                                            <div class="validation-Error">
                                                <span
                                                    ng-show="frm_latepayment.penalty_latepayment.$dirty && frm_latepayment.penalty_latepayment.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span
                                                    ng-show="frm_latepayment.penalty_latepayment.$dirty && frm_latepayment.penalty_latepayment.$error.pattern">
                                                    <p class="error-display">Only allows numeric value.</p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="penalty_latepayment"
                                            class="col-md-4 control-label text-right">Computation</label>
                                        <div class="col-md-8">
                                            <label class="control-label">
                                                Interest(Previous account balance x {{ yourPenalty_latepayment }} x no.
                                                of days deliquent/30 days)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_latepayment.$invalid"
                                class="btn btn-primary button-b"> <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Penalty Late Payment Modal -->


</div> <!-- /.container -->
</body>