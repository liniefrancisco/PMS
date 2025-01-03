
<div class="container" ng-controller="tableController">
    <div class="well">
        <div class="panel panel-default">
          <!-- Default panel contents -->
            <div class="panel-heading panel-leasing"><i class="fa fa-list"></i> Terminated Long Term Contracts</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <input type = "text" class="form-control search-query" placeholder="Search Here..." ng-model="searchedKeyword"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-table = "tableParams" ng-init="loadList('<?php echo base_url(); ?>index.php/leasing_transaction/terminated_ltenant')">
                            
                            <tbody>
                                <tr class="ng-cloak" ng-repeat= "dt in data">
                                    <td title = "'Tenant ID'" sortable = "'tenant_id'">{{dt.tenant_id}}</td>
                                    <td title = "'Trade Name'" sortable = "'trade_name'">{{dt.trade_name}}</td>
                                    <td title = "'Date of Termination'" sortable = "'termination_date'">{{dt.termination_date}}</td>
                                    <td title = "'Reason of Termination'" sortable = "'reason'">{{dt.reason}}</td>
                                    <td title = "'Action'">
                                        <!-- Split button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-danger">Action</button>
                                            <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a 
                                                        data-toggle="modal" data-target="#confirmation1_modal"
                                                        href="#"
                                                        ng-click = "confirm('<?php echo base_url(); ?>index.php/leasing_transaction/renew_contract/' + dt.id + '/' + dt.prospect_id)"> 
                                                        <i class = "fa fa-pencil-square"></i> Renew Contract
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
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
    
</div> <!-- /.container -->
</body>