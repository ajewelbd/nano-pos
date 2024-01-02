<?php
$page_title="Customers Info"; 
require_once '../layout/header.php';
?>
<div ng-controller="customerCtrl">

	<div class="jCustomers" ng-init="uid='<?php echo $_SESSION['id'];?>'">
		<div class="alert alert-danger notify"><p></p></div>
        <div class="alert alert-success notify"><p></p></div>
		<div class="pro-header">
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-success" ng-model="addModal" data-toggle="modal" data-target="#addModal">Add Customer</button>
					<button type="button" class="btn btn-danger" ng-if="type=='All'" ng-click="showTrash()"><span class="glyphicon glyphicon-trash"></span> Show Trash</button>
					<button type="button" class="btn btn-info" ng-if="type=='Trash'" ng-click="showCustomers()"><span class="glyphicon glyphicon-ok-circle"></span> Show All</button>
				</div>
				
				<div class="col-md-6">
					<h4 class="jDisplay">Export As: </h4>
					<div class="btn-group">
					  <button type="button" class="btn btn-success" ng-click="pdfExport()"><span class="glyphicon glyphicon-list"></span> PDF</button>
					  <button type="button" class="btn btn-info" ng-click="excelExport()"><span class="glyphicon glyphicon-print"></span> Excel</button>
					  <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-align-center"></span> XML</button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<table class="table table-striped table-bordered" id="tt" datatable="ng" dt-options="vm.dtOptions">
			<thead>
			  <tr>
				  <th>Sr</th>
				  <th>Name/Company</th>
				  <th>Owner's Name</th>
				  <th>Address</th>
				  <th>Mobile</th>
				  <th>Expense</th>
				  <th>Paid</th>
				  <th>Due/Advance</th>
				  <th>Action</th>
			  </tr>
			</thead>
			<tbody>
			  <tr ng-repeat="customer in customers">
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{$index + 1}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.name}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.owners_name}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.address}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.mobile}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.total_expense}}</td>
				<td ng-class="(customer.total_due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{customer.total_paid}}</td>
				<td ng-if="customer.total_due>0" class="np-bg-danger np-danger-text-color">D={{customer.total_due}}</td>
				<td ng-if="customer.total_due==0" class="bg-default">D={{customer.total_due}}</td>
				<td ng-if="customer.total_due<0" class="bg-success">A={{customer.total_due *(-1)}}</td>
				<td>
					<div ng-if="type=='All'">
						<button type="button" class="btn btn-sm btn-primary" ng-click="viewCustomer(customer.id)" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-eye-open"></span></button>
						<button type="button" class="btn btn-sm btn-info" ng-click="viewCustomer(customer.id)" data-toggle="modal" data-target="#updateModal"><span class="glyphicon glyphicon-edit"></span></button>
						<button type="button" class="btn btn-sm btn-success" ng-click="showTransaction(customer.id, customer.name)"><span class="glyphicon glyphicon-transfer"></span></button>
						<button type="button" class="btn btn-sm btn-warning" ng-click="paymentUpdate(customer.id, customer.name, customer.total_paid, customer.total_due)"><span class="glyphicon glyphicon-usd"></span></button>
						<button type="button" class="btn btn-sm btn-default" ng-click="showSinglePaymentHsitory(customer.id, customer.name, customer.total_paid, customer.total_due)"><span class="glyphicon glyphicon-indent-left"></span></button>
						<button type="button" class="btn btn-sm btn-danger" ng-disabled="customer.total_expense>0" data-toggle="modal" data-target="#deleteModal" ng-click="findId(customer.id)"><span class="glyphicon glyphicon-trash"></span></button>
					</div>
					<div ng-if="type=='Trash'">
						<button type="button" class="btn btn-sm btn-info" ng-click="viewCustomer(customer.id)" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-ok-circle"></span></button>
						<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#restoreModal" ng-click="findId(customer.id)"><span class="glyphicon glyphicon-retweet"></span></button>
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#finalDeleteModal" ng-click="findId(customer.id)"><span class="glyphicon glyphicon-trash"></span></button>
					</div>
				</td>
			  </tr>
			</tbody>
		</table>
	</div>
	
	<!-- Add Customer Modal -->
		<div class="modal fade" id="addModal" role="dialog">
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
		
		<!-- Update Customer Modal -->
		<div class="modal fade" id="updateModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Customer Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="updateCustomer(customer.id)" name="updateForm" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <fieldset>
						<input type="hidden" ng-value="{{uid}}" ng-model="update.updated_by" name="uid" ng-init="update.updated_by=uid">
						<div class="form-group">
						  <label for="name" class="col-lg-2 control-label">Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="customer.name" ng-model="update.name" name="name" class="form-control" id="name" placeholder="Customer Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="model" class="col-lg-2 control-label">Owner's Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="customer.owners_name" ng-model="update.owners_name" name="owners_name" class="form-control" id="Customer_model" placeholder="Customer Model">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="address" class="col-lg-2 control-label">Address</label>
						  <div class="col-lg-10">
							<textarea ng-model="update.address" class="form-control" id="address" name="address" ng-value="update.address=customer.address"></textarea>
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="email" class="col-lg-2 control-label">Email</label>
						  <div class="col-lg-10">
							<input type="email" ng-value="customer.email" ng-model="update.email" name="email" class="form-control" id="email" placeholder="abc@abc.com">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="mobile" class="col-lg-2 control-label">Mobile</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="customer.mobile" ng-model="update.mobile" name="eamil" class="form-control" id="eamil" placeholder="abc@abc.com">
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
				<h4 class="modal-title">Details of '{{customer.name}}'</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<tbody>
						  <tr>
							<th>Name</th>
							<td>{{customer.name}}</td>
							<th>Owner's Name</th>
							<td>{{customer.owners_name}}</td>
						  </tr>
						  <tr>
							<th>Address</th>
							<td>{{customer.address}}</td>
							<th>Added</th>
							<td>{{customer.created}}</td>
						  </tr>
						  <tr>
							<th>Email</th>
							<td>{{customer.email}}</td>
							<th>Mobile</th>
							<td>{{customer.mobile}}</td>
						  </tr>
						  <tr>
							<th>Added By</th>
							<td>{{customer.added_first_name}} {{customer.added_last_name}}</td>
							<th>Updated By</th>
							<td>{{customer.updated_first_name}} {{customer.updated_last_name}}</td>
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
		<!-- Transaction View Modal -->
	<div class="modal fade" id="transactionModal" role="dialog">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">All Transactiondfgset of <span class="label label-info"><i>{{transaction_customer_name}}</i></span></h4>
			</div>
			<div class="modal-body myCenter">
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th>Sr.</th>
						<th>Invoice</th>
						<th>Total</th>
						<th>Paid</th>
						<th>Due/Advance</th>
						<th>Payment Method</th>
						<th>Sold by</th>
						<th>Date</th>
					  </tr>
					</thead>
					<tbody>
					  <tr ng-repeat="transaction in transactions">
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{$index+1}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">#{{transaction.invoice}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{transaction.total}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{transaction.paid}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">
							<p ng-if="transaction.due>=0">D={{transaction.due}}</p>
							<p ng-if="transaction.due<0">A={{transaction.due * (-1)}}</p>
						</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{transaction.payment_method}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{transaction.first_name}} {{transaction.last_name}}</td>
						<td ng-class="(transaction.due>0) ? 'np-bg-danger np-danger-text-color': 'bg-default'">{{transaction.created}}</td>
					  </tr>
					</tbody>
					<tfoot>
						<th colspan="1"><td>Grand Total</td><td>{{transaction_paid}}</td><td>{{transaction_paid}}</td><td>{{transaction_due}}</td><td></td><td></td><td></td></th>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	</div>
	<!-- Payment Update Modal -->
	<div class="modal fade" id="paymentUpdateModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Payment Update of '{{customer_name}}'</h4>
			  </div>
			  <div class="modal-body">
			  <h4 ng-show="total_due>=0">Total Paid : <b>{{total_paid + payment_update.amount}}</b></h4>
			  <h4 ng-show="total_due>=0">Due : <b>{{total_due - payment_update.amount}}</b></h4>
			  <h4 ng-show="total_due<0">Advance : <b>{{calculated_advance}}</b></h4>
				<form class="form-horizontal" ng-submit="submitPaymentUpdate()" name="dueForm">
				  <fieldset>
				  
					<div class="form-group">
					  <label for="ammount" class="col-lg-3 control-label">Entry Type</label>
					  <div class="col-lg-9">
						<label class="radio-inline"><input type="radio" ng-model="payment_update.entry_type" ng-value="1" ng-change="balanceCalculation()">Deposit</label>
						<label class="radio-inline"><input type="radio" ng-model="payment_update.entry_type" ng-value="0" ng-disabled="total_due>=0" ng-change="balanceCalculation()">Withdraw</label>
					  </div>
					</div>
					<div class="form-group">
					  <label for="ammount" class="col-lg-3 control-label">Enter Ammount</label>
					  <div class="col-lg-9">
						<input type="number" ng-model="payment_update.amount" name="amount" value="0" class="form-control" id="amount" placeholder="Enter Amount" ng-change="balanceCalculation()">
					  </div>
					</div>
					
					<div class="form-group">
					  <label for="remarks" class="col-lg-3 control-label">Remarks</label>
					  <div class="col-lg-9">
						<textarea type="text" rows="6" ng-model="payment_update.remarks" name="remarks"class="form-control" id="remarks" placeholder="Due Cleared"></textarea>
					  </div>
					</div>
					
					<div class="form-group">
					  <div class="col-lg-9 col-lg-offset-3">
						<button type="reset" class="btn btn-danger">Reset</button>
						<button type="submit" class="btn btn-primary" ng-disabled="!payment_update.amount>0 || !payment_update.remarks || calculated_advance<0">Submit</button>
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
	<!-- End of payment update modal -->
	
	<!-- Single Payment History Modal -->
		<div class="modal fade" id="singlePaymentHistoryModal" role="dialog">
			<div class="modal-dialog big-modal">
			  <div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title myCenter">Paymnets history of {{history_customer_name}}</h4>
				</div>
				<div class="modal-body myCenter">
					<table class="table table-striped table-bordered" datatable="ng" dt-options="vm.dtOptions">
						<thead>
						  <tr>
							  <th>Sr</th>
							  <th>Amount</th>
							  <th>Previous Expense</th>
							  <th>Updated Expense</th>
							  <th>Previous Paid</th>
							  <th>Updated Paid</th>
							  <th>Previous Due</th>
							  <th>Updated Due</th>
							  <th>Remarks</th>
							  <th>Added</th>
							  <th>Added By</th>
							  <th>Action</th>
						  </tr>
						</thead>
						<tbody>
						  <tr ng-repeat="slog in single_payments_log">
							<td>{{$index + 1}}</td>
							<td>{{slog.amount}}</td>
							<td>{{slog.old_total_expense}}</td>
							<td>{{slog.updated_total_expense}}</td>
							<td>{{slog.old_total_paid}}</td>
							<td>{{slog.updated_total_paid}}</td>
							<td>
								<p ng-if="slog.old_total_due>=0">D={{slog.old_total_due}}</p>
								<p ng-if="slog.old_total_due<0">A={{slog.old_total_due*(-1)}}</p>
							</td>
							<td>
								<p ng-if="slog.updated_total_due>=0">D={{slog.updated_total_due}}</p>
								<p ng-if="slog.updated_total_due<0">A={{slog.updated_total_due*(-1)}}</p>
							</td>
							<td>{{slog.remarks}}</td>
							<td>{{slog.created}}</td>
							<td>{{slog.first_name}} {{slog.last_name}}</td>
							<td>
								<div ng-if="type=='All'">
									<button type="button" class="btn btn-xs btn-primary" ng-click="viewCustomer(customer.id)" data-toggle="modal" data-target="#viewModal" ng-disabled><span class="glyphicon glyphicon-edit"></span></button>
									<button type="button" class="btn btn-xs btn-danger" ng-click="viewCustomer(customer.id)" data-toggle="modal" data-target="#updateModal" ng-disabled><span class="glyphicon glyphicon-trash"></span></button>
								</div>
								<div ng-if="type=='Trash'">
									<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#restoreModal" ng-click="findId(customer.id)"><span class="glyphicon glyphicon-retweet"></span></button>
									<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#finalDeleteModal" ng-click="findId(customer.id)"><span class="glyphicon glyphicon-trash"></span></button>
								</div>
							</td>
						  </tr>
						</tbody>
					</table>
				</div>
			  </div>
			</div>
		</div>
		<!-- Single Payment History Modal -->
	
	</div>
	
	<script src="../../resources/datatables/jquery.dataTables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.bootstrap.min.js"></script>
	<script src="../../resources/app/controller/customerController.js"></script>
		

<?php require_once '../layout/footer.php';?>