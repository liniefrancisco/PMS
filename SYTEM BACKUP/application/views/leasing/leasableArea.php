
<div class="container" >
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Leasable Area</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-controller="tableController" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_reports/get_availableArea')">
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "dt in data">
                                    <td title="'Slot No.'" sortable = "'slot_no'">{{ dt.slot_no }}</td>
                                    <td title="'Tenancy Type'" sortable = "'tenancy_type'">{{ dt.tenancy_type }}</td>
                                    <td title="'Store/Property'" sortable = "'store_name'">{{ dt.store_name }}</td>
                                    <td title="'Floor Location'" sortable = "'floor_name'">{{ dt.floor_name }}</td>
                                    <td title="'Floor Area'" sortable = "'floor_area'">{{ dt.floor_area | currency : '' }}</td>
                                    <td title="'Rental Rate'" sortable = "'rental_rate'">{{ dt.rental_rate | currency : '&#8369;' }}</td>
                                    <td title="'Status'" sortable = "'status'"><span class="green">Vacant</span></td>
                                </tr>
                                <tr class="ng-cloak" ng-show="!data.length && !isLoading">
                                    <td colspan="9"><center>No Data Available.</center></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END OF WELL DIV  -->
</div> <!-- End of Container -->

</body>
