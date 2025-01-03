<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
        $this->consol = $this->load->database('consol', true);
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    // -------------------------------------------------------------------------------------------
    public function gettenantstin($tenancytype, $store, $tinstatus)
    {
        $currentdate = date('Y-m-d');
        $result = [];
        if ($store === 'All') {
            if ($tenancytype === 'All Terms') {
                if ($tinstatus === 'With TIN') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.tin NOT IN ('','ON PROCESS', 'N/A')
                                       AND t.status = 'Active' 
                                       ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'On Process') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.tin = 'ON PROCESS'
                                       AND t.status = 'Active' 
                                       ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'No TIN') {
                    $result = $this->db->query("SELECT 
                                       t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.tin IN ('N/A', '')
                                       AND t.status = 'Active' 
                                       ORDER BY t.tenant_id")->result_array();
                }
            } else {
                if ($tinstatus === 'With TIN') {
                    $result = $this->db->query("SELECT 
                                       t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin NOT IN ('', 'ON PROCESS', 'N/A')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'On Process') {
                    $result = $this->db->query("SELECT 
                                       t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin = 'ON PROCESS'
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'No TIN') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin IN ('N/A', '')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                }
            }
        } else {
            if ($tenancytype === 'All Terms') {
                if ($tinstatus === 'With TIN') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.tin NOT IN ('', 'ON PROCESS', 'N/A')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'On Process') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.tin = 'ON PROCESS'
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'No TIN') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.expiry_date > '{$currentdate}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.tin IN ('', 'N/A')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                }
            } else {
                if ($tinstatus === 'With TIN') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin NOT IN ('', 'ON PROCESS', 'N/A')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'On Process') {
                    $result = $this->db->query("SELECT 
                                        t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin = 'ON PROCESS'
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                } else if ($tinstatus === 'No TIN') {
                    $result = $this->db->query("SELECT 
                                       t.tenant_id,
                                        p.corporate_name,
                                        p.trade_name,
                                        t.tin,
                                        t.wht,
                                        t.opening_date,
                                        t.expiry_date,
                                        t.basic_rental
                                       FROM tenants t
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.tenancy_type = '{$tenancytype}'
                                       AND t.tenant_id NOT LIKE '%DELETED%'
                                       AND t.store_code = '{$store}'
                                       AND t.expiry_date > '{$currentdate}'
                                       AND t.tin IN ('', 'N/A')
                                       AND t.status = 'Active' ORDER BY t.tenant_id")->result_array();
                }
            }
        }

        return $result;
    }


    public function export_tenantLedger($tenant_id)
    {
        $query = $this->db->query(
            "
                SELECT
                        `sl`.`id` as `entry_no`,
                        `p`.`trade_name`,
                        `sl`.`document_type`,
                        `sl`.`due_date`,
                        `sl`.`doc_no`,
                        `sl`.`ref_no`,
                        `ac`.`gl_code`,
                        (CASE
                            WHEN
                                (`sl`.`status` != '' AND `sl`.`status` IS NOT NULL) THEN CONCAT(`ac`.`gl_account`, '-', `sl`.`status`)
                            ELSE `ac`.`gl_account`
                         END) AS `gl_account`,
                        `sl`.`posting_date`,
                        `sl`.`debit`,
                        ABS(`sl`.`credit`) AS `credit`
                    FROM
                        (SELECT *,  @row_number := @row_number + 1 as row_number FROM subsidiary_ledger CROSS JOIN (SELECT @row_number := 0) r WHERE tenant_id = '$tenant_id' ORDER BY posting_date, id ASC) `sl`,
                        `gl_accounts` `ac`,
                        `tenants` `t`,
                        `prospect` `p`
                    WHERE
                        `sl`.`gl_accountID` = `ac`.`id`
                    AND
                        `sl`.`tenant_id` = `t`.`tenant_id`
                    AND
                        `t`.`prospect_id` = `p`.`id`
                    GROUP BY
                        `sl`.`id`
                    ORDER BY
                        `sl`.`posting_date`, `sl`.`id` ASC
            "
        );


        return $query->result_array();
    }


}