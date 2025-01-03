<div class="container" ng-controller = "CtrlChart">
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<div class="panel panel-default">
					<div class="panel-heading panel-leasing"><i class="fa fa-pie-chart"></i> Dashboard/Charts</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">

								<div class="panel panel-default">
									<div class="panel-heading">Panel 1 <a href="#" ng-click = "printChart('line', '<?php echo ($storeDetails[0]['store_name']); ?>', '<?php echo ($storeDetails[0]['store_address']); ?>')" class="pull-right"><i class="fa fa-print print-dashboard"></i></a></div>
									<div class="panel-body">
										<div id = "lineChart" class="ng-cloak" ng-init = "lineChart()">
											<canvas 
						                        id="line"
						                        class="chart chart-line" 
						                        chart-data="lineData"
						                        chart-labels="lineLabel" 
						                        chart-legend="true" 
						                        chart-series="lineSeries"
						                        chart-click="onClick" 
						                        chart-colours = "lineColours">
						                    </canvas>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">Panel 2 <a href="#" class="pull-right"><i class="fa fa-print print-dashboard"></i></a></div>
									<div class="panel-body">
										<div class="ng-cloak" ng-init = "barChart()">
											<canvas 
						                        id="bar"
						                        class="chart chart-bar" 
						                        chart-data="barData"
						                        chart-labels="barLabels" 
						                        chart-legend="true" 
						                        chart-series="barSeries"
						                        chart-colours = "barColours">
						                    </canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">Panel 3 <a href="#" class="pull-right"><i class="fa fa-print print-dashboard"></i></a></div>
									<div class="panel-body">
										<div class="ng-cloak" ng-init = "doughnutChart()" id='canvasContainer'>
											<canvas id="doughnut" class="chart chart-doughnut"
											  	chart-data="doughnutData" chart-labels="doughnutLabels">
											</canvas> 
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">Panel 4 <a href="#" class="pull-right"><i class="fa fa-print print-dashboard"></i></a></div>
									<div class="panel-body">
										<div class="ng-cloak" ng-init = "pieChart()" id='canvasContainer'>
											<canvas id="pie" class="chart chart-pie"
											  	chart-data="pieData" chart-labels="pielabels">
											</canvas> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- ./ container -->