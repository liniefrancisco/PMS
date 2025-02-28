window.myApp.controller(
  "paymentCntrl",
  function (
    $scope,
    $http,
    $timeout,
    moment,
    $sce,
    $q,
    $filter,
    $rootScope,
    $interval
  ) {
    $scope.pmt = { application: null};
    $scope.tenant;
    $scope.invoices = [];
    $scope.payment_docs = [];
    $scope.stores = [];

    /*$scope.tender_types = [
        {id : 1, desc : 'Cash'},
        {id : 2, desc : 'Check'},
        {id : 3, desc : 'Bank to Bank'},
        {id : 80, desc : 'JV payment - Business Unit'},
        {id : 81, desc : 'JV payment - Subsidiary'},
        {id : 11, desc : 'Unidentified Fund Transfer'},
        {id : 12, desc : 'Internal Payment'},

    ];*/

    $scope.clearPaymentData = function () {
      $scope.pmt = { application: null };
      $scope.tenant = {};
      $scope.invoices = [];
      $scope.payment_docs = [];
      $scope.getInitData();
    };
    $scope.getInitData = function () {
      let url = $base_url + "leasing/get_payment_initial_data";

      $.get(
        url,
        function (res) {
          $scope.banks = res.banks;
          $scope.pmt.uft_no = res.uft_no;
          $scope.pmt.ip_no = res.ip_no;
          $scope.stores = res.stores;

          $scope.pmt.bank = res.banks[0] ? res.banks[0] : {};
          console.log($scope.pmt.bank);
          $scope.$apply();
        },
        "json"
      );
    };
    $scope.getTransactionNo = function (tender_typeCode) {
      let tenant_id = $scope.tenant.tenant_id;
      var data = { tenant_id };
      let url = $base_url + "leasing/getTransactionNo";

      if (tender_typeCode == "11" || tender_typeCode == "12") {
        $.get(
          url,
          data,
          function (res) {
            $scope.pmt.uft_no = res.uft_no;
            $scope.pmt.ip_no = res.ip_no;
            $scope.$apply();
          },
          "json"
        );
      }
    };
    $scope.generate_paymentCredentials = function (trade_name, tenancy_type) {
      $scope.payment_docs = [];
      $scope.pmt.soa_docs = [];
      $scope.invoices = [];
      $scope.pmt.application = null;
      $scope.pmt.payment_date = "";

      var data = { trade_name, tenancy_type };
      let url = $base_url + "leasing/get_tenant_details";
      console.log(
        "file: payment.js:69 ~ window.myApp.controller ~ data:",
        data
      );

      $.get(
        url,
        data,
        function (res) {
          $scope.tenant = res;
          $scope.$apply();
        },
        "json"
      );
    };
    $scope.testttt = function (trade_name, tenancy_type) {
      alert(trade_name);
      console.log(tenancy_type);
    };
    $scope.asd = function () {
      alert();
    };
    var loader = null;
    $scope.getSoaWithBalances = function (tenant_id, posting_date) {
      $scope.payment_docs = [];
      $scope.pmt.soa_docs = [];
      $scope.invoices = [];
      $scope.pmt.application = null;

      if (!loader) {
        loader = pop.loading("Collecting data. Please wait ...");
      } else {
        loader.modal("show");
      }

      let url =
        $base_url + `leasing/getSoaWithBalances/${tenant_id}/${posting_date}`;

      $.get(
        url,
        function (res) {
          $scope.pmt.soa_docs = res;
          if (res.length > 0) {
            $scope.pmt.soa = res[0];
            $scope.getInvoicesBySoaNo(tenant_id, res[0].soa_no);
          } else {
            pop.alert("<strong>No SOA with balances found!</strong>");
          }

          loader.modal("hide");
          $scope.$apply();
        },
        "json"
      );
    };
    $scope.getInvoicesBySoaNo = async function (tenant_id, soa_no) {
      if (!soa_no || soa_no == "" || soa_no.length == 0) {
        return;
      }
      $scope.payment_docs     = [];
      $scope.invoices         = [];
      $scope.pmt.application  = null;

      if (!loader) {
        loader = pop.loading("Collecting data. Please wait ...");
      } else {
        loader.modal("show");
      }

      let url = $base_url + `leasing/getInvoicesBySoaNo/${tenant_id}/${soa_no}`;

      await $.get(
        url,
        function (res) {
          $scope.invoices = res;
          $scope.$apply();
        },
        "json"
      );

      loader.modal("hide");
    };
    $scope.selectInvoice = function (invoice) {
      let arr_num = $scope.invoices.map(function (i) {
        if (!i.sequence) return 0;
        return i.sequence;
      });

      let nextNum = Math.max(...arr_num) + 1;

      invoice.sequence = invoice.selected ? nextNum : null;

      //console.log($scope.invoices);
    };
    $scope.getSelectedInvoices = function () {
      let selectedInvs = $scope.invoices.filter((inv) => {
        return inv.selected;
      });

      return selectedInvs;
    };
    $scope.applySelectedInvoices = function () {
      pop.confirm(
        "Are you sure you want to apply the payment to this selected invoices?",
        (res) => {
          if (!res) return;
          $scope.payment_docs = [];
          let selectedInvs    = $scope.getSelectedInvoices();

          let sortedInvs = selectedInvs.sort(function (a, b) {
            if (a.sequence == b.sequence) return 0;
            return b.sequence < a.sequence ? 1 : -1;
          });

          $scope.payment_docs       = sortedInvs;
          $scope.pmt.application    = "Apply To";
          $scope.pmt.selected_docs  = $scope.payment_docs.map((invoice) => ({//Added by Linie
            doc_no: invoice.doc_no,
            svi_no: invoice.svi_no || ""
          }));
          $scope.$apply();
          $("#apply-to-modal").modal("hide");
        }
      );
    };
    $scope.applyOldToNewest = function () {
      pop.confirm(
        "Are you sure you want to apply payment base on invoice's posting date? <br/><b>(OLDEST TO NEWEST)</b>",
        (res) => {
          if (!res) return;
          $scope.payment_docs       = $scope.invoices;
          $scope.pmt.application    = "Oldest to Newest";
          $scope.pmt.selected_docs  = $scope.payment_docs.map((invoice) => ({//Added by Linie
            doc_no: invoice.doc_no,
            svi_no: invoice.svi_no || ""
          }));
          console.log('application:',$scope.pmt.application);
          console.log('selected_docs:',$scope.pmt.selected_docs);
          console.log('payment_docs:',$scope.payment_docs);
          $scope.$apply();
        }
      );
    };
    $scope.totalPayable = function () {
      let total = 0;

      $scope.payment_docs.forEach((doc) => {
        total += parseNumber(doc.balance);
      });

      return total;
    };
    $scope.savePayment = function (e) {
      e.preventDefault();

      let totalPayable = parseNumber($scope.totalPayable());
      let amount_paid = parseNumber($scope.pmt.amount_paid);

      // if(amount_paid > totalPayable && $scope.pmt.application == 'Apply To'){
      //     let adv_amt = parseNumber(amount_paid - totalPayable);
      //     adv_amt = $filter('currency')(adv_amt, 'Php ');
      //     pop.alert(`<b>Advance Payment</b> amounting <b>${adv_amt}</b> was detected. </br>
      //         <b>Advance Payment</b> is <b>not allowed</b> when using <b>"Apply To"</b>.  </br>
      //         Please <b>select more invoice</b> or choose <b>"OLDEST TO NEWEST"</b> to proceed!`);
      //     return;
      // }

      pop.confirm("Do you really want to <b>post this payment</b>?", (res) => {
        if (!res) return;

        let payment_docs = JSON.parse(angular.toJson($scope.payment_docs));
        let formData = new FormData(e.target);

        formData = convertModelToFormData(
          payment_docs,
          formData,
          "payment_docs"
        );

        let loader = pop.loading("Posting Payment. Please wait ...");

        let url = $base_url + "leasing/save_payment";

        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          enctype: "multipart/form-data",
          async: true,
          cache: false,
          contentType: false,
          processData: false,
          success: function (res) {
            loader.modal("hide");

            if (res.type == "success") {
              if (res.file) {
                // window.open('http://172.16.170.10/PMS/assets/pdf/' + res.file);
                window.open($base_url + "assets/pdf/" + res.file);
              }

              notify("success", res.msg);

              $scope.clearPaymentData();
              return;
            }

            generate(res.type, res.msg);
          },
        });
      });
    };
    var ccm_customers = [];
    $scope.getCcmCustomers = function () {
      let url = $base_url + "ctrl_cfs/populate_ccm_customer";
      $http.post(url).success(function (result) {
        ccm_customers = result;
      });
    };
    $scope.ccm_cust_autocomplete = {
      suggest: (term) => {
        var q = term.toLowerCase().trim();
        var results = [];

        // Find first 10 states that start with `term`.
        for (var i = 0; i < ccm_customers.length && results.length < 10; i++) {
          var state = ccm_customers[i].fullname;
          if (state.toLowerCase().indexOf(q) === 0)
            results.push({ label: state, value: state });
        }

        return results;
      },
    };
    $scope.get_paymentScheme = function (trade_name, tenancy_type) {
      var objData = { trade_name: trade_name, tenancy_type: tenancy_type };
      $http
        .post("../leasing_transaction/get_tenantDetails/", objData)
        .success(function (result) {
          console.log(result);
          $scope.trade_name = result[0].trade_name;
          $scope.tenant_id = result[0].tenant_id;
          $scope.tin = result[0].tin;
          $scope.contract_no = result[0].contract_no;
          $scope.rental_type = result[0].rental_type;
          $scope.address = result[0].address;

          var url_payment =
            "../leasing_reports/get_orNumberEntry/" + result[0].tenant_id;
          $rootScope.$emit("CallTablelistMethod", url_payment);
        });
    };
    $scope.getTransactionNo2 = function (data) {
      var paymentSchemeID = data.id;
      $("#transaction_number").val(paymentSchemeID);
    };
    $scope.addORNumber = function (e) {
      e.preventDefault();

      pop.confirm("Are you sure you want to add this OR Number?", (res) => {
        if (!res) return;

        var formData = new FormData(e.target);

        $.ajax({
          type: "POST",
          url: $base_url + "leasing/saveORNumber",
          data: formData,
          enctype: "multipart/form-data",
          async: true,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $("#spinner_modal").modal({
              backdrop: "static",
              keyboard: false,
            });
          },
          success: function (response) {
            $("#spinner_modal").modal("toggle");

            if (response.status == "success") {
              $("#add_or").modal("toggle");
              pop.alert(response.msg);

              $scope.or_number = "";
              $scope.get_paymentScheme($scope.trade_name, $scope.tenancy_type);
            } else {
              generate("error", response.msg);
            }
          },
        });
      });
    };
  }
);
