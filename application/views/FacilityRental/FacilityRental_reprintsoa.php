<div class="container" ng-controller="FacilityRentalController">
    <div class="well" ng-init="populate_facilityrentalcustomer('<?php echo base_url(); ?>index.php/leasing_facilityrental/populate_facilityrentalcustomer/')">
        <div class="panel panel-default">
            <div class="panel-heading panel-leasing"><i class="fa fa-print"></i>Facility Rental Re-print SOA</div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#preop" aria-controls="preop" role="tab" data-toggle="tab">General </a></li>
                        </ul>
                        <div class="tab-content ng-cloak">
                            <div role="tabpanel" class="tab-pane active" id="preop">
                                <div class="col-md-11">
                                    <div class="row" style="margin-left:2%">
                                        <div class="form-group">
                                            <label for="tenant_id" class="col-md-2 control-label text-right"><i class="fa fa-asterisk"></i>Customer Name</label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <div mass-autocomplete>
                                                        <input 
                                                            required
                                                            name = "frcustomername"
                                                            ng-model = "dirty.value"
                                                            mass-autocomplete-item = "autocomplete_options"
                                                            class = "form-control"
                                                            id = "frcustomername">
                                                    </div>
                                                    <span class="input-group-btn">
                                                        <button 
                                                            class = "btn btn-info" 
                                                            type = "button"
                                                            ng-click = "ajaxLoadList('<?php echo base_url(); ?>index.php/leasing_facilityrental/get_frSoa/', dirty.value)"><i class = "fa fa-search"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <table class="table table-bordered" ng-table = "ajaxTableParams" id="reprintsoa_table" style="margin-left:50px" >
                                            
                                            <tbody id="soa_tbody">
                                                <tr ng-repeat = "dt in ajaxData">
                                                    <td title = "'Facility Rental No.'" sortable = "'facilityrental_no'">{{dt.facilityrental_no}}</td>
                                                    <td title = "'SOA No.'" sortable = "'soa_no'">{{dt.soa_no}}</td>
                                                    <td title = "'File Name'" sortable = "'file_name'">{{dt.file_name}}</td>
                                                    <td title = "'Collection Date'" sortable = "'collection_date'">{{dt.billing_period}}</td>
                                                    <td title = "'Action'">
                                                        <button
                                                            class = "btn btn-success small-print-button" 
                                                            type = "button"
                                                            ng-click = "reprint_soa('<?php echo base_url(); ?>assets/facilityrental_pdf/' + dt.file_name)">
                                                            <i class = "fa fa-print"></i>
                                                        </button>
                                                        <a
                                                            class = "btn btn-warning small-print-button" 
                                                            type = "button"
                                                            href = "<?php echo base_url(); ?>index.php/leasing_facilityrental/cancel_soa/{{dt.soa_no}}/{{dt.facilityrental_no}}">
                                                            <i class = "fa fa-close"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr class="ng-cloak" ng-show="!ajaxData.length && !isLoading">
                                                    <td colspan="5"><center>No Data Available.</center></td>
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
    </div> <!-- End of Well -->
</div> <!-- End of Container -->
</body>