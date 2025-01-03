<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('excel');
        $this->load->model('app_model');
        // $this->load->model('cas_model');
        $this->load->library('upload');
        $this->load->library('ftp');
        $this->load->library('fpdf');
        $this->load->library('fpdi/fpdi_lib');

        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_maxs_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('MAX_EXECUTION_TIME', '-1');

        date_default_timezone_set('Asia/Manila');

        $timestamp          = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->_user_id     = $this->session->userdata('id');

        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        if(!$this->session->userdata('leasing_logged_in') && !$this->session->userdata('cfs_logged_in')){
            redirect('ctrl_leasing/');
        }

        //SET TO FALSE IF PANDEMIC "NO PENALTY RULE" ENDS  
        $this->DISABLE_PENALTY = TRUE;

        $this->portal = $this->load->database('portal', true);
        $this->live = $this->load->database('live', true);
    }

    function sanitize($string){
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function datareport(){
        $data = [
            "receipt_type"=> "OR",
            "tenant"=> [
                "id"=> "4587",
                "trade_name"=> "COLOURS DIGITAL FOTO",
                "corporate_name"=> "COLOURS DIGITAL FOTO",
                "address"=> "2ND FLOOR, FYU CORPORATE CENTER GOLAM DRIVE, MABOLO",
                "tin"=> "103-785-485-000",
                "contract_no"=> "ICM-C000320",
                "tenant_id"=> "ICM-LT000001",
                "rental_type"=> "Fixed",
                "tenant_type"=> "Private Entities",
                "increment_percentage"=> "0",
                "opening_date"=> "2022-06-08",
                "rent_percentage"=> "0",
                "basic_rental"=> "43950.06",
                "org_basic_rental"=> "43950.06",
                "wht"=> "Added",
                "is_vat"=> "Added",
                "vat_agreement"=> "Exclusive",
                "tenancy_type"=> "Long Term",
                "increment_frequency"=> "Annual",
                "vat_percentage"=> "12.00",
                "wht_percentage"=> "5.00",
                "sales"=> "? string=>? string=>? ",
                "penalty_exempt"=> "1",
                "location_code"=> "ICM-000320",
                "floor_area"=> "60.40",
                "is_incrementable"=> "0"
            ],
            "store"=> [
                "id"=> "2",
                "store_name"=> "ISLAND CITY MALL",
                "company_code"=> "01.04",
                "store_code"=> "ICM",
                "dept_code"=> "01.04.2",
                "store_address"=> "Dao-Dampas Tagbilaran City Bohol, Philippines 6300",
                "contact_person"=> "Ma. Luz Alcala",
                "contact_no"=> "501-3000/09190699481",
                "email"=> "leasingacctg@alturasbohol.com",
                "logo"=> "1509172865logo-icm.png",
                "managed_by"=> "Alturas Supermarket Corporation",
                "tin"=> "000-254-327-00003"
            ],
            "receipt_no"=> "PS0000004",
            "soa_no"=> "SOA0000004",
            "payment_date"=> "2023-06-30",
            "remarks"=> "",
            "total_payable"=> 37352.83,
            "payment_docs"=> [
                0 => [
                    "document_type" => "Invoice",
                    "doc_no" => "IC0000008",
                    "posting_date" => "2023-06-23",
                    "due_date" => "2023-07-01",
                    "ref_no" => "GL0000008",
                    "description" => "Basic-COLOURS DIGITAL FOTO",
                    "tag" => "Basic Rent",
                    "gl_accountID" => "4",
                    "tenant_id" => "ICM-LT000001",
                    "invoice_amount" => "25080.83",
                    "adj_amount" => "-2900.00",
                    "debit" => "22180.83",
                    "credit" => "0.00",
                    "balance" => "22180.83",
                    "nopenalty_amount" => "0.00",
                    "org_sequence" => "0",
                    "sequence" => "",
                    "amount_paid" => 22180.83
                ],
                1 => [
                    "document_type" => "Invoice",
                    "doc_no" => "IC0000009",
                    "posting_date" => "2023-06-26",
                    "due_date" => "2023-07-04",
                    "ref_no" => "GL0000009",
                    "description" => "Other-COLOURS DIGITAL FOTO",
                    "tag" => "Other",
                    "gl_accountID" => "22",
                    "tenant_id" => "ICM-LT000001",
                    "invoice_amount" => "15172.00",
                    "adj_amount" => "0.00",
                    "debit" => "15172.00",
                    "credit" => "0.00",
                    "balance" => "15172.00",
                    "nopenalty_amount" => "0.00",
                    "org_sequence" => "1",
                    "sequence" => "",
                    "amount_paid" => 15172.0
                ]
            ],
            "tender_typeCode"=> "1",
            "tender_typeDesc"=> "Cash",
            "check_type"=> "",
            "bank_code"=> "B-007",
            "bank_name"=> "Land Bank of the Philippines",
            "tender_amount"=> "37352.83",
            "check_no"=> "",
            "balance"=> 0.0,
            "check_due_date"=> "1970-01-01",
            "check_date"=> null,
            "advance_amount"=> 0.0,
            "payor"=> "COLOURS DIGITAL FOTO",
            "payee"=> "ISLAND CITY MALL"
        ];

        return $data;
    }

    public function createPaymentDocsFile(){

        $pmt = $this->datareport();

        $pdf = new FPDF('p','mm','A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $pdf->setFont ('times','B',12);
        $logoPath = getcwd() . '/assets/other_img/';


        $store  = $pmt['store'];
        $tenant = $pmt['tenant'];

        $details    = $this->app_model->get_tenant_details_2($tenant['tenant_id']);
        $docno      = "";
        $tin_status = "";
        
        if(isset($details)){
            if($details->tin !== 'ON PROCESS' && $details->tin !== 'NO TIN' && $details->tin !== '' && $details->tin !== null && $details->tin !== '000-000-000' && $details->tin !== '000-000-000-000'){
                $tin_status = "with";
                    $pdf->cell(20, 20, $pdf->Image($logoPath . $store['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                    $pdf->ln();
                    $pdf->setFont ('times','B',14);
                    $pdf->cell(190, 10, strtoupper($store['store_name']), 0, 0, 'C');
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFillColor(35, 35, 35);
                    $pdf->ln();
                    $pdf->setFont ('times','',11);
                    $pdf->cell(190, 5, "Owned & Managed by ASC", 0, 0, 'C');
                    $pdf->ln();
                    $pdf->setFont ('times','',11);
                    $pdf->cell(190, 5, $store['store_address'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->setFont ('times','',11);
                    $pdf->cell(190, 5, "VAT REG TIN: 000-254-327-00003", 0, 0, 'C');

                    $store_name = $store['store_name'];
            }else{
                $tin_status = "no";
            }
        }


        $pdf->ln();
        $pdf->ln();
        $pdf->setFont ('times','B',16);
        $pdf->cell(0, 6, "PAYMENT SLIP", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times','B',9);
        $pdf->cell(25, 5, "PS No.", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $pmt['receipt_no'], "B", 0, 'L');
        $pdf->cell(10, 5, " ", 0, 0, 'L');
        $pdf->cell(25, 5, "SOA No.", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $pmt['soa_no'], "B", 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, "Trade Name", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $tenant['trade_name'], "B", 0, 'L');
        $pdf->cell(10, 5, " ", 0, 0, 'L');
        $pdf->cell(25, 5, "Payment Date", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $pmt['payment_date'], "B", 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, "Corporate Name", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $tenant['corporate_name'], "B", 0, 'L');
        $pdf->cell(10, 5, " ", 0, 0, 'L');
        $pdf->cell(25, 5, "Total Payable", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, number_format($pmt['total_payable'], 2), "B", 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, "TIN", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $tenant['tin'], "B", 0, 'L');
        $pdf->cell(10, 5, " ", 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 5, "Address", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 5, $tenant['address'], "B", 0, 'L');
        $pdf->cell(10, 5, " ", 0, 0, 'L');
        
        $pdf->ln();
        $pdf->ln();

        $pdf->ln();
        $pdf->setFont ('times','B',10);

        if($tin_status === 'with'){
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store['store_name']) , 0, 0, 'R');
        }
        // $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();


        $pdf->ln();


        // =================== Receipt Charges Table ============= //
        $pdf->setFont('times','B',10);
        $pdf->cell(120, 8, "Description", 0, 0, 'L');
        $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
        $pdf->setFont('times','',10);



        foreach ($pmt['payment_docs'] as $key => $doc) {
            $doc = (object) $doc;

            $pdf->ln();
            $pdf->cell(30, 5, ($doc->document_type == 'Preop-Charges' ? 'Preop-' : '').$doc->tag, 0, 0, 'L');
            $pdf->cell(30, 5, " - " . date('M Y', strtotime($doc->posting_date)), 0, 0, 'L');
            $pdf->cell(60, 5, "", 0, 0, 'R');
            $pdf->cell(30, 5, number_format($doc->balance, 2), 0, 0, 'R');
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(30, 5, "Total Payable", 0, 0, 'L');
        $pdf->setFont('times','',10);
        $pdf->cell(30, 5, "", 0, 0, 'L');
        $pdf->cell(60, 5, "", 0, 0, 'R');
        $pdf->cell(30, 5, "P " . number_format($pmt['total_payable'], 2), "T", 0, 'R');
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(30, 5, "Amount Paid", 0, 0, 'L');
        $pdf->setFont('times','',10);
        $pdf->cell(30, 5, "", 0, 0, 'L');
        $pdf->cell(60, 5, "", 0, 0, 'R');
        $pdf->cell(30, 5, "P " . number_format($pmt['tender_amount'], 2), "B", 0, 'R');
        $pdf->ln();

        $totalDue = $pmt['total_payable'] - $pmt['tender_amount'];

        $pdf->setFont('times','B',10);
        $pdf->cell(30, 5, "Amount Still Due", 0, 0, 'L');
        $pdf->setFont('times','',10);
        $pdf->cell(30, 5, "", 0, 0, 'L');
        $pdf->cell(60, 5, "", 0, 0, 'R');
        $pdf->cell(30, 5, "P " . number_format($totalDue, 2), 0, 0, 'R');

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(190, 8, "Payment Scheme: ", 0, 0, 'L');
        $pdf->ln();

        $pdf->setFont('times','',9);
        $pdf->cell(1, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Description", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt['tender_typeCode'] != 2 ? $pmt['tender_typeDesc'] : ucwords($pmt['check_type'])), "B", 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Total Payable", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt['total_payable'], 2), "B", 0, 'L');
        $pdf->ln();

        $pdf->cell(1, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Bank", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, (in_array($pmt['tender_typeCode'], [1,2,3,11]) ?  $pmt['bank_name'] : 'N/A'), "B", 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Amount Paid", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt['tender_amount'], 2), "B", 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Number", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt['tender_typeCode'] == 2  ? $pmt['check_no'] : 'N/A'), "B", 0, 'L');
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Balance", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt['balance'], 2), "B", 0, 'L');  

        $pdf->ln();
        $pdf->cell(1, 4, "     ", 0, 0, 'L');

        $pdf->cell(30, 4, "Check Date", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt['tender_typeCode'] == 2 ? $pmt['check_date'] : 'N/A') , "B", 0, 'L');
        
        $pdf->cell(5, 4, " ", 0, 0, 'L');
        $pdf->cell(30, 4, "Advance", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, "P " . number_format($pmt['advance_amount'], 2), "B", 0, 'L');

        $pdf->ln();
        $pdf->cell(1, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Check Due Date", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, ($pmt['tender_typeCode'] == 2 && $pmt['check_type'] == 'POST DATED CHECK' ? $pmt['check_due_date'] : 'N/A'), "B", 0, 'L');


        $pdf->ln();
        $pdf->cell(1, 4, "     ", 0, 0, 'L');
        $pdf->cell(30, 4, "Payor", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt['payor'], "B", 0, 'L');

        if($tin_status === 'with'){
            $pdf->ln();
            $pdf->cell(1, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payee", 0, 0, 'L');
            $pdf->cell(2, 4, ":", 0, 0, 'L');
            $pdf->cell(60, 4, $pmt['payee'], "B", 0, 'L');
            $pdf->ln();
            $pdf->cell(1, 4, "     ", 0, 0, 'L');
        }

        $pdf->cell(30, 4, "Document #", 0, 0, 'L'); # OR or AR
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $pmt['receipt_no'], "B", 0, 'L');
        // $pdf->ln();
        // $pdf->cell(20, 4, "     ", 0, 0, 'L');
        // $pdf->cell(30, 4, "OR #: ", 0, 0, 'L'); # OR or AR
        // $pdf->cell(60, 4, $pmt['receipt_no'], 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times','',10);
        $pdf->cell(95, 4, "Prepared By: _____________________", 0, 0, 'C');
        $pdf->cell(95, 4, "Checked By:______________________", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(45, 4, "Acknowledgment Certificate", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(38, 4, "", "B", 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, "Date Issued", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(38, 4, "", "B", 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, "Series Range", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(38, 4, "PS0000001 - PS9999999", "B", 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(0, 4, "THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX", 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times','B',10);
        $pdf->cell(0, 4, "THIS IS NOT AN OFFICIAL RECEIPT", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times','',9);
        $pdf->cell(25, 4, "Run Date and Time", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(38, 4, date('Y-m-d h:m:s A'), 0, 0, 'L');

        $file_name = $tenant['tenant_id'] . time() . '.pdf';

        // $pdf->Output('assets/pdf/' . $file_name , 'F');
        $pdf->Output();



        // return $file_name;


    }

   

    function soatesting(){

        $soa = [
        "tenant" => [
        "id"=> "5410",
        "trade_name"=> "POTATO CORNER - UG",
        "corporate_name"=> "WOW BRAND HOLDINGS, INC.",
        "address"=> "15KM East Service Road Corner, Marian Road 2, Barangay San Martin de Porres, Paranaque City 1700",
        "tin"=> "010-314-863-000",
        "contract_no"=> "ICM-C000786",
        "tenant_id"=> "ICM-LT000442",
        "rental_type"=> "Fixed",
        "tenant_type"=> "Private Entities",
        "increment_percentage"=> "0",
        "opening_date"=> "2022-03-05",
        "rent_percentage"=> "0",
        "basic_rental"=> "25000.00",
        "org_basic_rental"=> "25000.00",
        "wht"=> "Added",
        "is_vat"=> "Added",
        "vat_agreement"=> "Exclusive",
        "tenancy_type"=> "Long Term",
        "increment_frequency"=> "Annual",
        "vat_percentage"=> "12.00",
        "wht_percentage"=> "5.00",
        "sales"=> "? string=>? string=>? ",
        "penalty_exempt"=> "1",
        "location_code"=> "ICM-001175",
        "floor_area"=> "4.00",
        "is_incrementable"=> "0"
    ],
    "soa_no"=> "B0024112",
    "total_amount_due"=> 0,
    "billing_period"=> "JUNE 1 - 30, 2023",
    "collection_date"=> "July 06, 2023",
    "date_created"=> "June 26, 2023",
    "tenancy_type"=> "Long Term Tenant",
    "store" => [
        "id"=> "2",
        "store_name"=> "ISLAND CITY MALL",
        "company_code"=> "01.04",
        "store_code"=> "ICM",
        "dept_code"=> "01.04.2",
        "store_address"=> "Dao-Dampas Tagbilaran City Bohol, Philippines 6300",
        "contact_person"=> "Ma. Luz Alcala",
        "contact_no"=> "501-3000/09190699481",
        "email"=> "leasingacctg@alturasbohol.com",
        "logo"=> "1509172865logo-icm.png",
        "managed_by"=> "Alturas Supermarket Corporation",
        "tin"=> "000-254-327-00003"
    ],
    "previous"=> [
        "2020-01" => [
            "has_basic" => false,
            "debit" => 18768.0,
            "credit" => -16613.21,
            "balance" => 2154.79,
            "total" => 2154.79
        ],
        "2021-11" => [
            "has_basic" => false,
            "debit" => 9152.59,
            "credit" => -8309.55,
            "balance" => 843.04,
            "total" => 843.04
        ],
        "2022-05" => [
            "has_basic" => false,
            "debit" => 11410.54,
            "credit" => -10919.47,
            "balance" => 491.07,
            "total" => 491.07
        ]
    ],
    "current"=> [
        "date" => "2023-05",
        "basic" => [
            "IC0048937" => [
                "invoices" => [
                    0 => [
                        "description" => "Basic Rent",
                        "amount" => 25000.0,
                        "unit_price" => "25000.00",
                        "total_unit" => "1.00",
                        "details" => ""
                    ],
                    1 => [
                        "description" => "Vat Output",
                        "amount" => 3000.0,
                        "unit_price" => "0.00",
                        "total_unit" => "12.00",
                        "details" => ""
                    ],
                    2 => [
                        "description" => "Creditable Witholding Tax",
                        "amount" => -1250.0,
                        "unit_price" => "0.00",
                        "total_unit" => "5.00",
                        "details" => ""
                    ]
                ],
                "adj_amount" => "0.00",
                "total" => "26750.00"
            ]
        ],
        "basic_adj_amount" => 0.0,
        "basic_sub_total" => 26750.0,
        "basic_paid_amount" => 0.0,
        "others" => [
            "IC0048938" => [
                "invoices" => [
                    0 => [
                        "description" => "Aircon",
                        "amount" => 1000.0,
                        "prev_reading" => "0.00",
                        "curr_reading" => "0.00",
                        "unit_price" => "1000.00",
                        "total_unit" => "1.00"
                    ],
                    1 => [
                        "description" => "Common Usage Charges",
                        "amount" => 2000.0,
                        "prev_reading" => "0.00",
                        "curr_reading" => "0.00",
                        "unit_price" => "2000.00",
                        "total_unit" => "1.00"
                    ],
                    2 => [
                        "description" => "Electricity",
                        "amount" => 11220.0,
                        "prev_reading" => "90649.00",
                        "curr_reading" => "91669.00",
                        "unit_price" => "11.00",
                        "total_unit" => "1020.00"
                    ],
                    3 => [
                        "description" => "Pest Control",
                        "amount" => 500.0,
                        "prev_reading" => "0.00",
                        "curr_reading" => "0.00",
                        "unit_price" => "500.00",
                        "total_unit" => "1.00"
                    ],
                    4 =>  [
                        "description" => "Expanded Withholding Tax",
                        "amount" => -262.86,
                        "prev_reading" => "0.00",
                        "curr_reading" => "0.00",
                        "unit_price" => "0.00",
                        "total_unit" => 0.0,
                    ]
                ],
                "adj_amount" => "0.00",
                "total" => "14457.14",
            ]
        ],
        "other_adj_amount" => 0.0,
        "other_sub_total" => 14457.14,
        "other_paid_amount" => 0.0,
        "other_total_without_ewt" => 14457.14,
        "other_total_ewt" => 0.0,
        "total" => 41207.14
    ],
    "uri"=> [
        "total_uri_amount" => 0,
        "total_uri_amount_paid" => 0,
        "remaining" => 0,
        "date" => ""
    ],
    "net_amount_due"=> 44696.04
];
$debit = 80538.27;

        $soa = (object) $soa;
        $store = $soa->store;
        $tenant = $soa->tenant;

        $pdf = new FPDF('p','mm', 'A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $pdf->setFont ('times','B',12);
        $logoPath = getcwd() . '/assets/other_img/';
        $inCharge = getcwd() . '/img/karen_longjas_1.png';

        #---------------------------------------------------------------------------------------------------------------------
        $pdf->cell(15, 15, $pdf->Image($logoPath . $store['logo'], $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);

        $pdf->setFont ('times','B',12);
        $pdf->cell(50, 10, strtoupper($store['store_name']), 0, 0, 'L');
        $store_name = $store['store_name'];
        $pdf->SetTextColor(201, 201, 201);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->cell(35, 6, " ", 0, 0, 'L');

        $pdf->setFont ('times','',9);
        $pdf->cell(30, 6, "Statement For:", 1, 0, 'C', TRUE);
        $pdf->cell(30, 6, "Please Pay By:", 1, 0, 'C', TRUE);
        $pdf->cell(30, 6, "Amount Due:", 1, 0, 'C', TRUE);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->ln();
        #---------------------------------------------------------------------------------------------------------------------
        $pdf->setFont ('times','',10);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(20, 5, "Owned & Managed by ASC", 0, 0, 'L');

        $pdf->cell(65, 6, " ", 0, 0, 'L');
        $pdf->setFont ('times','',9);
        $pdf->cell(30, 5, $soa->billing_period, 1, 0, 'C');
        $pdf->cell(30, 5, date('F j, Y',strtotime($soa->collection_date)), 1, 0, 'C');
        $pdf->cell(30, 5, "P " . number_format($soa->net_amount_due, 2), 1, 0, 'C');
        #---------------------------------------------------------------------------------------------------------------------

        $pdf->ln();
        $pdf->setFont ('times','',10);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(20, 1, $store['store_address'], 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont ('times','',10);
        $pdf->cell(15, 0, " ", 0, 0, 'L');
        $pdf->cell(20, 6, "VAT REG TIN: 000-254-327-00003", 0, 0, 'L');
        $pdf->ln();

        $pdf->cell(75, 6, " ", 0, 0, 'L');
        $pdf->SetTextColor(201, 201, 201);

        $pdf->cell(25, 6, " ", 0, 0, 'L');
        $pdf->cell(90, 5, "Questions? Contact", 1, 0, 'C', TRUE);
        $pdf->setFont ('times','',10);
        $pdf->ln();

        $pdf->SetTextColor(201, 201, 201);
        $pdf->setFont ('times','B',10);
        $pdf->cell(75, 10, "LESSEE'S INFORMATION", 1, 0, 'C', TRUE);
        $pdf->cell(25, 6, " ", 0, 0, 'L');
        $pdf->setFont ('times','',10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Multicell(90, 4, $store['contact_person'] . "\n" . "Phone: " . $store['contact_no'] . "\n" . "E-mail: " .$store['email'], 1, 'C');

        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);

        // ============ LESSEE INFORMATION ============ //
        $rental_type = $tenant['rental_type'];
        $pdf->setFont ('times','B',8);
        $pdf->cell(25, 4, "Trade Name", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4,$tenant['trade_name'], "B", 0, 'L');
        $pdf->cell(10, 4, " ", 0, 0, 'L');
        $pdf->cell(25, 4, "SOA No.", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4,$soa->soa_no, "B", 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Corp Name", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $tenant['corporate_name'], "B", 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Date of Transaction", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $soa->date_created, "B", 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "TIN", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $tenant['tin'], "B", 0, 'L');
        $pdf->cell(10, 4, "  ", 0, 0, 'L');
        $pdf->cell(25, 4, "Billing Period", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $soa->billing_period, "B", 0, 'L');

        $pdf->ln();

        $pdf->cell(25, 4, "Address", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(60, 4, $tenant['address'], "B", 0, 'L');

        $pdf->ln();
        $pdf->ln();
        $pdf->setFont ('times','B',10);

        $tenant_id = $tenant['tenant_id'];

        if ($tenant_id == 'ICM-LT000008' || $tenant_id == 'ICM-LT000442' || $tenant_id == 'ICM-LT000492' || $tenant_id == 'ICM-LT000035' || $tenant_id == 'ICM-LT000120')
        {
            $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. BPI - 1201008995"  , 0, 0, 'R');
        }
        elseif ($store_name == 'ALTA CITTA') 
        {
            if($tenant_id == 'ACT-LT000027')
            {
                $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING BPI  ACCT# 9471-0016-75"  , 0, 0, 'R');
            }else
            {
                $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING PNB ACCT# 3059-7000-6462"  , 0, 0, 'R');
            }  
        }
        elseif ($tenant_id == 'ICM-LT000218' || $tenant_id == 'ICM-LT000219') 
        {
            $pdf->cell(0, 5, "Please make payment credited to ASC-ICM LEASING with acct# 3522 1000 63"  , 0, 0, 'R');
        }
        else
        {
            if ($store_name == 'ALTURAS MALL') 
            {
                $pdf->cell(0, 5, "ALTURAS SUPERMARKET CORP. with Acct# 3059-7000-5922"  , 0, 0, 'R');
            }
            elseif($store_name == 'ALTURAS TUBIGON') 
            {
                $pdf->cell(0, 5, "ASC- Home & Fashion With Account # to: 3053-7000-1850"  , 0, 0, 'R');
            }
            elseif($store_name == "PLAZA MARCELA")
            {
                $pdf->cell(0, 5, " MFI - PLAZA MARCELA, LB ACCT #0612-0011-11"  , 0, 0, 'R');
            }
            elseif($store_name == 'ISLAND CITY MALL' || $tenant_id != 'ICM-LT000008' || $tenant_id != 'ICM-LT000442' || $tenant_id != 'ICM-LT000492' || $tenant_id != 'ICM-LT000035' || $tenant_id != 'ICM-LT000120')
            {
                $pdf->cell(0, 5, "Please make all checks payable to ISLAND CITY MALL,Acct # 9471 -0016-59 "  , 0, 0, 'R');
            }else 
            {
                $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store->store_name). "" , 0, 0, 'R');
            }
        }

        $pdf->ln();
        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont ('times','B',16);
        $pdf->cell(0, 6, "Statement of Account", 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->setFont ('times','B',12);
        // $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L', TRUE);
        $pdf->cell(95, 6, "DESCRIPTION", 1, 0, 'C', TRUE);
        $pdf->cell(95, 6, "AMOUNT", 1, 0, 'C', TRUE);
        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);

        $date_created    = date('Y-m-d', strtotime($soa->date_created));
        $collection_date = date('Y-m-d', strtotime($soa->collection_date));

        // ============ IF HAS PRE-OPERATIONAL ============ //
        if(!empty($soa->preop))
        {
            $preop_total = 0;
            $pdf->cell(100, 8, "Additional/Preoparation Charges", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->ln();

            foreach ($soa->preop as $preop)
            {   
                $preop = (object) $preop;

                $preop_desc = "";
                if ($preop->description == 'Security Deposit - Kiosk and Cart' || $preop->description == 'Security Deposit')
                {
                    $preop_desc = "Security Deposit";
                }
                else
                {
                    $preop_desc = $preop->description;
                }

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, $preop_desc, 0, 0, 'L');
                $pdf->cell(40, 4, "P " . number_format($preop->amount, 2), 0, 0, 'R');
                $pdf->ln();
            }

            $pdf->ln();
            $pdf->setFont('times','',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(80, 4, "Total", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(40, 4, "P " . number_format($soa->preop_total, 2), 0, 0, 'R');
            $pdf->ln();
        }

        // ============ IF HAS RETRO RENTAL ============ //
        if (!empty($soa->retro)) // Retro Rental
        {
            $pdf->cell(100, 8, "RETRO RENT", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','',10);
            $pdf->ln();

            $pdf->setFont('times','',10);

            foreach ($soa->retro as $retro)
            {
                $retro = (object) $retro;

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                $pdf->cell(40, 4, "P " . number_format($retro->debit, 2), 0, 0, 'R');
                $pdf->ln();

                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                $pdf->setFont('times','U',10);
                $pdf->cell(40, 4, "P " . number_format($retro->credit, 2), 0, 0, 'R');
                $pdf->ln();
                $pdf->setFont('times','',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($retro->balance, 2), 0, 0, 'R');
                $pdf->ln();
            }
        }

        // ============ PREVIOUS BALANCES ============ //
        if(!empty($soa->previous))
        {

            $pdf->setFont('times','B',10);
            $pdf->cell(100, 8, "PREVIOUS", 0, 0, 'L');
            $pdf->ln();
            $prev_total = 0;

            foreach ($soa->previous as $date => $prev) {
                $prev = (object) $prev;

                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, date("F Y", strtotime($date)), 0, 0, 'L');
                $pdf->setFont('times','',10);

                if(!$prev->has_basic){
                    $pdf->cell(65, 4, "", 0, 0, 'L');
                    $pdf->cell(25, 4, "P " . number_format($prev->balance, 2), 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->ln();
                }else{
                    $pdf->cell(65, 4, "", 0, 0, 'L');
                    $pdf->cell(25, 4, "P " .  number_format($prev->balance, 2), 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->ln();
                }

                if (!empty($prev->no_penalty))
                {
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "No Penalty Charges", 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($prev->no_penalty, 2), 0, 0, 'R');
                    $pdf->ln();
                }

                if(!empty($prev->penalties)){

                    foreach ($prev->penalties as $key => $penalty) {
                        $penalty = (object)$penalty;

                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(30, 4, "Penalty:", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->setFont('times','',10);

                        $pdf->cell(80, 4, number_format($penalty->penaltyble_amount, 2) . " x $penalty->penalty_percentage% (" . date('F Y', strtotime($date)) . ")", 0, 0, 'L');
                        $pdf->cell(40, 4, number_format($penalty->penalty_amount, 2), 0, 0, 'R');
                    }
                }

                $prev_total += $prev->total;
            }

            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times','', '10');
            $pdf->cell(10, 4, "     ", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(95, 4, "Total Previous Amount Payable", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(25, 4, "P " . number_format($prev_total, 2), "T", 0, 'R');
            $pdf->cell(25, 4, "", 0, 0, 'R');
            $pdf->cell(25, 4, "P " . number_format($prev_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->ln();   
        }

        if(!empty($soa->current)){
            $pdf->setFont('times','B',10);
            $pdf->cell(100, 8, "CURRENT(" . date("F Y", strtotime($soa->current['date'])). ")", 0, 0, 'L');

           
           if(!empty($soa->current['basic'])){

                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Rental", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();
                   

                foreach ($soa->current['basic'] as $key => $basic) 
                {
                    $basic = (object)$basic;

                    foreach ($basic->invoices as $key => $inv) 
                    {
                        $inv = (object)$inv;

                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(85, 4, $inv->description . " $inv->details", 0, 0, 'L');
                        $pdf->cell(25, 4, ($key==0 ? 'P ' : '') . number_format($inv->amount, 2), 0, 0, 'R');
                        $pdf->cell(25, 4, "", 0, 0, 'R');
                        $pdf->cell(25, 4, "", 0, 0, 'R');
                        $pdf->ln();
                    } 

                    if(abs($basic->adj_amount) > 0)
                    {
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(10, 4, "     ", 0, 0, 'L');
                        $pdf->cell(30, 4, "Adjustment/s : " , 0, 0, 'L');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        foreach ($basic->adj_details as $adj) {
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(85, 4, ($adj->tag == 'Rent Income' ? 'Basic Rent' : $adj->tag), 0, 0, 'L');
                            $pdf->cell(25, 4, ($key==0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->cell(25, 4, "", 0, 0, 'R');
                            $pdf->cell(25, 4, "", 0, 0, 'R');
                            $pdf->ln();
                        }
                    }

                    $pdf->ln();
                }

                if($soa->current['basic_adj_amount'] > 0){
                    $pdf->cell(10, 4, "     ", 0, 0, 'L');
                    $pdf->cell(95, 4, "Adjustments : " , 0, 0, 'L');
                    $pdf->cell(25, 4, 'P'. number_format($soa->current['basic_adj_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->ln();
                }

                if(abs($soa->current['basic_paid_amount']) > 0)
                {
                    $pdf->setFont('times','B',10);
                    $pdf->cell(10, 4, "     ", 0, 0, 'L');
                    $pdf->cell(95, 4, "Payment Received : " , 0, 0, 'L');
                    $pdf->cell(25, 4, 'P'. number_format($soa->current['basic_paid_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');

                    $basic_paid_amount = -1 * $soa->current['basic_paid_amount'];
                }
                    // ========== ORIGINAL SUB TOTAL ========== //
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(95, 4, "Sub Total", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->setFont('times','B',10);
                $pdf->cell(25, 4, "P " . number_format($soa->current['basic_sub_total'], 2), "T", 0, 'R');
                $pdf->cell(25, 4, "P " . number_format($soa->current['basic_sub_total'], 2), 0, 0, 'R');
                $pdf->cell(25, 4, "", 0, 0, 'R'); 
                $pdf->ln();

            }

            if(!empty($soa->current['others'])){

                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Add:Other Charges", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();
                    

                foreach ($soa->current['others'] as $key => $other){
                    $other = (object)$other;

                    foreach ($other->invoices as $key => $inv){
                        $inv = (object) $inv;

                        switch ($inv->description) {
                            case 'Electricity':
                            case 'Water':
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(85, 4, $inv->description, 0, 0, 'L');
                                $pdf->ln();
                                $pdf->setFont('times','',8);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "Present", 0, 0, 'L');
                                $pdf->cell(20, 4, "Previous", 0, 0, 'L');
                                $pdf->cell(20, 4, "Consumed", 0, 0, 'L');
                                $pdf->ln();
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->curr_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->prev_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->total_unit, 2), 0, 0, 'L');
                                $pdf->cell(15, 4, " ", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->cell(25, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->cell(25, 4, "", 0, 0, 'R');
                                $pdf->cell(25, 4, "", 0, 0, 'R');
                                $pdf->ln();
                                break;
                            
                            default:
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(85, 4, $inv->description, 0, 0, 'L');
                                $pdf->cell(25, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->cell(25, 4, "", 0, 0, 'R');
                                $pdf->cell(25, 4, "", 0, 0, 'R');
                                $pdf->ln();
                                break;
                        }
                    }



                    if(abs($other->adj_amount) > 0){
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(10, 4, "     ", 0, 0, 'L');
                        $pdf->cell(95, 4, "Adjustment/s : " , 0, 0, 'L');
                        //$pdf->cell(40, 4, number_format($other->adj_amount, 2), 0, 0, 'R');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        foreach ($other->adj_details as $adj) {
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(85, 4, $adj->tag, 0, 0, 'L');
                            $pdf->cell(25, 4, ($key==0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->cell(25, 4, "", 0, 0, 'R');
                            $pdf->cell(25, 4, "", 0, 0, 'R');
                            $pdf->ln();
                        }
                    }


                    $pdf->ln();
                }

                if ($tenant_id == 'ICM-LT000114'){
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Total W/out Withholding Taxes", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($soa->current['other_total_without_ewt'], 2), 0, 0, 'R'); 
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Withholding Taxes", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($soa->current['other_total_ewt'], 2), 0, 0, 'R'); 
                    $pdf->ln();
                    $pdf->ln();
                }

                if(abs($soa->current['other_paid_amount']) > 0){
                    $pdf->setFont('times','B',10);
                    $pdf->cell(10, 4, "     ", 0, 0, 'L');
                    $pdf->cell(95, 4, "Payment Received : " , 0, 0, 'L');
                    $pdf->cell(25, 4, 'P'. number_format($soa->current['other_paid_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                }
                     

                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(95, 4, "Sub Total", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->setFont('times','B',10);
                $pdf->cell(25, 4, "P " . number_format($soa->current['other_sub_total'], 2), "T", 0, 'R');
                $pdf->cell(25, 4, "P " . number_format($soa->current['other_sub_total'], 2), 0, 0, 'R');
                $pdf->cell(25, 4, "", 0, 0, 'R');   
            }

            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(10, 4, "     ", 0, 0, 'L');
            $pdf->cell(95, 4, "Total Current Amount Payable", 0, 0, 'L');
            $pdf->setFont('times','B',10);
            $pdf->cell(25, 4, "", 0, 0, 'R');
            $pdf->cell(25, 4, "P " . number_format($soa->current['total'], 2), "T", 0, 'R');
            $pdf->cell(25, 4, "P " . number_format($soa->current['total'], 2), 0, 0, 'R');


            $pdf->ln();
            $pdf->ln();
        }

        $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(10, 4, "     ", 0, 0, 'L');
                    $pdf->cell(95, 4, "Advance Payment"  . " (2023-07-31)", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(25, 4, "P 35,900.00", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->cell(25, 4, "", 0, 0, 'R');
                    $pdf->ln();


        if (!empty($soa->uri)) 
        {
            $uri = (object)$soa->uri;

            if($uri->total_uri_amount > 0)
            {
                if ($basic_paid_amount > 0) 
                {
                    $advance_total = $basic_paid_amount + $uri->total_uri_amount;

                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Advance Payment"  . " (" . $uri->date . ")", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($advance_total, 2), 0, 0, 'R');
                    $pdf->ln();
                }
                else
                {
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Advance Payment"  . " (" . $uri->date . ")", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($uri->total_uri_amount, 2), 0, 0, 'R');
                    $pdf->ln();
                }
            }
        }else{
            $uri = [];
        }

        $pdf->setFont('times','B',10);
        $pdf->cell(10, 4, "     ", 0, 0, 'L');
        $pdf->cell(95, 4, "Total Amount Payable", 0, 0, 'L');
        $pdf->setFont('times','B',10);
        $pdf->cell(25, 4, "", 0, 0, 'R');
        $pdf->cell(25, 4, "", 0, 0, 'R');
        $pdf->cell(25, 4, "P " . number_format($soa->net_amount_due, 2), "T", 0, 'R');


        $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(10, 4, "     ", 0, 0, 'L');
                $pdf->cell(95, 4, "Remaining Advance Payment", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(25, 4, "", 0, 0, 'R');
                $pdf->cell(25, 4, "", 0, 0, 'R');
                $pdf->cell(25, 4, "P " . number_format($soa->net_amount_due, 2), "T", 0, 'R');
        

        if ($uri == []) {
        
        }else{
            if($uri->remaining > 0){
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Remaining Advance Payment", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($uri->remaining, 2), 0, 0, 'R');
            }
        }


        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times','',10);
        $pdf->cell(75, 5, "Certified: ", 0, 0, 'R');
        $pdf->cell(75, 5, $pdf->Image($inCharge, 80, $pdf->GetY(), 60, 13, 'PNG'), 0, 0, 'C');

        $pdf->ln(10);
        $pdf->setFont('times','',8);
        $pdf->cell(38, 4, "     ", 0, 0, 'L');
        $pdf->cell(38, 4, "     ", 0, 0, 'L');
        $pdf->cell(38, 4, "Corporate Leasing Manager", 0, 0, 'L');
        $pdf->cell(38, 4, "     ", 0, 0, 'L');
        $pdf->cell(38, 4, "     ", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times','B',10);
        $pdf->cell(45, 4, "Run Date and Time", 0, 0, 'L');
        $pdf->cell(2, 4, ":", 0, 0, 'L');
        $pdf->cell(38, 4, date('Y-m-d h:m:s A'), "B", 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
        $pdf->setFont('times','B',12);
        $pdf->ln();
        $pdf->cell(190, 1,"         ", 1, 0, 'L', TRUE);
        $pdf->ln();
        $pdf->setFont('times','B',8);
        $pdf->cell(0, 4, "Note: Presentation of this statement is sufficient notice that the account is due. Interest of 3% will be charged for all past due accounts.", 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times','B',8);
        $pdf->cell(7, 4, "", 0, 0, 'L');
        $pdf->cell(0, 4, "THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX", 0, 0, 'L');
   


        $file_name =  $tenant['tenant_id'] . time().'.pdf';

        $this->app_model->insert('soa_file', [
            'tenant_id'         =>  $tenant['tenant_id'],
            'file_name'         =>  $file_name,
            'soa_no'            =>  $soa->soa_no,
            'billing_period'    =>  $soa->billing_period,
            'amount_payable'    =>  $soa->net_amount_due,
            'posting_date'      =>  $date_created,
            'collection_date'   =>  $collection_date,
            'transaction_date'  =>  getCurrentDate()
        ]);

        //$response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
        //header('Content-Type: application/facilityrental_pdf');

        // $pdf->Output('assets/pdf/' . $file_name , 'F');
        $pdf->Output();

        // return $file_name;
    }

    public function testingReprint(){
        $file        = $this->uri->segment('3');
        $soano       = $this->uri->segment('4');
        $count_no    = $this->app_model->getReprintSoaLogNo($soano);
        $count       = 1;

        if(!empty($count_no)){
            $count       = $count_no->reprint_no;
            $count++;
        }

        $existingPdf = getcwd() . '/assets/pdf/' . $file;
        $newPdf      = getcwd() . '/assets/pdf/' . $count . "_" . $file;

        // Create a new instance of FPDI
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(10, 3);
        $pdf->Cell(35, 5, 'Re-printed copy : ' . $count, 0, 0, 'L');
        $pdf->setSourceFile($existingPdf);
        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId, 0, 0);

        $this->db->trans_start();

        if(!empty($count_no)){
            $this->db->where('id', $count_no->id);
            $this->db->update('soa_reprint_log', [
                'reprint_no' => $count,
                'reprint_date' => date('Y-m-d'),
                'reprint_time' => date("h:i:sa"),
                'reprinted_by' => $this->session->userdata('id')
            ]);
        }else{
            $this->db->insert('soa_reprint_log', [
                'soa_no' => $soano,
                'reprint_no' => $count,
                'reprint_date' => date('Y-m-d'),
                'reprint_time' => date("h:i:sa"),
                'reprinted_by' => $this->session->userdata('id')
            ]);
        }

        $this->db->trans_complete();

        // $pdf->Output($newPdf, 'D');
        $pdf->Output('D', $file);
    }

    public function testingFile(){
        $filename = "Basic_Rent_2023-07.pjn";
        $targetPath = getcwd() . '/assets/for_cas/' . $filename;

        if(file_exists($targetPath)){
            $pjnContent = file_get_contents($targetPath);
            $line       = explode("\n", $pjnContent);
            $totalLine  = count($line);
            $rows       = [];
            $line_no    = 10000;

            for ($i=0; $i < $totalLine; $i++) { 
                if($line[$i] != ""){
                    $rows[] = array($line[$i]);
                    dump(str_replace("\r", "", $line[$i]));
                }
            }

            for ($j=0; $j <= count($rows); $j++) { 
                if($j == count($rows)){
                    $index      = $j - 1;
                    $rowLine    = array($rows[$index]);
                    $stringLine = arrayToString($rowLine);
                    $refined    = explode("<|>", $stringLine);
                    $line_no    = $refined[1];
                }
            }

            dump($line_no);

            // for ($i=0; $i < 5; $i++) {
            //     $rows[] = array("GENERAL<|>50000<|>Customer<|>ICM-LT000005<|>07/13/2023<|>Payment<|>PR071323<|>BOS COFFEE<|>-1441.75<|>01.04<|>01.04.2<|>GENJNL<|>POS SALES<|><|><|><|>PS0000005<|><|>");

            // }
            // // $rows[] = array("GENERAL<|>50000<|>Customer<|>ICM-LT000005<|>07/13/2023<|>Payment<|>PR071323<|>BOS COFFEE<|>-1441.75<|>01.04<|>01.04.2<|>GENJNL<|>POS SALES<|><|><|><|>PS0000005<|><|>");
            // $file_data   = arrayToString($rows);

            // file_put_contents($targetPath, $file_data);

            // dump($rows);
        }
    }

    // public function uploadSubsidiaryLedger(){
    //     $inhouse  = $this->load->database('inhouse', true);
    //     $payments = $this->db->query("SELECT DISTINCT(doc_no) FROM subsidiary_ledger WHERE document_type = 'Payment'")->RESULT();

    //     $doc_nos = [];

    //     foreach ($payments as $key => $payment) {
    //         $doc_nos[] = str_replace(' ', '', $payment->doc_no);
    //     }

    //     $docs = $doc_nos;
    //     $docs = implode("','", $doc_nos);

    //     $p  = $inhouse->query("SELECT * FROM payment WHERE doc_no IN ('{$docs}')")->RESULT_ARRAY();
    //     $ps = $inhouse->query("SELECT * FROM payment_scheme WHERE receipt_no IN ('{$docs}')")->RESULT_ARRAY(); 

    //     $this->db->trans_start();
    //     foreach($p as $key => $value){
    //         $this->db->insert('payment', $value);
    //     }

    //     foreach ($ps as $key => $value) {
    //         $this->db->insert('payment_scheme', $value);
    //     }
    //     $this->db->trans_complete();

    //     if($this->db->trans_status() === FALSE){
    //         dump("wa na save");
    //     }else{
    //         dump("na save na");
    //     }

    //     exit();
        
    //     ###############################################
    //     $agc_pms = $inhouse->query("SELECT * FROM subsidiary_ledger WHERE posting_date LIKE '%2023-08%' AND tenant_id NOT LIKE '%PM%'")->RESULT_ARRAY();
    //     // $agc_pms = $inhouse->query("SELECT * FROM tmp_preoperationcharges WHERE posting_date LIKE '%2023-07%' AND tenant_id NOT LIKE '%PM%'")->RESULT_ARRAY();

    //     $this->db->trans_start();
    //     foreach($agc_pms as $key => $value){
    //         $this->db->insert('subsidiary_ledger', $value);
    //         // $this->db->insert('tmp_preoperationcharges', $value);
    //     }
    //     $this->db->trans_complete();

    //     if($this->db->trans_status() === FALSE){
    //         dump("wa na save");
    //     }else{
    //         dump("na save na");
    //     }
    // }

    public function generateTextFiles(){

        // $query = $this->db->query("SELECT distinct(doc_no), ref_no, cas_doc_no FROM subsidiary_ledger WHERE document_type = 'Invoice'")->RESULT_ARRAY();

        // $this->db->trans_start();
        // foreach ($query as $key => $value) {
        //     $this->db->where('ref_no', $value['ref_no']);
        //     $this->db->where('document_type', 'Payment');
        //     $this->db->update('subsidiary_ledger', ['cas_doc_no' => $value['cas_doc_no']]);
        // }
        // $this->db->trans_complete();

        // if($this->db->trans_status() === FALSE){
        //     dump("ERROR");
        // }else{
        //     dump("SUCCESS");
        // }

        // exit();

        # GETTING DATA FROM LIVE DATABASE TO CAS DATABASE ==============================================================================
            // $inhouse  = $this->load->database('inhouse', true);
            // $sl07 = $inhouse->query("SELECT distinct(ref_no) FROM `agc-pms`.subsidiary_ledger WHERE transaction_date LIKE '%2023-07%' AND ref_no <> 'DELETED'")->RESULT_ARRAY();
            // $sl08 = $inhouse->query("SELECT distinct(ref_no) FROM `agc-pms`.subsidiary_ledger WHERE transaction_date LIKE '%2023-08%' AND ref_no <> 'DELETED'")->RESULT_ARRAY();
            // $sl07Data = [];
            // $sl08Data = [];

            // $this->db->trans_start();
            // foreach ($sl07 as $key => $value) {
            //    $sl07Data = $inhouse->query("SELECT * FROM `agc-pms`.subsidiary_ledger WHERE ref_no = '{$value['ref_no']}'")->RESULT_ARRAY();

            //    $duplicate = $this->db->query("SELECT * FROM `agc-pms-cas`.subsidiary_ledger WHERE ref_no = '{$value['ref_no']}'")->RESULT_ARRAY();

            //    if(empty($duplicate)){
            //         foreach($sl07Data as $data){
            //             $this->db->insert("subsidiary_ledger", $data);
            //         }
            //    }
            // }

            // foreach ($sl08 as $key => $value) {
            //     $sl08Data = $inhouse->query("SELECT * FROM `agc-pms`.subsidiary_ledger WHERE ref_no = '{$value['ref_no']}'")->RESULT_ARRAY();
    
            //     $duplicate = $this->db->query("SELECT * FROM `agc-pms-cas`.subsidiary_ledger WHERE ref_no = '{$value['ref_no']}'")->RESULT_ARRAY();
    
            //     if(empty($duplicate)){
            //         foreach ($sl08Data as $data) {
            //             $this->db->insert("subsidiary_ledger", $data);
            //         }
            //     }
            //  }
            //  $this->db->trans_complete();

            //  if($this->db->trans_status() === FALSE){
            //     dump($this->db->_error_message());
            //  }else{
            //     dump("SUCCESSFULLY SAVED");
            //  }
            # GETTING DATA FROM LIVE DATABASE TO CAS DATABASE ==============================================================================
            # PREVIEWING ALL THE ref_no IN THE CAS DATABASE ================================================================================
            // $invoices = $this->db->query("SELECT distinct(ref_no) FROM subsidiary_ledger WHERE document_type = 'Payment' and tag = '' OR tag IS NULL")->RESULT();

            // foreach ($invoices as $key => $invoice) {
            //     $inv = $this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no = '{$invoice->ref_no}' AND document_type = 'Invoice'")->RESULT_ARRAY();
                
            //     if(empty($inv)){
            //         $doc_nos[] = $invoice->ref_no;
            //     }
            // }

            // $docs = $doc_nos;

            // $docs = implode("','", $doc_nos);

            // // dump($this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no IN ('{$docs}')")->RESULT_ARRAY());
            // echo "SELECT * FROM subsidiary_ledger WHERE ref_no IN ('{$docs}')";

            // echo "<br>";
            // echo count($doc_nos);
            # PREVIEWING ALL THE ref_no IN THE CAS DATABASE ================================================================================
            
            // $query = $this->db->query("SELECT distinct(ref_no) FROM subsidiary_ledger WHERE gl_accountID IN ('8', '9')")->RESULT_ARRAY();

            // $this->db->trans_start();
            // foreach ($query as $key => $value) {
            //     $this->db->where('ref_no', $value['ref_no']);
            //     $this->db->update('subsidiary_ledger', ['tag' => 'Preop']);
            // }
            // $this->db->trans_complete();

            //  if($this->db->trans_status() === FALSE){
            //     dump($this->db->_error_message());
            //  }else{
            //     dump("SUCCESSFULLY SAVED");
            //  }

        # GENERATING TEXT FILES =========================================================================================================
        // $basic    = $this->db->query("SELECT distinct(doc_no), posting_date, tenant_id FROM `agc-pms-cas`.subsidiary_ledger WHERE tenant_id NOT LIKE '%PM%' AND gl_accountID = '4' AND document_type = 'Invoice'")->RESULT_ARRAY();
        $others   = $this->db->query("SELECT distinct(doc_no), posting_date, tenant_id FROM `agc-pms-cas`.subsidiary_ledger WHERE tenant_id NOT LIKE '%PM%' AND gl_accountID = '22' AND document_type = 'Invoice'")->RESULT_ARRAY();
        $payments = $this->db->query("SELECT distinct(doc_no), posting_date, tenant_id FROM `agc-pms-cas`.subsidiary_ledger WHERE tenant_id NOT LIKE '%PM%' AND gl_accountID NOT IN ('29', '10') AND document_type = 'Payment'")->RESULT_ARRAY();

        // $this->db->trans_start();
        // foreach ($basic as $key => $value) {
        //     $sCode     = explode("-", $value['tenant_id']);
        //     $storeData = $this->db->query("SELECT * FROM stores WHERE store_code = '{$sCode[0]}'")->ROW();
        //     $this->generate_RRreports1($value['doc_no'], $sCode[0], $value['posting_date'], $storeData->company_code, $storeData->dept_code, $value['tenant_id']);
        // }

        // foreach ($others as $key => $value) {
        //     $sCode     = explode("-", $value['tenant_id']);
        //     $storeData = $this->db->query("SELECT * FROM stores WHERE store_code = '{$sCode[0]}'")->ROW();
        //     $this->generate_ARreports1($value['doc_no'], $sCode[0], $value['posting_date'], $storeData->company_code, $storeData->dept_code, $value['tenant_id']);
        // }

        foreach ($payments as $key => $value) {
            $sCode     = explode("-", $value['tenant_id']);
            $storeData = $this->db->query("SELECT * FROM stores WHERE store_code = '{$sCode[0]}'")->ROW();
            $this->generate_paymentCollection1($value['doc_no'], $sCode[0], $value['posting_date'], $value['tenant_id']);
        }
        
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            dump("Status : Error");
        }else{
            dump("Status : Success");
        }
    }

    # FOR EXTRACTION CAS TEXT FILE
    public function generate_RRreports1($docno, $store, $month, $company_code, $dept_code, $tenant_id){
        $month        = date('F Y', strtotime($month));
        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
        $doc_no       = 'BLS'.date('mdy',strtotime(date('Y-m-t', strtotime($month))));
        $report_data  = $this->app_model->generate_RRreports_test($month, $docno, $store);
        $data         = $report_data['data'];
        $doc_nos      = $report_data['doc_nos'];
        $file_data    = "";
        $externalDocNo = "";
        
        // dump($docno);
        if(!empty($data)){
            $rows    = [];
            $line_no = 10000;
                    
            foreach ($data as $result){
                $pDate = date('m/d/Y', strtotime($result['posting_date']));

                if ($result['gl_code'] == '10.10.01.03.16') {
                    $rows[] = array("GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['trade_name']}-Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else if($result['gl_code'] == '10.10.01.06.05'){
                    $rows[] = array("GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else{
                    $amount = str_replace('-','', $result['amount']);
                    $rows[] = array("GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['gl_account']}<|>($amount)<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }

                $line_no += 10000;

                $externalDocNo = "{$doc_no}-{$result['doc_no']}";
            }

            $exp_batch_no  = $this->app_model->generate_ExportNo(true);
            $filter_date   = date('Y-m', strtotime($posting_date));
            # $file_name    = "Basic_Rent_$filter_date" . ".pjn";
            $file_name     = "Basic Rent_$tenant_id". "_" ."$filter_date - " .$exp_batch_no. ".pjn";
            // $targetPath    = getcwd() . '/assets/for_cas/basic_rent/' . $file_name;
            $targetPath    = '\\\172.16.170.10/pos-sales/LEASING/' . $file_name;
            $file_data     = arrayToString($rows);
            #Tag Export
            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $externalDocNo);
            }
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Basic Rent', $file_name);

            file_put_contents($targetPath, $file_data);
        }
    }

    #YAWA NING CAS YWAW NING BIR YAWA NI TANAN - IF EVER MAHIMO KAG PROGRAMMER ANING LEASING, AYAW NA PADAYON, LABAD SA ULO RAY MAKUHA NIMO
    public function generate_ARreports1($invoiceNo, $store, $month, $company_code, $dept_code, $tenantid){
        $month         = date('F Y', strtotime($month));
        $posting_date  = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
        $date          = new DateTime();
        $timeStamp     = $date->getTimestamp();
        $docno         = 'OLS' .  date('mdy', strtotime(date('Y-m-t', strtotime($month))));
        $report_data   = $this->app_model->generate_ARreports_test($month, $invoiceNo, $store);
        $data          = $report_data['data'];
        $doc_nos       = $report_data['doc_nos'];
        $file_data     = '';
        $externalDocNo = "";

        if(!empty($data)){
            $line_no = 10000;
            $rows    = [];
            
            foreach ($data as $result){
                $pDate    = date('F Y', strtotime($result['posting_date']));
                $tenantID = str_replace('-', '-OC-', $result['tenant_id']);

                if ($result['gl_code'] == '10.10.01.03.03' || $result['gl_code'] == '10.10.01.03.04') {
                    $rows[] = array("GENERAL<|>{$line_no}<|>Customer<|>{$tenantID}<|>{$posting_date}<|><|>{$docno}-{$result['doc_no']}<|>{$result['trade_name']}-Other Charges<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else if($result['gl_code'] == '10.10.01.06.05'){
                    $rows[] = array("GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$docno}-{$result['doc_no']}<|>{$result['gl_account']}<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else{
                    $amount = str_replace('-','', $result['amount']);
                    $rows[] = array("GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$docno}-{$result['doc_no']}<|>{$result['gl_account']}<|>({$amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }

                $line_no += 10000;

                $externalDocNo = "{$docno}-{$result['doc_no']}";
            }

            $exp_batch_no  = $this->app_model->generate_ExportNo(true);
            $filter_date   = date('Y-m', strtotime($posting_date));
            $file_name     = "Other_Charges_$tenantid" . "_" . "$filter_date" . " - $exp_batch_no.pjn";
            // $targetPath   = getcwd() . '/assets/for_cas/other/' . $file_name;
            $targetPath    = '\\\172.16.170.10/pos-sales/LEASING/' . $file_name;
            $file_data     = arrayToString($rows);
            #Tag Export
            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $externalDocNo);
            }
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Other Charges', $file_name);

            file_put_contents($targetPath, $file_data);
        }
    }

    public function generate_paymentCollection1($docno, $store, $post_date, $tenantid){
        // $store      = $this->session->userdata('store_code');
        // $post_date  = $date = date('M Y');
        $post_date  = date('Y-m', strtotime($post_date));
        $entries    = $this->db->query("
            SELECT 
                tbl.*,
                SUM(tbl.db) AS debit,
                SUM(tbl.cr) AS credit,
                sl2.partnerID,
                SUM(tbl.db + tbl.cr) AS amount
            FROM
                (SELECT 
                    g.id,
                    g.tenant_id,
                    g.posting_date,
                    g.due_date,
                    g.doc_no,
                    g.cas_doc_no,
                    g.ref_no,
                    g.document_type,
                    p.trade_name,
                    g.gl_accountID,
                    g.bank_code,
                    g.bank_name,
                    g.company_code,
                    g.department_code,
                    a.gl_account,
                    g.tag,
                    SUM(IFNULL(g.debit, 0)) db,
                    SUM(IFNULL(g.credit, 0)) cr,
                    SUM(IFNULL(g.debit, 0) + IFNULL(g.credit, 0)) amt
                FROM
                    subsidiary_ledger AS g
                LEFT JOIN (
                    SELECT 
                        DISTINCT(tnt.prospect_id) AS prospect_id,
                        tnt.tenant_id,
                        tnt.store_code
                    FROM
                        tenants tnt) t 
                ON t.tenant_id = g.tenant_id
                LEFT JOIN 
                    prospect p 
                ON p.id = t.prospect_id
                LEFT JOIN 
                    gl_accounts a 
                ON a.id = g.gl_accountID
                WHERE DATE(g.posting_date) LIKE '%$post_date%'
                AND (g.document_type = 'Payment')
                AND (g.tenant_id <> 'DELETED'
                AND g.ref_no <> 'DELETED'
                AND g.doc_no = '$docno')
                AND g.tenant_id <> 'ICM-LT000064'
                AND (g.export_batch_code IS NULL
                OR g.export_batch_code = '')
                GROUP BY 
                    g.doc_no, g.ref_no, g.gl_accountID, g.tenant_id, g.posting_date/*, g.document_type*/
                HAVING 
                    amt <> 0
                ORDER BY 
                    g.document_type , g.doc_no) 
                AS tbl
            LEFT JOIN
                (SELECT 
                        s.*, s.gl_accountID AS partnerID
                    FROM
                        subsidiary_ledger s
                    WHERE
                        s.debit IS NULL 
                    AND s.credit IS NOT NULL
                    AND (s.document_type = 'Payment')
                    GROUP BY 
                        s.doc_no, s.ref_no , s.credit) sl2 
            ON tbl.doc_no = sl2.doc_no
            AND tbl.ref_no = sl2.ref_no
            AND tbl.db <> 0
            AND tbl.db = ABS(sl2.credit)
            AND tbl.document_type = sl2.document_type
            GROUP BY 
                tbl.doc_no, tbl.gl_accountID, tbl.posting_date, tbl.document_type, sl2.partnerID
            ORDER BY 
                tbl.posting_date, tbl.doc_no, tbl.id ,credit DESC , debit DESC , tbl.trade_name , tbl.ref_no")->result_object();

        // dump($entries);
        if(!empty($entries)){
            $line_no     = 10000;
            $data_csv    = [];
            $doc_nos     = [];
            $batchName   = "";
            $externalDoc = "";

            foreach ($entries as $key => $entry) {
                if($entry->amount == 0 ) continue;

                $pDate       = date('m/d/Y', strtotime($entry->posting_date)); 
                $doc_no      = 'PR' .  date('mdy', strtotime($entry->posting_date)) . '-' . $entry->doc_no;
                $externalDoc = 'PR' .  date('mdy', strtotime($entry->posting_date)) . '-' . $entry->doc_no;

                if($entry->tag == 'Preop'){
                    $batchName = "ACK_RCPT";
                }else{
                    $batchName = "OFCL_RCPT";
                }

                $tenderType = $this->db->query("SELECT * FROM payment_scheme WHERE receipt_no = '".$entry->doc_no."'")->ROW();

                if($entry->gl_accountID == 4 || $entry->gl_accountID == 22 || $entry->gl_accountID == 29){
                    $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';
                    $amount     = str_replace('-','', $entry->amount);
                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Customer<|>{$entry->tenant_id}<|>{$pDate}<|>{$docType}<|>{$doc_no}<|>{$entry->trade_name}-{$entry->doc_no}<|>({$amount})<|>{$entry->company_code}<|>{$entry->department_code}<|>CASHRECJNL<|>{$batchName}<|><|><|><|><|><|>");
                } else if($entry->gl_accountID == 9 || $entry->gl_accountID == 8 || $entry->gl_accountID == 7){
                    $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';
                    $amount     = str_replace('-','', $entry->amount);
                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Customer<|>{$entry->tenant_id}<|>{$pDate}<|>{$docType}<|>{$doc_no}<|>{$entry->trade_name}-{$entry->doc_no}<|>({$amount})<|>{$entry->company_code}<|>{$entry->department_code}<|>CASHRECJNL<|>{$batchName}<|><|><|><|><|><|>");
                }else{
                    $gl = $this->db->select('*')
                                   ->from('gl_accounts')
                                   ->where(['id'=>$entry->gl_accountID])
                                   ->limit(1)
                                   ->get()
                                   ->row();

                    if(!empty($tenderType)){
                        $tenderType = $tenderType->tender_typeDesc;
                    }else{
                        if($entry->gl_accountID == '3'){
                            $tenderType = "Cash";
                        }else{
                            $tenderType = $gl->gl_account;
                        }
                    }
        
                    $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';
                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Bank Account<|>{$entry->bank_code}<|>$pDate<|>$docType<|>$doc_no<|>{$entry->bank_name}-{$entry->doc_no}<|>$entry->amount<|>$entry->company_code<|>$entry->department_code<|>CASHRECJNL<|>$batchName<|><|><|><|>{$tenderType}<|><|>");
                }

                $line_no += 10000;

                if(!in_array($entry->doc_no, $doc_nos)){
                    $doc_nos[] = $entry->doc_no;
                }
            }

            foreach ($entries as $key => $entry){
                if($entry->tag != 'Preop'){
                    if($entry->gl_accountID == 4 || $entry->gl_accountID == 22 || $entry->gl_accountID == 29){
                       
                    } else if($entry->gl_accountID == 9 || $entry->gl_accountID == 8 || $entry->gl_accountID == 7){
                    
                    }else{
                        $invoice    = $this->app_model->getInvoice($entry->cas_doc_no);
                        $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>");
                    
                        $line_no += 10000;
                    }
                }
            }

            $exp_batch_no = $this->app_model->generate_ExportNo(true);

            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $externalDoc);
            }

            // $file_name  = "Other_Charges_$tenantid" . "_" . "$filter_date" . " - $exp_batch_no.pjn";
            $file_name     = "Payment_Collection_$tenantid" . "_$post_date" . " - $exp_batch_no.crj";
            // $targetPath = getcwd() . '/assets/for_cas/payment/' . $file_name;
            $targetPath    = '\\\172.16.170.10/pos-sales/LEASING/' . $file_name;

            $data = arrayToString($data_csv);
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);
            file_put_contents($targetPath, $data);
        }
    }

    # ADJUSTMENT

    public function generate_RRreportsAdjustment($docno, $store, $month, $company_code, $dept_code, $tenant_id){
        $month        = date('F Y', strtotime($month));
        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
        $report_data  = $this->app_model->generate_RRreports_test($month, $docno, $store);
        $data         = $report_data['data'];
        $doc_nos      = $report_data['doc_nos'];
        $file_data    = "";

        if(!empty($data)){
            $rows    = [];
            $line_no = 10000;
                    
            foreach ($data as $result){
                $pDate = date('m/d/Y', strtotime($result['posting_date']));

                if ($result['gl_code'] == '10.10.01.03.16') {
                    $rows[] = array("GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['trade_name']}-Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else if($result['gl_code'] == '10.10.01.06.05'){
                    $rows[] = array("GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else{
                    $amount = str_replace('-','', $result['amount']);
                    $rows[] = array("GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['gl_account']}<|>($amount)<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }

                $line_no += 10000;
            }

            $exp_batch_no  = $this->app_model->generate_ExportNo(true);
            $filter_date   = date('Y-m', strtotime($posting_date));
            # $file_name    = "Basic_Rent_$filter_date" . ".pjn";
            $file_name     = "Basic Rent_Adjustment_$tenant_id". "_" ."$filter_date - " .$exp_batch_no. ".pjn";
            $targetPath    = getcwd() . '/assets/for_cas/basic_rent' . $file_name;
            $file_data     = arrayToString($rows);
            $externalDocNo = $doc_no;
            #Tag Export
            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $result[0]['cas_doc_no']);
            }
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Basic Rent', $file_name);

            file_put_contents($targetPath, $file_data);
        }
    }

    public function generate_ARreportsAdjustment($docno, $store, $month, $company_code, $dept_code, $tenantid){
        $month        = $date  = date('M Y');
        $month        = date('F Y', strtotime($month));
        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
        $date         = new DateTime();
        $timeStamp    = $date->getTimestamp();
        $report_data   = $this->app_model->generate_ARreports_test($month, $docno, $store);
        $data         = $report_data['data'];
        $doc_nos      = $report_data['doc_nos'];
        $file_data    = '';

        
        if(!empty($data)){
            $line_no = 10000;
            $rows    = [];
            
            foreach ($data as $result){
                $pDate    = date('F Y', strtotime($result['posting_date']));
                $tenantID = str_replace('-', '-OC-', $result['tenant_id']);

                if ($result['gl_code'] == '10.10.01.03.03' || $result['gl_code'] == '10.10.01.03.04') {
                    $rows[] = array("GENERAL<|>{$line_no}<|>Customer<|>{$tenantID}<|>{$posting_date}<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['trade_name']}-Other Charges<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else if($result['gl_code'] == '10.10.01.06.05'){
                    $rows[] = array("GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['gl_account']}<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }else{
                    $amount = str_replace('-','', $result['amount']);
                    $rows[] = array("GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$result['cas_doc_no']}-{$result['doc_no']}<|>{$result['gl_account']}<|>({$amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>");
                }

                $line_no += 10000;
            }

            $exp_batch_no = $this->app_model->generate_ExportNo(true);
            $filter_date  = date('Y-m', strtotime($posting_date));
            $file_name    = "Other_Charges_Adjustment_$tenantid" . "_" . "$filter_date" . " - $exp_batch_no.pjn";
            $targetPath   = getcwd() . '/assets/for_cas/other/' . $file_name;
            $file_data    = arrayToString($rows);
            $externalDocNo = $docno;
            #Tag Export
            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $result[0]['cas_doc_no']);
            }
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Other Charges', $file_name);

            file_put_contents($targetPath, $file_data);
        }
    }

    public function generate_paymentCollectionAdjustment($tenantid){
        $store      = $this->session->userdata('store_code');
        $post_date  = $date = date('M Y');
        $post_date  = date('Y-m', strtotime($post_date));
        $entries    = $this->db->query("
            SELECT 
                tbl.*,
                SUM(tbl.db) AS debit,
                SUM(tbl.cr) AS credit,
                sl2.partnerID,
                SUM(tbl.db + tbl.cr) AS amount
            FROM
                (SELECT 
                    g.id,
                    g.tenant_id,
                    g.posting_date,
                    g.due_date,
                    g.doc_no,
                    g.cas_doc_no,
                    g.ref_no,
                    g.document_type,
                    p.trade_name,
                    g.gl_accountID,
                    g.bank_code,
                    g.bank_name,
                    g.company_code,
                    g.department_code,
                    a.gl_account,
                    g.tag,
                    SUM(IFNULL(g.debit, 0)) db,
                    SUM(IFNULL(g.credit, 0)) cr,
                    SUM(IFNULL(g.debit, 0) + IFNULL(g.credit, 0)) amt
                FROM
                    subsidiary_ledger AS g
                LEFT JOIN (
                    SELECT 
                        DISTINCT(tnt.prospect_id) AS prospect_id,
                        tnt.tenant_id,
                        tnt.store_code
                    FROM
                        tenants tnt) t 
                ON t.tenant_id = g.tenant_id
                LEFT JOIN 
                    prospect p 
                ON p.id = t.prospect_id
                LEFT JOIN 
                    gl_accounts a 
                ON a.id = g.gl_accountID
                WHERE DATE(g.posting_date) LIKE '%$post_date%'
                -- AND t.store_code = '$store'
                AND (g.document_type = 'Payment Adjustment')
                AND (g.tenant_id <> 'DELETED'
                AND g.ref_no <> 'DELETED'
                AND g.doc_no <> 'DELETED')
                AND g.tenant_id <> 'ICM-LT000064'
                AND (g.export_batch_code IS NULL
                OR g.export_batch_code = '')
                GROUP BY 
                    g.doc_no, g.ref_no, g.gl_accountID, g.tenant_id, g.posting_date/*, g.document_type*/
                HAVING 
                    amt <> 0
                ORDER BY 
                    g.document_type , g.doc_no) 
                AS tbl
            LEFT JOIN
                (SELECT 
                        s.*, s.gl_accountID AS partnerID
                    FROM
                        subsidiary_ledger s
                    WHERE
                        s.debit IS NULL 
                    AND s.credit IS NOT NULL
                    AND (s.document_type = 'Payment Adjustment')
                    GROUP BY 
                        s.doc_no, s.ref_no , s.credit) sl2 
            ON tbl.doc_no = sl2.doc_no
            AND tbl.ref_no = sl2.ref_no
            AND tbl.db <> 0
            AND tbl.db = ABS(sl2.credit)
            AND tbl.tenant_id = '$tenantid'
            AND tbl.document_type = sl2.document_type
            GROUP BY 
                tbl.doc_no, tbl.gl_accountID, tbl.posting_date, tbl.document_type, sl2.partnerID
            ORDER BY 
                tbl.posting_date, tbl.doc_no, tbl.id ,credit DESC , debit DESC , tbl.trade_name , tbl.ref_no")->result_object();

        if(!empty($entries)){
            $line_no     = 10000;
            $data_csv    = [];
            $doc_nos     = [];
            $batchName   = "";
            $docType     = "";
            $externalDoc = "";

            foreach ($entries as $key => $entry) {
                if($entry->amount == 0 ) continue;

                $pDate       = date('m/d/Y', strtotime($entry->posting_date)); 
                $externalDoc = $entry->cas_doc_no;

                if($entry->tag == 'Preop'){
                    $batchName = "ACK_RCPT";
                }else{
                    $batchName = "OFCL_RCPT";
                }

                $tenderType = $this->db->query("SELECT * FROM payment_scheme WHERE receipt_no = '".$entry->doc_no."'")->ROW();

                if($entry->gl_accountID == 4 || $entry->gl_accountID == 22 || $entry->gl_accountID == 29){
                    // $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';

                    if($entry->amount > 0){
                        $amount     = $entry->amount;
                    }else{
                        $amount     = "(" . str_replace('-','', $entry->amount) . ")";
                    }

                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Customer<|>{$entry->tenant_id}<|>{$pDate}<|>{$entry->document_type}<|>{$entry->cas_doc_no}<|>{$entry->trade_name}-{$entry->doc_no}<|>{$amount}<|>{$entry->company_code}<|>{$entry->department_code}<|>CASHRECJNL<|>{$batchName}<|><|><|><|><|><|>");
                } else if($entry->gl_accountID == 9 || $entry->gl_accountID == 8 || $entry->gl_accountID == 7){
                    // $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';
                    
                    if($entry->amount > 0){
                        $amount     = $entry->amount;
                    }else{
                        $amount     = "(" . str_replace('-','', $entry->amount) . ")";
                    }
                    
                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Customer<|>{$entry->tenant_id}<|>{$pDate}<|>{$entry->document_type}<|>{$entry->cas_doc_no}<|>{$entry->trade_name}-{$entry->doc_no}<|>{$amount}<|>{$entry->company_code}<|>{$entry->department_code}<|>CASHRECJNL<|>{$batchName}<|><|><|><|><|><|>");
                }else{
                    $gl = $this->db->select('*')
                                   ->from('gl_accounts')
                                   ->where(['id'=>$entry->gl_accountID])
                                   ->limit(1)
                                   ->get()
                                   ->row();
                    $tenderType = $tenderType->tender_typeDesc;
                    // $docType    = $entry->document_type == 'Payment Adjustment' ? 'Payment Adjustment' : 'Payment';

                    if($entry->amount > 0){
                        $amount     = $entry->amount;
                    }else{
                        $amount     = "(" . str_replace('-','', $entry->amount) . ")";
                    }

                    $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Bank Account<|>{$entry->bank_code}<|>$pDate<|>$entry->document_type<|>$entry->cas_doc_no<|>{$entry->bank_name}-{$entry->doc_no}<|>$amount<|>$entry->company_code<|>$entry->department_code<|>CASHRECJNL<|>$batchName<|><|><|><|>{$tenderType}<|><|>");
                }

                $line_no += 10000;

                if(!in_array($entry->doc_no, $doc_nos)){
                    $doc_nos[] = $entry->doc_no;
                }
            }

            foreach ($entries as $key => $entry){
                if($entry->tag != 'Preop'){
                    if($entry->gl_accountID == 4 || $entry->gl_accountID == 22 || $entry->gl_accountID == 29){
                       
                    } else if($entry->gl_accountID == 9 || $entry->gl_accountID == 8 || $entry->gl_accountID == 7){
                    
                    }else{
                        $invoice    = $this->app_model->getInvoice($entry->cas_doc_no);
                        
                        if($entry->amount > 0){
                            $amount     = $invoice->debit;
                        }else{
                            $amount     = "(" . $invoice->debit . ")";
                        }

                        $data_csv[] = array("CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$amount<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>");
                    
                        $line_no += 10000;
                    }
                }
            }

            $exp_batch_no = $this->app_model->generate_ExportNo(true);

            foreach ($doc_nos as $doc_no) {
                $this->app_model->updateEntryAsExported($doc_no, $exp_batch_no, $externalDoc);
            }

            # $file_name  = "Other_Charges_$tenantid" . "_" . "$filter_date" . " - $exp_batch_no.pjn";
            $file_name  = "Payment_Collection_Adjustment_$tenantid" . "_$post_date" . " - $exp_batch_no.crj";
            $targetPath = getcwd() . '/assets/for_cas/payment/' . $file_name;

            $data = arrayToString($data_csv);
            #$insert to export log
            $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);
            file_put_contents($targetPath, $data);
        }
    }

    public function generateTesting(){
        $tradename = 'BOHOL PERSONS WITH DISABILITY WORKERS MULTIPURPOSE COOPERATIVE';
        $basic = '-Rent';
        $other = '-Other Charges';
        $nameCount = strlen($tradename);

        $b = substr($tradename, 0, 45) . $basic;
        $o = substr($tradename, 0, 36) . $other;

        dump($b);
        dump($o);
        dump(strlen($basic));
        dump(strlen($other));

    }

    public function testUploadToPortal(){
        $tenantID = 'ICM-LT001642';

        $tenant       = $this->live->query("SELECT * FROM tenants WHERE tenant_id = '{$tenantID}' AND status = 'Active' AND flag = 'Posted'")->ROW();
        $prospect     = $this->live->query("SELECT * FROM prospect WHERE id = '{$tenant->prospect_id}'")->ROW();
        $locationCode = $this->live->query("SELECT * FROM location_code WHERE id = '{$tenant->locationCode_id}'")->ROW();
        #CHECK IF TENANT EXIST
        $tenantP      = $this->portal->query("SELECT * FROM `duxvwc44_agc-pms`.tenants WHERE tenant_id = '{$tenantID}'")->ROW();

        if(empty($tenantP)){
            $this->portal->trans_start();
            $this->portal->insert('`duxvwc44_agc-pms`.tenants', $tenant);
            $this->portal->insert('`duxvwc44_agc-pms`.prospect', $prospect);
            $this->portal->insert('`duxvwc44_agc-pms`.location_code', $locationCode);
            $this->portal->trans_complete();

            if($this->portal->trans_status() === FALSE){
                dump($this->portal->_error_message());
            }else{
                dump('success saved');
            }
        }else{
            if($tenantP->flag === 'Posted'){
                dump("Tenant already exist");
            }else{
                $this->portal->trans_start();
                $this->portal->where('id', $tenantP->id);
                $this->portal->update('`duxvwc44_agc-pms`.tenants', $tenant);
                $this->portal->insert('`duxvwc44_agc-pms`.location_code', $locationCode);
                $this->portal->trans_complete();

                if($this->portal->trans_status() === FALSE){
                    dump($this->portal->_error_message());
                }else{
                    dump('success update');
                }
            }
        }
    }

    public function passwordHash(){
        $pass = password_hash("ICM-LT000442", PASSWORD_DEFAULT);
        $p    = "ICM-LT000443";

        if(password_verify($p, $pass)){
            echo "correct password";
        }else{
            echo "Incorrect password, please try again";
        }
    }

    public function test(){
        $this->load->view('test');
    }
}