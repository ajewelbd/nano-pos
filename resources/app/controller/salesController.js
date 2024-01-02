var api_url = "../api/sales.php?service=";
app.controller("salesCtrl", [
  "$scope",
  "$http",
  "DTOptionsBuilder",
  "DTColumnDefBuilder",
  function ($scope, $http, DTOptionsBuilder, DTColumnDefBuilder) {
    //$scope.order={cash:0};
    $("#select-product").select2({
      placeholder: "Select a product",
      allowClear: true,
    });

    $scope.showProducts = function () {
      $http.get(api_url + "show_products").then(function (response) {
        return ($scope.productList = response.data);
        //$scope.type="Trash";
        //$scope.type="All";
      });
    };

    $scope.showProducts();

    //$scope.product_name='';
    //$scope.product_id='';
    //$scope.base_price='';

    $scope.selected_product = [];
    $scope.setProduct = function (product) {
      //$scope.check_low_stock=stock;
      if (parseInt(product.stock) < 50) {
        $scope.inStock = product.stock;
        $("#lowStockModal").modal("show");
        //$scope.setClass='danger';
      }
      if (parseInt(product.stock) < 5) {
        return;
      }
      //$scope.setClass='success';
      $scope.product_name = product.product_name;
      $scope.product_stock = product.stock;
      $scope.product_id = product.id;

      //$scope.base_price=base_price;
      if ($scope.selected_product.length > 0) {
        for (var x = 0; x < $scope.selected_product.length; x++) {
          if (product.id == $scope.selected_product[x].id) {
            $scope.selected_product[x].quantity += 1;
            return;
          }
        }
      }
      if ($scope.selected_product.indexOf($scope.selected) == -1) {
        $scope.selected_product.push({
          id: product.id,
          inv_id: product.inv_id,
          quantity: 1,
          sale_price: parseFloat(product.base_price),
          sub_total: 2 * parseFloat(product.base_price),
          product_name: product.product_name,
        });
      }
    };
    $scope.exceeded_stock = true;
    $scope.stockExceed = function (index) {
      if (index != "undefined" || index != "" || index != null) {
        if ($scope.selected_product[index].quantity > $scope.product_stock) {
          $scope.exceeded_stock = false;
          $scope.messageError(
            "Quantity is Greater Than The Stock !! Please reduce quantity Otherwise Sales Impossible"
          );
        } else if (
          $scope.selected_product[index].quantity < $scope.product_stock
        ) {
          $scope.exceeded_stock = true;
        }
      }
    };

    $scope.customer_id = "";
    $scope.selectCustomer = false;
    //$scope.customer_dues=null;
    //$scope.setCustomer = function(id, name, address, mobile){
    $scope.setCustomer = function (customer_info) {
      //alert(customer_info.dues);
      //$scope.order={customer_id:id};
      $scope.selectCustomer = true;
      $scope.customer_name = customer_info.name;
      $scope.customer_id = customer_info.id;
      $scope.customer_address = customer_info.address;
      $scope.customer_mobile = customer_info.mobile;
      if (
        customer_info.total_due != null ||
        parseFloat(customer_info.total_due) > 0
      ) {
        $scope.customer_dues = customer_info.total_due;
      } else {
        $scope.customer_dues = null;
      }
      $scope.focus_customer = false;
    };

    $scope.getTotal = function () {
      $scope.total = 0;
      for (var i = 0; i < $scope.selected_product.length; i++) {
        var item = $scope.selected_product[i];
        $scope.total += item.quantity * item.sale_price;
      }
      //angular.forEach($scope.selected_product, function($scope.selected){
      //total += selected.quantity * selected.sale_price;
      //})
      //$scope.order ={total_payable:$scope.total};
      return $scope.total;
    };

    //$scope.order ={cash:0,payment_method:''};

    $scope.submitOrder2 = function () {
      $scope.rows = $scope.selected_product;
      $scope.columns = [
        { title: "Sr.", dataKey: "$index" },
        { title: "Name", dataKey: "product_name" },
        { title: "Quantity", dataKey: "quantity" },
        { title: "Sale Price", dataKey: "sale_price" },
        { title: "Sub Total", dataKey: "sub_total" },
      ];
      $scope.doc = new jsPDF("p", "pt");
      $scope.doc.autoTable($scope.columns, $scope.rows);
      setTimeout(function () {
        $scope.doc.autoPrint();
        $scope.doc.save("test.pdf");
      }, 1000);
    };

    $scope.printInvoice = function (advance, invoice) {
      var currentDate = new Date();
      var today =
        currentDate.getDate() +
        "-" +
        (parseInt(currentDate.getMonth()) + 1) +
        "-" +
        currentDate.getFullYear();

      var note1 = "1. Cash or A/C Payee Cheque in Favor of 'Print Surface'.";
      var note2 =
        "       2. Please inform Accounts department for any information.";
      var product = [];
      var header = [
        { text: "Sl. No", fillColor: "#1cbaed" },
        { text: "Product Name", fillColor: "#1cbaed" },
        { text: "Quantity", alignment: "center", fillColor: "#1cbaed" },
        { text: "Unit Price", alignment: "center", fillColor: "#1cbaed" },
        { text: "Sub Total", alignment: "center", fillColor: "#1cbaed" },
      ];
      product.push(header);
      var tot = 0;
      for (var i = 0; i < $scope.selected_product.length; i++) {
        tot +=
          parseFloat($scope.selected_product[i].quantity) *
          parseFloat($scope.selected_product[i].sale_price);
        var elm = [];
        elm.push({ text: i + 1, alignment: "center" });
        elm.push({ text: $scope.selected_product[i].product_name.toString() });
        elm.push({
          text: $scope.selected_product[i].quantity.toString(),
          alignment: "center",
        });
        elm.push({
          text: $scope.selected_product[i].sale_price.toString(),
          alignment: "center",
        });
        elm.push({
          text:
            parseFloat($scope.selected_product[i].quantity) *
            parseFloat($scope.selected_product[i].sale_price),
          alignment: "center",
        });
        product.push(elm);
      }

      product.push([
        {
          text: "Taka(in words): " + numToWords(tot) + "taka only.",
          italic: true,
          fontSize: 10,
          alignment: "jsutify",
          colSpan: 3,
        },
        {},
        {},
        {
          text: "Total Ammount",
          alignment: "center",
          border: [false, true, true, true],
        },
        { text: tot, alignment: "center" },
      ]);
      product.push([
        {
          text: "Note: " + note1 + "\n" + note2,
          fontSize: 10,
          alignment: "jsutify",
          rowSpan: 2,
          colSpan: 3,
          border: [false, true, false, false],
        },
        {},
        {},
        {
          text: "Advance",
          alignment: "right",
          border: [false, true, true, false],
        },
        { text: advance, alignment: "center" },
      ]);
      product.push([
        {},
        {},
        {},
        {
          text: "Due Ammount",
          alignment: "right",
          border: [false, false, true, false],
        },
        { text: tot - advance, alignment: "center" },
      ]);

      //for advance /Dues calculation
      var balance = parseFloat($scope.customer_dues) + (tot - advance);
      if (balance > 0) {
        if (parseFloat($scope.customer_dues) > 0) {
          product.push([
            {
              text: "Previous Dues",
              alignment: "right",
              colSpan: 4,
              border: [false, false, true, false],
            },
            {},
            {},
            {},
            { text: $scope.customer_dues, alignment: "center" },
          ]);
          product.push([
            {
              text: "Total Dues",
              alignment: "right",
              colSpan: 4,
              border: [false, false, true, false],
            },
            {},
            {},
            {},
            { text: balance, alignment: "center" },
          ]);
        } else if (parseFloat($scope.customer_dues) < 0) {
          product.push([
            {
              text: "Previous Balance",
              alignment: "right",
              colSpan: 4,
              border: [false, false, true, false],
            },
            {},
            {},
            {},
            { text: $scope.customer_dues * -1, alignment: "center" },
          ]);
          product.push([
            {
              text: "Total Dues",
              alignment: "right",
              colSpan: 4,
              border: [false, false, true, false],
            },
            {},
            {},
            {},
            { text: balance, alignment: "center" },
          ]);
        }
      } else if (parseFloat($scope.customer_dues) < 0) {
        product.push([
          {
            text: "Previous Balance",
            alignment: "right",
            colSpan: 4,
            border: [false, false, true, false],
          },
          {},
          {},
          {},
          { text: $scope.customer_dues * -1, alignment: "center" },
        ]);
        product.push([
          {
            text: "Incurred Balance",
            alignment: "right",
            colSpan: 4,
            border: [false, false, true, false],
          },
          {},
          {},
          {},
          { text: balance * -1, alignment: "center" },
        ]);
      }

      var docDefinition = {
        content: [
          {
            style: "tableExample",
            table: {
              widths: ["*", 100, "*"],
              headerRows: 1,
              body: [
                [
                  {
                    text: "Client" + "'s" + " Name: " + $scope.customer_name,
                    margin: [0, 55, 0, 5],
                  },
                  { text: "Date : " + today, margin: [0, 55, 0, 5] },
                  { text: "Invoice: #" + invoice, margin: [0, 55, 0, 5] },
                ],
                [
                  {
                    colSpan: 2,
                    text: "Address: " + $scope.customer_address,
                    margin: [0, 5, 0, 15],
                  },
                  {},
                  {
                    text: "Cell: " + $scope.customer_mobile,
                    margin: [0, 5, 0, 15],
                  },
                ],
              ],
            },
            layout: "noBorders",
          },
          {
            table: {
              // headers are automatically repeated if the table spans over multiple pages
              // you can declare how many rows should be treated as headers
              headerRows: 1,
              widths: [35, 150, "*", 100, "*"],
              body: product,
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
      pdfMake.createPdf(docDefinition).print();
      //pdfMake.createPdf(docDefinition).download(invoice+'.pdf');
    };

    $scope.submitOrder = function (total, cash, payment_method) {
      var advance = parseFloat(cash);
      if (!$scope.isEmpty($scope.uid) && !$scope.isEmpty($scope.customer_id)) {
        $("#errModal").modal("show");
      } else if ($scope.isEmpty(total)) {
        $scope.messageError("Total is not Set. Set total First.");
      } else {
        $scope.order = {
          uid: $scope.uid,
          customer_id: $scope.customer_id,
          total_payable: total,
          paid: cash,
          payment_method: payment_method,
        };

        //$scope.order ={customer_id:$scope.customer_id, total_payable:x};

        $scope.data = {
          products: $scope.selected_product,
          others: $scope.order,
        };

        $http({
          method: "POST",
          url: api_url + "submit_order",
          data: $scope.data,
          header: { "Content-Type": "x-www-form-urlencoded" },
        }).then(function (response) {
          if (response.data.state == "OK") {
            $scope.printInvoice(advance, response.data.invoice);
            $scope.selected_product = [];
            $scope.order = {};
            $scope.cash = "";
            $scope.customer_id = "";
            $scope.selectCustomer = false;
            $scope.customer_name = "";
            $scope.customer_dues = null;
            $scope.messageSuccess(response.data.msg);
          } else {
            $scope.messageError(response.data.msg);
          }
        });
      }
    };

    $scope.removeProduct = function (index) {
      $scope.selected_product.splice(index, 1);
    };

    $scope.showCustomers = function () {
      $http
        .get("../api/customers.php?service=get_customers")
        .then(function (response) {
          $scope.customerList = response.data;
          //$scope.type="Trash";
          //$scope.type="All";
        });
    };
    $scope.clearInput = function () {
      $scope.focus = false;
    };

    $scope.add_customer = {};
    $scope.addCustomer = function () {
      $http({
        method: "POST",
        url: "../api/customers.php?service=add_customer",
        data: $scope.add_customer,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.add_customer.customer_id = response.data.customer_id;
          $scope.setCustomer($scope.add_customer);
          $scope.messageSuccess(response.data.msg);
          $("#addCustomerModal").modal("hide");
          $scope.showCustomers();
          $scope.add_customer = {};
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#addCustomerModal").modal("hide");
          $scope.showCustomers();
        }
      });
    };

    /* ########################Data Table############################ */
    $scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withBootstrap()

      .withOption("order", [0, "asc"]);

    /* ########################Data Table############################ */
    $scope.type = "All";
    $scope.showSalesHistory = function () {
      $http.get(api_url + "show_sales_history").then(function (response) {
        $scope.type = "All";
        return ($scope.salesHistory = response.data);
        //window.location='trashed_products';
        //$scope.products = response.data;
      });
    };
    $scope.salesHistory = $scope.showSalesHistory();

    $scope.showTrash = function () {
      $http.get(api_url + "show_trashed_sales").then(function (response) {
        $scope.type = "Trash";
        $scope.salesHistory = response.data;
        //window.location='trashed_products';
        //$scope.products = response.data;
      });
    };

    //#################Start of Due Update #################//
    $scope.showDueUpdateForm = function (sales_id, invoice, due) {
      $scope.due_sales_id = sales_id;
      $scope.invoice_show = invoice;
      $scope.due_ammount = due;
      $("#updateDueModal").modal("show");
    };
    $scope.dueUpdate = {};
    $scope.updateDue = function () {
      $scope.dueUpdate.sales_id = $scope.due_sales_id;

      $http({
        method: "POST",
        url: api_url + "update_due",
        data: $scope.dueUpdate,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      }).then(function (response) {
        if (response.data.state == "OK") {
          $scope.messageSuccess(response.data.msg);
          $("#updateDueModal").modal("hide");
          $scope.dueUpdate = {};
          $scope.showSalesHistory();
        } else {
          $scope.messageSuccess(response.data.msg);
          $("#updateDueModal").modal("hide");
          $scope.dueUpdate = {};
          $scope.showSalesHistory();
        }
      });
    };

    $scope.calculate_due = function () {
      return $scope.due_ammount - $scope.dueUpdate.ammount;
    };
    //#################End of Due Update #################//

    $scope.sales_id = "";
    $scope.findId = function (id) {
      $scope.sales_id = id;
    };

    $scope.showSoldProducts = function (sales_id, total) {
      $http
        .post(api_url + "sold_products_list", { sales_id: sales_id })
        .then(function (response) {
          $scope.soldProductsList = response.data;
          $scope.ordersTotal = total;
          //window.location='trashed_products';
          //$scope.products = response.data;
        });
    };

    $scope.softDeleteSales = function () {
      $http
        .post(api_url + "soft_delete_sales", { sales_id: $scope.sales_id })
        .then(function (response) {
          if (response.data.state == "OK") {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showSalesHistory();
          } else {
            $scope.messageSuccess(response.data.msg);
            $("#deleteModal").modal("hide");
            $scope.showSalesHistory();
          }
        });
    };

    $scope.restoreSales = function () {
      $http
        .post(api_url + "restore_sales", { sales_id: $scope.sales_id })
        .then(function (response) {
          if (response.data.state == "OK") {
            //$scope.msg=response.data;
            $scope.messageSuccess(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          } else {
            //$scope.msg="Product information not Deleted successfully";
            $scope.messageError(response.data.msg);
            $("#restoreModal").modal("hide");
            $scope.showTrash();
          }
        });
    };

    $scope.finalDelete = function () {
      $http
        .post(api_url + "final_delete_sales", { sales_id: $scope.sales_id })
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

    $scope.prinInvoice = function (products, others) {
      var myData = [];
      $http.get(api_url + "show_customers").then(function (response) {
        var myData1 = response.data;
        //for(var i=0; i<response.data.length; i++){
        //myData.push(response.data[i].name.toString(),response.data[i].owners_name.toString(),response.data[i].address.toString(),response.data[i].mobile.toString());
        //}
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
            { text: "List OF Customers", margin: [5, 2, 10, 20] },
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
        };
        pdfMake.createPdf(docDefinition).print();
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

    const arr = (x) => Array.from(x);
    const num = (x) => Number(x) || 0;
    const str = (x) => String(x);
    const isEmpty = (xs) => xs.length === 0;
    const take = (n) => (xs) => xs.slice(0, n);
    const drop = (n) => (xs) => xs.slice(n);
    const reverse = (xs) => xs.slice(0).reverse();
    const comp = (f) => (g) => (x) => f(g(x));
    const not = (x) => !x;
    const chunk = (n) => (xs) =>
      isEmpty(xs) ? [] : [take(n)(xs), ...chunk(n)(drop(n)(xs))];

    // numToWords :: (Number a, String a) => a -> String
    let numToWords = (n) => {
      let a = [
        "",
        "one",
        "two",
        "three",
        "four",
        "five",
        "six",
        "seven",
        "eight",
        "nine",
        "ten",
        "eleven",
        "twelve",
        "thirteen",
        "fourteen",
        "fifteen",
        "sixteen",
        "seventeen",
        "eighteen",
        "nineteen",
      ];

      let b = [
        "",
        "",
        "twenty",
        "thirty",
        "forty",
        "fifty",
        "sixty",
        "seventy",
        "eighty",
        "ninety",
      ];

      let g = [
        "",
        "thousand",
        "million",
        "billion",
        "trillion",
        "quadrillion",
        "quintillion",
        "sextillion",
        "septillion",
        "octillion",
        "nonillion",
      ];

      // this part is really nasty still
      // it might edit this again later to show how Monoids could fix this up
      let makeGroup = ([ones, tens, huns]) => {
        return [
          num(huns) === 0 ? "" : a[huns] + " hundred ",
          num(ones) === 0 ? b[tens] : (b[tens] && b[tens] + "-") || "",
          a[tens + ones] || a[ones],
        ].join("");
      };

      let thousand = (group, i) => (group === "" ? group : `${group} ${g[i]}`);

      if (typeof n === "number") return numToWords(String(n));
      else if (n === "0") return "zero";
      else
        return comp(chunk(3))(reverse)(arr(n))
          .map(makeGroup)
          .map(thousand)
          .filter(comp(not)(isEmpty))
          .reverse()
          .join(" ");
    };
  },
]);
