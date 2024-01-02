var URL = "../api/inventory.php?service=";
app.controller("inventoryCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    $scope.type = "log";
    $("#select-suppliers").select2({
      placeholder: "Select a Supplier",
      allowClear: true,
    });
    $scope.showInventory = function () {
      $http.get(URL + "show_inventory").then(function (response) {
        $scope.inventories = response.data;
        //$scope.type="Trash";
        $scope.type = "log";
      });
    };

    $scope.inventories = $scope.showInventory();

    /* ########################Start of Show History############################ */
    $scope.showLogAll = function () {
      $http.get(URL + "show_log_all").then(function (response) {
        $scope.logsData = response.data;
      });
    };

    /* ########################End of Show History############################ */

    /* ########################Data Table############################ */
    $scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withBootstrap()

      .withOption("order", [0, "asc"]);

    /* ########################Data Table############################ */

    /* ########################Start of Live Search############################ */
    $scope.getProducts = function () {
      $http.get(URL + "get_products").then(function (response) {
        $scope.product_list = response.data;
      });
    };

    $scope.getProducts();
    $scope.complete = function (string) {
      //alert(string);
      $scope.hidelist = false;
      var output = [];
      angular.forEach($scope.product_list, function (product_query) {
        if (
          product_query.product_name
            .toLowerCase()
            .indexOf(string.toLowerCase()) >= 0
        ) {
          output.push(product_query);
        }
      });
      $scope.product_filter_data = output;
    };

    $scope.pro_id = "";
    $scope.setProduct = function (id, pro_name) {
      //alert(pro_name);
      $scope.pro_id = id;
      $scope.pro_name = pro_name;
      $scope.focus = false;
    };
    /* ########################End of Live Search############################ */

    /* ########################Start of Add Product In Inventory############################ */
    //$scope.add_inventory={};
    $scope.addInventory = function (uid, pro_id) {
      $http({
        method: "POST",
        url: URL + "add_inventory",
        data: { uid: uid, product_id: pro_id },
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#addModal").modal("hide");
          $scope.showInventory();
          $scope.uid = "";
          $scope.product_id = "";
        } else if (response.data.state == "ERR") {
          $scope.messageError(response.data.msg);
          $("#addModal").modal("hide");
          $scope.showInventory();
        }
      });
      //
    };

    /* ########################End of Add Product In Inventory############################ */

    //Change Quantity
    $scope.change_quantity = {};
    //$scope.change_quantity.buy_price=0;
    $scope.changeQuantity = function (id) {
      $http({
        method: "POST",
        url: URL + "change_quantity&id=" + id,
        data: $scope.change_quantity,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.change_quantity = {};
          $scope.messageSuccess(response.data.msg);
          $("#changeQuantityModal").modal("hide");
          $scope.showInventory();
        } else {
          $scope.change_quantity = {};
          $scope.messageSuccess(response.data.msg);
          $("#changeQuantityModal").modal("hide");
          $scope.showInventory();
        }
      });
    };

    //Show Log Single Product
    $scope.showLog = function (id, product_name) {
      $scope.product_name = product_name;
      $http.get(URL + "show_log_single&id=" + id).then(function (response) {
        $scope.logs = response.data;
      });
    };

    $scope.id = "";
    $scope.findInfo = function (
      inv_id,
      pro_id,
      product_name,
      inventory_quantity
    ) {
      $scope.inv_id = inv_id;
      $scope.pro_id = pro_id;
      $scope.product_name = product_name;
      $scope.quantity = inventory_quantity;
      //$scope.suppliers=[];
      $http
        .get("../api/suppliers.php?service=show_suppliers")
        .then(function (response) {
          $scope.suppliers = response.data;
        });
      $("#changeQuantityModal").modal("show");
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
