<?php
$page_title = "Dashboard";
require_once '../layout/header.php';
?>
<link rel="stylesheet" href="../../resources/datatables/buttons.bootstrap.min.css">
<style>
	#brand-logo {
		display: grid;
		place-items: center;
	}

	#brand-logo img {
		width: 80px;
		height: 80px;
	}

	.widget {
		margin: 0 0 25px 0;
		display: block;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
	}

	.widget-heading {
		padding: 7px 15px;
		-webkit-border-radius: 2px 2px 0 0;
		-moz-border-radius: 2px 2px 0 0;
		border-radius: 2px 2px 0 0;
		text-transform: uppercase;
		text-align: center;
	}

	.widget-body {
		color: white;
	}

	.widget-footer {
		padding: 7px 15px;
		-webkit-border-radius: 0 0 2px 2px;
		-moz-border-radius: 0 0 2px 2px;
		border-radius: 0 0 2px 2px;
		text-transform: uppercase;
		text-align: center;
	}

	#widget-products-heading {
		background: #00add7;
		color: white;
	}

	#widget-products-body {
		padding: 10px 15px;
		font-size: 36px;
		font-weight: 300;
		background: #00c0ef;
	}

	#widget-products-footer {
		background: #00add7;
		color: white;
	}

	#widget-customer-heading {
		background: #008548;
		color: white;
	}

	#widget-customer-body {
		padding: 10px 15px;
		font-size: 36px;
		font-weight: 300;
		background: #00a65a;
	}

	#widget-customer-footer {
		background: #008548;
		color: white;
	}

	#widget-suppliers-heading {
		background: #c37d0e;
		color: white;
	}

	#widget-suppliers-body {
		padding: 10px 15px;
		font-size: 36px;
		font-weight: 300;
		background: #f39c12;
	}

	#widget-suppliers-footer {
		background: #c37d0e;
		color: white;
	}

	#widget-sales-heading {
		background: #b13c2e;
		color: white;
	}

	#widget-sales-body {
		padding: 10px 15px;
		font-size: 36px;
		font-weight: 300;
		background: #dd4b39;
	}

	#widget-sales-footer {
		background: #b13c2e;
		color: white;
	}

	.shop-hr {
		margin-top: 0px;
		margin-bottom: 10px;
	}

	.shop-well {
		background: #222d32;
		height: 50px;
		color: white;
	}

	.icon-bottom-text {
		font-size: 12px;
		margin: auto;
		margin-left: 2px;
	}

	.lowest-sold-progress {
		background-color: #263238;
		color: black;
	}

	.number {
		font-size: 26px;
	}
</style>

