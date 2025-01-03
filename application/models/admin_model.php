<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_model extends CI_model
{

    private $ccm_db;
    function __construct()
    {
        parent::__construct();
        $this->ccm_db = $this->load->database('CCM_DB', true);
    }

    public function check_adminLogin($username, $password)
    {
        $this->db->select('*');
        $this->db->from('leasing_users');
        $this->db->where('username = ' . "'" . $username . "'");
        $this->db->where('password = ' . "'" . md5($password) . "'");
        $this->db->where('status = ' . "'" . 'Active' . "'");
        $this->db->where('user_type = ' . "'" . 'Super Admin' . "'");
        $this->db->limit(1);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            foreach ($query->result() as $rows) {
                //add all data to session
                $data = array(
                    'id' => $rows->id,
                    'password' => $rows->password,
                    'user_type' => $rows->user_type,
                    'username' => $rows->username,
                    'first_name' => $rows->first_name,
                    'middle_name' => $rows->middle_name,
                    'last_name' => $rows->last_name,
                    'admin_logged_in' => TRUE
                );
                $this->session->set_userdata($data);
            }

            $session = (object) $this->session->userdata;

            $user_session_data = [
                'user_id' => isset($session->id) ? $session->id : '',
                'session_id' => isset($session->session_id) ? $session->session_id : '',
                'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                'user_data' => json_encode($session),
                'login_in' => 'superadmin'
            ];

            $this->db->insert('user_session', $user_session_data);

            return true;
        }
        return false;
    }


    public function get_activeTenants()
    {
        $query = $this->db
            ->where('status', 'Active')
            ->where('flag', 'Posted')
            ->count_all_results('tenants');

        return $query;
    }


    public function get_longtermTenants()
    {
        $query = $this->db
            ->where('status', 'Active')
            ->where('flag', 'Posted')
            ->where('tenancy_type', 'Long Term')
            ->count_all_results('tenants');

        return $query;
    }

    public function get_shorttermTenants()
    {
        $query = $this->db
            ->where('status', 'Active')
            ->where('flag', 'Posted')
            ->where('tenancy_type', 'Short Term')
            ->count_all_results('tenants');

        return $query;
    }


    public function get_lesseeTypeCount()
    {
        $this->db->select('COUNT(p.trade_name) AS count, lt.leasee_type AS type');
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $this->db->join('leasee_type AS lt', 'lt.id = p.lesseeType_id');
        $this->db->group_by('p.lesseeType_id');
        $this->db->order_by('count', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }


    public function get_areaClassificationCount()
    {
        $this->db->select('COUNT(p.trade_name) AS count, lc.area_classification');
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $this->db->join('location_code AS lc', 't.locationCode_id = lc.id AND lc.status = ' . "'Active'" . '');
        $this->db->group_by('lc.area_classification');
        $this->db->order_by('count', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_areaTypeCount()
    {
        $this->db->select('COUNT(p.trade_name) AS count, lc.area_type');
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $this->db->join('location_code AS lc', 't.locationCode_id = lc.id AND lc.status = ' . "'Active'" . '');
        $this->db->group_by('lc.area_type');
        $this->db->order_by('count', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_categoryCount()
    {
        $this->db->select('COUNT(p.trade_name) AS count, p.first_category AS category');
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $this->db->group_by('category');
        $this->db->order_by('count', 'desc');
        $result = $this->db->get();
        return $result->result_array();
    }


    // public function deleteEntry($tenant_id, $doc_no)
    // {

    //     $this->db->trans_start(); // Transaction function starts here!!!

    //     $this->db->query("DELETE FROM invoicing WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->query("DELETE FROM ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->query("DELETE FROM general_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->query("DELETE FROM subsidiary_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->query("DELETE FROM monthly_receivable_report WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->query("DELETE FROM tmp_preoperationcharges WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
    //     $this->db->trans_complete(); // End of transaction function

    //     if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
    //     {
    //         $this->db->trans_rollback(); // If failed rollback all queries
    //         return false;
    //     }

    //     return true;
    // }

    public function deleteEntry($tenant_id, $doc_no)
    { //UPDATE BY LINIE  
        $this->db->trans_start(); // Start transaction

        // Define the new values with the DELETED- prefix
        $new_tenant_id = 'DELETED-' . $tenant_id;
        $new_doc_no = 'DELETED-' . $doc_no;

        // Update queries
        $this->db->query("UPDATE invoicing SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE ledger SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE general_ledger SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE subsidiary_ledger SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE monthly_receivable_report SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE tmp_preoperationcharges SET tenant_id = '$new_tenant_id', doc_no = '$new_doc_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");

        $this->db->trans_complete(); // End transaction

        if ($this->db->trans_status() === FALSE) // Check if the transaction was successful
        {
            $this->db->trans_rollback(); // If failed, rollback all queries
            return false;
        }

        return true;
    }


    // public function cancel_soa($tenant_id, $soa_no)
    // {
    //     $this->load->model('app_model');

    //     $this->db->trans_start(); // Transaction function starts here!!!

    //     $this->app_model->updatePaymentSoaTagging($soa_no);

    //     $this->db->query("DELETE FROM ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
    //     $this->db->query("DELETE FROM general_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
    //     $this->db->query("DELETE FROM subsidiary_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
    //     $this->db->query("DELETE FROM soa WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
    //     $this->db->query("DELETE FROM soa_file WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
    //     $this->db->query("DELETE FROM soa_line WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
    //     $this->db->query("DELETE FROM monthly_receivable_report WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");

    //     $this->db->trans_complete(); // End of transaction function

    //     if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
    //     {
    //         $this->db->trans_rollback(); // If failed rollback all queries
    //         return false;
    //     }

    //     return true;
    // }

    public function cancel_soa($tenant_id, $soa_no)
    {
        $deleted_tenant_id = 'DELETED-' . $tenant_id;
        $deleted_soa_no = 'DELETED-' . $soa_no;

        $this->db->query("UPDATE ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
        $this->db->query("UPDATE general_ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
        $this->db->query("UPDATE subsidiary_ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");
        $this->db->query("UPDATE soa SET tenant_id = '$deleted_tenant_id', soa_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
        $this->db->query("UPDATE soa_file SET tenant_id = '$deleted_tenant_id', soa_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
        $this->db->query("UPDATE soa_line SET tenant_id = '$deleted_tenant_id', soa_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
        $this->db->query("UPDATE monthly_receivable_report SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_soa_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$soa_no'");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }


    public function change_dueDate($tenant_id, $doc_no, $new_dueDate)
    {
        $this->db->trans_start(); // Transaction function starts here!!!

        $this->db->query("UPDATE ledger SET due_date = '$new_dueDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE general_ledger SET due_date = '$new_dueDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE subsidiary_ledger SET due_date = '$new_dueDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE invoicing SET due_date = '$new_dueDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");


        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            return false;
        }

        return true;
    }


    public function change_postingDate($tenant_id, $doc_no, $new_postingDate)
    {
        $this->db->trans_start(); // Transaction function starts here!!!

        $this->db->query("UPDATE ledger SET posting_date = '$new_postingDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE general_ledger SET posting_date = '$new_postingDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE subsidiary_ledger SET posting_date = '$new_postingDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");
        $this->db->query("UPDATE invoicing SET posting_date = '$new_postingDate' WHERE tenant_id = '$tenant_id' AND doc_no = '$doc_no'");


        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            return false;
        }

        return true;
    }


    public function change_bankTagging($tenant_id, $or_number, $bank_name, $bank_code)
    {

        $this->db->trans_start(); // Transaction function starts here!!!
        $this->db->query("UPDATE general_ledger SET bank_name = '$bank_name', bank_code = '$bank_code' WHERE tenant_id = '$tenant_id' AND doc_no = '$or_number'");
        $this->db->query("UPDATE subsidiary_ledger SET bank_name = '$bank_name', bank_code = '$bank_code' WHERE tenant_id = '$tenant_id' AND doc_no = '$or_number'");

        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            return false;
        }

        return true;
    }


    public function change_SOACollectionDate($tenant_id, $soa_no, $collection_date)
    {
        $this->db->query("UPDATE soa SET collection_date = '$collection_date' WHERE tenant_id = '$tenant_id' AND soa_no = '$soa_no'");
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function change_receiptNo($tenant_id, $old_receiptNo, $new_receiptNo)
    {
        $this->db->trans_start(); // Transaction function starts here!!!

        $this->db->query("UPDATE payment_scheme SET receipt_no = '$new_receiptNo' WHERE tenant_id = '$tenant_id' AND receipt_no = '$old_receiptNo'");
        $this->db->query("UPDATE ledger SET doc_no = '$new_receiptNo' WHERE tenant_id = '$tenant_id' AND doc_no = '$old_receiptNo'");
        $this->db->query("UPDATE general_ledger SET doc_no = '$new_receiptNo' WHERE tenant_id = '$tenant_id' AND doc_no = '$old_receiptNo'");
        $this->db->query("UPDATE subsidiary_ledger SET doc_no = '$new_receiptNo' WHERE tenant_id = '$tenant_id' AND doc_no = '$old_receiptNo'");
        $this->db->query("UPDATE monthly_receivable_report SET doc_no = '$new_receiptNo' WHERE tenant_id = '$tenant_id' AND doc_no = '$old_receiptNo'");

        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            return false;
        }

        return true;
    }

    public function search_tradeName()
    {
        $this->db->select("CONCAT(('['), (t.tenant_id), (']'), (' - ') , (p.trade_name)) AS value, t.tenant_id as id");
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . '');
        $result = $this->db->get();
        return $result->result_array();
    }



    public function search_receiptNo()
    {

        $query = $this->db->query(
            "SELECT
                payment.id,
                payment.receipt_no AS value,
                (CASE
                   WHEN payment.vds_no IS NULL THEN '' 
                END) AS vds_no,
                sl.tenant_id,
                sl.posting_date,
                p.trade_name
            FROM 
                payment_scheme payment,
                subsidiary_ledger sl,
                tenants t,
                prospect p
            WHERE
                payment.receipt_no = sl.doc_no
            AND
                sl.tenant_id = t.tenant_id
            AND
                t.prospect_id = p.id
            GROUP BY 
                payment.receipt_no
        "
        );

        return $query->result_array();

    }


    public function get_tenantDetails($tenant_id)
    {
        $this->db->select('p.trade_name, p.corporate_name, p.address, p.contact_person, t.contract_no, t.tin, t.rental_type, t.rent_percentage, t.opening_date, t.expiry_date, t.tenant_type, t.increment_percentage, t.increment_frequency, t.is_vat, t.wht, t.basic_rental');
        $this->db->from('prospect AS p');
        $this->db->join('tenants AS t', 'p.id = t.prospect_id AND t.status = ' . "'Active'" . ' AND t.flag = ' . "'Posted'" . ' AND t.tenant_id = "' . $tenant_id . '"');
        $this->db->limit(1);
        $result = $this->db->get();
        return $result->result_array();
    }


    public function get_charges($id)
    {
        $this->db->select("selected.id, setup.description, setup.charges_code, selected.uom, selected.unit_price");
        $this->db->from("charges_setup AS setup");
        $this->db->join("selected_monthly_charges AS selected", "setup.id = selected.monthly_chargers_id AND selected.id = '$id'");
        $this->db->limit(1);
        $result = $this->db->get();
        return $result->result_array();
    }


    public function update_basicRental($tenant_id, $contract_no, $basic_rental)
    {
        $this->db->set('t.basic_rental', $basic_rental);
        $this->db->set('lc.rental_rate', $basic_rental);
        $this->db->where('t.tenant_id', $tenant_id);
        $this->db->where('t.contract_no', $contract_no);
        $this->db->where('t.status', 'Active');
        $this->db->where('lc.status', 'Active');
        $this->db->where('t.locationCode_id = lc.id');
        $this->db->update('tenants as t, location_code as lc');

        return ($this->db->affected_rows() > 0 ? true : false);
    }


    public function restore_contract($id)
    {
        $this->db->trans_start(); // Transaction function starts here!!!

        $this->db->query("UPDATE tenants SET status = 'Active' WHERE id = '$id'");
        $this->db->query("UPDATE location_code lc, tenants t SET lc.status = 'Active' WHERE t.id = '$id' AND t.locationCode_id = lc.id");
        $this->db->query("DELETE FROM terminated_contract WHERE tenant_id = '$id'");

        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            return false;
        }

        return true;
    }


    // public function cancel_payment($tenant_id, $receipt_no)
    // {
    //     $this->db->trans_start(); // Transaction function starts here!!!

    //     $this->db->query("DELETE FROM payment_scheme WHERE tenant_id = '$tenant_id' AND receipt_no = '$receipt_no'");
    //     $this->db->query("DELETE FROM ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
    //     $this->db->query("DELETE FROM subsidiary_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
    //     $this->db->query("DELETE FROM general_ledger WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
    //     $this->db->query("DELETE FROM payment WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
    //     $this->db->query("UPDATE invoicing SET balance = actual_amt, status = '', receipt_no = '' WHERE tenant_id = '$tenant_id' AND receipt_no = '$receipt_no'");
    //     $this->ccm_db->query("DELETE FROM checks WHERE leasing_docno = '$receipt_no'");
    //     $this->db->trans_complete(); // End of transaction function

    //     if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
    //     {
    //         $this->db->trans_rollback(); // If failed rollback all queries
    //         return false;
    //     }

    //     return true;
    // }

    public function cancel_payment($tenant_id, $receipt_no)
    { //UPDATED BY LINIE
        $this->db->trans_start();
        $deleted_tenant_id = 'DELETED-' . $tenant_id;
        $deleted_receipt_no = 'DELETED-' . $receipt_no;

        $this->db->query("UPDATE payment_scheme SET tenant_id = '$deleted_tenant_id', receipt_no = '$deleted_receipt_no' WHERE tenant_id = '$tenant_id' AND receipt_no = '$receipt_no'");
        $this->db->query("UPDATE ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_receipt_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
        $this->db->query("UPDATE subsidiary_ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_receipt_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
        $this->db->query("UPDATE general_ledger SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_receipt_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
        $this->db->query("UPDATE payment SET tenant_id = '$deleted_tenant_id', doc_no = '$deleted_receipt_no' WHERE tenant_id = '$tenant_id' AND doc_no = '$receipt_no'");
        $this->db->query("UPDATE invoicing SET balance = actual_amt, status = '', receipt_no = '' WHERE tenant_id = '$tenant_id' AND receipt_no = '$receipt_no'");
        // $this->db->query("UPDATE checks SET checks = '$deleted_receipt_no' WHERE checks = '$receipt_no'");
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }

    public function get_tenantCompanyCode($tenant_id)
    {
        return $this->db->query("SELECT s.company_code FROM tenants t, stores s WHERE t.tenant_id = '$tenant_id' AND t.store_code = s.store_code LIMIT 1")->row()->company_code;
    }

    public function get_tradeName($tenant_id)
    {
        return $this->db->query("SELECT p.trade_name FROM tenants t, prospect p WHERE t.tenant_id = '$tenant_id' AND t.prospect_id = p.id LIMIT 1")->row()->trade_name;
    }

    public function get_accredited_banks()
    {
        return $this->db->query("SELECT * FROM accredited_banks ORDER BY store_code ASC, bank_code ASC, bank_name ASC")->result_object();
    }



    public function search_cfsName() //added by Lilimae
    {
        $this->db->select("id, name");
        $this->db->from('leasing_users');
        $this->db->where('user_type', 'CFS');
        $this->db->order_by('name', 'asc');

        $result = $this->db->get();
        return $result->result_array();
    }


    public function get_leasing_denomination($user_id, $date) //added by Lilimae
    {
        $res = $this->db
            ->select('id, pieces, denomination, amount')
            ->from('leasing_denomination')
            ->where('inputted_by', $user_id)
            ->where('inputted_on', $date)
            ->get();

        return $res->result_array();
    }

    public function update_leasing_denomination($updatedData) //added by Lilimae
    {
        foreach ($updatedData as $data) {
            $this->db->where('id', $data['id']);
            $this->db->update('leasing_denomination', [
                'pieces' => $data['pieces'],
                'amount' => $data['amount']
            ]);
        }

        return true;
    }


} //end of Model
