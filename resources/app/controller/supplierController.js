var URL = "../api/suppliers.php?service=";
app.controller("supplierCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    $scope.type = "All";
    $scope.showSuppliers = function () {
      $http.get(URL + "show_suppliers").then(function (response) {
        $scope.suppliers = response.data;
        //$scope.type="Trash";
        $scope.type = "All";
      });
    };

    $scope.suppliers = $scope.showSuppliers();

    $scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withBootstrap()

      .withOption("order", [0, "asc"]);

    $scope.showTrash = function () {
      $http.get(URL + "show_trashed_suppliers").then(function (response) {
        $scope.type = "Trash";
        $scope.suppliers = response.data;
        //window.location='trashed_suppliers';
        //$scope.suppliers = response.data;
      });
    };
    $scope.add_supplier = {};
    $scope.addSupplier = function () {
      $http({
        method: "POST",
        url: URL + "add_supplier",
        data: $scope.add_supplier,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response, status, config, headers) {
        //$scope.reset();
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#addModal").modal("hide");
          $scope.up = "";
          $scope.showSuppliers();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#addModal").modal("hide");
          $scope.showSuppliers();
        }
      });
    };

    $scope.viewSupplier = function (id) {
      $http.get(URL + "view_supplier&id=" + id).then(function (response) {
        $scope.supplier = response.data;
        $scope.sid = $scope.supplier.id;
      });
    };

    $scope.suppliedProducts = function (id, supplier_name) {
      $scope.supplier_name = supplier_name;
      $http.get(URL + "supplied_products&id=" + id).then(function (response) {
        $scope.supplied = response.data;
      });
    };

    $scope.update = {};
    $scope.updateSupplier = function (id) {
      if (!$scope.isEmpty($scope.update)) {
        return;
      }

      $http({
        method: "POST",
        url: URL + "update_supplier&id=" + id,
        data: $scope.update,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.update = {};
          $scope.showSuppliers();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.showSuppliers();
        }
      });
    };
    $scope.sid = "";
    $scope.findId = function (sid) {
      $scope.sid = sid;
    };

    $scope.softDeleteSupplier = function () {
      $http
        .post(URL + "soft_delete_supplier", { sid: $scope.sid })
        .then(function (response) {
          if (response.data.state == "OK") {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showSuppliers();
          } else {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showSuppliers();
          }
        });
    };

    $scope.restoreSupplier = function () {
      $http
        .post(URL + "restore_supplier", { sid: $scope.sid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="supplier information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          }
        });
    };

    $scope.finalDelete = function () {
      $http
        .post(URL + "final_delete_supplier", { sid: $scope.sid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="supplier information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          }
        });
    };
    $scope.bill_info = {};
    $scope.billUpdate = function (id, name, due) {
      $scope.bill_info.supplier_id = id;
      $scope.billed_supplier_name;
      $scope.billed_supplier_name = name;
      $scope.total_due = due;
      $("#billUpdateModal").modal("show");
    };
    $scope.submitBillUpdate = function () {
      $scope.bill_info.added_by = $scope.uid;
      $scope.bill_info.payment_type = 1; //cash
      //$scope.bill_data['supplier_id']=[:]
      $http({
        method: "POST",
        url: URL + "bill_update",
        data: $scope.bill_info,
        headers: { "Content-Type": "x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#billUpdateModal").modal("hide");
          $scope.bill_info = {};
          $scope.showSuppliers();
        } else {
          $scope.messageError(response.data.msg);
          $scope.bill_info = {};
          $scope.showSuppliers();
          $("#billUpdateModal").modal("hide");
        }
      });
    };

    $scope.supplierBillLog = function (id, name) {
      $scope.log_supplier_name = name;
      $http
        .post(URL + "supplier_bill_log", { sid: id })
        .then(function (response) {
          $scope.supplier_logs = response.data;
          $("#supplierBillLog").modal("show");
        });
    };
    $scope.messageSuccess = function (msg) {
      $(".alert-success > p").html(msg);
      $(".alert-success").show();
      $(".alert-success")
        .delay(5000)
        .slideUp(function () {
          $(".alert-success > p").html("");
        });
    };

    // function to display error message
    $scope.messageError = function (msg) {
      $(".alert-danger > p").html(msg);
      $(".alert-danger").show();
      $(".alert-danger")
        .delay(5000)
        .slideUp(function () {
          $(".alert-danger > p").html("");
        });
    };

    $scope.isEmpty = function (obj) {
      for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) return true;
      }
      return false;
    };

    $scope.resetForm = function () {
      this.up = null;
      //$scope.upFrom.$setPristine(true);
    };
  },
]);
