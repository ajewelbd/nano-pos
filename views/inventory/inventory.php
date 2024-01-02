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
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#historyModal" ng-click="showLogAll()"><span class="glyphicon glyphicon-list"></span> Show History</button>
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
				<td ng-class="(inventory.quantity<50)? 'np-bg-danger np-danger-text-color': 'bg-default'">{{$index + 1}}</td>
				<td ng-class="(inventory.quantity<50)? 'np-bg-danger np-danger-text-color': 'bg-default'">{{inventory.product_name}}</td>
				<td ng-class="(inventory.quantity<50)? 'np-bg-danger np-danger-text-color': 'bg-default'">{{inventory.quantity}}</td>
				<td ng-class="(inventory.quantity<50)? 'np-bg-danger np-danger-text-color': 'bg-default'">{{inventory.updated}}</td>
				<td>
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-success" ng-click="findInfo(inventory.id, inventory.pro_id, inventory.product_name, inventory.quantity)"><span class="glyphicon glyphicon-ok-circle"></span> Change Quantity</button>
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
				  <div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon glyphicon-search"></span>
					    <input type="text" ng-model="pro_name" class="form-control" id="search" name="search" ng-focus="focus=true" placeholder="Type Product Name" required>
					    <span class="input-group-btn">
							<button class="btn btn-success" type="button" ng-click="addInventory(uid, pro_id)">Add</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					    </span>
					</div>
					<div id="search-results" ng-show="focus">
						<li class="list-group-item" ng-repeat="product in product_list | filter:pro_name" ng-bind="product.product_name" ng-click="setProduct(product.id, product.product_name)"></li>
					</div>
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
						  <label for="quantity" class="col-md-3 control-label">Inevntory Type</label>
						  <div class="col-lg-9">
							<input type="radio" ng-model="change_quantity.type" name="type" value="1" checked required><span class="label label-success">Add</span>
							<input type="radio" ng-model="change_quantity.type" name="type" value="0"><span class="label label-danger">Remove</span>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="quantity" class="col-md-3 control-label">Quantity</label>
						  <div class="col-md-9">
							<input type="number" ng-value="value" ng-model="change_quantity.quantity" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="buy_price" class="col-md-3 control-label">Price</label>
						  <div class="col-md-9">
							<input type="number" ng-value="value" ng-model="change_quantity.buy_price" name="buy_price" class="form-control" id="buy_price" placeholder="Enter Price" required>
						  </div>
						</div>
						
						<div class="form-group">
							<label for="quantity" class="col-md-3 control-label">Select Suppliers</label>
							<div class="col-md-9">
							  <select class="form-control" id="select-suppliers" ng-model="change_quantity.supplier_id" ng-options="supplier.id as supplier.company_name for supplier in suppliers" style="width:100%;">
									
							  </select>
							</div>
						</div>
						
						
						<div class="form-group">
						  <label for="remarks" class="col-md-3 control-label">Remarks</label>
						  <div class="col-md-9">
							<textarea ng-model="change_quantity.remarks" cols="6" class="form-control" id="remarks" name="remarks" required></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-9 col-md-offset-3">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-disabled="!change_quantity.updated_by || !change_quantity.type || !change_quantity.quantity || !change_quantity.buy_price<0 || !change_quantity.supplier_id || !change_quantity.remarks">Submit</button>
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
		  <div class="modal-dialog modal-lg" style="width:90vw">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Details of the Customer</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered" datatable="ng" dt-options="vm.dtOptions">
						<thead>
						  <tr>
							  <th>Sr</th>
							  <th>Product Name</th>
							  <th>Old Quantity</th>
							  <th>Quantity Changed</th>
							  <th>Updated Quantity</th>
							  <th>Type</th>
							  <th>Remarks</th>
							  <th>Updated BY</th>
							  <th>Inventory Date</th>
						  </tr>
						</thead>
						<tbody>
						  <tr ng-repeat="logData in logsData">
							<td>{{$index + 1}}</td>
							<td>{{logData.product_name}}</td>
							<td>{{logData.old_quantity}}</td>							
							<td>{{logData.quantity}}</td>							
							<td>{{logData.updated_quantity}}</td>							
							<td ng-if="logData.inventory_type==0"><span class="label label-danger">Substract</span></td>
							<td ng-if="logData.inventory_type==1"><span class="label label-success">Added</span></td>							
							<td ng-if="logData.inventory_type==2"><span class="label label-primary">Sales</span></td>
							<td>{{logData.remarks}}</td>
							<td>{{logData.first_name}} {{logData.first_name}}</td>
							<td>{{logData.created}}</td>
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
			<div class="modal-dialog modal-lg">
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
							<th>Supplier</th>							
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
							<td><b>{{log.company_name}}</b></td>							
							<td><b>{{log.quantity}}</b></td>							
							<td ng-if="log.inventory_type==0"><span class="label label-danger">Substract</span></td>
							<td ng-if="log.inventory_type==1"><span class="label label-success">Add</span></td>
							<td ng-if="log.inventory_type==2"><span class="label label-primary">Sales</span></td>
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