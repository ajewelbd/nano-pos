var api_url = "../api/dashboard.php?service=";
app.controller("dashboardCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    $scope.dailySales = function () {
      $http.get(api_url + "daily_sales").then(function (response) {
        var dataSet = [];
        for (var i = 0; i < response.data.length; i++) {
          dataSet.push([
            i + 1,
            response.data[i].invoice,
            response.data[i].total,
            response.data[i].paid,
            response.data[i].due,
            response.data[i].payment_method,
          ]);
        }
        $scope.type = "All";
        console.log(dataSet);
        $("#example").DataTable({
          data: dataSet,
          columns: [
            { title: "Sr." },
            { title: "Invoice" },
            { title: "Total" },
            { title: "Paid" },
            { title: "Due" },
            { title: "Payment Method" },
          ],
        });
      });
    };
    $scope.dailySales();

    $scope.headerWidget = function () {
      $http.get(api_url + "header_widget").then(function (response) {
        //console.log(response.data);
        $scope.widget = response.data;
        $scope.widget.suppliers.supplied =
          parseFloat(response.data.suppliers.added) -
          parseFloat(response.data.suppliers.removed);
      });
    };
    $scope.headerWidget();

    $scope.inventoryHightlights = function (type) {
      $scope.lowest_inventory_product = {};
      $http
        .get(api_url + "inventory_hightlights&type=" + type)
        .then(function (response) {
          console.log(response.data);
          $scope.lowest_inventory_product = response.data;
        });
    };
    $scope.inventoryHightlights("lowest_inventory");
  },
]);
