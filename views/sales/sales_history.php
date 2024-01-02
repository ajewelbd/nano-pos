<?php
$page_title="Sales History"; 
require_once '../layout/header.php';
?>
<div ng-controller="salesCtrl">
	<div class="jProducts" ng-init="uid='<?php echo $_SESSION['id'];?>'">
		<div class="alert alert-danger notify"><p></p></div>
        <div class="alert alert-success notify"><p></p></div>
		<div class="pro-header">
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-danger" ng-if="type=='All'" ng-click="showTrash()"><span class="glyphicon glyphicon-trash"></span> Show Trash</button>
					<button type="button" class="btn btn-info" ng-if="type=='Trash'" ng-click="showSalesHistory()"><span class="glyphicon glyphicon-ok-circle"></span> Show All</button>
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
				  <th>Invoice</th>
				  <th>Customer Name</th>
				  <th>Total</th>
				  <th>Paid</th>
				  <th>Due</th>
				  <th>Payment Method</th>
				  <th>Date</th>
				  <th>Action</th>
			  </tr>
			</thead>
			<tbody>
			  <tr ng-repeat="sales in salesHistory">
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{$index + 1}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.invoice}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.customer_name}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.total}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.paid}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.due}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.payment_method}}</td>
				<td ng-class="(sales.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{sales.created}}</td>
				<td>
					<div ng-show="type=='All'">
						<button type="button" class="btn btn-md btn-success" ng-click="showSoldProducts(sales.id, sales.total)" data-toggle="modal" data-target="#soldProductListModal"><span class="glyphicon glyphicon-leaf"></span></button>
						<button type="button" class="btn btn-md btn-info" ng-disabled="sales.due==0" ng-click="showDueUpdateForm(sales.id, sales.invoice, sales.due)"><span class="glyphicon glyphicon-edit"></span></button>
						<button type="button" class="btn btn-md btn-danger" data-toggle="modal" data-target="#deleteModal" ng-click="findId(sales.id)"><span class="glyphicon glyphicon-trash"></span></button>
					</div>
					<div class="btn-group" ng-show="type=='Trash'">
						<button type="button" class="btn btn-xs btn-info" ng-click="showSoldProducts(sales.id, sales.total)" data-toggle="modal" data-target="#soldProductListModal"><span class="glyphicon glyphicon-ok-circle"></span> Products</button>
						<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#restoreModal" ng-click="findId(sales.id)"><span class="glyphicon glyphicon-ok-circle"></span> Restore</button>
						<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#finalDeleteModal" ng-click="findId(sales.id)"><span class="glyphicon glyphicon-trash"></span> Delete</button>
					</div>
				</td>
			  </tr>
			</tbody>
		</table>
	</div>
	
		
		<!-- Update Due Modal -->
		<div class="modal fade" id="updateDueModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Due of #{{invoice_show}}</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="updateDue()" name="dueForm" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <fieldset>
						<input type="hidden" ng-value="{{uid}}" ng-model="dueUpdate.updated_by" name="uid" ng-init="dueUpdate.updated_by=uid">
						<div class="form-group">
						  <label for="ammount" class="col-lg-2 control-label">Enter Ammount</label>
						  <div class="col-lg-10">
							<input type="number" ng-model="dueUpdate.ammount" name="ammount" ng-init="dueUpdate.ammount=0" class="form-control" id="ammount" placeholder="Enter Ammount">
						  </div>
						</div>
						<h3>Due : <b>{{calculate_due()}}</b></h3>
						
						<div class="form-group">
						  <label for="remarks" class="col-lg-2 control-label">Remarks</label>
						  <div class="col-lg-10">
							<textarea type="text" rows="6" ng-model="dueUpdate.remarks" name="remarks"class="form-control" id="remarks" placeholder="Due Cleared"></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-disabled="!dueUpdate.ammount || !dueUpdate.remarks">Submit</button>
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
		<div id="soldProductListModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Details of the Product</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
						  <tr>
							  <th>Sr</th>
							  <th>Product Name</th>
							  <th>Quantity</th>
							  <th>Base Price</th>
							  <th>Sale Price</th>
							  <th>Sales Date</th>
						  </tr>
						</thead>
						<tbody>
						  <tr ng-repeat="product in soldProductsList">
							<td>{{$index + 1}}</td>
							<td>{{product.product_name}}</td>
							<td>{{product.quantity}}</td>
							<td>{{product.base_price}}</td>
							<td>{{product.sale_price}}</td>
							<td>{{product.created}}</td>
						  </tr>
						</tbody>
						<tfoot class="info">
							<th colspan="3"><td>Total</td><td>{{ordersTotal}}</td><td></td></th>
						</tfoot>
					</table>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
		
		<!-- Soft Delete Modal -->
		<div class="modal fade" id="deleteModal" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Are you Sure?</h4>
				</div>
				<div class="modal-body myCenter">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<button type="button" class="btn btn-block btn-success" ng-click="softDeleteSales()">Yes</button>
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
							<button type="button" class="btn btn-block btn-success" ng-click="restoreSales()">Yes</button>
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

<?php require_once '../layout/footer.php';?>