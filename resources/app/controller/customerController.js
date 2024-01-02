var API_URL = "../api/customers.php?service=";
app.controller("customerCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    $scope.type = "All";
    $scope.customers = [];
    $scope.showCustomers = function () {
      $http.get(API_URL + "show_customers").then(function (response) {
        $scope.type = "All";
        $scope.customers = response.data;
        //$scope.type="Trash";
      });
    };

    var x = $scope.showCustomers();

    $scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.fromFnPromise(function () {})
      .withBootstrap()

      .withOption("order", [0, "asc"]);

    $scope.showTrash = function () {
      $http.get(API_URL + "show_trashed_customers").then(function (response) {
        $scope.type = "Trash";
        $scope.customers = response.data;
        //window.location='trashed_customers';
        //$scope.customers = response.data;
      });
    };
    $scope.add_customer = {};
    $scope.addCustomer = function () {
      $http({
        method: "POST",
        url: API_URL + "add_customer",
        data: $scope.add_customer,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response, status, config, headers) {
        //$scope.reset();
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#addModal").modal("hide");
          $scope.up = "";
          $scope.showCustomers();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#addModal").modal("hide");
          $scope.showCustomers();
        }
      });
    };

    $scope.viewCustomer = function (id) {
      $scope.customer = {};
      $http.get(API_URL + "view_customer&id=" + id).then(function (response) {
        $scope.customer = response.data;
        $scope.cid = response.data.id;
      });
    };

    $scope.update = {};
    $scope.updateCustomer = function (id) {
      if (!$scope.isEmpty($scope.update)) {
        return;
      }

      $http({
        method: "POST",
        url: API_URL + "update_customer&id=" + id,
        data: $scope.update,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.update = {};
          $scope.showCustomers();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#updateModal").modal("hide");
          $scope.showCustomers();
        }
      });
    };
    $scope.cid = "";
    $scope.findId = function (cid) {
      $scope.cid = cid;
    };

    $scope.payment_update = {};
    $scope.paymentUpdate = function (id, name, paid, due) {
      $scope.balance_type = 1;
      $scope.payment_update.entry_type = 1;
      $scope.payment_update.amount = 0;
      //$scope.payment_update={customer_id:id};
      $scope.customer_name = name;
      $scope.total_paid = parseFloat(paid);
      $scope.total_due = due;
      $scope.payment_update.customer_id = id;
      $scope.balanceCalculation();
      $("#paymentUpdateModal").modal("show");
    };
    $scope.balance_type = 1;
    $scope.payment_update.entry_type = 1;
    $scope.balanceCalculation = function () {
      if ($scope.payment_update.entry_type == 1 && $scope.total_due < 0) {
        $scope.balance_type = 1;
        $scope.calculated_advance =
          -1 * parseFloat($scope.total_due) +
          parseFloat($scope.payment_update.amount);
      } else if (
        $scope.payment_update.entry_type == 1 &&
        $scope.total_due >= 0
      ) {
        $scope.balance_type = 1;
        $scope.calculated_advance =
          parseFloat($scope.total_due) +
          parseFloat($scope.payment_update.amount);
      } else if ($scope.payment_update.entry_type == 0) {
        $scope.balance_type = 0;
        $scope.calculated_advance =
          -1 * $scope.total_due - $scope.payment_update.amount;
        if ($scope.calculated_advance < 0) {
          alert(
            "Withdraw is Greater Than advance!!!\n Advance is= " +
              $scope.total_due * -1 +
              "\n Withdraw amount is= " +
              $scope.payment_update.amount +
              "\n Please enter amount less than Advance"
          );
        }
      }

      //if(payment_update.entry_type!=undefined && payment_update.entry_type==1){
      //$scope.grand_total=$scope.total_paid + parseFloat(payment_update.amount);
      //}
    };

    $scope.submitPaymentUpdate = function () {
      $http({
        method: "POST",
        url: API_URL + "payment_update",
        data: $scope.payment_update,
        headers: { "Content-Type": "x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#paymentUpdateModal").modal("hide");
          $scope.payment_update = {};
          $scope.showCustomers();
        } else {
          $scope.messageError(response.data.msg);
          $scope.payment_update = {};
          $("#paymentUpdateModal").modal("hide");
        }
      });
    };

    //###################View Transaction ####################//
    $scope.showTransaction = function (customer_id, customer_name) {
      $scope.transaction_customer_name = customer_name;
      $http({
        method: "POST",
        url: API_URL + "show_transaction",
        data: customer_id,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.transactions = response.data.transactions;
          transactions1 = response.data.transactions;
          $scope.transaction_total = 0;
          $scope.transaction_paid = 0;
          $scope.transaction_due = 0;
          for (var i = 0; i < response.data.transactions.length; i++) {
            $scope.transaction_total += parseFloat(
              response.data.transactions[i].total
            );
            $scope.transaction_paid += parseFloat(
              response.data.transactions[i].paid
            );
            $scope.transaction_due += parseFloat(
              response.data.transactions[i].due
            );
          }

          $("#transactionModal").modal("show");
        } else {
          $scope.messageError(response.data.msg);
          $("#transactionModal").modal("hide");
        }
      });
    };
    //###################End of Transaction ####################//

    //################### Customers Payment History ####################//
    $scope.showSinglePaymentHsitory = function (customer_id, customer_name) {
      $scope.history_customer_name = customer_name;
      $http({
        method: "POST",
        url: API_URL + "show_single_payment_hsitory",
        data: customer_id,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.transactions = response.data;
          $scope.single_payments_log = response.data.payments_log;
          $("#singlePaymentHistoryModal").modal("show");
        } else {
          $scope.messageError(response.data.msg);
          $("#singlePaymentHistoryModal").modal("hide");
        }
      });
    };
    //################### Customers Payment History ####################//

    $scope.softDeleteCustomer = function () {
      $http
        .post(API_URL + "soft_delete_customer", { cid: $scope.cid })
        .then(function (response) {
          if (response.data.state == "OK") {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showCustomers();
          } else {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showCustomers();
          }
        });
    };

    $scope.restoreCustomer = function () {
      $http
        .post(API_URL + "restore_customer", { cid: $scope.cid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="customer information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          }
        });
    };

    $scope.finalDelete = function () {
      $http
        .post(API_URL + "final_delete_customer", { cid: $scope.cid })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="customer information not Deleted successfully";
            $scope.messageSuccess(response.data.msg);
            $("#finalDeleteModal").modal("hide");
            $scope.showTrash();
          }
        });
    };
    //myData.push($scope.customers)
    //$('#tt').tableExport({type:'excel', escape:'false'});

    $scope.pdfExport = function () {
      var rows = [];
      $http.get(API_URL + "show_customers").then(function (response) {
        rows = response.data;
        var columns = [
          { title: "Sr.", dataKey: "$index" },
          { title: "Name", dataKey: "name" },
          { title: "Owners Name", dataKey: "owners_name" },
          { title: "Address", dataKey: "address" },
          { title: "Mobile", dataKey: "mobile" },
          { title: "Register Date", dataKey: "created" },
        ];
        var doc = new jsPDF("p", "pt");
        doc.autoTable(columns, rows);
        doc.save("test.pdf");
      });
    };

    $scope.pdfExport1 = function () {
      var myData = [];
      $http.get(API_URL + "show_customers").then(function (response) {
        var myData1 = response.data;
        //for(var i=0; i<response.data.length; i++){
        //myData.push(response.data[i].name.toString(),response.data[i].owners_name.toString(),response.data[i].address.toString(),response.data[i].mobile.toString());
        /////
        var titulos = new Array(
          "Name",
          "Owners Name",
          "Address",
          "Mobile",
          "Added"
        );
        myData.push(titulos);
        for (key in myData1) {
          if (myData1.hasOwnProperty(key)) {
            var x = myData1[key];
            var fila = new Array();
            fila.push(x.name.toString());
            fila.push(x.owners_name.toString());
            fila.push(x.address.toString());
            fila.push(x.mobile.toString());
            fila.push(x.created.toString());
            myData.push(fila);
          }
        }
      });

      setTimeout(function () {
        var docDefinition = {
          content: [
            {
              columns: [
                {
                  text: [
                    {
                      width: 200,
                      text: [
                        "Client Name: ",
                        { text: "Mr. XYZ", style: "cname" },
                      ],
                    },
                    {
                      width: 150,
                      text: ["Date :", { text: "27-12-21018", italics: true }],
                    },
                    {
                      width: 250,
                      text: [
                        "Invoice No.: ",
                        { text: "#POS123456789", italics: true },
                      ],
                    },
                    "\n\n",
                  ],
                },
                {
                  text: [
                    {
                      width: "*",
                      text: [
                        "Address: ",
                        {
                          text: "Banasree, Rampura, Dhaka-1219",
                          style: "cname",
                        },
                      ],
                    },
                    {
                      width: "200",
                      text: ["Cell :", { text: "01716138901", italics: true }],
                    },
                  ],
                },
              ],
              columnGap: 10,
            },
            {
              table: {
                // headers are automatically repeated if the table spans over multiple pages
                // you can declare how many rows should be treated as headers
                headerRows: 1,
                widths: [150, "*", "*", "*", "auto"],
                body: myData,
              },
            },
          ],
          styles: {
            cname: {
              fontSize: 14,
              italics: true,
            },
          },
        };
        pdfMake.createPdf(docDefinition).open();
      }, 500);
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
