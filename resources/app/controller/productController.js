var URL = "../api/products.php?service=";
app.controller("productCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    $scope.type = "All";
    $scope.showProducts = function () {
      $http.get(URL + "show_products").then(function (response) {
        $scope.products = response.data;
        //$scope.type="Trash";
        $scope.type = "All";
      });
    };

    $scope.products = $scope.showProducts();

    $scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withBootstrap()

      .withOption("order", [0, "asc"]);

    $scope.showTrash = function () {
      $http.get(URL + "show_trashed_products").then(function (response) {
        $scope.type = "Trash";
        $scope.products = response.data;
        //window.location='trashed_products';
        //$scope.products = response.data;
      });
    };

    $scope.addProducts = function () {
      $http
        .post(URL + "add_products", {
          uid: $scope.uid,
          product_name: $scope.product_name,
          product_model: $scope.product_model,
          size: $scope.size,
          base_price: $scope.base_price,
        })
        .then(function (response, status, config, headers) {
          //$scope.reset();
          if (response.statusText == "OK") {
            $scope.msg = "Product information added successfully";
            $scope.messageSuccess($scope.msg);
            $scope.add_products.$setPristine(true);
            $("#addModal").modal("hide");
            $scope.showProducts();
            $scope.product_name = "";
            $scope.product_model = "";
            $scope.size = "";
            $scope.base_price = "";
            $scope.add_products.$setPristine();
          } else {
            $scope.msg = "Product information not added successfully";
            $scope.messageError($scope.msg);
            $("#addModal").modal("hide");
          }
          //$scope.msg="New Product Added Successfully!!!";
          //$('#addModal').modal('hide');
          //$('#notify').show();
          //window.location='products';
        });
    };

    $scope.viewProduct = function (id) {
      $http.get(URL + "view_product&id=" + id).then(function (response) {
        $scope.product = response.data;
        $scope.pid = $scope.product.id;
      });
    };

    $scope.up = {};
    $scope.updateProduct = function (id) {
      if (!$scope.isEmpty($scope.up)) {
        return;
      }

      $http({
        method: "POST",
        url: URL + "update_product&id=" + id,
        data: $scope.up,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response, headers) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.showProducts();
          //$scope.up='';
          //$scope.upForm.$setPristine();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.showProducts();
        }
      });
    };
    $scope.pid = "";
    $scope.findId = function (pid) {
      $scope.pid = pid;
    };

    $scope.softDeleteProduct = function () {
      $http
        .post(URL + "soft_delete_product", { pid: $scope.pid })
        .then(function (response) {
          if (response.data.state == "OK") {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showProducts();
          } else {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showProducts();
          }
        });
    };

    $scope.restoreProduct = function () {
      $http
        .post(URL + "restore_product", { pid: $scope.pid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="Product information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          }
        });
    };

    $scope.finalDelete = function () {
      $http
        .post(URL + "final_delete_product", { pid: $scope.pid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="Product information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          }
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
