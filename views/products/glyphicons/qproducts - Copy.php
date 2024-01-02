<?php require_once '../layout/header.php';
//use POS\Products\Products;
//$products=new Products();
//$products=$products->show();
?>


	<div class="jProducts" ng-controller="productCtrl">
		<div class="pro-header">
			<div class="row">
				<div class="col-md-8">
					<form class="form-inline" role="form">
						<div class="form-group">
							<label for="email">Search:</label>
							<input type="text" class="form-control" id="search" ng-model="search">
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">Add Product</button>
						</div>
					</form>
				</div>
				
				<div class="col-md-4">
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
		<table ng-init="showProducts()" id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
		  <thead>
			<tr>
			  <th>Name</th>
			  <th>Model</th>
			  <th>Size</th>
			  <th>Base Price</th>
			  <th>Added</th>
			  <th width="200px">Action</th>
			</tr>
		  </thead>
		  <tbody>
			<tr ng-repeat="product in products">
			  <td>{{product.product_name}}</td>
			  <td>{{product.product_model}}</td>
			  <td>{{product.base_price}}</td>
			  <td>{{product.base_price}}</td>
			  <td>{{product.created}}</td>
			  
			  <td>
				  <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-ok-circle"></span> view</button>
				  <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#updateModal"><span class="glyphicon glyphicon-edit"></span> update</button>
				  <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal"><span class="glyphicon glyphicon-remove-circle"></span> Delete</button>
			  </td>
			</tr>
		  </tbody>
		</table>
	</div>
	
	<!-- Add Product Modal -->
	<div ng-controller="crudCtrl">
		<div class="modal fade" id="addModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Enter Product Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" name="add_products">
					  <fieldset>
					  <input type="text" ng-model="id" name="id" ng-show="<?php echo $_SESSION['id'];?>">
						<div class="form-group">
						  <label for="name" class="col-lg-2 control-label">Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="product_name" class="form-control" id="name" name="product_name" placeholder="Product Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="model" class="col-lg-2 control-label">Model</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="product_model" class="form-control" id="model" placeholder="Product Model">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="size" class="col-lg-2 control-label">Size</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="size" class="form-control" id="size" placeholder="Product Size">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="base_price" class="col-lg-2 control-label">Base Price</label>
						  <div class="col-lg-10">
							<input type="number" ng-model="base_price" class="form-control" id="base_price" placeholder="Base Price">
						  </div>
						</div>
						<div class="form-group">
						  <div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-click="addProducts()">Submit</button>
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
		
		<!-- Update Product Modal -->
		<div class="modal fade" id="updateModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Product Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <fieldset>
						<div class="form-group">
						  <label for="name" class="col-lg-2 control-label">Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="name" class="form-control" id="name" placeholder="Product Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="model" class="col-lg-2 control-label">Model</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="model" class="form-control" id="model" placeholder="Product Model">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="size" class="col-lg-2 control-label">Size</label>
						  <div class="col-lg-10">
							<input type="text" ng-model="size" class="form-control" id="size" placeholder="Product Size">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="base_price" class="col-lg-2 control-label">Base Price</label>
						  <div class="col-lg-10">
							<input type="number" ng-model="base_price" class="form-control" id="base_price" placeholder="Base Price">
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
		
		<!-- Delete Modal -->
		<div class="modal fade" id="deleteModal" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Are you Sure?</h4>
				</div>
				<div class="modal-body myCenter">
					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-block btn-success">Yes</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-block btn-danger" data-dismiss="modal">No</button>
						</div>
					</div>
				</div>
			  </div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	  $(document).ready(function() {
		$('#datatable').dataTable();
		$('#datatable-keytable').DataTable({
		  keys: true
		});
		$('#datatable-responsive').DataTable();
		$('#datatable-scroller').DataTable({
		  ajax: "../../resources/js/datatables/json/scroller-demo.json",
		  deferRender: true,
		  scrollY: 380,
		  scrollCollapse: true,
		  scroller: true
		});
		var table = $('#datatable-fixed-header').DataTable({
		  fixedHeader: true
		});
	  });
	  //TableManageButtons.init();
    </script>
	<script src="../../resources/js/datatables/jquery.dataTables.min.js"></script>
	<script src="../../resources/js/datatables/dataTables.bootstrap.js"></script>
	<script src="../../resources/js/datatables/dataTables.responsive.min.js"></script>
	<script src="../../resources/js/datatables/responsive.bootstrap.min.js"></script>

<?php require_once '../layout/footer.php';?>