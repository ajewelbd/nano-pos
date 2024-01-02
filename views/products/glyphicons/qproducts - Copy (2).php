<?php require_once '../layout/header.php';
//use POS\Products\Products;
//$products=new Products();
//$products=$products->show();
?>


	<div class="jProducts" ng-controller="productCtrl">
		<div ng-app="myApp" ng-controller="controller">
        <div class="container">
            <br/>
            <h3 align="center">AngularJS Sorting, Searching and Pagination of Data Table using PHP, MySQL</a></h3>
            <br/>
            <div class="row">
                <div class="col-sm-2 pull-left">
                    <label>PageSize:</label>
                    <select ng-model="data_limit" class="form-control">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                </div>
                <div class="col-sm-6 pull-right">
                    <label>Search:</label>
                    <input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control" />
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12" ng-show="filter_data > 0">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>Name&nbsp;<a ng-click="sort_with('name');"><i class="glyphicon glyphicon-sort"></i></a></th>
                            <th>Gender&nbsp;<a ng-click="sort_with('gender');"><i class="glyphicon glyphicon-sort"></i></a></th>
                            <th>Age&nbsp;<a ng-click="sort_with('age');"><i class="glyphicon glyphicon-sort"></i></a></th>
                            <th>Email&nbsp;<a ng-click="sort_with('email');"><i class="glyphicon glyphicon-sort"></i></a></th>
                            <th>Phone&nbsp;<a ng-click="sort_with('phone');"><i class="glyphicon glyphicon-sort"></i></a></th>
                            <th>Organization&nbsp;<a ng-click="sort_with('organization');"><i class="glyphicon glyphicon-sort"></i></a></th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in searched = (file | filter:search | orderBy : base :reverse) | beginning_data:(current_grid-1)*data_limit | limitTo:data_limit">
                                <td>{{data.name}}</td>
                                <td>{{data.gender}}</td>
                                <td>{{data.age}}</td>
                                <td>{{data.email}}</td>
                                <td>{{data.phone}}</td>
                                <td>{{data.organization}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12" ng-show="filter_data == 0">
                    <div class="col-md-12">
                        <h4>No records found..</h4>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6 pull-left">
                        <h5>Showing {{ searched.length }} of {{ entire_user}} entries</h5>
                    </div>
                    <div class="col-md-6" ng-show="filter_data > 0">
                        <div pagination="" page="current_grid" on-select-page="page_position(page)" boundary-links="true" total-items="filter_data" items-per-page="data_limit" class="pagination-small pull-right" previous-text="&laquo;" next-text="&raquo;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	</div>
	<script type="text/javascript">
	  
    </script>
	<script src="../../resources/js/datatables/responsive.bootstrap.min.js"></script>

<?php require_once '../layout/footer.php';?>