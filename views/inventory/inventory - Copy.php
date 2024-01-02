<?php
$page_title="Inventory Info"; 
require_once '../layout/header.php';
?>
<div ng-controller="inventoryCtrl">
	<div class="jCustomers" ng-init="uid='<?php echo $_SESSION['id'];?>'">
		<div class="alert alert-danger notify"><p></p></div>
        <div class="alert alert-success notify"><p></p></div>
		<div class="pro-header">
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-success" ng-model="addModal" data-toggle="modal" data-target="#addModal">Add Inventory</button>
					<button type="button" class="btn btn-info" ng-if="type=='log'" ng-click="showTrash()"><span class="glyphicon glyphicon-trash"></span> Show History</button>
					<button type="button" class="btn btn-info" ng-if="type=='inventory'" ng-click="showCustomers()"><span class="glyphicon glyphicon-ok-circle"></span> Show Inevntory</button>
				</div>
				
				<div class="col-md-6">
					<h4 class="jDisplay">Export As: </h4>
					<div class="btn-group">
					  <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-list"></span> PDF</button>
					  <button type="button" class="btn btn-info"><span class="glyphicon glyphicon-print"></span> Excel</button>
					  <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-align-center"></span> XML</button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<table class="table table-striped table-bordered" datatable="ng" dt-options="vm.dtOptions">
			<thead>
			  <tr>
				  <th>Sr</th>
				  <th>Product Name</th>
				  <th>Quantity</th>
				  <th>Last Updated</th>
				  <th width="250px">Action</th>
			  </tr>
			</thead>
			<tbody>
			  <tr ng-repeat="inventory in inventories">
				<td>{{$index + 1}}</td>
				<td>{{inventory.product_name}}</td>
				<td>{{inventory.quantity}}</td>
				<td>{{inventory.updated}}</td>
				<td>
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-success" ng-click="findInfo(inventory.id, inventory.pro_id, inventory.product_name, inventory.quantity)" data-toggle="modal" data-target="#changeQuantityModal"><span class="glyphicon glyphicon-ok-circle"></span> Change Quantity</button>
						<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#logModal" ng-click="showLog(inventory.id, inventory.product_name)"><span class="glyphicon glyphicon-trash"></span> Log</button>
					</div>
				</td>
			  </tr>
			</tbody>
		</table>
	</div>
	
	<!-- Add Inventory Modal -->
		<div class="modal fade" id="addModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="addInventory()" name="addInventoryForm">
					  <fieldset>
						<input type="hidden" ng-model="add_inventory.uid" ng-value="uid" name="uid" ng-init="add_inventory.uid=uid" required>
						
						<div class="input-group">
						<span class="input-group-addon glyphicon-search"></span>
						  <input type="text" ng-model="product_query" ng-keyup="complete(product_query)" class="form-control" ng-focus="focus=true" required>
						<span class="input-group-btn">
							<button class="btn btn-success" type="button">Add</button>
						  </span>
						</div>
						<div id="search-results" ng-model="hidelist" ng-hide="hidelist">	
							<li class="list-group-item search-result" ng-repeat="product in product_filter_data" ng-click="setProduct(product.product_name)">{{product.product_name}}</li>
						</ul>
					  </fieldset>
					</form>
				  </div>
				</div>

			</div>
		</div>
		
		<!-- Change Quantity Modal -->
		<div class="modal fade" id="changeQuantityModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Quantity Change of <span class="label label-success"><i>{{product_name}}</i></span></h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="changeQuantity(inv_id)" name="changeQuantityForm" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <h2>Now at Stock: <span class="label label-info">{{quantity}}</span></h2>
					  <fieldset>
						<input type="hidden" ng-value="{{uid}}" ng-model="change_quantity.updated_by" name="uid" ng-init="change_quantity.updated_by=uid">
						<div class="form-group">
						  <label for="quantity" class="col-lg-2 control-label">Inevntory Type</label>
						  <div class="col-lg-10">
							<input type="radio" ng-model="change_quantity.type" name="type" value="1" checked required><span class="label label-success">Add</span>
							<input type="radio" ng-model="change_quantity.type" name="type" value="0"><span class="label label-danger">Remove</span>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="quantity" class="col-lg-2 control-label">Quantity</label>
						  <div class="col-lg-10">
							<input type="number" ng-value="value" ng-model="change_quantity.quantity" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="remarks" class="col-lg-2 control-label">Remarks</label>
						  <div class="col-lg-10">
							<textarea ng-model="change_quantity.remarks" cols="6" class="form-control" id="remarks" name="remarks" required></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-disabled="changeQuantityForm.$pristine">Submit</button>
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
		
		<!-- View Modal -->
		<div id="viewModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Details of the Customer</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" ng-repeat="c in customer">
						<tbody>
						  <tr>
							<th>Name</th>
							<td>{{c.name}}</td>
							<th>Owner's Name</th>
							<td>{{c.owners_name}}</td>
						  </tr>
						  <tr>
							<th>Address</th>
							<td>{{c.address}}</td>
							<th>Added</th>
							<td>{{c.created}}</td>
						  </tr>
						  <tr>
							<th>Email</th>
							<td>{{c.email}}</td>
							<th>Mobile</th>
							<td>{{c.mobile}}</td>
						  </tr>
						  <tr>
							<th>Added By</th>
							<td>{{c.added_by}}</td>
							<th>Updated By</th>
							<td>{{c.updated_by}}</td>
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
		
		<!-- Delete Modal -->
		<div class="modal fade" id="deleteModal" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Are you Sure?</h4>
				</div>
				<div class="modal-body myCenter">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-success" ng-click="softDeleteCustomer()">Yes</button>
						</div>
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-danger" data-dismiss="modal">No</button>
						</div>
					</div>
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
	<script src="../../resources/app/controller/inventoryController.js"></script>

<?php require_once '../layout/footer.php';?>