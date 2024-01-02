<?php
$page_title="Suppliers Info"; 
require_once '../layout/header.php';
?>
<div ng-controller="supplierCtrl">
	<div class="jSuppliers" ng-init="uid='<?php echo $_SESSION['id'];?>'">
		<div class="alert alert-danger notify"><p></p></div>
        <div class="alert alert-success notify"><p></p></div>
		<div class="pro-header">
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-success" ng-model="addModal" data-toggle="modal" data-target="#addModal">Add Suplier</button>
					<button type="button" class="btn btn-danger" ng-if="type=='All'" ng-click="showTrash()"><span class="glyphicon glyphicon-trash"></span> Show Trash</button>
					<button type="button" class="btn btn-info" ng-if="type=='Trash'" ng-click="showSuppliers()"><span class="glyphicon glyphicon-ok-circle"></span> Show All</button>
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
				  <th>Company Name</th>
				  <th>Owner's Name</th>
				  <th>Total Bill</th>
				  <th>Total Paid</th>
				  <th>Total Due</th>
				  <th>Added</th>
				  <th>Action</th>
			  </tr>
			</thead>
			<tbody>
			  <tr ng-repeat="supplier in suppliers">
				<td>{{$index + 1}}</td>
				<td>{{supplier.company_name}}</td>
				<td>{{supplier.owners_name}}</td>
				<td>{{supplier.total_bill}}</td>
				<td>{{supplier.total_paid}}</td>
				<td>{{supplier.total_due}}</td>
				<td>{{supplier.created}}</td>
				<td>
					<div ng-if="type=='All'">
						<button type="button" class="btn btn-xs btn-success" ng-click="viewSupplier(supplier.id)" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-ok-circle"></span></button>						
						<button type="button" class="btn btn-xs btn-info" ng-click="viewSupplier(supplier.id)" data-toggle="modal" data-target="#updateModal"><span class="glyphicon glyphicon-edit"></span></button>
						<button type="button" class="btn btn-xs btn-primary" ng-click="suppliedProducts(supplier.id, supplier.company_name)" data-toggle="modal" data-target="#viewProducts"><span class="glyphicon glyphicon-leaf"></span></button>
						<button type="button" class="btn btn-xs btn-warning" ng-click="billUpdate(supplier.id, supplier.company_name, supplier.total_due)" ng-disabled="supplier.total_due==0"><span class="glyphicon glyphicon-usd"></span></button>
						<button type="button" class="btn btn-xs btn-default" ng-click="supplierBillLog(supplier.id, supplier.company_name)"><span class="glyphicon glyphicon-list"></span></button>
						<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal" ng-click="findId(supplier.id)"><span class="glyphicon glyphicon-trash"></span></button>
					</div>
					<div ng-if="type=='Trash'">
						<button type="button" class="btn btn-xs btn-info" ng-click="viewSupplier(supplier.id)" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-ok-circle"></span></button>
						<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#restoreModal" ng-click="findId(supplier.id)"><span class="glyphicon glyphicon-ok-retweet"></span></button>
						<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#finalDeleteModal" ng-click="findId(supplier.id)"><span class="glyphicon glyphicon-trash"></span></button>
					</div>
				</td>
			  </tr>
			</tbody>
		</table>
	</div>
	
	<!-- Add Supplier Modal -->
		<div class="modal fade" id="addModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Enter supplier Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="addSupplier()" name="addSupplierForm">
					  <fieldset>
						<input type="hidden" ng-model="add_supplier.uid" ng-value="uid" name="uid" ng-init="add_supplier.uid=uid" required>
						<div class="form-group">
						  <label for="name" class="col-lg-2 control-label">Name/Company</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="add_supplier.company_name" class="form-control" id="name" name="name" placeholder="Supplier Name" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="owners_name" class="col-lg-2 control-label">Owner's Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="add_supplier.owners_name" class="form-control" id="owners_name" name="owners_name" placeholder="Owner's Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="address" class="col-lg-2 control-label">Address</label>
						  <div class="col-lg-10">
							<textarea ng-model="add_supplier.address" class="form-control" id="address" name="address" required></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="email" class="col-lg-2 control-label">Email</label>
						  <div class="col-lg-10">
							<input type="email" ng-model="add_supplier.email" class="form-control" id="email" placeholder="abc@abc.com" required>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="mobile" class="col-lg-2 control-label">Mobile</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="add_supplier.mobile" class="form-control" id="mobile" placeholder="01XXXXXXXXX">
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
		
		<!-- Update supplier Modal -->
		<div class="modal fade" id="updateModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update supplier Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="updateSupplier(sup.id)" name="updateForm" ng-repeat="sup in supplier" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <fieldset>
						<input type="hidden" ng-value="{{uid}}" ng-model="update.updated_by" name="uid" ng-init="update.updated_by=uid">
						<div class="form-group">
						  <label for="company_name" class="col-lg-2 control-label">Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="sup.company_name" ng-model="update.company_name" name="company_name" class="form-control" id="name" placeholder="supplier Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="model" class="col-lg-2 control-label">Owner's Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="sup.owners_name" ng-model="update.owners_name" name="owners_name" class="form-control" id="supplier_model" placeholder="supplier Model">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="address" class="col-lg-2 control-label">Address</label>
						  <div class="col-lg-10">
							<textarea ng-model="update.address" class="form-control" id="address" name="address" ng-init="update.address=sup.address"></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="email" class="col-lg-2 control-label">Email</label>
						  <div class="col-lg-10">
							<input type="email" ng-value="sup.email" ng-model="update.email" name="email" class="form-control" id="email" placeholder="abc@abc.com">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="mobile" class="col-lg-2 control-label">Mobile</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="sup.mobile" ng-model="update.mobile" name="eamil" class="form-control" id="eamil" placeholder="abc@abc.com">
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-disabled="updateForm.$pristine">Submit</button>
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
				<h4 class="modal-title">Details of the supplier</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" ng-repeat="sup in supplier">
						<tbody>
						  <tr>
							<th>Company/Name</th>
							<td>{{sup.company_name}}</td>
							<th>Owner's Name</th>
							<td>{{sup.owners_name}}</td>
						  </tr>
						  <tr>
							<th>Address</th>
							<td>{{sup.address}}</td>
							<th>Added</th>
							<td>{{sup.created}}</td>
						  </tr>
						  <tr>
							<th>Email</th>
							<td>{{sup.email}}</td>
							<th>Mobile</th>
							<td>{{sup.mobile}}</td>
						  </tr>
						  <tr>
							<th>Added By</th>
							<td>{{sup.added_by}}</td>
							<th>Updated By</th>
							<td>{{sup.updated_by}}</td>
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
		
		<!-- Supplied Products View Modal -->
		<div class="modal fade" id="viewProducts" role="dialog">
			<div class="modal-dialog modal-lg">
			  <div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Supply History <span class="label label-info"><i>{{supplier_name}}</i></span></h4>
				</div>
				<div class="modal-body myCenter">
					<table class="table table-bordered">
						<thead>
						  <tr>
							<th>Sr.</th>
							<th>Product Name</th>							
							<th>Buy Price</th>							
							<th>Quantity</th>
							<th>Added</th>
							<th>Added by</th>
						  </tr>
						</thead>
						<tbody>
						  <tr ng-repeat="product in supplied">
							<td>{{$index+1}}</td>
							<td>{{product.product_name}}</td>							
							<td><b>{{product.buy_price}}</b></td>							
							<td><b>{{product.quantity}}</b></td>
							<td>{{product.created}}</td>
							<td>{{product.first_name}} {{product.last_name}}</td>
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
							<button type="button" class="btn btn-block btn-success" ng-click="softDeleteSupplier()">Yes</button>
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
							<button type="button" class="btn btn-block btn-success" ng-click="restoreSupplier()">Yes</button>
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
		
		<!-- Bill Update Modal -->
	<div class="modal fade" id="billUpdateModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Bill Update of '{{billed_supplier_name}}'</h4>
			  </div>
			  <div class="modal-body">
			  <h4>Due : <b>{{total_due - bill_info.amount}}</b></h4>
				<form class="form-horizontal" ng-submit="submitBillUpdate()" name="dueForm">
				  <fieldset>
					<input type="hidden" ng-value="{{uid}}" ng-model="uid" name="uid" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					<div class="form-group">
					  <label for="ammount" class="col-lg-3 control-label">Enter Amount</label>
					  <div class="col-lg-9">
						<input type="number" ng-model="bill_info.amount" name="amount" value="0" class="form-control" id="amount" placeholder="Enter Amount" ng-change="balanceCalculation()">
						<p class="input-alert-text" ng-show="bill_info.amount>total_due"><i>Input amount is greater than Due!!!</i></p>
					  </div>
					</div>
					
					<div class="form-group">
					  <label for="remarks" class="col-lg-3 control-label">Remarks</label>
					  <div class="col-lg-9">
						<textarea type="text" rows="6" ng-model="bill_info.remarks" name="remarks"class="form-control" id="remarks" placeholder="Due Cleared"></textarea>
					  </div>
					</div>
					
					<div class="form-group">
					  <div class="col-lg-9 col-lg-offset-3">
						<button type="reset" class="btn btn-danger">Reset</button>
						<button type="submit" class="btn btn-primary" ng-disabled="!bill_info.amount>0 || !bill_info.remarks || bill_info.amount>total_due">Submit</button>
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
	<!-- End of bill update modal -->
	<!-- Supplier Bill Log -->
		<div class="modal fade" id="supplierBillLog" role="dialog">
			<div class="modal-dialog modal-lg big-modal">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title myCenter">Bill Log's of '{{log_supplier_name}}'</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered" datatable="ng" dt-options="vm.dtOptions">
						<thead>
						  <tr>
							  <th>Sr</th>
							  <th>Amount</th>
							  <th>Previous Bill</th>
							  <th>Updated Bill</th>
							  <th>Previous Paid</th>
							  <th>Updated Paid</th>
							  <th>Previous Due</th>
							  <th>Updated Due</th>
							  <th>Remarks</th>
							  <th>Added</th>
							  <th>Action</th>
						  </tr>
						</thead>
						<tbody>
						  <tr ng-repeat="log in supplier_logs">
							<td>{{$index + 1}}</td>
							<td>{{log.amount}}</td>
							<td>{{log.old_total_bill}}</td>
							<td>{{log.updated_total_bill}}</td>
							<td>{{log.old_total_paid}}</td>
							<td>{{log.updated_total_paid}}</td>
							<td>{{log.old_total_due}}</td>
							<td>{{log.updated_total_due}}</td>
							<td>{{log.remarks}}</td>
							<td>{{log.created}}</td>
							<td>
								<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal" ng-click="findId(supplier.id)" disabled><span class="glyphicon glyphicon-trash"></span></button>							
							</td>
						  </tr>
						</tbody>
					</table>
				</div>
			  </div>
			</div>
		</div>
	</div>
	<script src="../../resources/datatables/jquery.dataTables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.bootstrap.min.js"></script>
	<script src="../../resources/app/controller/supplierController.js"></script>

<?php require_once '../layout/footer.php';?>