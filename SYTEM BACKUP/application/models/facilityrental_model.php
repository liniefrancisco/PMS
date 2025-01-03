<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class facilityrental_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function getAll($tbl_name)
    {
        $query = $this->db->get($tbl_name);
        return $query->result_array();
    }

    public function insert($tbl_name, $data)
    {
        $this->db->insert($tbl_name, $data);
    }

    public function checkexistinsert($data,$column_name,$table_name,$store_id)
    {
        $this->db->select('id');
		$this->db->from($table_name);
        $this->db->where($column_name,$data);
        $this->db->where('store_id', $store_id);
		$result = $this->db->get();
		$count = $result->num_rows();
		if($count>0)
		{
			return 'exist';
		}
		else
		{
			return 'good';
		}
    }

    public function checkexistinsertNoStoreID($data,$column_name,$table_name)
    {
        $this->db->select('id');
		$this->db->from($table_name);
        $this->db->where($column_name,$data);
		$result = $this->db->get();
		$count = $result->num_rows();
		if($count>0)
		{
			return 'exist';
		}
		else
		{
			return 'good';
		}
    }

    public function checkexistinsertWherefrno($data,$column_name,$table_name,$frno)
    {
        $this->db->select('id');
		$this->db->from($table_name);
        $this->db->where($column_name,$data);
        $this->db->where('facilityrental_no', $frno);
		$result = $this->db->get();
		$count = $result->num_rows();
		if($count>0)
		{
			return 'exist';
		}
		else
		{
			return 'good';
		}
    }

    public function checkexistupdate($data,$column_name,$table_name,$id,$store_id)
    {
        $this->db->select('id');
		$this->db->from($table_name);
        $this->db->where($column_name,$data);
        $this->db->where('id !=',$id);
        $this->db->where('store_id', $store_id);
		$result = $this->db->get();
		$count = $result->num_rows();
		if($count>0)
		{
			return 'exist';
		}
		else
		{
			return 'good';
		}

    }

    public function checkexistupdateNoStoreID($data,$column_name,$table_name,$id)
    {
        $this->db->select('id');
		$this->db->from($table_name);
        $this->db->where($column_name,$data);
        $this->db->where('id !=',$id);
		$result = $this->db->get();
		$count = $result->num_rows();
		if($count>0)
		{
			return 'exist';
		}
		else
		{
			return 'good';
		}

    }

    public function selectWhere($tbl_name, $id)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this -> db -> where('id = ' . "'" . $id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function selectWhereColumnName($column_name,$tbl_name, $data)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this -> db -> where($column_name,$data);
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function selectWhere_StoreID($tbl_name, $id)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this -> db -> where('store_id = ' . "'" . $id . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function selectWhereGroupBy_StoreID($tbl_name, $id,$groupby)
    {
        $this -> db -> select('*');
        $this -> db -> from($tbl_name);
        $this -> db -> where('store_id = ' . "'" . $id . "'");
        $this -> db -> group_by($groupby);
        $query = $this -> db -> get();

        return $query->result_array();
    }

    public function getID_WhereStoreID($table_name,$column_name,$data,$store_id)
    {
        // $this->db->select('id');
        // $this->db->from($table_name);
        // $this->db->where($column_name,$data);
        // $this->db->where('store_id', $this->session->userdata('user_group'));
        // $query = $this->db->get();
 
        // return $query->result_array();

        return $this->db->query("SELECT `id` FROM `$table_name` WHERE `$column_name` = '$data' AND `store_id` = '$store_id'  LIMIT 1")->row()->id;

    }

    public function getValue_WhereStoreID($value, $table_name, $column_name,$data)
    {
        return $this->db->query("SELECT `$value` FROM `$table_name` WHERE `$column_name` = '$data'  LIMIT 1")->row()->$value;
    }

    public function populate_facilityrentalcustomer($flag)
    {
        $query = $this->db->query(
            "SELECT
                FacilityRental_CusName
            FROM
                facilityrental_customers
            WHERE
                store_id = '" . $this->session->userdata('user_group') . "'          
            ORDER BY
                FacilityRental_CusName
            ASC
        "); 
   
        return $query->result_array();
    }

    public function generate_frcustomerdetails($customername)
    {
      
        $customerdetails = $this->db->query(
            "SELECT
                `id`,
                `FacilityRental_ContactPerson`,
                `FacilityRental_ContactNumber`,
                `FacilityRental_CustomerAddress`
            FROM
                `facilityrental_customers`            
            WHERE
                store_id = '" . $this->session->userdata('user_group') . "'
            AND
                `FacilityRental_CusName` = '".$customername."'
            LIMIT 1
        ");
        return $customerdetails->result_array();
        
    }
    public function delete($tbl_name, $where, $value)
    {
        $this->db->where($where, $value);
        $this->db->delete($tbl_name);
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function deleteWhereStoreID($tbl_name, $where, $value,$store_id)
    {
        $this->db->where($where, $value);
        $this->db->where('store_id', $store_id);
        $this->db->delete($tbl_name);
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update($data, $id, $tbl_name)
    {
        $this->db->where('id', $id);
        $this->db->update($tbl_name, $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateWhereColumnName($column_name,$data, $id, $tbl_name)
    {
        $this->db->where($column_name, $id);
        $this->db->update($tbl_name, $data);

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_frno($useNext= false)
    {
        // $count = $this->db->query("SELECT max(facilityrental_no) as `counter` FROM `facilityrental_reservation`")->row()->counter;

        // $count = ltrim($count, 'FR');
        // $count = ltrim($count, '0');

        // if ($count == "0")
        // {
        //     $ret = "0000001";
        // } else {
        //     $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        // }
        // return "FR" . $ret;
        $sequence = getSequenceNo(
            [
                'code'          => "FR",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Facility Sequence"
            ],
            [
                'table' =>  'facilityrental_reservation',
                'column' => 'facilityrental_no'
            ],
            $useNext
        );

        return $sequence;
    }

    public function getHoursReserved($store_id)
    {
        $count = $this->db->query("SELECT count(`id`) as 'hours' FROM `facilityrental_tmpreservedatetime` WHERE `store_id` = '$store_id'")->row()->hours;
        return $count;
    }

    public function get_reserved_time($reserve_date, $store_id)
    {
        $query = $this->db->query(
            "SELECT `time` from `facilityrental_reservedtime` INNER JOIN `facilityrental_reservation` on `facilityrental_reservation`.`facilityrental_no` = `facilityrental_reservedtime`.`facilityrental_no` WHERE `facilityrental_reservedtime`.`reserve_date` = '$reserve_date' and `facilityrental_reservation`.`store_id` = '$store_id'"
        );

        return $query->result_array();
    }

    public function get_tmpreserved_time($reserve_date, $store_id)
    {
        $query = $this->db->query(
            "SELECT `tmp_time` from `facilityrental_tmpreservedatetime` where `tmp_date` = '$reserve_date' and `facilityrental_tmpreservedatetime`.`store_id` = '$store_id'"
        );

        return $query->result_array();
    }

    public function get_reservationtable($store_id)
    {
        $query = $this->db->query(
            "SELECT `facilityrental_no`,`FacilityRental_Cusname`,`request_date` FROM `facilityrental_reservation` inner join `facilityrental_customers` on `facilityrental_customers`.`id`
            = `facilityrental_reservation`.`facilityrental_cusid` where `facilityrental_reservation`.`store_id` = '$store_id' order by `facilityrental_no`"
        );

        return $query->result_array();
    }

    public function get_reservedatebyfrno($frno)
    {
        $query = $this->db->query(
            "SELECT `reserve_date` from `facilityrental_reservedtime` where `facilityrental_no` = '$frno' group by `reserve_date`  order by `reserve_date`
            ");

        return $query->result_array();
    }

    public function get_reservetimebyfrno($frno,$reserve_date)
    {
        $query = $this->db->query(
            "SELECT `time` from `facilityrental_reservedtime` where `facilityrental_no` = '$frno' and `reserve_date` = '$reserve_date'
            ");

        return $query->result_array();
    }
    public function get_facilitiesreservedbyfrno($frno)
    {
        $query = $this->db->query(
            "SELECT `facility_name` from `facilityrental_facilities` INNER JOIN 
            `facilityrental_reservedfacility` on `facilityrental_reservedfacility`.`facility_id` = `facilityrental_facilities`.`id`
            where `facilityrental_reservedfacility`.`facilityrental_no` = '$frno'
            ");

        return $query->result_array();
    }

    public function get_tmptime($tmp_date,$store_id)
    {
        $query = $this->db->query(
            "SELECT `tmp_time` from `facilityrental_tmpreservedatetime` 
            where `facilityrental_tmpreservedatetime`.`tmp_date` = '$tmp_date' AND `facilityrental_tmpreservedatetime`.`store_id` = '$store_id'
            ");

        return $query->result_array();
    }
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    //INVOICE
    public function get_reservationtableforInvoice($store_id)
    {
        $query = $this->db->query(
            "SELECT `facilityrental_no`,`FacilityRental_Cusname`,`request_date` FROM `facilityrental_reservation` 
            inner join `facilityrental_customers` on `facilityrental_customers`.`id`= `facilityrental_reservation`.`facilityrental_cusid` 
            where `facilityrental_reservation`.`store_id` = '$store_id' 
            and `facilityrental_reservation`.`invoice_status` != 'Posted'
            order by `facilityrental_no`"
        );

        return $query->result_array();
    }

    public function get_customerdetailsInvoice($frno)
    {
        $data = $this->db->query(
            "SELECT `facilityrental_reservation`.`facilityrental_cusid` as 'frcustomerid',`FacilityRental_Cusname`,`FacilityRental_ContactPerson`,`FacilityRental_ContactNumber`,`FacilityRental_CustomerAddress` 
            FROM `facilityrental_customers` 
            INNER JOIN `facilityrental_reservation` on `facilityrental_reservation`.`facilityrental_cusID` = `facilityrental_customers`.`id` 
            where `facilityrental_reservation`.`facilityrental_no` = '$frno'"
            );
        
        return $data->result_array();
    }

    public function get_facilitiesreservedInvoice($frno)
    {
        $data = $this->db->query("SELECT 
        `facilityrental_facilities`.`id` as 'facility_id',
        `facility_name`,
        `reserve_date` as `DateOfUse`,
        `FacilityRental_rate`,
        (SELECT count(`id`) from `facilityrental_reservedtime` where `facilityrental_no` = '$frno' and `reserve_date` = `DateOfUse`) as `totalHours`
        FROM `facilityrental_reservedfacility` 
        inner join `facilityrental_facilities` on `facilityrental_facilities`.`id` = `facilityrental_reservedfacility`.`facility_id` 
        inner join `facilityrental_reservedtime` on `facilityrental_reservedtime`.`facilityrental_no` = `facilityrental_reservedfacility`.`facilityrental_no` 
        where `facilityrental_reservedfacility`.`facilityrental_no` = '$frno' group by `facilityrental_reservedfacility`.`facility_id`,reserve_date order by reserve_date");

        return $data->result_array();
    }

    
    public function get_discountforinvoice()
    {
        $query = $this->db->query(
            "SELECT
                `discount_type`
            FROM
                `facilityrental_discountsetup`
            ");

        return $query->result_array();
    }

    public function get_discountdetails($data)
    {
        $this -> db -> select('*');
        $this -> db -> from('facilityrental_discountsetup');
        $this -> db -> where('discount_type = ' . "'" . $data . "'");
        $query = $this -> db -> get();

        return $query->result_array();
    }
    public function get_invoiceAppendedDiscount($frno)
    {
        $query = $this->db->query(
            "SELECT `facilityrental_discountsetup`.`id` as 'discount_id',`discount_type`,`discount_option`,`discount_amount`,`discount_description`,`facilityrental_tmpinvoicediscount`.`id` as 'id'
            FROM `facilityrental_discountsetup`
            Inner Join `facilityrental_tmpinvoicediscount`
            on `facilityrental_discountsetup`.`id` = `facilityrental_tmpinvoicediscount`.`discount_id`
            where `facilityrental_tmpinvoicediscount`.`facilityrental_no` = '$frno'
            ");

            return $query->result_array();
    }

    public function get_frinvoicedocno($useNext= false)
    {
        // $count = $this->db->query("SELECT max(facilityrental_no) as `counter` FROM `facilityrental_reservation`")->row()->counter;

        // $count = ltrim($count, 'FR');
        // $count = ltrim($count, '0');

        // if ($count == "0")
        // {
        //     $ret = "0000001";
        // } else {
        //     $ret = str_pad($count + 1, 7, "0", STR_PAD_LEFT);
        // }
        // return "FR" . $ret;
        $sequence = getSequenceNo(
            [
                'code'          => "FR-IC",
                'number'        => '0',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Facility Invoice Sequence"
            ],
            [
                'table' =>  'facilityrental_invoicing',
                'column' => 'facilityrental_docno'
            ],
            $useNext
        );

        return $sequence;
    }
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    //SOA
    public function get_reservationtableforSoa($store_id)
    {
        $query = $this->db->query(
            "SELECT `facilityrental_invoicesummary`.`facilityrental_no` as 'facilityrental_no',`FacilityRental_Cusname`,`request_date`,`facilityrental_docno`
            FROM `facilityrental_invoicesummary` 
            inner join `facilityrental_customers` on `facilityrental_customers`.`id`= `facilityrental_invoicesummary`.`customer_id` 
            inner join `facilityrental_reservation` on `facilityrental_reservation`.`facilityrental_no` = `facilityrental_invoicesummary`.`facilityrental_no`
            where `facilityrental_invoicesummary`.`store_id` = '$store_id' 
            and `facilityrental_invoicesummary`.`is_soa_posted` = 0
            order by `facilityrental_no`"
        );

        return $query->result_array();
    }

    public function get_customerdetailsSoa($frno)
    {
        $data = $this->db->query(
            "SELECT `customer_id` as 'frcustomerid',`FacilityRental_Cusname`,`FacilityRental_ContactPerson`,`FacilityRental_ContactNumber`,`FacilityRental_CustomerAddress`,`expected_amount`,`total_discount`,`actual_amount`,`facilityrental_no`,`facilityrental_docno`
            FROM `facilityrental_invoicesummary` 
            INNER JOIN `facilityrental_customers` on `facilityrental_invoicesummary`.`customer_id` = `facilityrental_customers`.`id` 
            where `facilityrental_invoicesummary`.`facilityrental_no` = '$frno'"
            );
        
        return $data->result_array();
    }

    public function get_SoaNo($useNext= false)
    {
        $sequence = getSequenceNo(
            [
                'code'          => "FR-SOA",
                'number'        => '0',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Facility Soa Sequence"
            ],
            [
                'table' =>  'facilityrental_soa',
                'column' => 'facilityrental_soaNo'
            ],
            $useNext
        );

        return $sequence;
    }
    public function get_soaInvoicing($frno)
    {
        $data = $this->db->query(
            "SELECT `facilityrental_docno`,`facility_name`,`date_used`,`facility_rate`,`hours_used`,`amount`
            FROM `facilityrental_invoicing`
            INNER JOIN `facilityrental_facilities` on `facilityrental_invoicing`.`facility_id` = `facilityrental_facilities`.`id`
            WHERE `facilityrental_no` = '$frno'
            order by `date_used`,`facility_name`
        ");

        return $data->result_array();
    }

    public function get_soaDiscount($frno)
    {
        $data = $this->db->query(
            "SELECT `id`,`discount_type`,`discount_option`,`discount_amount`,`discount_description`,`facilityrental_docno`
            FROM  `facilityrental_addedinvoicediscount`
            WHERE `facilityrental_no` = '$frno'"
        );

        return $data->result_array();
    }

    public function get_invoicingdataSoa($frno)
    {
        $result = $this->db->query(
            "SELECT * FROM `facilityrental_invoicing` where `facilityrental_no` = '$frno'
            ");

        return $result->result_array();
    }

    public function get_storeDetails($store_id)
    {
        $query = $this->db->query("SELECT * FROM stores WHERE id = '$store_id' LIMIT 1");
        return $query->result_array();
    }
    
    public function get_frSoa($customername)
    {
        $result = $this->db->query(
            "SELECT * 
            FROM `facilityrental_soafile` 
            INNER JOIN `facilityrental_customers`
            on `facilityrental_soafile`.`customer_id` = `facilityrental_customers`.`id`   
            where `facilityrental_customers`.FacilityRental_Cusname = '$customername'
         ");

         return $result->result_array();
    }
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    //PAYMENT
    public function get_receiptno($useNext= false)
    {
        $sequence = getSequenceNo(
            [
                'code'          => "FR-PR",
                'number'        => '2',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Facility Sequence"
            ],
            [
                'table' =>  'facilityrental_payment',
                'column' => 'facilityrental_receiptno'
            ],
            $useNext
        );

        return $sequence;
    }

    public function get_storeBankCode()
    {
        $store = $this->session->userdata('store_code');

        $query = $this->db->query(
            "SELECT
                `bank_code`
            FROM
                `accredited_banks`
            WHERE
                `store_code` LIKE '%$store%'
            ");

        return $query->result_array();
    }

    public function get_bankName($bank_code)
    {   
        $store = $this->session->userdata('store_code');
        $query = $this->db->query("SELECT `bank_name` FROM `accredited_banks` WHERE `bank_code` = '$bank_code' AND  store_code LIKE '%$store%' LIMIT 1");
        return $query->result_array();
    }
    
    public function populate_soano($flag)
    {
        $query = $this->db->query(
            "SELECT
                soa_no
            FROM
                facilityrental_soafile
            inner join 
                facilityrental_invoicesummary 
            on 
                `facilityrental_invoicesummary`.`facilityrental_docno` = `facilityrental_soafile`.`facilityrental_docno`
            WHERE
                `facilityrental_soafile`.`store_id` = '" . $this->session->userdata('user_group') . "'          
            and
                `balance`>0
            ORDER BY
                soa_no
            ASC
        ");
   
        return $query->result_array();
    }

    public function get_soadetailspayment($soano)
    {
        $query = $this->db->query(
            "SELECT `FacilityRental_Cusname`, `billing_period`, `facilityrental_soafile`.`expected_amount`, `facilityrental_soafile`.`total_discount`, `amount_payable`, `facilityrental_soafile`.`facilityrental_docno`,`facilityrental_soafile`.`customer_id`,`amount_paid`,`balance`
            FROM `facilityrental_soafile`
            inner join `facilityrental_customers` on `facilityrental_customers`.`id` = `facilityrental_soafile`.`customer_id`
            inner join `facilityrental_invoicesummary` on `facilityrental_soafile`.`facilityrental_docno` = `facilityrental_invoicesummary`.`facilityrental_docno`
            where `soa_no` = '$soano'
            "
        );

        return $query->result_array();
    }

    public function get_paymentsoatable($soano)
    {
        $result = $this->db->query(
            "SELECT `facilityrental_docno`, `facility_name`, `date_used`, `facility_rate`,`hours_used`, `amount` from `facilityrental_soa`
            inner join `facilityrental_invoicing` on `facilityrental_soa`.`facilityrental_invoicingID` = `facilityrental_invoicing`.`id`
            inner join `facilityrental_facilities` on `facilityrental_facilities`.`id` = `facilityrental_invoicing`.`facility_id`
            where `facilityrental_soa`.`facilityrental_soaNo` = '$soano'
            order by `date_used`
            "
        );

        return $result->result_array();
    }

    public function get_paymentdiscounttable($soano)
    {
        $result = $this->db->query(
            "SELECT `discount_type`, `discount_option`, `discount_amount`, `discount_description`,`facilityrental_docno`,`facilityrental_docno`
            FROM `facilityrental_soadiscount`
            inner join `facilityrental_addedinvoicediscount` on `facilityrental_addedinvoicediscount`.`id` = `facilityrental_soadiscount`.`facilityrental_addedinvoicediscountID`
            where `facilityrental_soaNo` = '$soano'
            "
        );

        return $result->result_array();
    }

    public function get_paymentexpectedamount($soano)
    {
        return $this->db->query(
            "SELECT `expected_amount`
            from `facilityrental_soafile`
            where `soa_no` = '$soano'
            "
        )->row()->expected_amount;
    }

    public function get_invoiceSummaryBalance($soano)
    {
        return $this->db->query(
            "SELECT `balance`
            FROM `facilityrental_invoicesummary`
            inner join `facilityrental_soafile` on `facilityrental_soafile`.`facilityrental_docno` = `facilityrental_invoicesummary`.`facilityrental_docno`
            where `soa_no` = '$soano'
            "
        )->row()->balance;
    }
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    //PAYMENT HISTORY
    public function get_paymentdata($customerID)
    {
        $query = $this->db->query(
                "SELECT `facilityrental_receiptno`, `tender_type`, `amount_paid`, `check_no`, `check_date`, `payee`,`receipt_file`,`soa_no`
                FROM `facilityrental_payment`
                INNER JOIN `facilityrental_customers` on `facilityrental_customers`.`id` = `facilityrental_payment`.`customer_id`
                where `facilityrental_payment`.`customer_id` = $customerID
                "
            );

        return $query->result_array();
    }

    public function get_paymentDocs($receipt_no)
    {
        $query = $this->db->query("SELECT `file_name` FROM `facilityrental_paymentsuppdocs` WHERE `facilityrental_receiptno` = '$receipt_no'");
        return $query->result_array();
    }
}