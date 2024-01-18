<?php
$page_title = "Sales";
require_once '../layout/header.php';
?>

<div ng-controller="salesCtrl">
	<div class="jCustomers" ng-init="uid='<?php echo $_SESSION['id']; ?>'">
		<div class="alert alert-danger notify">
			<p></p>
		</div>
		<div class="alert alert-success notify">
			<p></p>
		</div>
		<div class="sales">
			<div class="row">
				<div class="col-md-8">
					<div class="well">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
							<select class="form-control" id="select-product" ng-options="product.product_name for product in productList" style="width:100%;" ng-model="product" ng-change="setProduct(product)">
							</select>
							<span class="input-group-btn">
								<button class="btn btn-success" type="button">Add</button>
							</span>
						</div>
					</div>

					<div class="panel panel-info">
						<div class="panel-heading">
							Order Table
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Serial</th>
											<th width="200px">Product Name</th>
											<th width="200px">Quantity</th>
											<th width="200px">Price</th>
											<th width="100px">Sub Total</th>
											<th width="60px">Action</th>
										</tr>
									</thead>
									<tbody ng-repeat="selected in selected_product">
										<tr>
											<td>{{$index + 1}}{{stockExceed($index)}}</td>
											<td><b>{{selected.product_name}}</b></h4>
											</td>
											<td><input type="number" class="order-input-quantity" ng-model="selected.quantity"></td>
											<td><input type="number" class="order-input-price" ng-model="selected.sale_price" ng-init="sale_price=selected.base_price"></td>
											<td>{{(selected.quantity * selected.sale_price)}}</td>
											<td><button type="button" class="btn btn-xs btn-danger" ng-click="removeProduct($index)"><span class="glyphicon glyphicon-trash"></span></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 well">
					<div class="panel panel-info">
						<div class="panel-heading">Customer Info</div>
						<div class="panel-body">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
								<input type="text" ng-model="customer_name" class="form-control" ng-click="showCustomers()" ng-focus="focus_customer=true">
								<span class="input-group-btn">
									<button class="btn btn-success" type="button" ng-model="addCustomerModal" data-toggle="modal" data-target="#addCustomerModal">Add New</button>
								</span>
							</div>
							<div id="search-results" ng-show="focus_customer">
								<li class="list-group-item" ng-repeat="customer in customerList | filter:name" ng-bind="customer.name" ng-click="setCustomer(customer)"></li>
							</div>
							<div class="customer-info" ng-show="selectCustomer==true">
								<div class="list-group">
									<li class="list-group-item"><span class="glyphicon glyphicon-user"></span>: {{customer_name}}</li>
									<li class="list-group-item"><span class="glyphicon glyphicon-book"></span>: {{customer_address}}</li>
									<li class="list-group-item"><span class="glyphicon glyphicon-phone"></span>: {{customer_mobile}}</li>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-warning">
								<div class="panel-heading">Total Amount</div>
								<div class="panel-body">{{getTotal() | currency}}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-danger">
								<div class="panel-heading">Amount Due</div>
								<div class="panel-body">{{getTotal() - cash | currency}}</div>
							</div>
						</div>
					</div>
					<div ng-if="customer_dues>0 || customer_dues!=null" class="alert alert-danger">
						<strong>Previous Dues:</strong> ${{customer_dues}}
					</div>

					<style>
						.my-group .form-control {
							width: 50%;
						}
					</style>
					<div class="panel panel-success">
						<div class="panel-heading">Payment Information</div>
						<div class="panel-body">
							<div class="input-group my-group">
								<span class="input-group-addon">$</span>
								<input type="number" class="form-control" ng-model="cash" name="cash" placeholder="Enter Amount" ng-init="cash=0" required>
								<select class="form-control" ng-model="payment_method" required>
									<option value="Cash" ng-selected="selected">Cash</option>
									<option value="Check">Check</option>
									<option value="bkash">Bkash</option>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-primary" type="submit" ng-disabled="!payment_method || cash<0 || customer_id=='' || selected_product.length==0 || !exceeded_stock;" ng-click="submitOrder(getTotal(), cash, payment_method)">Submit</button>
								</span>

							</div>

						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

	<!-- Add Customer Modal -->
	<div class="modal fade" id="addCustomerModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Enter Customer Details</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" ng-submit="addCustomer()" name="addCustomerForm">
						<fieldset>
							<input type="hidden" ng-model="add_customer.uid" ng-value="uid" name="uid" ng-init="add_customer.uid=uid" required>
							<div class="form-group">
								<label for="name" class="col-lg-2 control-label">Name/Company</label>
								<div class="col-lg-10">
									<input type="text" ng-model="add_customer.name" class="form-control" id="name" name="name" placeholder="Customer Name" required>
								</div>
							</div>

							<div class="form-group">
								<label for="owners_name" class="col-lg-2 control-label">Owner's Name</label>
								<div class="col-lg-10">
									<input type="text" ng-model="add_customer.owners_name" class="form-control" id="owners_name" name="owners_name" placeholder="Owner's Name">
								</div>
							</div>

							<div class="form-group">
								<label for="address" class="col-lg-2 control-label">Address</label>
								<div class="col-lg-10">
									<textarea ng-model="add_customer.address" class="form-control" id="address" name="address" required></textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input type="email" ng-model="add_customer.email" class="form-control" id="email" placeholder="abc@abc.com" required>
								</div>
							</div>

							<div class="form-group">
								<label for="mobile" class="col-lg-2 control-label">Mobile</label>
								<div class="col-lg-10">
									<input type="text" ng-model="add_customer.mobile" class="form-control" id="mobile" placeholder="01XXXXXXXXX">
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2">
									<button type="reset" class="btn btn-danger">Reset</button>
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>



	<!-- View Inventory History Modal -->
	<div id="historyModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Details of the Product inventory</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered" datatable="ng" dt-options="vm.dtOptions">
							<thead>
								<tr>
									<th>Sr</th>
									<th>Product Name</th>
									<th>Quantity</th>
									<th>Sale Price</th>
									<th>Sales Date</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="product in soldProductsList">
									<td>{{$index + 1}}</td>
									<td>{{product.product_name}}</td>
									<td>{{product.quantity}}</td>
									<td>{{product.sale_price}}</td>
									<td>{{product.created}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Log Modal -->
	<div class="modal fade" id="logModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Inventory Log of <span class="label label-info"><i>{{product_name}}</i></span></h4>
				</div>
				<div class="modal-body myCenter">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Quantity</th>
								<th>Type</th>
								<th>Remarks</th>
								<th>Added</th>
								<th>Added by</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="log in logs">
								<td>{{$index+1}}</td>
								<td><b>{{log.quantity}}</b></td>
								<td ng-if="log.inventory_type==1"><span class="label label-success">Add</span></td>
								<td ng-if="log.inventory_type==0"><span class="label label-danger">Substract</span></td>
								<td>{{log.remarks}}</td>
								<td>{{log.created}}</td>
								<td>{{log.added_by}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Low Stock Modal -->
	<div class="modal fade" id="lowStockModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="myCenter danger">Stock is low. Only <i><b>"{{inStock}}"</b></i> in stock</h4>
				</div>
			</div>
		</div>
	</div>

	<!-- Delete Modal Modal -->
	<div class="modal fade" id="errModal" role="dialog">
		<div class="modal-dialog danger">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Warning !!!</h4>
				</div>
				<div class="modal-body myCenter">
					<h3>Something is Worng!!! Please try again. Better option is Log Out First & Log in Again !!!</h3>
				</div>
			</div>
		</div>
	</div>

	<!-- Restore Modal -->
	<div class="modal fade" id="restoreModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Are you Sure to Restore?</h4>
				</div>
				<div class="modal-body myCenter">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-success" ng-click="restoreCustomer()">Yes</button>
						</div>
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-danger" data-dismiss="modal">No</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Final Delete Modal -->
	<div class="modal fade" id="finalDeleteModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title myCenter">Are you Sure to Delete Permanently ?</h4>
				</div>
				<div class="modal-body myCenter">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-success" ng-click="finalDelete()">Yes</button>
						</div>
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-danger" data-dismiss="modal">No</button>
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
<script src="../../resources/app/controller/salesController.js"></script>

<?php require_once '../layout/footer.php'; ?>