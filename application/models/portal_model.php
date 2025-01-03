<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
    }

    public function check_login($username, $password)
    {
        $query = $this->db->query("SELECT tu.*, t.tenancy_type, p.trade_name, t.status 
                                   FROM tenant_users tu
                                   LEFT JOIN tenants t ON tu.tenant_id = t.tenant_id
                                   LEFT JOIN prospect p ON t.prospect_id = p.id
                                   WHERE t.status = 'ACTIVE' 
                                   AND tu.password = '" . MD5($password) . "' AND tu.tenant_id = '$username'");

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $data = array(
                    'id'                => $rows->id,
                    'password'          => $rows->password,
                    'user_type'         => $rows->user_type,
                    'tenant_id'         => $rows->tenant_id,
                    'tenancy_type'      => $rows->tenancy_type,
                    'trade_name'        => $rows->trade_name,
                    'status'            => $rows->status,
                    'portal_logged_in' =>  TRUE
                );
                $this->session->set_userdata($data);
                
            }

            $session = (object)$this->session->userdata;

            $user_session_data = [
                'user_id'    => isset($session->id)         ? $session->id         : '',
                'session_id' => isset($session->session_id) ? $session->session_id : '',
                'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                'user_data'  => json_encode($session),
                'login_in'   => 'portal_leasing'
            ];

            $this->db->insert('user_session', $user_session_data);

            return true;
        }

        return false;
    }
}