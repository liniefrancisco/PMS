
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ccm_model extends CI_model
{
    private $ccm_db;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $date = new DateTime();
        $this->_timeStamp = $date->format('Y-m-d H:i:s');
        $this->ccm_db = $this->load->database('CCM_DB', true);
    }   

    public function check() {
        $query = $this->ccm_db->query("SELECT currency_name FROM currency");
        return $query->result_array();
    }

    public function insert($table, $data)
    {
        $this->ccm_db->insert($table, $data);
    }


    public function populate_ccm_customer() {
        $query = $this->ccm_db->query("SELECT fullname FROM customers");
        return $query->result_array();
    }


    public function ccm_banks() {
        $query = $this->ccm_db->query("SELECT bank_id, bankbranchname FROM banks WHERE bankcode = '0'");
        return $query->result_array();
    }


    public function generate_customerCode() {
        $query = $this->ccm_db->query("SELECT MAX(cus_code) AS cus_code FROM customers");

        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['cus_code'] + 1;
        }
        else
        {
            return '10000001';
        }
    }


    public function generate_checksreceivingtransaction_ctrlno() {
        $query = $this->ccm_db->query("SELECT MAX(checksreceivingtransaction_ctrlno) AS checksreceivingtransaction_ctrlno FROM checksreceivingtransaction");

        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['checksreceivingtransaction_ctrlno'] + 1;
        }
        else
        {
            return '1000000001';
        }
    }


    public function check_customer($customer_name) {
        //check if exists
        $query = $this->ccm_db->query("SELECT customer_id FROM customers WHERE fullname = '$customer_name' LIMIT 1");

        if ($query->num_rows() > 0) {
            return $query->result_array()[0]['customer_id'];
        }
        else
        {
            $customer_code = $this->generate_customerCode();
            // Insert
            $data = array(
                'cus_code'   => $customer_code,
                'fullname'   => $customer_name,
                'id'         => '0',
                'created_at' => $this->_timeStamp,
                'updated_at' => $this->_timeStamp
            );
            $this->ccm_db->insert('customers', $data);

            // Get customer_id
            return $this->ccm_db->query("SELECT customer_id FROM customers WHERE cus_code = '$customer_code' LIMIT 1")->row()->customer_id;
        }
    }


    public function get_BU() {
        $user_group = $this->session->userdata('user_group');

        if ($user_group == '1') {
            return '2';
        }
        elseif ($user_group == '2') 
        {
            return '4';
        }
        elseif ($user_group == '3') {
            return '21';
        }
        else
        {
            return '0';
        }
    }


    public function checksreceivingtransaction() {

        $control_no = $this->generate_checksreceivingtransaction_ctrlno();
        $data = array(
            'checksreceivingtransaction_ctrlno' => $control_no, 
            'id'                                => '0',
            'company_id'                        => '1',
            'businessunit_id'                   => $this->get_BU(),
            'created_at'                        => $this->_timeStamp,
            'updated_at'                        => $this->_timeStamp
        );

        $this->ccm_db->insert('checksreceivingtransaction', $data);


        // Get checksreceivingtransaction_id
        return $this->ccm_db->query("SELECT checksreceivingtransaction_id FROM checksreceivingtransaction WHERE checksreceivingtransaction_ctrlno = '$control_no' LIMIT 1")->row()->checksreceivingtransaction_id;

    }





} //end of Model
