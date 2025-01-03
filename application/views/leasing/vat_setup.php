<div class="container">
    <div class="well">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> VAT & Annual Rental Incrementation Setup
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type="text" class="form-control search-query" placeholder="Search Here..."
                            ng-model="query" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><a href="#"
                                            data-ng-click="sortField = 'description'; reverse = !reverse">Description</a>
                                    </th>
                                    <th><a href="#" data-ng-click="sortField = 'amount'; reverse = !reverse">Amount</a>
                                    </th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Value Added Tax</td>
                                    <td>
                                        <?php echo $vat; ?> % of Basic rent or percentage rent or sum of basic and
                                        percentage rent
                                    </td>
                                    <td align="center">
                                        <a href="#" data-toggle="modal" data-target="#update_modal"
                                            data-backdrop="static" data-keyboard="false"
                                            class="btn btn-xs btn-warning button-carety"><i class="fa fa-edit"></i>
                                            Update</a>
                                    </td>
                                </tr>
                                <?php foreach ($rental_increment as $value): ?>
                                    <tr>
                                        <td>Annual Rental Increment</td>
                                        <td>
                                            <?php echo number_format($value['amount'], 2); ?> %
                                        </td>
                                        <td align="center">
                                            <a href="#" data-toggle="modal" ng-click="rentalInc()"
                                                data-target="#rentalInc_modal" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-xs btn-warning button-carety"><i class="fa fa-edit"></i>
                                                Update</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php foreach ($wtax as $value): ?>
                                    <tr>
                                        <td>Withholding Tax</td>
                                        <td>
                                            <?php echo number_format($value['withholding'], 2); ?> %
                                        </td>
                                        <td align="center">
                                            <a href="#" data-toggle="modal" ng-click="wtax()" data-target="#wtax_modal"
                                                data-backdrop="static" data-keyboard="false"
                                                class="btn btn-xs btn-warning button-carety"><i class="fa fa-edit"></i>
                                                Update</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->

    <!-- Update Store Modal -->
    <div class="modal fade" id="update_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="top: -20px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update VAT</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_vat" method="post"
                        name="frm_update" id="frm_update">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="vat"><i class="fa fa-asterisk"></i>Value Added Tax</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>%</strong></div>
                                        <input type="number" class="form-control currency" value="<?php echo $vat; ?>"
                                            id="vat" name="vat" autocomplete="off">
                                        <!-- FOR ERRORS -->
                                        <div class="validation-Error">
                                            <span ng-show="frm_update.vat.$dirty && frm_update.vat.$error.required">
                                                <p class="error-display">This field is required.</p>
                                            </span>
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
    <!-- End Update Store Modal -->

    <!-- Annual Increment Modal -->
    <div class="modal fade" id="rentalInc_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="top: -20px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update VAT</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_rentalInc" method="post"
                        name="frm_rentalInc" id="frm_rentalInc">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="rental_inc"><i class="fa fa-asterisk"></i>Annual Rental
                                        Increment</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>%</strong></div>
                                        <input type="text" class="form-control currency" ng-model="yourRental_inc"
                                            required format="number" id="rental_inc" name="rental_inc"
                                            autocomplete="off">
                                    </div>
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span
                                            ng-show="frm_rentalInc.rental_inc.$dirty && frm_rentalInc.rental_inc.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_rentalInc.$invalid" class="btn btn-primary button-b">
                                <i class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Annual Increment Modal -->

    <!-- Withholding Tax Modal -->
    <div class="modal fade" id="wtax_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="top: -20px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Update VAT</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url(); ?>index.php/leasing_mstrfile/update_wtax" method="post"
                        name="frm_wtax" id="frm_wtax">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="wtax"><i class="fa fa-asterisk"></i>Withholding Tax</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><strong>%</strong></div>
                                        <input type="text" class="form-control currency" ng-model="yourWtax" required
                                            format="number" id="wtax" name="wtax" autocomplete="off">
                                    </div>
                                    <!-- FOR ERRORS -->
                                    <div class="validation-Error">
                                        <span ng-show="frm_wtax.wtax.$dirty && frm_wtax.wtax.$error.required">
                                            <p class="error-display">This field is required.</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" ng-disabled="frm_wtax.$invalid" class="btn btn-primary button-b"> <i
                                    class="fa fa-save"></i> Save Changes</button>
                            <button type="button" class="btn btn-danger button-r" data-dismiss="modal"> <i
                                    class="fa fa-close"></i> Close</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Withholding Tax Modal -->

</div> <!-- /.container -->
</body>