<div ng-controller="dashboardCtrl">
	<div id="brand-logo">
		<h1>Welcome Back</h1>
	</div>
	<div class="clearfix"></div>
	<hr>
	<!-- Start of header Widget -->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-6">
			<div class="widget">
				<div class="widget-heading clearfix" id="widget-products-heading">
					<div class="pull-left">Products</div>
					<div class="pull-right"><i class="glyphicon glyphicon-leaf"></i> {{widget.products.total_products}}</div>
				</div>
				<div class="widget-body clearfix" id="widget-products-body">
					<div class="pull-left">
						<i class="glyphicon glyphicon-gift"></i>
						<p class="icon-bottom-text">Sold</p>
					</div>
					<div class="pull-right number">{{widget.products.total_sold}}</div>
				</div>
				<div class="widget-footer clearfix" id="widget-products-footer">
					<div class="pull-left">In Inventory</div>
					<div class="pull-right"> {{widget.products.in_inventory}}</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-6">
			<div class="widget">
				<div class="widget-heading clearfix" id="widget-customer-heading">
					<div class="pull-left">Customers</div>
					<div class="pull-right"><i class="glyphicon glyphicon-share-alt"></i> {{widget.customer.total_customer}}</div>
				</div>
				<div class="widget-body clearfix" id="widget-customer-body">
					<div class="pull-left">
						<i class="glyphicon glyphicon-bitcoin"></i>
						<p class="icon-bottom-text">Spend</p>
					</div>
					<div class="pull-right number">{{widget.customer.spend}}</div>
				</div>
				<div class="widget-footer clearfix" id="widget-customer-footer">
					<div class="pull-left">Paid</div>
					<div class="pull-right"> {{widget.customer.paid}}</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-6">
			<div class="widget">
				<div class="widget-heading clearfix" id="widget-suppliers-heading">
					<div class="pull-left">Suppliers</div>
					<div class="pull-right"><i class="glyphicon glyphicon-hand-right"></i> {{widget.suppliers.total_suppliers}}</div>
				</div>
				<div class="widget-body clearfix" id="widget-suppliers-body">
					<div class="pull-left">
						<i class="glyphicon glyphicon-random"></i>
						<p class="icon-bottom-text">Supplied</p>
					</div>
					<div class="pull-right number">{{widget.suppliers.supplied}}</div>
				</div>
				<div class="widget-footer clearfix" id="widget-suppliers-footer">
					<div class="pull-left">Sold</div>
					<div class="pull-right"> {{widget.products.total_sold}}</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-6">
			<div class="widget">
				<div class="widget-heading clearfix" id="widget-sales-heading">
					<div class="pull-left">Sales</div>
					<div class="pull-right"><i class="glyphicon glyphicon-send"></i> {{widget.sales.total_sales}}</div>
				</div>
				<div class="widget-body clearfix" id="widget-sales-body">
					<div class="pull-left">
						<i class="glyphicon glyphicon-shopping-cart"></i>
						<p class="icon-bottom-text">Paid</p>
					</div>
					<div class="pull-right number">{{widget.sales.paid}}</div>
				</div>
				<div class="widget-footer clearfix" id="widget-sales-footer">
					<div class="pull-left">Due</div>
					<div class="pull-right"> {{widget.sales.due}}</div>
				</div>
			</div>
		</div>

	</div>
	<!-- End of header Widget -->
	<hr class="shop-hr">
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-lg-8 col-md-9 col-sm-12">
			<div class="well well-sm shop-well">
				<h5><b>Today's Sales History</b>
					<h5>
			</div>
			<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

			</table>
		</div>
		<div class="col-lg-4 col-md-3 col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h5>Highlights</h5>
				</div>
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#lowest-inventory" ng-click="inventoryHightlights('lowest_inventory')">Stock</a></li>
						<li><a data-toggle="tab" href="#highest-sold" ng-click="inventoryHightlights('highest_sold')">Highest Sold</a></li>
						<li><a data-toggle="tab" href="#lowest-sold" ng-click="inventoryHightlights('lowest_sold')">Lowest Sold</a></li>
					</ul>

					<div class="tab-content">
						<div id="lowest-inventory" class="tab-pane fade in active">
							<div ng-repeat="lowest in lowest_inventory_product">
								<h5><i class="glyphicon glyphicon-leaf"></i> {{lowest.product_name}}</h5>
								<div class="progress">
									<div ng-if="lowest.quantity>20" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="{{lowest.quantity}}" aria-valuemin="0" aria-valuemax="100" style="width:{{lowest.quantity}}%">
										{{lowest.quantity}}
									</div>
									<div ng-if="lowest.quantity<=020" class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{lowest.quantity}}" aria-valuemin="0" aria-valuemax="100" style="width:{{lowest.quantity}}%">
										{{lowest.quantity}}
									</div>
								</div>
							</div>
						</div>
						<div id="highest-sold" class="tab-pane fade">
							<div ng-repeat="highest in lowest_inventory_product">
								<h5><i class="glyphicon glyphicon-leaf"></i> {{highest.product_name}}</h5>
								<div class="progress">
									<div ng-if="highest.quantity>1000" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{highest.quantity}}" aria-valuemin="0" aria-valuemax="2000" style="width:{{highest.quantity}}%">
										{{highest.quantity}}
									</div>
									<div ng-if="highest.quantity<=1000" class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="{{highest.quantity}}" aria-valuemin="0" aria-valuemax="2000" style="width:{{highest.quantity}}%">
										{{highest.quantity}}
									</div>
								</div>
							</div>
						</div>
						<div id="lowest-sold" class="tab-pane fade">
							<div ng-repeat="lowest in lowest_inventory_product">
								<h5><i class="glyphicon glyphicon-leaf"></i> {{lowest.product_name}}</h5>
								<div class="progress">
									<div ng-if="lowest.quantity==null" class="lowest-sold-progress progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="500" style="width:0%">
										0
									</div>
									<div ng-if="lowest.quantity>200" class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="{{lowest.quantity}}" aria-valuemin="0" aria-valuemax="500" style="width:{{lowest.quantity}}%">
										{{lowest.quantity}}
									</div>
									<div ng-if="lowest.quantity<=200" class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{lowest.quantity}}" aria-valuemin="0" aria-valuemax="500" style="width:{{lowest.quantity}}%">
										{{lowest.quantity}}
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



<script src="../../resources/datatables/jquery.dataTables.min.js"></script>
<script src="../../resources/datatables/angular-datatables.min.js"></script>
<script src="../../resources/datatables/angular-datatables.bootstrap.min.js"></script>
<script src="../../resources/datatables/dataTables.bootstrap.min.js"></script>
<script src="../../resources/datatables/dataTables.buttons.min.js"></script>
<script src="../../resources/datatables/buttons.bootstrap.min.js"></script>
<script src="../../resources/datatables/buttons.flash.min.js"></script>
<script src="../../resources/datatables/buttons.html5.min.js"></script>
<script src="../../resources/app/controller/dashboardController.js"></script>
<script>

</script>
<?php require_once '../layout/footer.php'; ?>