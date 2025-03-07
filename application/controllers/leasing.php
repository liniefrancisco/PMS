<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Leasing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url']);
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

        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->_user_id = $this->session->userdata('id');

        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        if (!$this->session->userdata('leasing_logged_in') && !$this->session->userdata('cfs_logged_in')) {
            redirect('ctrl_leasing/');
        }

        //SET TO FALSE IF PANDEMIC "NO PENALTY RULE" ENDS
        $this->DISABLE_PENALTY = false;
    }
    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }
    function password_crypt()
    {
        echo "<form action='../leasing/password_crypt' method='post'>";
        echo "<input type='text' name='password'>";
        echo "<button type='submit'>Generate</button>";
        echo "</form>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // collect value of input field
            $password = $_POST['password'];
            echo 'Password MD5 - ' . md5($password);
            echo '<br>';
            echo "<a href='../leasing/password_crypt'>CLEAR</a>";
        }
    }
    public function invoicing()
    {
        $data = [
            'current_date' => getCurrentDate(),
            'flashdata' => $this->session->flashdata('message'),
            'expiry_tenants' => $this->app_model->get_expiryTenants(),
            'title' => 'Billing'
        ];

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/invoicing');
        $this->load->view('leasing/footer');
    }
    public function get_tenant_details()
    {
        $trade_name = $this->input->get('trade_name', true);
        $tenancy_type = $this->input->get('tenancy_type', true);
        $result = $this->app_model->select_tradeName($trade_name, $tenancy_type);

        if (empty($result)) {
            JSONResponse(null);
        }

        /* ==========  START MODIFICATIONS ==============*/
        $tenant = (object) $result[0];
        $tenant->discounts = $this->app_model->get_myDiscounts($tenant->primaryKey);

        JSONResponse($tenant);
    }
    public function getTransactionNo()
    {
        $tenant_id = $this->input->get('tenant_id', true);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $uft_no = $this->app_model->generate_UTFTransactionNo(false, $tin_status);
        $ip_no = $this->app_model->generate_InternalTransactionNo(false, $tin_status);

        JSONResponse(compact('uft_no', 'ip_no'));
    }
    public function get_document_number()
    {
        $tenant_id = $this->uri->segment(3);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $docno = $this->app_model->get_docNo(false, $tin_status);

        JSONResponse($docno);
    }
    public function getnewsoanumber()
    {
        $tenant_id = $this->uri->segment(3);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $docno = $this->app_model->get_soaNo(false, $tin_status);

        JSONResponse($docno);
    }
    public function getnewpreopdoc()
    {
        $tenant_id = $this->uri->segment(3);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $docno = $this->app_model->payment_docNo(false, $tin_status);

        JSONResponse($docno);
    }
    public function invoicing_init_data()
    {
        $preop_charges = $this->app_model->get_preopCharges();
        $cons_materials = $this->app_model->get_constMat();

        JSONResponse(compact('preop_charges', 'cons_materials'));
    }
    public function selected_monthly_charges($tenant_id)
    {
        $tenant_id = $this->sanitize($tenant_id);
        $result = $this->app_model->selected_monthly_charges($tenant_id);
        JSONResponse($result);
    }
    public function get_otherCharges($tenant_id)
    {
        $tenant_id = $this->sanitize($tenant_id);
        $result = $this->app_model->get_otherCharges($tenant_id);
        JSONResponse($result);
    }
    # WITH CAS FUNCTIONS
    public function save_invoice()
    {
        $date = new DateTime();
        $timeStamp = $date->getTimestamp();
        $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name = $this->sanitize($this->input->post('trade_name'));
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $contract_no = $this->sanitize($this->input->post('contract_no'));
        $rental_type = $this->sanitize($this->input->post('rental_type'));
        $transaction_date = date('Y-m-d');
        $posting_date = $this->sanitize($this->input->post('posting_date'));
        $due_date = $this->sanitize($this->input->post('due_date'));
        $invoice_type = $this->sanitize($this->input->post('invoice_type'));
        $invoices = $this->input->post('invoices');
        $basic_override_token = $this->sanitize($this->input->post('basic_override_token'));
        $posting_date = date('Y-m-d', strtotime($posting_date));
        $due_date = date('Y-m-d', strtotime($due_date));
        $cas_RENT = 'BLS' . date('mdy', strtotime(date('Y-m-t', strtotime($posting_date))));
        $cas_OTHER = 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($posting_date))));

        if (!validDate($posting_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid date format on posting date!',]);
        }

        if (!validDate($due_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid date format on due date!',]);
        }

        if (!in_array($tenancy_type, ['Short Term Tenant', 'Long Term Tenant'])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenancy Type!']);
        }

        if (!in_array($rental_type, ['Fixed', 'Percentage', 'Fixed Plus Percentage', 'Fixed/Percentage w/c Higher', 'Fixed/Percentage/Minimum w/c Higher',])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Rental Type!']);
        }

        if (!in_array($invoice_type, ['Basic', 'Basic Manual', 'Retro Rent', 'Pre Operation Charges', 'Contruction Materials', 'Other Charges',])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Invoice Type!']);
        }

        if (empty($invoices)) {
            JSONResponse(['type' => 'error', 'msg' => 'No invoices found!']);
        }

        $total = 0;

        foreach ($invoices as $key => $invoice) {
            $total += (float) $invoice['actual_amount'];
        }

        if ($total <= 0) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Invoice total!',]);
        }

        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code) || empty($store)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.',]);
        }

        //VALIDATE BASIC OVERRIDE TOKEN
        if ($invoice_type == 'Basic Manual') {
            $session_key = $this->session->userdata('invoice_basic_override_token');

            if (empty($basic_override_token) || empty($session_key) || empty($session_key->token)) {
                JSONResponse(['type' => 'error_token', 'msg' => 'Manager\'s key is required!',]);
            }

            if ($basic_override_token !== $session_key->token) {
                JSONResponse(['type' => 'error_token', 'msg' => 'Manager\'s key mismatch!',]);
            }

            $upload = new FileUpload();

            $supp_docs = $upload->validate('supp_docs', 'Supporting Document')
                ->required()
                ->multiple()
                ->get();

            if ($upload->has_error()) {
                JSONResponse(['type' => 'error', 'msg' => $upload->get_errors('<br>'),]);
            }
        }

        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $doc_no = '';
        $gl_refNo = '';
        $tin_status = 'with';
        $cas_docnumber = '';
        $cas_reference = '';
        $doc_no = $this->app_model->get_docNo(false, false);
        $gl_refNo = $this->app_model->gl_refNo(false, false);

        // if (!$this->DISABLE_PENALTY) {
        if (!$this->DISABLE_PENALTY && $due_date > '2025-01-25') { // gwaps
            $this->generateLatePaymentPenalty($tenant_id, $posting_date, $due_date);
        }

        if (in_array($invoice_type, ['Basic', 'Basic Manual', 'Retro Rent'])) {
            $receivable = 0;
            $rent_income = 0;
            $subledger_data = [];
            $invoices_data = [];
            $monthly_rec_data = [];
            $subledger_data_cas = [];
            $invoices_data_cas = [];
            $monthly_rec_data_cas = [];
            $vat = null;
            $cwt = null;
            $mr_basic_rent = 0;
            $mr_percentage_rent = 0;
            $mr_discount = 0;
            $mr_vat = 0;
            $mr_cwt = 0;

            foreach ($invoices as $key => $invoice) {
                $invoice = (object) $invoice;
                $receivable += (float) $invoice->actual_amount;

                $invoices_data[] = [
                    'tenant_id' => $tenant_id,
                    'trade_name' => $trade_name,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'store_code' => $store_code,
                    'contract_no' => $contract_no,
                    'charges_type' => $invoice->charge_type,
                    'charges_code' => $invoice->charge_code,
                    'description' => $invoice->description,
                    'uom' => $invoice->uom,
                    'unit_price' => !empty($invoice->unit_price) ? $invoice->unit_price : 0,
                    'total_unit' => $invoice->total_unit,
                    'expected_amt' => $invoice->actual_amount,
                    'balance' => $invoice->actual_amount,
                    'actual_amt' => $invoice->actual_amount,
                    'total_gross' => $invoice->charge_type == 'Percentage Rent' ? $invoice->unit_price : 0,
                    'days_in_month' => $invoice->charge_type == 'Basic/Monthly Rental' ? $invoice->days_in_month : null,
                    'days_occupied' => $invoice->charge_type == 'Basic/Monthly Rental' ? $invoice->days_occupied : null,
                    'tag' => 'Posted',
                ];

                if ($invoice->description != 'Vat Output' && $invoice->description != 'Creditable Withholding Tax') {
                    $rent_income += (float) $invoice->actual_amount;
                }

                //MONTHLY RECEIVABLE PERCENTAGE RENT
                if (in_array($invoice->description, ['Percentage Rent'])) {
                    $mr_percentage_rent += (float) $invoice->actual_amount;
                }

                //MONTHLY RECEIVABLE BASIC RENT
                if (in_array($invoice->description, ['Rental Incrementation',]) || in_array($invoice->charge_type, ['Basic/Monthly Rental'])) {
                    $mr_basic_rent += (float) $invoice->actual_amount;
                }

                //MONTHLY RECEIVABLE BASIC DISCOUNT
                if ($invoice->charge_type == 'Discount') {
                    $mr_discount += (float) abs($invoice->actual_amount);
                }

                if ($invoice->description == 'Vat Output') {
                    $vat = $invoice;
                    $mr_vat += (float) abs($invoice->actual_amount);
                }

                if ($invoice->description == 'Creditable Withholding Tax') {
                    $cwt = $invoice;
                    $mr_cwt += (float) abs($invoice->actual_amount);
                }
            }

            /* ======================== START MONTHLY RECEIVABLE RECORD =========================*/

            //BASIC RENT
            $monthly_rec_data[] = [
                'tenant_id' => $tenant_id,
                'doc_no' => $doc_no,
                'posting_date' => $posting_date,
                'description' => 'Basic Rent',
                'amount' => abs($mr_basic_rent),
            ];

            //PERCENTAGE RENT
            if ($mr_percentage_rent > 0) {
                $monthly_rec_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'description' => 'Percentage Rent',
                    'amount' => abs($mr_basic_rent),
                ];
            }

            //VAT
            if ($mr_vat > 0) {
                $monthly_rec_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'description' => 'VAT',
                    'amount' => abs($mr_vat),
                ];
            }

            //CWT
            if ($mr_cwt > 0) {
                $monthly_rec_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'description' => 'WHT',
                    'amount' => abs($mr_cwt),
                ];
            }

            $monthly_rec_data[] = [
                'tenant_id' => $tenant_id,
                'doc_no' => $doc_no,
                'posting_date' => $posting_date,
                'description' => 'Net Rental',
                'amount' => abs($receivable),
            ];

            /*========================= END MONTHLY RECEIVABLE RECORD==========================*/

            //RENT RECEIVABLE SL DATA
            $subledger_data[] = [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Invoice',
                'ref_no' => $gl_refNo,
                'doc_no' => $doc_no,
                'cas_doc_no' => $cas_RENT . '-' . $doc_no,
                'due_date' => $due_date,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID('10.10.01.03.16'),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => abs($receivable),
                'tag' => $invoice_type == 'Retro Rent' ? 'Retro Rent' : 'Basic Rent',
                'prepared_by' => $this->session->userdata('id'),
            ];

            //RENT INCOME SL DATA
            $subledger_data[] = [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'due_date' => $due_date,
                'document_type' => 'Invoice',
                'ref_no' => $gl_refNo,
                'doc_no' => $doc_no,
                'cas_doc_no' => $cas_RENT . '-' . $doc_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID('20.60.01'),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'credit' => -1 * abs($rent_income),
                'prepared_by' => $this->session->userdata('id'),
            ];

            if (!empty($vat)) {
                $subledger_data[] = [
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'document_type' => 'Invoice',
                    'ref_no' => $gl_refNo,
                    'doc_no' => $doc_no,
                    'cas_doc_no' => $cas_RENT . '-' . $doc_no,
                    'tenant_id' => $tenant_id,
                    'gl_accountID' => $this->app_model->gl_accountID('10.20.01.01.01.14'),
                    'company_code' => $store->company_code,
                    'department_code' => $store->dept_code,
                    'credit' => -1 * abs($vat->actual_amount),
                    'prepared_by' => $this->session->userdata('id'),
                ];
            }

            if (!empty($cwt)) {
                $subledger_data[] = [
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'document_type' => 'Invoice',
                    'ref_no' => $gl_refNo,
                    'doc_no' => $doc_no,
                    'cas_doc_no' => $cas_RENT . '-' . $doc_no,
                    'tenant_id' => $tenant_id,
                    'gl_accountID' => $this->app_model->gl_accountID('10.10.01.06.05'),
                    'company_code' => $store->company_code,
                    'department_code' => $store->dept_code,
                    'debit' => abs($cwt->actual_amount),
                    'prepared_by' => $this->session->userdata('id'),
                ];
            }

            $ledger_data = [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Invoice',
                'ref_no' => $this->app_model->generate_refNo(false, false),
                'doc_no' => $doc_no,
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'description' => ($invoice_type == 'Retro Rent' ? 'Retro-' : 'Basic-') . $trade_name,
                'credit' => abs($receivable),
                'debit' => 0,
                'balance' => -1 * abs($receivable),
                'due_date' => $due_date,
                'charges_type' => $invoice_type == 'Retro Rent' ? 'Retro' : 'Basic',
            ];

            $this->db->trans_start();
            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert('ledger', $ledger_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            foreach ($subledger_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            if ($invoice_type == 'Basic Manual') {
                $session_key = $this->session->userdata('invoice_basic_override_token');

                $inv_over_data = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'manager_id' => $session_key->manager_id,
                    'override_by' => $this->session->userdata('id'),
                    'invoice_type' => 'Basic Rent',
                    'amount' => abs($receivable),
                ];

                $this->db->insert('invoice_override', $inv_over_data);
                $inv_over_id = $this->db->insert_id();
                $targetPath = getcwd() . '/assets/invoice_override_docs/';

                foreach ($supp_docs as $key => $supp) {
                    //Setup our new file path
                    $filename = $tenant_id . time() . $supp['name'];
                    move_uploaded_file($supp['tmp_name'], $targetPath . $filename);

                    $supp_doc_data = [
                        'inv_over_id' => $inv_over_id,
                        'tenant_id' => $tenant_id,
                        'doc_no' => $doc_no,
                        'file_name' => $filename,
                    ];

                    $this->db->insert('invoice_override_docs', $supp_doc_data);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                JSONResponse(['type' => 'error', 'msg' => 'Something went wrong!',]);
            }

            // $this->generate_RRreports($tenant_id, $posting_date);
            JSONResponse(['type' => 'success', 'msg' => 'Transaction complete!',]);
        } elseif ($invoice_type == 'Pre Operation Charges') {
            $preop_data = [];
            $invoices_data = [];
            $monthly_rec_data = [];
            $preop_data_cas = [];
            $invoices_data_cas = [];
            $monthly_rec_data_cas = [];

            foreach ($invoices as $key => $invoice) {
                $invoice = (object) $invoice;

                $preop_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'cas_doc_no' => $cas_OTHER . '-' . $doc_no,
                    'description' => $invoice->description,
                    'posting_date' => $posting_date,
                    'due_date' => $due_date,
                    'amount' => abs($invoice->actual_amount),
                    'org_amount' => abs($invoice->actual_amount),
                    'tag' => 'Posted',
                ];

                // For Montly Receivable Report
                $monthly_rec_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'description' => $invoice->description,
                    'amount' => abs($invoice->actual_amount),
                ];

                $invoices_data[] = [
                    'tenant_id' => $tenant_id,
                    'trade_name' => $trade_name,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'store_code' => $store_code,
                    'contract_no' => $contract_no,
                    'charges_type' => $invoice->charge_type,
                    'charges_code' => $invoice->charge_code,
                    'description' => $invoice->description,
                    'uom' => $invoice->uom,
                    'unit_price' => $invoice->unit_price,
                    'total_unit' => $invoice->total_unit,
                    'expected_amt' => abs($invoice->actual_amount),
                    'actual_amt' => abs($invoice->actual_amount),
                    'balance' => abs($invoice->actual_amount),
                    'tag' => 'Posted',
                ];
            }

            $this->db->trans_start();
            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert_batch('tmp_preoperationcharges', $preop_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                JSONResponse(['type' => 'error', 'msg' => 'Something went wrong!',]);
            }

            // $this->generate_PreopReports($tenant_id, $posting_date);
            JSONResponse(['type' => 'success', 'msg' => 'Transaction complete!',]);
        } else {
            $invoices_data = [];
            $sl_data = [];
            $monthly_rec_data = [];
            $ledger_data = [];
            $ar_amount = 0;
            $invoices_data_cas = [];
            $sl_data_cas = [];
            $monthly_rec_data_cas = [];
            $ledger_data_cas = [];

            foreach ($invoices as $key => $invoice) {
                $invoice = (object) $invoice;
                // $ar_amount += (float) $invoice->actual_amount;
                if ($invoice->description !== "Vat Output") { // gwaps addition
                    $ar_amount += (float) $invoice->actual_amount;
                } // gwaps end

                // INVOICING DATA
                $invoices_data[] = [
                    'tenant_id' => $tenant_id,
                    'trade_name' => $trade_name,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'store_code' => $store_code,
                    'contract_no' => $contract_no,
                    'charges_type' => $invoice->charge_type,
                    'charges_code' => $invoice->charge_code,
                    'description' => $invoice->description,
                    'uom' => $invoice->uom,
                    'unit_price' => $invoice->unit_price,
                    'prev_reading' => $invoice->prev_reading,
                    'curr_reading' => $invoice->curr_reading,
                    'total_unit' => $invoice->total_unit,
                    'expected_amt' => $invoice->actual_amount,
                    'actual_amt' => $invoice->actual_amount,
                    'balance' => $invoice->actual_amount,
                    'with_penalty' => $invoice->with_penalty,
                    'tag' => 'Posted',
                ];

                $ledger_data[] = [
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'document_type' => 'Invoice',
                    'doc_no' => $doc_no,
                    'ref_no' => $this->app_model->generate_refNo(false, false),
                    'tenant_id' => $tenant_id,
                    'contract_no' => $contract_no,
                    'description' => 'Other-' . $trade_name . '-' . $invoice->description,
                    'credit' => $invoice->description != 'Expanded Withholding Tax' ? abs($invoice->actual_amount) : 0,
                    'debit' => $invoice->description == 'Expanded Withholding Tax' ? abs($invoice->actual_amount) : 0,
                    'balance' => $invoice->description == 'Expanded Withholding Tax' ? abs($invoice->actual_amount) : -1 * abs($invoice->actual_amount),
                    'due_date' => $due_date,
                    'charges_type' => 'Other',
                    'flag' => $invoice->description == 'Expanded Withholding Tax' ? 'EWT' : null,
                    'with_penalty' => $invoice->with_penalty,
                ];

                // For Monthly Receivable Report
                $monthly_rec_data[] = [
                    'tenant_id' => $tenant_id,
                    'doc_no' => $doc_no,
                    'posting_date' => $posting_date,
                    'description' => $invoice->description,
                    'amount' => abs($invoice->actual_amount),
                ];

                if ($invoice->description == 'Expanded Withholding Tax') {
                    $gl_code = '10.10.01.06.05';
                } elseif ($invoice->description == 'Common Usage Charges') {
                    $gl_code = '20.80.01.08.03';
                } elseif ($invoice->description == 'Electricity') {
                    $gl_code = '20.80.01.08.02';
                } elseif ($invoice->description == 'Aircon') {
                    $gl_code = '20.80.01.08.04';
                } elseif ($invoice->description == 'Late submission of Deposit Slip' || $invoice->description == 'Late Payment Penalty' || $invoice->description == 'Penalty') {
                    $gl_code = '20.80.01.08.01';
                } elseif ($invoice->description == 'Chilled Water') {
                    $gl_code = '20.80.01.08.05';
                } elseif ($invoice->description == 'Water') {
                    $gl_code = '20.80.01.08.08';
                } else {
                    $gl_code = '20.80.01.08.07';
                }

                $sl_data[] = [
                    'posting_date' => $posting_date,
                    'transaction_date' => $transaction_date,
                    'due_date' => $due_date,
                    'document_type' => 'Invoice',
                    'ref_no' => $gl_refNo,
                    'doc_no' => $doc_no,
                    'cas_doc_no' => $cas_OTHER . '-' . $doc_no,
                    'tenant_id' => $tenant_id,
                    'gl_accountID' => $this->app_model->gl_accountID($gl_code),
                    'company_code' => $store->company_code,
                    'department_code' => $store->dept_code,
                    'debit' => $invoice->description == 'Expanded Withholding Tax' ? abs($invoice->actual_amount) : null,
                    'credit' => $invoice->description != 'Expanded Withholding Tax' ? -1 * abs($invoice->actual_amount) : null,
                    'tag' => $invoice->description == 'Expanded Withholding Tax' ? 'Expanded' : null,
                    'with_penalty' => $invoice->with_penalty,
                    'prepared_by' => $this->session->userdata('id'),
                ];
            }

            $isAGCSubsidiary = $this->app_model->is_AGCSubsidiary($tenant_id);
            $ar_code = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

            //AR ENTRY
            array_unshift($sl_data, [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Invoice',
                'ref_no' => $gl_refNo,
                'doc_no' => $doc_no,
                'cas_doc_no' => $cas_OTHER . '-' . $doc_no,
                'due_date' => $due_date,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($ar_code),
                'company_code' => $this->session->userdata('company_code'),
                'department_code' => $store->dept_code,
                'debit' => $ar_amount,
                'tag' => 'Other',
                'prepared_by' => $this->session->userdata('id'),
            ]);

            $this->db->trans_start();

            $this->db->insert_batch('invoicing', $invoices_data);
            $this->db->insert_batch('ledger', $ledger_data);
            $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                JSONResponse(['type' => 'error', 'msg' => 'Something went wrong!',]);
            }

            // $this->generate_ARreports($tenant_id, $posting_date);
            JSONResponse(['type' => 'success', 'msg' => 'Transaction complete!',]);
        }
    }
    function generateLatePaymentPenalty($tenant_id = '', $posting_date = '', $due_date = '')
    {
        $tenant_id = $this->sanitize($tenant_id);
        $posting_date = $this->sanitize($posting_date);
        $due_date = $this->sanitize($due_date);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $doc_no = '';
        $gl_refNo = '';
        $tin_status = 'with';
        $cas_docnumber = '';
        $cas_reference = '';

        $penalty_docNo = $this->app_model->get_docNo(false, $tin_status);
        $gl_penaltyRefNo = $this->app_model->gl_refNo(false, $tin_status);

        if (!validDate($posting_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Posting Date!']);
        }

        if (!validDate($due_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Due Date!']);
        }

        $posting_date = date('Y-m-d', strtotime($posting_date));
        $tenant = $this->app_model->getTenantByTenantID($tenant_id);

        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.',]);
        }

        $transaction_date = date('Y-m-d');
        $penalty_latepayment = $this->app_model->get_latePaymentPenalty($tenant_id);

        if (!$penalty_latepayment) {
            return;
        }

        $invoices_data = [];
        $ledger_data = [];
        $sl_data = [];
        $monthly_rec_data = [];
        $invoices_data_cas = [];
        $ledger_data_cas = [];
        $sl_data_cas = [];
        $monthly_rec_data_cas = [];

        foreach ($penalty_latepayment as $penalty) {
            $invoices_data[] = [
                'tenant_id' => $tenant_id,
                'trade_name' => $tenant->trade_name,
                'doc_no' => $penalty_docNo,
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'due_date' => $due_date,
                'store_code' => $store_code,
                'contract_no' => $tenant->contract_no,
                'charges_type' => 'Other',
                'description' => $penalty['description'],
                'expected_amt' => $penalty['amount'],
                'actual_amt' => $penalty['amount'],
                'balance' => $penalty['amount'],
                'flag' => 'Penalty',
                'tag' => 'Posted',
                'with_penalty' => 'Yes',
            ];

            $ledger_data[] = [
                'posting_date' => $posting_date,
                'transaction_date' => $penalty['posting_date'],
                'document_type' => 'Payment',
                'due_date' => $due_date,
                'doc_no' => $penalty_docNo,
                'charges_type' => 'Other',
                'ref_no' => $this->app_model->generate_refNo(false, false),
                'tenant_id' => $tenant_id,
                'contract_no' => $penalty['contract_no'],
                'description' => 'Other-' . $tenant->trade_name . '-Penalty',
                'credit' => $penalty['amount'],
                'debit' => 0,
                'balance' => -1 * round($penalty['amount'], 2),
                'flag' => 'Penalty',
                'with_penalty' => 'Yes',
            ];

            # CAS _______________________________________________________________________________________________________

            $isAGCSubsidiary = $this->app_model->is_AGCSubsidiary($tenant_id);
            $ar_code = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

            $sl_data[] = [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Invoice',
                'ref_no' => $gl_penaltyRefNo,
                'doc_no' => $penalty_docNo,
                'due_date' => $due_date,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($ar_code),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => round($penalty['amount'], 2),
                'tag' => 'Penalty',
                'prepared_by' => $this->session->userdata('id'),
            ];

            $sl_data[] = [
                'posting_date' => $posting_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Invoice',
                'ref_no' => $gl_penaltyRefNo,
                'due_date' => $due_date,
                'doc_no' => $penalty_docNo,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID('20.80.01.08.01'),
                'company_code' => $this->session->userdata('company_code'),
                'department_code' => $store->dept_code,
                'credit' => -1 * round($penalty['amount'], 2),
                'prepared_by' => $this->session->userdata('id'),
                'with_penalty' => 'Yes',
            ];

            // For Montly Receivable Report
            $monthly_rec_data[] = [
                'tenant_id' => $tenant_id,
                'doc_no' => $penalty_docNo,
                'posting_date' => $posting_date,
                'description' => 'Penalty',
                'amount' => $penalty['amount'],
            ];
            // $this->app_model->insert('monthly_receivable_report', $reportData);
        }

        $this->db->trans_start();
        $this->db->insert_batch('invoicing', $invoices_data);
        $this->db->insert_batch('ledger', $ledger_data);
        $this->db->insert_batch('monthly_receivable_report', $monthly_rec_data);

        foreach ($sl_data as $key => $data) {
            $this->db->insert('general_ledger', $data);
            $this->db->insert('subsidiary_ledger', $data);
        }

        $this->db->trans_complete();

        if ($result = $this->db->trans_status()) {
            foreach ($penalty_latepayment as $penalty) {
                //===== Update tmp_latepaymentpenalty that the penalty was already invoiced ===== //
                $this->app_model->update_tmp_latepaymentpenalty($penalty['id'], $penalty_docNo);
                $this->app_model->update_ledgerDueDate($tenant_id, $penalty['doc_no'], $due_date);
            }
        }

        return $result;
    }
    public function soa()
    {
        // $data['soa_no']          = $this->app_model->get_soaNo(false);
        // $data['current_date'] = getCurrentDate();
        // $data['flashdata'] = $this->session->flashdata('message');
        // $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

        $data = [
            'current_date' => getCurrentDate(),
            'flashdata' => $this->session->flashdata('message'),
            'expiry_tenants' => $this->app_model->get_expiryTenants(),
            'title' => 'SOA'
        ];

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/soa');
        $this->load->view('leasing/footer');
    }
    public function get_tenant_balances()
    {
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $date_created = $this->sanitize($this->input->post('date_created'));
        $date_created = date('Y-m-d', strtotime($date_created));
        $invoices = $this->app_model->getTenantBalances($tenant_id, $date_created);
        $preop_charges = $this->app_model->getTenantPreopBalances($tenant_id, $date_created);
        $data = array_merge($invoices, $preop_charges);
        $uris = $this->app_model->getTenantUnearnedRentIncome($tenant_id, $date_created);
        $advance = 0;

        foreach ($uris as $key => $uri) {
            $advance += $uri->balance;
        }

        JSONResponse(['docs' => $data, 'advance' => $advance]);
    }
    public function generate_soa()
    {
        $tenancy_type = $this->input->post('tenancy_type');
        $trade_name = $this->input->post('trade_name');
        $contract_no = $this->input->post('contract_no');
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $tenant_address = $this->input->post('tenant_address');
        $billing_period = $this->input->post('billing_period');
        $date_created = $this->input->post('date_created');
        $date_created = date('Y-m-d', strtotime($date_created));
        $collection_date = $this->input->post('collection_date');
        $totalAmount = $this->input->post('totalAmount');
        $totalAmount = str_replace(',', '', $totalAmount);
        $soa_docs = $this->input->post('soa_docs');
        // $soa_no          = $this->app_model->get_soaNo();
        $details_soa = $this->app_model->details_soa($tenant_id);
        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $soa_no = '';
        $soa_no_cas = '';
        $tin_status = 'with';
        $soa_no = $this->app_model->get_soaNo(false);

        $transaction_date = date('Y-m-d');

        $this->db->trans_start();

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);
        $soa_display = [
            'tenant' => $tenant,
            'soa_no' => $soa_no,
            'total_amount_due' => 0,
            'billing_period' => strtoupper($billing_period),
            'collection_date' => date('F d, Y', strtotime($collection_date)),
            'date_created' => date('F d, Y', strtotime($date_created)),
            'tenancy_type' => ucwords($tenancy_type),
        ];

        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);
            $soa_display['store'] = $store;

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.']);
        }

        $grouped_docs = array_group_by($soa_docs, function ($doc) {
            return $doc['document_type'];
        });

        // ====== FOR SHOWING THE ORIGINAL SUB TOTAL ====== ADDED 2021-09-06 //
        $debit_display = 0;

        foreach ($soa_docs as $key => $value) {
            $debit_display += $value['debit'];
        }

        $retro_rent = !empty($grouped_docs['Retro']) ? $grouped_docs['Retro'] : [];
        $preop_charges = !empty($grouped_docs['Preop-Charges']) ? $grouped_docs['Preop-Charges'] : [];
        $invoices = !empty($grouped_docs['Invoice']) ? $grouped_docs['Invoice'] : [];

        /*$date = date_create($date_created);
        date_sub($date,date_interval_create_from_date_string("15 days"));
        $soa_current_date   =  date_format($date,"Y-m");*/

        $soa_amount_due = 0;

        # FOR PRE-OPERATIONAL CHARGES
        if (!empty($preop_charges)) {
            $soa_display['preop'] = [];
            $preop_total = 0;

            foreach ($preop_charges as $key => $preop) {
                $preop = (object) $preop;

                $soa_display['preop'][$key]['description'] = $preop->description;
                $soa_display['preop'][$key]['amount'] = $preop->balance;

                $preop_total += $preop->balance;

                $this->db->insert('soa_line', [
                    'soa_no' => $soa_no,
                    'doc_no' => $preop->doc_no,
                    'amount' => $preop->balance,
                    'tenant_id' => $tenant_id,
                    'preop_id' => $preop->id,
                ]);
            }

            $soa_display['preop_total'] = $preop_total;
            $soa_amount_due += $preop_total;
        }

        # FOR RETRO RENT
        if (!empty($retro_rent)) {
            $retro_total = 0;

            foreach ($retro_rent as $key => $retro) {
                $retro = (object) $retro;

                $soa_display['retro'][$key]['debit'] = $retro->debit;
                $soa_display['retro'][$key]['credit'] = $retro->credit;
                $soa_display['retro'][$key]['balance'] = $retro->balance;
                $retro_total += $retro->balance;

                $this->db->insert('soa_line', [
                    'soa_no' => $soa_no,
                    'doc_no' => $retro->doc_no,
                    'amount' => $retro->balance,
                    'tenant_id' => $tenant_id,
                ]);
            }

            $soa_display['retro_total'] = $retro_total;
            $soa_amount_due += $retro_total;
        }

        # GET THE LAST POSTING AND DUE DATE
        if (!empty($invoices)) {
            $last_due_date = $invoices[0]['due_date'];
            $last_post_date = $invoices[0]['posting_date'];

            foreach ($invoices as $key => $inv) {
                $inv = (object) $inv;
                $last_due_date = strtotime($inv->due_date) > strtotime($last_due_date) ? $inv->due_date : $last_due_date;
                $last_post_date = strtotime($inv->posting_date) > strtotime($last_post_date) ? $inv->posting_date : $last_post_date;
            }

            /*//POSTING DATE BASE CURRENT DATE
            $curr_month = date_create($last_post_date);
            date_sub($curr_month, date_interval_create_from_date_string("15 days"));*/

            //DUE DATE BASE CURRENT DATE
            $curr_month = date_create($last_due_date);
            date_sub($curr_month, date_interval_create_from_date_string('20 days'));

            $curr_month = date_format($curr_month, 'Y-m');
        }

        #GET THE LAST POSTING AND DUE DATE
        $grouped_invoices = array_group_by($invoices, function ($doc) {
            $doc = (object) $doc;
            //POSTING DATE BASE GROUP
            //$date = date_create($doc->posting_date);
            //date_sub($date,date_interval_create_from_date_string("15 days"));

            //DUE DATE BASE GROUP
            $date = date_create($doc->due_date);
            date_sub($date, date_interval_create_from_date_string('20 days'));

            return date_format($date, 'Y-m');
        });

        ksort($grouped_invoices);

        if (!empty($grouped_invoices)) {
            $soa_display['previous'] = [];
            $penalties = [];

            foreach ($grouped_invoices as $date => $gp_inv) {
                if ($date != $curr_month) {
                    $soa_display['previous'][$date] = [];
                    $total_per_month_debit = 0;
                    $total_per_month_credit = 0;
                    $total_per_month_balance = 0;
                    $total_per_month_payable = 0;
                    $has_basic = false;

                    foreach ($gp_inv as $key => $inv) {
                        $inv = (object) $inv;
                        $total_per_month_debit += $inv->debit;
                        $total_per_month_credit += $inv->credit;
                        $total_per_month_balance += $inv->balance;

                        if ($inv->gl_accountID == 4) {
                            $has_basic = true;
                        }

                        $this->db->insert('soa_line', [
                            'soa_no' => $soa_no,
                            'doc_no' => $inv->doc_no,
                            'amount' => $inv->balance,
                            'tenant_id' => $tenant_id,
                        ]);
                    }



                    $soa_display['previous'][$date]['has_basic'] = $has_basic;
                    $soa_display['previous'][$date]['debit'] = $total_per_month_debit;
                    $soa_display['previous'][$date]['credit'] = $total_per_month_credit;
                    $soa_display['previous'][$date]['balance'] = $total_per_month_balance;

                    $total_per_month_payable += $total_per_month_balance;

                    //========== START OF CALCULATING PENALTY HERE ================
                    if ($tenant->penalty_exempt != 1 && !$this->DISABLE_PENALTY) {
                        $penalty_grouped_invoices = array_group_by(
                            $gp_inv,
                            function ($inv) use ($last_due_date) {
                                $inv = (object) $inv;

                                $last_due = date_create($last_due_date);
                                $due_date = date_create($inv->due_date);
                                $diff = date_diff($due_date, $last_due);
                                $diff = (int) $diff->format('%R%a');

                                return floor($diff / 20);
                            }
                        );

                        $soa_display['previous'][$date]['penalties'] = [];
                        $total_no_penalty = 0;

                        foreach ($penalty_grouped_invoices as $penalty => $pen_inv) {
                            if ($penalty == 0) {
                                continue;
                            }


                            $penalty_percentage = $penalty >= 2 ? 3 : 2;
                            $total_penaltyble = 0;
                            $total_penalty = 0;
                            $penalty_due_date = $last_due_date;

                            foreach ($pen_inv as $key => $inv) {
                                $inv = (object) $inv;


                                // added by gwaps
                                // if ($inv->posting_date < '2025-01-01') { 
                                //     continue;
                                // }
                                if ($inv->due_date < '2025-01-31') {
                                    continue;
                                } // ends

                                $penaltyble = $inv->balance - $inv->nopenalty_amount;
                                $total_penaltyble += $penaltyble > 0 ? $penaltyble : 0;
                                $total_no_penalty += $penaltyble > 0 ? $inv->nopenalty_amount : 0;

                                if ($penaltyble > 0) {
                                    $penalty_due_date = $inv->due_date;
                                    $penalty_doc_no = $inv->doc_no;
                                }
                            }

                            //IF ZERO PENALTY SKIP HERE
                            if ($total_penaltyble <= 0) {
                                continue;
                            }

                            $total_penalty = $total_penaltyble * ($penalty_percentage / 100);
                            $total_penalty = round($total_penalty, 2);

                            $total_per_month_payable += $total_penalty;

                            $soa_display['previous'][$date]['penalties'][] = [
                                'penaltyble_amount' => $total_penaltyble,
                                'penalty_percentage' => $penalty_percentage,
                                'penalty_amount' => $total_penalty,
                            ];

                            //=================PUT PENALTY ENTRY HERE =====================

                            //INSERT PENALTY TO LEDGER TABLE
                            $reference = $this->app_model->generate_refNo(false, false);
                            $this->app_model->insert('ledger', [
                                'posting_date' => $date_created,
                                'document_type' => 'Penalty',
                                'ref_no' => $reference,
                                'due_date' => $penalty_due_date,
                                'transaction_date' => $transaction_date,
                                'doc_no' => $soa_no,
                                'tenant_id' => $tenant_id,
                                'contract_no' => $contract_no,
                                'description' => 'Penalty-' . $trade_name,
                                'credit' => $total_penalty,
                                'balance' => -1 * $total_penalty,
                                'flag' => 'Penalty',
                            ]);

                            $sl_data = [];
                            $ref_no = $this->app_model->gl_refNo(false, false);
                            // $ref_no_cas   = $this->cas_model->cas_referenceGL();
                            $isAGCSubsidiary = $this->app_model->is_AGCSubsidiary($tenant_id);
                            $ar_code = $isAGCSubsidiary ? '10.10.01.03.04' : '10.10.01.03.03';

                            $penalties[] = [
                                'doc_no' => $soa_no,
                                'ref_no' => $ref_no,
                                'gl_accountID' => $this->app_model->gl_accountID($ar_code),
                                'balance' => $total_penalty,
                            ];

                            $sl_data['ar_entry'] = [
                                'posting_date' => $date_created,
                                'transaction_date' => $transaction_date,
                                'due_date' => $penalty_due_date,
                                'document_type' => 'Invoice',
                                'ref_no' => $ref_no,
                                'doc_no' => $soa_no,
                                'tenant_id' => $tenant_id,
                                'gl_accountID' => $this->app_model->gl_accountID($ar_code),
                                'company_code' => $store->company_code,
                                'department_code' => '01.04.2',
                                'debit' => $total_penalty,
                                'tag' => 'Penalty',
                                'prepared_by' => $this->session->userdata('id'),
                            ];

                            $sl_data['penalty_entry'] = [
                                'posting_date' => $date_created,
                                'transaction_date' => $transaction_date,
                                'due_date' => $penalty_due_date,
                                'document_type' => 'Invoice',
                                'ref_no' => $ref_no,
                                'doc_no' => $soa_no,
                                'tenant_id' => $tenant_id,
                                'gl_accountID' => $this->app_model->gl_accountID('20.80.01.08.01'),
                                'company_code' => $store->company_code,
                                'department_code' => '01.04.2',
                                'credit' => -1 * $total_penalty,
                                'prepared_by' => $this->session->userdata('id'),
                            ];

                            foreach ($sl_data as $key => $data) {
                                $this->db->insert('subsidiary_ledger', $data);
                                $this->db->insert('general_ledger', $data);
                            }

                            // ============ Insert Into monthly_penalty table ============ //
                            $this->app_model->insert('monthly_penalty', [
                                'tenant_id' => $tenant_id,
                                'percent' => $penalty_percentage,
                                'due_date' => $penalty_due_date,
                                'doc_no' => $penalty_doc_no,
                                'collection_date' => $collection_date,
                                'soa_no' => $soa_no,
                                'amount' => $total_penalty,
                                'balance' => $total_penalty,
                            ]);

                            $this->app_model->insert(
                                'monthly_receivable_report',
                                [
                                    'tenant_id' => $tenant_id,
                                    'doc_no' => $soa_no,
                                    'posting_date' => $date_created,
                                    'description' => 'Penalty',
                                    'amount' => $total_penalty,
                                ]
                            );

                            $this->db->insert('invoicing', [
                                'tenant_id' => $tenant_id,
                                'trade_name' => $tenant->trade_name,
                                'doc_no' => $soa_no,
                                'posting_date' => $date_created,
                                'transaction_date' => $transaction_date,
                                'due_date' => $penalty_due_date,
                                'store_code' => $store_code,
                                'contract_no' => $tenant->contract_no,
                                'charges_type' => 'Other',
                                'description' => 'Penalty',
                                'expected_amt' => $total_penalty,
                                'actual_amt' => $total_penalty,
                                'balance' => $total_penalty,
                                'flag' => 'Penalty',
                                'tag' => 'Posted',
                                'with_penalty' => 'Yes',
                            ]);

                            $this->db->insert('soa_line', [
                                'soa_no' => $soa_no,
                                'doc_no' => $soa_no,
                                'amount' => $total_penalty,
                                'tenant_id' => $tenant_id,
                            ]);
                        }

                        $soa_display['previous'][$date][
                            'no_penalty'
                        ] = $total_no_penalty;
                        //========== END OF CALCULATING PENALTY HERE =====================
                    }

                    $soa_display['previous'][$date][
                        'total'
                    ] = $total_per_month_payable;
                    $soa_amount_due += $total_per_month_payable;
                } else {
                    $soa_display['current'] = ['date' => $date];
                    $current_total = 0;

                    $type_grouped_invoices = array_group_by($gp_inv, function ($inv) {
                        $inv = (object) $inv;
                        return $inv->gl_accountID == 4 ? 'basic' : 'others';
                    });

                    //CURRENT BASIC
                    $soa_display['current']['basic'] = [];
                    if (!empty($type_grouped_invoices['basic'])) {
                        $basic_sub_total = 0;
                        $basic_adj_amount = 0;
                        $basic_paid_amount = 0;

                        foreach ($type_grouped_invoices['basic'] as $key => $inv) {
                            $inv = (object) $inv;

                            $invoicing = $this->app_model->getInvoicingData(
                                $tenant_id,
                                $inv->doc_no
                            );

                            $soa_display['current']['basic'][$inv->doc_no][
                                'invoices'
                            ] = [];
                            $soa_display['current']['basic'][$inv->doc_no][
                                'adj_amount'
                            ] = $inv->adj_amount;
                            $soa_display['current']['basic'][$inv->doc_no][
                                'total'
                            ] = $inv->balance;
                            $current_total += $inv->balance;
                            $basic_sub_total += $inv->balance;
                            $basic_adj_amount += $inv->adj_amount;
                            $basic_paid_amount += $inv->credit;

                            foreach ($invoicing as $key => $invoice) {
                                $details = '';
                                if (
                                    $invoice->charges_type ==
                                    'Basic/Monthly Rental'
                                ) {
                                    $description = 'Basic Rent';

                                    if (
                                        $invoice->total_unit != 1 &&
                                        is_numeric($invoice->days_in_month) &&
                                        is_numeric($invoice->days_occupied)
                                    ) {
                                        $details =
                                            '(' .
                                            number_format(
                                                $invoice->unit_price,
                                                2
                                            ) .
                                            " x $invoice->days_occupied" .
                                            "/$invoice->days_in_month" .
                                            ' days)';
                                    }
                                } elseif (
                                    $invoice->charges_type == 'Discount'
                                ) {
                                    $description =
                                        'Discount/' . $invoice->description;
                                } else {
                                    $description = $invoice->description;

                                    if (
                                        $description ==
                                        'Rental Incrementation' ||
                                        $description == 'Percentage Rent'
                                    ) {
                                        $details =
                                            '(' .
                                            number_format(
                                                $invoice->unit_price,
                                                2
                                            ) .
                                            " x $invoice->total_unit%)";
                                    }
                                }

                                if (
                                    $invoice->charges_type == 'Discount' ||
                                    $invoice->description ==
                                    'Creditable Withholding Tax' ||
                                    $invoice->description ==
                                    'Creditable Witholding Taxes'
                                ) {
                                    $amount = abs($invoice->actual_amt) * -1;
                                } elseif (
                                    $invoice->charges_type ==
                                    'Basic/Monthly Rental'
                                ) {
                                    $amount = abs($invoice->expected_amt);
                                } else {
                                    $amount = abs($invoice->actual_amt);
                                }

                                $soa_display['current']['basic'][$inv->doc_no][
                                    'invoices'
                                ][] = [
                                        'description' => $description,
                                        'amount' => $amount,
                                        'unit_price' => $invoice->unit_price,
                                        'total_unit' => $invoice->total_unit,
                                        'details' => $details,
                                    ];
                            }

                            if (abs($inv->adj_amount) > 0) {
                                $invoice_adjustments = $this->app_model->get_adj_for_soa_display(
                                    $tenant_id,
                                    $inv->doc_no
                                );
                                $soa_display['current']['basic'][$inv->doc_no][
                                    'adj_details'
                                ] = $invoice_adjustments;
                            }

                            $soa_display['current'][
                                'basic_adj_amount'
                            ] = $basic_adj_amount;
                            $soa_display['current'][
                                'basic_sub_total'
                            ] = $basic_sub_total;
                            $soa_display['current'][
                                'basic_paid_amount'
                            ] = $basic_paid_amount;

                            $this->db->insert('soa_line', [
                                'soa_no' => $soa_no,
                                'doc_no' => $inv->doc_no,
                                'amount' => $inv->balance,
                                'tenant_id' => $tenant_id,
                            ]);
                        }
                    }

                    //CURRENT OTHER CHARGES
                    $soa_display['current']['others'] = [];
                    if (!empty($type_grouped_invoices['others'])) {
                        $other_sub_total = 0;
                        $other_adj_amount = 0;
                        $other_paid_amount = 0;
                        $total_ewt = 0;

                        foreach ($type_grouped_invoices['others'] as $key => $inv) {
                            $inv = (object) $inv;

                            $invoicing = $this->app_model->getInvoicingData(
                                $tenant_id,
                                $inv->doc_no
                            );

                            $soa_display['current']['others'][$inv->doc_no][
                                'invoices'
                            ] = [];
                            $soa_display['current']['others'][$inv->doc_no][
                                'adj_amount'
                            ] = $inv->adj_amount;
                            $soa_display['current']['others'][$inv->doc_no][
                                'total'
                            ] = $inv->balance;
                            $current_total += $inv->balance;
                            $other_sub_total += $inv->balance;
                            $other_adj_amount += $inv->adj_amount;
                            $other_paid_amount += $inv->credit;

                            foreach ($invoicing as $key => $invoice) {
                                $amount = abs($invoice->actual_amt);
                                $amount =
                                    $invoice->description ==
                                    'Expanded Withholding Tax'
                                    ? $amount * -1
                                    : $amount;

                                // ICM POST OFFICE EXEMPTION MOTHERFUCKER
                                if (
                                    $tenant_id == 'ICM-LT000114' &&
                                    $invoice->description ==
                                    'Expanded Withholding Tax'
                                ) {
                                    $total_ewt += $amount;
                                } else {
                                    $total_unit =
                                        empty($invoice->total_unit) ||
                                        $invoice->total_unit == 0
                                        ? $invoice->curr_reading -
                                        $invoice->prev_reading
                                        : $invoice->total_unit;
                                    $soa_display['current']['others'][
                                        $inv->doc_no
                                    ]['invoices'][] = [
                                            'description' => $invoice->description,
                                            'amount' => $amount,
                                            'prev_reading' =>
                                            $invoice->prev_reading,
                                            'curr_reading' =>
                                            $invoice->curr_reading,
                                            'unit_price' => $invoice->unit_price,
                                            'total_unit' => $total_unit,
                                        ];
                                }
                            }

                            if (abs($inv->adj_amount) > 0) {
                                $invoice_adjustments = $this->app_model->get_adj_for_soa_display(
                                    $tenant_id,
                                    $inv->doc_no
                                );
                                $soa_display['current']['others'][$inv->doc_no][
                                    'adj_details'
                                ] = $invoice_adjustments;
                            }

                            $soa_display['current'][
                                'other_adj_amount'
                            ] = $other_adj_amount;
                            $soa_display['current'][
                                'other_sub_total'
                            ] = $other_sub_total;
                            $soa_display['current'][
                                'other_paid_amount'
                            ] = $other_paid_amount;

                            $soa_display['current'][
                                'other_total_without_ewt'
                            ] = round($other_sub_total - $total_ewt, 2);
                            $soa_display['current']['other_total_ewt'] = round(
                                $total_ewt,
                                2
                            );

                            $this->db->insert('soa_line', [
                                'soa_no' => $soa_no,
                                'doc_no' => $inv->doc_no,
                                'amount' => $inv->balance,
                                'tenant_id' => $tenant_id,
                            ]);
                        }
                    }

                    $soa_display['current']['total'] = $current_total;
                    $soa_amount_due += $current_total;
                }
            }

            /* =============  START APPLY ADVANCES  =========== */
            $uris = $this->app_model->getTenantUnearnedRentIncome($tenant_id, $date_created);
            $total_uri_amount = 0;
            $total_uri_amount_paid = 0;

            foreach ($uris as $key => $uri) {
                $total_uri_amount += $uri->balance;
            }

            //COMMENT THIS IF APPLIED FR0M OLDEST TO NEWEST
            //$grouped_invoices = array_reverse($grouped_invoices);

            foreach ($grouped_invoices as $date => $gp_inv) {
                foreach ($gp_inv as $key => $inv) {
                    $inv = (object) $inv;

                    foreach ($uris as $key => $uri) {
                        if ($inv->balance <= 0) {
                            break;
                        }
                        if ($uri->balance <= 0) {
                            continue;
                        }

                        if ($uri->balance >= $inv->balance) {
                            $uri_amount = $inv->balance;
                            $uri->balance -= $inv->balance;
                            $inv->balance -= $inv->balance;
                        } else {
                            $uri_amount = $uri->balance;
                            $inv->balance -= $uri->balance;
                            $uri->balance -= $uri->balance;
                        }

                        $total_uri_amount_paid += $uri_amount;

                        $sl_data = [];
                        $ft_ref = $this->app_model->generate_ClosingRefNo(
                            false,
                            $tin_status
                        );

                        $sl_data['uri_entry'] = [
                            'posting_date' => $date_created,
                            'transaction_date' => $transaction_date,
                            'document_type' => 'Payment',
                            'ref_no' => $uri->ref_no,
                            'doc_no' => $soa_no,
                            'cas_doc_no' => $inv->cas_doc_no,
                            'tenant_id' => $tenant_id,
                            'gl_accountID' => $uri->gl_accountID,
                            'company_code' => $store->company_code,
                            'department_code' => $store->dept_code,
                            'debit' => $uri_amount,
                            'ft_ref' => $ft_ref,
                            'prepared_by' => $this->session->userdata('id'),
                        ];

                        $sl_data['rec_entry'] = [
                            'posting_date' => $date_created,
                            'transaction_date' => $transaction_date,
                            'document_type' => 'Payment',
                            'ref_no' => $inv->ref_no,
                            'doc_no' => $soa_no,
                            'cas_doc_no' => $inv->cas_doc_no,
                            'tenant_id' => $tenant_id,
                            'gl_accountID' => $inv->gl_accountID,
                            'company_code' => $store->company_code,
                            'department_code' => $store->dept_code,
                            'credit' => -1 * $uri_amount,
                            'ft_ref' => $ft_ref,
                            'prepared_by' => $this->session->userdata('id'),
                        ];

                        foreach ($sl_data as $key => $data) {
                            $this->db->insert('subsidiary_ledger', $data);
                            $this->db->insert('general_ledger', $data);
                        }

                        //START OF ledger table entry
                        $lgr_inv = $this->app_model->getLedgerFirstResultByDocNo(
                            $tenant_id,
                            $inv->doc_no
                        );
                        if (!empty($lgr_inv)) {
                            $ledger_data = [
                                'posting_date' => $date_created,
                                'transaction_date' => $transaction_date,
                                'document_type' => 'SOA',
                                'ref_no' => $lgr_inv->ref_no,
                                'doc_no' => $soa_no,
                                'tenant_id' => $tenant_id,
                                'contract_no' => $contract_no,
                                'description' => $lgr_inv->description,
                                'debit' => $uri_amount,
                                'balance' => 0,
                            ];

                            $this->app_model->insert('ledger', $ledger_data);
                        }

                        $lgr_uri = $this->app_model->getLedgerFirstResultByDocNo(
                            $tenant_id,
                            $uri->doc_no
                        );
                        if (!empty($lgr_uri)) {
                            $ledger_data = [
                                'posting_date' => $date_created,
                                'transaction_date' => $transaction_date,
                                'document_type' => 'SOA',
                                'ref_no' => $lgr_uri->ref_no,
                                'doc_no' => $soa_no,
                                'tenant_id' => $tenant_id,
                                'contract_no' => $contract_no,
                                'description' => $lgr_uri->description,
                                'credit' => $uri_amount,
                                'balance' => 0,
                            ];

                            $this->app_model->insert('ledger', $ledger_data);
                        }
                        //END OF ledger table entry
                    }
                }
            }

            foreach ($penalties as $key => $inv) {
                $inv = (object) $inv;
                foreach ($uris as $key => $uri) {
                    if ($inv->balance <= 0) {
                        break;
                    }
                    if ($uri->balance <= 0) {
                        continue;
                    }

                    if ($uri->balance >= $inv->balance) {
                        $uri_amount = $inv->balance;
                        $uri->balance -= $inv->balance;
                        $inv->balance -= $inv->balance;
                    } else {
                        $uri_amount = $uri->balance;
                        $inv->balance -= $uri->balance;
                        $uri->balance -= $uri->balance;
                    }

                    $total_uri_amount_paid += $uri_amount;

                    $details = $this->app_model->get_tenant_details_2(
                        $tenant_id
                    );
                    $docno = '';
                    $tin_status = 'with';

                    $sl_data = [];
                    $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);

                    $sl_data['uri_entry'] = [
                        'posting_date' => $date_created,
                        'transaction_date' => $transaction_date,
                        'document_type' => 'Payment',
                        'ref_no' => $uri->ref_no,
                        'doc_no' => $soa_no,
                        'cas_doc_no' => $inv->cas_doc_no,
                        'tenant_id' => $tenant_id,
                        'gl_accountID' => $uri->gl_accountID,
                        'company_code' => $store->company_code,
                        'department_code' => $store->dept_code,
                        'debit' => $uri_amount,
                        'ft_ref' => $ft_ref,
                        'prepared_by' => $this->session->userdata('id'),
                    ];

                    $sl_data['rec_entry'] = [
                        'posting_date' => $date_created,
                        'transaction_date' => $transaction_date,
                        'document_type' => 'Payment',
                        'ref_no' => $inv->ref_no,
                        'doc_no' => $soa_no,
                        'cas_doc_no' => $inv->cas_doc_no,
                        'tenant_id' => $tenant_id,
                        'gl_accountID' => $inv->gl_accountID,
                        'company_code' => $store->company_code,
                        'department_code' => $store->dept_code,
                        'credit' => -1 * $uri_amount,
                        'ft_ref' => $ft_ref,
                        'prepared_by' => $this->session->userdata('id'),
                    ];

                    foreach ($sl_data as $key => $data) {
                        $this->db->insert('subsidiary_ledger', $data);
                        $this->db->insert('general_ledger', $data);
                    }

                    //START OF ledger table entry
                    $lgr_inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $inv->doc_no);

                    if (!empty($lgr_inv)) {
                        $ledger_data = [
                            'posting_date' => $date_created,
                            'transaction_date' => $transaction_date,
                            'document_type' => 'SOA',
                            'ref_no' => $lgr_inv->ref_no,
                            'doc_no' => $soa_no,
                            'tenant_id' => $tenant_id,
                            'contract_no' => $contract_no,
                            'description' => $lgr_inv->description,
                            'debit' => $uri_amount,
                            'balance' => 0,
                        ];

                        $this->app_model->insert('ledger', $ledger_data);
                    }

                    $lgr_uri = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $uri->doc_no);

                    if (!empty($lgr_uri)) {
                        $ledger_data = [
                            'posting_date' => $date_created,
                            'transaction_date' => $transaction_date,
                            'document_type' => 'SOA',
                            'ref_no' => $lgr_uri->ref_no,
                            'doc_no' => $soa_no,
                            'tenant_id' => $tenant_id,
                            'contract_no' => $contract_no,
                            'description' => $lgr_uri->description,
                            'credit' => $uri_amount,
                            'balance' => 0,
                        ];

                        $this->app_model->insert('ledger', $ledger_data);
                    }
                    //END OF ledger table entry
                }
            }

            /* =============  END APPLY ADVANCES  =========== */

            $uri_balance = 0;
            foreach ($uris as $key => $uri) {
                $uri_balance += $uri->balance;
                $uri_date = $uri->posting_date;
            }

            $soa_display['uri'] = [
                'total_uri_amount' => $total_uri_amount,
                'total_uri_amount_paid' => $total_uri_amount_paid,
                'remaining' => $uri_balance,
                'date' => !empty($uri_date) ? $uri_date : '',
            ];

            $soa_amount_due -= $total_uri_amount_paid;
        }

        $soa_display['net_amount_due'] = $soa_amount_due;

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            JSONResponse([
                'type' => 'error',
                'msg' => 'Something went wrong! Unable to generate SOA file',
            ]);
        }

        $file = $this->createSoaFile($soa_display, $debit_display);

        foreach ($soa_docs as $key => $value) {
            if ($value['gl_accountID'] === '4') {
                $this->generate_RRreports($value['doc_no'], $value['posting_date']);
            } else if ($value['gl_accountID'] === '22') {
                $this->generate_ARreports($value['doc_no'], $value['posting_date']);
            } else {
                $this->generate_PreopReports($value['doc_no'], $value['posting_date']);
            }
        }

        JSONResponse([
            'type' => 'success',
            'msg' => 'SOA Successfully Generated',
            'file' => $file,
        ]);
    }
    #SOA PDF
    function createSoaFile($soa, $debit)
    {
        $soa = (object) $soa;
        $store = $soa->store;
        $tenant = $soa->tenant;
        $basic_paid_amount = 0;

        $pdf = new FPDF('p', 'mm', 'A4');
        $logoPath = getcwd() . '/assets/other_img/';
        $inCharge = getcwd() . '/img/karen_longjas_1.png';
        $store_name = $store->store_name;

        $pdf->AddPage();
        $pdf->setDisplayMode('fullpage');
        $pdf->setFont('times', 'B', 12);
        #---------------------------------------------------------------------------------------------------------------------
        $pdf->cell(15, 15, $pdf->Image($logoPath . $store->logo, $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);
        $pdf->setFont('times', 'B', 12);
        $pdf->cell(50, 10, strtoupper($store->store_name), 0, 0, 'L');
        $pdf->SetTextColor(201, 201, 201);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->cell(35, 6, ' ', 0, 0, 'L');

        $pdf->setFont('times', '', 8);
        $pdf->cell(30, 6, 'Statement For:', 1, 0, 'C', true);
        $pdf->cell(30, 6, 'Please Pay By:', 1, 0, 'C', true);
        $pdf->cell(30, 6, 'Amount Due:', 1, 0, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->ln();
        #---------------------------------------------------------------------------------------------------------------------
        $pdf->setFont('times', '', 7);
        $pdf->cell(15, 0, ' ', 0, 0, 'L');
        $pdf->cell(20, 5, 'Owned & Managed by Alturas Supermarket Corporation', 0, 0, 'L');

        $pdf->cell(65, 6, ' ', 0, 0, 'L');
        $pdf->setFont('times', '', 6);
        $pdf->cell(30, 5, $soa->billing_period, 1, 0, 'C');
        $pdf->cell(30, 5, date('F j, Y', strtotime($soa->collection_date)), 1, 0, 'C');
        $pdf->cell(30, 5, 'P ' . number_format($soa->net_amount_due, 2), 1, 0, 'C');
        #---------------------------------------------------------------------------------------------------------------------

        $pdf->ln();
        $pdf->setFont('times', '', 7);
        $pdf->cell(15, 0, ' ', 0, 0, 'L');
        $pdf->cell(20, 1, $store->store_address, 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', '', 7);
        $pdf->cell(15, 0, ' ', 0, 0, 'L');
        // $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'L');
        // ============== gwaps ====================================
        if ($store_name == 'ALTURAS MALL') {
            $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00000', 0, 0, 'L');
        } elseif ($store_name == 'ALTURAS TALIBON') {
            $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-002', 0, 0, 'L');
        } elseif ($store_name == 'ISLAND CITY MALL') {
            $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'L');
        } elseif ($store_name == 'ALTURAS TUBIGON') {
            $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00006', 0, 0, 'L');
        } elseif ($store_name == 'ALTA CITTA') {
            $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00009', 0, 0, 'L');
        }
        // ============== gwaps end ================================
        $pdf->ln();
        $pdf->cell(75, 6, ' ', 0, 0, 'L');
        $pdf->SetTextColor(201, 201, 201);
        $pdf->cell(25, 6, ' ', 0, 0, 'L');
        $pdf->cell(90, 5, 'Questions? Contact', 1, 0, 'C', true);
        $pdf->setFont('times', '', 10);
        $pdf->ln();
        $pdf->SetTextColor(201, 201, 201);
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(75, 10, "LESSEE'S INFORMATION", 1, 0, 'C', true);
        $pdf->cell(25, 6, ' ', 0, 0, 'L');
        $pdf->setFont('times', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Multicell(90, 4, $store->contact_person . "\n" . 'Phone: ' . $store->contact_no . "\n" . 'E-mail: ' . $store->email, 1, 'C');
        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);
        # ============ LESSEE INFORMATION ============ #
        $rental_type = $tenant->rental_type;
        $pdf->setFont('times', 'B', 8);
        $pdf->cell(25, 4, 'Trade Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $tenant->trade_name, 'B', 0, 'L');
        $pdf->cell(10, 4, ' ', 0, 0, 'L');
        $pdf->cell(25, 4, 'SOA No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $soa->soa_no, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 4, 'Corp Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $tenant->corporate_name, 'B', 0, 'L');
        $pdf->cell(10, 4, '  ', 0, 0, 'L');
        $pdf->cell(25, 4, 'Date of Transaction', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $soa->date_created, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 4, 'TIN', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $tenant->tin, 'B', 0, 'L');
        $pdf->cell(10, 4, '  ', 0, 0, 'L');
        $pdf->cell(25, 4, 'Billing Period', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $soa->billing_period, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 4, 'Address', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $tenant->address, 'B', 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 8);

        $tenant_id = $tenant->tenant_id;

        if (in_array($tenant_id, ['ICM-LT000008', 'ICM-LT000442', 'ICM-LT000492', 'ICM-LT000035', 'ICM-LT000120'])) {
            $pdf->cell(0, 5, 'Please make all checks payable to ISLAND CITY MALL; BANK:BPI ACCOUNT No. 9471-0019-85', 0, 0, 'R');
        } elseif ($store_name == 'ALTA CITTA') {
            if ($tenant_id == 'ACT-LT000027') {
                $pdf->cell(0, 5, 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION  LBP #5882-111-590', 0, 0, 'R');
            } else {
                $pdf->cell(0, 5, 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION  LBP #5882-111-590', 0, 0, 'R');
            }
        } elseif ($tenant_id == 'ICM-LT000218' || $tenant_id == 'ICM-LT000219') {
            $pdf->cell(0, 5, 'Please make all checks payable to ISLAND CITY MALL; BANK:BPI ACCOUNT No. 9471-0019-85', 0, 0, 'R');
        } else if ($store_name == 'ALTURAS TALIBON') {
            $pdf->cell(0, 5, 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION -  TALIBON or DEPOSIT TO LBP BANK ACCOUNT: 2232117993', 0, 0, 'R');
        } else {
            if ($store_name == 'ALTURAS MALL') {
                $pdf->cell(0, 5, 'Please make all checks payable to ALTURAS SUPERMARKET CORP. MAIN STORE; BANK:PNB ACCOUNT No. 3058-7000-6513', 0, 0, 'R');
            } elseif ($store_name == 'ALTURAS TUBIGON') {
                $pdf->cell(0, 5, 'Please make all checks payable to ASC-Home & Fashion; BANK:PNB ACCOUNT No. 305370004516', 0, 0, 'R');
            } elseif ($store_name == 'PLAZA MARCELA') {
                $pdf->cell(0, 5, 'Please make all checks payable to MFI - PLAZA MARCELA, LB ACCT #0612-0011-11', 0, 0, 'R');
            } elseif ($store_name == 'ISLAND CITY MALL' || $tenant_id != 'ICM-LT000008' || $tenant_id != 'ICM-LT000442' || $tenant_id != 'ICM-LT000492' || $tenant_id != 'ICM-LT000035' || $tenant_id != 'ICM-LT000120') {
                $pdf->cell(0, 5, 'Please make all checks payable to ISLAND CITY MALL; BANK:BPI ACCOUNT No. 9471-0019-85', 0, 0, 'R');
            } else {
                $pdf->cell(0, 5, 'Please make all checks payable to ' . strtoupper($store->store_name) . '', 0, 0, 'R');
            }
        }

        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(0, 5, '__________________________________________________________________________________________________________', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times', 'B', 16);
        $pdf->cell(0, 6, 'Statement of Account', 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->setFont('times', 'B', 12);
        // $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L', TRUE);
        $pdf->cell(95, 6, 'DESCRIPTION', 1, 0, 'C', true);
        $pdf->cell(95, 6, 'AMOUNT', 1, 0, 'C', true);
        $pdf->ln();
        $pdf->SetTextColor(0, 0, 0);

        $date_created = date('Y-m-d', strtotime($soa->date_created));
        $collection_date = date('Y-m-d', strtotime($soa->collection_date));

        // ============ IF HAS PRE-OPERATIONAL ============ //
        if (!empty($soa->preop)) {
            $preop_total = 0;
            $pdf->cell(100, 8, 'Additional/Preoparation Charges', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times', 'B', 10);

            foreach ($soa->preop as $preop) {
                $preop = (object) $preop;
                $preop_desc = '';
                if ($preop->description == 'Security Deposit - Kiosk and Cart' || $preop->description == 'Security Deposit') {
                    $preop_desc = 'Security Deposit';
                } else {
                    $preop_desc = $preop->description;
                }

                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(85, 4, $preop_desc, 0, 0, 'L');
                $pdf->cell(25, 4, 'P ' . number_format($preop->amount, 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->ln();
            }

            $pdf->ln();
            $pdf->setFont('times', '', 10);
            $pdf->cell(10, 4, '     ', 0, 0, 'L');
            $pdf->cell(85, 4, 'Total', 0, 0, 'L');
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(25, 4, 'P ' . number_format($soa->preop_total, 2), 0, 0, 'R');
            $pdf->cell(25, 4, '', 0, 0, 'R');
            $pdf->cell(25, 4, '', 0, 0, 'R');
            $pdf->ln();
        }

        // ============ IF HAS RETRO RENTAL ============ //
        if (!empty($soa->retro)) {
            // Retro Rental
            $pdf->cell(100, 8, 'RETRO RENT', 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont('times', 'B', 10);

            $pdf->setFont('times', '', 10);

            foreach ($soa->retro as $retro) {
                $retro = (object) $retro;

                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(85, 4, 'Previous Balance', 0, 0, 'L');
                $pdf->cell(25, 4, 'P ' . number_format($retro->debit, 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->ln();

                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(85, 4, 'Payment Received', 0, 0, 'L');
                $pdf->setFont('times', 'U', 10);
                $pdf->cell(25, 4, 'P ' . number_format($retro->credit, 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->ln();
                $pdf->setFont('times', '', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(85, 4, 'Balance', 0, 0, 'L');
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(25, 4, 'P ' . number_format($retro->balance, 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->ln();
            }
        }

        // ============ PREVIOUS BALANCES ============ //
        if (!empty($soa->previous)) {
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(100, 8, 'PREVIOUS', 0, 0, 'L');
            $pdf->ln();
            $prev_total = 0;

            foreach ($soa->previous as $date => $prev) {
                $prev = (object) $prev;

                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(30, 4, date('F Y', strtotime($date)), 0, 0, 'L');
                $pdf->setFont('times', '', 10);

                if (!$prev->has_basic) {
                    $pdf->cell(65, 4, '', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P ' . number_format($prev->balance, 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                } else {
                    $pdf->cell(65, 4, '', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P ' . number_format($prev->balance, 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                }

                if (!empty($prev->no_penalty)) {
                    $pdf->cell(10, 4, '     ', 0, 0, 'L');
                    $pdf->cell(30, 4, 'No Penalty Charges', 0, 0, 'L');
                    $pdf->cell(65, 4, '', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P ' . number_format($prev->no_penalty, 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                }

                if (!empty($prev->penalties)) {
                    foreach ($prev->penalties as $key => $penalty) {
                        $penalty = (object) $penalty;

                        $pdf->ln();
                        $pdf->setFont('times', 'B', 10);
                        $pdf->cell(10, 4, '     ', 0, 0, 'L');
                        $pdf->cell(30, 4, 'Penalty:', 0, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(20, 4, '     ', 0, 0, 'L');
                        $pdf->setFont('times', '', 10);


                        $pdf->cell(85, 4, number_format($penalty->penaltyble_amount, 2) . " x $penalty->penalty_percentage% (" . date('F Y', strtotime($date)) . ')', 0, 0, 'L');
                        $pdf->cell(25, 4, number_format($penalty->penalty_amount, 2), 0, 0, 'R');
                        $pdf->cell(25, 4, '', 0, 0, 'R');
                        $pdf->cell(25, 4, '', 0, 0, 'R');
                        $pdf->ln(); // addede by gwaps
                    }
                }

                $prev_total += $prev->total;
            }

            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times', '', '10');
            $pdf->cell(10, 4, '     ', 0, 0, 'L');
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(95, 4, 'Total Previous Amount Payable', 0, 0, 'L');
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(25, 4, 'P ' . number_format($prev_total, 2), 'T', 0, 'R');
            $pdf->cell(25, 4, '', 0, 0, 'R');
            $pdf->cell(25, 4, 'P ' . number_format($prev_total, 2), 0, 0, 'R');
            $pdf->ln();
            $pdf->ln();
        }

        if (!empty($soa->current)) {
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(100, 8, 'CURRENT(' . date('F Y', strtotime($soa->current['date'])) . ')', 0, 0, 'L');

            if (!empty($soa->current['basic'])) {
                $pdf->ln();
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(30, 4, 'Rental', 0, 0, 'L');
                $pdf->setFont('times', '', 10);
                $pdf->ln();

                foreach ($soa->current['basic'] as $key => $basic) {
                    $basic = (object) $basic;

                    foreach ($basic->invoices as $key => $inv) {
                        $inv = (object) $inv;

                        $pdf->cell(20, 4, '     ', 0, 0, 'L');
                        $pdf->cell(85, 4, $inv->description . " $inv->details", 0, 0, 'L');
                        $pdf->cell(25, 4, ($key == 0 ? 'P ' : '') . number_format($inv->amount, 2), 0, 0, 'R');
                        $pdf->cell(25, 4, '', 0, 0, 'R');
                        $pdf->cell(25, 4, '', 0, 0, 'R');
                        $pdf->ln();
                    }

                    if (abs($basic->adj_amount) > 0) {
                        $pdf->ln();
                        $pdf->setFont('times', 'B', 10);
                        $pdf->cell(10, 4, '     ', 0, 0, 'L');
                        $pdf->cell(30, 4, 'Adjustment/s : ', 0, 0, 'L');
                        $pdf->setFont('times', '', 10);
                        $pdf->ln();

                        foreach ($basic->adj_details as $adj) {
                            $pdf->cell(20, 4, '     ', 0, 0, 'L');
                            $pdf->cell(85, 4, $adj->tag == 'Rent Income' ? 'Basic Rent' : $adj->tag, 0, 0, 'L');
                            $pdf->cell(25, 4, ($key == 0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->cell(25, 4, '', 0, 0, 'R');
                            $pdf->cell(25, 4, '', 0, 0, 'R');
                            $pdf->ln();
                        }
                    }

                    $pdf->ln();
                }

                if ($soa->current['basic_adj_amount'] > 0) {
                    $pdf->cell(10, 4, '     ', 0, 0, 'L');
                    $pdf->cell(95, 4, 'Adjustments : ', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P' . number_format($soa->current['basic_adj_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                }

                // $basic_paid_amount = 0;

                if (abs($soa->current['basic_paid_amount']) > 0) {
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(10, 4, '     ', 0, 0, 'L');
                    $pdf->cell(95, 4, 'Payment Received : ', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P' . number_format($soa->current['basic_paid_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');

                    $basic_paid_amount = -1 * $soa->current['basic_paid_amount'];
                }
                // ========== ORIGINAL SUB TOTAL ========== //
                $pdf->ln();
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(95, 4, 'Sub Total', 0, 0, 'L');
                $pdf->setFont('times', '', 10);
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(25, 4, 'P ' . number_format($soa->current['basic_sub_total'], 2), 'T', 0, 'R');
                $pdf->cell(25, 4, 'P ' . number_format($soa->current['basic_sub_total'], 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->ln();
            }

            if (!empty($soa->current['others'])) {
                $pdf->ln();
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(30, 4, 'Add:Other Charges', 0, 0, 'L');
                $pdf->setFont('times', '', 10);
                $pdf->ln();

                foreach ($soa->current['others'] as $key => $other) {
                    $other = (object) $other;

                    foreach ($other->invoices as $key => $inv) {
                        $inv = (object) $inv;

                        switch ($inv->description) {
                            case 'Electricity':
                            case 'Water':
                                $pdf->cell(20, 4, '     ', 0, 0, 'L');
                                $pdf->cell(85, 4, $inv->description, 0, 0, 'L');
                                $pdf->ln();
                                $pdf->setFont('times', '', 8);
                                $pdf->cell(30, 4, '     ', 0, 0, 'L');
                                $pdf->cell(20, 4, 'Present', 0, 0, 'L');
                                $pdf->cell(20, 4, 'Previous', 0, 0, 'L');
                                $pdf->cell(20, 4, 'Consumed', 0, 0, 'L');
                                $pdf->ln();
                                $pdf->cell(30, 4, '     ', 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->curr_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->prev_reading, 2), 0, 0, 'L');
                                $pdf->cell(20, 4, number_format($inv->total_unit, 2), 0, 0, 'L');
                                $pdf->cell(15, 4, ' ', 0, 0, 'L');
                                $pdf->setFont('times', '', 10);
                                $pdf->cell(25, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->cell(25, 4, '', 0, 0, 'R');
                                $pdf->cell(25, 4, '', 0, 0, 'R');
                                $pdf->ln();
                                break;

                            default:
                                $pdf->cell(20, 4, '     ', 0, 0, 'L');
                                $pdf->cell(85, 4, $inv->description, 0, 0, 'L');
                                $pdf->cell(25, 4, number_format($inv->amount, 2), 0, 0, 'R');
                                $pdf->cell(25, 4, '', 0, 0, 'R');
                                $pdf->cell(25, 4, '', 0, 0, 'R');
                                $pdf->ln();
                                break;
                        }
                    }

                    if (abs($other->adj_amount) > 0) {
                        $pdf->ln();
                        $pdf->setFont('times', 'B', 10);
                        $pdf->cell(10, 4, '     ', 0, 0, 'L');
                        $pdf->cell(95, 4, 'Adjustment/s : ', 0, 0, 'L');
                        //$pdf->cell(40, 4, number_format($other->adj_amount, 2), 0, 0, 'R');
                        $pdf->setFont('times', '', 10);
                        $pdf->ln();

                        foreach ($other->adj_details as $adj) {
                            $pdf->cell(20, 4, '     ', 0, 0, 'L');
                            $pdf->cell(85, 4, $adj->tag, 0, 0, 'L');
                            $pdf->cell(25, 4, ($key == 0 ? 'P ' : '') . number_format($adj->amount, 2), 0, 0, 'R');
                            $pdf->cell(25, 4, '', 0, 0, 'R');
                            $pdf->cell(25, 4, '', 0, 0, 'R');
                            $pdf->ln();
                        }
                    }

                    $pdf->ln();
                }

                if ($tenant_id == 'ICM-LT000114') {
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(20, 4, '     ', 0, 0, 'L');
                    $pdf->cell(85, 4, 'Total W/out Withholding Taxes', 0, 0, 'L');
                    $pdf->setFont('times', '', 10);
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(25, 4, 'P ' . number_format($soa->current['other_total_without_ewt'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(20, 4, '     ', 0, 0, 'L');
                    $pdf->cell(85, 4, 'Withholding Taxes', 0, 0, 'L');
                    $pdf->setFont('times', '', 10);
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(25, 4, 'P ' . number_format($soa->current['other_total_ewt'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                    $pdf->ln();
                }

                if (abs($soa->current['other_paid_amount']) > 0) {
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(10, 4, '     ', 0, 0, 'L');
                    $pdf->cell(95, 4, 'Payment Received : ', 0, 0, 'L');
                    $pdf->cell(25, 4, 'P' . number_format($soa->current['other_paid_amount'], 2), 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->ln();
                }

                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(95, 4, 'Sub Total', 0, 0, 'L');
                $pdf->setFont('times', '', 10);
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(25, 4, 'P ' . number_format($soa->current['other_sub_total'], 2), 'T', 0, 'R');
                $pdf->cell(25, 4, 'P ' . number_format($soa->current['other_sub_total'], 2), 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
            }

            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times', 'B', 10);
            $pdf->cell(10, 4, '     ', 0, 0, 'L');
            $pdf->cell(95, 4, 'Total Current Amount Payable', 0, 0, 'L');
            $pdf->setFont('times', 'B', 10);
            $pdf->cell(25, 4, '', 0, 0, 'R');
            $pdf->cell(25, 4, 'P ' . number_format($soa->current['total'], 2), 'T', 0, 'R');
            $pdf->cell(25, 4, 'P ' . number_format($soa->current['total'], 2), 0, 0, 'R');

            $pdf->ln();
            $pdf->ln();
        }

        if (!empty($soa->uri)) {
            $uri = (object) $soa->uri;

            if ($uri->total_uri_amount > 0) {
                if ($basic_paid_amount > 0) {
                    $advance_total = $basic_paid_amount + $uri->total_uri_amount;

                    // $pdf->ln();
                    // $pdf->ln();
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(10, 4, '', 0, 0, 'L');
                    $pdf->cell(95, 4, 'Advance Payment' . ' (' . $uri->date . ')', 0, 0, 'L');
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, 'P ' . number_format($advance_total, 2), 0, 0, 'R');
                    $pdf->ln();
                } else {
                    // $pdf->ln();
                    // $pdf->ln();
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(10, 4, '     ', 0, 0, 'L');
                    $pdf->cell(95, 4, 'Advance Payment' . ' (' . $uri->date . ')', 0, 0, 'L');
                    $pdf->setFont('times', 'B', 10);
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, '', 0, 0, 'R');
                    $pdf->cell(25, 4, 'P ' . number_format($uri->total_uri_amount, 2), 0, 0, 'R');
                    $pdf->ln();
                }
            }
        } else {
            $uri = [];
        }

        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(10, 4, '     ', 0, 0, 'L');
        $pdf->cell(95, 4, 'Total Amount Payable', 0, 0, 'L');
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(25, 4, '', 0, 0, 'R');
        $pdf->cell(25, 4, '', 0, 0, 'R');
        $pdf->cell(25, 4, 'P ' . number_format($soa->net_amount_due, 2), 'T', 0, 'R');

        if ($uri == []) {
        } else {
            if ($uri->remaining > 0) {
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(10, 4, '     ', 0, 0, 'L');
                $pdf->cell(95, 4, 'Remaining Advance Payment', 0, 0, 'L');
                $pdf->setFont('times', 'B', 10);
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, '', 0, 0, 'R');
                $pdf->cell(25, 4, 'P ' . number_format($uri->remaining, 2), 0, 0, 'R');
            }
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times', '', 10);
        $pdf->cell(75, 5, 'Certified: ', 0, 0, 'R');
        $pdf->cell(75, 5, $pdf->Image($inCharge, 80, $pdf->GetY(), 60, 13, 'PNG'), 0, 0, 'C');

        $pdf->ln(10);
        $pdf->setFont('times', '', 8);
        $pdf->cell(38, 4, '     ', 0, 0, 'L');
        $pdf->cell(38, 4, '     ', 0, 0, 'L');
        $pdf->cell(38, 4, 'Corporate Leasing Manager', 0, 0, 'L');
        $pdf->cell(38, 4, '     ', 0, 0, 'L');
        $pdf->cell(38, 4, '     ', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 12);
        $pdf->ln();
        $pdf->cell(190, 1, '         ', 1, 0, 'L', true);
        $pdf->ln();
        $pdf->setFont('times', 'B', 8);
        $pdf->cell(0, 4, 'Note: Presentation of this statement is sufficient notice that the account is due. Interest of 3% will be charged for all past due accounts.', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(45, 4, 'Acknowledgment Certificate No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, ' AC_123_122023_000135', 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Date Issued', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, "December 12, 2023", 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Series Range', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, 'SOA0000001 - SOA9999999', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(0, 4, 'Thank you for your prompt payment!', 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', 'B', 8);
        $pdf->cell(0, 4, 'THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX', 0, 0, 'L');
        // ======================= gwaps ===================================
        $pdf->ln();
        $pdf->setFont('times', '', 8);
        $pdf->cell(24, 4, 'Run Date and Time:', 0, 0, 'L');
        $pdf->cell(38, 4, date('Y-m-d h:i:s A'), 0, 0, 'L');
        // ======================= gwaps end ===============================

        $file_name = $tenant->tenant_id . time() . '.pdf';

        $this->app_model->insert('soa_file', [
            'tenant_id' => $tenant->tenant_id,
            'file_name' => $file_name,
            'soa_no' => $soa->soa_no,
            'billing_period' => $soa->billing_period,
            'amount_payable' => $soa->net_amount_due,
            'posting_date' => $date_created,
            'collection_date' => $collection_date,
            'transaction_date' => getCurrentDate(),
        ]);

        // $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
        // header('Content-Type: application/facilityrental_pdf');

        $pdf->Output('assets/pdf/' . $file_name, 'F');

        return $file_name;
    }
    public function payment()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff') {
            $tenderTypes = json_encode([
                ['id' => 1, 'desc' => 'Cash'],
                ['id' => 2, 'desc' => 'Check'],
                ['id' => 3, 'desc' => 'Bank to Bank'],
                // ['id' => 80, 'desc' => 'JV payment - Business Unit'],
                // ['id' => 81, 'desc' => 'JV payment - Subsidiary'],
                ['id' => 11, 'desc' => 'Unidentified Fund Transfer'],
                // ['id' => 12, 'desc' => 'Internal Payment'],
            ]);

            $data = [
                'current_date' => getCurrentDate(),
                'flashdata' => $this->session->flashdata('message'),
                'expiry_tenants' => $this->app_model->get_expiryTenants(),
                'payee' => $this->app_model->my_store(),
                'store_id' => $this->session->userdata('user_group'),
                'payment_docNo' => $this->app_model->generate_paymentSlipNo(false),
                'title' => 'Payment',
                'tender_types' => $tenderTypes
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/payment');
            $this->load->view('leasing/footer');
        }
    }
    public function preop_transfer()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff') {
            $tenderTypes = json_encode([
                ['id' => 1, 'desc' => 'Cash'],
                ['id' => 2, 'desc' => 'Check'],
                ['id' => 3, 'desc' => 'Bank to Bank']
            ]);

            $data = [
                'current_date' => getCurrentDate(),
                'flashdata' => $this->session->flashdata('message'),
                'expiry_tenants' => $this->app_model->get_expiryTenants(),
                'payee' => $this->app_model->my_store(),
                'store_id' => $this->session->userdata('user_group'),
                'payment_docNo' => $this->app_model->generate_paymentSlipNo(false),
                'title' => 'Payment',
                'tender_types' => $tenderTypes
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/preop_transfer');
            $this->load->view('leasing/footer');
        }
    }
    public function orentry()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff') {
            $data['current_date'] = getCurrentDate();

            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['payee'] = $this->app_model->my_store();
            $data['store_id'] = $this->session->userdata('user_group');

            $data['tender_types'] = json_encode([
                ['id' => 4, 'desc' => 'AR-Employee'],
                ['id' => 1, 'desc' => 'Cash'],
                ['id' => 2, 'desc' => 'Check'],
                ['id' => 3, 'desc' => 'Bank to Bank'],
                ['id' => 80, 'desc' => 'JV payment - Business Unit'],
                ['id' => 81, 'desc' => 'JV payment - Subsidiary'],
                ['id' => 11, 'desc' => 'Unidentified Fund Transfer'],
                ['id' => 12, 'desc' => 'Internal Payment'],
            ]);

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/or_entry');
            $this->load->view('leasing/footer');
        }
    }
    public function getSoaWithBalances($tenant_id, $posting_date)
    {
        $tenant_id = $this->sanitize($tenant_id);
        $posting_date = $this->sanitize($posting_date);

        $date_now = date('Y-m-d');

        if ($date_now < '2021-08-31') {
            $this->prepost_old_soa($tenant_id, $posting_date);
        }

        $soa_docs = $this->app_model->getSoaWithBalances(
            $tenant_id,
            $posting_date
        );

        JSONResponse($soa_docs);
    }
    public function getInvoicesBySoaNo($tenant_id, $soa_no)
    {
        $tenant_id = $this->sanitize($tenant_id);
        $soa_no = $this->sanitize($soa_no);
        $data = $this->app_model->getInvoicesBySoaNo($tenant_id, $soa_no);
        JSONResponse($data);
    }
    public function get_payment_initial_data()
    {
        if ($this->session->userdata('cfs_logged_in')) {
            $store_id = $this->session->userdata('user_group');
            $banks = $this->app_model->get_mycashbank($store_id);
        } else {
            $banks = $this->app_model->getAccreditedBanks();
        }

        $tin_status = 'with';

        $uft_no = $this->app_model->generate_UTFTransactionNo(
            false,
            $tin_status
        );
        $ip_no = $this->app_model->generate_InternalTransactionNo(
            false,
            $tin_status
        );
        $stores = $this->app_model->get_stores();

        JSONResponse(compact('banks', 'uft_no', 'ip_no', 'stores'));
    }
    public function save_payment(){
        /*=====================  SETTING VALUES STARTS HERE ==========================*/
        $tenancy_type       = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name         = $this->sanitize($this->input->post('trade_name'));
        $tenant_id          = $this->sanitize($this->input->post('tenant_id'));
        $contract_no        = $this->sanitize($this->input->post('contract_no'));
        $tenant_address     = $this->sanitize($this->input->post('tenant_address'));
        $payment_date       = $this->sanitize($this->input->post('payment_date'));
        $soa_no             = $this->sanitize($this->input->post('soa_no'));
        $billing_period     = $this->sanitize($this->input->post('billing_period'));
        $remarks            = $this->sanitize($this->input->post('remarks'));
        $tender_typeCode    = $this->sanitize($this->input->post('tender_typeCode'));
        $receipt_no         = $this->sanitize($this->input->post('receipt_no'));
        $amount_paid        = $this->sanitize($this->input->post('amount_paid'));
        $receipt_type       = $this->sanitize($this->input->post('receipt_type'));
        $svi_no             = $this->input->post('svi_no');
        $tender_amount      = $amount_paid;
        // $receipt_no      = $this->app_model->generate_paymentSlipNo();
        $payment_docs       = $this->input->post('payment_docs');
        $transaction_date   = getCurrentDate();
        $payment_date       = date('Y-m-d', strtotime($payment_date));
        //IF NOT INTERNAL PAYMENT
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $bank_name          = $this->sanitize($this->input->post('bank_name'));
        $payor              = $this->sanitize($this->input->post('payor'));
        $payee              = $this->sanitize($this->input->post('payee'));
        //IF INTERNAL PAYMENT
        $ip_store_code      = $this->sanitize($this->input->post('store_code'));
        $ip_store_name      = $this->sanitize($this->input->post('store_name'));
        //IF CHECK
        $check_type         = $this->sanitize($this->input->post('check_type'));
        $bank_code          = $this->sanitize($this->input->post('bank_code'));
        $account_no         = $this->sanitize($this->input->post('account_no'));
        $account_name       = $this->sanitize($this->input->post('account_name'));
        $check_no           = $this->sanitize($this->input->post('check_no'));
        $check_date         = $this->sanitize($this->input->post('check_date'));
        $check_due_date     = $this->sanitize($this->input->post('check_due_date'));
        $expiry_date        = $this->sanitize($this->input->post('expiry_date'));
        $check_class        = $this->sanitize($this->input->post('check_class'));
        $check_category     = $this->sanitize($this->input->post('check_category'));
        $customer_name      = $this->sanitize($this->input->post('customer_name'));
        $check_bank         = $this->sanitize($this->input->post('check_bank'));
        $check_date         = date('Y-m-d', strtotime($check_date));
        $check_due_date     = date('Y-m-d', strtotime($check_due_date));
        $expiry_date        = date('Y-m-d', strtotime($expiry_date));
        /*=====================  SETTING VALUES ENDS HERE ==========================*/

        /*=====================  VALIDATION STARTS HERE ==========================*/
        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('soa_no', 'SOA No.', 'required');
        $this->form_validation->set_rules('billing_period', 'Billing Period', 'required');
        $this->form_validation->set_rules('tender_typeCode', '', 'required|in_list[1,2,3,11,12,80,81]');
        // $this->form_validation->set_rules('receipt_no', 'Reciept No.', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');
        $this->form_validation->set_rules('payment_docs', 'Payment Documents', 'required');
        $this->form_validation->set_rules('svi_no', 'SVI No.', 'required');

        if (in_array($tender_typeCode, [1, 2, 3, 11])) {
            $this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
            $this->form_validation->set_rules('payor', 'Payor', 'required');
            $this->form_validation->set_rules('payee', 'Payee', 'required');
        } else {
            if ($tender_typeCode == 12) {
                $this->form_validation->set_rules('store_code', 'Store Code', 'required');
                $this->form_validation->set_rules('store_name', 'Store Name', 'required');
            }
            $bank_code = null;
            $bank_name = null;
        }
        if ($tender_typeCode == '2') {
            if ($this->session->userdata('cfs_logged_in')) {
                $this->form_validation->set_rules('account_no', 'Account No.', 'required');
                $this->form_validation->set_rules('account_name', 'Account Name', 'required');
                //$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
                $this->form_validation->set_rules('check_class', 'Check Class', 'required');
                $this->form_validation->set_rules('check_category', 'Check Category', 'required');
                $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('check_bank', 'Check Bank', 'required');
            }
            $this->form_validation->set_rules('check_type', 'Check Type', 'required|in_list[DATED CHEC, POST DATED CHECK]');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('check_date', 'Check Date', 'required');

            if (!in_array($check_type, ['DATED CHECK', 'POST DATED CHECK'])) {
                JSONResponse(['type' => 'error', 'msg' => 'Invalid Check Type!']);
            }
            if ($check_type == 'POST DATED CHECK') {
                $this->form_validation->set_rules('check_due_date', 'Check Due Date', 'required');
                if (!validDate($check_due_date)) {
                    JSONResponse(['type' => 'error', 'msg' => 'Check Due Date is not valid!']);
                }
            }
            if ($this->session->userdata('cfs_logged_in') && !validDate($expiry_date)) {
                $expiry_date = '';
                //JSONResponse(['type'=>'error', 'msg'=>'Check Expiry Date is not valid!']);
            }
            if (!validDate($check_date)) {
                JSONResponse(['type' => 'error', 'msg' => 'Check Date is not valid!']);
            }
        }

        if ($this->form_validation->run() == false) {
            JSONResponse(['type' => 'error', 'msg' => validation_errors()]);
        }

        $soa = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if (empty($soa)) {
            JSONResponse(['type' => 'error', 'msg' => 'SOA not found!']);
        }

        $tender_types = [
            '1'     => 'Cash',
            '2'     => 'Check',
            '3'     => 'Bank to Bank',
            '4'     => 'AR-Employee',
            '11'    => 'Unidentified Fund Transfer',
            '12'    => 'Internal Payment',
            '80'    => 'JV payment - Business Unit',
            '81'    => 'JV payment - Subsidiary',
        ];

        $tender_typeDesc = $tender_types[$tender_typeCode];

        if (!in_array($tender_typeCode, [1, 2, 3, 11, 12, 80, 81, 4])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tender Type!']);
        }
        if (in_array($tender_typeCode, ['2', '80', '81', '3'])) {
            $upload = new FileUpload();

            $supp_doc = $upload
                ->validate('supp_doc', 'Supporting Documents')
                ->required()
                ->multiple()
                ->get();

            if ($upload->has_error()) {
                JSONResponse(['type' => 'error', 'msg' => $upload->get_errors('<br>')]);
            }
        }
        if (in_array($tender_typeCode, ['1', '2', '80', '81', '4'])) {
            if ($this->app_model->checkPaymentReceiptExistence($receipt_no)) {
                // JSONResponse(['type'=>'error', 'msg'=>'Payment Slip already used!']);
            }
        }
        if (!validDate($payment_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Payment Date is not valid!']);
        }
        if (empty($payment_docs) || !is_array($payment_docs)) {
            JSONResponse(['type' => 'error', 'msg' => 'Payment documents are required!']);
        }

        $total_payable = 0;
        foreach ($payment_docs as $key => $doc) {
            $payment_docs[$key]['amount_paid'] = 0;
            $doc = (object) $doc;
            $total_payable += $doc->balance;
        }

        $balance = round(
            $total_payable > $amount_paid ? $total_payable - $amount_paid : 0,
            2
        );

        if ($total_payable < 1) {
            JSONResponse(['type' => 'error', 'msg' => 'Total Payable amount can\'t be 0.00']);
        }
        if ($amount_paid <= 0) {
            JSONResponse(['type' => 'error', 'msg' => 'Amount paid can\'t be 0.00']);
        }

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);

        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);
            $soa_display['store'] = $store;

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.']);
        }

        /*=====================  VALIDATION ENDS HERE ==========================*/

        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $tinStatus = ['ON PROCESS', 'NO TIN', '', null, '000-000-000', '000-000-000-000'];

        //SET UFT OR IP PAYMENT DOC. NO.
        switch ($tender_typeCode) {
            case '11':
                $receipt_no = $this->app_model->generate_UTFTransactionNo(false, $tin_status);
                break;
            case '12':
                $receipt_no = $this->app_model->generate_InternalTransactionNo(false, $tin_status);
                break;
            default:
                $receipt_no;
                break;
        }

        /*
        $tender_types = [
            '1'     =>'Cash', 
            '2'     =>'Check', 
            '3'     =>'Bank to Bank', 
            '80'    =>'JV payment - Business Unit',
            '81'    =>'JV payment - Subsidiary',
            '11'    => 'Unidentified Fund Transfer',
            '12'    =>'Internal Payment'
        ];*/

        $this->db->trans_start();

        //APPLY PAYMENT TO INVOICES
        foreach ($payment_docs as $key => $doc) {
            $doc = (object) $doc;
            $svi_value = isset($svi_no[$doc->doc_no]) ? $svi_no[$doc->doc_no] : ''; // added by gwaps

            if ($amount_paid <= 0) {
                break;
            }

            $doc_payment_amt = round($amount_paid > $doc->balance ? $doc->balance : $amount_paid, 2);
            $amount_paid = round($amount_paid - $doc_payment_amt, 2);

            $payment_docs[$key]['amount_paid'] = $doc_payment_amt;

            //IF PREOP CHARGES GENERATE GLREFERENCE NO. AND SET CORRESPONDING GL ACCOUNT
            $preOpTag = '';
            if ($doc->document_type == 'Preop-Charges' || empty($doc->gl_accountID)) {
                $doc->ref_no = $this->app_model->gl_refNo(false, false);

                switch ($doc->description) {
                    case 'Security Deposit - Kiosk and Cart':
                    case 'Security Deposit':
                        $doc->gl_accountID = 9;
                        break;
                    case 'Construction Bond':
                        $doc->gl_accountID = 8;
                        break;
                    default:
                        $doc->gl_accountID = 7;
                        break;
                }

                $preop_balance = round($doc->balance - $doc_payment_amt, 2);

                $this->app_model->update(['amount' => $preop_balance], $doc->id, 'tmp_preoperationcharges');

                $preOpTag = 'Preop';
            }

            $sl_data = [];
            $gl_code = '';
            $debit_status = null;
            $credit_status = null;
            $ft_ref = null;
            $due_date = null;

            if ($tender_typeCode == 1 || $tender_typeCode == 3) { //CASH | BANK TO BANK
                $gl_code = '10.10.01.01.02';
            } elseif ($tender_typeCode == 2) { //CHECK
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';
                if ($check_type == 'POST DATED CHECK') {
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                    $due_date = $check_due_date;
                }
            } elseif ($tender_typeCode == 80) { //JV payment - Business Unit
                $gl_code = $this->app_model->bu_entry();
            } elseif ($tender_typeCode == 81) { //JV payment - Subsidiary
                $gl_code = '10.10.01.03.11';
            } elseif ($tender_typeCode == 11) { //UFT
                $gl_code = '10.10.01.01.02';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                switch ($doc->gl_accountID) {
                    case '9':
                    case '8':
                    case '7':
                        $credit_status = 'Preop Clearing';
                        break;
                    case '4':
                        $credit_status = 'RR Clearing';
                        break;
                    default:
                        $credit_status = 'AR Clearing';
                        break;
                }
            } elseif ($tender_typeCode == 4) { //INTERNAL PAYMENT
                //AR - EMPLOYEE
                $gl_code = '10.10.01.03.01.03';
            } else {
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                $gl_code = '10.10.01.03.04';
                $debit_status = $ip_store_name;
                $credit_status = 'ARNTI';
            }

            $cas_doc_no = $doc->cas_doc_no;

            $sl_data['debit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $doc->ref_no,
                'doc_no' => $receipt_no,
                'svi_no' => $svi_value,// added by gwaps
                'cas_doc_no' => $cas_doc_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($gl_code),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => $doc_payment_amt,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $debit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
                'tag' => $preOpTag,
            ];
            $sl_data['credit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $doc->ref_no,
                'doc_no' => $receipt_no,
                'svi_no' => $svi_value,// added by gwaps
                'cas_doc_no' => $cas_doc_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $doc->gl_accountID,
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'credit' => -1 * $doc_payment_amt,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $credit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
                'tag' => $preOpTag,
            ];

            foreach ($sl_data as $key => $data) { //original
                $this->db->insert('general_ledger', $data);
                // echo $this->db->_error_message();
                $this->db->insert('subsidiary_ledger', $data);
                // echo $this->db->_error_message();
            }

            if ($doc->document_type != 'Preop-Charges') {
                $inv = $this->app_model->getLedgerFirstResultByDocNo($tenant_id, $doc->doc_no);
                if (!empty($inv)) {
                    $ledger_data = [
                        'posting_date' => $payment_date,
                        'document_type' => 'Payment',
                        'ref_no' => $inv->ref_no,
                        'doc_no' => $receipt_no,
                        'tenant_id' => $tenant_id,
                        'contract_no' => $contract_no,
                        'description' => $inv->description,
                        'debit' => $doc_payment_amt,
                        'balance' => 0,
                    ];
                    $this->app_model->insert('ledger', $ledger_data);
                }
            }

            // For Accountability Report
            if (in_array($tender_typeCode, ['1', '2', '3', '80', '81', '4']) && in_array($doc->gl_accountID, ['22', '29', '4', '9', '8', '7', '33',]) && $this->session->userdata('cfs_logged_in')) {
                switch ($doc->gl_accountID) {
                    case '22':
                    case '29':
                        $gl_account_desc = 'Account Receivable';
                        break;
                    case '4':
                        $gl_account_desc = 'Rent Receivable';
                        break;
                    case '9':
                        $gl_account_desc = 'Security Deposit';
                        break;
                    case '8':
                        $gl_account_desc = 'Construction Bond';
                        break;
                    case '33':
                        $gl_account_desc = 'AR-Employee';
                        break;
                    default:
                        $gl_account_desc = 'Advance Deposit';
                }

                $this->app_model->insert_accReport($tenant_id, $gl_account_desc, $doc_payment_amt, $payment_date, $tender_typeDesc);
            }
        }

        //INSERT URI IF HAS ADVANCE
        $advance_amount = $amount_paid;
        if ($advance_amount > 0) {
            $sl_data = [];
            $gl_code = '';
            $ft_ref = null;
            $debit_status = null;
            $credit_status = null;
            $uri_ref_no = $this->app_model->gl_refNo(false, false);
            $due_date = null;

            if ($tender_typeCode == 1 || $tender_typeCode == 3) { //CASH | BANK TO BANK
                $gl_code = '10.10.01.01.02';
            } elseif ($tender_typeCode == 2) { //CHECK
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';
                if ($check_type == 'POST DATED CHECK') {
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                    $due_date = $check_due_date;
                }
            } elseif ($tender_typeCode == 80) { //JV payment - Business Unit
                $gl_code = $this->app_model->bu_entry();
            } elseif ($tender_typeCode == 81) { //JV payment - Subsidiary
                $gl_code = '10.10.01.03.11';
            } elseif ($tender_typeCode == 11) { //UFT
                $gl_code = '10.10.01.01.02';
                $credit_status = 'URI Clearing';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
            } else { //INTERNAL PAYMENT
                $gl_code = '10.10.01.03.04';
                $debit_status = $ip_store_name;
                $credit_status = 'ARNTI';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
            }

            $sl_data['debit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($gl_code),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $debit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];
            $sl_data['credit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID('10.20.01.01.02.01'),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'credit' => -1 * $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $credit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];

            foreach ($sl_data as $key => $data) {
                // var_dump('testing2:',$data);
                // die();
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            // For Montly Receivable Report
            $mon_rec_report_data = [
                'tenant_id' => $tenant_id,
                'doc_no' => $receipt_no,
                'posting_date' => $payment_date,
                'description' => 'Advance Payment',
                'amount' => $advance_amount,
            ];

            $this->app_model->insert('monthly_receivable_report', $mon_rec_report_data);

            $ledger_data = [
                'posting_date' => $payment_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Advance Payment',
                'doc_no' => $receipt_no,
                'ref_no' => $this->app_model->generate_refNo(false, false),
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'description' => 'Advance Payment-' . $trade_name,
                'debit' => $advance_amount,
                'credit' => 0,
                'balance' => $advance_amount,
            ];

            $this->app_model->insert('ledger', $ledger_data);

            // For Accountability Report
            if (in_array($tender_typeCode, ['1', '2', '3', '80', '81']) && $this->session->userdata('cfs_logged_in')) {
                $this->app_model->insert_accReport(
                    $tenant_id,
                    'Advance Deposit',
                    $advance_amount,
                    $payment_date,
                    $tender_typeDesc
                );
            }
        }

        //SAVE SUPPORTING DOCUMENT
        if (in_array($tender_typeCode, ['2', '80', '81', '3'])) {
            $targetPath = getcwd() . '/assets/payment_docs/';

            foreach ($supp_doc as $key => $supp) {
                //Setup our new file path
                $filename = $tenant_id . time() . $supp['name'];
                move_uploaded_file($supp['tmp_name'], $targetPath . $filename);

                $supp_doc_data = [
                    'tenant_id' => $tenant_id,
                    'file_name' => $filename,
                    'receipt_no' => $receipt_no,
                ];

                $this->db->insert('payment_supportingdocs', $supp_doc_data);
            }
        }

        //INSERT TO PAYMENT SCHEME
        if (in_array($tender_typeCode, ['1', '2', '3', '80', '81', '4'])) {
            $check_date = $tender_typeCode == 2 ? $check_date : null;

            $pmt_display = (object) compact(
                'receipt_type',
                'tenant',
                'store',
                'receipt_no',
                'soa_no',
                'payment_date',
                'remarks',
                'total_payable',
                'payment_docs',
                'payment_date',
                'tender_typeCode',
                'tender_typeDesc',
                'check_type',
                'bank_code',
                'bank_name',
                'tender_amount',
                'check_no',
                'balance',
                'check_due_date',
                'check_date',
                'advance_amount',
                'payor',
                'payee'
            );

            $payment_report = $this->createPaymentDocsFile($pmt_display);

            /*$check_date = ($tender_typeCode == 2 ?
                ($check_type == 'POST DATED CHECK'? $check_due_date : $payment_date) 
            : null);*/

            $paymentScheme = [
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'tenancy_type' => $tenancy_type,
                'receipt_no' => $receipt_no,
                'tender_typeCode' => $tender_typeCode,
                'tender_typeDesc' => $tender_typeDesc,
                'soa_no' => $soa_no,
                'billing_period' => $billing_period,
                'amount_due' => $total_payable,
                'amount_paid' => $tender_amount,
                'bank' => $bank_name,
                'check_no' => $tender_typeCode == 2 || $tender_typeCode == 3 ? $check_no : null,
                'check_date' => $check_date,
                'payor' => $payor,
                'payee' => $payee,
                'receipt_doc' => $payment_report,
                'rec_amount_paid' => $tender_amount,
                'status' => 'NO OR',
            ];
            $this->db->insert('payment_scheme', $paymentScheme);

            /*======================  CCM DATA =================================== */
            if ($tender_typeCode == '2' && $this->session->userdata('cfs_logged_in')) {
                $this->load->model('ccm_model');
                $customer_id = $this->ccm_model->check_customer($customer_name);
                $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();

                $ccm_data = [
                    'checksreceivingtransaction_id' => $checksreceivingtransaction_id,
                    'customer_id' => $customer_id,
                    'businessunit_id' => $this->ccm_model->get_BU(),
                    'department_from' => '12',
                    'leasing_docno' => $receipt_no,
                    'check_no' => $check_no,
                    'check_class' => $check_class,
                    'check_category' => $check_category,
                    'check_expiry' => $expiry_date,
                    'check_date' => $check_date,
                    'check_received' => $transaction_date,
                    'check_type' => $check_type,
                    'account_no' => $account_no,
                    'account_name' => $account_name,
                    'bank_id' => $check_bank,
                    'check_amount' => $tender_amount,
                    'currency_id' => '1',
                    'check_status' => 'PENDING',
                ];
                $this->ccm_model->insert('checks', $ccm_data);
            }
            /*========================   CCM DATA ===================================*/
        }

        //$last_soa = $this->app_model->getLastestSOA($tenant_id, $payment_date);

        //INSERT TO PAYMENT
        $paymentData = [
            'posting_date' => $payment_date,
            'soa_no' => $soa_no,
            'amount_paid' => $tender_amount,
            'tenant_id' => $tenant_id,
            'doc_no' => $receipt_no,
            'rec_amount_paid' => $tender_amount,
        ];

        $this->db->insert('payment', $paymentData);

        //INSERT UFT
        if ($tender_typeCode == '11') {
            $data_utf = [
                'tenant_id' => $tenant_id,
                'bank_code' => $bank_code,
                'bank_name' => $bank_name,
                'posting_date' => $payment_date,
                'amount_payable' => $total_payable,
                'amount_paid' => $tender_amount,
            ];
            $this->db->insert('uft_payment', $data_utf);
        }

        //CHECK IF DELAYED PAYMENT
        if (!$this->app_model->is_penaltyExempt($tenant_id) && $soa->billing_period != 'Upon Signing of Notice' && !$this->DISABLE_PENALTY) {
            $collection_date = $soa->collection_date;

            if (date('Y-m-d', strtotime($payment_date)) > date('Y-m-d', strtotime($collection_date . '+ 1 day'))) {
                $daysOfMonth = date('t', strtotime($payment_date));
                $daydiff = floor(
                    abs(
                        strtotime($payment_date . '- 1 days') -
                        strtotime($collection_date)
                    ) /
                    (60 * 60 * 24)
                );

                $sundays = $this->app_model->get_sundays($collection_date, $payment_date);
                $daydiff = $daydiff - $sundays;
                $penalty_latepayment = ($tender_amount * 0.02 * $daydiff) / $daysOfMonth;

                $penaltyEntry = [
                    'tenant_id' => $tenant_id,
                    'posting_date' => $payment_date,
                    'contract_no' => $contract_no,
                    'doc_no' => $receipt_no,
                    'description' => 'Late Payment-' . $trade_name,
                    'amount' => round($penalty_latepayment, 2),
                ];
                $this->db->insert('tmp_latepaymentpenalty', $penaltyEntry);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->app_model->insert('error_log', ['action' => 'Saving Payment', 'error_msg' => $this->db->_error_message()]);

            JSONResponse(['type' => 'error', 'msg' => 'Something went wrong while posting payment!']);
        }

        // ['1' => 'Cash', '2' => 'Check', '3' => 'Bank to Bank', '80' => 'JV payment - Business Unit', '81' => 'JV payment - Subsidiary', '4' => 'AR-Employee']
        if (in_array($tender_typeCode, ['1', '2', '3', '80', '81', '4'])) {
            $this->generate_paymentCollection($tenant_id, $payment_date);
            JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!', 'file' => $payment_report]);
        }
        JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!',]);
    }
    #PAYMENT PDF
    function createPaymentDocsFile($pmt)
    {
        // dump($pmt);
        // exit();
        $pdf = new FPDF('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->setDisplayMode('fullpage');
        $pdf->setFont('times', 'B', 12);

        $logoPath = getcwd() . '/assets/other_img/';
        $store = $pmt->store;
        $tenant = $pmt->tenant;
        $details = $this->app_model->get_tenant_details_2($tenant->tenant_id);
        $docno = '';
        $tin_status = 'with';

        $pdf->cell(20, 20, $pdf->Image($logoPath . $store->logo, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
        $pdf->ln();
        $pdf->setFont('times', 'B', 14);
        $pdf->cell(190, 10, strtoupper($store->store_name), 0, 0, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        $pdf->cell(190, 5, 'Owned & Managed by Alturas Supermarket Corporation', 0, 0, 'C');
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        $pdf->cell(190, 5, $store->store_address, 0, 0, 'C');
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        // $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'C');
        // ============== gwaps ====================================
        $store_name = $store->store_name;
        if ($store_name == 'ALTURAS MALL') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00000', 0, 0, 'C');
        } elseif ($store_name == 'ALTURAS TALIBON') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-002', 0, 0, 'C');
        } elseif ($store_name == 'ISLAND CITY MALL') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'C');
        } elseif ($store_name == 'ALTURAS TUBIGON') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00006', 0, 0, 'C');
        } elseif ($store_name == 'ALTA CITTA') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00009', 0, 0, 'C');
        }
        // ============== gwaps end ================================
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 16);
        $pdf->cell(0, 6, 'PAYMENT SLIP', 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 9);
        $pdf->cell(25, 5, 'PS No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->receipt_no, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'SOA No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->soa_no, 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, 'Trade Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->trade_name, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'Payment Date', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->payment_date, 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, 'Corporate Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->corporate_name, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'Total Payable', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, number_format($pmt->total_payable, 2), 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, 'TIN', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->tin, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 5, 'Address', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->address, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 8);

        $icm = 'Please make all checks payable to ISLAND CITY MALL; BANK:BPI ACCOUNT No. 9471-0019-85';
        $am = 'Please make all checks payable to ALTURAS SUPERMARKET CORP. MAIN STORE; BANK:PNB ACCOUNT No. 3058-7000-6513';
        $act = 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION  LBP #5882-111-590';
        $tubigon = 'Please make all checks payable to ASC-Home & Fashion; BANK:PNB ACCOUNT No. 305370004516';
        $asct = 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION -  TALIBON or DEPOSIT TO LBP BANK ACCOUNT: 2232117993';

        if ($tenant->tenant_id == 'ICM-LT000008' || $tenant->tenant_id == 'ICM-LT000442' || $tenant->tenant_id == 'ICM-LT000492' || $tenant->tenant_id == 'ICM-LT000035' || $tenant->tenant_id == 'ICM-LT000120') {
            $pdf->cell(0, 5, $icm, 0, 0, 'R');
        } elseif ($store_name == 'ALTA CITTA') {
            if ($tenant->tenant_id == 'ACT-LT000027') {
                $pdf->cell(0, 5, $act, 0, 0, 'R');
            } else {
                $pdf->cell(0, 5, $act, 0, 0, 'R');
            }
        } elseif ($tenant->tenant_id == 'ICM-LT000218' || $tenant->tenant_id == 'ICM-LT000219') {
            $pdf->cell(0, 5, $icm, 0, 0, 'R');
        } else if ($store_name == 'ALTURAS TALIBON') {
            $pdf->cell(0, 5, $asct, 0, 0, 'R');
        } else {
            if ($store_name == 'ALTURAS MALL') {
                $pdf->cell(0, 5, $am, 0, 0, 'R');
            } elseif ($store_name == 'ALTURAS TUBIGON') {
                $pdf->cell(0, 5, $tubigon, 0, 0, 'R');
            } elseif ($store_name == 'PLAZA MARCELA') {
                // $pdf->cell(0, 5, 'Please make all checks payable to MFI - PLAZA MARCELA, LB ACCT #0612-0011-11', 0, 0, 'R');
            } elseif ($store_name == 'ISLAND CITY MALL' || $tenant->tenant_id != 'ICM-LT000008' || $tenant->tenant_id != 'ICM-LT000442' || $tenant->tenant_id != 'ICM-LT000492' || $tenant->tenant_id != 'ICM-LT000035' || $tenant->tenant_id != 'ICM-LT000120') {
                $pdf->cell(0, 5, $icm, 0, 0, 'R');
            } else {
                $pdf->cell(0, 5, 'Please make all checks payable to ' . strtoupper($store->store_name) . '', 0, 0, 'R');
            }
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        // =================== Receipt Charges Table ============= //
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(120, 8, 'Description', 0, 0, 'L');
        $pdf->cell(30, 8, 'Total Amount Due', 0, 0, 'C');
        $pdf->setFont('times', '', 10);

        foreach ($pmt->payment_docs as $key => $doc) {
            $doc = (object) $doc;
            #'Preop-' .
            $pdf->ln();
            $pdf->cell(30, 5, $doc->document_type == 'Preop-Charges' ? $doc->description : $doc->tag, 0, 0, 'L');
            $pdf->cell(30, 5, ' - ' . date('M Y', strtotime($doc->posting_date)), 0, 0, 'L');
            $pdf->cell(60, 5, '', 0, 0, 'R');
            $pdf->cell(30, 5, number_format($doc->balance, 2), 0, 0, 'R');
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(30, 5, 'Total Payable', 0, 0, 'L');
        $pdf->setFont('times', '', 10);
        $pdf->cell(30, 5, '', 0, 0, 'L');
        $pdf->cell(60, 5, '', 0, 0, 'R');
        $pdf->cell(30, 5, 'P ' . number_format($pmt->total_payable, 2), 'T', 0, 'R');
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(30, 5, 'Amount Paid', 0, 0, 'L');
        $pdf->setFont('times', '', 10);
        $pdf->cell(30, 5, '', 0, 0, 'L');
        $pdf->cell(60, 5, '', 0, 0, 'R');
        $pdf->cell(30, 5, 'P ' . number_format($pmt->tender_amount, 2), 'B', 0, 'R');
        $pdf->ln();

        $totalDue = $pmt->total_payable - $pmt->tender_amount;

        $pdf->setFont('times', 'B', 10);
        $pdf->cell(30, 5, 'Amount Still Due', 0, 0, 'L');
        $pdf->setFont('times', '', 10);
        $pdf->cell(30, 5, '', 0, 0, 'L');
        $pdf->cell(60, 5, '', 0, 0, 'R');
        $pdf->cell(30, 5, 'P ' . number_format($totalDue, 2), 0, 0, 'R');

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(190, 8, 'Payment Scheme: ', 0, 0, 'L');
        $pdf->ln();

        $pdf->setFont('times', '', 9);
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Description', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->tender_typeCode != 2 ? $pmt->tender_typeDesc : ucwords($pmt->check_type), 'B', 0, 'L');
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Total Payable', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format($pmt->total_payable, 2), 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Bank', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, in_array($pmt->tender_typeCode, [1, 2, 3, 11]) ? $pmt->bank_name : 'N/A', 'B', 0, 'L');
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Amount Paid', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format($pmt->tender_amount, 2), 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Check Number', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->tender_typeCode == 2 ? $pmt->check_no : 'N/A', 'B', 0, 'L');
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Balance', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format($pmt->balance, 2), 'B', 0, 'L');

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');

        $pdf->cell(30, 4, 'Check Date', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->tender_typeCode == 2 ? $pmt->check_date : 'N/A', 'B', 0, 'L');
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Advance', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format($pmt->advance_amount, 2), 'B', 0, 'L');

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Check Due Date', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->tender_typeCode == 2 && $pmt->check_type == 'POST DATED CHECK' ? $pmt->check_due_date : 'N/A', 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Payor', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payor, 'B', 0, 'L');

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Payee', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payee, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '', 0, 0, 'L');

        $pdf->cell(30, 4, 'Document #', 0, 0, 'L'); # OR or AR
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->receipt_no, 'B', 0, 'L');
        // $pdf->ln();
        // $pdf->cell(20, 4, "     ", 0, 0, 'L');
        // $pdf->cell(30, 4, "OR #: ", 0, 0, 'L'); # OR or AR
        // $pdf->cell(60, 4, $pmt['receipt_no'], 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', '', 10);
        $pdf->cell(95, 4, 'Prepared By: _____________________', 0, 0, 'C');
        $pdf->cell(95, 4, 'Checked By:______________________', 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(45, 4, 'Acknowledgment Certificate No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, ' AC_123_122023_000135', 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Date Issued', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, "December 12, 2023", 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Series Range', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, 'PS0000001 - PS9999999', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(0, 4, 'THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX', 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(0, 4, 'THIS IS NOT AN OFFICIAL RECEIPT', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(0, 4, 'Thank you for your prompt payment!', 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', '', 9);
        $pdf->cell(25, 4, 'Run Date and Time', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, date('Y-m-d h:m:s A'), 0, 0, 'L');

        $file_name = $tenant->tenant_id . time() . '.pdf';
        $pdf->Output('assets/pdf/' . $file_name, 'F');
        return $file_name;
    }
    public function preop_payment()
    {
        $data['payment_docNo'] = $this->app_model->payment_docNo(false, 'with');
        $data['current_date'] = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['payee'] = $this->app_model->my_store();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/preop_payment');
        $this->load->view('leasing/footer');
    }
    public function get_preop_balance($tenant_id = '', $gl_account = '')
    {
        $tenant_id = str_replace('%20', ' ', $tenant_id);
        $gl_account = str_replace('%20', ' ', $gl_account);

        JSONResponse(
            $this->app_model->get_preop_balance($tenant_id, $gl_account)
        );
    }
    public function getPreopPaymentInvoicesBySoaNo($tenant_id, $soa_no)
    {
        $tenant_id = $this->sanitize($tenant_id);
        $soa_no = $this->sanitize($soa_no);

        $data = $this->app_model->getInvoicesBySoaNo(
            $tenant_id,
            $soa_no,
            'preop_payment'
        );

        JSONResponse($data);
    }
    public function save_paymentUsingPreop()
    {
        /*=====================  SETTING VALUES STARTS HERE ==========================*/
        //$receipt_no         = $this->sanitize($this->input->post('receipt_no'));
        $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name = $this->sanitize($this->input->post('trade_name'));
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $contract_no = $this->sanitize($this->input->post('contract_no'));
        $tenant_address = $this->sanitize($this->input->post('tenant_address'));
        $payee = $this->sanitize($this->input->post('payee'));

        $payment_date = $this->sanitize($this->input->post('payment_date'));
        $soa_no = $this->sanitize($this->input->post('soa_no'));
        $billing_period = $this->sanitize($this->input->post('billing_period'));
        $remarks = $this->sanitize($this->input->post('remarks'));

        $tender_typeDesc = $this->sanitize(
            $this->input->post('tender_typeDesc')
        );
        $amount_paid = $this->sanitize($this->input->post('amount_paid'));
        $tender_amount = $amount_paid;

        $payment_docs = $this->input->post('payment_docs');

        $transaction_date = getCurrentDate();
        $payment_date = date('Y-m-d', strtotime($payment_date));

        $receipt_type = 'Document Number';

        /*=====================  SETTING VALUES ENDS HERE ==========================*/

        /*=====================  VALIDATION STARTS HERE ==========================*/

        $this->form_validation->set_rules(
            'tenancy_type',
            'Tenancy Type',
            'required|in_list[Short Term Tenant,Long Term Tenant]'
        );
        $this->form_validation->set_rules(
            'trade_name',
            'Trade Name',
            'required'
        );
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules(
            'contract_no',
            'Contract No.',
            'required'
        );
        $this->form_validation->set_rules(
            'tenant_address',
            'Tenant Address',
            'required'
        );
        $this->form_validation->set_rules(
            'payment_date',
            'Payment Date',
            'required'
        );
        $this->form_validation->set_rules('soa_no', 'Trade Name', 'required');
        $this->form_validation->set_rules(
            'billing_period',
            'Billing Period',
            'required'
        );
        $this->form_validation->set_rules(
            'tender_typeDesc',
            'Tender Type Description',
            'required|in_list[Security Deposit,Construction Bond]'
        );
        $this->form_validation->set_rules(
            'amount_paid',
            'Amount Paid',
            'required|numeric'
        );
        $this->form_validation->set_rules(
            'payment_docs',
            'Payment Documents',
            'required'
        );
        $this->form_validation->set_rules('payee', 'Payee', 'required');

        if ($this->form_validation->run() == false) {
            JSONResponse(['type' => 'error', 'msg' => validation_errors()]);
        }

        $soa = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if (empty($soa)) {
            JSONResponse(['type' => 'error', 'msg' => 'SOA not found!']);
        }

        if (!validDate($payment_date)) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Payment Date is not valid!',
            ]);
        }

        if (empty($payment_docs) || !is_array($payment_docs)) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Payment documents are required!',
            ]);
        }

        $total_payable = 0;
        foreach ($payment_docs as $key => $doc) {
            $payment_docs[$key]['amount_paid'] = 0;
            $doc = (object) $doc;
            $total_payable += $doc->balance;
        }

        $balance = round(
            $total_payable > $amount_paid ? $total_payable - $amount_paid : 0,
            2
        );

        if ($total_payable < 1) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Total Payable amount can\'t be 0.00',
            ]);
        }

        if ($amount_paid <= 0) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Amount paint can\'t be 0.00',
            ]);
        }

        if ($amount_paid > $total_payable) {
            JSONResponse([
                'type' => 'error',
                'msg' =>
                "Amount Paid can't be greater than Total Payable Amount!",
            ]);
        }

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);
        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);
            $soa_display['store'] = $store;

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Invalid Tenant! Tenant might be terminated.',
            ]);
        }

        $preop_total = $this->app_model->get_preop_balance(
            $tenant_id,
            $tender_typeDesc
        );

        if (empty($preop_total->balance)) {
            JSONResponse([
                'type' => 'error',
                'msg' => "No $tender_typeDesc available.",
            ]);
        }

        if ($amount_paid > $preop_total->balance) {
            JSONResponse([
                'type' => 'error',
                'msg' => "Amount Paid can't be greater than $tender_typeDesc amount",
            ]);
        }

        /*=====================  VALIDATION ENDS HERE ==========================*/

        $this->db->trans_start();

        // $receipt_no = $this->app_model->payment_docNo(true);
        $receipt_no = '';

        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';
        $receipt_no = $this->app_model->payment_docNo(
            false,
            $tin_status
        );

        $preops = $this->app_model->get_preops_with_balance(
            $tenant_id,
            $tender_typeDesc
        );

        //APPLY PAYMENT TO INVOICES
        foreach ($payment_docs as $key => $doc) {
            $doc = (object) $doc;

            if ($amount_paid <= 0) {
                break;
            }

            $inv_balance = $doc->balance > $amount_paid ? $amount_paid : $doc->balance;

            foreach ($preops as $prekey => $preop) {
                if ($inv_balance <= 0) {
                    break;
                }
                if ($preop->balance <= 0) {
                    continue;
                }

                $doc_payment_amt = $preop->balance >= $inv_balance ? $inv_balance
                    : $preop->balance;
                $preop->balance -= $doc_payment_amt;
                $inv_balance -= $doc_payment_amt;

                $amount_paid = round($amount_paid - $doc_payment_amt, 2);

                $payment_docs[$key]['amount_paid'] += $doc_payment_amt;

                $sl_data = [];

                $cas_doc_no = $doc->cas_doc_no;

                $sl_data['debit'] = [
                    'posting_date' => $payment_date,
                    'transaction_date' => $transaction_date,
                    'document_type' => 'Payment',
                    'ref_no' => $preop->ref_no,
                    'doc_no' => $receipt_no,
                    'cas_doc_no' => $cas_doc_no,
                    'tenant_id' => $tenant_id,
                    'gl_accountID' => $preop->gl_accountID,
                    'company_code' => $store->company_code,
                    'department_code' => $store->dept_code,
                    'debit' => $doc_payment_amt,
                    'prepared_by' => $this->session->userdata('id'),
                ];

                $sl_data['credit'] = [
                    'posting_date' => $payment_date,
                    'transaction_date' => $transaction_date,
                    'document_type' => 'Payment',
                    'ref_no' => $doc->ref_no,
                    'doc_no' => $receipt_no,
                    'cas_doc_no' => $cas_doc_no,
                    'tenant_id' => $tenant_id,
                    'gl_accountID' => $doc->gl_accountID,
                    'company_code' => $store->company_code,
                    'department_code' => $store->dept_code,
                    'credit' => -1 * $doc_payment_amt,
                    'prepared_by' => $this->session->userdata('id'),
                ];

                foreach ($sl_data as $data) {
                    $this->db->insert('general_ledger', $data);
                    $this->db->insert('subsidiary_ledger', $data);
                }

                //SAVE TO LEDGER
                $inv = $this->app_model->getLedgerFirstResultByDocNo(
                    $tenant_id,
                    $doc->doc_no
                );
                if (!empty($inv)) {
                    $ledger_data = [
                        'posting_date' => $payment_date,
                        'document_type' => 'Payment',
                        'ref_no' => $inv->ref_no,
                        'doc_no' => $receipt_no,
                        'tenant_id' => $tenant_id,
                        'contract_no' => $contract_no,
                        'description' => $inv->description,
                        'debit' => $doc_payment_amt,
                        'balance' => 0,
                    ];

                    $this->app_model->insert('ledger', $ledger_data);
                }
            }
        }

        //SETTING TO DATA TO AVOID ERRORS IN createPaymentDocsFile METHOD
        $tender_typeCode = null;
        $check_type = null;
        $bank_code = null;
        $bank_name = null;
        $check_no = null;
        $check_due_date = null;
        $advance_amount = 0;
        $payor = $trade_name;

        /*============= SAVE TO PAYMENT SCHEME ============ */
        $pmt_display = (object) compact(
            'receipt_type',
            'tenant',
            'store',
            'receipt_no',
            'soa_no',
            'payment_date',
            'remarks',
            'total_payable',
            'payment_docs',
            'tender_typeCode',
            'tender_typeDesc',
            'check_type',
            'bank_code',
            'bank_name',
            'tender_amount',
            'check_no',
            'balance',
            'check_due_date',
            'advance_amount',
            'payor',
            'payee'
        );

        $payment_report = $this->createPaymentDocsFile($pmt_display);

        $paymentScheme = [
            'tenant_id' => $tenant_id,
            'contract_no' => $contract_no,
            'tenancy_type' => $tenancy_type,
            'receipt_no' => $receipt_no,
            'tender_typeCode' => '',
            'tender_typeDesc' => $tender_typeDesc,
            'soa_no' => $soa_no,
            'billing_period' => $billing_period,
            'amount_due' => $total_payable,
            'amount_paid' => $tender_amount,
            'bank' => '',
            'check_no' => '',
            'check_date' => '',
            'payor' => $trade_name,
            'payee' => $payee,
            'receipt_doc' => $payment_report,
            'status' => 'NO OR',
        ];

        $this->db->insert('payment_scheme', $paymentScheme);

        //$last_soa = $this->app_model->getLastSOA();

        //INSERT TO PAYMENT
        $paymentData = [
            'posting_date' => $payment_date,
            'soa_no' => $soa_no,
            'amount_paid' => $tender_amount,
            'tenant_id' => $tenant_id,
            'doc_no' => $receipt_no,
        ];

        $this->db->insert('payment', $paymentData);
        /*============= END OF SAVE TO PAYMENT SCHEME ============ */

        //CHECK IF DELAYED PAYMENT
        // if (!$this->app_model->is_penaltyExempt($tenant_id) && $soa->billing_period != 'Upon Signing of Notice')
        // {
        //     $collection_date  = $soa->collection_date;

        //     if (date('Y-m-d', strtotime($payment_date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
        //     {
        //         $daysOfMonth         = date('t', strtotime($payment_date));
        //         $daydiff             = floor((abs(strtotime($payment_date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
        //         $sundays             = $this->app_model->get_sundays($collection_date, $payment_date);
        //         $daydiff             = $daydiff - $sundays;
        //         $penalty_latepayment = ($tender_amount * .02 * $daydiff) / $daysOfMonth;

        //         $penaltyEntry = array(
        //             'tenant_id'     =>  $tenant_id,
        //             'posting_date'  =>  $payment_date,
        //             'contract_no'   =>  $contract_no,
        //             'doc_no'        =>  $receipt_no,
        //             'description'   =>  'Late Payment-' . $trade_name,
        //             'amount'        =>  round($penalty_latepayment, 2)
        //         );
        //         $this->db->insert('tmp_latepaymentpenalty', $penaltyEntry);
        //     }
        // }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->app_model->insert('error_log', [
                'action' => 'Saving Payment',
                'error_msg' => $this->db->_error_message(),
            ]);

            JSONResponse([
                'type' => 'error',
                'msg' => 'Something went wrong while posting payment!',
            ]);
        }

        // $this->generate_paymentCollection($tenant_id);
        JSONResponse([
            'type' => 'success',
            'msg' => 'Payment successfully posted!',
            'file' => $payment_report,
        ]);
    }
    public function advance_payment()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff') {
            $current_date = getCurrentDate();
            $flashdata = $this->session->flashdata('message');
            $expiry_tenants = $this->app_model->get_expiryTenants();
            $payee = $this->app_model->my_store();
            $store_id = $this->session->userdata('user_group');
            $tender_types = json_encode([
                ['id' => 1, 'desc' => 'Cash'],
                ['id' => 2, 'desc' => 'Check'],
                ['id' => 3, 'desc' => 'Bank to Bank'],
                ['id' => 11, 'desc' => 'Unidentified Fund Transfer'],
                // ['id' => 80, 'desc' => 'JV payment - Business Unit'],
                // ['id' => 81, 'desc' => 'JV payment - Subsidiary'],
                // ['id' => 12, 'desc' => 'Internal Payment'],
            ]);

            $data = [
                'current_date' => $current_date,
                'flashdata' => $flashdata,
                'expiry_tenants' => $expiry_tenants,
                'payee' => $payee,
                'store_id' => $store_id,
                'tender_types' => $tender_types,
                'title' => 'Advance Payment'
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/accounting/advance_payment');
            $this->load->view('leasing/footer');
        }
    }
    public function save_advancePayment()
    {
        /*=====================  SETTING VALUES STARTS HERE ==========================*/
        $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name = $this->sanitize($this->input->post('trade_name'));
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $contract_no = $this->sanitize($this->input->post('contract_no'));
        $tenant_address = $this->sanitize($this->input->post('tenant_address'));
        $payment_date = $this->sanitize($this->input->post('payment_date'));
        $remarks = $this->sanitize($this->input->post('remarks'));
        $tender_typeCode = $this->sanitize($this->input->post('tender_typeCode'));
        $receipt_no = $this->sanitize($this->input->post('receipt_no'));
        $amount_paid = $this->sanitize($this->input->post('amount_paid'));
        $receipt_type = $this->sanitize($this->input->post('receipt_type'));
        $tender_amount = $amount_paid;
        $transaction_date = getCurrentDate();
        $payment_date = date('Y-m-d', strtotime($payment_date));
        //IF NOT INTERNAL PAYMENT
        $bank_code = $this->sanitize($this->input->post('bank_code'));
        $bank_name = $this->sanitize($this->input->post('bank_name'));
        $payor = $this->sanitize($this->input->post('payor'));
        $payee = $this->sanitize($this->input->post('payee'));
        //IF INTERNAL PAYMENT
        $ip_store_code = $this->sanitize($this->input->post('store_code'));
        $ip_store_name = $this->sanitize($this->input->post('store_name'));
        //IF CHECK
        $check_type = $this->sanitize($this->input->post('check_type'));
        $bank_code = $this->sanitize($this->input->post('bank_code'));
        $account_no = $this->sanitize($this->input->post('account_no'));
        $account_name = $this->sanitize($this->input->post('account_name'));
        $check_no = $this->sanitize($this->input->post('check_no'));
        $check_date = $this->sanitize($this->input->post('check_date'));
        $check_due_date = $this->sanitize($this->input->post('check_due_date'));
        $expiry_date = $this->sanitize($this->input->post('expiry_date'));
        $check_class = $this->sanitize($this->input->post('check_class'));
        $check_category = $this->sanitize($this->input->post('check_category'));
        $customer_name = $this->sanitize($this->input->post('customer_name'));
        $check_bank = $this->sanitize($this->input->post('check_bank'));
        $check_date = date('Y-m-d', strtotime($check_date));
        $check_due_date = date('Y-m-d', strtotime($check_due_date));
        $expiry_date = date('Y-m-d', strtotime($expiry_date));
        /*=====================  SETTING VALUES ENDS HERE ==========================*/
        /*=====================  VALIDATION STARTS HERE ==========================*/
        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('tender_typeCode', '', 'required|in_list[1,2,3,11,12,80,81]');
        $this->form_validation->set_rules('receipt_no', 'Reciept No.', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');

        if (in_array($tender_typeCode, [1, 2, 3, 11])) {
            $this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
            $this->form_validation->set_rules('payor', 'Payor', 'required');
            $this->form_validation->set_rules('payee', 'Payee', 'required');
        } else {
            if ($tender_typeCode == 12) {
                $this->form_validation->set_rules('store_code', 'Store Code', 'required');
                $this->form_validation->set_rules('store_name', 'Store Name', 'required');
            }

            $bank_code = null;
            $bank_name = null;
        }

        if ($tender_typeCode == '2') {
            if ($this->session->userdata('cfs_logged_in')) {
                $this->form_validation->set_rules('account_no', 'Account No.', 'required');
                $this->form_validation->set_rules('account_name', 'Account Name', 'required');
                //$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
                $this->form_validation->set_rules('check_class', 'Check Class', 'required');
                $this->form_validation->set_rules('check_category', 'Check Category', 'required');
                $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('check_bank', 'Check Bank', 'required');
            }

            $this->form_validation->set_rules('check_type', 'Check Type', 'required|in_list[DATED CHEC, POST DATED CHECK]');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('check_date', 'Check Date', 'required');

            if (!in_array($check_type, ['DATED CHECK', 'POST DATED CHECK'])) {
                JSONResponse(['type' => 'error', 'msg' => 'Invalid Check Type!',]);
            }

            if ($check_type == 'POST DATED CHECK') {
                $this->form_validation->set_rules('check_due_date', 'Check Due Date', 'required');

                if (!validDate($check_due_date)) {
                    JSONResponse(['type' => 'error', 'msg' => 'Check Due Date is not valid!',]);
                }
            }

            if ($this->session->userdata('cfs_logged_in') && !validDate($expiry_date)) {
                $expiry_date = '';
                //JSONResponse(['type'=>'error', 'msg'=>'Check Expiry Date is not valid!']);
            }
        }

        if ($this->form_validation->run() == false) {
            JSONResponse(['type' => 'error', 'msg' => validation_errors()]);
        }

        /*$soa  = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if(empty($soa)){
            JSONResponse(['type'=>'error', 'msg'=>'SOA not found!']);
        }*/

        $tender_types = [
            '1' => 'Cash',
            '2' => 'Check',
            '3' => 'Bank to Bank',
            '11' => 'Unidentified Fund Transfer',
            // '80' => 'JV payment - Business Unit',
            // '81' => 'JV payment - Subsidiary',
            // '12' => 'Internal Payment',
        ];

        $tender_typeDesc = $tender_types[$tender_typeCode];

        if (!in_array($tender_typeCode, [1, 2, 3, 11, 12, 80, 81])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tender Type!']);
        }

        if (in_array($tender_typeCode, ['2', '80', '81', '3'])) {
            $upload = new FileUpload();
            $supp_doc = $upload->validate('supp_doc', 'Supporting Documents')
                ->required()
                ->multiple()
                ->get();

            if ($upload->has_error()) {
                JSONResponse(['type' => 'error', 'msg' => $upload->get_errors('<br>'),]);
            }
        }

        if (in_array($tender_typeCode, ['1', '2', '80', '81', '3'])) {
            if ($this->app_model->checkPaymentReceiptExistence($receipt_no)) {
                JSONResponse(['type' => 'error', 'msg' => 'Payment Slip already used!',]);
            }
        }

        if (!validDate($payment_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Payment Date is not valid!',]);
        }

        if ($amount_paid <= 0) {
            JSONResponse(['type' => 'error', 'msg' => 'Amount paint can\'t be 0.00',]);
        }

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);
        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.',]);
        }

        /*=====================  VALIDATION ENDS HERE ==========================*/

        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';

        //SET UFT OR IP PAYMENT DOC. NO.
        switch ($tender_typeCode) {
            case '11':
                $receipt_no = $this->app_model->generate_UTFTransactionNo(false, $tin_status);
                break;
            case '12':
                $receipt_no = $this->app_model->generate_InternalTransactionNo(false, $tin_status);
                break;
            default:
                $receipt_no;
                break;
        }

        $this->db->trans_start();

        //INSERT URI IF HAS ADVANCE
        $advance_amount = $amount_paid;
        if ($advance_amount > 0) {
            $sl_data = [];
            $gl_code = '';
            $ft_ref = null;
            $debit_status = null;
            $credit_status = null;
            $uri_ref_no = $this->app_model->gl_refNo(false, false);
            $due_date = null;

            //CASH | BANK TO BANK
            if ($tender_typeCode == 1 || $tender_typeCode == 3) {
                $gl_code = '10.10.01.01.02';

                //CHECK
            } elseif ($tender_typeCode == 2) {
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';

                if ($check_type == 'POST DATED CHECK') {
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                    $due_date = $check_due_date;
                }

                //JV payment - Business Unit
            } elseif ($tender_typeCode == 80) {
                $gl_code = $this->app_model->bu_entry();

                //JV payment - Subsidiary
            } elseif ($tender_typeCode == 81) {
                $gl_code = '10.10.01.03.11';

                //UFT
            } elseif ($tender_typeCode == 11) {
                $gl_code = '10.10.01.01.02';
                $credit_status = 'URI Clearing';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);

                //INTERNAL PAYMENT
            } else {
                $gl_code = '10.10.01.03.04';
                $debit_status = $ip_store_name;
                $credit_status = 'ARNTI';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
            }

            $sl_data['debit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($gl_code),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $debit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];

            $sl_data['credit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID('10.20.01.01.02.01'),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'credit' => -1 * $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $credit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            // For Montly Receivable Report
            $mon_rec_report_data = [
                'tenant_id' => $tenant_id,
                'doc_no' => $receipt_no,
                'posting_date' => $payment_date,
                'description' => 'Advance Payment',
                'amount' => $advance_amount,
            ];

            $this->app_model->insert('monthly_receivable_report', $mon_rec_report_data);

            $ledger_data = [
                'posting_date' => $payment_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Advance Payment',
                'doc_no' => $receipt_no,
                'ref_no' => $this->app_model->generate_refNo(false, false),
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'description' => 'Advance Payment-' . $trade_name,
                'debit' => $advance_amount,
                'credit' => 0,
                'balance' => $advance_amount,
            ];

            $this->app_model->insert('ledger', $ledger_data);

            // For Accountability Report
            if (in_array($tender_typeCode, ['1', '2', '3', '80', '81']) && $this->session->userdata('cfs_logged_in')) {
                $this->app_model->insert_accReport($tenant_id, 'Advance Deposit', $advance_amount, $payment_date, $tender_typeDesc);
            }
        }

        $adv_amt_for_preop = $amount_paid;

        //SAVE SUPPORTING DOCUMENT
        if (in_array($tender_typeCode, ['2', '80', '81', '3'])) {
            $targetPath = getcwd() . '/assets/payment_docs/';

            foreach ($supp_doc as $key => $supp) {
                //Setup our new file path
                $filename = $tenant_id . time() . $supp['name'];

                move_uploaded_file($supp['tmp_name'], $targetPath . $filename);

                $supp_doc_data = [
                    'tenant_id' => $tenant_id,
                    'file_name' => $filename,
                    'receipt_no' => $receipt_no,
                ];

                $this->db->insert('payment_supportingdocs', $supp_doc_data);
            }
        }

        //GET LAST SOA TO REFLECT PAYMENT
        $soa_no = '';
        $billing_period = '';
        $last_soa = $this->app_model->getLastestSOA($tenant_id);

        if (!empty($last_soa)) {
            $soa_no = $last_soa->soa_no;
            $billing_period = $last_soa->billing_period;
        }

        //INSERT TO PAYMENT SCHEME
        if (in_array($tender_typeCode, ['1', '2', '3', '80', '81'])) {
            $check_date = $tender_typeCode == 2 ? $check_date : null;
            $pmt_display = (object) compact(
                'tenant',
                'store',
                'receipt_no',
                'payment_date',
                'remarks',
                'payment_date',
                'tender_typeCode',
                'tender_typeDesc',
                'check_type',
                'bank_code',
                'bank_name',
                'tender_amount',
                'check_no',
                'check_due_date',
                'check_date',
                'advance_amount',
                'payor',
                'payee'
            );

            $payment_report = $this->createAdvancePaymentDocsFile($pmt_display);

            /*$check_date = ($tender_typeCode == 2 ?
                ($check_type == 'POST DATED CHECK'? $check_due_date : $payment_date) 
            : null);*/

            $paymentScheme = [
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'tenancy_type' => $tenancy_type,
                'receipt_no' => $receipt_no,
                'tender_typeCode' => $tender_typeCode,
                'tender_typeDesc' => $tender_typeDesc,
                'soa_no' => $soa_no,
                'billing_period' => $billing_period,
                'amount_due' => 0,
                'amount_paid' => $tender_amount,
                'bank' => $bank_name,
                'check_no' => $tender_typeCode == 2 || $tender_typeCode == 3 ? $check_no : null,
                'check_date' => $check_date,
                'payor' => $payor,
                'payee' => $payee,
                'receipt_doc' => $payment_report,
                'status' => 'NO OR',
            ];

            $this->db->insert('payment_scheme', $paymentScheme);

            /*======================  CCM DATA =================================== */

            if ($tender_typeCode == '2' && $this->session->userdata('cfs_logged_in')) {
                $this->load->model('ccm_model');

                $customer_id = $this->ccm_model->check_customer($customer_name);
                $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();
                $ccm_data = [
                    'checksreceivingtransaction_id' => $checksreceivingtransaction_id,
                    'customer_id' => $customer_id,
                    'businessunit_id' => $this->ccm_model->get_BU(),
                    'department_from' => '12',
                    'leasing_docno' => $receipt_no,
                    'check_no' => $check_no,
                    'check_class' => $check_class,
                    'check_category' => $check_category,
                    'check_expiry' => $expiry_date,
                    'check_date' => $check_date,
                    'check_received' => $transaction_date,
                    'check_type' => $check_type,
                    'account_no' => $account_no,
                    'account_name' => $account_name,
                    'bank_id' => $check_bank,
                    'check_amount' => $tender_amount,
                    'currency_id' => '1',
                    'check_status' => 'PENDING',
                ];

                $this->ccm_model->insert('checks', $ccm_data);
            }

            /*========================   CCM DATA ===================================*/
        }

        //INSERT TO PAYMENT
        $paymentData = [
            'posting_date' => $payment_date,
            'soa_no' => $soa_no,
            'amount_paid' => $tender_amount,
            'tenant_id' => $tenant_id,
            'doc_no' => $receipt_no,
        ];

        $this->db->insert('payment', $paymentData);

        //INSERT UFT
        if ($tender_typeCode == '11') {
            $data_utf = [
                'tenant_id' => $tenant_id,
                'bank_code' => $bank_code,
                'bank_name' => $bank_name,
                'posting_date' => $payment_date,
                'amount_payable' => 0,
                'amount_paid' => $tender_amount,
            ];
            $this->db->insert('uft_payment', $data_utf);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->app_model->insert('error_log', ['action' => 'Saving Payment', 'error_msg' => $this->db->_error_message(),]);

            JSONResponse(['type' => 'error', 'msg' => 'Something went wrong while posting payment!',]);
        }

        if (in_array($tender_typeCode, ['1', '2', '3', '80', '81'])) {
            $this->generate_paymentCollection($tenant_id, $payment_date);
            JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!', 'file' => $payment_report,]);
        }

        JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!',]);
    }
    public function save_transferedPreop()
    {
        /*=====================  SETTING VALUES STARTS HERE ==========================*/
        $preop_type = $this->sanitize($this->input->post('preop_type'));
        $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
        $trade_name = $this->sanitize($this->input->post('trade_name'));
        $tenant_id = $this->sanitize($this->input->post('tenant_id'));
        $contract_no = $this->sanitize($this->input->post('contract_no'));
        $tenant_address = $this->sanitize($this->input->post('tenant_address'));
        $payment_date = $this->sanitize($this->input->post('payment_date'));
        $remarks = $this->sanitize($this->input->post('remarks'));
        $tender_typeCode = $this->sanitize($this->input->post('tender_typeCode'));
        $receipt_no = $this->sanitize($this->input->post('receipt_no'));
        $amount_paid = $this->sanitize($this->input->post('amount_paid'));
        $receipt_type = $this->sanitize($this->input->post('receipt_type'));
        $tender_amount = $amount_paid;
        $transaction_date = getCurrentDate();
        $payment_date = date('Y-m-d', strtotime($payment_date));
        //IF NOT INTERNAL PAYMENT
        $bank_code = $this->sanitize($this->input->post('bank_code'));
        $bank_name = $this->sanitize($this->input->post('bank_name'));
        $payor = $this->sanitize($this->input->post('payor'));
        $payee = $this->sanitize($this->input->post('payee'));
        //IF INTERNAL PAYMENT
        $ip_store_code = $this->sanitize($this->input->post('store_code'));
        $ip_store_name = $this->sanitize($this->input->post('store_name'));
        //IF CHECK
        $check_type = $this->sanitize($this->input->post('check_type'));
        $bank_code = $this->sanitize($this->input->post('bank_code'));
        $account_no = $this->sanitize($this->input->post('account_no'));
        $account_name = $this->sanitize($this->input->post('account_name'));
        $check_no = $this->sanitize($this->input->post('check_no'));
        $check_date = $this->sanitize($this->input->post('check_date'));
        $check_due_date = $this->sanitize($this->input->post('check_due_date'));
        $expiry_date = $this->sanitize($this->input->post('expiry_date'));
        $check_class = $this->sanitize($this->input->post('check_class'));
        $check_category = $this->sanitize($this->input->post('check_category'));
        $customer_name = $this->sanitize($this->input->post('customer_name'));
        $check_bank = $this->sanitize($this->input->post('check_bank'));
        $check_date = date('Y-m-d', strtotime($check_date));
        $check_due_date = date('Y-m-d', strtotime($check_due_date));
        $expiry_date = date('Y-m-d', strtotime($expiry_date));
        /*=====================  SETTING VALUES ENDS HERE ==========================*/
        /*=====================  VALIDATION STARTS HERE ==========================*/
        $this->form_validation->set_rules('tenancy_type', 'Tenancy Type', 'required|in_list[Short Term Tenant,Long Term Tenant]');
        $this->form_validation->set_rules('trade_name', 'Trade Name', 'required');
        $this->form_validation->set_rules('tenant_id', 'Tenant ID', 'required');
        $this->form_validation->set_rules('contract_no', 'Contract No.', 'required');
        $this->form_validation->set_rules('tenant_address', 'Tenant Address', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('tender_typeCode', '', 'required|in_list[1,2,3,11,12,80,81]');
        $this->form_validation->set_rules('receipt_no', 'Reciept No.', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric');

        if (in_array($tender_typeCode, [1, 2, 3, 11])) {
            $this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
            $this->form_validation->set_rules('payor', 'Payor', 'required');
            $this->form_validation->set_rules('payee', 'Payee', 'required');
        } else {
            if ($tender_typeCode == 12) {
                $this->form_validation->set_rules('store_code', 'Store Code', 'required');
                $this->form_validation->set_rules('store_name', 'Store Name', 'required');
            }

            $bank_code = null;
            $bank_name = null;
        }

        if ($tender_typeCode == '2') {
            if ($this->session->userdata('cfs_logged_in')) {
                $this->form_validation->set_rules('account_no', 'Account No.', 'required');
                $this->form_validation->set_rules('account_name', 'Account Name', 'required');
                //$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
                $this->form_validation->set_rules('check_class', 'Check Class', 'required');
                $this->form_validation->set_rules('check_category', 'Check Category', 'required');
                $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('check_bank', 'Check Bank', 'required');
            }

            $this->form_validation->set_rules('check_type', 'Check Type', 'required|in_list[DATED CHEC, POST DATED CHECK]');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('check_date', 'Check Date', 'required');

            if (!in_array($check_type, ['DATED CHECK', 'POST DATED CHECK'])) {
                JSONResponse(['type' => 'error', 'msg' => 'Invalid Check Type!']);
            }

            if ($check_type == 'POST DATED CHECK') {
                $this->form_validation->set_rules('check_due_date', 'Check Due Date', 'required');

                if (!validDate($check_due_date)) {
                    JSONResponse(['type' => 'error', 'msg' => 'Check Due Date is not valid!']);
                }
            }

            if ($this->session->userdata('cfs_logged_in') && !validDate($expiry_date)) {
                $expiry_date = '';
                //JSONResponse(['type'=>'error', 'msg'=>'Check Expiry Date is not valid!']);
            }
        }

        if ($this->form_validation->run() == false) {
            JSONResponse(['type' => 'error', 'msg' => validation_errors()]);
        }

        /*$soa  = $this->app_model->get_soaDetails($tenant_id, $soa_no);

        if(empty($soa)){
        JSONResponse(['type'=>'error', 'msg'=>'SOA not found!']);
        }*/

        $tender_types = [
            '1' => 'Cash',
            '2' => 'Check',
            '3' => 'Bank to Bank',
            '11' => 'Unidentified Fund Transfer',
        ];

        $tender_typeDesc = $tender_types[$tender_typeCode];

        if (!in_array($tender_typeCode, [1, 2, 3, 11, 12, 80, 81])) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tender Type!']);
        }

        if (in_array($tender_typeCode, ['1', '2', '80', '81', '3'])) {
            if ($this->app_model->checkPaymentReceiptExistence($receipt_no)) {
                JSONResponse(['type' => 'error', 'msg' => 'Payment Slip already used!']);
            }
        }

        if (!validDate($payment_date)) {
            JSONResponse(['type' => 'error', 'msg' => 'Payment Date is not valid!']);
        }

        if ($amount_paid <= 0) {
            JSONResponse(['type' => 'error', 'msg' => 'Amount paint can\'t be 0.00']);
        }

        $tenant = $this->app_model->getTenantByTenantID($tenant_id);
        try {
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $store = $this->app_model->getStore($store_code);

            if (empty($store_code) || empty($store) || empty($tenant)) {
                throw new Exception('Invalid Tenant');
            }
        } catch (Exception $e) {
            JSONResponse(['type' => 'error', 'msg' => 'Invalid Tenant! Tenant might be terminated.']);
        }

        /*=====================  VALIDATION ENDS HERE ==========================*/

        $details = $this->app_model->get_tenant_details_2($tenant_id);
        $docno = '';
        $tin_status = 'with';

        //SET UFT OR IP PAYMENT DOC. NO.
        switch ($tender_typeCode) {
            case '11':
                $receipt_no = $this->app_model->generate_UTFTransactionNo(false, $tin_status);
                break;
            case '12':
                $receipt_no = $this->app_model->generate_InternalTransactionNo(false, $tin_status);
                break;
            default:
                $receipt_no;
                break;
        }

        $this->db->trans_start();

        //INSERT URI IF HAS ADVANCE
        $advance_amount = $amount_paid;
        if ($advance_amount > 0) {
            $sl_data = [];
            $gl_code = '';
            $gl_codeID = '';
            $ft_ref = null;
            $debit_status = null;
            $credit_status = null;
            $uri_ref_no = $this->app_model->gl_refNo(false, false);
            $due_date = null;

            //CASH | BANK TO BANK
            if ($tender_typeCode == 1 || $tender_typeCode == 3) {
                $gl_code = '10.10.01.01.02';
                //CHECK
            } elseif ($tender_typeCode == 2) {
                $gl_code = $check_type == 'POST DATED CHECK' ? '10.10.01.03.07.01' : '10.10.01.01.02';

                if ($check_type == 'POST DATED CHECK') {
                    //$debit_status = 'PDC';
                    $credit_status = 'PDC';
                    $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                    $due_date = $check_due_date;
                }
                //JV payment - Business Unit
            } elseif ($tender_typeCode == 80) {
                $gl_code = $this->app_model->bu_entry();
                //JV payment - Subsidiary
            } elseif ($tender_typeCode == 81) {
                $gl_code = '10.10.01.03.11';
                //UFT
            } elseif ($tender_typeCode == 11) {
                $gl_code = '10.10.01.01.02';
                $credit_status = 'URI Clearing';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
                //INTERNAL PAYMENT
            } else {
                $gl_code = '10.10.01.03.04';
                $debit_status = $ip_store_name;
                $credit_status = 'ARNTI';
                $ft_ref = $this->app_model->generate_ClosingRefNo(false, $tin_status);
            }

            if ($preop_type === "Security Deposit") {
                $gl_codeID = '10.20.01.01.03.12';
            } else if ($preop_type === "Construction Bond") {
                $gl_codeID = '10.20.01.01.03.10';
            } else if ($preop_type === "Advances") {
                $gl_codeID = '10.20.01.01.02.01';
            }
            $sl_data['debit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($gl_code),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'debit' => $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $debit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];

            $sl_data['credit'] = [
                'posting_date' => $payment_date,
                'due_date' => $due_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Payment',
                'ref_no' => $uri_ref_no,
                'doc_no' => $receipt_no,
                'tenant_id' => $tenant_id,
                'gl_accountID' => $this->app_model->gl_accountID($gl_codeID),
                'company_code' => $store->company_code,
                'department_code' => $store->dept_code,
                'credit' => -1 * $advance_amount,
                'bank_name' => $tender_typeCode != 12 ? $bank_name : null,
                'bank_code' => $tender_typeCode != 12 ? $bank_code : null,
                'status' => $credit_status,
                'ft_ref' => $ft_ref,
                'prepared_by' => $this->session->userdata('id'),
            ];

            foreach ($sl_data as $key => $data) {
                $this->db->insert('general_ledger', $data);
                $this->db->insert('subsidiary_ledger', $data);
            }

            // For Montly Receivable Report
            $mon_rec_report_data = [
                'tenant_id' => $tenant_id,
                'doc_no' => $receipt_no,
                'posting_date' => $payment_date,
                'description' => 'Preop Payment',
                'amount' => $advance_amount,
            ];

            $this->app_model->insert('monthly_receivable_report', $mon_rec_report_data);

            $ledger_data = [
                'posting_date' => $payment_date,
                'transaction_date' => $transaction_date,
                'document_type' => 'Preop Payment',
                'doc_no' => $receipt_no,
                'ref_no' => $this->app_model->generate_refNo(false, false),
                'tenant_id' => $tenant_id,
                'contract_no' => $contract_no,
                'description' => 'Preop Payment-' . $trade_name,
                'debit' => $advance_amount,
                'credit' => 0,
                'balance' => $advance_amount,
            ];

            $this->app_model->insert('ledger', $ledger_data);

            // For Accountability Report
            if (in_array($tender_typeCode, ['1', '2', '3', '80', '81']) && $this->session->userdata('cfs_logged_in')) {
                $this->app_model->insert_accReport($tenant_id, 'Preop Payment', $advance_amount, $payment_date, $tender_typeDesc);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->app_model->insert('error_log', ['action' => 'Saving Payment', 'error_msg' => $this->db->_error_message()]);

            JSONResponse(['type' => 'error', 'msg' => 'Something went wrong while posting payment!']);
        }

        if (in_array($tender_typeCode, ['1', '2', '3', '80', '81'])) {
            // $this->generate_paymentCollection($tenant_id, $payment_date);
            JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!']);
        }

        JSONResponse(['type' => 'success', 'msg' => 'Payment successfully posted!']);

    }
    #ADVANCE PAYMENT PDF
    function createAdvancePaymentDocsFile($pmt)
    {
        $pdf = new FPDF('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->setDisplayMode('fullpage');
        $pdf->setFont('times', 'B', 12);
        $logoPath = getcwd() . '/assets/other_img/';

        $store = $pmt->store;
        $tenant = $pmt->tenant;

        $pdf->cell(
            20,
            20,
            $pdf->Image($logoPath . $store->logo, 100, $pdf->GetY(), 15),
            0,
            0,
            'C',
            false
        );
        $pdf->ln();
        $pdf->setFont('times', 'B', 14);
        $pdf->cell(190, 10, strtoupper($store->store_name), 0, 0, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(35, 35, 35);
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        $pdf->cell(190, 5, 'Owned & Managed by Alturas Supermarket Corporation', 0, 0, 'C');
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        $pdf->cell(190, 5, $store->store_address, 0, 0, 'C');
        $pdf->ln();
        $pdf->setFont('times', '', 11);
        // $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'C');
        $store_name = $store->store_name;
        // ============== gwaps ====================================
        if ($store_name == 'ALTURAS MALL') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00000', 0, 0, 'L');
        } elseif ($store_name == 'ALTURAS TALIBON') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-002', 0, 0, 'L');
        } elseif ($store_name == 'ISLAND CITY MALL') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'L');
        } elseif ($store_name == 'ALTURAS TUBIGON') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00006', 0, 0, 'L');
        } elseif ($store_name == 'ALTA CITTA') {
            $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00009', 0, 0, 'L');
        }
        // ============== gwaps end ================================
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 16);
        $pdf->cell(0, 6, 'PAYMENT SLIP', 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();

        $pdf->setFont('times', 'B', 9);
        $pdf->cell(25, 5, 'PS No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->receipt_no, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'Transaction Date', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->payment_date, 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, 'Trade Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->trade_name, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'Remarks', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $pmt->remarks, 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(25, 5, 'Corporate Name', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->corporate_name, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');
        $pdf->cell(25, 5, 'TIN', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->tin, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(25, 5, 'Address', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 5, $tenant->address, 'B', 0, 'L');
        $pdf->cell(10, 5, ' ', 0, 0, 'L');

        $pdf->ln();
        $pdf->ln();

        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(
            0,
            5,
            'Please make all checks payable to ' .
            strtoupper($store->store_name),
            0,
            0,
            'R'
        );
        // $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();

        $pdf->ln();

        // =================== Receipt Charges Table ============= //
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(120, 8, 'Description', 0, 0, 'L');
        $pdf->cell(30, 8, 'Amount', 0, 0, 'C');
        $pdf->setFont('times', '', 10);

        $pdf->ln();
        $pdf->cell(30, 5, 'Advance Payment', 0, 0, 'L');
        $pdf->cell(
            30,
            5,
            ' - ' . date('M Y', strtotime($pmt->payment_date)),
            0,
            0,
            'L'
        );
        $pdf->cell(60, 5, '', 0, 0, 'R');
        $pdf->cell(30, 5, number_format($pmt->advance_amount, 2), 0, 0, 'R');

        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(30, 5, 'Amount Paid', 0, 0, 'L');
        $pdf->setFont('times', '', 10);
        $pdf->cell(30, 5, '', 0, 0, 'L');
        $pdf->cell(60, 5, '', 0, 0, 'R');
        $pdf->cell(
            30,
            5,
            'P ' . number_format($pmt->tender_amount, 2),
            'T',
            0,
            'R'
        );
        $pdf->ln();

        // $pdf->ln();
        // $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
        // $pdf->ln();

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(190, 8, 'Payment Scheme: ', 0, 0, 'L');
        $pdf->ln();

        $pdf->setFont('times', '', 9);
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Description: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            $pmt->tender_typeCode != 2
            ? $pmt->tender_typeDesc
            : ucwords($pmt->check_type),
            'B',
            0,
            'L'
        );
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Total Payable: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format(0, 2), 'B', 0, 'L');
        $pdf->ln();

        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Bank: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            in_array($pmt->tender_typeCode, [1, 2, 3, 11])
            ? $pmt->bank_name
            : 'N/A',
            'B',
            0,
            'L'
        );
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Amount Paid: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            'P ' . number_format($pmt->tender_amount, 2),
            'B',
            0,
            'L'
        );
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Check Number: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            $pmt->tender_typeCode == 2 ? $pmt->check_no : 'N/A',
            'B',
            0,
            'L'
        );
        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Balance: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, 'P ' . number_format(0, 2), 'B', 0, 'L');

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');

        $pdf->cell(30, 4, 'Check Date: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            $pmt->tender_typeCode == 2 ? $pmt->check_date : 'N/A',
            'B',
            0,
            'L'
        );

        $pdf->cell(5, 4, ' ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Advance: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            'P ' . number_format($pmt->advance_amount, 2),
            'B',
            0,
            'L'
        );

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Check Due Date: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(
            60,
            4,
            $pmt->tender_typeCode == 2 && $pmt->check_type == 'POST DATED CHECK'
            ? $pmt->check_due_date
            : 'N/A',
            'B',
            0,
            'L'
        );

        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Payor: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payor, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Payee: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->payee, 'B', 0, 'L');
        $pdf->ln();
        $pdf->cell(1, 4, '     ', 0, 0, 'L');
        $pdf->cell(30, 4, 'Document #: ', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(60, 4, $pmt->receipt_no, 'B', 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', '', 10);
        $pdf->cell(95, 4, 'Prepared By: _____________________', 0, 0, 'C');
        $pdf->cell(95, 4, 'Checked By:______________________', 0, 0, 'C');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(45, 4, 'Acknowledgment Certificate No.', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, ' AC_123_122023_000135', 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Date Issued', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, "December 12, 2023", 0, 0, 'L');
        $pdf->ln();
        $pdf->cell(45, 4, 'Series Range', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, 'PS0000001 - PS9999999', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(0, 4, 'THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX', 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', 'B', 10);
        $pdf->cell(0, 4, 'THIS IS NOT AN OFFICIAL RECEIPT', 0, 0, 'L');
        $pdf->ln();
        $pdf->ln();
        $pdf->cell(0, 4, 'Thank you for your prompt payment!', 0, 0, 'L');
        $pdf->ln();
        $pdf->setFont('times', '', 9);
        $pdf->cell(25, 4, 'Run Date and Time', 0, 0, 'L');
        $pdf->cell(2, 4, ':', 0, 0, 'L');
        $pdf->cell(38, 4, date('Y-m-d h:m:s A'), 0, 0, 'L');

        $file_name = $tenant->tenant_id . time() . '.pdf';

        $pdf->Output('assets/pdf/' . $file_name, 'F');

        return $file_name;
    }
    public function prepost_old_soa_action($tenant_id, $date = null)
    {
        $res = $this->prepost_old_soa($tenant_id, $date);

        die(
            $res > 0
            ? "Success! $res invoice/s preposted."
            : 'No invoice preposted!'
        );
    }
    private function prepost_old_soa($tenant_id, $date = null)
    {
        $success = 0;
        $exist = 0;

        //$year = date('Y-m');

        //if(!$this->app_model->tenant_has_record_in_soa_line($tenant_id, $date)){

        $data = $this->app_model->get_old_soa_invoices_with_balance(
            $tenant_id,
            $date
        );
        if ($data) {
            $soa_no = $data[0]->soa_no;

            //CHECK HERE IF SOA EXIST
            if (
                !$this->app_model->soa_no_exist_in_soa_line($soa_no, $tenant_id)
            ) {
                foreach ($data as $key => $inv) {
                    if (
                        !$this->app_model->prepost_exist_in_soa_line(
                            $soa_no,
                            $inv->doc_no
                        )
                    ) {
                        $soa_line = [
                            'soa_no' => $soa_no,
                            'doc_no' => $inv->doc_no,
                            'amount' => $inv->inv_amount,
                            'tenant_id' => $inv->tenant_id,
                        ];
                        $this->db->insert('soa_line', $soa_line);

                        $success++;
                    } else {
                        $exist++;
                    }
                }

                $preop_data = $this->app_model->get_tmp_preop_invoice_with_balance(
                    $tenant_id,
                    $date
                );

                if ($preop_data) {
                    foreach ($preop_data as $key => $inv) {
                        $soa_line = [
                            'soa_no' => $soa_no,
                            'doc_no' => $inv->doc_no,
                            'amount' => $inv->amount,
                            'tenant_id' => $inv->tenant_id,
                            'preop_id' => $inv->id,
                        ];

                        $this->db->insert('soa_line', $soa_line);

                        $success++;
                    }
                }
            }
        }

        return $success > 0;
    }
    public function get_managers_key()
    {
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $for = $this->sanitize($this->input->post('for'));

        if (empty($username) || empty($password) || empty($for)) {
            JSONResponse([
                'type' => 'error',
                'msg' => 'Please input the required fields!',
            ]);
        }

        $store_name = $this->app_model->my_store();

        if ($this->app_model->managers_key($username, $password, $store_name)) {
            //$token = openssl_random_pseudo_bytes(16);
            //$token = bin2hex($token);

            $token = md5(microtime());

            $manager = $this->app_model->get_user_by_credentials(
                $username,
                $password
            );

            $this->session->set_userdata(
                $for,
                (object) ['manager_id' => $manager->id, 'token' => $token]
            );
            JSONResponse([
                'type' => 'success',
                'msg' => "Manager's key applied!",
                'token' => $token,
            ]);
        }

        JSONResponse(['type' => 'error', 'msg' => 'Invalid Credentials!']);
    }
    public function recon_sys_vs_nav()
    {
        $data['current_date'] = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['stores'] = $this->app_model->get_stores();
        $data['gl_accounts'] = $this->app_model->get_gl_accounts();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/recon_sys_vs_nav');
        $this->load->view('leasing/footer');
    }
    public function generate_recon_sys_vs_nav_report()
    {
        $store = $this->sanitize($this->input->post('store'));
        $gl_ids = $this->input->post('gl_ids');
        $date_from = $this->sanitize($this->input->post('date_from'));
        $date_to = $this->sanitize($this->input->post('date_to'));

        $date_from = date('Y-m-d', strtotime($date_from));
        $date_to = date('Y-m-d', strtotime($date_to));

        if ($this->session->userdata('user_group') != 0) {
            $store = $this->app_model->get_storeDetails();

            if (empty($store)) {
                $store = '';
            } else {
                $store = $store[0]['store_code'];
            }
        }

        $result = $this->app_model->get_data_recon_sys_vs_nav_report(
            $store,
            $gl_ids,
            $date_from,
            $date_to
        );

        $csv_data[] = [
            'ID',
            'Tenant Code',
            'Trade Name',
            'Document Type',
            'GL Account',
            'Doc. No.',
            'Ref. No.',
            'Posting Date',
            'Due Date',
            'Bank Code',
            'Bank Name',
            'Debit',
            'Credit',
        ];

        foreach ($result as $d) {
            $d = (object) $d;

            $csv_data[] = [
                $d->id,
                $d->tenant_id,
                $d->trade_name,
                $d->document_type,
                $d->description,
                $d->doc_no,
                $d->ref_no,
                $d->posting_date,
                $d->due_date,
                $d->bank_code,
                $d->bank_name,
                $d->debit,
                $d->credit,
            ];
        }

        $csv_data = arrayToString($csv_data);

        //$file_name = "GL Report - $store ".strtoupper(uniqid()).'.csv';
        $file_name =
            "GL Report - $store " .
            date('Ymd', strtotime($date_from)) .
            '-' .
            date('Ymd', strtotime($date_to)) .
            '.csv';

        download_send_headers($file_name, $csv_data);
    }
    public function recon_sys_vs_bank()
    {
        $data['current_date'] = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['stores'] = $this->app_model->get_stores();
        $data['banks'] = $this->app_model->get_banks();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/recon_sys_vs_bank');
        $this->load->view('leasing/footer');
    }
    public function generate_recon_sys_vs_bank_report()
    {
        //$store      = $this->sanitize($this->input->post('store'));
        //$gl_ids     = $this->input->post('gl_ids');

        $bank_id = $this->input->post('bank_id');
        $date_from = $this->sanitize($this->input->post('date_from'));
        $date_to = $this->sanitize($this->input->post('date_to'));

        $date_from = date('Y-m-d', strtotime($date_from));
        $date_to = date('Y-m-d', strtotime($date_to));

        /* $store = '';*/

        /*if($this->session->userdata('user_group') != 0){
            $store = $this->app_model->get_storeDetails();

            if(empty($store)){
                $store = '';
            }else{
                $store = $store[0]['store_code'];
            }
           
        }*/

        $bank = $this->app_model->get_bank_by_id($bank_id);

        if (!$bank) {
            die('INVALID BANK ID!');
        }

        $result = $this->app_model->get_data_recon_sys_vs_bank_report(
            $bank,
            $date_from,
            $date_to
        );

        $csv_data[] = [
            'ID',
            'Tenant Code',
            'Trade Name',
            'Document Type',
            'GL Account',
            'Doc. No.',
            'Posting Date',
            'Due Date',
            'Bank Code',
            'Bank Name',
            'Debit',
            'Credit',
            'Amount',
        ];

        foreach ($result as $d) {
            $d = (object) $d;

            $csv_data[] = [
                $d->id,
                $d->tenant_id,
                $d->trade_name,
                $d->document_type,
                $d->description,
                $d->doc_no,
                $d->posting_date,
                $d->due_date,
                $d->bank_code,
                $d->bank_name,
                $d->debit,
                $d->credit,
                $d->amount,
            ];
        }

        $csv_data = arrayToString($csv_data);

        //$file_name = "GL Report - $store ".strtoupper(uniqid()).'.csv';
        $file_name =
            "SYS VS BANK REPORT - $bank->store_code " .
            date('Ymd', strtotime($date_from)) .
            '-' .
            date('Ymd', strtotime($date_to)) .
            ' ' .
            time() .
            '.csv';

        download_send_headers($file_name, $csv_data);
    }
    public function invoice_override_history()
    {
        $data['current_date'] = getCurrentDate();

        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/accounting/invoice_override_history');
        $this->load->view('leasing/footer');
    }
    public function get_invoice_override_data($tenant_id = '')
    {
        $tenant_id = $this->sanitize($tenant_id);

        $data = $this->app_model->get_invoice_override_data($tenant_id);

        JSONResponse($data);
    }
    function test_date_diff($last_due, $due_date)
    {
        $daylen = 60 * 60 * 24;
        dump($daylen);
        $daysDiff = (strtotime($last_due) - strtotime($due_date)) / $daylen;
        dump($daysDiff);
        $daysDiff = $daysDiff / 20;
        dump($daysDiff);
        $daysDiff = floor($daysDiff);
        dump($daysDiff);
        $current_due = strtotime($due_date . '-20 days');
        dump($current_due);
    }
    function test_date_diff2($last_due, $due_date)
    {
        $last_due = date_create($last_due);
        $due_date = date_create($due_date);
        $diff = date_diff($due_date, $last_due);
        dump($last_due);
        dump($due_date);
        dump($diff);
        echo FLOOR((int) $diff->format('%R%a') / 20) . '<br>';

        /*$daylen      = 60*60*24;
        dump($daylen);
        $daysDiff    = (strtotime($last_due)-strtotime($due_date))/$daylen;
        dump($daysDiff);
        $daysDiff    = $daysDiff / 20;
        dump($daysDiff);
        $daysDiff    = floor($daysDiff);
        dump($daysDiff);
        $current_due = strtotime($due_date[$i] . "-20 days");
        dump($current_due);*/
    }
    function testpage()
    {
        //dump(DecimalToWord::convert(1599.6, 'Pesos', 'Centavos'));
        // dump(DecimalToWord::$formatted);

        // $last_soa = $this->app_model->getLastestSOA('ACT-LT000065');

        // $date  = date('M Y');
        // $month = date('F Y', strtotime($date));

        // dd($month);

        $data = [];

        foreach ($data as $value) {
            dump('HELLO');
        }
    }
    public function getAdvanceTransactionNo()
    {
        $transactionNo = $this->app_model->getAdvanceTransactionNo(false);
        JSONResponse($transactionNo);
    }
    #------------------------------------------------------ AUTO EXTRACTION ------------------------------------------------------#
    # FOR EXTRACTION CAS TEXT FILE
    public function generate_RRreports($tenant_id, $posting_date)
    {
        $month = $posting_date;
        $month = date('F Y', strtotime($month));
        $store = $this->session->userdata('store_code');
        // $tntID     = "AND tenant_id = '{$tenant_id}'";
        // $RRreports = $this->app_model->generate_RRreports($month, $tntID);
        $docNumber = "AND doc_no LIKE '%{$tenant_id}%'";
        $RRreports = $this->app_model->generate_RRreports($month, $docNumber);


        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC',
        ];
        $m = date('m', strtotime($month));

        $linesCounter = [];

        if (!empty($RRreports['data'])) {
            try {
                foreach ($RRreports['data'] as $key => $value) {
                    $checkBalance = $this->app_model->checkBalance($value['doc_no'], $value['posting_date']);

                    if ($checkBalance->debit > 0) {
                        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                        $company_code = $this->session->userdata('company_code');
                        $dept_code = $this->session->userdata('dept_code');
                        $doc_no = ($value['cas_doc_no'] != "") ? $value['cas_doc_no'] : 'BLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                        $report_data = $this->app_model->generate_RRreports($month, "AND tenant_id LIKE '%" . $value['tenant_id'] . "%'");
                        $data = $report_data['data'];
                        $doc_nos = $report_data['doc_nos'];
                        $file_data = '';
                        $externalDocNo = '';

                        // dump($doc_nos);

                        if (!empty($data)) {
                            $rows = [];
                            $line_no = 10000;
                            $tradeName = '';



                            // foreach ($data as $result) {
                            //     $pDate = date('m/d/Y', strtotime($result['posting_date']));
                            //     $tradeName = substr($result['trade_name'], 0, 45);

                            //     if ($result['gl_code'] == '10.10.01.03.16') {
                            //         $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$doc_no}<|>{$tradeName}-Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                            //     } elseif ($result['gl_code'] == '10.10.01.06.05') {
                            //         $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                            //     } else {
                            //         $amount = str_replace('-', '', $result['amount']);
                            //         $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>($amount)<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                            //     }

                            //     $line_no += 10000;

                            //     $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            // }


                            //========================================= gwaps ===============================================================
                            foreach ($data as $result) {
                                $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$result['doc_no']}' AND tenant_id = '{$result['tenant_id']}'")->row();

                                if (!empty($soaLine)) {
                                    $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$result['tenant_id']}'")->row();
                                } else {
                                    $soa = new stdClass();
                                    $soa->billing_period = '';
                                }

                                $billing_period = $soa->billing_period;
                                $month_mapping = [
                                    'JANUARY' => 'Jan',
                                    'FEBRUARY' => 'Feb',
                                    'MARCH' => 'Mar',
                                    'APRIL' => 'Apr',
                                    'MAY' => 'May',
                                    'JUNE' => 'Jun',
                                    'JULY' => 'Jul',
                                    'AUGUST' => 'Aug',
                                    'SEPTEMBER' => 'Sep',
                                    'OCTOBER' => 'Oct',
                                    'NOVEMBER' => 'Nov',
                                    'DECEMBER' => 'Dec',
                                ];
                                foreach ($month_mapping as $full => $abbrev) {
                                    if (stripos($billing_period, $full) !== false) {
                                        $billing_period = str_ireplace($full, $abbrev, $billing_period);
                                        break;
                                    }
                                }

                                $due_date = date('M j, Y', strtotime($result['due_date']));

                                $pDate = date('m/d/Y', strtotime($result['posting_date']));
                                $tradeName = substr($result['trade_name'], 0, 45);
                                $doc_no = ($value['cas_doc_no'] != "") ? $value['cas_doc_no'] : 'BLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));

                                if ($result['gl_code'] == '10.10.01.03.16') {
                                    $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$doc_no}<|>{$result['soa_no']}-Basic Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                } elseif ($result['gl_code'] == '10.10.01.06.05') {
                                    $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                } else {
                                    $amount = str_replace('-', '', $result['amount']);
                                    $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>$amount<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                }

                                $line_no += 10000;
                                $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            }
                            //========================================= gwaps ends ==========================================================

                            // dump($rows);
                            $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            $filter_date = date('Y-m', strtotime($posting_date));
                            $file_name = $exp_batch_no . "_Basic Rent_" . $value['tenant_id'] . '_' . $filter_date . ".pjn";
                            // $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/rent/' . $monthfolder["$m"] . '/' . $file_name;
                            // $targetPath = getcwd() . '/assets/for_cas/basic_rent/' . $file_name;
                            $targetPath = getcwd() . '/assets/for_cas/basic_rent/' . $monthfolder["$m"] . '/' . $file_name; // gwaps
                            $file_data = arrayToString($rows);

                            $toUpdate = ($value['cas_doc_no'] != '') ? ['export_batch_code' => $exp_batch_no] : ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];

                            foreach ($doc_nos as $doc_no) {
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                            }

                            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Basic Rent', $file_name);

                            file_put_contents($targetPath, $file_data);

                            $linesCounter = $rows;
                        }
                    }
                }

                if (!empty($linesCounter)) {
                    $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
                } else {
                    $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
                }
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }


        return $msg;
    }
    #YAWA NING CAS YWAW NING BIR YAWA NI TANAN - IF EVER MAHIMO KAG PROGRAMMER ANING LEASING, AYAW NA PADAYON, LABAD SA ULO RAY MAKUHA NIMO
    public function generate_ARreports($tenant_id, $posting_date)
    {
        $month = $posting_date;
        $month = date('F Y', strtotime($month));
        $store = $this->session->userdata('store_code');
        // $tntID     = "AND sl.tenant_id = '{$tenant_id}'";
        // $ARreports = $this->app_model->generate_ARreports($month, $tntID);
        $billing_period = $this->input->post('billing_period');
        $soa_no = $this->input->post('soa_no');

        $docNumber = "AND sl.doc_no LIKE '%{$tenant_id}%'";
        $ARreports = $this->app_model->generate_ARreports($month, $docNumber);


        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC',
        ];

        $m = date('m', strtotime($month));
        $linesCounter = [];

        if (!empty($ARreports['data'])) {
            try {
                foreach ($ARreports['data'] as $key => $value) {
                    $checkBalance = $this->app_model->checkBalance($value['doc_no'], $value['posting_date']);

                    if ($checkBalance->debit > 0) {
                        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                        $company_code = $this->session->userdata('company_code');
                        $dept_code = $this->session->userdata('dept_code');
                        $date = new DateTime();
                        $timeStamp = $date->getTimestamp();
                        $doc_no = ($value['cas_doc_no'] != '') ? $value['cas_doc_no'] : 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                        $report_data = $this->app_model->generate_ARreports($month, "AND sl.tenant_id LIKE '%{$value['tenant_id']}%'");
                        $data = $report_data['data'];
                        $doc_nos = $report_data['doc_nos'];
                        $file_data = '';
                        $externalDocNo = '';

                        if (!empty($data)) {
                            $line_no = 10000;
                            $rows = [];

                            $glCodeMapping = [
                                '10.10.01.06.05' => 'Expanded Withholding Tax',
                                '20.80.01.08.04' => 'Aircon',
                                '20.80.01.08.08' => 'Water',
                                '20.80.01.08.05' => 'Chilled Water',
                                '20.80.01.08.03' => 'Common Usage Charges',
                                '20.80.01.08.02' => 'Electricity Charge (156.10 kw)',
                                // '20.80.01.08.01' => 'Penalty',
                            ];

                            // Sort $data by gl_code in the specified order
                            usort($data, function ($a, $b) {
                                $order = [
                                    '10.10.01.03.03',
                                    '10.10.01.06.05',
                                    // '20.80.01.08.01',
                                    '10.10.01.03.04',
                                    '20.80.01.08.04',
                                    // Aircon
                                    '20.80.01.08.08',
                                    // Water
                                    '20.80.01.08.05',
                                    // Chilled Water
                                    '20.80.01.08.03',
                                    // Common Usage Charges
                                    '20.80.01.08.02',
                                    // Electricity
                                    '20.80.01.08.07' // Charges

                                ];
                                return array_search($a['gl_code'], $order) - array_search($b['gl_code'], $order);
                            });

                            function formatBillingPeriod($billingPeriod)
                            {
                                // Debugging output for original billing period
                                error_log("Original billing period: $billingPeriod");

                                // Flexible regex to capture variations in format and capitalization
                                if (preg_match('/^\s*([A-Za-z]+)\s+(\d+)\s*-\s*(?:([A-Za-z]+)\s*)?(\d+),\s*(\d{4})\s*$/i', trim($billingPeriod), $matches)) {
                                    $startMonth = ucfirst(strtolower(substr($matches[1], 0, 3)));
                                    $startDay = $matches[2];
                                    $endMonth = isset($matches[3]) && $matches[3] ? ucfirst(strtolower(substr($matches[3], 0, 3))) : $startMonth;
                                    $endDay = $matches[4];
                                    $year = $matches[5];

                                    // If the months are the same, format as "Jun 1-30, 2024"
                                    if ($startMonth === $endMonth) {
                                        return "{$startMonth} {$startDay}-{$endDay}, {$year}";
                                    } else {
                                        // If the months are different, format as "Jun 1-Jul 6, 2024"
                                        return "{$startMonth} {$startDay}-{$endMonth} {$endDay}, {$year}";
                                    }
                                } else {
                                    // If no match, return original and log it for debugging
                                    error_log("Billing period format did not match: $billingPeriod");
                                    return $billingPeriod;
                                }
                            }

                            foreach ($data as $result) {
                                $otherCharges_query = $this->db->query("
                                SELECT debit 
                                FROM subsidiary_ledger 
                                WHERE doc_no = '{$result['doc_no']}' 
                                AND gl_accountID IN ('5', '22', '29')
                            ");

                                // Initialize variables
                                $OC_amount = 0; // Total Other Charges
                                $amount_noVat = 0; // Amount without VAT
                                $vat_amount = 0; // VAT (12%)

                                foreach ($otherCharges_query->result() as $row) {
                                    $OC_amount += floatval($row->debit); // Sum up all debit values
                                }

                                // Compute Amount Without VAT (Base Amount)
                                // $amount_noVat = round($OC_amount / 1.12, 3);
                                $amount_noVat = number_format($OC_amount / 1.12, 2, '.', '');

                                // Compute VAT (12% of Base Amount)
                                // $vat_amount = round($amount_noVat * 0.12, 2);
                                $vat_amount = number_format($amount_noVat * 0.12, 2, '.', '');


                                $pDate = date('F Y', strtotime($result['posting_date']));
                                $tenantID = str_replace('-', '-OC-', $result['tenant_id']);
                                $tradeName = substr($result['trade_name'], 0, 36);
                                $glAccountName = isset($glCodeMapping[$result['gl_code']]) ? $glCodeMapping[$result['gl_code']] : $result['gl_account'];
                                $dueDateFormatted = date('M d, Y', strtotime($result['due_date'])); // Format due date to abbreviated month
                                $formattedBillingPeriod = formatBillingPeriod($billing_period);

                                $vatDisplayed = false;

                                if (in_array($result['gl_code'], ['10.10.01.03.03', '10.10.01.03.04']) && $result['tag'] == 'Other') {
                                    $rows[] = ["GENERAL<|>{$line_no}<|>Customer<|>{$tenantID}<|>{$posting_date}<|><|>{$doc_no}<|>{$tradeName}-Other Charges<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>10.20.01.01.01.14<|>{$posting_date}<|><|>{$doc_no}<|>VAT Output<|>({$vat_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>",];
                                    $vatDisplayed = true;
                                    $line_no += 10000;
                                } elseif ($result['gl_code'] == '20.80.01.08.01') {
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$glAccountName}<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>",];
                                    $line_no += 10000;
                                    // Add a new line for VAT Output
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>10.20.01.01.01.14<|>{$posting_date}<|><|>{$doc_no}<|>VAT Output<|>({$vat_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>",];
                                } elseif ($result['gl_code'] == '10.10.01.06.05') {
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$glAccountName}<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>",];
                                    $line_no += 10000;
                                } elseif ($result['gl_code'] == '20.80.01.08.07') {
                                    $amawnt = str_replace('-', '', $result['amount']);
                                    $invoicing = $this->db->query("SELECT * FROM invoicing WHERE balance = '{$amawnt}' AND doc_no = '{$result['doc_no']}'")->row();

                                    $amount_noVat = number_format($amawnt / 1.12, 2, '.', '');

                                    switch ($invoicing->charges_code) {
                                        case 'PC000007':
                                            $customDescription = 'Gas';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000008':
                                            $customDescription = 'Pest Control';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000018':
                                            $customDescription = 'Penalty for Late Opening and Early Closing';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000020':
                                            $customDescription = 'Worker ID';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000021':
                                            $customDescription = 'Plywood Enclosure';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000022':
                                            $customDescription = 'PVC Door and Lock Set';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000023':
                                            $customDescription = 'Bio Augmentation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000024':
                                            $customDescription = 'Service Request';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000025':
                                            $customDescription = 'Overtime and Overnight';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000028':
                                            $customDescription = 'Security Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000029':
                                            $customDescription = 'Exhaust Duct Cleaning Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000033':
                                            $customDescription = 'Storage Room Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000036':
                                            $customDescription = 'Adbox Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000038':
                                            $customDescription = 'Houserules Violation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000039':
                                            $customDescription = 'Notary Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000043':
                                            $customDescription = 'Service Request from ASC Construction';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000044':
                                            $customDescription = 'Penalty House Rules Violation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000046':
                                            $customDescription = 'Bannerboard Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000047':
                                            $customDescription = 'Led Wall';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000050':
                                            $customDescription = 'Others';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000051':
                                            $customDescription = 'Adjustment VAT Output';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000054':
                                            $customDescription = 'Glass Service';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000056':
                                            $customDescription = 'Standy';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000057':
                                            $customDescription = 'Sprinkler Water Draining Charging';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000067':
                                            $customDescription = 'Plywood_Enclosure';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000068':
                                            $customDescription = 'During Construction Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000071':
                                            $customDescription = 'Alturush Food Delivery';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000074':
                                            $customDescription = 'Electricity 01';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000076':
                                            $customDescription = 'Electricity Freezer';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000077':
                                            $customDescription = 'Telephone Bill';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000078':
                                            $customDescription = 'Pylon Signage';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000079':
                                            $customDescription = 'LED BILLBOARD';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000080':
                                            $customDescription = 'Billboard';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000081':
                                            $customDescription = 'Management Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000082':
                                            $customDescription = 'Regulatory Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000083':
                                            $customDescription = 'Internet Connection';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                            $line_no += 10000;
                                            break;

                                    }
                                    $customDescription = '';

                                } else {
                                    $amount = str_replace('-', '', $result['amount']);
                                    $nv_amount = number_format($amount / 1.12, 2, '.', '');

                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa_no}-{$glAccountName}<|>({$nv_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                                    $line_no += 10000;
                                }

                                // if (!$vatDisplayed && in_array($result['gl_code'], ['10.10.01.03.03', '10.10.01.03.04'])) {
                                //     $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>10.20.01.01.01.14<|>{$posting_date}<|><|>{$doc_no}<|>VAT Output<|>({$vat_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>",];

                                //     $line_no += 10000; // Ensure VAT gets a unique line number
                                //     $vatDisplayed = true;
                                // }

                                $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            }
                            // dump($rows);
                            // exit();

                            $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            $filter_date = date('Y-m', strtotime($posting_date));
                            $file_name = $exp_batch_no . "_Other_Charges_" . $value['tenant_id'] . '_' . $filter_date . ".pjn";
                            $store = $this->session->userdata('store_code');
                            // $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/others/' . $monthfolder["$m"] . '/' . $file_name;
                            $targetPath = getcwd() . '/assets/for_cas/other/' . $file_name;
                            $file_data = arrayToString($rows);

                            $toUpdate = ($value['cas_doc_no'] != '') ? ['export_batch_code' => $exp_batch_no] : ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];

                            foreach ($doc_nos as $doc_no) {
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                            }

                            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Other Charges', $file_name);
                            file_put_contents($targetPath, $file_data);

                            $linesCounter = $rows;
                        }
                    }
                }

                if (!empty($linesCounter)) {
                    $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
                } else {
                    $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
                }
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }


        return $msg;
    }
    public function generate_paymentCollection($tenant_id, $posting_date)
    {
        $store = $this->session->userdata('store_code');
        $post_date = date('Y-m', strtotime($posting_date));
        $m = date('m', strtotime($posting_date));
        $Pmtreports = $this->app_model->generate_paymentCollection($post_date, "AND g.tenant_id = '{$tenant_id}'");

        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC'
        ];

        $REFERENCE = "";
        $CRJENTRY = [];

        if (!empty($Pmtreports)) {
            try {
                foreach ($Pmtreports as $key => $value) {
                    $DATE = date('m/d/Y', strtotime($value->posting_date));
                    $SOA = substr($value->doc_no, 0, 3);
                    $DOCUMENT = $value->doc_no;
                    $TRADENAME = substr($value->trade_name, 0, 41);
                    $AMOUNT = str_replace('-', '', $value->amount);
                    $BATCHNAME = ($value->tag == 'Preop') ? 'ACK_RCPT' : 'OFCL_RCPT';
                    $TENDERTYPE = $this->app_model->getPaymetSchemeDetails($value->doc_no, $value->tenant_id);
                    $bankAcroname = $this->app_model->getBankAcroname($value->bank_code);
                    $TTYPE = "";

                    if ($SOA === "SOA") {
                        if ($REFERENCE !== $value->doc_no) {
                            if ($value->gl_accountID !== '7') {
                                $line_no = 10000;
                                $CRJENTRY = [];
                                $DOCUMENT = (in_array($value->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                                $TENANTID = (in_array($value->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>{$AMOUNT}<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;

                                if ($value->tag == 'Preop') {
                                    $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                    $company_code = $this->session->userdata('company_code');
                                    $department_code = $this->session->userdata('dept_code');
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                    }
                                } else {
                                    $invoice = $this->app_model->getInvoice($value->ref_no);
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                    }
                                }

                                #EXTRACT HERE
                                $TOUPDATE1 = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $TOUPDATE2 = "doc_no = '$value->doc_no' AND gl_accountID = '7' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE1);
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE2);
                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                                $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                                $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                                # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                                $data = arrayToString($CRJENTRY);

                                $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                                file_put_contents($targetPath, $data);
                                #EXTRACT HERE
                            }
                        } else if ($REFERENCE === $value->doc_no) {
                            if ($value->gl_accountID !== '7') {
                                $line_no = 10000;
                                $CRJENTRY = [];

                                $DOCUMENT = (in_array($value->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                                $TENANTID = (in_array($value->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>{$AMOUNT}<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;

                                if ($value->tag == 'Preop') {
                                    $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                    $company_code = $this->session->userdata('company_code');
                                    $department_code = $this->session->userdata('dept_code');
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                    }
                                } else {
                                    $invoice = $this->app_model->getInvoice($value->ref_no);
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                    }
                                }

                                #EXTRACT HERE
                                $TOUPDATE1 = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $TOUPDATE2 = "doc_no = '$value->doc_no' AND gl_accountID = '7' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE1);
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE2);
                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                                $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                                $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                                # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                                $data = arrayToString($CRJENTRY);

                                $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                                file_put_contents($targetPath, $data);
                                #EXTRACT HERE
                            }
                        }

                        $REFERENCE = $value->doc_no;
                    } else {
                        if ($TENDERTYPE) {
                            switch ($TENDERTYPE->tender_typeDesc) {
                                case 'Bank to Bank':
                                    $TTYPE = 'Online: DS#' . $TENDERTYPE->check_no . ' Bank: ' . $bankAcroname->acroname;
                                    break;
                                case 'Check':
                                    $acroname = (!empty($bankAcroname)) ? $bankAcroname->acroname : '';
                                    $TTYPE = $TENDERTYPE->tender_typeDesc . ': Check#' . $TENDERTYPE->check_no . ' Bank: ' . $acroname;
                                    break;
                                case 'Cash':
                                    $TTYPE = 'Cash : ' . $value->amount;
                                    break;
                                default:
                                    $gl = $this->app_model->getGlAccount($value->gl_accountID);
                                    $TTYPE = ($value->gl_accountID == '3') ? 'Cash : ' . $value->amount : $gl->gl_account . ' : ' . $value->amount;
                                    break;
                            }
                        } else {
                            $gl = $this->app_model->getGlAccount($value->gl_accountID);
                            $TTYPE = ($value->gl_accountID == '3') ? 'Cash : ' . $value->amount : $gl->gl_account . ' : ' . $value->amount;
                        }

                        $GLCODE = $this->app_model->getInvoice($value->ref_no);

                        if (!empty($GLCODE)) {
                            $DOCUMENT = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                            $TENANTID = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                        } else {
                            $DOCUMENT = $DOCUMENT;
                            $TENANTID = $value->tenant_id;
                        }

                        $ACK_URI = $this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no = '$value->ref_no' AND credit LIKE '%{$value->amount}%'")->ROW();

                        if (!empty($ACK_URI)) {
                            if ($ACK_URI->gl_accountID == '7') {
                                $BATCHNAME = "ACK_RCPT";
                                $DOCUMENT = "URI-{$DOCUMENT}";
                                $TENANTID = str_replace('-', '-URI-', $value->tenant_id);

                            }
                        }

                        if ($REFERENCE !== $value->ref_no):
                            $line_no = 10000;
                            $CRJENTRY = [];
                            $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Bank Account<|>{$value->bank_code}<|>$DATE<|>$value->document_type<|>$DOCUMENT<|>{$value->bank_name}<|>$value->amount<|>$value->company_code<|>$value->department_code<|>CASHRECJNL<|>$BATCHNAME<|><|><|><|>{$TTYPE}<|><|>"];

                            $line_no += 10000;
                        elseif ($REFERENCE === $value->ref_no):
                            if ($value->gl_accountID == '7') {
                                $BATCHNAME = "ACK_RCPT";
                                // $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>URI-{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                // $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>URI-{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            } else {
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            }

                            $line_no += 10000;

                            if ($value->tag == 'Preop') {
                                $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                $company_code = $this->session->userdata('company_code');
                                $department_code = $this->session->userdata('dept_code');

                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                }
                            } else {
                                $invoice = $this->app_model->getInvoice($value->ref_no);
                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                }
                            }

                            # EXTRACT HERE
                            $TOUPDATE = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                            $exp_batch_no = $this->app_model->generate_ExportNo(true);

                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                            $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE);
                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                            $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                            $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                            # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                            $data = arrayToString($CRJENTRY);

                            $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                            file_put_contents($targetPath, $data);
                            # EXTRACT HERE
                        endif;

                        $REFERENCE = $value->ref_no;
                    }
                }

                $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No more data to generate as textfile. Data might be extracted already.', 'info' => 'empty'];
        }

        return $msg;
    }
    public function generate_PreopReports($tenant_id, $posting_date)
    {
        $month = $posting_date;
        $month = date('F Y', strtotime($month));
        $store = $this->session->userdata('store_code');
        $billing_period = $this->input->post('billing_period');
        $soa_no = $this->input->post('soa_no');
        // $tntID = "AND tenant_id = '{$tenant_id}'";
        // $ARreports = $this->app_model->generate_PreOpReports($month, $tntID);
        $docNumber = "AND doc_no LIKE '%{$tenant_id}%'";
        $ARreports = $this->app_model->generate_PreOpReports($month, $docNumber);



        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC',
        ];
        $m = date('m', strtotime($month));

        if (!empty($ARreports['data'])) {
            try {
                foreach ($ARreports['data'] as $key => $value) {
                    $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                    $company_code = $this->session->userdata('company_code');
                    $dept_code = $this->session->userdata('dept_code');
                    // $doc_no        = 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));

                    $doc_no = ($value['cas_doc_no'] != '') ? $value['cas_doc_no'] : 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                    $report_data = $this->app_model->generate_PreOpReports($month, "AND tenant_id LIKE '%{$value['tenant_id']}%'");
                    $data = $report_data['data'];
                    $doc_nos = $report_data['doc_nos'];
                    $file_data = '';
                    $externalDocNo = '';

                    if (!empty($data)) {
                        $rows = [];
                        $line_no = 10000;
                        $tradeName = '';
                        $preopAmount = 0;
                        $preopDate = $data[0]['posting_date'];
                        $dd_no = $doc_no . '-' . $data[0]['doc_no'];

                        function formatBillingPeriod($billingPeriod)
                        {
                            // Debugging output for original billing period
                            error_log("Original billing period: $billingPeriod");

                            // Flexible regex to capture variations in format and capitalization
                            if (preg_match('/^\s*([A-Za-z]+)\s+(\d+)\s*-\s*(?:([A-Za-z]+)\s*)?(\d+),\s*(\d{4})\s*$/i', trim($billingPeriod), $matches)) {
                                $startMonth = ucfirst(strtolower(substr($matches[1], 0, 3)));
                                $startDay = $matches[2];
                                $endMonth = isset($matches[3]) && $matches[3] ? ucfirst(strtolower(substr($matches[3], 0, 3))) : $startMonth;
                                $endDay = $matches[4];
                                $year = $matches[5];

                                // If the months are the same, format as "Jun 1-30, 2024"
                                if ($startMonth === $endMonth) {
                                    return "{$startMonth} {$startDay}-{$endDay}, {$year}";
                                } else {
                                    // If the months are different, format as "Jun 1-Jul 6, 2024"
                                    return "{$startMonth} {$startDay}-{$endMonth} {$endDay}, {$year}";
                                }
                            } else {
                                // If no match, return original and log it for debugging
                                error_log("Billing period format did not match: $billingPeriod");
                                return $billingPeriod;
                            }
                        }

                        foreach ($data as $result) {
                            $preopAmount += $result['amount'];
                        }

                        $dueDateFormatted = date('M d, Y', strtotime($result['due_date'])); // Format due date to abbreviated month
                        $formattedBillingPeriod = formatBillingPeriod($billing_period);

                        // $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$data[0]['tenant_id']}<|>$preopDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$data[0]['trade_name']}-Preop<|>{$preopAmount}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$data[0]['doc_no']}<|><|>"];
                        $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$data[0]['tenant_id']}<|>$preopDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$data[0]['trade_name']}-Preop<|>{$preopAmount}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                        $line_no += 10000;

                        foreach ($data as $result) {
                            $pDate = date('m/d/Y', strtotime($result['posting_date']));
                            $tradeName = substr($result['trade_name'], 0, 45);
                            $dueDateFormatted = date('M d, Y', strtotime($result['due_date'])); // Format due date to abbreviated month
                            $formattedBillingPeriod = formatBillingPeriod($billing_period);

                            if ($result['description'] == 'Construction Bond') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.10<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.10<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            } else if ($result['description'] == 'Security Deposit - Kiosk and Cart' || $result['description'] == 'Security Deposit') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.12<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.12<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$soa_no}-{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            } else if ($result['description'] == 'Advance Rent') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.02.01<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$result['doc_no']}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.02.01<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$soa_no}-{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            }

                            $line_no += 10000;

                            if ($value['cas_doc_no'] != '') {
                                $externalDocNo = $doc_no;
                            } else {
                                $externalDocNo = "{$doc_no}-{$result['doc_no']}";
                            }
                        }

                        $exp_batch_no = $this->app_model->generate_ExportNo(true);
                        $filter_date = date('Y-m', strtotime($posting_date));
                        $file_name = $exp_batch_no . "_Preop_" . $value['tenant_id'] . "_" . $filter_date . ".pjn";

                        $store = $this->session->userdata('store_code');
                        $targetPath = getcwd() . '/assets/for_cas/other/' . $file_name;
                        // $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/preop/' . $monthfolder["$m"] . '/' . $file_name;
                        $file_data = arrayToString($rows);

                        $toUpdate = "";

                        if ($value['cas_doc_no'] != '') {
                            $toUpdate = ['export_batch_code' => $exp_batch_no];
                        } else {
                            $toUpdate = ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];
                        }

                        foreach ($doc_nos as $doc_no) {
                            $this->app_model->updateEntryAsExported('tmp_preoperationcharges', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                        }

                        $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Preop', $file_name);
                        file_put_contents($targetPath, $file_data);
                    }
                }
                $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }

        return $msg;
    }
    #------------------------------------------------------ AUTO EXTRACTION ------------------------------------------------------#


    #------------------------------------------------------ MANUAL EXTRACTION ------------------------------------------------------#
    # FOR EXTRACTION CAS TEXT FILE
    public function generate_RRreports_manual()
    {
        $rrData = $this->input->post(null);
        $month = $rrData['month'];
        $month = date('F Y', strtotime($month));
        $store = $this->session->userdata('store_code');
        $upload_by_type = $rrData['upload_by_type'];
        $tntID = "AND tenant_id LIKE '%{$rrData['searchInput']}%'";
        $docNumber = "AND doc_no LIKE '%{$rrData['searchInput']}%'";


        switch ($upload_by_type) {
            case 'Tenant ID':
                $RRreports = $this->app_model->generate_RRreports($month, $tntID);
                break;
            case 'Document No.':
                $RRreports = $this->app_model->generate_RRreports($month, $docNumber);
                break;
            default:
                $RRreports = $this->app_model->generate_RRreports($month, "AND tenant_id LIKE '%{$store}-{$rrData['tenancy_type']}%'");
        }


        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC'
        ];
        $m = date('m', strtotime($month));

        $linesCounter = [];

        if (!empty($RRreports['data'])) {
            try {
                foreach ($RRreports['data'] as $key => $value) {
                    $checkBalance = $this->app_model->checkBalance($value['doc_no'], $value['posting_date']);

                    if ($checkBalance->debit > 0) {
                        $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                        $company_code = $this->session->userdata('company_code');
                        $dept_code = $this->session->userdata('dept_code');
                        $doc_no = ($value['cas_doc_no'] != "") ? $value['cas_doc_no'] : 'BLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                        $report_data = $this->app_model->generate_RRreports($month, "AND tenant_id LIKE '%" . $value['tenant_id'] . "%'");
                        $data = $report_data['data'];
                        $doc_nos = $report_data['doc_nos'];
                        $file_data = '';
                        $externalDocNo = '';

                        if (!empty($data)) {
                            $rows = [];
                            $line_no = 10000;
                            $tradeName = '';

                            // foreach ($data as $result) {
                            //     $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$result['doc_no']}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                            //     if (!empty($soaLine)) {
                            //         $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                            //     } else {
                            //         $soa->billing_period = '';
                            //     }

                            //     $pDate = date('m/d/Y', strtotime($result['posting_date']));
                            //     $tradeName = substr($result['trade_name'], 0, 45);

                            //     if ($result['gl_code'] == '10.10.01.03.16') {
                            //         $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$doc_no}<|>{$tradeName}-Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];       
                            //     } elseif ($result['gl_code'] == '10.10.01.06.05') {
                            //         $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];           
                            //     } else {
                            //         $amount = str_replace('-', '', $result['amount']);
                            //         $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>($amount)<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];               
                            //     }

                            //     $line_no += 10000;

                            //     $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            // }

                            //========================================= gwaps ===============================================================
                            foreach ($data as $result) {
                                $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$result['doc_no']}' AND tenant_id = '{$result['tenant_id']}'")->row();

                                if (!empty($soaLine)) {
                                    $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$result['tenant_id']}'")->row();
                                } else {
                                    $soa = new stdClass();
                                    $soa->billing_period = '';
                                }

                                $billing_period = $soa->billing_period;
                                $month_mapping = [
                                    'JANUARY' => 'Jan',
                                    'FEBRUARY' => 'Feb',
                                    'MARCH' => 'Mar',
                                    'APRIL' => 'Apr',
                                    'MAY' => 'May',
                                    'JUNE' => 'Jun',
                                    'JULY' => 'Jul',
                                    'AUGUST' => 'Aug',
                                    'SEPTEMBER' => 'Sep',
                                    'OCTOBER' => 'Oct',
                                    'NOVEMBER' => 'Nov',
                                    'DECEMBER' => 'Dec',
                                ];
                                foreach ($month_mapping as $full => $abbrev) {
                                    if (stripos($billing_period, $full) !== false) {
                                        $billing_period = str_ireplace($full, $abbrev, $billing_period);
                                        break;
                                    }
                                }

                                $due_date = date('M j, Y', strtotime($result['due_date']));

                                $pDate = date('m/d/Y', strtotime($result['posting_date']));
                                $tradeName = substr($result['trade_name'], 0, 45);
                                $doc_no = ($value['cas_doc_no'] != "") ? $value['cas_doc_no'] : 'BLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));

                                if ($result['gl_code'] == '10.10.01.03.16') {
                                    $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$result['tenant_id']}<|>$pDate<|><|>{$doc_no}<|>{$result['soa_no']}-Basic Rent<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                } elseif ($result['gl_code'] == '10.10.01.06.05') {
                                    $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                } else {
                                    $amount = str_replace('-', '', $result['amount']);
                                    $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>{$result['gl_code']}<|>$pDate<|><|>{$doc_no}<|>{$result['gl_account']}<|>$amount<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$billing_period};{$due_date}<|><|>"];
                                }

                                $line_no += 10000;
                                $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            }
                            //========================================= gwaps ends ==========================================================

                            // dump($rows);
                            $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            $filter_date = date('Y-m', strtotime($posting_date));
                            $file_name = "Basic Rent_" . $value['tenant_id'] . '_' . "$filter_date - " . $exp_batch_no . '.pjn';
                            // $targetPath   = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/rent/' . $monthfolder["$m"] . '/' . $file_name;
                            // $targetPath = getcwd() . '/assets/for_cas/basic_rent/' . $file_name;
                            $targetPath = getcwd() . '/assets/for_cas/basic_rent/' . $monthfolder["$m"] . '/' . $file_name; // gwaps
                            $file_data = arrayToString($rows);

                            $toUpdate = ($value['cas_doc_no'] != '') ? ['export_batch_code' => $exp_batch_no] : ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];

                            foreach ($doc_nos as $doc_no) {
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                            }

                            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Basic Rent', $file_name);

                            file_put_contents($targetPath, $file_data);

                            $linesCounter = $rows;
                        }
                    }
                }

                if (!empty($linesCounter)) {
                    $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
                } else {
                    $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
                }
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }

        echo json_encode($msg);
    }
    #YAWA NING CAS YWAW NING BIR YAWA NI TANAN - IF EVER MAHIMO KAG PROGRAMMER ANING LEASING, AYAW NA PADAYON, LABAD SA ULO RAY MAKUHA NIMO
    public function generate_ARreports_manual(){
        $arData         = $this->input->post(null);
        $month          = $arData['month'];
        $month          = date('F Y', strtotime($month));
        $store          = $this->session->userdata('store_code');
        $upload_by_type = $arData['upload_by_type'];
        $tntID          = "AND sl.tenant_id LIKE '%{$arData['searchInput']}%'";
        $docNumber      = "AND sl.doc_no LIKE '%{$arData['searchInput']}%'";

        switch ($upload_by_type) {
            case 'Tenant ID':
                $ARreports = $this->app_model->generate_ARreports($month, $tntID);
                break;
            case 'Document No.':
                $ARreports = $this->app_model->generate_ARreports($month, $docNumber);
                break;
            default:
                $ARreports = $this->app_model->generate_ARreports($month, "AND sl.tenant_id LIKE '%{$store}-{$arData['AR_tenancy_type']}%'");
        }


        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC'
        ];

        $m = date('m', strtotime($month));
        $linesCounter = [];

        if (!empty($ARreports['data'])) {
            try {
                foreach ($ARreports['data'] as $key => $value) {
                    $checkBalance = $this->app_model->checkBalance($value['doc_no'], $value['posting_date']);
                    if ($checkBalance->debit > 0) {
                        $posting_date   = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                        $company_code   = $this->session->userdata('company_code');
                        $dept_code      = $this->session->userdata('dept_code');
                        $date           = new DateTime();
                        $timeStamp      = $date->getTimestamp();
                        $doc_no         = ($value['cas_doc_no'] != '') ? $value['cas_doc_no'] : 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                        $report_data    = $this->app_model->generate_ARreports($month, "AND sl.tenant_id LIKE '%{$value['tenant_id']}%'");
                        $data           = $report_data['data'];
                        $doc_nos        = $report_data['doc_nos'];
                        $file_data      = '';
                        $externalDocNo  = '';

                        if (!empty($data)) {
                            $line_no    = 10000;
                            $rows       = [];

                            $glCodeMapping = [
                                '10.10.01.06.05' => 'Expanded Withholding Tax',
                                '20.80.01.08.04' => 'Aircon',
                                '20.80.01.08.08' => 'Water',
                                '20.80.01.08.05' => 'Chilled Water',
                                '20.80.01.08.03' => 'Common Usage Charges',
                                '20.80.01.08.02' => 'Electricity Charge',
                                '20.80.01.08.01' => 'Penalty',
                            ];

                            // Sort $data by gl_code in the specified order
                            usort($data, function ($a, $b) {
                                $order = [
                                    '10.10.01.03.03',
                                    '10.10.01.06.05',
                                    '20.80.01.08.01',
                                    '10.10.01.03.04',
                                    '20.80.01.08.04',
                                    // Aircon
                                    '20.80.01.08.08',
                                    // Water
                                    '20.80.01.08.05',
                                    // Chilled Water
                                    '20.80.01.08.03',
                                    // Common Usage Charges
                                    '20.80.01.08.02',
                                    // Electricity
                                    '20.80.01.08.07' // Charges

                                ];
                                return array_search($a['gl_code'], $order) - array_search($b['gl_code'], $order);
                            });

                            function formatBillingPeriod($billingPeriod){
                                error_log("Original billing period: $billingPeriod");
                                // Flexible regex to capture variations in format and capitalization
                                if (preg_match('/^\s*([A-Za-z]+)\s+(\d+)\s*-\s*(?:([A-Za-z]+)\s*)?(\d+),\s*(\d{4})\s*$/i', trim($billingPeriod), $matches)) {
                                    $startMonth = ucfirst(strtolower(substr($matches[1], 0, 3)));
                                    $startDay   = $matches[2];
                                    $endMonth   = isset($matches[3]) && $matches[3] ? ucfirst(strtolower(substr($matches[3], 0, 3))) : $startMonth;
                                    $endDay     = $matches[4];
                                    $year       = $matches[5];

                                    // If the months are the same, format as "Jun 1-30, 2024"
                                    if ($startMonth === $endMonth) {
                                        return "{$startMonth} {$startDay}-{$endDay}, {$year}";
                                    } else {
                                        // If the months are different, format as "Jun 1-Jul 6, 2024"
                                        return "{$startMonth} {$startDay}-{$endMonth} {$endDay}, {$year}";
                                    }
                                } else {
                                    // If no match, return original and log it for debugging
                                    error_log("Billing period format did not match: $billingPeriod");
                                    return $billingPeriod;
                                }
                            }
                            // -----------------------------------------------------------------------

                            foreach ($data as $result) {
                                $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$result['doc_no']}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                                if (!empty($soaLine)) {
                                    $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                                } else {
                                    $soa->billing_period = '';
                                }

                                $otherCharges_query = $this->db->query("
                                    SELECT debit 
                                    FROM subsidiary_ledger 
                                    WHERE doc_no = '{$result['doc_no']}' 
                                    AND gl_accountID IN ('5', '22', '29')
                                ");

                                // Initialize variables
                                $OC_amount      = 0; // Total Other Charges
                                $amount_noVat   = 0; // Amount without VAT
                                $vat_amount     = 0; // VAT (12%)

                                foreach ($otherCharges_query->result() as $row) {
                                    $OC_amount += floatval($row->debit); // Sum up all debit values
                                }

                                // Compute Amount Without VAT (Base Amount)
                                // $amount_noVat = round($OC_amount / 1.12, 3);
                                $amount_noVat = number_format($OC_amount / 1.12, 2, '.', '');

                                // Compute VAT (12% of Base Amount)
                                // $vat_amount = round($amount_noVat * 0.12, 2);
                                $vat_amount = number_format($amount_noVat * 0.12, 2, '.', '');


                                $pDate                  = date('F Y', strtotime($result['posting_date']));
                                $tenantID               = str_replace('-', '-OC-', $result['tenant_id']);
                                $tradeName              = substr($result['trade_name'], 0, 36);
                                $glAccountName          = isset($glCodeMapping[$result['gl_code']]) ? $glCodeMapping[$result['gl_code']] : $result['gl_account'];
                                $dueDateFormatted       = date('M d, Y', strtotime($result['due_date']));
                                $formattedBillingPeriod = formatBillingPeriod($soa->billing_period);
                                $vatDisplayed           = false;

                                if (in_array($result['gl_code'], ['10.10.01.03.03', '10.10.01.03.04']) && $result['tag'] === 'Other') { //A/R Non Trade
                                    $invoicing  = $this->db->query("SELECT * FROM invoicing WHERE doc_no = '{$soa->soa_no}'")->row();//Added by Linie
                                    $nv_penalty = number_format($invoicing->actual_amt / 1.12, 2, '.', '');//Added by Linie
                                    $oc_amount  = number_format($result['amount'] + $nv_penalty, 2, '.', '');//Added by Linie
                                    array_shift($rows);//Added by Linie
                                    $rows[] = ["GENERAL<|>{$line_no}<|>Customer<|>{$tenantID}<|>{$posting_date}<|><|>{$doc_no}<|>{$tradeName}-Other Charges<|>{$oc_amount}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>",];
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>10.20.01.01.01.14<|>{$posting_date}<|><|>{$doc_no}<|>VAT Output<|>({$vat_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>",];
                                    $vatDisplayed = true;
                                    $line_no += 10000;
                                } elseif ($result['gl_code'] == '10.10.01.06.05') { //Creditable WHT Receivable
                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$glAccountName}<|>{$result['amount']}<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>",];
                                    // Increment line number for the new row
                                    $line_no += 10000;
                                } elseif ($result['gl_code'] == '20.80.01.08.07') { //MI-Charges
                                    $amawnt = str_replace('-', '', $result['amount']);
                                    $invoicing = $this->db->query("SELECT * FROM invoicing WHERE balance = '{$amawnt}' AND doc_no = '{$result['doc_no']}'")->row();
                                    // $amount_noVat = round($amawnt / 1.12, 2); // Amount without VAT
                                    $amount_noVat = number_format($amawnt / 1.12, 2, '.', '');

                                    switch ($invoicing->charges_code) {
                                        case 'PC000007':
                                            $customDescription = 'Gas';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000008':
                                            $customDescription = 'Pest Control';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000018':
                                            $customDescription = 'Penalty for Late Opening and Early Closing';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000020':
                                            $customDescription = 'Worker ID';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000021':
                                            $customDescription = 'Plywood Enclosure';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000022':
                                            $customDescription = 'PVC Door and Lock Set';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000023':
                                            $customDescription = 'Bio Augmentation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000024':
                                            $customDescription = 'Service Request';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000025':
                                            $customDescription = 'Overtime and Overnight';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000028':
                                            $customDescription = 'Security Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000029':
                                            $customDescription = 'Exhaust Duct Cleaning Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000033':
                                            $customDescription = 'Storage Room Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000036':
                                            $customDescription = 'Adbox Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000038':
                                            $customDescription = 'Houserules Violation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000039':
                                            $customDescription = 'Notary Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000043':
                                            $customDescription = 'Service Request from ASC Construction';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000044':
                                            $customDescription = 'Penalty House Rules Violation';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000046':
                                            $customDescription = 'Bannerboard Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000047':
                                            $customDescription = 'Led Wall';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000050':
                                            $customDescription = 'Others';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000051':
                                            $customDescription = 'Adjustment VAT Output';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000054':
                                            $customDescription = 'Glass Service';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000056':
                                            $customDescription = 'Standy';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000057':
                                            $customDescription = 'Sprinkler Water Draining Charging';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000067':
                                            $customDescription = 'Plywood_Enclosure';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000068':
                                            $customDescription = 'During Construction Charges';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000071':
                                            $customDescription = 'Alturush Food Delivery';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000074':
                                            $customDescription = 'Electricity 01';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000076':
                                            $customDescription = 'Electricity Freezer';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000077':
                                            $customDescription = 'Telephone Bill';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000078':
                                            $customDescription = 'Pylon Signage';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000079':
                                            $customDescription = 'LED BILLBOARD';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000080':
                                            $customDescription = 'Billboard';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000081':
                                            $customDescription = 'Management Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000082':
                                            $customDescription = 'Regulatory Fee';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                        case 'PC000083':
                                            $customDescription = 'Internet Connection';
                                            $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$customDescription}<|>({$amount_noVat})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}|><|>"];
                                            $line_no += 10000;
                                            break;
                                    }
                                    $customDescription = '';
                                } else {//MI-Charges
                                    $amount = str_replace('-', '', $result['amount']);
                                    // $nv_amount = round($amount / 1.12, 2);
                                    $nv_amount = number_format($amount / 1.12, 2, '.', '');

                                    $rows[] = ["GENERAL<|>{$line_no}<|>G/L Account<|>{$result['gl_code']}<|>{$posting_date}<|><|>{$doc_no}<|>{$soa->soa_no}-{$glAccountName}<|>({$nv_amount})<|>{$company_code}<|>{$dept_code}<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>",];
                                    $line_no += 10000; // Increment line number
                                }

                                $externalDocNo = ($value['cas_doc_no'] != '') ? $doc_no : "{$doc_no}-{$result['doc_no']}";
                            }

                            // dump($rows);
                            // exit();

                            $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            $filter_date = date('Y-m', strtotime($posting_date));
                            $file_name = "Other_Charges_" . $value['tenant_id'] . '_' . $filter_date . "-" . $exp_batch_no . ".pjn";
                            $store = $this->session->userdata('store_code');
                            // $targetPath   = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/others/' . $monthfolder["$m"] . '/' . $file_name;
                            $targetPath = getcwd() . '/assets/for_cas/other/' . $file_name;
                            $file_data = arrayToString($rows);
                            $toUpdate = ($value['cas_doc_no'] != '') ? ['export_batch_code' => $exp_batch_no] : ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];

                            foreach ($doc_nos as $doc_no) {
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                            }

                            $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Other Charges', $file_name);
                            file_put_contents($targetPath, $file_data);

                            $linesCounter = $rows;
                        }
                    }
                }

                if (!empty($linesCounter)) {
                    $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
                } else {
                    $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
                }
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }

        echo json_encode($msg);
    }
    public function generate_paymentCollection_manual()
    {
        $pData = $this->input->post(null);
        $store = $this->session->userdata('store_code');
        $upload_by_type = $pData['upload_by_type'];
        $post_date = $pData['month'];
        $post_date = date('Y-m', strtotime($post_date));
        $m = date('m', strtotime($post_date));

        switch ($upload_by_type) {
            case 'Tenant ID':
                $Pmtreports = $this->app_model->generate_paymentCollection($post_date, "AND g.tenant_id LIKE '%{$pData['searchInput']}%'");
                break;
            case 'Document No.':
                $Pmtreports = $this->app_model->generate_paymentCollection($post_date, "AND g.doc_no LIKE '%{$pData['searchInput']}%'");
                break;
            default:
                $Pmtreports = $this->app_model->generate_paymentCollection($post_date, "AND g.tenant_id LIKE '%{$store}-{$pData['p_tenancy_type']}%'");
        }



        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC'
        ];

        $REFERENCE = "";
        $CRJENTRY = [];

        if (!empty($Pmtreports)) {
            try {
                foreach ($Pmtreports as $key => $value) {
                    $DATE = date('m/d/Y', strtotime($value->posting_date));
                    $SOA = substr($value->doc_no, 0, 3);
                    $DOCUMENT = $value->doc_no;
                    $TRADENAME = substr($value->trade_name, 0, 41);
                    $AMOUNT = str_replace('-', '', $value->amount);
                    $BATCHNAME = ($value->tag == 'Preop') ? 'ACK_RCPT' : 'OFCL_RCPT';
                    $TENDERTYPE = $this->app_model->getPaymetSchemeDetails($value->doc_no, $value->tenant_id);
                    $bankAcroname = $this->app_model->getBankAcroname($value->bank_code);
                    $TTYPE = "";

                    if ($SOA === "SOA") {
                        if ($REFERENCE !== $value->doc_no) {
                            if ($value->gl_accountID !== '7') {
                                $line_no = 10000;
                                $CRJENTRY = [];

                                $DOCUMENT = (in_array($value->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                                $TENANTID = (in_array($value->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>{$AMOUNT}<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;

                                if ($value->tag == 'Preop') {
                                    $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                    $company_code = $this->session->userdata('company_code');
                                    $department_code = $this->session->userdata('dept_code');
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                    }
                                } else {
                                    $invoice = $this->app_model->getInvoice($value->ref_no);
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                    }
                                }

                                #EXTRACT HERE
                                $TOUPDATE1 = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $TOUPDATE2 = "doc_no = '$value->doc_no' AND gl_accountID = '7' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE1);
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE2);
                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                                $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                                $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                                # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                                $data = arrayToString($CRJENTRY);

                                $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                                file_put_contents($targetPath, $data);
                                #EXTRACT HERE
                            }
                        } else if ($REFERENCE === $value->doc_no) {
                            if ($value->gl_accountID !== '7') {
                                $line_no = 10000;
                                $CRJENTRY = [];

                                $DOCUMENT = (in_array($value->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                                $TENANTID = (in_array($value->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>{$AMOUNT}<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                $line_no += 10000;

                                if ($value->tag == 'Preop') {
                                    $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                    $company_code = $this->session->userdata('company_code');
                                    $department_code = $this->session->userdata('dept_code');
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                    }
                                } else {
                                    $invoice = $this->app_model->getInvoice($value->ref_no);
                                    if (!empty($invoice)) {
                                        $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                    }
                                }

                                #EXTRACT HERE
                                $TOUPDATE1 = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $TOUPDATE2 = "doc_no = '$value->doc_no' AND gl_accountID = '7' AND (export_batch_code IS NULL OR export_batch_code = '')";
                                $exp_batch_no = $this->app_model->generate_ExportNo(true);

                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE1);
                                $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE2);
                                # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                                $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                                $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                                # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                                $data = arrayToString($CRJENTRY);

                                $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                                file_put_contents($targetPath, $data);
                                #EXTRACT HERE
                            }
                        }

                        $REFERENCE = $value->doc_no;
                    } else {
                        if ($TENDERTYPE) {
                            switch ($TENDERTYPE->tender_typeDesc) {
                                case 'Bank to Bank':
                                    $TTYPE = 'Online: DS#' . $TENDERTYPE->check_no . ' Bank: ' . $bankAcroname->acroname;
                                    break;
                                case 'Check':
                                    $acroname = (!empty($bankAcroname)) ? $bankAcroname->acroname : '';
                                    $TTYPE = $TENDERTYPE->tender_typeDesc . ': Check#' . $TENDERTYPE->check_no . ' Bank: ' . $acroname;
                                    break;
                                case 'Cash':
                                    $TTYPE = 'Cash : ' . $value->amount;
                                    break;
                                default:
                                    $gl = $this->app_model->getGlAccount($value->gl_accountID);
                                    $TTYPE = ($value->gl_accountID == '3') ? 'Cash : ' . $value->amount : $gl->gl_account . ' : ' . $value->amount;
                                    break;
                            }
                        } else {
                            $gl = $this->app_model->getGlAccount($value->gl_accountID);
                            $TTYPE = ($value->gl_accountID == '3') ? 'Cash : ' . $value->amount : $gl->gl_account . ' : ' . $value->amount;
                        }

                        $GLCODE = $this->app_model->getInvoice($value->ref_no);

                        if (!empty($GLCODE)) {
                            $DOCUMENT = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                            $TENANTID = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $value->tenant_id) : $value->tenant_id;
                        } else {
                            $DOCUMENT = $DOCUMENT;
                            $TENANTID = $value->tenant_id;
                        }

                        $ACK_URI = $this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no = '$value->ref_no' AND credit LIKE '%{$value->amount}%'")->ROW();

                        if (!empty($ACK_URI)) {
                            if ($ACK_URI->gl_accountID == '7') {
                                $BATCHNAME = "ACK_RCPT";
                                $DOCUMENT = "URI-{$DOCUMENT}";
                                $TENANTID = str_replace('-', '-URI-', $value->tenant_id);

                            }
                        }

                        // var_dump('REFERENCE: ' . $REFERENCE);
                        if ($REFERENCE !== $value->ref_no):

                            $line_no = 10000;
                            $CRJENTRY = [];
                            $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Bank Account<|>{$value->bank_code}<|>$DATE<|>$value->document_type<|>$DOCUMENT<|>{$value->bank_name}<|>$value->amount<|>$value->company_code<|>$value->department_code<|>CASHRECJNL<|>$BATCHNAME<|><|><|><|>{$TTYPE}<|><|>"];

                            $line_no += 10000;
                        elseif ($REFERENCE === $value->ref_no):

                            // $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            // var_dump('exp batch no: ' . $exp_batch_no);
                            // die;
                            if ($value->gl_accountID == '7') {
                                $BATCHNAME = "ACK_RCPT";
                                // $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>G/L Account<|>10.20.01.01.02.01<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>URI-{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                                // $line_no += 10000;
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>URI-{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            } else {
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            }

                            $line_no += 10000;

                            if ($value->tag == 'Preop') {
                                $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                                $company_code = $this->session->userdata('company_code');
                                $department_code = $this->session->userdata('dept_code');

                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                }
                            } else {
                                $invoice = $this->app_model->getInvoice($value->ref_no);
                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                }
                            }


                            //================================ gwaps start ===============================================
                            // if ($REFERENCE !== $value->ref_no){
                            //     $line_no = 10000;
                            //     $CRJENTRY = [];
                            //     $CRJENTRY[] = ["aaCASHRCPT<|>{$line_no}<|>Bank Account<|>{$value->bank_code}<|>$DATE<|>$value->document_type<|>$DOCUMENT<|>{$value->bank_name}<|>$value->amount<|>$value->company_code<|>$value->department_code<|>CASHRECJNL<|>$BATCHNAME<|><|><|><|>{$TTYPE}<|><|>"];
                            //    $line_no += 10000;
                            //    }  elseif ($REFERENCE === $value->ref_no) {
                            //                 if ($value->gl_accountID == '7') {
                            //                     $BATCHNAME = "ACK_RCPT";
                            //                     $CRJENTRY[] = ["bbCASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>URI-{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            //                 } 
                            // }
                            // //  else {
                            //     $CRJENTRY[] = ["ccCASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$value->document_type}<|>{$DOCUMENT}<|>{$TRADENAME}<|>({$AMOUNT})<|>{$value->company_code}<|>{$value->department_code}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            // // }
                            //     $line_no += 10000;

                            // if ($value->tag == 'Preop') {
                            //     $invoice = $this->app_model->getPreopInvoice($value->cas_doc_no);
                            //     $company_code = $this->session->userdata('company_code');
                            //     $department_code = $this->session->userdata('dept_code');

                            //     if (!empty($invoice)) {
                            //         $CRJENTRY[] = ["ddCASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                            //     }
                            // } else {
                            //     $invoice = $this->app_model->getInvoice($value->ref_no);
                            //     if (!empty($invoice)) {
                            //         $CRJENTRY[] = ["eeCASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                            //     }
                            // }

                            //    $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            //    var_dump($exp_batch_no);
                            //    die;
                            //================================ gwaps end ===============================================

                            # EXTRACT HERE
                            $TOUPDATE = "doc_no = '$value->doc_no' AND ref_no = '$value->ref_no' AND (export_batch_code IS NULL OR export_batch_code = '')";
                            $exp_batch_no = $this->app_model->generate_ExportNo(true);
                            var_dump($exp_batch_no);
                            die;

                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                            $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE);
                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                            $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                            $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                            # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                            $data = arrayToString($CRJENTRY);

                            $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                            file_put_contents($targetPath, $data);
                            # EXTRACT HERE
                        endif;

                        $REFERENCE = $value->ref_no;
                    }
                }

                $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No more data to generate as textfile. Data might be extracted already.', 'info' => 'empty'];
        }

        echo json_encode($msg);
    }
    public function generate_PreopReports_manual()
    {
        $arData = $this->input->post(null);
        $month = $arData['month'];
        $month = date('F Y', strtotime($month));
        $store = $this->session->userdata('store_code');
        $upload_by_type = $arData['upload_by_type'];
        $tntID = "AND tenant_id LIKE '%{$arData['searchInput']}%'";
        $docNumber = "AND doc_no LIKE '%{$arData['searchInput']}%'";
        $ARreports = [];

        // AND tenant_id LIKE '%$tenantid%'
        switch ($upload_by_type) {
            case 'Tenant ID':
                $ARreports = $this->app_model->generate_PreOpReports($month, $tntID);
                break;
            case 'Document No.':
                $ARreports = $this->app_model->generate_PreOpReports($month, $docNumber);
                break;
            default:
                $ARreports = $this->app_model->generate_PreOpReports($month, "AND tenant_id LIKE '%{$store}-{$arData['AR_tenancy_type']}%'");
        }

        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC'
        ];
        $m = date('m', strtotime($month));

        if (!empty($ARreports['data'])) {
            try {
                foreach ($ARreports['data'] as $key => $value) {
                    $posting_date = date('m/d/Y', strtotime(date('Y-m-t', strtotime($month))));
                    $company_code = $this->session->userdata('company_code');
                    $dept_code = $this->session->userdata('dept_code');
                    // $doc_no        = 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));

                    $doc_no = ($value['cas_doc_no'] != '') ? $value['cas_doc_no'] : 'OLS' . date('mdy', strtotime(date('Y-m-t', strtotime($month))));
                    $report_data = $this->app_model->generate_PreOpReports($month, "AND tenant_id LIKE '%{$value['tenant_id']}%'");
                    $data = $report_data['data'];
                    $doc_nos = $report_data['doc_nos'];
                    $file_data = '';
                    $externalDocNo = '';

                    if (!empty($data)) {
                        $rows = [];
                        $line_no = 10000;
                        $tradeName = '';
                        $preopAmount = 0;
                        $preopDate = $data[0]['posting_date'];
                        $dd_no = $doc_no . '-' . $data[0]['doc_no'];

                        function formatBillingPeriod($billingPeriod)
                        {
                            // Debugging output for original billing period
                            error_log("Original billing period: $billingPeriod");

                            // Flexible regex to capture variations in format and capitalization
                            if (preg_match('/^\s*([A-Za-z]+)\s+(\d+)\s*-\s*(?:([A-Za-z]+)\s*)?(\d+),\s*(\d{4})\s*$/i', trim($billingPeriod), $matches)) {
                                $startMonth = ucfirst(strtolower(substr($matches[1], 0, 3)));
                                $startDay = $matches[2];
                                $endMonth = isset($matches[3]) && $matches[3] ? ucfirst(strtolower(substr($matches[3], 0, 3))) : $startMonth;
                                $endDay = $matches[4];
                                $year = $matches[5];

                                // If the months are the same, format as "Jun 1-30, 2024"
                                if ($startMonth === $endMonth) {
                                    return "{$startMonth} {$startDay}-{$endDay}, {$year}";
                                } else {
                                    // If the months are different, format as "Jun 1-Jul 6, 2024"
                                    return "{$startMonth} {$startDay}-{$endMonth} {$endDay}, {$year}";
                                }
                            } else {
                                // If no match, return original and log it for debugging
                                error_log("Billing period format did not match: $billingPeriod");
                                return $billingPeriod;
                            }
                        }

                        foreach ($data as $result) {
                            $preopAmount += $result['amount'];
                        }

                        $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$data[0]['doc_no']}' AND tenant_id = '{$data[0]['tenant_id']}'")->ROW();
                        if (!empty($soaLine)) {
                            $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$data[0]['tenant_id']}'")->ROW();
                        } else {
                            $soa->billing_period = '';
                        }

                        $pDate = date('m/d/Y', strtotime($preopDate));
                        $dueDateFormatted = date('M d, Y', strtotime($result['due_date']));
                        $formattedBillingPeriod = formatBillingPeriod($soa->billing_period);
                        //$rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$data[0]['tenant_id']}<|>$preopDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$data[0]['trade_name']}-Preop<|>{$preopAmount}<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];
                        $rows[] = ["GENERAL<|>$line_no<|>Customer<|>{$data[0]['tenant_id']}<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$data[0]['trade_name']}-Preop<|>{$preopAmount}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                        $line_no += 10000;

                        foreach ($data as $result) {
                            $soaLine = $this->db->query("SELECT * FROM soa_line WHERE doc_no = '{$result['doc_no']}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                            if (!empty($soaLine)) {
                                $soa = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '{$soaLine->soa_no}' AND tenant_id = '{$result['tenant_id']}'")->ROW();
                            } else {
                                $soa->billing_period = '';
                            }

                            $pDate = date('m/d/Y', strtotime($result['posting_date']));
                            $tradeName = substr($result['trade_name'], 0, 45);
                            $dueDateFormatted = date('M d, Y', strtotime($result['due_date'])); // Format due date to abbreviated month
                            $formattedBillingPeriod = formatBillingPeriod($soa->billing_period);

                            if ($result['description'] == 'Construction Bond') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.10<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.10<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            } else if ($result['description'] == 'Security Deposit - Kiosk and Cart' || $result['description'] == 'Security Deposit') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.12<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.03.12<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>$soa->soa_no-{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            } else if ($result['description'] == 'Advance Rent') {
                                // $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.02.01<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$result['description']}<|>({$result['amount']})<|>$company_code<|>$dept_code<|>GENJNL<|>LEASING<|><|><|><|>{$soa->billing_period}<|><|>"];
                                $rows[] = ["GENERAL<|>$line_no<|>G/L Account<|>10.20.01.01.02.01<|>$pDate<|><|>{$doc_no}-{$result['doc_no']}<|>{$soa->soa_no}-{$result['description']}<|>{$result['amount']}<|>$company_code<|>$dept_code<|>GENJNL<|>SERVICEINV<|><|><|><|>{$formattedBillingPeriod};{$dueDateFormatted}<|><|>"];
                            }

                            $line_no += 10000;

                            if ($value['cas_doc_no'] != '') {
                                $externalDocNo = $doc_no;
                            } else {
                                $externalDocNo = "{$doc_no}-{$result['doc_no']}";
                            }
                        }


                        $exp_batch_no = $this->app_model->generate_ExportNo(true);
                        $filter_date = date('Y-m', strtotime($posting_date));
                        $file_name = "Preop_" . $value['tenant_id'] . '_' . $filter_date . "-" . $exp_batch_no . '.pjn';

                        $store = $this->session->userdata('store_code');
                        $targetPath = getcwd() . '/assets/for_cas/other/' . $file_name;
                        // $targetPath    = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/preop/' . $monthfolder["$m"] . '/'.$file_name;
                        $file_data = arrayToString($rows);

                        $toUpdate = "";

                        if ($value['cas_doc_no'] != '') {
                            $toUpdate = ['export_batch_code' => $exp_batch_no];
                        } else {
                            $toUpdate = ['export_batch_code' => $exp_batch_no, 'cas_doc_no' => $externalDocNo];
                        }

                        foreach ($doc_nos as $doc_no) {
                            $this->app_model->updateEntryAsExported('tmp_preoperationcharges', $toUpdate, "doc_no = '$doc_no' AND (export_batch_code IS NULL OR export_batch_code = '')");
                        }

                        $this->app_model->logExportForNav($exp_batch_no, $filter_date, $file_data, 'Preop', $file_name);
                        file_put_contents($targetPath, $file_data);
                    }
                }
                $msg = ['message' => 'Textfiles Generated.', 'info' => 'success'];
            } catch (Exception $e) {
                $msg = ['message' => $e, 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'No Data to Generate.', 'info' => 'empty'];
        }

        echo json_encode($msg);
    }
    #------------------------------------------------------ MANUAL EXTRACTION ------------------------------------------------------#

    public function RR_reports_internal()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data = [
                'flashdata' => $this->session->flashdata('message'),
                'expiry_tenants' => $this->app_model->get_expiryTenants()
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/reports/RR_reports_internal');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }
    public function AR_reports_internal()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data = [
                'flashdata' => $this->session->flashdata('message'),
                'expiry_tenants' => $this->app_model->get_expiryTenants(),
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/reports/AR_reports_internal');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }
    public function collection_reports_internal()
    {
        if ($this->session->userdata('leasing_logged_in')) {
            $data = [
                'flashdata' => $this->session->flashdata('message'),
                'expiry_tenants' => $this->app_model->get_expiryTenants(),
            ];

            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/reports/collection_reports_internal');
            $this->load->view('leasing/footer');
        } else {
            redirect('ctrl_leasing/');
        }
    }
    #--------------------------------------------------------- CAS END
    public function saveORNumber()
    {
        $data = $this->input->post(null);
        $payment_scheme = [];

        if (!$data['or_number']) {
            JSONResponse([
                'msg' => 'Please input OR number.',
                'status' => 'error',
            ]);
        }

        if (!$data['transaction_number']) {
            JSONResponse([
                'msg' => 'Something went wrong, please try again.',
                'status' => 'error',
            ]);
        }

        $ornumber = $data['or_number'];
        $id = $data['transaction_number'];
        $duplicate = $this->app_model->checkOrNumber($ornumber);

        if (empty($duplicate)) {
            $payment_scheme = ['or_no' => $ornumber, 'status' => 'WITH OR'];

            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('payment_scheme', $payment_scheme);
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                JSONResponse([
                    'msg' => 'Failed adding OR number, please try again.',
                    'status' => 'error',
                ]);
            } else {
                JSONResponse([
                    'msg' => 'OR number added.',
                    'status' => 'success',
                ]);
            }
        } else {
            JSONResponse([
                'msg' => 'OR number already exist.',
                'status' => 'error',
            ]);
        }
    }
    public function soaReprintNew()
    {
        $file = $this->uri->segment('3');
        $soano = $this->uri->segment('4');
        $password = $this->input->post('password');
        $username = $this->input->post('username');
        $storeID = $this->session->userdata('user_group');
        $storename = $this->db->query("SELECT * FROM stores WHERE id = '{$this->session->userdata('user_group')}'")->ROW();

        if ($this->app_model->managers_key($username, $password, $storename->store_name)) {
            $managerID = $this->db->query("SELECT id FROM leasing_users WHERE username='{$username}' AND password='" . md5($password) . "'")->ROW();
            $count_no = $this->app_model->getReprintSoaLogNo($soano);
            $count = 1;

            if (!empty($count_no)) {
                $count = $count_no->reprint_no;
                $count++;
            }

            $existingPdf = getcwd() . '/assets/pdf/' . $file;
            $newPdf = getcwd() . '/assets/pdf/' . $count . '_' . $file;

            $pdf = new \setasign\Fpdi\Fpdi();
            // $pdf->AddPage();
            // $pdf->SetFont('Arial', 'B', 8);
            // $pdf->SetTextColor(0, 0, 0);
            // $pdf->SetXY(10, 3);
            // $pdf->Cell(35, 5, 'Re-printed copy : ' . $count, 0, 0, 'L');
            // $pdf->setSourceFile($existingPdf);
            // $templateId = $pdf->importPage(1);
            // $pdf->useTemplate($templateId, 0, 0);
            // ====================== gwaps ===========================
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(10, 3);
            $pdf->Cell(35, 5, 'Re-printed copy : ' . $count, 0, 0, 'L');
            $pdf->setSourceFile($existingPdf);
            $totalPages = $pdf->setSourceFile($existingPdf);
            for ($i = 1; $i <= $totalPages; $i++) {
                $templateId = $pdf->importPage($i);
                $pdf->useTemplate($templateId, 0, 0);
                if ($i < $totalPages) {
                    $pdf->AddPage();
                }
            }
            // ====================== gwaps ends ======================

            $this->db->trans_start();

            if (!empty($count_no)) {
                $this->db->where('id', $count_no->id);
                $this->db->update('soa_reprint_log', [
                    'reprint_no' => $count,
                    'reprint_date' => date('Y-m-d'),
                    'reprint_time' => date('h:i:sa'),
                    'reprinted_by' => $this->session->userdata('id'),
                    'approved_by' => $managerID->id
                ]);
            } else {
                $this->db->insert('soa_reprint_log', [
                    'soa_no' => $soano,
                    'reprint_no' => $count,
                    'reprint_date' => date('Y-m-d'),
                    'reprint_time' => date('h:i:sa'),
                    'reprinted_by' => $this->session->userdata('id'),
                    'approved_by' => $managerID->id
                ]);
            }

            $this->db->trans_complete();
            $pdf->Output('D', $file);
        } else {
            $this->session->set_flashdata('message', 'Invalid Key');
            redirect('Leasing_transaction/reprint_soa');
        }
    }

    // public function paymentReprintNew()
    // {
    //     $file = $this->uri->segment('3');
    //     $docno = $this->uri->segment('4');
    //     $password = $this->input->post('password');
    //     $username = $this->input->post('username');
    //     $storeID = $this->session->userdata('user_group');
    //     $storename = $this->db->query("SELECT * FROM stores WHERE id = '{$this->session->userdata('user_group')}'")->ROW();



    //     if ($this->app_model->managers_key($username, $password, $storename->store_name)) {
    //         $managerID = $this->db->query("SELECT id FROM leasing_users WHERE username='{$username}' AND password='{$password}'")->ROW();
    //         $count_no = $this->app_model->getReprintPaymentLogNo($docno);
    //         $count = 1;


    //         if (!empty($count_no)) {
    //             $count = $count_no->reprint_no;
    //             $count++;
    //         }

    //         $existingPdf = getcwd() . '/assets/pdf/' . $file;
    //         $newPdf = getcwd() . '/assets/pdf/' . $count . '_' . $file;

    //         $pdf = new \setasign\Fpdi\Fpdi();
    //         $pdf->AddPage();
    //         $pdf->SetFont('Arial', 'B', 8);
    //         $pdf->SetTextColor(0, 0, 0);
    //         $pdf->SetXY(10, 3);
    //         $pdf->Cell(35, 5, 'Re-printed copy : ' . $count, 0, 0, 'L');
    //         $pdf->setSourceFile($existingPdf);
    //         $templateId = $pdf->importPage(1);
    //         $pdf->useTemplate($templateId, 0, 0);

    //         $this->db->trans_start();

    //         if (!empty($count_no)) {
    //             $this->db->where('id', $count_no->id);
    //             $this->db->update('payment_reprint_log', [
    //                 'reprint_no' => $count,
    //                 'reprint_date' => date('Y-m-d'),
    //                 'reprint_time' => date('h:i:s a'),
    //                 'reprinted_by' => $this->session->userdata('id'),
    //                 'approved_by' => $managerID
    //             ]);
    //         } else {
    //             $this->db->insert('payment_reprint_log', [
    //                 'doc_no' => $docno,
    //                 'reprint_no' => $count,
    //                 'reprint_date' => date('Y-m-d'),
    //                 'reprint_time' => date('h:i:s a'),
    //                 'reprinted_by' => $this->session->userdata('id'),
    //                 'approved_by' => $managerID
    //             ]);
    //         }

    //         $this->db->trans_complete();
    //         $pdf->Output('D', $file);
    //     } else {
    //         $this->session->set_flashdata('message', 'Invalid Key');
    //         redirect('Leasing_reports/payment_scheme');
    //     }
    // }
    // ================================ gwaps ===========================================

    public function paymentReprintNew()
    {
        $file = $this->uri->segment('3');
        $docno = $this->uri->segment('4');
        $password = $this->input->post('password');
        $username = $this->input->post('username');
        $storeID = $this->session->userdata('user_group');
        $storename = $this->db->query("SELECT * FROM stores WHERE id = '{$this->session->userdata('user_group')}'")->ROW();

        if ($this->app_model->managers_key($username, $password, $storename->store_name)) {
            $managerID = $this->db->query("SELECT id FROM leasing_users WHERE username='{$username}' AND password='" . md5($password) . "'")->ROW();
            $count_no = $this->app_model->getReprintPaymentLogNo($docno);
            $count = 1;

            if (!empty($count_no)) {
                $count = $count_no->reprint_no;
                $count++;
            }

            $existingPdf = getcwd() . '/assets/pdf/' . $file;
            $newPdf = getcwd() . '/assets/pdf/' . $count . '_' . $file;

            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);

            // Import all pages from PDF, similar to soaReprintNew
            $totalPages = $pdf->setSourceFile($existingPdf);
            for ($i = 1; $i <= $totalPages; $i++) {
                $pdf->AddPage();
                $pdf->SetXY(10, 3);
                $pdf->Cell(35, 5, 'Re-printed copy : ' . $count, 0, 0, 'L');
                $templateId = $pdf->importPage($i);
                $pdf->useTemplate($templateId, 0, 0);
            }

            // Transaction for logging the reprint
            $this->db->trans_start();

            if (!empty($count_no)) {
                $this->db->where('id', $count_no->id);
                $this->db->update('payment_reprint_log', [
                    'reprint_no' => $count,
                    'reprint_date' => date('Y-m-d'),
                    'reprint_time' => date('h:i:s a'),
                    'reprinted_by' => $this->session->userdata('id'),
                    'approved_by' => $managerID->id // Make sure it's the ID
                ]);
            } else {
                $this->db->insert('payment_reprint_log', [
                    'doc_no' => $docno,
                    'reprint_no' => $count,
                    'reprint_date' => date('Y-m-d'),
                    'reprint_time' => date('h:i:s a'),
                    'reprinted_by' => $this->session->userdata('id'),
                    'approved_by' => $managerID->id // Make sure it's the ID
                ]);
            }

            $this->db->trans_complete();
            $pdf->Output('D', $file);
        } else {
            $this->session->set_flashdata('message', 'Invalid Key');
            redirect('Leasing_reports/payment_scheme');
        }
    }
    // ================================ gwaps ends ======================================

    public function uploadToCasTesting()
    {
        // $live     = $this->load->database('live', true);
        $cas = $this->load->database('agc_cas', true);
        $pms = $this->app_model->tenant_details('6466');
        $prospect = [];
        // $result   = $this->app_model->select_tradeName_1($pms[0]['trade_name'], $pms[0]['tenancy_type']);
        // dump($result);

        if (!empty($pms)) {
            $prospect = $cas->query("SELECT * FROM prospect 
                                      WHERE trade_name = '" . $pms[0]['trade_name'] . "'
                                      AND corporate_name = '" . $pms[0]['corporate_name'] . "'")->ROW();
        }

        if (empty($prospect)) {
            dump($prospect);
        } else {
            // dump($prospect);
        }

        // dump($pms);
        // dump($prospect);
    }
    public function uploadToCCM()
    {
        if ($tender_typeCode == '2' && $this->session->userdata('cfs_logged_in')) {
            $this->load->model('ccm_model');

            $customer_id = $this->ccm_model->check_customer($customer_name);
            $checksreceivingtransaction_id = $this->ccm_model->checksreceivingtransaction();

            $ccm_data = [
                'checksreceivingtransaction_id' => $checksreceivingtransaction_id,
                'customer_id' => $customer_id,
                'businessunit_id' => $this->ccm_model->get_BU(),
                'department_from' => '12',
                'leasing_docno' => $receipt_no,
                'check_no' => $check_no,
                'check_class' => $check_class,
                'check_category' => $check_category,
                'check_expiry' => $expiry_date,
                'check_date' => $check_date,
                'check_received' => $transaction_date,
                'check_type' => $check_type,
                'account_no' => $account_no,
                'account_name' => $account_name,
                'bank_id' => $check_bank,
                'check_amount' => $tender_amount,
                'currency_id' => '1',
                'check_status' => 'PENDING',
            ];

            $this->ccm_model->insert('checks', $ccm_data);
        }
    }
    public function generate_URIClosing()
    {
        $tenant_id = 'ICM-LT000001';
        $posting_date = '2024-07-31';
        $store = $this->session->userdata('store_code');
        $post_date = date('Y-m', strtotime($posting_date));
        $m = date('m', strtotime($posting_date));
        $clearedUFT = $this->app_model->get_clearedPayment($post_date, $tenant_id);
        $msg = [];
        $monthfolder = [
            '01' => '01 - JAN',
            '02' => '02 - FEB',
            '03' => '03 - MAR',
            '04' => '04 - APR',
            '05' => '05 - MAY',
            '06' => '06 - JUNE',
            '07' => '07 - JUL',
            '08' => '08 - AUG',
            '09' => '09 - SEP',
            '10' => '10 - OCT',
            '11' => '11 - NOV',
            '12' => '12 - DEC',
        ];

        $REFERENCE = "";
        $CRJENTRY = [];

        // dump($clearedUFT);
        // exit();
        switch (true) {
            case (!empty($clearedUFT)):
                foreach ($clearedUFT as $key => $cleared) {
                    $DATE = date('m/d/Y', strtotime($cleared['posting_date']));
                    $SOA = substr($cleared['doc_no'], 0, 3);
                    $DOCUMENT = $cleared['doc_no'];
                    $TRADENAME = substr($cleared['trade_name'], 0, 41);
                    $AMOUNT = str_replace('-', '', $cleared['amount']);
                    $BATCHNAME = ($cleared['tag'] == 'Preop') ? 'ACK_RCPT' : 'OFCL_RCPT';
                    $TENDERTYPE = $this->app_model->getPaymetSchemeDetails($cleared['doc_no'], $cleared['tenant_id']);
                    $bankAcroname = $this->app_model->getBankAcroname($cleared['bank_code']);
                    $TTYPE = "";


                    switch (true) {
                        case ($cleared['amount'] <= 0):
                            $closingDocument = $this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no = '{$cleared['ref_no']}' AND doc_no LIKE '%PS%'")->result_array();
                            $closingAmount = str_replace('-', '', $closingDocument[1]['credit']);

                            // dump($cleared['debit']);
                            // dump($closingDocument[1]['credit']);
                            if ($TENDERTYPE) {
                                switch ($TENDERTYPE->tender_typeDesc) {
                                    case 'Bank to Bank':
                                        $TTYPE = 'Online: DS#' . $TENDERTYPE->check_no . ' Bank: ' . $bankAcroname->acroname;
                                        break;
                                    case 'Check':
                                        $acroname = (!empty($bankAcroname)) ? $bankAcroname->acroname : '';
                                        $TTYPE = $TENDERTYPE->tender_typeDesc . ': Check#' . $TENDERTYPE->check_no . ' Bank: ' . $acroname;
                                        break;
                                    case 'Cash':
                                        $TTYPE = 'Cash : ' . $cleared['debit'];
                                        break;
                                    default:
                                        $gl = $this->app_model->getGlAccount($cleared['gl_accountID']);
                                        $TTYPE = ($cleared['gl_accountID'] == '3') ? 'Cash : ' . $cleared['debit'] : $gl->gl_account . ' : ' . $cleared['debit'];
                                        break;
                                }
                            } else {
                                $gl = $this->app_model->getGlAccount($cleared['gl_accountID']);
                                $TTYPE = ($cleared['gl_accountID'] == '3') ? 'Cash : ' . $cleared['debit'] : $gl->gl_account . ' : ' . $cleared['debit'];
                            }

                            $GLCODE = $this->app_model->getInvoice($cleared['ref_no']);

                            if (!empty($GLCODE)) {
                                $DOCUMENT = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? "OC{$DOCUMENT}" : $DOCUMENT;
                                $TENANTID = (in_array($GLCODE->gl_accountID, ['22', '29'])) ? str_replace('-', '-OC-', $cleared['tenant_id']) : $cleared['tenant_id'];
                            } else {
                                $DOCUMENT = $DOCUMENT;
                                $TENANTID = $cleared['tenant_id'];
                            }

                            $ACK_URI = $this->db->query("SELECT * FROM subsidiary_ledger WHERE ref_no = '{$cleared['ref_no']}' AND credit LIKE '%{$cleared['debit']}%'")->ROW();

                            if (!empty($ACK_URI)) {
                                if ($ACK_URI->gl_accountID == '7') {
                                    $BATCHNAME = "ACK_RCPT";
                                    $DOCUMENT = "URI-{$DOCUMENT}";
                                    $TENANTID = str_replace('-', '-URI-', $cleared['tenant_id']);

                                }
                            }

                            $line_no = 10000;
                            $CRJENTRY = [];
                            $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Bank Account<|>{$cleared['bank_code']}<|>$DATE<|>{$cleared['document_type']}<|>$DOCUMENT<|>{$cleared['bank_name']}<|>{$cleared['debit']}<|>{$cleared['company_code']}<|>{$cleared['department_code']}<|>CASHRECJNL<|>$BATCHNAME<|><|><|><|>{$TTYPE}<|><|>"];
                            $line_no += 10000;

                            if ($closingDocument[1]['gl_accountID'] == '7') {
                                $BATCHNAME = "ACK_RCPT";
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$closingDocument[1]['document_type']}<|>{$closingDocument[1]['doc_no']}<|>URI-{$TRADENAME}<|>({$closingAmount})<|>{$closingDocument[1]['company_code']}<|>{$closingDocument[1]['department_code']}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            } else {
                                $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Customer<|>{$TENANTID}<|>{$DATE}<|>{$closingDocument[1]['document_type']}<|>{$closingDocument[1]['doc_no']}<|>{$TRADENAME}<|>({$closingAmount})<|>{$closingDocument[1]['company_code']}<|>{$closingDocument[1]['department_code']}<|>CASHRECJNL<|>{$BATCHNAME}<|><|><|><|><|><|>"];
                            }

                            $line_no += 10000;

                            if ($cleared['tag'] == 'Preop') {
                                $invoice = $this->app_model->getPreopInvoice($cleared['cas_doc_no']);
                                $company_code = $this->session->userdata('company_code');
                                $department_code = $this->session->userdata('dept_code');

                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Preop<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->amount<|>$company_code<|>$department_code<|>CASHRECJNL<|>ACK_RCPT<|><|><|><|><|><|>"];
                                }
                            } else {
                                $invoice = $this->app_model->getInvoice($cleared['ref_no']);
                                if (!empty($invoice)) {
                                    $CRJENTRY[] = ["CASHRCPT<|>{$line_no}<|>Invoice<|>{$invoice->cas_doc_no}<|><|><|><|><|>$invoice->debit<|>$invoice->company_code<|>$invoice->department_code<|>CASHRECJNL<|>OFCL_RCPT<|><|><|><|><|><|>"];
                                }
                            }

                            // dump($CRJENTRY);
                            # EXTRACT HERE
                            $TOUPDATE = "doc_no = '" . $cleared['doc_no'] . "' AND ref_no = '" . $cleared['ref_no'] . "' AND (export_batch_code IS NULL OR export_batch_code = '')";
                            $exp_batch_no = $this->app_model->generate_ExportNo(true);

                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER
                            $this->app_model->updateEntryAsExported('subsidiary_ledger', ['export_batch_code' => $exp_batch_no], $TOUPDATE);
                            # UPDATE DOCUMENT NUMBER AND REFERENCE NUMBER

                            $file_name = "PC_{$BATCHNAME}_{$TENANTID}_{$post_date}-{$exp_batch_no}.crj";
                            $targetPath = getcwd() . '/assets/for_cas/payment/' . $monthfolder["$m"] . '/' . $file_name;
                            # $targetPath = '\\\172.16.170.10/pos-sales/LEASING/' . $store . '/payment/' . $monthfolder["$m"] . '/' .$file_name;
                            dump($CRJENTRY);
                            $data = arrayToString($CRJENTRY);

                            $this->app_model->logExportForNav($exp_batch_no, $post_date, $data, 'Payment', $file_name);

                            file_put_contents($targetPath, $data);
                            # EXTRACT HERE
                            break;
                        default:
                            # JUST BREAK NOTHING TO DO HERE
                            break;
                    }
                }
                break;
            default:
                $msg = ['message' => 'No cleared UFT found, please try again.', 'info' => 'error-no-cleared'];
                break;
        }
    }
    public function docs()
    {
        $this->load->view('docs/docs');
    }

    // public function soaReprintPdf($file, $docno)
    // {
    //     $file = $this->uri->segment('3');
    //     $soa_no = $this->uri->segment('4');
    //     $storeID = $this->session->userdata('user_group');
    //     $details = $this->app_model->get_soaReprintDetails($storeID, $soa_no);


    //     $existingPdf = getcwd() . '/assets/pdf/' . $file;
    //     $newPdf = getcwd() . '/assets/pdf/' . $file;

    //     $pdf = new \setasign\Fpdi\Fpdi();
    //     $pdf->AddPage();
    //     $pdf->setDisplayMode('fullpage');
    //     $pdf->setFont('times', 'B', 12);

    //     $pdf->SetTitle($details->file_name);
    //     $pdf->SetSubject($details->file_name);
    //     $pdf->SetKeywords($details->file_name);

    //     $pdf->setSourceFile($existingPdf);
    //     $templateId = $pdf->importPage(1);
    //     $pdf->useTemplate($templateId, 0, 0);



    //     $pdf->SetFillColor(255, 255, 255);
    //     $pdf->Rect(-50, -50, 270, 120, 'F'); // Draw a white rectangle covering the upper part of the page


    //     $logoPath = getcwd() . '/assets/other_img/';
    //     $inCharge = getcwd() . '/img/karen_longjas_1.png';
    //     $store_name = $details->store_name;

    //     $pdf->cell(15, 15, $pdf->Image($logoPath . $details->logo, $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);


    //     $pdf->cell(50, 10, strtoupper($store_name), 0, 0, 'L');

    //     $pdf->SetTextColor(201, 201, 201);
    //     $pdf->SetFillColor(35, 35, 35);
    //     $pdf->cell(35, 6, ' ', 0, 0, 'L');

    //     $pdf->setFont('times', '', 8);
    //     $pdf->cell(30, 6, 'Statement For:', 1, 0, 'C', true);
    //     $pdf->cell(30, 6, 'Please Pay By:', 1, 0, 'C', true);
    //     $pdf->cell(30, 6, 'Amount Due:', 1, 0, 'C', true);

    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->ln();
    //     #---------------------------------------------------------------------------------------------------------------------
    //     $pdf->setFont('times', '', 7);
    //     $pdf->cell(15, 0, ' ', 0, 0, 'L');
    //     $pdf->cell(20, 5, 'Owned & Managed by Alturas Supermarket Corporation', 0, 0, 'L');

    //     $pdf->cell(65, 6, ' ', 0, 0, 'L');
    //     $pdf->setFont('times', '', 6);
    //     $pdf->cell(30, 5, $details->billing_period, 1, 0, 'C');
    //     $pdf->cell(30, 5, date('F j, Y', strtotime($details->collection_date)), 1, 0, 'C');
    //     $pdf->cell(30, 5, 'P ' . number_format($details->amount_payable, 2), 1, 0, 'C');


    //     $pdf->ln();
    //     $pdf->setFont('times', '', 7);
    //     $pdf->cell(15, 0, ' ', 0, 0, 'L');
    //     $pdf->cell(20, 1, $details->store_address, 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 7);
    //     $pdf->cell(15, 0, ' ', 0, 0, 'L');
    //     // $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'L');
    //     if ($store_name == 'ALTURAS MALL') {
    //         $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00000', 0, 0, 'L');
    //     } elseif ($store_name == 'ALTURAS TALIBON') {
    //         $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-002', 0, 0, 'L');
    //     } elseif ($store_name == 'ISLAND CITY MALL') {
    //         $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'L');
    //     } elseif ($store_name == 'ALTURAS TUBIGON') {
    //         $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00006', 0, 0, 'L');
    //     } elseif ($store_name == 'ALTA CITTA') {
    //         $pdf->cell(20, 6, 'VAT REG TIN: 000-254-327-00009', 0, 0, 'L');
    //     }
    //     //  else {
    //     //     $pdf->cell(20, 6, 'VAT REG TIN: 987-654-321-00002', 0, 0, 'L');
    //     // }
    //     $pdf->ln();
    //     $pdf->cell(75, 6, ' ', 0, 0, 'L');
    //     $pdf->SetTextColor(201, 201, 201);
    //     $pdf->cell(25, 6, ' ', 0, 0, 'L');
    //     $pdf->cell(90, 5, 'Questions? Contact', 1, 0, 'C', true);
    //     $pdf->setFont('times', '', 10);
    //     $pdf->ln();
    //     $pdf->SetTextColor(201, 201, 201);
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(75, 10, "LESSEE'S INFORMATION", 1, 0, 'C', true);
    //     $pdf->cell(25, 6, ' ', 0, 0, 'L');
    //     $pdf->setFont('times', '', 10);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Multicell(90, 4, $details->contact_person . "\n" . 'Phone: ' . $details->contact_no . "\n" . 'E-mail: ' . $details->email, 1, 'C');
    //     $pdf->ln();
    //     $pdf->SetTextColor(0, 0, 0);

    //     $pdf->setFont('times', 'B', 8);
    //     $pdf->setFont('times', 'B', 8);
    //     $pdf->cell(25, 4, 'Trade Name', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $details->trade_name, 'B', 0, 'L');
    //     $pdf->cell(10, 4, ' ', 0, 0, 'L');
    //     $pdf->cell(25, 4, 'SOA No.', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $details->soa_no, 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(25, 4, 'Corp Name', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $details->corporate_name, 'B', 0, 'L');
    //     $pdf->cell(10, 4, '  ', 0, 0, 'L');
    //     $pdf->cell(25, 4, 'Date of Transaction', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $postingDate = new DateTime($details->posting_date);
    //     $formattedDate = $postingDate->format('F d, Y');
    //     $pdf->cell(60, 4, $formattedDate, 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(25, 4, 'TIN', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $details->tin, 'B', 0, 'L');
    //     $pdf->cell(10, 4, '', 0, 0, 'L');
    //     $pdf->cell(25, 4, 'Billing Period', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     if ($details->billing_period === 'UPON SIGNING OF NOTICE') {
    //         $pdf->SetFont('times', 'B', 7); // Set font to bold, size 7
    //     } else {
    //         $pdf->SetFont('times', 'B', 8); // Set font to regular, size 7 (or whatever your default font is)
    //     }
    //     $pdf->Cell(60, 4, $details->billing_period, 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 8);
    //     $pdf->cell(25, 4, 'Address', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $details->address, 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 8);
    //     $tenant_id = $details->tenant_id;

    //     $this->db->trans_start();


    //     $this->db->trans_complete();
    //     header('Content-Type: application/pdf');
    //     header('Content-Disposition: inline; filename="' . $details->file_name . '"');
    //     $pdf->Output('I', $details->file_name); // Output PDF to browser inline
    //     $pdf->Output('assets/pdf/' . $file_name, 'F');

    //     return $file_name;
    // }



    // public function paymentReprintPdf($file, $docno)
    // {
    //     $docno = $this->uri->segment('4');
    //     $receiptNo = $this->uri->segment('4');
    //     $paymentData = $paymentData2 = $this->app_model->get_paymentDataHistory($receiptNo);
    //     $paymentDesc = $this->app_model->get_paymentDescription($receiptNo);
    //     $existingPdf = getcwd() . '/assets/pdf/' . $file;
    //     $newPdf = getcwd() . '/assets/pdf/' . $file;

    //     $pdf = new FPDF('p', 'mm', 'A4');
    //     $pdf->AddPage();
    //     $pdf->setDisplayMode('fullpage');
    //     $pdf->setFont('times', 'B', 12);

    //     $pdf->SetTitle($paymentData->receipt_doc);
    //     $pdf->SetSubject($paymentData->receipt_doc);
    //     $pdf->SetKeywords($paymentData->receipt_doc);

    //     $logoPath = getcwd() . '/assets/other_img/';

    //     $pdf->cell(20, 20, $pdf->Image($logoPath . $paymentData->logo, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 14);
    //     $pdf->cell(190, 10, strtoupper($paymentData->store_name), 0, 0, 'C');
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->SetFillColor(35, 35, 35);
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 11);
    //     $pdf->cell(190, 5, 'Owned & Managed by Alturas Supermarket Corporation', 0, 0, 'C');
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 11);
    //     $pdf->cell(190, 5, $paymentData->store_address, 0, 0, 'C');
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 11);
    //     // $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'C');
    //     if ($paymentData->store_name == 'ALTURAS MALL') {
    //         $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00000', 0, 0, 'C');
    //     } elseif ($paymentData->store_name == 'ALTURAS TALIBON') {
    //         $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-002', 0, 0, 'C');
    //     } elseif ($paymentData->store_name == 'ISLAND CITY MALL') {
    //         $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00003', 0, 0, 'C');
    //     } elseif ($paymentData->store_name == 'ALTURAS TUBIGON') {
    //         $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00006', 0, 0, 'C');
    //     } elseif ($paymentData->store_name == 'ALTA CITTA') {
    //         $pdf->cell(190, 5, 'VAT REG TIN: 000-254-327-00009', 0, 0, 'C');
    //     }
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 16);
    //     $pdf->cell(0, 6, 'PAYMENT SLIP', 0, 0, 'C');
    //     $pdf->cell(0, 6, 'REPRINT', 0, 0, 'C');
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 9);
    //     $pdf->cell(25, 5, 'PS No.', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->receipt_no, 'B', 0, 'L');
    //     $pdf->cell(10, 5, ' ', 0, 0, 'L');
    //     $pdf->cell(25, 5, 'SOA No.', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->soa_no, 'B', 0, 'L');
    //     $pdf->ln();

    //     $pdf->cell(25, 5, 'Trade Name', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->trade_name, 'B', 0, 'L');
    //     $pdf->cell(10, 5, ' ', 0, 0, 'L');
    //     $pdf->cell(25, 5, 'Payment Date', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->posting_date, 'B', 0, 'L');
    //     $pdf->ln();

    //     $pdf->cell(25, 5, 'Corporate Name', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->corporate_name, 'B', 0, 'L');
    //     $pdf->cell(10, 5, ' ', 0, 0, 'L');
    //     $pdf->cell(25, 5, 'Total Payable', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, number_format($paymentData->amount_due, 2), 'B', 0, 'L');
    //     $pdf->ln();

    //     $pdf->cell(25, 5, 'TIN', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->tin, 'B', 0, 'L');
    //     $pdf->cell(10, 5, ' ', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(25, 5, 'Address', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 5, $paymentData->address, 'B', 0, 'L');
    //     $pdf->cell(10, 5, ' ', 0, 0, 'L');

    //     $store_name = $paymentData->store_name;

    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 8);


    //     $icm = 'Please make all checks payable to ISLAND CITY MALL; BANK:BPI ACCOUNT No. 9471-0019-85';
    //     $am = 'Please make all checks payable to ALTURAS SUPERMARKET CORP. MAIN STORE; BANK:PNB ACCOUNT No. 3058-7000-6513';
    //     $act = 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION  LBP #5882-111-590';
    //     $tubigon = 'Please make all checks payable to ASC-Home & Fashion; BANK:PNB ACCOUNT No. 305370004516';
    //     $asct = 'Please make all checks payable to ALTURAS SUPERMARKET CORPORATION -  TALIBON or DEPOSIT TO LBP BANK ACCOUNT: 2232117993';

    //     if ($paymentData->tenant_id == 'ICM-LT000008' || $paymentData->tenant_id == 'ICM-LT000442' || $paymentData->tenant_id == 'ICM-LT000492' || $paymentData->tenant_id == 'ICM-LT000035' || $paymentData->tenant_id == 'ICM-LT000120') {
    //         $pdf->cell(0, 5, $icm, 0, 0, 'R');
    //     } elseif ($store_name == 'ALTA CITTA') {
    //         if ($paymentData->tenant_id == 'ACT-LT000027') {
    //             $pdf->cell(0, 5, $act, 0, 0, 'R');
    //         } else {
    //             $pdf->cell(0, 5, $act, 0, 0, 'R');
    //         }
    //     } elseif ($paymentData->tenant_id == 'ICM-LT000218' || $paymentData->tenant_id == 'ICM-LT000219') {
    //         $pdf->cell(0, 5, $icm, 0, 0, 'R');
    //     } else if ($store_name == 'ALTURAS TALIBON') {
    //         $pdf->cell(0, 5, $asct, 0, 0, 'R');
    //     } else {
    //         if ($store_name == 'ALTURAS MALL') {
    //             $pdf->cell(0, 5, $am, 0, 0, 'R');
    //         } elseif ($store_name == 'ALTURAS TUBIGON') {
    //             $pdf->cell(0, 5, $tubigon, 0, 0, 'R');
    //         } elseif ($store_name == 'PLAZA MARCELA') {
    //             // $pdf->cell(0, 5, 'Please make all checks payable to MFI - PLAZA MARCELA, LB ACCT #0612-0011-11', 0, 0, 'R');
    //         } elseif ($store_name == 'ISLAND CITY MALL' || $paymentData->tenant_id != 'ICM-LT000008' || $paymentData->tenant_id != 'ICM-LT000442' || $paymentData->tenant_id != 'ICM-LT000492' || $paymentData->tenant_id != 'ICM-LT000035' || $paymentData->tenant_id != 'ICM-LT000120') {
    //             $pdf->cell(0, 5, $icm, 0, 0, 'R');
    //         } else {
    //             $pdf->cell(0, 5, 'Please make all checks payable to ' . strtoupper($store->store_name) . '', 0, 0, 'R');
    //         }
    //     }

    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();




    //     // // =================== Receipt Charges Table ============= //
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(120, 8, 'Description', 0, 0, 'L');
    //     $pdf->cell(30, 8, 'Total Amount Due', 0, 0, 'C');
    //     $pdf->setFont('times', '', 10);


    //     $processedDocNo = [];
    //     $totalAmountDue = 0;
    //     $totalAmountPaid = 0;

    //     foreach ($paymentDesc as $key => $doc) {
    //         $doc = (object) $doc;

    //         if (in_array($doc->doc_no, $processedDocNo)) {
    //             continue; // Skip this iteration if it's a duplicate
    //         }

    //         $chargesTypeText = ($doc->charges_type == 'Basic') ? 'Basic Rent' : $doc->charges_type;

    //         $processedDocNo[] = $doc->doc_no;

    //         // Aggregate amounts
    //         $totalAmountDue = $doc->amount_due;
    //         $totalAmountPaid = $doc->amount_paid;

    //         // Your PDF generation code
    //         $pdf->ln();
    //         $pdf->cell(30, 5, $chargesTypeText, 0, 0, 'L');
    //         $pdf->cell(30, 5, ' - ' . date('M Y', strtotime($doc->posting_date)), 0, 0, 'L');
    //         $pdf->cell(60, 5, '', 0, 0, 'R');
    //         $pdf->cell(30, 5, number_format($doc->amount, 2), 0, 0, 'R');
    //     }

    //     // Display the totals only once
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(30, 5, 'Total Payable', 0, 0, 'L');
    //     $pdf->setFont('times', '', 10);
    //     $pdf->cell(30, 5, '', 0, 0, 'L');
    //     $pdf->cell(60, 5, '', 0, 0, 'R');
    //     $pdf->cell(30, 5, 'P ' . number_format($totalAmountDue, 2), 'T', 0, 'R');
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(30, 5, 'Amount Paid', 0, 0, 'L');
    //     $pdf->setFont('times', '', 10);
    //     $pdf->cell(30, 5, '', 0, 0, 'L');
    //     $pdf->cell(60, 5, '', 0, 0, 'R');
    //     $pdf->cell(30, 5, 'P ' . number_format($totalAmountPaid, 2), 'B', 0, 'R');
    //     $pdf->ln();

    //     $totalDue = $totalAmountDue - $totalAmountPaid;

    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(30, 5, 'Amount Still Due', 0, 0, 'L');
    //     $pdf->setFont('times', '', 10);
    //     $pdf->cell(30, 5, '', 0, 0, 'L');
    //     $pdf->cell(60, 5, '', 0, 0, 'R');
    //     $pdf->cell(30, 5, 'P ' . number_format($totalDue, 2), 0, 0, 'R');

    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(190, 8, 'Payment Scheme: ', 0, 0, 'L');
    //     $pdf->ln();

    //     $pdf->setFont('times', '', 9);
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Description', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->tender_typeCode != 2 ? $paymentData->tender_typeDesc : ucwords($paymentData->check_type), 'B', 0, 'L');
    //     // $pdf->cell(60, 4, '', 'B', 0, 'L');
    //     $pdf->cell(5, 4, ' ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Total Payable', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, 'P ' . number_format($paymentData->amount_due, 2), 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Bank', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, in_array($paymentData->tender_typeCode, [1, 2, 3, 11]) ? $paymentData->bank : 'N/A', 'B', 0, 'L');
    //     $pdf->cell(5, 4, ' ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Amount Paid', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, 'P ' . number_format($paymentData->amount_paid, 2), 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Check Number', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->tender_typeCode == 2 ? $paymentData->check_no : 'N/A', 'B', 0, 'L');
    //     $pdf->cell(5, 4, ' ', 0, 0, 'L');
    //     $Balance = $paymentData->amount_due - $paymentData->amount_paid;
    //     $pdf->cell(30, 4, 'Balance', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, 'P ' . number_format($Balance, 2), 'B', 0, 'L');

    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');

    //     $pdf->cell(30, 4, 'Check Date', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->tender_typeCode == 2 ? $paymentData->check_date : 'N/A', 'B', 0, 'L');
    //     $pdf->cell(5, 4, ' ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Advance', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     // $pdf->cell(60, 4, 'P ' . number_format($paymentData->advance_amount, 2), 'B', 0, 'L');
    //     $pdf->cell(60, 4, 'P ' . '0.00', 'B', 0, 'L');

    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Check Due Date', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->tender_typeCode == 2 && $paymentData->check_type == 'POST DATED CHECK' ? $paymentData->check_due_date : 'N/A', 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Payor', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->payor, 'B', 0, 'L');

    //     $pdf->ln();
    //     $pdf->cell(1, 4, '     ', 0, 0, 'L');
    //     $pdf->cell(30, 4, 'Payee', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->payee, 'B', 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(1, 4, '', 0, 0, 'L');

    //     $pdf->cell(30, 4, 'Document #', 0, 0, 'L'); # OR or AR
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(60, 4, $paymentData->receipt_no, 'B', 0, 'L');

    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 10);
    //     $pdf->cell(95, 4, 'Prepared By: _____________________', 0, 0, 'C');
    //     $pdf->cell(95, 4, 'Checked By:______________________', 0, 0, 'C');
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->cell(45, 4, 'Acknowledgment Certificate No.', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(38, 4, ' AC_123_122023_000135', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(45, 4, 'Date Issued', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(38, 4, "December 12, 2023", 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->cell(45, 4, 'Series Range', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(38, 4, 'PS0000001 - PS9999999', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(0, 4, 'THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->setFont('times', 'B', 10);
    //     $pdf->cell(0, 4, 'THIS IS NOT AN OFFICIAL RECEIPT', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->ln();
    //     $pdf->cell(0, 4, 'Thank you for your prompt payment!', 0, 0, 'L');
    //     $pdf->ln();
    //     $pdf->setFont('times', '', 9);
    //     $pdf->cell(25, 4, 'Run Date and Time', 0, 0, 'L');
    //     $pdf->cell(2, 4, ':', 0, 0, 'L');
    //     $pdf->cell(38, 4, date('Y-m-d h:m:s A'), 0, 0, 'L');
    //     $this->db->trans_start();


    //     $this->db->trans_complete();

    //     // Set the correct headers to open the PDF in the browser
    //     header('Content-Type: application/pdf');
    //     header('Content-Disposition: inline; filename="' . $file . '"');

    //     $pdf->Output('I', $file); // Output PDF to browser inline
    // }
    //}


}