<?php require_once '../layout/header.php';
use POS\Products\Products;
$products=new Products();
$products=$products->show();
?>

<div ng-controller="productCtrl">
	<div class="jProducts">
		<div class="alert alert-danger notify"><p></p></div>
        <div class="alert alert-success notify"><p></p></div>
		<div class="pro-header">
			<div class="row">
				<div class="col-md-8">
					<form class="form-inline" role="form">
						<div class="form-group">
							<label for="email">Search:</label>
							<input type="text" class="form-control" id="search" ng-model="search">
							<button type="button" class="btn btn-success" ng-model="addModal" data-toggle="modal" data-target="#addModal">Add Product</button>
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
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
			  <?php foreach($products as $product):?>
			<tr>
			  <td><?php echo $product['product_name'];?></td>
			  <td><?php echo $product['product_model'];?></td>
			  <td><?php echo $product['size'];?></td>
			  <td><?php echo $product['base_price'];?></td>
			  <td><?php echo $product['created'];?></td>
			  
			  <td>
				  <button type="button" class="btn btn-xs btn-success" ng-click="viewProduct(<?php echo $product['id'];?>)" data-toggle="modal" data-target="#viewModal"><span class="glyphicon glyphicon-ok-circle"></span> view</button>
				  <button type="button" class="btn btn-xs btn-info" ng-click="viewProduct(<?php echo $product['id'];?>)" data-toggle="modal" data-target="#updateModal"><span class="glyphicon glyphicon-edit"></span> update</button>
				  <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal"><span class="glyphicon glyphicon-remove-circle"></span> Delete</button>
			  </td>
			</tr>
			<?php endforeach;?>
		  </tbody>
		</table>
	</div>
	
	<!-- Add Product Modal -->
		<div class="modal fade" id="addModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Enter Product Details</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" name="add_products" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <fieldset>
					  <input type="hidden" ng-model="uid" name="id" ng-value="{{uid}}">
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
							<button type="submit" class="btn btn-primary" ng-click="addProducts(add_products.$valid)">Submit</button>
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
					<h4 class="modal-title">Update Product Details {{p.product_name}}</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal" ng-submit="updateProduct()" name="upForm" ng-repeat="p in product" ng-init="uid='<?php echo $_SESSION['id'];?>'">
					  <fieldset>
						<input type="text" ng-value="p.id" ng-model="up.id" name="id">
						<input type="text" ng-value="{{uid}}" ng-model="up.uid" name="uid">
						<div class="form-group">
						  <label for="name" class="col-lg-2 control-label">Name</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="p.product_name" ng-model="up.product_name" name="product_name" class="form-control" id="product_name" placeholder="Product Name">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="model" class="col-lg-2 control-label">Model</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="p.product_model" ng-model="up.product_model" name="product_model"class="form-control" id="product_model" placeholder="Product Model">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="size" class="col-lg-2 control-label">Size</label>
						  <div class="col-lg-10">
							<input type="text" ng-value="p.size" ng-model="up.size" class="form-control" name="size" id="size" placeholder="Product Size">
						  </div>
						</div>
						
						<div class="form-group">
						  <label for="base_price" class="col-lg-2 control-label">Base Price</label>
						  <div class="col-lg-10">
							<input type="number" ng-value="p.base_price" ng-model="up.base_price" name="base_price" class="form-control" id="base_price" placeholder="Base Price">
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-primary" ng-disabled="upForm.$invalid || $untouched">Submit</button>
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
				<h4 class="modal-title">Details of the Product</h4>
			  </div>
			  <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover" ng-repeat="p in product">
						<tbody>
						  <tr>
							<th>Name</th>
							<td>{{p.product_name}}</td>
							<th>Model</th>
							<td>{{p.product_model}}</td>
						  </tr>
						  <tr>
							<th>Size</th>
							<td>{{p.size}}</td>
							<th>Base Price</th>
							<td>{{p.base_price}}</td>
						  </tr>
						  <tr>
							<th>Added</th>
							<td>{{p.created}}</td>
							<th>Updated</th>
							<td>{{p.updated}}</td>
						  </tr>
						  <tr>
							<th>Added By</th>
							<td>{{p.product_name}}</td>
							<th>Firstname</th>
							<td>{{p.product_name}}</td>
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
							<button type="button" class="btn btn-block btn-success">Yes</button>
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