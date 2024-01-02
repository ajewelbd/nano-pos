<?php
$page_title="Customers Info"; 
require_once '../layout/header.php';
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<div ng-controller="customerCtrl">
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
</script>
<table id="example" class="display table table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
            </tr>
        </thead>
		<tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
            </tr>
        </tfoot>
        <tbody>
            <tr ng-repeat="c in customers">
                <td>{{c.name}}</td>
                <td>{{c.owners_name}}</td>
                <td>{{c.address}}</td>
                <td>{{c.mobile}}</td>
                <td>{{c.created}}</td>
            </tr>
			</tbody>
			<table>

</div>
		
	
	<script src="../../resources/datatables/jquery.dataTables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.bootstrap.min.js"></script>
	<script src="../../resources/app/controller/customerController.js"></script>
	

<?php require_once '../layout/footer.php';?>