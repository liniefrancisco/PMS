<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Leasing_transaction extends CI_Controller
{
function __construct()
{
    parent::__construct();
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('excel');
    $this->load->model('app_model');
    $this->load->library('upload');
    $this->load->library('fpdf');
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
    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
}

function sanitize($string)
{
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
    $string = trim($string);
    return $string;
}

public function Lprospect_management()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['current_date']   = $this->_currentDate;
        if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
        {
            // $data['stores'] = $this->app_model->get_store();
            $data['store_floors'] = $this->app_model->store_floors();
            $data['stores']       = $this->app_model->my_store();
        }
        else
        {
            $data['stores'] = $this->app_model->get_stores();
        }

        $data['leasee_types'] = $this->app_model->getAll('leasee_type');
        $data['category_one'] = $this->app_model->getAll('category_one');

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/Lprospect_management');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function populate_price()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $location_code = str_replace('%20', " ", $this->uri->segment(3));
        $result        = $this->app_model->populate_price($location_code);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function add_lprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if (!empty($_FILES))
        {
            $prospect_id;
            $response[]      = array();
            $preparedby_id   = $this->session->userdata('id');
            $trade_name      = $this->sanitize($this->input->post('trade_name'));
            $corp_name       = $this->sanitize($this->input->post('corp_name'));
            $lessee_type     = $this->input->post('lessee_type');
            $first_category  = $this->sanitize($this->input->post('first_category'));
            $second_category = $this->sanitize($this->input->post('second_category'));
            $third_category  = $this->sanitize($this->input->post('third_category'));
            $contact_person  = $this->sanitize($this->input->post('contact_person'));
            $contact_number  = $this->sanitize($this->input->post('contact_number'));
            $address         = $this->sanitize($this->input->post('address'));
            $store_name      = $this->sanitize($this->input->post('store_name'));
            $floor_name      = $this->sanitize($this->input->post('floor_name'));
            $location_code   = $this->sanitize($this->input->post('location_code'));
            $remarks         = $this->sanitize($this->input->post('remarks'));
            $request_date    = $this->sanitize($this->input->post('request_date'));

            $store_id      = $this->app_model->store_id($store_name);
            $lesseeType_id = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);

            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();

            if ($this->app_model->check_tradeName($store_id, $trade_name)) 
            {
                $data = array(
                    'trade_name'        =>  $trade_name,
                    'corporate_name'    =>  $corp_name,
                    'lesseeType_id'     =>  $lesseeType_id,
                    'first_category'    =>  $first_category,
                    'second_category'   =>  $second_category,
                    'third_category'    =>  $third_category,
                    'contact_person'    =>  $contact_person,
                    'address'           =>  $address,
                    'store_id'          =>  $store_id,
                    'contact_number'    =>  $contact_number,
                    'status'            =>  'Pending',
                    'request_date'      =>  $request_date,
                    'remarks'           =>  $remarks,
                    'prepared_by'       =>  $preparedby_id,
                    'flag'              =>  'Long Term'
                );

                $this->app_model->insert('prospect', $data);
                $prospect_id =  $this->app_model->prospect_id($trade_name, $store_id, $request_date);
                if ($prospect_id != '0')
                {
                

                    for ($i=0; $i < count($_FILES["intent_letter"]['name']); $i++)
                    {
                        $targetPath = getcwd() . '/assets/intent_letter/';
                        $tmpFilePath = $_FILES['intent_letter']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename = $timeStamp . $_FILES['intent_letter']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('intent_letter', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["com_prof"]['name']); $i++)
                    {
                        $targetPath = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['com_prof']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename = $timeStamp . $_FILES['com_prof']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('com_prof', $data);
                        }
                    }


                    for ($i=0; $i < count($_FILES["dti_busireg"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['dti_busireg']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['dti_busireg']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('dti_busireg', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["brochures"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['brochures']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['brochures']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('brochures', $data);
                        }
                    }


                    for ($i=0; $i < count($_FILES["perspective"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['perspective']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['perspective']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('perspective', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["gross_sales"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['gross_sales']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['gross_sales']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('gsales_img', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["pricemenu_list"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['pricemenu_list']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['pricemenu_list']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                            $this->app_model->insert('pricemenu_list', $data);
                        }
                    }

                    $response['msg'] = 'Success';
                }
                else
                {
                    $response['msg'] = 'Internal Error. Please Contact the administrator.';
                }
            }
            else
            {
                $response['msg'] = 'Trade name already exists in this store.';
            }
        }
        else
        {
            $response['msg'] = 'No File';
        }
        echo json_encode($response);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_lprospect()

{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
        {
            $result = $this->app_model->store_lprospect();
            echo json_encode($result);
        }
        else
        {
            $result = $this->app_model->get_lprospect();
            echo json_encode($result);
        }
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_ltforreview()
{
    if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'General Manager')
    {
        $result = $this->app_model->get_ltforreview();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_stforreview()
{
    if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'General Manager')
    {
        $result = $this->app_model->get_stforreview();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function upload_intent_letter()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ( !empty( $_FILES ) )
        {
            $tempPath   = $_FILES[ 'file' ][ 'tmp_name' ];
            $uploadPath = getcwd().DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'intent_letter' . DIRECTORY_SEPARATOR . $currentDate . $_FILES[ 'file' ][ 'name' ];
            move_uploaded_file( $tempPath, $uploadPath );
            $answer = array( 'answer' => 'File transfer completed');
            $json   = json_encode($answer);
            echo $json;
        }
        else
        {
            echo 'No files';
        }
    }
}


public function view_lprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->view_lprospect($id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_letter()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('intent_letter', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_proposal()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_exhibitor_attachements('prospect_id', $id, 'Proposal Letter');
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_excomprof()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_exhibitor_attachements('prospect_id', $id, 'Company Profile');
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_boothlayout()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_exhibitor_attachements('prospect_id', $id, 'Booth Layout');
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_experspective()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_exhibitor_attachements('prospect_id', $id, 'Perspective');
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_exdti_busireg()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_exhibitor_attachements('prospect_id', $id, 'DTI BUSINESS REG');
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_comprof()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('com_prof', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_dtibusireg()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('dti_busireg', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_brochures()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('brochures', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_perspective()
{
    $id     = $this->uri->segment(3);
    $result = $this->app_model->get_img('perspective', 'prospect_id', $id);
    echo json_encode($result);
}


public function get_gsalesImg()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('gsales_img', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_pricemenuList()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_img('pricemenu_list', 'prospect_id', $id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_contractDocs()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_contractDocs($id);
        echo json_encode($result);
    }
}

public function get_suppDocs()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->get_suppDocs($id);
        echo json_encode($result);
    }
}


public function get_prospect_data()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->view_prospect($id);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_lprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update    = $this->uri->segment(3);
        $prospect_id;
        $response[]      = array();
        $preparedby_id   = $this->session->userdata('id');
        $trade_name      = $this->sanitize($this->input->post('trade_name'));
        $corp_name       = $this->sanitize($this->input->post('corp_name'));
        $lessee_type     = $this->input->post('lessee_type');
        $first_category  = $this->sanitize($this->input->post('first_category'));
        $second_category = $this->sanitize($this->input->post('second_category'));
        $third_category  = $this->sanitize($this->input->post('third_category'));
        $contact_person  = $this->sanitize($this->input->post('contact_person'));
        $contact_number  = $this->sanitize($this->input->post('contact_number'));
        $address         = $this->sanitize($this->input->post('address'));
        $store_name      = $this->sanitize($this->input->post('store_name'));
        $floor_name      = $this->sanitize($this->input->post('floor_name'));
        $location_code   = $this->sanitize($this->input->post('location_code'));
        $remarks         = $this->sanitize($this->input->post('remarks'));
        $request_date    = $this->sanitize($this->input->post('request_date'));

        $store_id        = $this->app_model->store_id($store_name);
        $lesseeType_id   = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);
        $locationCode_id = $this->app_model->select_id('location_code', 'location_code', $location_code);


        $date = new DateTime();
        $timeStamp = $date->getTimestamp();

        $data = array(
            'trade_name'        =>  $trade_name,
            'corporate_name'    =>  $corp_name,
            'lesseeType_id'     =>  $lesseeType_id,
            'first_category'    =>  $first_category,
            'second_category'   =>  $second_category,
            'third_category'    =>  $third_category,
            'contact_person'    =>  $contact_person,
            'address'           =>  $address,
            'store_id'          =>  $store_id,
            'contact_number'    =>  $contact_number,
            'request_date'      =>  $request_date,
            'remarks'           =>  $remarks,
            'prepared_by'       =>  $preparedby_id,
            'flag'              =>  'Long Term'
        );

        if ($this->app_model->update($data, $id_to_update, 'prospect'))
        {
            $this->session->set_flashdata('message', 'Updated');
        }


        redirect('Leasing_transaction/Lprospect_management');

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_intent()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update = $this->uri->segment(3);
        $old_file     = $this->input->post('old_file');

        if (!empty($_FILES))
        {
            $date       = new DateTime();
            $timeStamp  = $date->getTimestamp();
            $targetPath = getcwd() . '/assets/intent_letter/';


            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'intent_letter'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_comprof()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $old_file     = $this->input->post('old_file');
        $id_to_update = $this->uri->segment(3);

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'com_prof'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_dti()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update = $this->uri->segment(3);
        $old_file     = $this->input->post('old_file');

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'dti_busireg'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function update_brochures()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update = $this->uri->segment(3);
        $old_file     = $this->input->post('old_file');

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'brochures'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }

    } else {
        redirect('ctrl_leasing/');
    }
}

public function update_perspective()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update = $this->uri->segment(3);
        $old_file     = $this->input->post('old_file');

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'perspective'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_gsaleImg()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update = $this->uri->segment(3);
        $old_file     = $this->input->post('old_file');

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'gsales_img'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }


    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function update_pricemenu()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $old_file     = $this->input->post('old_file');
        $id_to_update = $this->uri->segment(3);

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();
            $targetPath  = getcwd() . '/assets/other_img/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'pricemenu_list'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Lprospect_management');
        }


    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function addImg()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $prospect_id = $this->uri->segment(3);
        $flag        = $this->uri->segment(4);
        $targetPath;

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                    //Setup our new file path
                $filename = $timeStamp . $_FILES['img']['name'];
                $data     = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Long Term');
                switch ($flag) {
                    case 'LOI':
                        $targetPath  = getcwd() . '/assets/intent_letter/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('intent_letter', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'CP':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('com_prof', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'DTI':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('dti_busireg', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'BR':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('brochures', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'GR':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('gsales_img', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'PM':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('pricemenu_list', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                    case 'PERSPECTIVE':
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $newFilePath = $targetPath . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $this->app_model->insert('perspective', $data);
                        $this->session->set_flashdata('message', 'Added');
                        redirect('Leasing_transaction/Lprospect_management');
                    break;
                }
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Sprospect_management');
        }

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function curdate()
{
    return date('Y-m-d');
}

public function now()
{
    return date('Y-m-d H:i:s');
}


// public function magic()
// {
//     $query = $this->db->query("SELECT id, trade_name FROM prospect WHERE status = 'Approved'");

//     foreach ($query->result_array() as $value)
//     {
//         $tenancy_type = $this->app_model->get_tenancyFromProspect($value['id']);
//         $tenantData = array(
//             'prospect_id'   =>  $value['id'],
//             'store_code'    =>  $this->app_model->get_storeCode($value['id']),
//             'tenancy_type'  =>  $tenancy_type,
//             'flag'          =>  'Pending',
//             'tenant_id'     =>  $this->app_model->generateID($value['id'], $tenancy_type),
//             'contract_no'   =>  $this->app_model->generate_contractCode($value['id']),
//             'status'        =>  'Active'
//         );

//         $this->app_model->insert('tenants', $tenantData);
//     }

// }


public function approve_prospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id         = $this->uri->segment(3);
        $store_name = str_replace("%20", " ", $this->uri->segment(4));
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));

        $data = array(
            'status'        =>  'Approved',
            'approved_date' =>   $this->_currentDate
        );

        $tenancy_type = $this->app_model->get_tenancyFromProspect($id);

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "General Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $this->session->set_flashdata('message', 'Approved');
                    $tenantData = array(
                        'prospect_id'   =>  $id,
                        'store_code'    =>  $this->app_model->get_storeCode($id),
                        'tenancy_type'  =>  $tenancy_type,
                        'flag'          =>  'Pending',
                        'tenant_id'     =>  $this->app_model->generateID($id, $tenancy_type),
                        'contract_no'   =>  $this->app_model->generate_contractCode($id),
                        'status'        =>  'Active'
                    );

                    $this->app_model->insert('tenants', $tenantData);
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $tenantData = array(
                    'prospect_id'   =>  $id,
                    'store_code'    =>  $this->app_model->get_storeCode($id),
                    'tenancy_type'  =>  $tenancy_type,
                    'flag'          =>  'Pending',
                    'tenant_id'     =>  $this->app_model->generateID($id, $tenancy_type),
                    'contract_no'   =>  $this->app_model->generate_contractCode($id),
                    'status'        =>  'Active'
                );

                $this->app_model->insert('tenants', $tenantData);
                $this->session->set_flashdata('message', 'Approved');
            }


            if ($this->session->userdata('user_type') == 'General Manager')
            {
                redirect('ctrl_leasing/lt_forreview');
            }
            else
            {
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}



public function gm_review()
{
    if ($this->session->userdata('user_type') == 'Store Manager' || $this->session->userdata('user_type') == 'Administrator')
    {
        $id = $this->uri->segment(3);
        $data = array(
            'status'        =>  'For Review',
            'approved_date' =>  $this->_currentDate
        );

        if ($this->app_model->update($data, $id, 'prospect'))
        {
            $this->session->set_flashdata('message', 'Saved');
        }
        redirect('Leasing_transaction/Lprospect_management');

    }
    else
    {
        $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->session->set_flashdata('message', 'Restriction');
        redirect($prev_url);
    }
}



public function check_expiredTenants()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $expired_tenants = $this->app_model->check_expiredTenants();

        foreach ($expired_tenants as $tenant)
        {
            $this->auto_terminate($tenant['id']);
        }
    }
}

public function auto_terminate($tenant_incrementID)
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $reason        = "End of Contract";
        $tenant_id     = $this->app_model->get_tenantID($tenant_incrementID);
        $store_name    = $this->app_model->my_store();
        $tenancy_type  = $this->app_model->get_tenancy_type($tenant_incrementID);
        $update_status = array('status'  =>  'Terminated');

        $data = array(
            'tenant_id'          =>  $tenant_incrementID,
            'reason'             =>  $reason,
            'termination_date'   =>  $this->_currentDate
        );



        $this->db->trans_start(); // Transaction function starts here!!!
        if ($this->app_model->update_status($update_status, $tenant_incrementID, 'tenants'))
        {
            $this->app_model->disable_locationCode($tenant_incrementID);
            $this->app_model->change_selectedCharges($tenant_id);
            $this->app_model->change_statusAttachment('selected_discount', $tenant_incrementID);
            $this->app_model->change_statusAttachment('contract_docs', $tenant_incrementID);
            $this->app_model->change_selectedCharges($tenant_id);
            $this->app_model->insert('terminated_contract', $data);
        }
        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
        }
    }
}




public function terminate_tenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $reason        = str_replace("%20", " ", $this->uri->segment(3));
        $id            = $this->uri->segment(4);
        $tenant_id     = $this->app_model->get_tenantID($id);
        $username      = $this->sanitize($this->input->post('username'));
        $password      = $this->sanitize($this->input->post('password'));
        $store_name    = $this->app_model->my_store();
        $tenancy_type  = $this->app_model->get_tenancy_type($id);
        $update_status = array('status'  =>  'Terminated');

        $data = array(
            'tenant_id'          =>  $id,
            'reason'             =>  $reason,
            'termination_date'   =>  $this->_currentDate,
            'terminated_by'      =>  $this->session->userdata('id')
        );

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Documentation Officer" && $this->session->userdata('user_type') != "Documentation Officer" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if ($this->app_model->update_status($update_status, $id, 'tenants'))
                {
                    $this->app_model->disable_locationCode($id);
                    $this->app_model->change_selectedCharges($tenant_id);
                    $this->app_model->change_statusAttachment('selected_discount', $id);
                    $this->app_model->change_statusAttachment('contract_docs', $id);
                    $this->app_model->change_selectedCharges($tenant_id);
                    $this->app_model->insert('terminated_contract', $data);
                    $this->session->set_flashdata('message', 'Terminated');
                }

                if ($tenancy_type == 'Short Term')
                {
                    redirect('Leasing_transaction/lst_Stenants');
                } else {
                    redirect('Leasing_transaction/lst_Ltenants');
                }
            }
            else
            {

                $this->session->set_flashdata('message', 'Invalid Key');
                if ($tenancy_type == 'Short Term')
                {
                    redirect('Leasing_transaction/lst_Stenants');
                } else {
                    redirect('Leasing_transaction/lst_Ltenants');
                }
            }
        }
        else
        {
            if ($this->app_model->update_status($update_status, $id, 'tenants'))
            {

                $this->app_model->disable_locationCode($id);
                $this->app_model->change_selectedCharges($tenant_id);
                $this->app_model->change_statusAttachment('selected_discount', $id);
                $this->app_model->change_statusAttachment('contract_docs', $id);
                $this->app_model->change_selectedCharges($tenant_id);
                $this->app_model->insert('terminated_contract', $data);
                $this->session->set_flashdata('message', 'Terminated');
            }
            if ($tenancy_type == 'Short Term')
            {
                redirect('Leasing_transaction/lst_Stenants');
            }
            else
            {
                redirect('Leasing_transaction/lst_Ltenants');
            }
        }

    } else {
        redirect('ctrl_leasing');
    }
}


public function terminated_ltenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->terminated_ltenant();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function terminated_stenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->terminated_stenant();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function deny_prospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id         = $this->uri->segment(3);
        $store_name = str_replace("%20", " ", $this->uri->segment(4));
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));

        $data = array(
            'status'        =>  'Denied',
            'approved_date' =>   $this->_currentDate
        );

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "General Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Documentation Officer" && $this->session->userdata('user_type') != "Documentation Officer" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $this->session->set_flashdata('message', 'Denied');
                }
                redirect('Leasing_transaction/Lprospect_management');
            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $this->session->set_flashdata('message', 'Denied');
            }

            if ($this->session->userdata('user_type') == 'General Manager')
            {
                redirect('ctrl_leasing/lt_forreview');
            }
            else
            {
                redirect('Leasing_transaction/Lprospect_management');
            }
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function deny_lprospect2()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id         = $this->uri->segment(3);
        $store_name = str_replace("%20", " ", $this->uri->segment(4));
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));

        $data = array('status' => 'Denied');

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $this->session->set_flashdata('message', 'Denied');
                }
                redirect('Leasing_transaction/lst_Lforcontract');

            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/lst_Lforcontract');
            }

        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $this->session->set_flashdata('message', 'Denied');
            }
            redirect('Leasing_transaction/lst_Lforcontract');
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function Sprospect_management()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        $data['flashdata']      = $this->session->flashdata('message');
        $data['current_date']   = $this->_currentDate;
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();

        if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
        {
            // $data['stores'] = $this->app_model->get_store();
            $data['store_floors'] = $this->app_model->store_floors();
            $data['stores'] = $this->app_model->my_store();

        }
        else
        {
            $data['stores'] = $this->app_model->get_stores();
        }

        $data['leasee_types'] = $this->app_model->getAll('leasee_type');
        $data['category_one'] = $this->app_model->getAll('category_one');

        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/Sprospect_management');
        $this->load->view('leasing/footer');

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function exhibit_code()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $store_name = str_replace("%20", " ", $this->uri->segment(3));
        $floor_name = str_replace("%20", " ", $this->uri->segment(4));
        $result     = $this->app_model->exhibit_code($store_name, $floor_name);
        echo json_encode($result);

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function exhibit_credentials()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $location_code = $this->uri->segment(3);
        $result        = $this->app_model->exhibit_credentials($location_code);
        echo json_encode($result);

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function floor_exhibit_price()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $store_name = str_replace("%20", " ", $this->uri->segment(3));
        $result     = $this->app_model->floor_exhibit_price($store_name);
        echo json_encode($result);

    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function exhibit_floors()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $store_name = str_replace("%20", " ", $this->uri->segment(3));
        $result     = $this->app_model->exhibit_floors($store_name);
        echo json_encode($result);

    } else {
        redirect('ctrl_leasing');
    }
}


public function add_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if (!empty($_FILES))
        {

            $prospect_id;
            $response[]      = array();
            $preparedby_id   = $this->session->userdata('id');
            $trade_name      = $this->sanitize($this->input->post('trade_name'));
            $corp_name       = $this->sanitize($this->input->post('corp_name'));
            $lessee_type     = $this->input->post('lessee_type');
            $first_category  = $this->sanitize($this->input->post('first_category'));
            $second_category = $this->sanitize($this->input->post('second_category'));
            $third_category  = $this->sanitize($this->input->post('third_category'));
            $contact_person  = $this->sanitize($this->input->post('contact_person'));
            $contact_number  = $this->sanitize($this->input->post('contact_number'));
            $address         = $this->sanitize($this->input->post('address'));
            $store_name      = $this->sanitize($this->input->post('store_name'));

            $remarks       = $this->sanitize($this->input->post('remarks'));
            $request_date  = $this->sanitize($this->input->post('request_date'));
            $store_id      = $this->app_model->store_id($store_name);
            $lesseeType_id = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);


            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();

            if ($this->app_model->check_tradeName($store_id, $trade_name)) 
            {
                $data = array(
                    'trade_name'        =>  $trade_name,
                    'corporate_name'    =>  $corp_name,
                    'lesseeType_id'     =>  $lesseeType_id,
                    'first_category'    =>  $first_category,
                    'second_category'   =>  $second_category,
                    'third_category'    =>  $third_category,
                    'contact_person'    =>  $contact_person,
                    'address'           =>  $address,
                    'store_id'          =>  $store_id,
                    'contact_number'    =>  $contact_number,
                    'status'            =>  'Pending',
                    'request_date'      =>  $request_date,
                    'remarks'           =>  $remarks,
                    'prepared_by'       =>  $preparedby_id,
                    'flag'              =>  'Short Term'
                );

                $this->app_model->insert('prospect', $data);
                $prospect_id =  $this->app_model->prospect_id($trade_name, $store_id, $request_date);



                if ($prospect_id != '0')
                {

                    for ($i=0; $i < count($_FILES["proposal_letter"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
                        $tmpFilePath = $_FILES['proposal_letter']['tmp_name'][$i];
                            //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                                //Setup our new file path
                            $filename    = $timeStamp . $_FILES['proposal_letter']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Proposal Letter');
                            $this->app_model->insert('exhibitor_attachements', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["com_prof"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
                        $tmpFilePath = $_FILES['com_prof']['tmp_name'][$i];
                            //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['com_prof']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Company Profile');
                            $this->app_model->insert('exhibitor_attachements', $data);
                        }
                    }


                    for ($i=0; $i < count($_FILES["dti_busireg"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
                        $tmpFilePath = $_FILES['dti_busireg']['tmp_name'][$i];
                            //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['dti_busireg']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'DTI BUSINESS REG');
                            $this->app_model->insert('exhibitor_attachements', $data);
                        }
                    }



                    for ($i=0; $i < count($_FILES["perspective"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
                        $tmpFilePath = $_FILES['perspective']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['perspective']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Perspective');
                            $this->app_model->insert('exhibitor_attachements', $data);
                        }
                    }

                    for ($i=0; $i < count($_FILES["booth_layout"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
                        $tmpFilePath = $_FILES['booth_layout']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['booth_layout']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => 'Booth Layout');
                            $this->app_model->insert('exhibitor_attachements', $data);
                        }
                    }

                    $response['msg'] = 'Success';
                }
                else
                {
                    $response['msg'] = 'Internal Error. Please Contact the administrator.';
                }
            }
            else
            {
                $response['msg'] = 'Trade name already exists in this store.';
            }

        }
        else
        {
                $response['msg'] = 'No File';
        }

            echo json_encode($response);

    } else {
        redirect('ctrl_leasing/');
    }
}


public function truncate_transaction()
{

    /*$this->db->query("TRUNCATE tmp_latepaymentpenalty");
    $this->db->query("TRUNCATE soa_file");
    $this->db->query("TRUNCATE soa");
    $this->db->query("TRUNCATE payment_scheme");
    $this->db->query("TRUNCATE ledger");
    $this->db->query("TRUNCATE invoicing");
    $this->db->query("TRUNCATE general_ledger");
    $this->db->query("TRUNCATE tmp_preoperationcharges");
    $this->db->query("TRUNCATE uft_payment");
    $this->db->query("TRUNCATE waived_penalties");
    $this->db->query("TRUNCATE monthly_receivable_report");
    $this->db->query("TRUNCATE subsidiary_ledger");
    $this->db->query("TRUNCATE wof_transactions");


    $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
    $this->session->set_flashdata('message', 'Deleted');
    redirect($prev_url);*/

}


public function get_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        if ($this->session->userdata('user_group') != '0' && $this->session->userdata('user_group') != null)
        {
            $result = $this->app_model->store_sprospect();
            echo json_encode($result);
        }
        else
        {
            $result = $this->app_model->get_sprospect();
            echo json_encode($result);
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function view_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id = $this->uri->segment(3);
        $result = $this->app_model->view_sprospect($id);
        echo json_encode($result);

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function update_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id_to_update    = $this->uri->segment(3);
        $prospect_id;
        $response[]      = array();
        $preparedby_id   = $this->session->userdata('id');
        $trade_name      = $this->sanitize($this->input->post('trade_name'));
        $corp_name       = $this->sanitize($this->input->post('corp_name'));
        $lessee_type     = $this->input->post('lessee_type');
        $first_category  = $this->sanitize($this->input->post('first_category'));
        $second_category = $this->sanitize($this->input->post('second_category'));
        $third_category  = $this->sanitize($this->input->post('third_category'));
        $contact_person  = $this->sanitize($this->input->post('contact_person'));
        $contact_number  = $this->sanitize($this->input->post('contact_number'));
        $address         = $this->sanitize($this->input->post('address'));
        $store_name      = $this->sanitize($this->input->post('store_name'));
        $remarks         = $this->sanitize($this->input->post('remarks'));
        $request_date    = $this->sanitize($this->input->post('request_date'));

        $lesseeType_id   = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);
        $store_id        = $this->app_model->store_id($store_name);
        // $categoryOne_id = $this->app_model->select_id('category_one', 'category_name', $first_category);
        // $locationCode_id = $this->app_model->select_id('price_locationcode', 'location_code', $location_code);

        $date = new DateTime();
        $timeStamp = $date->getTimestamp();

        $data = array(
            'trade_name'        =>  $trade_name,
            'corporate_name'    =>  $corp_name,
            'lesseeType_id'     =>  $lesseeType_id,
            'first_category'    =>  $first_category,
            'second_category'   =>  $second_category,
            'third_category'    =>  $third_category,
            'contact_person'    =>  $contact_person,
            'address'           =>  $address,
            'store_id'          =>  $store_id,
            'contact_number'    =>  $contact_number,
            'request_date'      =>  $request_date,
            'remarks'           =>  $remarks,
            'prepared_by'       =>  $preparedby_id,
            'flag'              =>  'Short Term'
        );


        if ($this->app_model->update($data, $id_to_update, 'prospect'))
        {
            $this->session->set_flashdata('message', 'Updated');
        }
        redirect('Leasing_transaction/Sprospect_management');

    } else {
       redirect('ctrl_leasing');
    }
}


public function update_exhibit_attachments()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        $old_file     = $this->input->post('old_file');
        $id_to_update = $this->uri->segment(3);

        if (!empty($_FILES))
        {
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('file_name' => $filename);
                if ($this->app_model->update($data, $id_to_update, 'exhibitor_attachements'))
                {
                    $this->session->set_flashdata('message', 'Updated');
                    $this->app_model->unlink_file($targetPath, $old_file);
                }
                redirect('Leasing_transaction/Sprospect_management');
            }
        } else
        {
            $this->session->set_flashdata('message', 'Required');
            redirect('Leasing_transaction/Sprospect_management');
        }

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function add_exhibitor_attachements()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $prospect_id = $this->uri->segment(3);
        $flag        = str_replace("%20", " ", $this->sanitize($this->uri->segment(4)));

        if (!empty($_FILES))
        {
            $date        = new DateTime();
            $timeStamp   = $date->getTimestamp();

            $targetPath  = getcwd() . '/assets/exhibitor_attachements/';
            $tmpFilePath = $_FILES['img']['tmp_name'];
                //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['img']['name'];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('prospect_id' => $prospect_id, 'file_name' => $filename, 'flag' => $flag);
                $this->app_model->insert('exhibitor_attachements', $data);
                $this->session->set_flashdata('message', 'Added');
                redirect('Leasing_transaction/Sprospect_management');
            }
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function approve_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id           = $this->uri->segment(3);
        $store_name   = str_replace("%20", " ", $this->uri->segment(4));
        $username     = $this->sanitize($this->input->post('username'));
        $password     = $this->sanitize($this->input->post('password'));
        $tenancy_type = $this->app_model->get_tenancyFromProspect($id);

        $data = array(
            'status'        =>  'Approved',
            'approved_date' =>   $this->_currentDate
        );

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "General Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Documentation Officer" && $this->session->userdata('user_type') != "Documentation Officer" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {

                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $tenantData = array(
                        'prospect_id'   =>  $id,
                        'store_code'    =>  $this->app_model->get_storeCode($id),
                        'tenancy_type'  =>  $tenancy_type,
                        'flag'          =>  'Pending',
                        'tenant_id'     =>  $this->app_model->generateID($id, $tenancy_type),
                        'contract_no'   =>  $this->app_model->generate_contractCode($id),
                        'status'        =>  'Active'
                    );

                    $this->app_model->insert('tenants', $tenantData);
                    $this->session->set_flashdata('message', 'Approved');
                }
                redirect('Leasing_transaction/Sprospect_management');

            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/Sprospect_management');
            }

        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $tenantData = array(
                    'prospect_id'   =>  $id,
                    'store_code'    =>  $this->app_model->get_storeCode($id),
                    'tenancy_type'  =>  $tenancy_type,
                    'flag'          =>  'Pending',
                    'tenant_id'     =>  $this->app_model->generateID($id, $tenancy_type),
                    'contract_no'   =>  $this->app_model->generate_contractCode($id),
                    'status'        =>  'Active'
                );

                $this->app_model->insert('tenants', $tenantData);
                $this->session->set_flashdata('message', 'Approved');
            }

            if ($this->session->userdata('user_type') == 'General Manager')
            {
                redirect('ctrl_leasing/st_forreview');
            }
            else
            {
                redirect('Leasing_transaction/Sprospect_management');
            }
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function deny_sprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id         = $this->uri->segment(3);
        $store_name = str_replace("%20", " ", $this->uri->segment(4));
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));

        $data = array(
            'status'        =>  'Denied',
            'approved_date' =>   $this->_currentDate
        );

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "General Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Documentation Officer" && $this->session->userdata('user_type') != "Documentation Officer" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {

                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $this->session->set_flashdata('message', 'Denied');
                }
                redirect('Leasing_transaction/Sprospect_management');
            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/Sprospect_management');
            }

        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $this->session->set_flashdata('message', 'Denied');
            }

            if ($this->session->userdata('user_type') == 'General Manager')
            {
                redirect('ctrl_leasing/st_forreview');
            }
            else
            {
                redirect('Leasing_transaction/Sprospect_management');
            }
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function deny_sprospect2()
{
     if ($this->session->userdata('leasing_logged_in'))
    {
        $id         = $this->uri->segment(3);
        $store_name = str_replace("%20", " ", $this->uri->segment(4));
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));

        $data = array('status' => 'Denied');

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if ($this->app_model->update($data, $id, 'prospect'))
                {
                    $this->session->set_flashdata('message', 'Denied');
                }
                redirect('Leasing_transaction/lst_Sforcontract');

            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/lst_Sforcontract');
            }

        }
        else
        {
            if ($this->app_model->update($data, $id, 'prospect'))
            {
                $this->session->set_flashdata('message', 'Denied');
            }
            redirect('Leasing_transaction/lst_Sforcontract');
        }
    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function Lcontract_management()
{
    if($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/Lcontract_management');
        $this->load->view('leasing/footer');

    }
    else
    {
        redirect('ctrl_leasing');
    }
}



public function lst_Lforcontract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata'] = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_Lforcontract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function lst_lpendingContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['leasee_types']   = $this->app_model->getAll('leasee_type');
        $data['category_one']   = $this->app_model->getAll('category_one');
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_lpendingContract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function lst_SpendingContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_SpendingContract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing');
    }

}


public function get_approveLprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_approveLprospect();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function create_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id                     = $this->uri->segment(3);
        $store_code             = $this->app_model->get_storeCode($id);
        $tenancy_type           = str_replace("%20", " ", $this->uri->segment(4));
        $data['details']        = $this->app_model->view_prospect($id);
        $data['floor_location'] = $this->app_model->get_floorLocation($id);
        $data['flashdata']      = $this->session->flashdata('message');
        $data['contract_no']    = $this->app_model->get_myContract($id);
        $data['classification'] = $this->app_model->getAll('area_classification');
        $data['area_type']      = $this->app_model->getAll('area_type');
        $data['rent_period']    = $this->app_model->get_rentPeriod($tenancy_type);
        $data['tenant_id']      = $this->app_model->get_myTenantID($id);
        $data['location_code']  = $store_code . '-' . $this->app_model->generate_locationCodeID();
        $data['current_date']   = $this->_currentDate;
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/create_contract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing');
    }
}



public function get_locationSlots()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $floor_id      = $this->uri->segment(3);
        $idArray       = array();
        $occupiedSlots = $this->app_model->get_occupiedSlots($floor_id);

        foreach ($occupiedSlots as $slots_id)
        {
            $id = explode(",", $slots_id['slots_id']);

            for ($i=0; $i < count($id); $i++)
            {
                array_push($idArray, $id[$i]);
            }
        }
        $result = $this->app_model->get_locationSlots($floor_id, $idArray);
        echo json_encode($result);
    }
}


public function get_availableSlot_for_amendents()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $floor_name    = str_replace("%20", " ", $this->uri->segment(3));
        $store_name    = str_replace("%20", " ", $this->uri->segment(4));

        $floor_id      = $this->app_model->get_floorID($store_name, $floor_name);

        $idArray       = array();
        $occupiedSlots = $this->app_model->get_occupiedSlots($floor_id);

        foreach ($occupiedSlots as $slots_id)
        {
            $id = explode(",", $slots_id['slots_id']);

            for ($i=0; $i < count($id); $i++)
            {
                array_push($idArray, $id[$i]);
            }
        }
        $result = $this->app_model->get_locationSlots($floor_id, $idArray);
        echo json_encode($result);
    }
}


public function setup_forOtherContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']    = $this->session->flashdata('message');
        $prospect_id          = $this->uri->segment(3);
        $data['prospectData'] = $this->app_model->view_prospect($prospect_id);
        $data['stores']       = $this->app_model->get_anotherStores($prospect_id);
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/setup_forOtherContract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/admin_login');
    }
}


public function save_setForOtherContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $response[]     = array();
        $old_prospectID = $this->uri->segment(3);
        $date           = new DateTime();
        $timeStamp      = $date->getTimestamp();

        if (!empty($_FILES))
        {
            $preparedby_id   = $this->session->userdata('id');
            $trade_name      = $this->sanitize($this->input->post('trade_name'));
            $corp_name       = $this->sanitize($this->input->post('corp_name'));
            $lessee_type     = $this->sanitize($this->input->post('lessee_type'));
            $first_category  = $this->sanitize($this->input->post('first_category'));
            $second_category = $this->sanitize($this->input->post('second_category'));
            $third_category  = $this->sanitize($this->input->post('third_category'));
            $contact_person  = $this->sanitize($this->input->post('contact_person'));
            $contact_number  = $this->sanitize($this->input->post('contact_number'));
            $address         = $this->sanitize($this->input->post('address'));
            $store_name      = $this->sanitize($this->input->post('store_name'));
            $floor_name      = $this->sanitize($this->input->post('floor_name'));
            $location_code   = $this->sanitize($this->input->post('location_code'));
            $remarks         = $this->sanitize($this->input->post('remarks'));
            $request_date    = $this->sanitize($this->input->post('request_date'));

            $lesseeType_id   = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);
            $locationCode_id = $this->app_model->select_id('location_code', 'location_code', $location_code);

             $data = array(
                'trade_name'        =>  $trade_name,
                'corporate_name'    =>  $corp_name,
                'lesseeType_id'     =>  $lesseeType_id,
                'first_category'    =>  $first_category,
                'second_category'   =>  $second_category,
                'third_category'    =>  $third_category,
                'contact_person'    =>  $contact_person,
                'address'           =>  $address,
                'locationCode_id'   =>  $locationCode_id,
                'contact_number'    =>  $contact_number,
                'status'            =>  'Pending',
                'request_date'      =>  $request_date,
                'remarks'           =>  $remarks,
                'prepared_by'       =>  $preparedby_id,
                'flag'              =>  'Long Term'
            );

            $this->app_model->insert('prospect', $data);
            $new_prospectID =  $this->app_model->prospect_id($trade_name, $locationCode_id, $request_date);

            for ($i=0; $i < count($_FILES["intent_letter"]['name']); $i++)
            {
                $targetPath  = getcwd() . '/assets/intent_letter/';
                $tmpFilePath = $_FILES['intent_letter']['tmp_name'][$i];
                //Make sure we have a filepath
                if ($tmpFilePath != "")
                {
                    //Setup our new file path
                    $filename    = $timeStamp . $_FILES['intent_letter']['name'][$i];
                    $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                    move_uploaded_file($tmpFilePath, $newFilePath);

                    $data = array('prospect_id' => $new_prospectID, 'file_name' => $filename, 'flag' => 'Long Term');
                    $this->app_model->insert('intent_letter', $data);
                }
            }

            for ($i=0; $i < count($_FILES["perspective"]['name']); $i++)
            {
                $targetPath  = getcwd() . '/assets/other_img/';
                $tmpFilePath = $_FILES['perspective']['tmp_name'][$i];
                //Make sure we have a filepath
                if ($tmpFilePath != "")
                {
                    //Setup our new file path
                    $filename    = $timeStamp . $_FILES['perspective']['name'][$i];
                    $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                    move_uploaded_file($tmpFilePath, $newFilePath);

                    $data = array('prospect_id' => $new_prospectID, 'file_name' => $filename, 'flag' => 'Long Term');
                    $this->app_model->insert('perspective', $data);
                }
            }

            $com_prof       = $this->app_model->get_img('com_prof', 'prospect_id', $old_prospectID);
            $dti_busireg    = $this->app_model->get_img('dti_busireg', 'prospect_id', $old_prospectID);
            $brochures      = $this->app_model->get_img('brochures', 'prospect_id', $old_prospectID);
            $gsales_img     = $this->app_model->get_img('gsales_img', 'prospect_id', $old_prospectID);
            $pricemenu_list = $this->app_model->get_img('pricemenu_list', 'prospect_id', $old_prospectID);

            foreach ($com_prof as $img)
            {
                $imgData = array('prospect_id' => $new_prospectID, 'file_name' => $img['file_name'], 'flag' => 'Long Term');
                $this->app_model->insert('com_prof', $imgData);
            }

            foreach ($dti_busireg as $img)
            {
                $imgData = array('prospect_id' => $new_prospectID, 'file_name' => $img['file_name'], 'flag' => 'Long Term');
                $this->app_model->insert('dti_busireg', $imgData);
            }

            foreach ($brochures as $img)
            {
                $imgData = array('prospect_id' => $new_prospectID, 'file_name' => $img['file_name'], 'flag' => 'Long Term');
                $this->app_model->insert('brochures', $imgData);

            }

            foreach ($gsales_img as $img)
            {
                $imgData = array('prospect_id' => $new_prospectID, 'file_name' => $img['file_name'], 'flag' => 'Long Term');
                $this->app_model->insert('gsales_img', $imgData);

            }


            foreach ($pricemenu_list as $img)
            {
                $imgData = array('prospect_id' => $new_prospectID, 'file_name' => $img['file_name'], 'flag' => 'Long Term');
                $this->app_model->insert('pricemenu_list', $imgData);
            }


            $response['msg'] = 'Success';

        }
        else
        {
            $response['msg'] = 'No File';
        }


        echo json_encode($response);
    }
    else
    {
        redirect('ctrl_leasing/admin_login');
    }
}

public function lterminated_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lterminated_contract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function screate_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id = $this->uri->segment(3);
        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {
            $username   = $this->sanitize($this->input->post('username'));
            $password   = $this->sanitize($this->input->post('password'));
            $store_name = $this->app_model->my_store();
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $data['basic_data']    = $this->app_model->view_sprospect($id);
                $data['tenant_id']     = $this->app_model->shortTerm_tenantID($id);
                $data['contract_no']   = $this->app_model->generate_contractCode_forShorterm($id);
                $data['discount_type'] = $this->app_model->getAll('tenant_type');
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/screate_contract');
                $this->load->view('leasing/footer');
            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/lst_Sforcontract');
            }
        }
        else
        {
            $data['basic_data']    = $this->app_model->view_sprospect($id);
            $data['tenant_id']     = $this->app_model->shortTerm_tenantID($id);
            $data['contract_no']   = $this->app_model->generate_contractCode_forShorterm($id);
            $data['discount_type'] = $this->app_model->getAll('tenant_type');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/screate_contract');
            $this->load->view('leasing/footer');
        }

    }
    else
    {
        redirect('ctrl_leasing');
    }
}


public function Scontract_management()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/Scontract_management');
        $this->load->view('leasing/footer');

    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function get_approveSprospect()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_approveSprospect();
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function lst_Sforcontract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_Sforcontract');
        $this->load->view('leasing/footer');

    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function sterminated_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/sterminated_contract');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function s_renew()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {
            $username   = $this->sanitize($this->input->post('username'));
            $password   = $this->sanitize($this->input->post('password'));
            $store_name = $this->app_model->my_store();

            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $data['prospectData'] = $this->app_model->get_prospectData($tenant_id);
                $data['tenant_id']    = $tenant_id;

                $data['contract_no']  = $this->app_model->generate_contractCode_forShorterm($this->app_model->get_prospect_id($tenant_id));
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/s_renew');
                $this->load->view('leasing/footer');

            }
            else
            {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/sterminated_contract');
            }
        }
        else
        {
            $data['prospectData'] = $this->app_model->get_prospectData($tenant_id);
            $data['tenant_id']    = $tenant_id;

            $data['contract_no']  = $this->app_model->generate_contractCode_forShorterm($this->app_model->get_prospect_id($tenant_id));
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/s_renew');
            $this->load->view('leasing/footer');
        }

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function renew_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        $id                         = $this->uri->segment(3);
        $prospect_id                = $this->uri->segment(4);
        $tenancy_type               = $this->app_model->get_tenancy_type($id);
        $data['tenant_incrementID'] = $id;
        $data['expiry_tenants']     = $this->app_model->get_expiryTenants();
        $data['details']            = $this->app_model->get_tenantOldData($id);
        $data['flashdata']          = $this->session->flashdata('message');
        $data['current_date']       = $this->_currentDate;
        $data['floor_location']     = $this->app_model->get_floorLocation($prospect_id);
        $data['flashdata']          = $this->session->flashdata('message');
        $data['contract_no']        = $this->app_model->generate_contractCode($prospect_id);
        $data['classification']     = $this->app_model->getAll('area_classification');
        $data['area_type']          = $this->app_model->getAll('area_type');
        $data['rent_period']        = $this->app_model->get_rentPeriod($tenancy_type);
        $store_code                 = $this->app_model->get_storeCode($prospect_id);
        $data['location_code']      = $this->app_model->get_previousLocationCode($id);
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/renew_contract');
        $this->load->view('leasing/footer');

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function l_renew()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {
            $username   = $this->sanitize($this->input->post('username'));
            $password   = $this->sanitize($this->input->post('password'));
            $store_name = $this->app_model->my_store();

            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $data['prospectData'] = $this->app_model->get_lprospectData($tenant_id);
                $data['tenant_id']    = $tenant_id;
                $data['contract_no']  = $this->app_model->generate_contractCode($this->app_model->get_prospect_id($tenant_id));
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/l_renew');
                $this->load->view('leasing/footer');
            } else {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/lterminated_contract');
            }
        }
        else
        {
            $data['prospectData'] = $this->app_model->get_lprospectData($tenant_id);
            $data['tenant_id']    = $tenant_id;
            $data['contract_no']  = $this->app_model->generate_contractCode($this->app_model->get_prospect_id($tenant_id));
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/l_renew');
            $this->load->view('leasing/footer');
        }
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function get_discount()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data   = str_replace("%20", " ", $this->sanitize($this->uri->segment(3)));
        $result = $this->app_model->get_discount($data);
        echo json_encode($result);
    }
    else
    {
        redirect('ctrl_leasing');
    }
}

public function managersKey()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $response[] = array();
        $username   = $this->sanitize($this->input->post('username'));
        $password   = $this->sanitize($this->input->post('password'));
        $store_name = $this->db->query("SELECT store_name FROM stores WHERE id = '" . $this->session->userdata('user_group') . "' LIMIT 1")->row()->store_name;

        if ($this->app_model->managers_key($username, $password, $store_name))
        {
            $response['msg'] = "Correct";
        }
        else
        {
            $response['msg'] = "Incorrect";
        }

        echo json_encode($response);
    }
}


public function add_tenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $date                 = new DateTime();
        $timeStamp            = $date->getTimestamp();
        $response[]           = array();
        $prospect_id          = $this->sanitize($this->input->post('prospect_id'));
        $store_code           = $this->app_model->get_storeCode($prospect_id);
        $floor_location       = $this->sanitize($this->input->post('floor_location'));
        $tenant_id            = $this->sanitize($this->input->post('tenant_id'));
        $contract_no          = $this->sanitize($this->input->post('contract_no'));
        $tenancy_type         = $this->sanitize($this->input->post('tenancy_type'));
        $tin                  = $this->sanitize($this->input->post('tin'));
        $rental_type          = $this->sanitize($this->input->post('rental_type'));
        $rent_percentage      = $this->sanitize($this->input->post('rent_percentage'));
        $sales                = $this->sanitize($this->input->post('sales'));
        $location_code        = $this->sanitize($this->input->post('location_code'));
        $slots_id             = $this->sanitize($this->input->post('slots_id'));
        $floor_area           = str_replace(",", "", $this->input->post('floor_area'));
        $area_classification  = $this->sanitize($this->input->post('area_classification'));
        $area_type            = $this->sanitize($this->input->post('area_type'));
        $rent_period          = $this->sanitize($this->input->post('rent_period'));
        $location_desc        = $this->sanitize($this->input->post('location_desc'));
        $opening_date         = $this->sanitize($this->input->post('opening_date'));
        $expiry_date          = $this->sanitize($this->input->post('expiry_date'));
        $increment_percentage = $this->sanitize($this->input->post('increment_percentage'));
        $increment_frequency  = $this->sanitize($this->input->post('increment_frequency'));
        $tenant_type          = $this->input->post('tenant_type');
        $is_vat               = $this->input->post('plus_vat');
        $less_wht             = $this->input->post('less_wht');
        $vat_percentage       = $this->input->post('vat_percentage');
        $wht_percentage       = $this->input->post('wht_percentage');
        $vat_agreement        = $this->input->post('vat_agreement');
        $penalty_exempt       = $this->sanitize($this->input->post('penalty_exempt'));
        $bir_doc              = "";
        $basic_rental         = $this->sanitize($this->input->post('basic_rental'));
        $basic_rental         = str_replace(",", "", $basic_rental);
        $sdiscount            = $this->input->post('sdiscount');
        $store_name           = $this->input->post('store_name');
        $monthly_charges      = $this->input->post('monthly_charges');

        if ($rental_type == 'Fixed') {$rent_percentage = 0;}
        if ($is_vat == 'on') {$is_vat = 'Added';} else {$vat_percentage = 0;}
        if ($less_wht == 'on') {$less_wht = 'Added';} else {$wht_percentage = 0;}
        if ($increment_percentage == 'None') {$increment_percentage = 0;}
        if ($penalty_exempt != '1') {$penalty_exempt = 0;}

        $username = str_replace("%20", " ", $this->uri->segment(3));
        $password = str_replace("%20", " ", $this->uri->segment(4));

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                if (($rental_type == 'Percentage'  || $rental_type == 'Fixed Plus Percentage' || $rental_type == 'Fixed/Percentage w/c Higher') && $rent_percentage == '0')
                {
                    $response['msg'] = "Please fill out all required fields.";
                }
                else
                {

                    if ($tenant_type == 'Private Entities')
                    {
                        if ($is_vat != "Added")
                        {
                            $targetPath  = getcwd() . '/assets/bir/';
                            $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);
                                $bir_doc = $filename;
                            }
                        }
                    }

                    if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
                    {

                        for ($i=0; $i < count($_FILES["supporting_doc"]['name']); $i++)
                        {
                            $targetPath  = getcwd() . '/assets/other_img/';
                            $tmpFilePath = $_FILES['supporting_doc']['tmp_name'][$i];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $timeStamp . $_FILES['supporting_doc']['name'][$i];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);

                                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                                $this->app_model->insert('tenanttype_supportingdocs', $data);
                            }
                        }
                    }

                    $this->db->trans_start(); // Transaction function starts here!!!



                    $locationData = array(
                        'tenancy_type'          =>  $tenancy_type,
                        'slots_id'              =>  $slots_id,
                        'floor_id'              =>  $floor_location,
                        'location_code'         =>  $location_code,
                        'location_desc'         =>  $location_desc,
                        'floor_area'            =>  $floor_area,
                        'area_classification'   =>  $area_classification,
                        'area_type'             =>  $area_type,
                        'rent_period'           =>  $rent_period,
                        'rental_rate'           =>  $basic_rental,
                        'status'                =>  'Active',
                        'modified_by'           =>  $this->session->userdata('id'),
                        'date_modified'         =>  $this->_currentDate
                    );


                    $this->app_model->insert('location_code', $locationData);

                    $locationCode_id = $this->app_model->get_locationCode_id($location_code);
                    $data = array(
                        'prospect_id'          =>  $prospect_id,
                        'tenant_id'            =>  $tenant_id,
                        'locationCode_id'      =>  $locationCode_id,
                        'store_code'           =>  $store_code,
                        'rental_type'          =>  $rental_type,
                        'contract_no'          =>  $contract_no,
                        'tin'                  =>  $tin,
                        'rent_percentage'      =>  $rent_percentage,
                        'opening_date'         =>  $opening_date,
                        'expiry_date'          =>  $expiry_date,
                        'tenant_type'          =>  $tenant_type,
                        'increment_percentage' =>  $increment_percentage,
                        'increment_frequency'  =>  $increment_frequency,
                        'sales'                =>  $sales,
                        'is_vat'               =>  $is_vat,
                        'wht'                  =>  $less_wht,
                        'vat_percentage'       =>  $vat_percentage,
                        'wht_percentage'       =>  $wht_percentage,
                        'vat_agreement'        =>  $vat_agreement,
                        'penalty_exempt'       =>  $penalty_exempt,
                        'bir_doc'              =>  $bir_doc,
                        'basic_rental'         =>  $basic_rental,
                        'tenancy_type'         =>  $tenancy_type,
                        'status'               =>  'Active',
                        'flag'                 =>  'Posted',
                        'created_at'           =>  $this->_currentDate,
                        'prepared_by'          =>  $this->session->userdata('id')

                    );


                    $this->app_model->updateTenant($tenant_id, $data);

                    $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

                    for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/contract_docs/';
                        $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $tenant_id . $timeStamp . $_FILES['contract_docs']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                            $this->app_model->insert('contract_docs', $data);
                        }
                    }

                    if ($sdiscount > 0)
                    {
                        for ($i=0; $i < count($sdiscount); $i++)
                        {
                            $discountData = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i], 'status' => 'Active');
                            $this->app_model->insert('selected_discount', $discountData);
                        }
                    }

                    if ($monthly_charges > 0)
                    {
                        for ($i=0; $i < count($monthly_charges); $i++)
                        {

                            $charges          = explode("_", $monthly_charges[$i]);
                            $charges_id       = $charges[0];
                            $unit_price       = str_replace(",", "",  $charges[1]);
                            $charges_uom      = $charges[2];
                            $selected_charges = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charges_id, 'unit_price' => $unit_price, 'uom' => $charges_uom,  'flag' => 'Active');
                            $this->app_model->insert('selected_monthly_charges', $selected_charges);
                        }
                    }


                    $this->db->trans_complete(); // End of transaction function

                    if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                    {
                        $this->db->trans_rollback(); // If failed rollback all queries
                        $error = array('action' => 'Saving Long Term Contract', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                        $this->app_model->insert('error_log', $error);
                    }
                    else
                    {
                        $this->db->query("UPDATE `prospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
                        $response['msg']          = "Success";
                        $response['tenancy_type'] = $tenancy_type;
                    }
                }
            }
            else
            {
                $response['msg'] = "Invalid Key";
            }
        }
        else
        {

            if (($rental_type == 'Percentage'  || $rental_type == 'Fixed Plus Percentage' || $rental_type == 'Fixed/Percentage w/c Higher') && $rent_percentage == '0')
            {
                $response['msg'] = "Please fill out all required fields.";
            } else {

                if ($tenant_type == 'Private Entities')
                {
                    if ($is_vat != "Added")
                    {
                        $targetPath = getcwd() . '/assets/bir/';
                        $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);
                            $bir_doc = $filename;
                        }
                    }
                }


                if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
                {

                    for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/other_img/';
                        $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $timeStamp . $_FILES['contract_docs']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                            $this->app_model->insert('tenanttype_supportingdocs', $data);
                        }
                    }

                }

                $this->db->trans_start(); // Transaction function starts here!!!


                $locationData = array(
                    'tenancy_type'          =>  $tenancy_type,
                    'slots_id'              =>  $slots_id,
                    'floor_id'              =>  $floor_location,
                    'location_code'         =>  $location_code,
                    'location_desc'         =>  $location_desc,
                    'floor_area'            =>  $floor_area,
                    'area_classification'   =>  $area_classification,
                    'area_type'             =>  $area_type,
                    'rent_period'           =>  $rent_period,
                    'rental_rate'           =>  $basic_rental,
                    'status'                =>  'Active',
                    'modified_by'           =>  $this->session->userdata('id'),
                    'date_modified'         =>  $this->_currentDate
                );


                $this->app_model->insert('location_code', $locationData);

                $locationCode_id = $this->app_model->get_locationCode_id($location_code);

                $data = array(
                    'prospect_id'          =>  $prospect_id,
                    'tenant_id'            =>  $tenant_id,
                    'locationCode_id'      =>  $locationCode_id,
                    'store_code'           =>  $store_code,
                    'rental_type'          =>  $rental_type,
                    'contract_no'          =>  $contract_no,
                    'tin'                  =>  $tin,
                    'tenant_type'          =>  $tenant_type,
                    'rent_percentage'      =>  $rent_percentage,
                    'sales'                =>  $sales,
                    'opening_date'         =>  $opening_date,
                    'expiry_date'          =>  $expiry_date,
                    'increment_percentage' =>  $increment_percentage,
                    'increment_frequency'  =>  $increment_frequency,
                    'is_vat'               =>  $is_vat,
                    'wht'                  =>  $less_wht,
                    'vat_percentage'       =>  $vat_percentage,
                    'wht_percentage'       =>  $wht_percentage,
                    'vat_agreement'        =>  $vat_agreement,
                    'penalty_exempt'       =>  $penalty_exempt,
                    'bir_doc'              =>  $bir_doc,
                    'basic_rental'         =>  $basic_rental,
                    'tenancy_type'         =>  $tenancy_type,
                    'flag'                 =>  'Posted',
                    'status'               =>  'Active'
                );


                $this->app_model->updateTenant($tenant_id, $data);

                $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

                for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
                {
                    $targetPath  = getcwd() . '/assets/contract_docs/';
                    $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                    //Make sure we have a filepath
                    if ($tmpFilePath != "")
                    {
                        //Setup our new file path
                        $filename    = $tenant_id . $timeStamp . $_FILES['contract_docs']['name'][$i];
                        $newFilePath = $targetPath . $filename;
                        //Upload the file into the temp dir
                        move_uploaded_file($tmpFilePath, $newFilePath);

                        $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                        $this->app_model->insert('contract_docs', $data);
                    }
                }



                if ($sdiscount > 0)
                {
                    for ($i=0; $i < count($sdiscount); $i++)
                    {
                        $discountData = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i]);
                        $this->app_model->insert('selected_discount', $discountData);
                    }
                }


                if ($monthly_charges > 0)
                {
                    for ($i=0; $i < count($monthly_charges); $i++)
                    {
                        $charges          = explode("_", $monthly_charges[$i]);
                        $charges_id       = $charges[0];
                        $unit_price       = str_replace(",", "",  $charges[1]);
                        $selected_charges = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charges_id, 'unit_price' => $unit_price, 'flag' => 'Active');
                        $this->app_model->insert('selected_monthly_charges', $selected_charges);
                    }
                }


                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'Saving Long Term Contract', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                }
                else
                {
                    $this->db->query("UPDATE `prospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
                    $response['msg'] = "Success";
                    $response['tenancy_type'] = $tenancy_type;
                }
            }

        }

        echo json_encode($response);

    }
}


public function pending_contract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $date                 = new DateTime();
        $timeStamp            = $date->getTimestamp();
        $response[]           = array();
        $prospect_id          = $this->sanitize($this->input->post('prospect_id'));
        $store_code           = $this->app_model->get_storeCode($prospect_id);
        $floor_location       = $this->sanitize($this->input->post('floor_location'));
        $tenant_id            = $this->sanitize($this->input->post('tenant_id'));
        $contract_no          = $this->sanitize($this->input->post('contract_no'));
        $tenancy_type         = $this->sanitize($this->input->post('tenancy_type'));
        $tin                  = $this->sanitize($this->input->post('tin'));
        $rental_type          = $this->sanitize($this->input->post('rental_type'));
        $rent_percentage      = $this->sanitize($this->input->post('rent_percentage'));
        $sales                = $this->sanitize($this->input->post('sales'));
        $location_code        = $this->sanitize($this->input->post('location_code'));
        $slots_id             = $this->sanitize($this->input->post('slots_id'));
        $floor_area           = str_replace(",", "", $this->input->post('floor_area'));
        $area_classification  = $this->sanitize($this->input->post('area_classification'));
        $area_type            = $this->sanitize($this->input->post('area_type'));
        $rent_period          = $this->sanitize($this->input->post('rent_period'));
        $location_desc        = $this->sanitize($this->input->post('location_desc'));
        $opening_date         = $this->sanitize($this->input->post('opening_date'));
        $expiry_date          = $this->sanitize($this->input->post('expiry_date'));
        $increment_percentage = $this->sanitize($this->input->post('increment_percentage'));
        $increment_frequency  = $this->sanitize($this->input->post('increment_frequency'));
        $tenant_type          = $this->input->post('tenant_type');
        $is_vat               = $this->input->post('plus_vat');
        $less_wht             = $this->input->post('less_wht');
        $vat_percentage       = $this->sanitize($this->input->post('vat_percentage'));
        $vat_agreement        = $this->sanitize($this->input->post('vat_agreement'));
        $wht_percentage       = $this->sanitize($this->input->post('wht_percentage'));
        $penalty_exempt       = $this->sanitize($this->input->post('penalty_exempt'));
        $bir_doc              = "";
        $basic_rental         = $this->sanitize($this->input->post('basic_rental'));
        $basic_rental         = str_replace(",", "", $basic_rental);
        $sdiscount            = $this->input->post('sdiscount');
        $store_name           = $this->input->post('store_name');
        $monthly_charges      = $this->input->post('monthly_charges');


        if ($is_vat == 'on') {$is_vat = 'Added';} else {$vat_percentage = 0;}
        if ($less_wht == 'on') {$less_wht = 'Added';} else {$wht_percentage = 0;}
        if ($increment_percentage == 'None') {$increment_percentage = 0;}
        if ($penalty_exempt != '1') {$penalty_exempt = 0;}


        if ($tenant_type == 'Private Entities')
        {
            if ($is_vat != "Added")
            {
                $targetPath  = getcwd() . '/assets/bir/';
                $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                //Make sure we have a filepath
                if ($tmpFilePath != "")
                {
                    //Setup our new file path
                    $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                    $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                    move_uploaded_file($tmpFilePath, $newFilePath);
                    $bir_doc = $filename;
                }
            }
        }

        if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
        {

            for ($i=0; $i < count($_FILES["supporting_doc"]['name']); $i++)
            {
                $targetPath  = getcwd() . '/assets/other_img/';
                $tmpFilePath = $_FILES['supporting_doc']['tmp_name'][$i];
                //Make sure we have a filepath
                if ($tmpFilePath != "")
                {
                    //Setup our new file path
                    $filename    = $timeStamp . $_FILES['supporting_doc']['name'][$i];
                    $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                    move_uploaded_file($tmpFilePath, $newFilePath);

                    $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                    $this->app_model->insert('tenanttype_supportingdocs', $data);
                }
            }
        }

        $this->db->trans_start(); // Transaction function starts here!!!

        $locationData = array(
            'tenancy_type'          =>  $tenancy_type,
            'slots_id'              =>  $slots_id,
            'floor_id'              =>  $floor_location,
            'location_code'         =>  $location_code,
            'location_desc'         =>  $location_desc,
            'floor_area'            =>  $floor_area,
            'area_classification'   =>  $area_classification,
            'area_type'             =>  $area_type,
            'rent_period'           =>  $rent_period,
            'rental_rate'           =>  $basic_rental,
            'status'                =>  'Active',
            'modified_by'           =>  $this->session->userdata('id'),
            'date_modified'         =>  $this->_currentDate
        );

        $this->app_model->insert('location_code', $locationData);
        $locationCode_id = $this->app_model->get_locationCode_id($location_code);

        $data = array(
            'prospect_id'          =>  $prospect_id,
            'tenant_id'            =>  $tenant_id,
            'locationCode_id'      =>  $locationCode_id,
            'store_code'           =>  $store_code,
            'rental_type'          =>  $rental_type,
            'contract_no'          =>  $contract_no,
            'tin'                  =>  $tin,
            'rent_percentage'      =>  $rent_percentage,
            'opening_date'         =>  $opening_date,
            'expiry_date'          =>  $expiry_date,
            'tenant_type'          =>  $tenant_type,
            'sales'                =>  $sales,
            'increment_percentage' =>  $increment_percentage,
            'increment_frequency'  =>  $increment_frequency,
            'is_vat'               =>  $is_vat,
            'wht'                  =>  $less_wht,
            'vat_percentage'       =>  $vat_percentage,
            'wht_percentage'       =>  $wht_percentage,
            'vat_agreement'        =>  $vat_agreement,
            'penalty_exempt'       =>  $penalty_exempt,
            'bir_doc'              =>  $bir_doc,
            'basic_rental'         =>  $basic_rental,
            'tenancy_type'         =>  $tenancy_type,
            'status'               =>  'Active',
            'flag'                 =>  'Pending',
            'created_at'           =>  $this->_currentDate,
            'prepared_by'          =>  $this->session->userdata('id')

        );


        $this->app_model->updateTenant($tenant_id, $data);
        $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

        for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
        {
            $targetPath  = getcwd() . '/assets/contract_docs/';
            $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
            //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $tenant_id . $timeStamp . $_FILES['contract_docs']['name'][$i];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                $this->app_model->insert('contract_docs', $data);
            }
        }

        if ($sdiscount > 0)
        {
            for ($i=0; $i < count($sdiscount); $i++)
            {
                $discountData = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i]);
                $this->app_model->insert('selected_discount', $discountData);
            }
        }

        if ($monthly_charges > 0)
        {
            for ($i=0; $i < count($monthly_charges); $i++)
            {
                $charges          = explode("_", $monthly_charges[$i]);
                $charges_id       = $charges[0];
                $unit_price       = str_replace(",", "",  $charges[1]);
                $charges_uom      = $charges[2];
                $selected_charges = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charges_id, 'unit_price' => $unit_price, 'uom' => $charges_uom,  'flag' => 'Active');
                $this->app_model->insert('selected_monthly_charges', $selected_charges);
            }
        }


        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed, rollback all queries
            $error = array('action' => 'Saving Long Term Contract', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
        }
        else
        {
            $this->db->query("UPDATE `prospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
            $response['msg'] = "Success";
            $response['tenancy_type'] = $tenancy_type;
        }

        echo json_encode($response);
    }
}


public function tenant_amendment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['id']      = $this->uri->segment(3);
        $data['details'] = $this->app_model->tenant_details($data['id']);
        if ($this->session->userdata('user_type') == 'Administrator' || $this->session->userdata('user_type') == 'Corporate Documentation Officer' || $this->session->userdata('user_type') == 'Corporate Leasing Supervisor')
        {
            $stores = $this->app_model->get_stores();
            $data['floors'] = $this->app_model->floors_tenantStoreLocation($data['id']);
        }
        else
        {
            $stores         = $this->app_model->my_store();
            $data['floors'] = $this->app_model->store_floors();
        }
        $tenancy_type           = $this->app_model->get_tenancy_type($data['id']);
        $data['classification'] = $this->app_model->getAll('area_classification');
        $data['area_type']      = $this->app_model->getAll('area_type');
        $data['rent_period']    = $this->app_model->get_rentPeriod($tenancy_type);

        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/tenant_amendment');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function stenant_amendment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['tenant_id'] = $this->uri->segment(3);

        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['tenant_id'] = $this->uri->segment(3);

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager")
            {

                $username   = $this->sanitize($this->input->post('username'));
                $password   = $this->sanitize($this->input->post('password'));
                $store_name = $this->app_model->my_store();

                if ($this->app_model->managers_key($username, $password, $store_name))
                {
                    $this->load->view('leasing/header', $data);
                    $this->load->view('leasing/stenant_amendment');
                    $this->load->view('leasing/footer');
                } else {
                    $this->session->set_flashdata('message', 'Invalid Key');
                    redirect('Leasing_transaction/lst_Stenants');
                }

            }
            else
            {
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/stenant_amendment');
                $this->load->view('leasing/footer');
            }
        } else {
            redirect('ctrl_leasing/');
        }
    }
}

public function amend_tenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        $id                   = $this->sanitize($this->input->post('id'));
        $tenant_id            = $this->sanitize($this->input->post('tenant_id'));
        $prospect_id          = $this->sanitize($this->input->post('prospect_id'));
        $tenancy_type         = $this->sanitize($this->input->post('tenancy_type'));
        $contract_no          = $this->sanitize($this->input->post('contract_no'));
        $rental_type          = $this->sanitize($this->input->post('rental_type'));
        $store_name           = $this->sanitize($this->input->post('store_name'));
        $floor_name           = $this->sanitize($this->input->post('floor_name'));
        $floor_id             = $this->app_model->get_floorID($store_name, $floor_name);
        $location_code        = $this->sanitize($this->input->post('location_code'));
        $location_desc        = $this->sanitize($this->input->post('location_desc'));
        $rent_percentage      = $this->sanitize($this->input->post('rent_percentage'));
        $rent_percentage      = str_replace(",", "", $rent_percentage);
        $increment_percentage = $this->sanitize($this->input->post('increment_percentage'));
        $increment_frequency  = $this->sanitize($this->input->post('increment_frequency'));
        $tin                  = $this->sanitize($this->input->post('tin'));
        $tenant_type          = $this->sanitize($this->input->post('tenant_type'));
        $opening_date         = $this->sanitize($this->input->post('opening_date'));
        $expiry_date          = $this->sanitize($this->input->post('expiry_date'));
        $plus_vat             = $this->input->post('plus_vat');
        $less_wht             = $this->input->post('less_wht');
        $sales                = $this->sanitize($this->input->post('sales'));
        $vat_percentage       = $this->sanitize($this->input->post('vat_percentage'));
        $wht_percentage       = $this->sanitize($this->input->post('wht_percentage'));
        $vat_agreement        = $this->sanitize($this->input->post('vat_agreement'));
        $bir_doc              = "";
        $penalty_exempt       = $this->input->post('penalty_exempt');
        $basic_rental         = $this->sanitize($this->input->post('basic_rental'));
        $location_code        = $this->sanitize($this->input->post('location_code'));
        $slots_id             = $this->sanitize($this->input->post('slots_id'));
        $floor_area           = str_replace(",", "", $this->input->post('floor_area'));
        $area_classification  = $this->sanitize($this->input->post('area_classification'));
        $area_type            = $this->sanitize($this->input->post('area_type'));
        $rent_period          = $this->sanitize($this->input->post('rent_period'));

        $basic_rental         = str_replace(",", "", $basic_rental);
        $scharges             = $this->input->post('scharges');
        $sdiscount            = $this->input->post('sdiscount');
        $contract_docs        = $this->input->post('contract_docs');

        $date                 = new DateTime();
        $timeStamp            = $date->getTimestamp();
        $response[]           = array();


        if ($plus_vat == 'on') {$plus_vat = 'Added';} else {$vat_percentage = 0;}
        if ($less_wht == 'on') {$less_wht = 'Added';} else {$wht_percentage = 0;}
        if ($increment_percentage == 'None') {$increment_percentage = 0;}
        if ($penalty_exempt != '1') {$penalty_exempt = 0;}

        $username = str_replace("%20", " ", $this->uri->segment(3));
        $password = str_replace("%20", " ", $this->uri->segment(4));


        if (count($_FILES['contract_docs']['name']) <= 0)
        {
            $response['msg'] = "Required";
        }
        else
        {
            $this->db->trans_start(); // Transaction function starts here
            $this->app_model->disable_locationCode($id);
            $store_code = $this->app_model->get_storeCode($prospect_id);

            if ($tenancy_type == 'Private Entities')
            {
                if ($plus_vat != "Added")
                {
                    $targetPath  = getcwd() . '/assets/bir/';
                    $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                    //Make sure we have a filepath
                    if ($tmpFilePath != "")
                    {
                        //Setup our new file path
                        $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                        $newFilePath = $targetPath . $filename;
                        //Upload the file into the temp dir
                        move_uploaded_file($tmpFilePath, $newFilePath);
                        $bir_doc = $filename;
                    }

                    $plus_vat = ""; // Update table that VAT is not added
                }
            }

            if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
            {
                for ($i=0; $i < count($_FILES["supporting_doc"]['name']); $i++)
                {
                    $targetPath  = getcwd() . '/assets/other_img/';
                    $tmpFilePath = $_FILES['supporting_doc']['tmp_name'][$i];
                    //Make sure we have a filepath
                    if ($tmpFilePath != "")
                    {
                        //Setup our new file path
                        $filename    = $timeStamp . $_FILES['supporting_doc']['name'][$i];
                        $newFilePath = $targetPath . $filename;
                        //Upload the file into the temp dir
                        move_uploaded_file($tmpFilePath, $newFilePath);

                        $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                        $this->app_model->insert('tenanttype_supportingdocs', $data);
                    }
                }
            }

            $this->app_model->change_tenantStatus($id);
            $this->app_model->change_statusAttachment('selected_discount', $id);
            // $this->app_model->change_statusAttachment('contract_docs', $id);
            $this->app_model->change_selectedCharges($tenant_id);

            $locationData = array(
                'tenancy_type'          =>  $tenancy_type,
                'slots_id'              =>  $slots_id,
                'floor_id'              =>  $floor_id,
                'location_code'         =>  $location_code,
                'location_desc'         =>  $location_desc,
                'floor_area'            =>  $floor_area,
                'area_classification'   =>  $area_classification,
                'area_type'             =>  $area_type,
                'rent_period'           =>  $rent_period,
                'rental_rate'           =>  $basic_rental,
                'status'                =>  'Active',
                'modified_by'           =>  $this->session->userdata('id'),
                'date_modified'         =>  $this->_currentDate
            );

            $this->app_model->insert('location_code', $locationData);

            $locationCode_id = $this->app_model->get_locationCode_id($location_code);
            $data = array(
                'prospect_id'           =>  $prospect_id,
                'tenant_id'             =>  $tenant_id,
                'locationCode_id'       =>  $locationCode_id,
                'store_code'            =>  $store_code,
                'sales'                 =>  $sales,
                'tenancy_type'          =>  $tenancy_type,
                'contract_no'           =>  $contract_no,
                'tin'                   =>  $tin,
                'rental_type'           =>  $rental_type,
                'rent_percentage'       =>  $rent_percentage,
                'increment_frequency'   =>  $increment_frequency,
                'increment_percentage'  =>  $increment_percentage,
                'opening_date'          =>  $opening_date,
                'expiry_date'           =>  $expiry_date,
                'tenant_type'           =>  $tenant_type,
                'vat_percentage'        =>  $vat_percentage,
                'vat_agreement'         =>  $vat_agreement,
                'wht_percentage'        =>  $wht_percentage,
                'penalty_exempt'        =>  $penalty_exempt,
                'is_vat'                =>  $plus_vat,
                'wht'                   =>  $less_wht,
                'bir_doc'               =>  $bir_doc,
                'basic_rental'          =>  $basic_rental,
                'flag'                  =>  'Posted',
                'status'                =>  'Active',
                'created_at'            =>  $this->_currentDate,
                'prepared_by'           =>  $this->session->userdata('id')
            );

            $this->app_model->insert('tenants', $data);
            $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

            for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
            {
                $targetPath  = getcwd() . '/assets/contract_docs/';
                $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                //Make sure we have a filepath
                if ($tmpFilePath != "")
                {
                    //Setup our new file path
                    $filename    = $timeStamp . $_FILES['contract_docs']['name'][$i];
                    $newFilePath = $targetPath . $filename;
                    //Upload the file into the temp dir
                    move_uploaded_file($tmpFilePath, $newFilePath);

                    $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                    $this->app_model->insert('contract_docs', $data);
                }
            }




            if ($sdiscount > 0)
            {
                $discounts_array = array();
                for ($i=0; $i < count($sdiscount); $i++)
                {
                    $discounts_array = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i]);
                    $this->app_model->insert('selected_discount', $discounts_array);
                }
            }

            if ($scharges > 0)
            {
                $charges_array = array();
                for ($i=0; $i < count($scharges); $i++)
                {
                    $selectedCharges = explode("_", $scharges[$i]);
                    $charge_id       = $selectedCharges[0];
                    $unit_price      = $selectedCharges[1];
                    $charges_uom     = $selectedCharges[2];
                    $charges_array   = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charge_id, 'unit_price' => $unit_price, 'uom' => $charges_uom, 'flag' => 'Active');
                    $this->app_model->insert('selected_monthly_charges', $charges_array);
                }
            }

            $this->db->trans_complete(); // End of transaction function
            if ($this->db->trans_status() === FALSE) // Check if transaction is failed or succeed
            {
                $response['msg'] = "Error";
            }
            else
            {
                $response['msg']          = "Success";
                $response['tenancy_type'] = $tenancy_type;
            }

        }

        echo json_encode($response);
    }
}

public function add_stenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $date         = new DateTime();
        $timeStamp    = $date->getTimestamp();
        $response[]   = array();

        $prospect_id  = $this->sanitize($this->input->post('prospect_id'));
        $store_code   = $this->app_model->get_storeCode2($prospect_id);
        $tenant_id    = $this->sanitize($this->input->post('tenant_id'));
        $contract_no  = $this->sanitize($this->input->post('contract_no'));
        $sdiscount    = $this->input->post('sdiscount');
        $tin          = $this->sanitize($this->input->post('tin'));
        $opening_date = $this->sanitize($this->input->post('opening_date'));
        $expiry_date  = $this->sanitize($this->input->post('expiry_date'));
        $is_vat       = $this->input->post('plus_vat');
        $price_persq  = $this->sanitize($this->input->post('price_persq'));
        $price_persq  = str_replace(",", "", $price_persq);
        $floor_area   = $this->sanitize($this->input->post('floor_area'));
        $floor_area   = str_replace(",", "", $floor_area);
        $bir_doc      = "";

        $actual_balance = str_replace(",", "", $this->input->post('actual_balance'));

        if ($is_vat != "Added")
        {
            $targetPath  = getcwd() . '/assets/bir/';
            $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
            //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $bir_doc = $filename;
            }
        }


        $data = array(
            'prospect_id'       =>  $prospect_id,
            'tenant_id'         =>  $tenant_id,
            'store_code'        =>  $store_code,
            'tin'               =>  $tin,
            'contract_no'       =>  $contract_no,
            'opening_date'      =>  $opening_date,
            'expiry_date'       =>  $expiry_date,
            'is_vat'            =>  $is_vat,
            'bir_doc'           =>  $bir_doc,
            'price_persq'       =>  $price_persq,
            'floor_area'        =>  $floor_area,
            'actual_balance'    =>  $actual_balance,
            'flag'              =>  'Short Term',
            'status'            =>  'Active'
        );

        $this->db->trans_start(); // Transaction function starts here
        $this->app_model->insert('ltenant', $data);

        for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
        {
            $targetPath  = getcwd() . '/assets/contract_docs/';
            $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
            //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['contract_docs']['name'][$i];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => 'Short Term');
                $this->app_model->insert('contract_docs', $data);
            }
        }

        if ($sdiscount > 0) // Inserting Selected Discounts to `selected_discount` table
        {
            $discounts_array = array();
            for ($i=0; $i < count($sdiscount); $i++)
            {
                $discounts_array = array('tenant_id' => $tenant_id, 'discount_id' => $sdiscount[$i]);
                $this->app_model->insert('selected_discount', $discounts_array);
            }
        }

        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if transaction is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'Saving Short Term Contract', 'error_msg' => $this->db->_error_message()); // Log error to `error_log` table
            $this->app_model->insert('error_log', $error);
        } else {
            $this->db->query("UPDATE `sprospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
            $response['msg'] = "Success";
        }
        echo json_encode($response);
    }
}


public function amend_stenant()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id     = $this->sanitize($this->input->post('tenant_id'));
        $contract_no   = $this->sanitize($this->input->post('contract_no'));
        $price_persq   = $this->sanitize($this->input->post('price_persq'));
        $price_persq   = str_replace(",", "", $price_persq);
        $floor_area    = $this->sanitize($this->input->post('floor_area'));
        $floor_area    = str_replace(",", "", $floor_area);
        $opening_date  = $this->sanitize($this->input->post('opening_date'));
        $expiry_date   = $this->sanitize($this->input->post('expiry_date'));
        $basic_rental  = $this->sanitize($this->input->post('basic_rental'));
        $basic_rental  = str_replace(",", "", $basic_rental);
        $plus_vat      = $this->input->post('plus_vat');
        $sdiscount     = $this->input->post('sdiscount');
        $reason        = $this->sanitize($this->input->post('reason'));
        $date_modified = date('Y-m-d');
        $bir_doc       = "";

        $date       = new DateTime();
        $timeStamp  = $date->getTimestamp();
        $response[] = array();

        if ($plus_vat != "Added")
        {
            $targetPath  = getcwd() . '/assets/bir/';
            $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
            //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);
                $bir_doc = $filename;
            }

            $plus_vat = ""; // Update table that VAT is not added
        }

        ////////// Delete the old contract documents ////////

        $this->app_model->delete_oldContractDocs($tenant_id);

        ///////// Add amended contract documents/////////////

        for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
        {
            $targetPath  = getcwd() . '/assets/contract_docs/';
            $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
            //Make sure we have a filepath
            if ($tmpFilePath != "")
            {
                //Setup our new file path
                $filename    = $timeStamp . $_FILES['contract_docs']['name'][$i];
                $newFilePath = $targetPath . $filename;
                //Upload the file into the temp dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => 'Short Term');
                $this->app_model->insert('contract_docs', $data);
            }
        }

        // //////////////////////////// Discounts //////////////////////////

        // Clear all discounts first
        $this->app_model->delete_selectedDiscounts($tenant_id);
        if ($sdiscount > 0)
        {
            $discounts_array = array();
            for ($i=0; $i < count($sdiscount); $i++)
            {
                $discounts_array = array('tenant_id' => $tenant_id, 'discount_id' => $sdiscount[$i]);
                $this->app_model->insert('selected_discount', $discounts_array);
            }
        }

        $amendment_data = array(
            'price_persq'       =>  $price_persq,
            'floor_area'        =>  $floor_area,
            'expiry_date'       =>  $expiry_date,
            'is_vat'            =>  $plus_vat,
            'bir_doc'           =>  $bir_doc,
            'actual_balance'    =>  $basic_rental
        );

        $this->app_model->update_tenant($amendment_data, $tenant_id);


        $amendment_history = array(
            'tenant_id'     =>  $tenant_id,
            'contract_no'   =>  $contract_no,
            'reason'        =>  $reason,
            'date_modified' =>  $date_modified,
            'modified_by'   =>  $this->session->userdata('id'),
            'flag'          =>  'Short Term'
        );
        $this->app_model->insert('contract_amendment', $amendment_history);

        $response['msg'] = 'Updated';
        echo json_encode($response);


    } else {
        redirect('ctrl_leasing');
    }
}

public function lst_Ltenants()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['leasee_types']   = $this->app_model->getAll('leasee_type');
        $data['category_one']   = $this->app_model->getAll('category_one');
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_Ltenants');
        $this->load->view('leasing/footer');
    } else {
        redirect('ctrl_leasing');
    }
}


public function update_primaryDetails()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $prospect_id     = $this->uri->segment(3);
        $tenancy_type    = str_replace("%20", " ", $this->uri->segment(4));
        $trade_name      = $this->sanitize($this->input->post('trade_name'));
        $corp_name       = $this->sanitize($this->input->post('corp_name'));
        $address         = $this->sanitize($this->input->post('address'));
        $lessee_type     = $this->sanitize($this->input->post('lessee_type'));
        $first_category  = $this->sanitize($this->input->post('first_category'));
        $second_category = $this->sanitize($this->input->post('second_category'));
        $third_category  = $this->sanitize($this->input->post('third_category'));
        $contact_person1 = $this->sanitize($this->input->post('contact_person1'));
        $contact_number1 = $this->sanitize($this->input->post('contact_number1'));


        $lesseeType_id = $this->app_model->select_id('leasee_type', 'leasee_type', $lessee_type);

        $data = array(
            'trade_name'        =>  $trade_name,
            'corporate_name'    =>  $corp_name,
            'address'           =>  $address,
            'lesseeType_id'     =>  $lesseeType_id,
            'first_category'    =>  $first_category,
            'second_category'   =>  $second_category,
            'third_category'    =>  $third_category,
            'contact_person'    =>  $contact_person1,
            'contact_number'    =>  $contact_number1
        );


        if ($this->app_model->update($data, $prospect_id, 'prospect'))
        {
            $this->session->set_flashdata('message', 'Saved');
        }
        $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
        redirect($prev_url);

    } else {
        redirect('ctrl_leasing');
    }
}


public function tenant_details()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->tenant_details($tenant_id);
        echo json_encode($result);
    }
}

public function terms_amendment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->terms_amendment($tenant_id);
        echo json_encode($result);
    }
}

public function sterms_amendment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->sterms_amendment($tenant_id);
        echo json_encode($result);
    }
}

public function tenant_terms()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id     = $this->uri->segment(3);
        $result = $this->app_model->tenant_terms($id);
        echo json_encode($result);
    }
}


public function get_discounts()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_discounts($tenant_id);
        echo json_encode($result);
    }
}

public function stenant_terms()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->stenant_terms($tenant_id);
        echo json_encode($result);
    }
}

public function get_prospectID()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_prospectID($tenant_id);
        echo json_encode($result);
    }
}


public function lst_Stenants()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['leasee_types']   = $this->app_model->getAll('leasee_type');
        $data['category_one']   = $this->app_model->getAll('category_one');
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/lst_Stenants');
        $this->load->view('leasing/footer');
    } else {
        redirect('ctrl_leasing');
    }
}


public function get_Ltenants()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_Ltenants();
        echo json_encode($result);
    }
}

public function pending_lcontracts()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->pending_lcontracts();
        echo json_encode($result);
    }
}


public function pending_scontracts()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->pending_scontracts();
        echo json_encode($result);
    }
}


public function get_Stenants()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_Stenants();
        echo json_encode($result);
    }
}


public function test()
{
    echo $this->uri->segment(3);
}



public function post_lpendingContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id       = $this->uri->segment(3);
        $username = $this->input->post('username');
        $password = $this->input->post('password');


        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            $store_name = $this->app_model->tenant_store($id);
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $data = array('flag' => 'Posted');
                $this->app_model->update($data, $id, 'tenants');

                $this->session->set_flashdata('message', 'Posted');
                redirect('leasing_transaction/lst_lpendingContract');

            } else {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('leasing_transaction/lst_lpendingContract');
            }
        } else {
            $data = array('flag' => 'Posted');
            $this->app_model->update($data, $id, 'tenants');
            $this->session->set_flashdata('message', 'Posted');
            redirect('leasing_transaction/lst_lpendingContract');
        }
    }
}


public function post_spendingContract()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $id       = $this->uri->segment(3);
        $username = $this->input->post('username');
        $password = $this->input->post('password');


        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
        {
            $store_name = $this->app_model->tenant_store($id);
            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $data = array('flag' => 'Posted');
                $this->app_model->update($data, $id, 'tenants');

                $this->session->set_flashdata('message', 'Posted');
                redirect('leasing_transaction/lst_spendingContract');

            } else {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('leasing_transaction/lst_spendingContract');
            }
        } else {
            $data = array('flag' => 'Posted');
            $this->app_model->update($data, $id, 'tenants');
            $this->session->set_flashdata('message', 'Posted');
            redirect('leasing_transaction/lst_spendingContract');
        }
    }

}

public function invoicing()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_type') != 'Administrator')
        {
            $data['user_group'] = $this->app_model->my_store();
        } else {
            $data['stores'] = $this->app_model->get_stores();
        }
        $data['rental_increment'] = $this->app_model->get_rentalIncrement();
        $data['current_date']     = $this->_currentDate;
        $data['doc_no']           = $this->app_model->get_docNo(false);
        //$data['tenant_id']      = $this->app_model->get_tenantID();
        $data['flashdata']        = $this->session->flashdata('message');
        $data['expiry_tenants']   = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/invoicing');
        $this->load->view('leasing/footer');

    } else {
        redirect('ctrl_leasing');
    }
}


public function draft_invoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_type') != 'Documentation Officer')
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/draft_invoice');
            $this->load->view('leasing/footer');

        } else {
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $this->session->set_flashdata('message', 'Restriction');
            redirect($prev_url);
        }

    } else {
        redirect('ctrl_leasing');
    }
}

public function posted_invoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_type') != 'Documentation Officer')
        {
            $data['flashdata'] = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/posted_invoice');
            $this->load->view('leasing/footer');

        } else {
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $this->session->set_flashdata('message', 'Restriction');
            redirect($prev_url);
        }

    } else {
        redirect('ctrl_leasing');
    }
}


public function get_postedInvoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_postedInvoice();
        echo json_encode($result);
    }
}


public function get_draftInvoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_draftInvoice();
        echo json_encode($result);
    }
}

// public function delete_invoiceCharge()
// {
//     if ($this->session->userdata('leasing_logged_in'))
//     {
//         $id = $this->uri->segment(3);
//         $result = array();
//         if ($this->app_model->delete('invoicing', 'id', $id)) {
//             $result['msg'] = 'Deleted';
//         } else {
//             $result['msg'] = 'Not Deleted';
//         }

//         echo json_encode($result);
//     }
// }

public function update_invoicePage()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['tenant_id']         = $this->uri->segment(3);
        $data['doc_no']            = $this->uri->segment(4);
        $data['expiry_tenants']    = $this->app_model->get_expiryTenants();
        // $data['primaryDetails'] = $this->app_model->invoice_primaryDetails($tenant_id, $doc_no);

        $data['flashdata']         = $this->session->flashdata('message');
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/update_invoicePage');
        $this->load->view('leasing/footer');

    } else {
        redirect('ctrl_leasing');
    }
}

public function invoice_primaryDetails()
{
    $tenant_id = $this->uri->segment(3);
    $doc_no    = $this->uri->segment(4);
    $result    = $this->app_model->invoice_primaryDetails($tenant_id, $doc_no);
    echo json_encode($result);
}

public function draft_invoiceCharges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $doc_no    = $this->uri->segment(4);
        $result    = $this->app_model->draft_invoiceCharges($tenant_id, $doc_no);
        echo json_encode($result);
    }
}

public function delete_invoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $doc_no    = $this->uri->segment(4);

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {

            $username   = $this->sanitize($this->input->post('username'));
            $password   = $this->sanitize($this->input->post('password'));
            $store_name = $this->app_model->my_store();

            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $this->app_model->delete_oldInvoice($tenant_id, $doc_no);

                $this->session->set_flashdata('message', 'Deleted');
                redirect('Leasing_transaction/draft_invoice');

            } else {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/draft_invoice');
            }

        } else {
            $this->app_model->delete_oldInvoice($tenant_id, $doc_no);
            $this->session->set_flashdata('message', 'Deleted');
            redirect('Leasing_transaction/draft_invoice');
        }
    }
}


public function delete_PostedInvoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $doc_no    = $this->uri->segment(4);

        if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
        {

            $username   = $this->sanitize($this->input->post('username'));
            $password   = $this->sanitize($this->input->post('password'));
            $store_name = $this->app_model->my_store();

            if ($this->app_model->managers_key($username, $password, $store_name))
            {
                $this->app_model->delete_oldInvoice($tenant_id, $doc_no);
                $this->session->set_flashdata('message', 'Deleted');
                redirect('Leasing_transaction/credit_memo');

            } else {
                $this->session->set_flashdata('message', 'Invalid Key');
                redirect('Leasing_transaction/credit_memo');
            }

        } else {
                $this->app_model->delete_oldInvoice($tenant_id, $doc_no);
                $this->session->set_flashdata('message', 'Deleted');
                redirect('Leasing_transaction/draft_invoice');
        }
    }
}

public function save_draftInvoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tag = $this->uri->segment(3);
        $this->db->trans_start(); // Transaction function starts here!!!

        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();
        $doc_no           = $this->input->post('doc_no');
        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $posting_date     = $this->input->post('posting_date');
        $transaction_date = $this->sanitize($this->input->post('transaction_date'));
        $due_date         = $this->sanitize($this->input->post('due_date'));
        $prev_reading     = $this->input->post('prev_reading');
        $curr_reading     = $this->input->post('curr_reading');
        $total            = $this->sanitize($this->input->post('total'));
        $charges_type     = $this->input->post('charges_type');
        $charges_code     = $this->input->post('charges_code');
        $description      = $this->input->post('description');
        $uom              = $this->input->post('uom');
        $unit_price       = $this->input->post('unit_price');
        $total_unit       = $this->input->post('total_unit');
        $actual_amount    = $this->input->post('actual_amount');
        $rental_type      = $this->input->post('rental_type');
        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $gl_refno         = $this->app_model->gl_refNo();
        $response         = array();
        $data             = array();
        $rent_income;

        if (!$actual_amount)
        {
            $response['msg'] = 'No Charges Added';
        } else {

            //======== Delete old data before inserting new Invoice charges =======//
            $store_code = $this->app_model->tenant_storeCode($tenant_id);
            $this->app_model->delete_oldInvoice($tenant_id, $doc_no);
            $this->app_model->delete_oldPreop($tenant_id, $doc_no);

            //==================== Inserting new Invoice charges =================//

            for ($i=0; $i < count($actual_amount); $i++)
            {
                if ($this->sanitize($charges_type[$i]) == 'Basic/Monthly Rental')
                {
                    $data = array(
                        'tenant_id'     =>  $tenant_id,
                        'trade_name'    =>  $trade_name,
                        'doc_no'        =>  $doc_no,
                        'posting_date'  =>  $posting_date,
                        'transaction_date' => $transaction_date,
                        'due_date'      =>  $due_date,
                        'store_code'    =>  $store_code,
                        'contract_no'   =>  $contract_no,
                        'charges_type'  =>  $charges_type[$i],
                        'description'   =>  $description[$i],
                        'uom'           =>  $uom[$i],
                        'unit_price'    =>  str_replace(",", "", $unit_price[$i]),
                        'total_unit'    =>  str_replace(",", "", $total_unit[$i]),
                        'expected_amt'  =>  str_replace(",", "", $actual_amount[$i]),
                        'actual_amt'    =>  str_replace(",", "", $total),
                        'balance'       =>  str_replace(",", "", $total),
                        'tag'           =>  $tag
                    );


                    if ($tag == 'Posted')
                    {
                        $dataLedger = array(
                            'posting_date'      =>  $posting_date,
                            'transaction_date'  =>  $transaction_date,
                            'document_type'     =>  'Invoice',
                            'ref_no'            =>  $this->app_model->generate_refNo(),
                            'doc_no'            =>  $doc_no,
                            'tenant_id'         =>  $tenant_id,
                            'contract_no'       =>  $contract_no,
                            'description'       =>  'Basic-' . $trade_name,
                            'credit'            =>  str_replace(",", "", $total),
                            'debit'             =>  0,
                            'balance'           =>  -1 * str_replace(",", "", $total),
                            'due_date'          =>  $due_date,
                            'charges_type'      =>  'Basic'
                        );

                        $this->app_model->insert('ledger', $dataLedger);

                        $rent_income = str_replace(",", "", $actual_amount[$i]);

                        $rent_receivable = array(
                            'posting_date'      =>  $posting_date,
                            'transaction_date' => $transaction_date,
                            'document_type'     =>  'Invoice',
                            'ref_no'            =>  $gl_refno,
                            'doc_no'            =>  $doc_no,
                            'due_date'          =>  $due_date,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'debit'             =>  str_replace(",", "", $total),
                            'tag'               =>  'Basic Rent',
                            'prepared_by'       =>  $this->session->userdata('id')
                        );

                        $this->app_model->insert('general_ledger', $rent_receivable);
                        $this->app_model->insert('subsidiary_ledger', $rent_receivable);


                        // For Montly Receivable Report
                        $reportData = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $doc_no,
                            'posting_date'  =>  $posting_date,
                            'description'   =>  'Net Rental',
                            'amount'        =>  str_replace(",", "", $total)
                        );

                        $this->app_model->insert('monthly_receivable_report', $reportData);
                    }
                }
                elseif ($this->sanitize($charges_type[$i]) == 'Other' || $this->sanitize($charges_type[$i]) == 'Construction Materials')
                {
                    $data = array(
                        'tenant_id'        =>   $tenant_id,
                        'trade_name'       =>   $trade_name,
                        'doc_no'           =>   $doc_no,
                        'posting_date'     =>   $posting_date,
                        'transaction_date' => $transaction_date,
                        'due_date'         =>   $due_date,
                        'store_code'       =>   $store_code,
                        'contract_no'      =>   $contract_no,
                        'charges_type'     =>   $charges_type[$i],
                        'charges_code'     =>   $charges_code[$i],
                        'description'      =>   $description[$i],
                        'uom'              =>   $uom[$i],
                        'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                        'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                        'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                        'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                        'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                        'tag'              =>   $tag
                    );

                    if ($tag == 'Posted')
                    {

                        $amount = str_replace(",", "", $actual_amount[$i]);

                        if ($i == 0)
                        {
                            $ar_code = '10.10.01.03.03';

                            if ($this->app_model->is_AGCSubsidiary($tenant_id)) {
                                $ar_code = '10.10.01.03.04';
                            }
                            $account_receivable = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'due_date'          =>  $due_date,
                                'ref_no'            =>  $gl_refno,
                                'doc_no'            =>  $doc_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  str_replace(",", "", $total),
                                'tag'               =>  'Other',
                                'prepared_by'       =>  $this->session->userdata('id')
                            );

                            $this->app_model->insert('general_ledger', $account_receivable);
                            $this->app_model->insert('subsidiary_ledger', $account_receivable);
                        }

                        $gl_code;
                        if ($description[$i] == 'Expanded Withholding Tax')
                        {
                            $gl_code = "10.10.01.06.05";
                            $gl_entry = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refno,
                                'doc_no'            =>  $doc_no,
                                'due_date'          =>  $due_date,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  ABS($amount),
                                'tag'               =>  'Expanded',
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $gl_entry);
                            $this->app_model->insert('subsidiary_ledger', $gl_entry);

                            $dataLedger = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  => $transaction_date,
                                'document_type'     =>  'Invoice',
                                'doc_no'            =>  $doc_no,
                                'ref_no'            =>  $this->app_model->generate_refNo(),
                                'tenant_id'         =>  $tenant_id,
                                'contract_no'       =>  $contract_no,
                                'description'       =>  'Other-' . $trade_name . '-' . $description[$i],
                                'credit'            =>  0,
                                'debit'             =>  ABS(str_replace(",", "", $actual_amount[$i])),
                                'balance'           =>  ABS(str_replace(",", "", $actual_amount[$i])),
                                'due_date'          =>  $due_date,
                                'flag'              =>  'EWT',
                                'charges_type'      =>  'Other'
                            );

                            $this->app_model->insert('ledger', $dataLedger);

                        }
                        else
                        {
                            if ($description[$i] == 'Common Usage Charges')
                            {
                                $gl_code = '20.80.01.08.03';
                            }
                            elseif ($description[$i] == 'Electricity')
                            {
                                $gl_code = '20.80.01.08.02';
                            }
                            elseif ($description[$i] == 'Aircon')
                            {
                                $gl_code = '20.80.01.08.04';
                            }
                            elseif ($description[$i] == 'Late submission of Deposit Slip' || $description[$i] == 'Late Payment Penalty' || $description[$i] == 'Penalty')
                            {
                                $gl_code = '20.80.01.08.01';
                            }
                            elseif ($description[$i] == 'Chilled Water')
                            {
                                $gl_code = '20.80.01.08.05';
                            }
                            elseif ($description[$i] == 'Water')
                            {
                                $gl_code = '20.80.01.08.08';
                            }
                            else
                            {
                                $gl_code = '20.80.01.08.07';
                            }

                            $amount = -1 * $amount;
                            $gl_entry = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  => $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refno,
                                'due_date'          =>  $due_date,
                                'doc_no'            =>  $doc_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'             =>  $amount,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $gl_entry);
                            $this->app_model->insert('subsidiary_ledger', $gl_entry);

                            $dataLedger = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'doc_no'            =>  $doc_no,
                                'ref_no'            =>  $this->app_model->generate_refNo(),
                                'tenant_id'         =>  $tenant_id,
                                'contract_no'       =>  $contract_no,
                                'description'       =>  'Other-' . $trade_name . '-' . $description[$i],
                                'credit'            =>  str_replace(",", "", $actual_amount[$i]),
                                'debit'             =>  0,
                                'balance'           =>  -1 * str_replace(",", "", $actual_amount[$i]),
                                'due_date'          =>  $due_date,
                                'charges_type'      =>  'Other'
                            );

                            $this->app_model->insert('ledger', $dataLedger);

                        }
                        // For Montly Receivable Report
                        $reportData = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $doc_no,
                            'posting_date'  =>  $posting_date,
                            'description'   =>  $description[$i],
                            'amount'        =>  $amount
                        );

                        $this->app_model->insert('monthly_receivable_report', $reportData);


                    }

                }
                elseif ($this->sanitize($charges_type[$i]) == 'Pre Operation Charges')
                {
                    $tmp_preop = array(
                        'tenant_id'     =>  $tenant_id,
                        'doc_no'        =>  $doc_no,
                        'description'   =>  $description[$i],
                        'posting_date'  =>  $posting_date,
                        'due_date'      =>  $due_date,
                        'amount'        =>  str_replace(",", "", $actual_amount[$i]),
                        'tag'           =>  $tag
                    );

                    $this->app_model->insert('tmp_preoperationcharges', $tmp_preop);

                    $data = array(
                        'tenant_id'        =>   $tenant_id,
                        'trade_name'       =>   $trade_name,
                        'doc_no'           =>   $doc_no,
                        'posting_date'     =>   $posting_date,
                        'transaction_date' =>   $transaction_date,
                        'due_date'         =>   $due_date,
                        'store_code'       =>   $store_code,
                        'contract_no'      =>   $contract_no,
                        'charges_type'     =>   $charges_type[$i],
                        'charges_code'     =>   $charges_code[$i],
                        'description'      =>   $description[$i],
                        'uom'              =>   $uom[$i],
                        'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                        'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                        'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                        'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                        'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                        'tag'              =>   $tag
                    );

                    if ($tag == 'Posted')
                    {
                         // For Montly Receivable Report
                        $reportData = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $doc_no,
                            'posting_date'  =>  $posting_date,
                            'description'   =>  $description[$i],
                            'amount'        =>  str_replace(",", "", $actual_amount[$i])
                        );

                        $this->app_model->insert('monthly_receivable_report', $reportData);
                    }
                }
                else
                {

                    $data = array(
                        'tenant_id'        =>   $tenant_id,
                        'trade_name'       =>   $trade_name,
                        'doc_no'           =>   $doc_no,
                        'posting_date'     =>   $posting_date,
                        'transaction_date' =>   $transaction_date,
                        'due_date'         =>   $due_date,
                        'store_code'       =>   $store_code,
                        'contract_no'      =>   $contract_no,
                        'charges_type'     =>   $charges_type[$i],
                        'charges_code'     =>   $charges_code[$i],
                        'description'      =>   $description[$i],
                        'uom'              =>   $uom[$i],
                        'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                        'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                        'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                        'tag'              =>   $tag
                    );

                    if ($tag == 'Posted')
                    {
                        if ($description[$i] == 'VAT Output')
                        {
                            $amount = str_replace(",", "", $actual_amount[$i]);
                            $amount = -1 * $amount;
                            $vat = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refno,
                                'doc_no'            =>  $doc_no,
                                'due_date'          =>  $due_date,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  $amount,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $vat);
                            $this->app_model->insert('subsidiary_ledger', $vat);


                             // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'VAT',
                                'amount'        =>  $amount
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);


                        }
                        elseif ($description[$i] == 'Creditable Witholding Taxes')
                        {
                            $wht = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refno,
                                'due_date'          =>  $due_date,
                                'doc_no'            =>  $doc_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  str_replace(",", "", $actual_amount[$i]),
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $wht);
                            $this->app_model->insert('subsidiary_ledger', $wht);

                            // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'WHT',
                                'amount'        =>  str_replace(",", "", $actual_amount[$i]),
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);


                            $rent_incomeData = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refno,
                                'due_date'          =>  $due_date,
                                'doc_no'            =>  $doc_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $rent_income,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $rent_incomeData);
                            $this->app_model->insert('subsidiary_ledger', $rent_incomeData);


                            // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'Basic Rent',
                                'amount'        =>  $rent_income
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);


                        }
                        elseif ($description[$i] == 'Rental Incrementation')
                        {
                            $amount      = str_replace(",", "", $actual_amount[$i]);

                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'Rental Incrementation',
                                'amount'        =>  $amount
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);

                            $rent_income = $rent_income + $amount;
                        }
                        elseif ($charges_type[$i] == 'Discount')
                        {
                            $amount      = str_replace(",", "", $actual_amount[$i]);
                            $rent_income = $rent_income - $amount;

                             // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'Discount',
                                'amount'        =>  $amount
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }
                    }
                }

                $this->app_model->insert('invoicing', $data);
            }

            $response['msg'] = "Success";
        }


        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $response['msg'] = 'DB_error';
        } else {
            $response['msg'] = "Success";
        }

        echo json_encode($response);
    }
}


public function retro_invoice()
{

    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Supervisor')
        {

            if ($this->session->userdata('user_type') != 'Administrator')
            {
                $data['user_group'] = $this->app_model->my_store();
            } else {
                $data['stores'] = $this->app_model->get_stores();
            }
            $data['rental_increment'] = $this->app_model->get_rentalIncrement();
            $data['current_date']     = $this->_currentDate;
            $data['doc_no']           = $this->app_model->get_docNo(false);
            // $data['tenant_id'] = $this->app_model->get_tenantID();
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/retro_invoice');
            $this->load->view('leasing/footer');
        } else {
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            $this->session->set_flashdata('message', 'Restriction');
            redirect($prev_url);
        }
    } else {
        redirect('ctrl_leasing/');
    }
}


public function post_invoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id     = $this->uri->segment(3);
        $doc_no        = $this->uri->segment(4);
        $draft_invoice = $this->app_model->draft_invoice($tenant_id, $doc_no);

        $this->db->trans_start(); // Transaction function starts here!!!

        $data  = $this->app_model->get_postedData($tenant_id, $doc_no);
        $total = $this->app_model->total_perDocNo($tenant_id, $doc_no);
        $rent_income;
        foreach ($data as $item)
        {
            $charges_type = explode("-", $item['description']);
            $dataLedger = array(
                'posting_date'      =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $this->app_model->generate_refNo(),
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $item['tenant_id'],
                'contract_no'       =>  $item['contract_no'],
                'description'       =>  $item['description'],
                'debit'             =>  $item['balance'],
                'credit'            =>  0,
                'balance'           =>  $item['balance'],
                'due_date'          =>  $item['due_date'],
                'charges_type'      =>  'Basic'
            );


            $this->app_model->insert('ledger', $dataLedger);
        }

        $this->app_model->post_invoice($tenant_id, $doc_no);
        $counter = 0;
        foreach ($draft_invoice as $value)
        {
            if ($value['charges_type'] == 'Basic/Monthly Rental')
            {
                $rent_income = $value['expected_amt'];
                $rent_receivable = array(
                    'posting_date'      =>  $value['posting_date'],
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $this->app_model->gl_refNo(),
                    'doc_no'            =>  $value['doc_no'],
                    'tenant_id'         =>  $value['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $value['actual_amt'],
                    'tag'               =>  'Basic Rent',
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $rent_receivable);
                $this->app_model->insert('subsidiary_ledger', $rent_receivable);

            }
            elseif ($value['charges_type'] == 'Other')
            {

                if ($counter == 0)
                {
                    $account_receivable = array(
                        'posting_date'      =>  $value['posting_date'],
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Invoice',
                        'ref_no'            =>  $this->app_model->gl_refNo(),
                        'doc_no'            =>  $value['doc_no'],
                        'tenant_id'         =>  $value['tenant_id'],
                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'debit'             =>  str_replace(",", "", $total),
                        'tag'               =>  'Other',
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('general_ledger', $account_receivable);
                    $this->app_model->insert('subsidiary_ledger', $account_receivable);
                }


                $gl_code;
                if ($value['description'] == 'Common Usage Charges')
                {
                    $gl_code = '20.80.01.08.03';
                }
                elseif ($value['description'] == 'Electricity')
                {
                    $gl_code = '20.80.01.08.02';
                }
                elseif ($value['description'] == 'Aircon')
                {
                    $gl_code = '20.80.01.08.04';
                }
                elseif ($value['description'] == 'Chilled Water')
                {
                    $gl_code = '20.80.01.08.05';
                }
                elseif ($value['description'] == 'Water')
                {
                    $gl_code = '20.80.01.08.08';
                }
                elseif ($value['description'] == 'Construction Bond')
                {
                    $gl_code = '10.20.01.01.03.10';
                }
                elseif ($value['description'] == 'Security Bond')
                {
                    $gl_code = '10.20.01.01.03.12';
                }
                elseif ($value['description'] == 'Advance rent')
                {
                    $gl_code = '10.20.01.01.02.01';
                }
                else
                {
                    $gl_code = '20.80.01.08.07';
                }

                $amount = -1 * $value['actual_amt'];

                $otherGL = array(
                    'posting_date'      =>  $value['posting_date'],
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $this->app_model->gl_refNo(),
                    'doc_no'            =>  $value['doc_no'],
                    'tenant_id'         =>  $value['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $amount,
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $otherGL);
                $this->app_model->insert('subsidiary_ledger', $otherGL);
                $counter++;
            }
            else
            {
                if ($value['description'] == 'VAT Output')
                {
                    $amount = -1 * $value['actual_amt'];
                    $vat = array(
                        'posting_date'      =>  $value['posting_date'],
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Invoice',
                        'ref_no'            =>  $this->app_model->gl_refNo(),
                        'doc_no'            =>  $value['doc_no'],
                        'tenant_id'         =>  $value['tenant_id'],
                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'credit'            =>  $amount,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );
                    $this->app_model->insert('general_ledger', $vat);
                    $this->app_model->insert('subsidiary_ledger', $vat);
                }
                elseif ($value['description'] == 'Creditable Witholding Taxes')
                {

                    $wht = array(
                        'posting_date'      =>  $value['posting_date'],
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Invoice',
                        'ref_no'            =>  $this->app_model->gl_refNo(),
                        'doc_no'            =>  $value['doc_no'],
                        'tenant_id'         =>  $value['tenant_id'],
                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'debit'             =>  $value['actual_amt'],
                        'prepared_by'       =>  $this->session->userdata('id')
                    );
                    $this->app_model->insert('general_ledger', $wht);
                    $this->app_model->insert('subsidiary_ledger', $wht);

                    $rent_incomeData = array(
                        'posting_date'      =>  $value['posting_date'],
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Invoice',
                        'ref_no'            =>  $this->app_model->gl_refNo(),
                        'doc_no'            =>  $value['doc_no'],
                        'tenant_id'         =>  $value['tenant_id'],
                        'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'credit'            =>  -1 * $rent_income,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );
                    $this->app_model->insert('general_ledger', $rent_incomeData);
                    $this->app_model->insert('subsidiary_ledger', $rent_incomeData);
                }
                elseif ($value['description'] == 'Rental Incrementation')
                {
                    $rent_income = $rent_income + $value['actual_amt'];
                }
                elseif ($value['charges_type'] == 'Discount')
                {
                    $rent_income = $rent_income - $value['actual_amt'];
                }
            }

        }


        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->session->set_flashdata('message', 'Error');
            redirect('leasing_transaction/draft_invoice');
        } else {
            $this->session->set_flashdata('message', 'Posted');
            redirect('leasing_transaction/draft_invoice');
        }

    } else {
        redirect('ctrl_leasing/');
    }
}


public function get_otherCharges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->sanitize($this->uri->segment(3));
        $result = $this->app_model->get_otherCharges($tenant_id);
        echo json_encode($result);
    }
}


public function get_constMat()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_constMat();
        echo json_encode($result);
    }
}

public function get_preopCharges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->get_preopCharges();
        echo json_encode($result);
    }
}




public function get_tradeName()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('cfs_logged_in'))
    {
        // Format JSON POST
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $jsonstring = file_get_contents ( 'php://input' );

        $arr        = json_decode($jsonstring,true);


        $trade_name = $arr["trade_name"];
        $tenancy_type = $arr["tenancy_type"];
        $result     = $this->app_model->select_tradeName($trade_name, $tenancy_type);


        /* ==========  START MODIFICATIONS ==============*/
        $result = (object)$result[0];

        if($result->is_incrementable > 1){
            $exponent = $result->is_incrementable - 1;

            $basic_rental = $result->basic_rental *  pow(1+ ($result->increment_percentage/100), $exponent);

            $result->basic_rental = round($basic_rental, 2);
            $result->is_incrementable = 1;
        }

        $data[0] = $result;

        $result = $data;

        /* ==========  END MODIFICATIONS ==============*/


        echo json_encode($result);

    }
}

// public function get_invoiceNo()
// {
//     if ($this->session->userdata('leasing_logged_in'))
//     {
//         $tenant_id = $this->uri->segment(3);
//         $result['invoice_no'] = $this->app_model->get_invoiceNo($tenant_id);
//         echo json_encode($result);
//     }
// }

public function get_docNo()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id        = $this->uri->segment(3);
        $result['doc_no'] = $this->app_model->get_docNo(false);
        echo json_encode($result);
    }
}


public function closingPDC_docNo()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        $result['doc_no'] = $this->app_model->closingPDC_docNo();
        echo json_encode($result);
    }
}

public function get_soaNo()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id        = $this->uri->segment(3);
        $result['soa_no'] = $this->app_model->get_soaNo(false);
        echo json_encode($result);
    }
}


public function chargeDetails()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $description = $this->sanitize($this->uri->segment(3));
        $description = str_replace("%20", " ", $description);
        $result      = $this->app_model->chargeDetails($description);
        echo json_encode($result);
    }
}

public function datafor_basicRent()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->datafor_basicRent($tenant_id);
        echo json_encode($result);
    }
}

public function vat_wht()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['vat'] = $this->app_model->getVat();
        $data['wht'] = $this->app_model->WHT();
        echo json_encode($data);
    }
}

public function selected_monthly_charges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->selected_monthly_charges($tenant_id);
        echo json_encode($result);
    }
}

public function get_monthlyCharges_details()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $desc   = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->app_model->get_monthlyCharges_details($desc);
        echo json_encode($result);
    }
}


public function selected_monthlyCharges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->selected_monthlyCharges($tenant_id);
        echo json_encode($result);
    }
}


public function stenant_details()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->stenant_details($tenant_id);
        echo json_encode($result);
    }
}


public function get_monthlyCharges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_monthlyCharges($tenant_id);
        echo json_encode($result);
    }
}

public function populate_tenantID()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $flag   = str_replace("%20", " ", $this->uri->segment(3));
        $result = $this->app_model->populate_tenantID($flag);
        echo json_encode($result);
    }
}


public function populate_tradeName()
{
    $flag   = str_replace("%20", " ", $this->sanitize($this->uri->segment(3)));
    $result = $this->app_model->populate_tradeName($flag);
    echo json_encode($result);
}

public function shortTerm_charges()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->shortTerm_charges($tenant_id);
        echo json_encode($result);
    }
}

public function get_myDiscounts()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->sanitize($this->uri->segment(3));
        $result    = $this->app_model->get_myDiscounts($tenant_id);
        echo json_encode($result);
    }
}


public function get_rentalIncrement()
{
    $result['rental_increment'] = $this->app_model->get_rentalIncrement();
    echo json_encode($result);
}

public function get_billingDate()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_billingDate($tenant_id);
        echo json_encode($result);
    }
}


public function save_retro()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $this->db->trans_start(); // Transaction function starts here!!!
        $response         = array();
        $doc_no           = $this->app_model->get_docNo();
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $actual_amount    = $this->input->post('actual_amount');
        $due_date         = $this->sanitize($this->input->post('due_date'));
        $total            = $this->sanitize($this->input->post('total'));
        $total            = str_replace(",", "", $total);
        $posting_date     = $this->input->post('posting_date');
        $transaction_date = $this->input->post('transaction_date');
        $charges_type     = $this->input->post('charges_type');
        $charges_code     = $this->input->post('charges_code');
        $description      = $this->input->post('description');
        $uom              = $this->input->post('uom');
        $unit_price       = $this->input->post('unit_price');
        $total_unit       = $this->input->post('total_unit');
        $store_code       = $this->app_model->tenant_storeCode($tenant_id);
        $store_name       = $this->app_model->store_name($store_code);
        $store_address    = $this->app_model->store_address($store_code);
        $gl_refNo         = $this->app_model->gl_refNo();
        $sl_refNo         = $this->app_model->generate_refNo();
        $rent_income      = 0;
        for ($i=0; $i < count($actual_amount); $i++)
        {
            if ($description[$i] == 'Retro Rental')
            {
                $data = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $charges_type[$i],
                    'charges_code'     =>   $charges_code[$i],
                    'description'      =>   $description[$i],
                    'uom'              =>   $uom[$i],
                    'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                    'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                    'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                    'balance'          =>   str_replace(",", "", $total),
                    'actual_amt'       =>   str_replace(",", "", $total),
                    'tag'              =>   'Posted'
                );

                $this->app_model->insert('invoicing', $data);

                $dataLedger = array(
                    
                    'contact_no'        =>  $contact_no,
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $sl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'contract_no'       =>  $contract_no,
                    'description'       =>  'Retro-' . $trade_name,
                    'credit'            =>  $total,
                    'debit'             =>  0,
                    'balance'           =>  -1 * $total,
                    'due_date'          =>  $due_date,
                    'charges_type'      =>  'Retro'
                );
                $this->app_model->insert('ledger', $dataLedger);

                $rent_receivable = array(

                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'due_date'          =>  $due_date,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $total,
                    'tag'               =>  'Retro Rent',
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $rent_receivable);
                $this->app_model->insert('subsidiary_ledger', $rent_receivable);

                $rent_income -= $total;

            }
            elseif ($description[$i] == 'VAT Output')
            {
                $data = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $charges_type[$i],
                    'charges_code'     =>   $charges_code[$i],
                    'description'      =>   $description[$i],
                    'uom'              =>   $uom[$i],
                    'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                    'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                    'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                    'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                    'tag'              =>   'Posted'
                );
                $this->app_model->insert('invoicing', $data);

                $vat = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'          =>  $due_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * str_replace(",", "", $actual_amount[$i]),
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $vat);
                $this->app_model->insert('subsidiary_ledger', $vat);
                $rent_income += str_replace(",", "", $actual_amount[$i]);
            }
            else
            {
                $data = array(
                    'tenant_id'        =>   $tenant_id,
                    'trade_name'       =>   $trade_name,
                    'doc_no'           =>   $doc_no,
                    'posting_date'     =>   $posting_date,
                    'transaction_date' =>   $transaction_date,
                    'due_date'         =>   $due_date,
                    'store_code'       =>   $store_code,
                    'contract_no'      =>   $contract_no,
                    'charges_type'     =>   $charges_type[$i],
                    'charges_code'     =>   $charges_code[$i],
                    'description'      =>   $description[$i],
                    'uom'              =>   $uom[$i],
                    'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                    'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                    'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                    'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                    'tag'              =>   'Posted'
                );
                $this->app_model->insert('invoicing', $data);


                $wht = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $transaction_date,
                    'due_date'          =>  $due_date,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  str_replace(",", "", $actual_amount[$i]),
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $wht);
                $this->app_model->insert('subsidiary_ledger', $wht);
                $rent_income -= str_replace(",", "", $actual_amount[$i]);
            }

        }
        $rent_incomeData = array(
            'posting_date'      =>  $posting_date,
            'transaction_date' =>   $transaction_date,
            'due_date'          =>  $due_date,
            'document_type'     =>  'Invoice',
            'ref_no'            =>  $gl_refNo,
            'doc_no'            =>  $doc_no,
            'tenant_id'         =>  $tenant_id,
            'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
            'company_code'      =>  $this->session->userdata('company_code'),
            'department_code'   =>  '01.04',
            'credit'            =>  -1 * $rent_income,
            'prepared_by'       =>  $this->session->userdata('id')
        );
        $this->app_model->insert('general_ledger', $rent_incomeData);
        $this->app_model->insert('subsidiary_ledger', $rent_incomeData);
        $this->db->trans_complete(); // End of transaction function

        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'Saving Invoice', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
            $response['msg'] = 'Error';
        } else {

            $response['msg'] = "Success";
        }

        echo json_encode($response);

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function save_invoice()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tag              = $this->uri->segment(3);
        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();

        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $posting_date     = $this->input->post('posting_date');
        $transaction_date = $this->input->post('transaction_date');
        $due_date         = $this->sanitize($this->input->post('due_date'));
        $total            = $this->sanitize($this->input->post('total'));
        $total            = str_replace(",", "", $total);
        $total_gross      = $this->sanitize($this->input->post('total_gross'));
        $charges_type     = $this->input->post('charges_type');
        $charges_code     = $this->input->post('charges_code');
        $description      = $this->input->post('description');
        $uom              = $this->input->post('uom');
        $unit_price       = $this->input->post('unit_price');
        $prev_reading     = $this->input->post('prev_reading');
        $curr_reading     = $this->input->post('curr_reading');
        $total_unit       = $this->input->post('total_unit');
        $actual_amount    = $this->input->post('actual_amount');
        $rental_type      = $this->input->post('rental_type');
        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));

        $due_date         = date('Y-m-d', strtotime($due_date));


        $store_code    = $this->app_model->tenant_storeCode($tenant_id);
        $store_name    = $this->app_model->store_name($store_code);
        $store_address = $this->app_model->store_address($store_code);


        $rent_income;
        $response = array();



        if ($this->app_model->dueDate_validation($tenant_id, $due_date, $charges_type[0]) > 0)
        {
            $response['msg'] = 'Duplicate';
        } else {
            if (!$actual_amount)
            {
                $response['msg'] = 'No Charges Added';
            }
            else
            {
                $this->db->trans_start(); // Transaction function starts here!!!


                $is_penaltyExempt = true; //SET TO FALSE IF PENALTY REMOVAL DUE TO COVID WILL BE REVOKED

                if(!$is_penaltyExempt){
                	
                	//====== Check if Tenant has late payment penalty ======//
	                $penalty_latepayment = $this->app_model->get_latePaymentPenalty($tenant_id);
	                if ($penalty_latepayment)
	                {
	                    foreach ($penalty_latepayment as $penalty)
	                    {
	                        $penalty_docNo = $this->app_model->get_docNo();
	                        $penaltyData = array(
	                            'tenant_id'        =>   $tenant_id,
	                            'trade_name'       =>   $trade_name,
	                            'doc_no'           =>   $penalty_docNo,
	                            'posting_date'     =>   $posting_date,
	                            'transaction_date' =>   $transaction_date,
	                            'due_date'         =>   $due_date,
	                            'store_code'       =>   $store_code,
	                            'contract_no'      =>   $contract_no,
	                            'charges_type'     =>   'Other',
	                            'description'      =>   $penalty['description'],
	                            'expected_amt'     =>   $penalty['amount'],
	                            'actual_amt'       =>   $penalty['amount'],
	                            'balance'          =>   $penalty['amount'],
	                            'flag'             =>   'Penalty',
	                            'tag'              =>   $tag
	                        );
	                        $this->app_model->insert('invoicing', $penaltyData);
	                        // ===== Update tmp_latepaymentpenalty that the penalty was already invoiced ===== //
	                        $this->app_model->update_tmp_latepaymentpenalty($penalty['id'], $penalty_docNo);
	                        $this->app_model->update_ledgerDueDate($tenant_id, $penalty['doc_no'], $due_date);

	                        // GL and SL for late payment penalty


	                        $gl_penaltyRefNo = $this->app_model->gl_refNo();

	                        $penaltyLedger = array(
	                            'posting_date'      =>  $posting_date,
	                            'transaction_date'  =>  $penalty['posting_date'],
	                            'document_type'     =>  'Payment',
	                            'due_date'          =>  $due_date,
	                            'doc_no'            =>  $penalty_docNo,
	                            'charges_type'      =>  'Other',
	                            'ref_no'            =>  $this->app_model->generate_refNo(),
	                            'tenant_id'         =>  $tenant_id,
	                            'contract_no'       =>  $penalty['contract_no'],
	                            'description'       =>  'Other-' . $trade_name . '-Penalty',
	                            'credit'            =>  $penalty['amount'],
	                            'debit'             =>  0,
	                            'balance'           =>  -1 * round($penalty['amount'], 2),
	                            'flag'              =>  'Penalty'
	                        );

	                        $this->app_model->insert('ledger', $penaltyLedger);


	                        $account_receivable = array(
	                            'posting_date'      =>  $posting_date,
	                            'transaction_date'  =>  $transaction_date,
	                            'document_type'     =>  'Invoice',
	                            'ref_no'            =>  $gl_penaltyRefNo,
	                            'doc_no'            =>  $penalty_docNo,
	                            'due_date'          =>  $due_date,
	                            'tenant_id'         =>  $tenant_id,
	                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
	                            'company_code'      =>  $this->session->userdata('company_code'),
	                            'department_code'   =>  '01.04',
	                            'debit'             =>  round($penalty['amount'], 2),
	                            'tag'               =>  'Penalty',
	                            'prepared_by'       =>  $this->session->userdata('id')
	                        );


	                        $mipenalties = array(
	                            'posting_date'      =>  $posting_date,
	                            'transaction_date'  =>  $transaction_date,
	                            'document_type'     =>  'Invoice',
	                            'ref_no'            =>  $gl_penaltyRefNo,
	                            'due_date'          =>  $due_date,
	                            'doc_no'            =>  $penalty_docNo,
	                            'tenant_id'         =>  $tenant_id,
	                            'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
	                            'company_code'      =>  $this->session->userdata('company_code'),
	                            'department_code'   =>  '01.04',
	                            'credit'             =>  -1 * round($penalty['amount'], 2),
	                            'prepared_by'       =>  $this->session->userdata('id')
	                        );

	                        $this->app_model->insert('general_ledger', $account_receivable);
	                        $this->app_model->insert('general_ledger', $mipenalties);
	                        $this->app_model->insert('subsidiary_ledger', $account_receivable);
	                        $this->app_model->insert('subsidiary_ledger', $mipenalties);

	                        // For Montly Receivable Report
	                        $reportData = array(
	                            'tenant_id'     =>  $tenant_id,
	                            'doc_no'        =>  $penalty_docNo,
	                            'posting_date'  =>  $posting_date,
	                            'description'   =>  'Penalty',
	                            'amount'        =>  $penalty['amount']
	                        );
	                        $this->app_model->insert('monthly_receivable_report', $reportData);
	                    }
	                }

                }

                


                $doc_no = $this->app_model->get_docNo();
                $gl_refNo = $this->app_model->gl_refNo();

                for ($i=0; $i < count($actual_amount); $i++)
                {
                    if ($charges_type[$i] == 'Basic/Monthly Rental')
                    {

                        $data = array(
                            'tenant_id'        =>   $tenant_id,
                            'trade_name'       =>   $trade_name,
                            'doc_no'           =>   $doc_no,
                            'posting_date'     =>   $posting_date,
                            'transaction_date' =>   $transaction_date,
                            'due_date'         =>   $due_date,
                            'store_code'       =>   $store_code,
                            'contract_no'      =>   $contract_no,
                            'charges_type'     =>   $charges_type[$i],
                            'charges_code'     =>   $charges_code[$i],
                            'description'      =>   $description[$i],
                            'uom'              =>   $uom[$i],
                            'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                            'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                            'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                            'balance'          =>   str_replace(",", "", $total),
                            'actual_amt'       =>   str_replace(",", "", $total),
                            'total_gross'      =>   str_replace(",", "", $total_gross),
                            'tag'              =>   $tag
                        );


                        if ($tag == 'Posted')
                        {
                            $ref_no = $this->app_model->generate_refNo();
                            $rent_income = str_replace(",", "", $actual_amount[$i]);
                            $dataLedger = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $ref_no,
                                'doc_no'            =>  $doc_no,
                                'tenant_id'         =>  $tenant_id,
                                'contract_no'       =>  $contract_no,
                                'description'       =>  'Basic-' . $trade_name,
                                'credit'            =>  $total,
                                'debit'             =>  0,
                                'balance'           =>  -1 * $total,
                                'due_date'          =>  $due_date,
                                'charges_type'      =>  'Basic'
                            );

                            $this->app_model->insert('ledger', $dataLedger);


                            $rent_receivable = array(
                                'posting_date'      =>  $posting_date,
                                'transaction_date'  =>  $transaction_date,
                                'document_type'     =>  'Invoice',
                                'ref_no'            =>  $gl_refNo,
                                'doc_no'            =>  $doc_no,
                                'due_date'          =>  $due_date,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  $total,
                                'tag'               =>  'Basic Rent',
                                'prepared_by'       =>  $this->session->userdata('id')
                            );

                            $this->app_model->insert('general_ledger', $rent_receivable);
                            $this->app_model->insert('subsidiary_ledger', $rent_receivable);


                            // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  'Net Rental',
                                'amount'        =>  $total
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }

                    }
                    elseif ($charges_type[$i] == 'Other' || $charges_type[$i] == 'Construction Materials')
                    {
                        // ===== Other Charges Invoicing ==== //
                        $with_penalty = $this->app_model->get_withPenalty($description[$i]);
                        $data = array(
                            'tenant_id'        =>   $tenant_id,
                            'trade_name'       =>   $trade_name,
                            'doc_no'           =>   $doc_no,
                            'posting_date'     =>   $posting_date,
                            'transaction_date' =>   $transaction_date,
                            'due_date'         =>   $due_date,
                            'store_code'       =>   $store_code,
                            'contract_no'      =>   $contract_no,
                            'charges_type'     =>   $charges_type[$i],
                            'charges_code'     =>   $charges_code[$i],
                            'description'      =>   $description[$i],
                            'uom'              =>   $uom[$i],
                            'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                            'prev_reading'     =>   str_replace(",", "", $prev_reading[$i]),
                            'curr_reading'     =>   str_replace(",", "", $curr_reading[$i]),
                            'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                            'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                            'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                            'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                            'tag'              =>   $tag,
                            'with_penalty'     =>   $with_penalty
                        );

                        if ($tag == 'Posted')
                        {
                            $amount = str_replace(",", "", $actual_amount[$i]);

                            if ($i == 0)
                            {
                                $ar_code = '10.10.01.03.03';
                                if ($this->app_model->is_AGCSubsidiary($tenant_id)) {
                                    $ar_code = '10.10.01.03.04';
                                }
                                $account_receivable = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'due_date'          =>  $due_date,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $total,
                                    'tag'               =>  'Other',  
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $account_receivable);
                                $this->app_model->insert('subsidiary_ledger', $account_receivable);
                            }

                            $gl_code = "000";

                            if ($description[$i] == 'Expanded Withholding Tax')
                            {
                                $gl_code = "10.10.01.06.05";
                                $gl_entry = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'due_date'          =>  $due_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  ABS($amount),
                                    'tag'               =>  'Expanded',
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $gl_entry);
                                $this->app_model->insert('subsidiary_ledger', $gl_entry);

                                $dataLedger = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>   $transaction_date,
                                    'document_type'     =>  'Invoice',
                                    'doc_no'            =>  $doc_no,
                                    'ref_no'            =>  $this->app_model->generate_refNo(),
                                    'tenant_id'         =>  $tenant_id,
                                    'contract_no'       =>  $contract_no,
                                    'description'       =>  'Other-' . $trade_name . '-' . $description[$i],
                                    'credit'            =>  0,
                                    'debit'             =>  ABS(str_replace(",", "", $actual_amount[$i])),
                                    'balance'           =>  ABS(str_replace(",", "", $actual_amount[$i])),
                                    'due_date'          =>  $due_date,
                                    'charges_type'      =>  'Other',
                                    'flag'              =>  'EWT',
                                    'with_penalty'      =>  $with_penalty
                                );
                                $this->app_model->insert('ledger', $dataLedger);

                            }
                            else
                            {
                                if ($description[$i] == 'Common Usage Charges')
                                {
                                    $gl_code = '20.80.01.08.03';
                                }
                                elseif ($description[$i] == 'Electricity')
                                {
                                    $gl_code = '20.80.01.08.02';
                                }
                                elseif ($description[$i] == 'Aircon')
                                {
                                    $gl_code = '20.80.01.08.04';
                                }
                                elseif ($description[$i] == 'Late submission of Deposit Slip' || $description[$i] == 'Late Payment Penalty' || $description[$i] == 'Penalty')
                                {
                                    $gl_code = '20.80.01.08.01';
                                }
                                elseif ($description[$i] == 'Chilled Water')
                                {
                                    $gl_code = '20.80.01.08.05';
                                }
                                elseif ($description[$i] == 'Water')
                                {
                                    $gl_code = '20.80.01.08.08';
                                }
                                else
                                {
                                    $gl_code = '20.80.01.08.07';
                                }
                                $amount = -1 * $amount;
                                $gl_entry = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'due_date'          =>  $due_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  $amount,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $gl_entry);
                                $this->app_model->insert('subsidiary_ledger', $gl_entry);

                                $dataLedger = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date' =>   $transaction_date,
                                    'document_type'     =>  'Invoice',
                                    'doc_no'            =>  $doc_no,
                                    'ref_no'            =>  $this->app_model->generate_refNo(),
                                    'tenant_id'         =>  $tenant_id,
                                    'contract_no'       =>  $contract_no,
                                    'description'       =>  'Other-' . $trade_name . '-' . $description[$i],
                                    'credit'            =>  str_replace(",", "", $actual_amount[$i]),
                                    'debit'             =>  0,
                                    'balance'           =>  -1 * str_replace(",", "", $actual_amount[$i]),
                                    'due_date'          =>  $due_date,
                                    'charges_type'      =>  'Other',
                                    'with_penalty'      =>  $with_penalty
                                );
                                $this->app_model->insert('ledger', $dataLedger);
                            }

                            // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  $description[$i],
                                'amount'        =>  $amount
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }
                    }
                    elseif ($this->sanitize($charges_type[$i]) == 'Pre Operation Charges')
                    {
                        $tmp_preop = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $doc_no,
                            'description'   =>  $description[$i],
                            'posting_date'  =>  $posting_date,
                            'due_date'      =>  $due_date,
                            'amount'        =>  str_replace(",", "", $actual_amount[$i]),
                            'tag'           =>  $tag
                        );

                        $this->app_model->insert('tmp_preoperationcharges', $tmp_preop);


                        if ($tag == 'Posted')
                        {
                            // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'description'   =>  $description[$i],
                                'amount'        =>  str_replace(",", "", $actual_amount[$i])
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }


                        $data = array(
                            'tenant_id'        =>   $tenant_id,
                            'trade_name'       =>   $trade_name,
                            'doc_no'           =>   $doc_no,
                            'posting_date'     =>   $posting_date,
                            'transaction_date' =>   $transaction_date,
                            'due_date'         =>   $due_date,
                            'store_code'       =>   $store_code,
                            'contract_no'      =>   $contract_no,
                            'charges_type'     =>   $charges_type[$i],
                            'charges_code'     =>   $charges_code[$i],
                            'description'      =>   $description[$i],
                            'uom'              =>   $uom[$i],
                            'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                            'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                            'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                            'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                            'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                            'tag'              =>   $tag
                        );
                    }
                    else
                    {
                        $data = array(
                            'tenant_id'        =>   $tenant_id,
                            'trade_name'       =>   $trade_name,
                            'doc_no'           =>   $doc_no,
                            'posting_date'     =>   $posting_date,
                            'transaction_date' =>   $transaction_date,
                            'due_date'         =>   $due_date,
                            'store_code'       =>   $store_code,
                            'contract_no'      =>   $contract_no,
                            'charges_type'     =>   $charges_type[$i],
                            'charges_code'     =>   $charges_code[$i],
                            'description'      =>   $description[$i],
                            'uom'              =>   $uom[$i],
                            'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                            'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                            'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                            'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                            'tag'              =>   $tag
                        );

                        if ($tag == 'Posted')
                        {
                            if ($description[$i] == 'VAT Output')
                            {

                                $vat = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'due_date'          =>  $due_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * str_replace(",", "", $actual_amount[$i]),
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );
                                $this->app_model->insert('general_ledger', $vat);
                                $this->app_model->insert('subsidiary_ledger', $vat);


                                 // For Montly Receivable Report
                                $reportData = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'description'   =>  'VAT',
                                    'amount'        =>  str_replace(",", "", $actual_amount[$i])
                                );

                                $this->app_model->insert('monthly_receivable_report', $reportData);


                            }
                            elseif ($description[$i] == 'Creditable Witholding Taxes')
                            {
                                $wht = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'due_date'          =>  $due_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  str_replace(",", "", $actual_amount[$i]),
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );
                                $this->app_model->insert('general_ledger', $wht);
                                $this->app_model->insert('subsidiary_ledger', $wht);


                                // For Montly Receivable Report
                                $reportData = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'description'   =>  'WHT',
                                    'amount'        =>  str_replace(",", "", $actual_amount[$i])
                                );

                                $this->app_model->insert('monthly_receivable_report', $reportData);



                                $rent_incomeData = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'due_date'          =>  $due_date,
                                    'document_type'     =>  'Invoice',
                                    'ref_no'            =>  $gl_refNo,
                                    'doc_no'            =>  $doc_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $rent_income,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );
                                $this->app_model->insert('general_ledger', $rent_incomeData);
                                $this->app_model->insert('subsidiary_ledger', $rent_incomeData);


                                // For Montly Receivable Report
                                $reportData = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'description'   =>  'Basic Rent',
                                    'amount'        =>  $rent_income
                                );

                                $this->app_model->insert('monthly_receivable_report', $reportData);

                            }
                            elseif ($description[$i] == 'Rental Incrementation')
                            {
                                $amount = str_replace(",", "", $actual_amount[$i]);

                                $reportData = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'description'   =>  'Rental Incrementation',
                                    'amount'        =>  $amount
                                );

                                $this->app_model->insert('monthly_receivable_report', $reportData);


                                $rent_income = $rent_income + $amount;
                            }
                            elseif ($charges_type[$i] == 'Discount')
                            {
                                $amount = str_replace(",", "", $actual_amount[$i]);
                                $rent_income = $rent_income - $amount;

                                // For Montly Receivable Report
                                $reportData = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'description'   =>  'Discount',
                                    'amount'        =>  $amount
                                );

                                $this->app_model->insert('monthly_receivable_report', $reportData);
                            }
                        }
                    }


                    $this->app_model->insert('invoicing', $data);

                }


                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'Saving Invoice', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'Error';
                } else {

                    $response['msg'] = "Success";
                }

            }
        }
        echo json_encode($response);
    }
}

public function soa()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['current_date']   = $this->_currentDate;
        $data['billing_period'] = ['1-30 January', '1-28 February', '1-30 March', '1-30 April', '1-30 May', '1-30 June', '1-30 July', '1-30 August', '1-30 September', '1-30 October', '1-30 November', '1-30 December'];
        $data['current_year']   = $this->_currentYear;
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/soa');
        $this->load->view('leasing/footer');
    } else {
        redirect('ctrl_leasing/');
    }
}


public function save_soa()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $response        = array();
        $date            = new DateTime();
        $timeStamp       = $date->getTimestamp();
        $tenancy_type    = $this->input->post('tenancy_type');
        $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
        $trade_name      = $this->input->post('trade_name');
        $tenant_address  = $this->input->post('tenant_address');
        $doc_type        = $this->input->post('doc_type');
        $desc            = $this->input->post('desc');
        $posting_date    = $this->input->post('posting_date');
        $curr_date       = $this->input->post('curr_date');
        $due_date        = $this->input->post('due_date');
        $retro_due_date  = $this->input->post('retro_due_date');
        $retro_doc_no    = $this->input->post('retro_doc_no');
        $preop_doc_no    = $this->input->post('preop_doc_no');
        $amount_due      = $this->input->post('amount');
        $balance         = $this->input->post('balance');
        $total_amountDue = $this->input->post('total_amountDue');
        $totalAmount     = $this->input->post('totalAmount');
        $totalAmount     = str_replace(",", "", $totalAmount);
        $contract_no     = $this->input->post('contract_no');
        $soa_no          = $this->app_model->get_soaNo();
        $doc_no          = $this->input->post('doc_no');
        $billing_period  = $this->input->post('billing_period');
        $collection_date = $this->input->post('collection_date');
        $details_soa     = $this->app_model->details_soa($tenant_id, $tenancy_type);

        //TEMPORARY REMOVE PENALTY DUE TO COVID
        //$is_penaltyExempt = $this->app_model->is_penaltyExempt($tenant_id); 

        $is_penaltyExempt 	= true;
        $tenant_type     = $this->app_model->get_tenantType($tenant_id);

        if (!$due_date)
        {
            $due_date = array();
        }

        $file_name;
        $expanded          = 0;
        $advance_date;
        $advance_amount    = 0;
        $basic_total       = 0;
        $other_total       = 0;
        $remaining_advance = 0;
        $previous_amount   = 0;
        $paid_amount       = 0;
        $tenant_advances   = $this->app_model->get_totalAdvance($tenant_id, $contract_no);
        if ($tenant_advances)
        {
            foreach ($tenant_advances as $amount)
            {
               $advance_amount += abs($amount['advance']);
               $advance_date   = $amount['posting_date'];
            }
        }

        $is_advanceDeduction    = false;
        $orginal_advancePayment = $advance_amount;
        $SL_advanceAmount       = $advance_amount;
        $GL_advanceAmount       = $advance_amount;
        $store_code             = $this->app_model->tenant_storeCode($tenant_id);
        $store_details          = $this->app_model->store_details(trim($store_code));
        $preop_total            = 0;
        $overall_amount         = 0.00;

        $lessee_info            = $this->app_model->get_lesseeInfo($tenant_id, $contract_no);
        $store_name             = "";
        $rental_type            = "";
        $rent_percentage;

        if ($this->app_model->collectionDate_validation($tenant_id, $collection_date) > 0) {
            $response['msg'] = 'Duplicate';
        } else {


            if (count($total_amountDue) != 0)  //======= Check if there is invoiced charges ======//
            {
                $this->db->trans_start(); // Transaction function starts here!!!

                $pdf = new FPDF('p','mm', 'A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');
                $logoPath = getcwd() . '/assets/other_img/';

                // ==================== Receipt Header ================== //
                foreach ($store_details as $detail)
                {
                    $pdf->cell(15, 15, $pdf->Image($logoPath . $detail['logo'], $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);

                    $pdf->setFont ('times','B',12);
                    $pdf->cell(50, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                    $store_name = $detail['store_name'];
                    $pdf->SetTextColor(201, 201, 201);
                    $pdf->SetFillColor(35, 35, 35);
                    $pdf->cell(35, 6, " ", 0, 0, 'L');


                    $pdf->setFont ('times','',9);
                    $pdf->cell(30, 6, "Statement For:", 1, 0, 'C', TRUE);
                    $pdf->cell(30, 6, "Please Pay By:", 1, 0, 'C', TRUE);
                    $pdf->cell(30, 6, "Amount Due:", 1, 0, 'C', TRUE);

                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->ln();

                    $pdf->setFont ('times','',12);
                    $pdf->cell(15, 0, " ", 0, 0, 'L');
                    $pdf->cell(20, 10, $detail['store_address'], 0, 0, 'L');

                    $pdf->cell(65, 6, " ", 0, 0, 'L');
                    $pdf->setFont ('times','',9);
                    $pdf->cell(30, 5, $billing_period, 1, 0, 'C');
                    $pdf->cell(30, 5, date('F j, Y',strtotime($collection_date)), 1, 0, 'C');
                    if ($due_date)
                    {
                        $pdf->cell(30, 5, "P " . number_format($this->check_totalPayableForSOA($tenant_id, $contract_no, $tenant_type, $due_date, $retro_due_date, $preop_doc_no), 2), 1, 0, 'C');
                    }
                    else
                    {
                        $pdf->cell(30, 5, "P " . number_format($totalAmount, 2), 1, 0, 'C');
                    }

                    $pdf->ln();
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
                    $pdf->Multicell(90, 4, $detail['contact_person'] . "\n" . "Phone: " . $detail['contact_no'] . "\n" . "E-mail: " . $detail['email'], 1, 'C');

                    $pdf->ln();

                    $pdf->SetTextColor(0, 0, 0);
                }

                foreach ($lessee_info as $data)
                {
                    $rental_type = $data['rental_type'];
                    $pdf->setFont ('times','B',8);
                    $pdf->cell(25, 4, "Tenant ID ", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . $tenant_id, 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "SOA No.", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . $soa_no, 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Contract No", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . $contract_no, 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "Date", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . date('F j, Y',strtotime($curr_date)), 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Trade Name", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . $trade_name, 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "Location Code", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " . $data['location_code'], 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Corp Name", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $data['corporate_name'] , 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "Floor Area", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $data['floor_area'] . " Square Meters", 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Address", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $this->check_strLength($tenant_address, 30), 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "Billing Period", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $billing_period, 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Rental Type", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $data['rental_type'], 0, 0, 'L');
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "TIN", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ":  " .  $data['tin'], 0, 0, 'L');

                    $pdf->ln();

                    $pdf->cell(25, 4, "Percentage Rate", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');


                    if ($data['rental_type'] == 'Fixed' )
                    {
                        $pdf->cell(60, 4, ":  " .  "N/A", 0, 0, 'L');
                    } else {
                        $pdf->cell(60, 4, ":  " .  $data['rent_percentage'] . "%", 0, 0, 'L');
                        $rent_percentage = $data['rent_percentage'];
                    }
                    $pdf->cell(10, 4, "  ", 0, 0, 'L');
                    $pdf->cell(25, 4, "Tenancy Type", 0, 0, 'L');
                    $pdf->cell(2, 4, "  ", 0, 0, 'L');
                    $pdf->cell(60, 4, ": " . $tenancy_type, 0, 0, 'L');
                }


                $pdf->ln();
                $pdf->setFont ('times','B',10);

                if ($tenant_id == 'ICM-LT000008' || $tenant_id == 'ICM-LT000442' || $tenant_id == 'ICM-LT000492' || $tenant_id == 'ICM-LT000035' || $tenant_id == 'ICM-LT000120')
                {
                    $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. BPI - 1201008995"  , 0, 0, 'R');
                }

                elseif ($store_name == 'ALTA CITTA') {
                    if($tenant_id == 'ACT-LT000027'){
                        $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING BPI  ACCT# 9471-0016-75"  , 0, 0, 'R');
                    }else{
                        $pdf->cell(0, 5, "Please make all checks payable to ALTURAS SUPERMARKET CORP. - ALTA CITTA LEASING PNB ACCT# 3059-7000-6462"  , 0, 0, 'R');
                    }
                    
                }
                elseif ($tenant_id == 'ICM-LT000218' || $tenant_id == 'ICM-LT000219') {
                    $pdf->cell(0, 5, "Please make payment credited to ASC-ICM LEASING with acct# 3522 1000 63"  , 0, 0, 'R');
                }
             
                else
                {
                    if ($store_name == 'ALTURAS MALL') 
                    {
                        $pdf->cell(0, 5, "ALTURAS SUPERMARKET CORP. with Acct# 3059-7000-5922"  , 0, 0, 'R');
                    }
                    elseif($store_name == "PLAZA MARCELA")
                    {
                    	$pdf->cell(0, 5, " MFI - PLAZA MARCELA, LB ACCT #0612-0011-11"  , 0, 0, 'R');
                    }
                    elseif($store_name == 'ISLAND CITY MALL' || $tenant_id != 'ICM-LT000008' || $tenant_id != 'ICM-LT000442' || $tenant_id != 'ICM-LT000492' || $tenant_id != 'ICM-LT000035' || $tenant_id != 'ICM-LT000120')
                    {

                          $pdf->cell(0, 5, "Please make all checks payable to ISLAND CITY MALL,Acct # 9471 -0016-59 "  , 0, 0, 'R');
                    }
                     else

                     {
                        $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name). "" , 0, 0, 'R');
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
                $pdf->setFont ('times','B',12);
                $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L');
                $pdf->ln();

                //========== Previous Billing =========== //

                $previous_billing = $this->app_model->get_previousBilling($tenant_id, $contract_no);
                foreach ($previous_billing as $previous)
                {
                    $previous_amount = $previous['amount_payable'];
                    $paid_amount = $previous['amount_paid'];
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(100, 8, "Previous Billing Amount", 0, 0, 'L');
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, number_format($previous['amount_payable'], 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(100, 8, "Payment Received Amount - Thank you!", 0, 0, 'L');
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, number_format($previous['amount_paid'], 2), 0, 0, 'R');
                    $pdf->ln();
                }

                if ($preop_doc_no)
                {
                    $preop_total = 0;
                    $pdf->cell(100, 8, "Additional/Preoparation Charges", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->ln();

                    $preop_data = $this->app_model->get_preopdata($tenant_id);

                    foreach ($preop_data as $preop)
                    {

                        $preop_desc = "";

                        if ($preop['description'] == 'Security Deposit - Kiosk and Cart' || $preop['description'] == 'Security Deposit')
                        {
                            $preop_desc = "Security Deposit";
                        }
                        else
                        {
                            $preop_desc = $preop['description'];
                        }

                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, $preop_desc, 0, 0, 'L');
                        $pdf->cell(40, 4, "P " . number_format($preop['amount'], 2), 0, 0, 'R');
                        $pdf->ln();

                        $preop_total    += $preop['amount'];
                        $overall_amount += $preop['amount'];

                        $data = array(
                            'tenant_id'         => $tenant_id,
                            'store_code'        => $store_code,
                            'invoice_id'        => $this->app_model->get_invoiceID($tenant_id, $preop['doc_no'], $preop['description']),
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );

                        $this->app_model->insert('soa', $data);
                    }

                    $pdf->ln();
                    $pdf->setFont('times','',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Total", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($preop_total, 2), 0, 0, 'R');
                    $pdf->ln();
                }

                if ($retro_due_date) // Retro Rental
                {

                    $retro_data = $this->app_model->get_invoiceRetro($tenant_id);
                    $pdf->cell(100, 8, "RETRO RENT", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->ln();

                    $pdf->setFont('times','',10);

                    foreach ($retro_data as $retro)
                    {
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                        $pdf->cell(40, 4, "P " . number_format($retro['actual_amt'], 2), 0, 0, 'R');
                        $pdf->ln();

                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                        $pdf->setFont('times','U',10);
                        $pdf->cell(40, 4, "P " . number_format($retro['amount_paid'], 2), 0, 0, 'R');
                        $pdf->ln();
                        $pdf->setFont('times','',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                        $pdf->setFont('times','B',10);
                        $pdf->cell(40, 4, "P " . number_format($retro['balance'], 2), 0, 0, 'R');

                        $overall_amount += $retro['balance'];

                        // ========== Save to SOA ======== //
                        $data = array(
                            'tenant_id'         => $tenant_id,
                            'store_code'        => $store_code,
                            'invoice_id'        => $this->app_model->get_invoiceID($tenant_id, $retro['doc_no'], 'Retro Rental'),
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );

                        $this->app_model->insert('soa', $data);
                    }
                }

                // =================== Receipt Charges Breakdown ============= //

                $due_date = array_values(array_unique($due_date));
                $due_date = $this->sort_ascending($due_date);
                if ($due_date)
                {
                    $latest_dueDate = max($due_date);
                }

                for ($i=0; $i < count($due_date); $i++)
                {
                    if ($due_date[$i] != "")
                    {
                        // ===== Calculate due_date difference to determine the appropriate penalty ===== //
                        $daylen      = 60*60*24;
                        $daysDiff    = (strtotime($latest_dueDate)-strtotime($due_date[$i]))/$daylen;
                        $daysDiff    = $daysDiff / 20;
                        $daysDiff    = floor($daysDiff);
                        $current_due = strtotime($due_date[$i] . "-20 days");
                        $pdf->setFont('times','B',10);

                        
                        $basic_rent           = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Basic/Monthly Rental');
                        $rental_increment     = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Rent Incrementation');
                        $discount             = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Discount');
                        $basic                = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Basic');
                        $other_charges        = $this->app_model->other_chargeDetails($tenant_id, $due_date[$i]);
                        $total_perDuedate     = $this->app_model->previous_totalPerDueDate($tenant_id, $due_date[$i]);
                        $total_payableDuedate = $this->app_model->total_payableDuedate($tenant_id, $due_date[$i]);
                        $overall_amount       += $total_perDuedate;



                        // Record Late payment penalty to SL and GL
                        $invoiced_latepaymentpenalty = $this->app_model->get_invoicedlatepaymentpenalty($tenant_id);
                        if ($invoiced_latepaymentpenalty)
                        {
                            foreach ($invoiced_latepaymentpenalty as $lp_penalty)
                            {
                                $data_latepaymentTable = array(
                                    'soa'    =>  'Yes',
                                    'soa_no' =>  $soa_no
                                );
                                $this->app_model->update($data_latepaymentTable, $lp_penalty['id'], 'tmp_latepaymentpenalty');
                            }
                        }

                        $pdf->ln();

                        if ($i == count($due_date)-1) // Check current due date
                        {
                            $pdf->setFont('times','B',10);
                            $pdf->cell(100, 8, "CURRENT(" . date("F Y",$current_due) . ")", 0, 0, 'L');
                            foreach ($basic_rent as $value)
                            {
                                // ========== Save to SOA ======== //
                                $data = array(
                                    'tenant_id'         => $tenant_id,
                                    'store_code'        => $store_code,
                                    'invoice_id'        => $value['id'],
                                    'soa_no'            => $soa_no,
                                    'collection_date'   => $collection_date,
                                    'flag'              => $tenancy_type,
                                    'status'            => $billing_period
                                );

                                $this->app_model->insert('soa', $data);

                                $pdf->ln();
                                $pdf->setFont('times','B',10);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(30, 4, "Rental", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->ln();

                                if ($rental_type == 'Fixed')
                                {
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');
                                    $pdf->cell(40, 4, "P " . number_format($value['expected_amt'], 2), 0, 0, 'R');
                                    $basic_total += $value['expected_amt'];
                                }
                                elseif ($rental_type == 'Percentage')
                                {
                                    $pdf->setFont('times','',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                                    $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');
                                    $basic_total += $value['total_gross'] * ($rent_percentage / 100);
                                }
                                elseif ($rental_type == 'Fixed Plus Percentage')
                                {
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');

                                    /* ==========  START MODIFICATIONS ==============*/

                                    $basic_rental = $value['expected_amt'] - ($value['total_gross'] * ($rent_percentage / 100));
                                    $basic_total += $basic_rental;

                                    $pdf->cell(40, 4, "P " . number_format($basic_rental, 2), 0, 0, 'R');

                                    /* ==========  END MODIFICATIONS ==============*/


                                    /* ========== REMOVE FOR MODIFICATIONS ==============

                                    $pdf->cell(40, 4, "P " . number_format($value['basic_rental'], 2), 0, 0, 'R');
                                    $basic_total += $value['basic_rental'];

                                    ============= REMOVE FOR MODIFICATIONS ===============*/

                                    $pdf->ln();
                                    $pdf->setFont('times','',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                                    $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');
                                    $basic_total += $value['total_gross'] * ($rent_percentage / 100);

                                }
                                elseif ($rental_type == 'Fixed/Percentage w/c Higher')
                                {
                                    if ($value['expected_amt'] != $value['basic_rental'] && ($value['status'] != 'Credited' && $value['status'] != 'Debited') && $value['total_gross'] != 0)
                                    {
                                        $pdf->setFont('times','',10);
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                                        $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');
                                        $basic_total += $value['total_gross'] * ($rent_percentage / 100);
                                    } else {
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');
                                        $pdf->cell(40, 4, "P " . number_format($value['expected_amt'], 2), 0, 0, 'R');
                                        $basic_total += $value['expected_amt'];
                                    }
                                }



                                foreach ($rental_increment as $increment)
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, 'Rental Incrementation(' . $increment['unit_price'] . '%)', 0, 0, 'L');
                                    $pdf->cell(40, 4, number_format($increment['expected_amt'], 2), 0, 0, 'R');
                                    $basic_total += $increment['expected_amt'];
                                }

                                $pdf->ln();
                            }


                            $counter = 0;
                            foreach ($discount as $value)
                            {
                                // ========== Save to SOA ======== //
                                if ($counter == 0)
                                {
                                    $pdf->setFont('times','B',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(30, 4, "Discount", 0, 0, 'L');
                                    $pdf->setFont('times','',10);
                                }

                                $pdf->ln();
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                                $pdf->cell(40, 4, "-" . number_format($value['amount'], 2), 0, 0, 'R');
                                $basic_total -= $value['amount'];

                                $data = array(
                                    'tenant_id'         => $tenant_id,
                                    'store_code'        => $store_code,
                                    'invoice_id'        => $value['id'],
                                    'soa_no'            => $soa_no,
                                    'collection_date'   => $collection_date,
                                    'flag'              => $tenancy_type,
                                    'status'            => $billing_period
                                );

                                $this->app_model->insert('soa', $data);

                                $counter++;
                                $pdf->ln();
                            }


                            foreach ($basic as $value)
                            {
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, $value['description'], 0, 0, 'L');

                                if ($value['description'] == 'Creditable Witholding Taxes')
                                {
                                    $pdf->cell(40, 4, "-" . number_format($value['amount'], 2), 0, 0, 'R');
                                    $basic_total -= $value['amount'];
                                } else {
                                    $pdf->cell(40, 4, number_format($value['amount'], 2), 0, 0, 'R');
                                    $basic_total += $value['amount'];
                                }

                                // ========== Save to SOA ========= //
                                $data = array(
                                    'tenant_id'         => $tenant_id,
                                    'store_code'        => $store_code,
                                    'invoice_id'        => $value['id'],
                                    'soa_no'            => $soa_no,
                                    'collection_date'   => $collection_date,
                                    'flag'              => $tenancy_type,
                                    'status'            => $billing_period
                                );
                                $this->app_model->insert('soa', $data);
                                $pdf->ln();
                            }


                            $pdf->ln();
                            $pdf->ln();
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(100, 4, "Sub Total", 0, 0, 'L');
                            $pdf->setFont('times','',10);
                            $pdf->setFont('times','B',10);
                            $pdf->cell(40, 4, "P " . number_format($basic_total, 2), 0, 0, 'R');

                            $pdf->ln();
                            $pdf->ln();
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(30, 4, "Add:Other Charges", 0, 0, 'L');
                            $pdf->setFont('times','',10);

                            foreach ($other_charges as $value)
                            {
                                // ========== Save to SOA ======== //
                                $data = array(
                                    'tenant_id'         => $tenant_id,
                                    'store_code'        => $store_code,
                                    'invoice_id'        => $value['id'],
                                    'soa_no'            => $soa_no,
                                    'collection_date'   => $collection_date,
                                    'flag'              => $tenancy_type,
                                    'status'            => $billing_period
                                );

                                $this->app_model->insert('soa', $data);

                                if ((strtolower($value['description']) == 'water' || strtolower($value['description']) == 'electricity') && $value['status'] != 'Debited')
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                                    $pdf->ln();
                                    $pdf->setFont('times','',8);
                                    $pdf->cell(60, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "Present", 0, 0, 'L');
                                    $pdf->cell(20, 4, "Previous", 0, 0, 'L');
                                    $pdf->cell(20, 4, "Consumed", 0, 0, 'L');
                                    $pdf->ln();
                                    $pdf->cell(60, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, number_format($value['curr_reading'], 2), 0, 0, 'L');
                                    $pdf->cell(20, 4, number_format($value['prev_reading'], 2), 0, 0, 'L');
                                    $pdf->cell(20, 4, number_format($value['curr_reading'] - $value['prev_reading'], 2), 0, 0, 'L');
                                    $pdf->cell(10, 4, " ", 0, 0, 'L');
                                    $pdf->setFont('times','',10);
                                    $pdf->cell(40, 4, number_format($value['balance'], 2), 0, 0, 'R');

                                }
                                else
                                {

                                    // ICM POST OFFICE EXEMPTION MOTHERFUCKER
                                    if ($tenant_id == 'ICM-LT000114' && $value['description'] == 'Expanded Withholding Tax')
                                    {
                                        $expanded = $value['balance'];
                                    }
                                    else
                                    {
                                        $pdf->ln();
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                                        $pdf->cell(40, 4, number_format($value['balance'], 2), 0, 0, 'R');
                                    }

                                }


                                $other_total += $value['balance'];
                            }

                            // ICM POST OFFICE EXEMPTION MOTHERFUCKER
                            if ($tenant_id == 'ICM-LT000114')
                            {
                                $pdf->ln();
                                $pdf->ln();
                                $pdf->setFont('times','B',10);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(100, 4, "Total W/out Withholding Taxes", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->setFont('times','B',10);
                                $pdf->cell(40, 4, "P " . number_format($other_total - $expanded, 2), 0, 0, 'R');

                                $pdf->ln();
                                $pdf->ln();

                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(100, 4, "Withholding Taxes", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->setFont('times','B',10);
                                $pdf->cell(40, 4, "P " . number_format($expanded, 2), 0, 0, 'R');

                                $pdf->ln();
                            }


                            $pdf->ln();
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(100, 4, "Sub Total", 0, 0, 'L');
                            $pdf->setFont('times','',10);
                            $pdf->setFont('times','B',10);
                            $pdf->cell(40, 4, "P " . number_format($other_total, 2), 0, 0, 'R');


                            $pdf->ln();
                            $pdf->ln();
                            $pdf->ln();

                            // ICM POST OFFICE EXEMPTION MOTHERFUCKER
                            if ($tenant_id != 'ICM-LT000114')
                            {
                                $pdf->setFont('times','B',10);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->setFont('times','B',10);
                                $pdf->cell(40, 4, "P " . number_format($total_perDuedate, 2), 0, 0, 'R');
                            }


                            $pdf->ln();
                            $pdf->ln();

                            // End of Current Due Date
                        }
                        else
                        {
                            $previous_dueDateData     = $this->app_model->get_previous_dueDateData($tenant_id, $due_date[$i]);
                            $previous_totalPerDueDate = $this->app_model->previous_totalPerDueDate($tenant_id, $due_date[$i]);
                            $nopenalty_amount         = $this->app_model->previous_withNoPenalty($tenant_id, $due_date[$i]);
                            $penalty                  = 0;
                            $payment_received         = $total_payableDuedate - $previous_totalPerDueDate;
                            if ($previous_totalPerDueDate > 1)
                            {
                                $pdf->setFont('times','B',10);
                                $pdf->cell(100, 8, "PREVIOUS", 0, 0, 'L');
                                $pdf->ln();
                                $pdf->setFont('times','B',10);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(30, 4, date("F Y",$current_due), 0, 0, 'L');
                                $pdf->setFont('times','',10);
                                $pdf->ln();

                                foreach ($basic_rent as $value)
                                {
                                    // ========== Save to SOA ======== //
                                    $data = array(
                                        'tenant_id'         => $tenant_id,
                                        'store_code'        => $store_code,
                                        'invoice_id'        => $value['id'],
                                        'soa_no'            => $soa_no,
                                        'collection_date'   => $collection_date,
                                        'flag'              => $tenancy_type,
                                        'status'            => $billing_period
                                    );

                                    $this->app_model->insert('soa', $data);
                                    $pdf->setFont('times','',10);

                                    foreach ($previous_dueDateData as  $value)
                                    {
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');


                                        $pdf->cell(40, 4, "P " . number_format($value['previous_balance'], 2), 0, 0, 'R');

                                        $pdf->ln();

                                        if ($nopenalty_amount)
                                        {
                                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                            $pdf->cell(80, 4, "No Penalty Charges", 0, 0, 'L');
                                            $pdf->cell(40, 4, "P " . number_format($nopenalty_amount, 2), 0, 0, 'R');
                                            $pdf->ln();
                                        }

                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');



                                        $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                                        $pdf->setFont('times','U',10);

                                        $pdf->cell(40, 4, number_format($value['amount_paid'], 2), 0, 0, 'R');
                                    }


                                    $pdf->ln();
                                    $pdf->setFont('times','',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                                    $pdf->cell(40, 4, number_format($previous_totalPerDueDate, 2), 0, 0, 'R');

                                }

                                foreach ($other_charges as $value)
                                {
                                    // ========== Save to SOA ======== //
                                    $data = array(
                                        'tenant_id'         =>  $tenant_id,
                                        'store_code'        =>  $store_code,
                                        'invoice_id'        =>  $value['id'],
                                        'soa_no'            =>  $soa_no,
                                        'collection_date'   =>  $collection_date,
                                        'flag'              =>  $tenancy_type,
                                        'status'            =>  $billing_period
                                    );

                                    $this->app_model->insert('soa', $data);
                                }

                                if (count($basic_rent) == 0)
                                {
                                    $pdf->setFont('times','',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                                    $pdf->cell(40, 4, "P " . number_format($total_perDuedate, 2), 0, 0, 'R');
                                }


                                if ($daysDiff != 0)
                                {
                                    $pdf->ln();
                                    $pdf->setFont('times','B',10);
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(30, 4, "Penalty:", 0, 0, 'L');
                                    $pdf->ln();
                                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                    $pdf->setFont('times','',10);


                                    $percent_penalty          = 0;
                                    $additional_penalty       = 0;
                                    $penalty_carryOver        = 0;
                                    $previous_totalPerDueDate -= $nopenalty_amount;

                                    
                                    if (!$is_penaltyExempt)
                                    {
                                        if ($daysDiff == 1)
                                        {
                                            $percent_penalty    = 2;
                                            $pdf->cell(80, 4, number_format($previous_totalPerDueDate, 2) . " x 2% (" . date('F Y', $current_due) . ")", 0, 0, 'L');
                                            $penalty            = $previous_totalPerDueDate * .02;
                                            $additional_penalty = $penalty;
                                            //$overall_amount += $penalty;
                                            $pdf->cell(40, 4, number_format($penalty, 2), 0, 0, 'R');
                                        }
                                        elseif($daysDiff >= 2)
                                        {
                                            $percent_penalty = 3;
                                            $pdf->cell(80, 4, number_format($previous_totalPerDueDate, 2) . " x 3% (" . date('F Y', $current_due) . ")", 0, 0, 'L');
                                            $penalty            = $previous_totalPerDueDate * .03;
                                            $additional_penalty = $penalty;

                                            $pdf->cell(40, 4, number_format($penalty, 2), 0, 0, 'R');
                                        }
                                    }
                                    
                                    if (!$is_penaltyExempt)
                                    {
                                        $penaltyEntry = array(
                                            'posting_date'  =>  $curr_date,
                                            'document_type' =>  'Penalty',
                                            'ref_no'        =>  $this->app_model->generate_refNo(),
                                            'due_date'      =>  $due_date[$i],
                                            'doc_no'        =>  $soa_no,
                                            'tenant_id'     =>  $tenant_id,
                                            'contract_no'   =>  $contract_no,
                                            'description'   =>  'Penalty-' . $trade_name,
                                            'credit'        =>  $additional_penalty,
                                            'balance'       =>  -1 * $additional_penalty,
                                            'flag'          =>  'Penalty'
                                        );

                                        $this->app_model->insert('ledger', $penaltyEntry);

                                        $ref_no = $this->app_model->gl_refNo();

                                        $account_receivable = array(
                                            'posting_date'      =>  $curr_date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'due_date'          =>  $due_date[$i],
                                            'document_type'     =>  'Invoice',
                                            'ref_no'            =>  $ref_no,
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $additional_penalty,
                                            'tag'              =>  'Penalty',
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $account_receivable);
                                        $this->app_model->insert('subsidiary_ledger', $account_receivable);
                                        $gl_penalty = array(
                                            'posting_date'      =>  $curr_date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'due_date'          =>  $due_date[$i],
                                            'document_type'     =>  'Invoice',
                                            'ref_no'            =>  $ref_no,
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>   -1 * $additional_penalty,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );

                                        $this->app_model->insert('general_ledger', $gl_penalty);
                                        $this->app_model->insert('subsidiary_ledger', $gl_penalty);

                                        // ============ Insert Into monthly_penalty table ============ //
                                        $data = array(
                                            'tenant_id'         =>      $tenant_id,
                                            'percent'           =>      $percent_penalty,
                                            'due_date'          =>      $due_date[$i],
                                            'doc_no'            =>      $doc_no[$i],
                                            'collection_date'   =>      $collection_date,
                                            'soa_no'            =>      $soa_no,
                                            'amount'            =>      $additional_penalty,
                                            'balance'           =>      $additional_penalty
                                        );

                                        $this->app_model->insert('monthly_penalty', $data);

                                        // For Montly Receivable Report
                                        $reportData = array(
                                            'tenant_id'     =>  $tenant_id,
                                            'doc_no'        =>  $soa_no,
                                            'posting_date'  =>  $curr_date,
                                            'description'   =>  'Penalty',
                                            'amount'        =>  $additional_penalty
                                        );

                                        $this->app_model->insert('monthly_receivable_report', $reportData);
                                    }
                                   
                                }

                                $pdf->ln();
                                $pdf->ln();
                                $pdf->ln();
                                $pdf->setFont('times','', '10');
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->setFont('times','B',10);
                                $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
                                $overall_amount += $penalty;
                                $pdf->cell(40, 4, "P " . number_format($previous_totalPerDueDate + $penalty  + $nopenalty_amount, 2), 0, 0, 'R');

                                $pdf->ln();
                                $pdf->ln();
                            }
                        }
                    }
                }


                // ==== If has advance payment this section is the manipulation of data in tenant ledger ==== //
                $total_deducted = 0; //Total deducted in the advance payment
                if ($advance_amount > 0)
                {
                    $doc_no = $this->sort_ascending($doc_no);
                    for ($i=0; $i < count($doc_no); $i++)
                    {
                        if ($doc_no[$i] != "")
                        {
                            $ledger_item = $this->app_model->get_ledgerEntry($tenant_id, $doc_no[$i]);
                            foreach ($ledger_item as $item)
                            {
                                $is_advanceDeduction = TRUE;
                                if ($advance_amount > 0)
                                {
                                    $credit;
                                    $balance;

                                    if ($advance_amount >= $item['balance'])
                                    {
                                        $credit = $item['balance'];
                                        $balance = 0;
                                    }
                                    else
                                    {
                                        $credit = $advance_amount;
                                        $balance = abs($item['balance'] - $advance_amount);
                                    }

                                    $entryData = array(

                                        'posting_date'  =>  $curr_date,
                                        'document_type' =>  'SOA',
                                        'ref_no'        =>  $item['ref_no'],
                                        'doc_no'        =>  $soa_no,
                                        'tenant_id'     =>  $tenant_id,
                                        'contract_no'   =>  $contract_no,
                                        'description'   =>  $item['description'],
                                        'debit'         =>  $credit,
                                        'balance'       =>  $balance
                                    );

                                    $total_deducted += $item['balance'];
                                    $this->app_model->insert('ledger', $entryData);
                                    $advance_amount -= $item['balance'];
                                }
                            }
                        }
                    }
                }

                // ======= Close the advance payment in tenant ledger ====== //

                if ($SL_advanceAmount > 0 && $is_advanceDeduction)
                {
                    $advances = $this->app_model->get_advancePayment($tenant_id, $contract_no);
                    foreach ($advances as $advance)
                    {
                        if ($advance['balance'] > 0 && $total_deducted > 0)
                        {
                            if ($total_deducted >= $advance['balance'])
                            {
                                // ENTRY TO TENANT LEDGER
                                $entryData = array(
                                    'posting_date'  =>  $curr_date,
                                    'document_type' =>  'SOA',
                                    'ref_no'        =>  $advance['ref_no'],
                                    'doc_no'        =>  $soa_no,
                                    'tenant_id'     =>  $tenant_id,
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $advance['description'],
                                    'credit'        =>  $advance['balance'],
                                    'balance'       =>  0
                                );
                                $this->app_model->insert('ledger', $entryData);

                                $orginal_advancePayment -= $advance['balance'];
                                $total_deducted -= $advance['balance'];
                            }
                            else
                            {
                                $entryData = array(
                                    'posting_date'  =>  $curr_date,
                                    'document_type' =>  'SOA',
                                    'ref_no'        =>  $advance['ref_no'],
                                    'doc_no'        =>  $soa_no,
                                    'tenant_id'     =>  $tenant_id,
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $advance['description'],
                                    'credit'        =>  $total_deducted,
                                    'balance'       =>  abs($advance['balance'] - $total_deducted)
                                );
                                $this->app_model->insert('ledger', $entryData);
                                $total_deducted = 0;
                            }
                        }
                    }
                }


                //===== Advance payment to General Ledger ===== //

                if ($GL_advanceAmount > 0)
                {
                    $doc_no = $this->sort_ascending($doc_no);
                    for ($i=0; $i < count($doc_no); $i++)
                    {
                        $gl_entries =  $this->app_model->gl_entries($tenant_id, $doc_no[$i]);
                        foreach ($gl_entries as $gl_entry)
                        {
                            if ($gl_entry['tag'] == 'Other') // AR
                            {
                                $ar_code = '10.10.01.03.03'; // Check if AGC Subsidiary, Subject for ARNTI
                                if ($this->app_model->is_AGCSubsidiary($tenant_id)) {
                                    $ar_code = '10.10.01.03.04';
                                }

                                $gl_advances = $this->app_model->gl_advancePayment($tenant_id);
                                foreach ($gl_advances as $gl_advance)
                                {
                                    if ($gl_advance['amount'] > 0 && $gl_entry['balance'] > 0)
                                    {
                                        if ($gl_entry['balance'] >= $gl_advance['amount'])
                                        {
                                            $advance_URI = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_advance['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $gl_advance['amount'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $advance_URI);
                                            $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                            $AR = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_entry['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $gl_advance['amount'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $AR);
                                            $this->app_model->insert('subsidiary_ledger', $AR);



                                            $gl_entry['balance'] -= $gl_advance['amount'];
                                            $gl_advance['amount'] = 0;


                                        }
                                        else
                                        {
                                            $advance_URI = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_advance['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $gl_entry['balance'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $advance_URI);
                                            $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                            $AR = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_entry['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $gl_entry['balance'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $AR);
                                            $this->app_model->insert('subsidiary_ledger', $AR);

                                            $gl_advance['amount'] -= $gl_entry['balance'];
                                            $gl_entry['balance'] = 0;
                                        }
                                    }
                                }
                            }
                            else // RR
                            {
                                $gl_advances = $this->app_model->gl_advancePayment($tenant_id);
                                foreach ($gl_advances as $gl_advance)
                                {
                                    if ($gl_advance['amount'] > 0 && $gl_entry['balance'] > 0)
                                    {
                                        if ($gl_entry['balance'] >= $gl_advance['amount'])
                                        {
                                            $advance_URI = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_advance['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $gl_advance['amount'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $advance_URI);
                                            $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                            $RR = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_entry['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $gl_advance['amount'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $RR);
                                            $this->app_model->insert('subsidiary_ledger', $RR);


                                            $gl_entry['balance'] -= $gl_advance['amount'];
                                            $gl_advance['amount'] = 0;

                                        }
                                        else
                                        {
                                            $advance_URI = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_advance['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $gl_entry['balance'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $advance_URI);
                                            $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                            $RR = array(
                                                'posting_date'      =>  $curr_date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_entry['ref_no'],
                                                'doc_no'            =>  $soa_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $gl_entry['balance'],
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $RR);
                                            $this->app_model->insert('subsidiary_ledger', $RR);

                                            $gl_advance['amount'] -= $gl_entry['balance'];
                                            $gl_entry['balance'] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                if ($due_date)
                {
                    // If has waived penalties
                    $waived_penalty = $this->app_model->get_waivedPenalty($tenant_id, min($due_date), max($due_date));
                    if ($waived_penalty > 1)
                    {
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(100, 4, "Waived Penalty", 0, 0, 'L');
                        $pdf->setFont('times','B',10);
                        $pdf->cell(40, 4, "-" . number_format($waived_penalty, 2), 0, 0, 'R');
                        $overall_amount = $overall_amount - $waived_penalty;
                    }
                }

                // To show advance amount to SOA
                if ($tenant_advances)
                {
                    $amount_to_show = 0;
                    foreach ($tenant_advances as $amount)
                    {
                        $amount_to_show += $amount['advance'];
                    }
                    if ($amount_to_show > 0)
                    {
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(100, 4, "Advance Payment"  . " (" . $advance_date . ")", 0, 0, 'L');
                        $pdf->setFont('times','',10);
                        $pdf->cell(40, 4, "P " . number_format($amount_to_show, 2), 0, 0, 'R');

                        $overall_amount    -= $preop_total; // Exclude preop deduction for advance rent
                        $remaining_advance = $amount_to_show - $overall_amount;
                        $overall_amount    = $overall_amount - $amount_to_show;
                        if ($overall_amount < 0)
                        {
                            $overall_amount = $preop_total;
                        }
                        else
                        {
                            $overall_amount += $preop_total;
                        }
                    }
                }

                $pdf->ln();
                $pdf->ln();

                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Total Amount Due", 0, 0, 'L');
                $pdf->setFont('times','BU',10);
                if ($overall_amount > 0)
                {
                    $pdf->cell(40, 4, "P " . number_format($overall_amount, 2), 0, 0, 'R');
                }
                else
                {
                    $pdf->cell(40, 4, "P 0.00", 0, 0, 'R');
                }


                if ($remaining_advance > 0) // if  there is still remaining advance payment
                {
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Remaining Advance Payment", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($remaining_advance, 2), 0, 0, 'R');
                }


                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();

                $pdf->setFont('times','',10);
                $pdf->cell(0, 4, "Certified: _____________________      ______________________                                          ", 0, 0, 'R');
                $pdf->ln();
                $pdf->setFont('times','',8);
                $pdf->cell(50, 4, "     ", 0, 0, 'L');
                $pdf->cell(0, 4, "                                            Mall Auditor                             Leasing Account In-charge                               ", 0, 0, 'L');
                $pdf->ln();
                $pdf->ln();

                $pdf->setFont('times','B',10);

                $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
                $pdf->setFont('times','B',12);
                $pdf->ln();
                $pdf->cell(190, 4,"         ", 1, 0, 'L', TRUE);
                $pdf->ln();
                $pdf->setFont('times','B',8);
                $pdf->cell(0, 4, "Note: Presentation of this statement is sufficient notice that the account is due. Interest of 3% will be charged for all past due accounts.", 0, 0, 'L');

                $file_name =  $tenant_id . $timeStamp . '.pdf';
                $data = array(
                    'tenant_id'         =>  $tenant_id,
                    'file_name'         =>  $file_name,
                    'soa_no'            =>  $soa_no,
                    'billing_period'    =>  $billing_period,
                    'amount_payable'    =>  $overall_amount,
                    'posting_date'      =>  $curr_date
                );



                $this->db->trans_complete(); // End of transaction function
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'Generating SOA', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'DB_error';
                }
                else
                {

                    $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                    header('Content-Type: application/facilityrental_pdf');
                    $pdf->Output('assets/pdf/' . $file_name , 'F');

                    $this->app_model->insert('soa_file', $data);
                    $response['msg'] = 'Success';
                }

            }
            else
            {
                $response['msg'] = 'No Charges';
            }
        }

        echo json_encode($response);

    }
    else
    {
        redirect('ctrl_leasing/');
    }
}

public function soa_batch()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['current_date']   = $this->_currentDate;
        $data['billing_period'] = ['1-30 January', '1-28 February', '1-30 March', '1-30 April', '1-30 May', '1-30 June', '1-30 July', '1-30 August', '1-30 September', '1-30 October', '1-30 November', '1-30 December'];
        $data['current_year']   = $this->_currentYear;
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/soa_batch');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function generate_batchSOA()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $response        = array();
        $date            = new DateTime();
        $timeStamp       = $date->getTimestamp();
        $billing_period  = $this->sanitize($this->input->post('billing_period'));
        $collection_date = $this->sanitize($this->input->post('collection_date'));
        $file_name       =  $collection_date . $timeStamp . '.pdf';
        $invoicedTenant  = $this->app_model->invoicedTenant();
        $pdf             = new FPDF('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->setDisplayMode ('fullpage');
        $logoPath = getcwd() . '/assets/other_img/';

        $this->db->trans_start(); // Transaction function starts here!!!

        foreach ($invoicedTenant as $tenant)
        {
            $advance_date;
            $overall_amount  = 0.00;
            $advance_amount  = 0;
            $tenant_advances = $this->app_model->get_totalAdvance($tenant['tenant_id'], $tenant['contract_no']);
            $tenant_type     = $this->app_model->get_tenantType($tenant['tenant_id']);
            if ($tenant_advances)
            {
                foreach ($tenant_advances as $amount)
                {
                   $advance_amount += $amount['advance'];
                   $advance_date   = $amount['transaction_date'];
                }
            }

            $is_advanceDeduction    = false;
            $orginal_advancePayment = $advance_amount;
            $GL_advanceAmount       = $advance_amount;
            $SL_advanceAmount       = $advance_amount;
            $store_code             = $this->app_model->tenant_storeCode($tenant['tenant_id']);
            $store_details          = $this->app_model->store_details(trim($store_code));
            $lessee_info            = $this->app_model->get_lesseeInfo($tenant['tenant_id'], $tenant['contract_no']);
            $soa_no                 = $this->app_model->get_soaNo();
            $store_name             = "";
            $trade_name;
            $contract_no;
            $rental_type            = "";
            $tenancy_type           = "";
            $rent_percentage;
            $preop_total            = 0;



            foreach ($store_details as $detail)
            {
                $pdf->cell(15, 15, $pdf->Image($logoPath . $detail['logo'], $pdf->GetX(), $pdf->GetY(), 15), 0, 0, 'L', false);

                $pdf->setFont ('times','B',12);
                $pdf->cell(50, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                $store_name = $detail['store_name'];
                $pdf->SetTextColor(201, 201, 201);
                $pdf->SetFillColor(35, 35, 35);
                $pdf->cell(35, 6, " ", 0, 0, 'L');

                $pdf->setFont ('times','',9);
                $pdf->cell(30, 6, "Statement For:", 1, 0, 'C', TRUE);
                $pdf->cell(30, 6, "Please Pay By:", 1, 0, 'C', TRUE);
                $pdf->cell(30, 6, "Amount Due:", 1, 0, 'C', TRUE);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->ln();

                $pdf->setFont ('times','',12);
                $pdf->cell(15, 0, " ", 0, 0, 'L');
                $pdf->cell(20, 10, $detail['store_address'], 0, 0, 'L');

                $pdf->cell(65, 6, " ", 0, 0, 'L');
                $pdf->setFont ('times','',9);
                $pdf->cell(30, 5, $billing_period, 1, 0, 'C');
                $pdf->cell(30, 5, date('F j, Y',strtotime($collection_date)), 1, 0, 'C');
                $pdf->cell(30, 5, "P " . number_format($this->check_totalPayableForSOA($tenant['tenant_id'], $tenant['contract_no'], $tenant_type), 2), 1, 0, 'C');


                $pdf->ln();
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
                $pdf->Multicell(90, 4, $detail['contact_person'] . "\n" . "Phone: " . $detail['contact_no'] . "\n" . "E-mail: " . $detail['email'], 1, 'C');

                $pdf->ln();
                $pdf->SetTextColor(0, 0, 0);
            }


            foreach ($lessee_info as $data)
            {
                $rental_type  = $data['rental_type'];
                $tenancy_type = $data['tenancy_type'];
                $contract_no  = $data['contract_no'];
                $trade_name   = $data['trade_name'];
                $pdf->setFont ('times','B',8);
                $pdf->cell(25, 4, "Tenant ID ", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['tenant_id'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "SOA No.", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $soa_no, 0, 0, 'L');
                $pdf->ln();
                $pdf->cell(25, 4, "Contract No", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['contract_no'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Date", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . date('F j, Y',strtotime($this->_currentDate)), 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Trade Name", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['trade_name'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Location Code", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " . $data['location_code'], 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Corp Name", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $data['corporate_name'] , 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Floor Area", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $data['floor_area'] . " Square Meters", 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Address", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $data['address'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "Billing Period", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $billing_period, 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Rental Type", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $data['rental_type'], 0, 0, 'L');
                $pdf->cell(10, 4, "  ", 0, 0, 'L');
                $pdf->cell(25, 4, "TIN", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');
                $pdf->cell(60, 4, ":  " .  $data['tin'], 0, 0, 'L');

                $pdf->ln();

                $pdf->cell(25, 4, "Percentage Rate", 0, 0, 'L');
                $pdf->cell(2, 4, "  ", 0, 0, 'L');


                if ($data['rental_type'] == 'Fixed' )
                {
                    $pdf->cell(60, 4, ":  " .  "N/A", 0, 0, 'L');
                } else {
                    $pdf->cell(60, 4, ":  " .  $data['rent_percentage'] . "%", 0, 0, 'L');

                    $rent_percentage = $data['rent_percentage'];
                }
            }


            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, "Statement of Account", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont ('times','B',12);
            $pdf->cell(190, 6, "                                            DESCRIPTION                                                                    AMOUNT", 1, 0, 'L');
            $pdf->ln();



            //========== Previous Billing =========== //

            $previous_billing = $this->app_model->get_previousBilling($tenant['tenant_id'], $tenant['contract_no']);
            foreach ($previous_billing as $previous)
            {
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(100, 8, "Previous Billing Amount", 0, 0, 'L');
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->cell(20, 4, number_format($previous['amount_due'], 2), 0, 0, 'R');
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(100, 8, "Payment Received Amount - Thank you!", 0, 0, 'L');
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->cell(20, 4, number_format($previous['amount_paid'], 2), 0, 0, 'R');
                $pdf->ln();
            }




            // For Preoparation Charges
            $preop_data = $this->app_model->get_preopdata($tenant['tenant_id']);
            if ($preop_data)
            {
                $preop_total = 0;
                $pdf->cell(100, 8, "Preoparation Charges", 0, 0, 'L');
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();

                foreach ($preop_data as $preop)
                {
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, $preop['description'], 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($preop['amount'], 2), 0, 0, 'R');
                    $pdf->ln();

                    $preop_total += $preop['amount'];
                    $overall_amount += $preop['amount'];

                    $data = array(
                        'tenant_id'         => $tenant['tenant_id'],
                        'store_code'        => $store_code,
                        'invoice_id'        => $this->app_model->get_invoiceID($tenant['tenant_id'], $preop['doc_no'], $preop['description']),
                        'soa_no'            => $soa_no,
                        'collection_date'   => $collection_date,
                        'flag'              => $tenancy_type,
                        'status'            => $billing_period
                    );
                    $this->app_model->insert('soa', $data);
                }


                $pdf->ln();
                $pdf->setFont('times','',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(80, 4, "Total", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "P " . number_format($preop_total, 2), 0, 0, 'R');
                $pdf->ln();
            }


            $retro_data = $this->app_model->get_invoiceRetro($tenant['tenant_id']);
            if ($retro_data)
            {
                $pdf->cell(100, 8, "RETRO RENT", 0, 0, 'L');
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->setFont('times','',10);
                $pdf->ln();

                $pdf->setFont('times','',10);

                foreach ($retro_data as $retro)
                {
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                    $pdf->cell(40, 4, "P " . number_format($retro['actual_amt'], 2), 0, 0, 'R');
                    $pdf->ln();

                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                    $pdf->setFont('times','U',10);
                    $pdf->cell(40, 4, "P " . number_format($retro['amount_paid'], 2), 0, 0, 'R');
                    $pdf->ln();
                    $pdf->setFont('times','',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                    $pdf->setFont('times','B',10);
                    $pdf->cell(40, 4, "P " . number_format($retro['balance'], 2), 0, 0, 'R');

                    $overall_amount += $retro['balance'];

                    // ========== Save to SOA ======== //
                    $data = array(
                        'tenant_id'         => $tenant['tenant_id'],
                        'store_code'        => $store_code,
                        'invoice_id'        => $this->app_model->get_invoiceID($tenant['tenant_id'], $retro['doc_no'], 'Retro Rental'),
                        'soa_no'            => $soa_no,
                        'collection_date'   => $collection_date,
                        'flag'              => $tenancy_type,
                        'status'            => $billing_period
                    );

                    $this->app_model->insert('soa', $data);
                }
            }



            // =================== Receipt Charges Breakdown ============= //

            $due_date = $this->app_model->get_dueDate($tenant['tenant_id']);
            $latest_dueDate = $this->app_model->get_latestDueDate($due_date);
            for ($i=0; $i < count($due_date); $i++)
            {
                //===== Calculate due_date difference to determine the appropriate penalty ===== //
                $daylen      = 60*60*24;
                $daysDiff    = (strtotime($latest_dueDate) - strtotime($due_date[$i]['due_date']))/$daylen;
                $daysDiff    = $daysDiff / 28;
                $daysDiff    = floor($daysDiff);

                $current_due = strtotime($due_date[$i]['due_date'] . "-20 days");
                $pdf->setFont('times','B',10);

                $basic_rent           = $this->app_model->chargesDetails($tenant['tenant_id'], $due_date[$i]['due_date'], 'Basic/Monthly Rental');
                $rental_increment     = $this->app_model->chargesDetails($tenant['tenant_id'], $due_date[$i]['due_date'], 'Rent Incrementation');
                $discount             = $this->app_model->chargesDetails($tenant['tenant_id'], $due_date[$i]['due_date'], 'Discount');
                $basic                = $this->app_model->chargesDetails($tenant['tenant_id'], $due_date[$i]['due_date'], 'Basic');
                $other_charges        = $this->app_model->other_chargeDetails($tenant['tenant_id'], $due_date[$i]['due_date']);
                $total_perDuedate     = $this->app_model->previous_totalPerDueDate($tenant['tenant_id'], $due_date[$i]['due_date']);
                $total_payableDuedate = $this->app_model->total_payableDuedate($tenant['tenant_id'], $due_date[$i]['due_date']);
                $overall_amount       += $total_perDuedate;
                $pdf->ln();




                // Record Late payment penalty to SL and GL
                $invoiced_latepaymentpenalty = $this->app_model->get_invoicedlatepaymentpenalty($tenant['tenant_id']);
                if ($invoiced_latepaymentpenalty)
                {
                    foreach ($invoiced_latepaymentpenalty as $lp_penalty)
                    {
                        $data_latepaymentTable = array(
                            'soa'    =>  'Yes',
                            'soa_no' =>  $soa_no
                        );
                        $this->app_model->update($data_latepaymentTable, $lp_penalty['id'], 'tmp_latepaymentpenalty');
                    }
                }


                if ($i == count($due_date)-1)  // Check current due date
                {
                    $pdf->cell(100, 8, "CURRENT(" . date("F Y",$current_due) . ")", 0, 0, 'L');
                    foreach ($basic_rent as $value)
                    {
                        // ========== Save to SOA ======== //
                        $data = array(
                            'tenant_id'         => $tenant['tenant_id'],
                            'store_code'        => $store_code,
                            'invoice_id'        => $value['id'],
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );

                        $this->app_model->insert('soa', $data);

                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(30, 4, "Rental", 0, 0, 'L');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        if ($rental_type == 'Fixed')
                        {
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');
                            $pdf->cell(40, 4, "P " . number_format($value['basic_rental'], 2), 0, 0, 'R');
                        }
                        elseif ($rental_type == 'Percentage')
                        {
                            $pdf->setFont('times','',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                            $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');
                        }
                        elseif ($rental_type == 'Fixed Plus Percentage')
                        {
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');
                            $pdf->cell(40, 4, "P " . number_format($value['basic_rental'], 2), 0, 0, 'R');
                            $pdf->ln();
                            $pdf->setFont('times','',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                            $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');

                        }
                        elseif ($rental_type == 'Fixed/Percentage w/c Higher')
                        {
                            if ($value['expected_amt'] != $value['base_rental'] && ($value['status'] != 'Credited' && $value['status'] != 'Debited'))
                            {
                                $pdf->setFont('times','',10);
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, "Percentage Rent (P " . number_format($value['total_gross'], 2) . "X" . $rent_percentage . "%)", 0, 0, 'L');
                                $pdf->cell(40, 4, number_format(($value['total_gross'] * ($rent_percentage / 100)), 2), 0, 0, 'R');
                            }
                            else
                            {
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, 'Basic rent', 0, 0, 'L');
                                $pdf->cell(40, 4, "P " . number_format($value['expected'], 2), 0, 0, 'R');
                            }
                        }

                        $pdf->ln();

                        foreach ($rental_increment as $increment)
                        {
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, 'Rental Incrementation(' . $increment['unit_price'] . '%)', 0, 0, 'L');
                            $pdf->cell(40, 4, number_format($increment['expected_amt'], 2), 0, 0, 'R');
                        }

                        $pdf->ln();

                    } // End of Current Basic Payment


                    $counter = 0;
                    foreach ($discount as $value)
                    {
                        // ========== Save to SOA ======== //
                        if ($counter == 0)
                        {
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(30, 4, "Discount", 0, 0, 'L');
                            $pdf->setFont('times','',10);
                        }


                        $pdf->ln();
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                        $pdf->cell(40, 4, "-" . number_format($value['amount'], 2), 0, 0, 'R');

                        $data = array(
                            'tenant_id'         => $tenant['tenant_id'],
                            'store_code'        => $store_code,
                            'invoice_id'        => $value['id'],
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );

                        $this->app_model->insert('soa', $data);

                        $counter++;
                    }


                    $counter = 0;
                    foreach ($basic as $value)
                    {

                        if ($counter == 0)
                        {
                            $pdf->ln();
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(30, 4, "Basic", 0, 0, 'L');
                            $pdf->setFont('times','',10);

                        }

                        $pdf->ln();
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                        $pdf->cell(80, 4, $value['description'], 0, 0, 'L');

                        if ($value['description'] == 'Creditable Witholding Taxes')
                        {
                            $pdf->cell(40, 4, "-" . number_format($value['amount'], 2), 0, 0, 'R');
                        }
                        else
                        {
                            $pdf->cell(40, 4, number_format($value['amount'], 2), 0, 0, 'R');
                        }

                        // ========== Save to SOA ========= //
                        $data = array(
                            'tenant_id'         => $tenant['tenant_id'],
                            'store_code'        => $store_code,
                            'invoice_id'        => $value['id'],
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );
                        $this->app_model->insert('soa', $data);

                        $counter++;
                    }


                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Add:Other Charges", 0, 0, 'L');
                    $pdf->setFont('times','',10);

                    foreach ($other_charges as $value)
                    {
                        // ========== Save to SOA ======== //
                        $data = array(
                            'tenant_id'         => $tenant['tenant_id'],
                            'store_code'        => $store_code,
                            'invoice_id'        => $value['id'],
                            'soa_no'            => $soa_no,
                            'collection_date'   => $collection_date,
                            'flag'              => $tenancy_type,
                            'status'            => $billing_period
                        );

                        $this->app_model->insert('soa', $data);

                        if ((strtolower($value['description']) == 'water' || strtolower($value['description']) == 'electricity') && $value['status'] != 'Debited')
                        {
                            $pdf->ln();
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                            $pdf->ln();
                            $pdf->setFont('times','',8);
                            $pdf->cell(60, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "Present", 0, 0, 'L');
                            $pdf->cell(20, 4, "Previous", 0, 0, 'L');
                            $pdf->cell(20, 4, "Consumed", 0, 0, 'L');
                            $pdf->ln();
                            $pdf->cell(60, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, number_format($value['curr_reading'], 2), 0, 0, 'L');
                            $pdf->cell(20, 4, number_format($value['prev_reading'], 2), 0, 0, 'L');
                            $pdf->cell(20, 4, number_format($value['curr_reading'] - $value['prev_reading'], 2), 0, 0, 'L');
                            $pdf->cell(10, 4, " ", 0, 0, 'L');
                            $pdf->setFont('times','',10);
                            $pdf->cell(40, 4, number_format($value['balance'], 2), 0, 0, 'R');
                        }
                        else
                        {
                            $pdf->ln();
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, $value['description'], 0, 0, 'L');
                            $pdf->cell(40, 4, number_format($value['balance'], 2), 0, 0, 'R');

                            if ($value['charges_type'] == 'Pre Operation Charges')
                            {
                                $total_perDuedate  += $value['balance'];
                                $overall_amount = $total_perDuedate;
                            }
                        }
                    } // End of Current Other Charges




                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->cell(40, 4, "P " . number_format($total_perDuedate, 2), 0, 0, 'R');


                    $pdf->ln();
                    $pdf->ln();


                } // End of Current Due Date
                else
                {
                    // Previous Due Date

                    $previous_totalPerDueDate = $this->app_model->previous_totalPerDueDate($tenant['tenant_id'], $due_date[$i]['due_date']);

                    // $get_previousAmount    = $this->app_model->get_previousAmount($tenant_id ,$due_date[$i]);
                    $previous_totalPerDueDate = $this->app_model->previous_totalPerDueDate($tenant['tenant_id'], $due_date[$i]['due_date']);
                    $nopenalty_amount         = $this->app_model->previous_withNoPenalty($tenant['tenant_id'], $due_date[$i]['due_date']);


                    if ($previous_totalPerDueDate > 1)
                    {
                        $pdf->cell(100, 8, "PREVIOUS", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->cell(30, 4, date("F Y",$current_due), 0, 0, 'L');
                        $pdf->setFont('times','',10);
                        $pdf->ln();

                        foreach ($basic_rent as $value)
                        {
                            // ========== Save to SOA ======== //
                            $data = array(
                                'tenant_id'         => $tenant['tenant_id'],
                                'store_code'        => $store_code,
                                'invoice_id'        => $value['id'],
                                'soa_no'            => $soa_no,
                                'collection_date'   => $collection_date,
                                'flag'              => $tenancy_type,
                                'status'            => $billing_period
                            );

                            $this->app_model->insert('soa', $data);
                            $pdf->setFont('times','',10);

                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, "Previous Balance", 0, 0, 'L');
                            $pdf->cell(40, 4, "P " . number_format($total_payableDuedate - $nopenalty_amount, 2), 0, 0, 'R');
                            $pdf->ln();
                            if ($nopenalty_amount)
                            {
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, "No Penalty Charges", 0, 0, 'L');
                                $pdf->cell(40, 4, "P " . number_format($nopenalty_amount, 2), 0, 0, 'R');
                                $pdf->ln();
                            }
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, "Payment Received", 0, 0, 'L');
                            $pdf->setFont('times','U',10);
                            $pdf->cell(40, 4, number_format($total_payableDuedate - $previous_totalPerDueDate, 2), 0, 0, 'R');
                            $pdf->ln();
                            $pdf->setFont('times','',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->cell(80, 4, "Balance", 0, 0, 'L');
                            $pdf->cell(40, 4, number_format($previous_totalPerDueDate, 2), 0, 0, 'R');

                        }

                        foreach ($other_charges as $value)
                        {
                            // ========== Save to SOA ======== //
                            $data = array(
                                'tenant_id'         =>  $tenant['tenant_id'],
                                'store_code'        =>  $store_code,
                                'invoice_id'        =>  $value['id'],
                                'soa_no'            =>  $soa_no,
                                'collection_date'   =>  $collection_date,
                                'flag'              =>  $tenancy_type,
                                'status'            => $billing_period
                            );

                            $this->app_model->insert('soa', $data);
                        }


                        if ($daysDiff != 0)
                        {
                            $pdf->ln();
                            $pdf->setFont('times','B',10);
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(30, 4, "Penalty:", 0, 0, 'L');
                            $pdf->ln();
                            $pdf->cell(30, 4, "     ", 0, 0, 'L');
                            $pdf->cell(20, 4, "     ", 0, 0, 'L');
                            $pdf->setFont('times','',10);

                            $penalty                  = 0;
                            $percent_penalty          = 0;
                            $additional_penalty       = 0;
                            $penalty_carryOver        = 0;
                            $previous_totalPerDueDate -= $nopenalty_amount;
                            if ($daysDiff == 1)
                            {
                                $percent_penalty    = 2;
                                $pdf->cell(80, 4, number_format($previous_totalPerDueDate, 2) . " x 2% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+1 Month")) . ")", 0, 0, 'L');
                                $penalty            = $previous_totalPerDueDate * .02;
                                $additional_penalty = $penalty;
                                //$overall_amount += $penalty;
                                $pdf->cell(40, 4, number_format($penalty, 2), 0, 0, 'R');
                            }
                            elseif($daysDiff == 2)
                            {
                                $percent_penalty = 3;

                                $pdf->cell(80, 4, number_format($previous_totalPerDueDate, 2) .  " x 2% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+1 Month")) . ")", 0, 0, 'L');
                                $pdf->cell(40, 4, number_format($previous_totalPerDueDate * .02, 2), 0, 0, 'R');
                                $penalty_carryOver = $previous_totalPerDueDate * .02;
                                $pdf->ln();
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, number_format($previous_totalPerDueDate + $penalty_carryOver, 2) .  " x 3% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+2 Month")) . ")", 0, 0, 'L');
                                $pdf->cell(40, 4, number_format(($previous_totalPerDueDate + $penalty_carryOver) * .03, 2), 0, 0, 'R');
                                $penalty = ($previous_totalPerDueDate * .02) + (($previous_totalPerDueDate + + $penalty_carryOver) * .03);
                                $additional_penalty = ($previous_totalPerDueDate + $penalty_carryOver) * .03;
                                //$overall_amount += $penalty;
                            }
                            elseif ($daysDiff > 2)
                            {
                                $percent_penalty = 3;

                                $pdf->cell(80, 4, number_format($previous_totalPerDueDate, 2) . " x 2% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+1 Month")) . ")", 0, 0, 'L');
                                $pdf->cell(40, 4, number_format($previous_totalPerDueDate * .02, 2), 0, 0, 'R');
                                $penalty_carryOver = $previous_totalPerDueDate * .02;
                                $pdf->ln();
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                $pdf->cell(80, 4, number_format($previous_totalPerDueDate + $penalty_carryOver, 2) . " x 3% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+2 Month")) . ")", 0, 0, 'L');
                                $pdf->cell(40, 4, number_format(($previous_totalPerDueDate + $penalty_carryOver) * .03, 2), 0, 0, 'R');
                                $penalty = ($previous_totalPerDueDate * .02) + (($previous_totalPerDueDate + $penalty_carryOver) * .03);

                                $pdf->ln();
                                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                $pdf->cell(20, 4, "     ", 0, 0, 'L');

                                $penalty_carryOver += $penalty;

                                for ($month_count=3; $month_count <= $daysDiff; $month_count++)
                                {

                                    if ($month_count == $daysDiff)
                                    {
                                        $pdf->cell(80, 4, number_format($previous_totalPerDueDate + + $penalty_carryOver, 2) . " x 3% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+" . $month_count . " Month")) . ")", 0, 0, 'L');
                                        $pdf->cell(40, 4, number_format(($previous_totalPerDueDate + $penalty_carryOver) * .03, 2), 0, 0, 'R');
                                        $pdf->ln();
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $additional_penalty = ($previous_totalPerDueDate + $penalty_carryOver) * .03;
                                        $penalty            += $additional_penalty ;
                                    }
                                    else
                                    {
                                        $pdf->cell(80, 4, number_format($previous_totalPerDueDate + $penalty_carryOver, 2)  . " x 3% (" . date('F Y', strtotime(date('F Y', strtotime($due_date[$i]['due_date'])) . "+" . $month_count . " Month")) . ")", 0, 0, 'L');
                                        $pdf->cell(40, 4, number_format(($previous_totalPerDueDate + $penalty_carryOver) * .03, 2), 0, 0, 'R');
                                        $pdf->ln();
                                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                                        $pdf->cell(20, 4, "     ", 0, 0, 'L');
                                        $penalty           += ($previous_totalPerDueDate + $penalty_carryOver) * .03;
                                        $penalty_carryOver +=  ($previous_totalPerDueDate + $penalty_carryOver) * .03;
                                    }

                                    //$overall_amount += $penalty;
                                }
                            }


                            $penaltyEntry = array(
                                'posting_date'  =>  $this->_currentDate,
                                'document_type' =>  'Penalty',
                                'ref_no'        =>  $this->app_model->generate_refNo(),
                                'doc_no'        =>  $soa_no,
                                'tenant_id'     =>  $tenant['tenant_id'],
                                'contract_no'   =>  $contract_no,
                                'description'   =>  'Penalty-' . $trade_name,
                                'credit'        =>  $additional_penalty,
                                'balance'       =>  -1 * $additional_penalty,
                                'flag'          =>  'Penalty'
                            );

                            $this->app_model->insert('ledger', $penaltyEntry);

                            $ref_no = $this->app_model->gl_refNo();

                            $account_receivable = array(
                                'posting_date'      =>  $this->_currentDate,
                                'document_type'     =>  'SOA',
                                'ref_no'            =>  $ref_no,
                                'doc_no'            =>  $soa_no,
                                'tenant_id'         =>  $tenant['tenant_id'],
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  $additional_penalty,
                                'tag'               =>  'Penalty',
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $account_receivable);
                            $this->app_model->insert('subsidiary_ledger', $account_receivable);

                            $gl_penalty = array(
                                'posting_date'      =>  $this->_currentDate,
                                'document_type'     =>  'SOA',
                                'ref_no'            =>  $ref_no,
                                'doc_no'            =>  $soa_no,
                                'tenant_id'         =>  $tenant['tenant_id'],
                                'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $additional_penalty,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );

                            $this->app_model->insert('general_ledger', $gl_penalty);
                            $this->app_model->insert('subsidiary_ledger', $gl_penalty);


                            // ============ Insert Into monthly_penalty table ============ //
                            $data = array(
                                'tenant_id'         =>      $tenant['tenant_id'],
                                'percent'           =>      $percent_penalty,
                                'due_date'          =>      $current_due,
                                'doc_no'            =>      $soa_no,
                                'collection_date'   =>      $collection_date,
                                'soa_no'            =>      $soa_no,
                                'amount'            =>      $additional_penalty,
                                'balance'           =>      $additional_penalty
                            );

                            $this->app_model->insert('monthly_penalty', $data);

                             // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant['tenant_id'],
                                'doc_no'        =>  $soa_no,
                                'posting_date'  =>  $this->_currentDate,
                                'description'   =>  'Penalty',
                                'amount'        =>  $additional_penalty
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);



                        }

                        $pdf->ln();
                        $pdf->ln();
                        $pdf->ln();
                        $pdf->setFont('times','B',10);
                        $pdf->setFont('times','', '10');
                        $pdf->cell(30, 4, "     ", 0, 0, 'L');
                        $pdf->setFont('times','B',10);
                        $pdf->cell(100, 4, "Total Amount", 0, 0, 'L');
                        $overall_amount += $penalty;

                        $pdf->cell(40, 4, "P " . number_format($previous_totalPerDueDate + $penalty + $nopenalty_amount, 2), 0, 0, 'R');

                        $pdf->ln();
                        $pdf->ln();

                    }

                } // End of Previous Due Date
            } //End of Due Date Loop


            // ==== If has advance payment this section is the manipulation of data in tenant ledger ==== //

                $total_deducted = 0; //Total deducted in the advance payment
                if ($advance_amount > 0)
                {

                    $invoicedDocNo = $this->app_model->get_invoicedDocNo($tenant['tenant_id']);
                    foreach($invoicedDocNo as $doc_no)
                    {
                        $ledger_item         = $this->app_model->get_ledgerEntry($tenant['tenant_id'], $doc_no['doc_no']);
                        $is_advanceDeduction = TRUE;
                        foreach ($ledger_item as $item)
                        {
                            if ($advance_amount > 0)
                            {
                                $credit;
                                $balance;

                                if ($advance_amount >= $item['balance'])
                                {
                                    $credit  = $item['balance'];
                                    $balance = 0;
                                }
                                else
                                {
                                    $credit  = $advance_amount;
                                    $balance = abs($item['balance'] - $advance_amount);
                                }

                                $entryData = array(

                                    'posting_date'  =>  $this->_currentDate,
                                    'document_type' =>  'SOA',
                                    'ref_no'        =>  $item['ref_no'],
                                    'doc_no'        =>  $soa_no,
                                    'tenant_id'     =>  $tenant['tenant_id'],
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $item['description'],
                                    'debit'         =>  $credit,
                                    'balance'       =>  $balance
                                );

                                $total_deducted += $item['balance'];
                                $this->app_model->insert('ledger', $entryData);
                                $advance_amount -= $item['balance'];
                            }
                        }
                    }
                }


                // ======= Close the advance payment in tenant ledger ====== //

                if ($SL_advanceAmount > 0 && $is_advanceDeduction)
                {
                    $advances = $this->app_model->get_advancePayment($tenant['tenant_id'], $contract_no);
                    foreach ($advances as $advance)
                    {
                        if ($advance['balance'] > 0)
                        {
                            if ($total_deducted >= $advance['balance'])
                            {
                                // ENTRY TO TENANT LEDGER
                                $entryData = array(
                                    'posting_date'  =>  $this->_currentDate,
                                    'document_type' =>  'SOA',
                                    'ref_no'        =>  $advance['ref_no'],
                                    'doc_no'        =>  $soa_no,
                                    'tenant_id'     =>  $tenant['tenant_id'],
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $advance['description'],
                                    'credit'        =>  $advance['balance'],
                                    'balance'       =>  0
                                );
                                $this->app_model->insert('ledger', $entryData);

                                $orginal_advancePayment -= $advance['balance'];

                            }
                            else
                            {
                                $entryData = array(
                                    'posting_date'  =>  $this->_currentDate,
                                    'document_type' =>  'SOA',
                                    'ref_no'        =>  $advance['ref_no'],
                                    'doc_no'        =>  $soa_no,
                                    'tenant_id'     =>  $tenant['tenant_id'],
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $advance['description'],
                                    'credit'        =>  $total_deducted,
                                    'balance'       =>  abs($advance['balance'] - $total_deducted)
                                );
                                $this->app_model->insert('ledger', $entryData);
                            }
                        }
                    }
                }

            //===== Advance payment to General Ledger ===== //

            if ($GL_advanceAmount > 0)
            {
                $invoicedDocNo = $this->app_model->get_invoicedDocNo($tenant['tenant_id']);
                foreach ($invoicedDocNo as $doc_no)
                {
                    $gl_entries =  $this->app_model->gl_entries($tenant['tenant_id'], $doc_no['doc_no']);
                    foreach ($gl_entries as $gl_entry)
                    {
                        if ($gl_entry['tag'] == 'Other') // AR
                        {
                            $gl_advances = $this->app_model->gl_advancePayment($tenant['tenant_id']);
                            foreach ($gl_advances as $gl_advance)
                            {
                                if ($gl_advance['amount'] > 0)
                                {
                                    if ($gl_entry['balance'] >= $gl_advance['amount'])
                                    {
                                        $advance_URI = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_advance['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gl_advance['amount'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $advance_URI);
                                        $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                        $AR = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_entry['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_advance['amount'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $AR);
                                        $this->app_model->insert('subsidiary_ledger', $AR);

                                    }
                                    else
                                    {
                                        $advance_URI = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_advance['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gl_entry['balance'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $advance_URI);
                                        $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                        $AR = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_entry['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_entry['balance'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $AR);
                                        $this->app_model->insert('subsidiary_ledger', $AR);
                                    }
                                }
                            }
                        }
                        else // RR
                        {
                            $gl_advances = $this->app_model->gl_advancePayment($tenant['tenant_id']);
                            foreach ($gl_advances as $gl_advance)
                            {
                                if ($gl_advance['amount'] > 0)
                                {
                                    if ($gl_entry['balance'] >= $gl_advance['amount'])
                                    {
                                        $advance_URI = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_advance['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gl_advance['amount'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $advance_URI);
                                        $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                        $RR = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_entry['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_advance['amount'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $RR);
                                        $this->app_model->insert('subsidiary_ledger', $RR);
                                    }
                                    else
                                    {
                                        $advance_URI = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_advance['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gl_entry['balance'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $advance_URI);
                                        $this->app_model->insert('subsidiary_ledger', $advance_URI);

                                        $RR = array(
                                            'posting_date'      =>  $this->_currentDate,
                                            'document_type'     =>  'SOA',
                                            'ref_no'            =>  $gl_entry['ref_no'],
                                            'doc_no'            =>  $soa_no,
                                            'tenant_id'         =>  $tenant['tenant_id'],
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_entry['balance'],
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $RR);
                                        $this->app_model->insert('subsidiary_ledger', $RR);
                                    }
                                }
                            }
                        }
                    }
                }
            }


            // If has waived penalties
            $waived_penalty = $this->app_model->get_waivedPenalty($tenant['tenant_id'], $this->app_model->get_oledstDueDate($due_date), $this->app_model->get_latestDueDate($due_date));
            if ($waived_penalty > 1)
            {
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 4, "     ", 0, 0, 'L');
                $pdf->cell(100, 4, "Waived Penalty", 0, 0, 'L');
                $pdf->setFont('times','B',10);
                $pdf->cell(40, 4, "-" . number_format($waived_penalty, 2), 0, 0, 'R');
                $overall_amount = $overall_amount - $waived_penalty;
            }


            // To show advance amount to SOA
            if ($tenant_advances)
            {

                $amount_to_show = 0;
                foreach ($tenant_advances as $amount)
                {
                    $amount_to_show += $amount['advance'];
                }


                if ($amount_to_show > 0)
                {
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 4, "     ", 0, 0, 'L');
                    $pdf->cell(100, 4, "Advance Payment"  . " (" . $advance_date . ")", 0, 0, 'L');
                    $pdf->setFont('times','',10);
                    $pdf->cell(40, 4, "P " . number_format($amount_to_show, 2), 0, 0, 'R');

                    $overall_amount -= $preop_total; // Exclude preop deduction for advance rent
                    $overall_amount = $overall_amount - $amount_to_show;

                    if ($overall_amount < 0)
                    {
                        $overall_amount = $preop_total;
                    }
                    else
                    {
                        $overall_amount += $preop_total;
                    }
                }

            }


            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(100, 4, "Total Amount Due", 0, 0, 'L');
            $pdf->setFont('times','BU',10);
            if ($overall_amount > 0)
            {
                $pdf->cell(40, 4, "P " . number_format($overall_amount, 2), 0, 0, 'R');
            }
            else
            {
                $pdf->cell(40, 4, "P 0.00", 0, 0, 'R');
            }

            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(0, 4, "Certified: _____________________      ______________________     _______________________", 0, 0, 'R');
            $pdf->ln();
            $pdf->setFont('times','',8);
            $pdf->cell(50, 4, "     ", 0, 0, 'L');
            $pdf->cell(0, 4, "                               Mall Auditor                             Leasing Account In-charge                               Prepared By", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);

            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(190, 4,"         ", 1, 0, 'L', TRUE);
            $pdf->ln();
            $pdf->setFont('times','B',8);
            $pdf->cell(0, 4, "Note: Presentation of this statement is sufficient notice that the account is due. Interest of 3% will be charged for all past due accounts.", 0, 0, 'L');

            $data = array(
                'tenant_id'         =>  $tenant['tenant_id'],
                'file_name'         =>  $file_name,
                'soa_no'            =>  $soa_no,
                'billing_period'    =>  $billing_period
            );

            $this->app_model->insert('soa_file', $data);

            $pdf->AddPage();
        }



        $this->db->trans_complete(); // End of transaction function
        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
        {
            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'Generating Batch SOA', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
            $response['msg'] = 'DB_error';
        }
        else
        {
            $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
            $pdf->Output('assets/pdf/' . $file_name , 'F');
            $response['msg'] = "Success";
        }

        echo json_encode($response);
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function save_preopPayment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $response         = array();
        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();

        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
        $receipt_no       = $this->app_model->payment_docNo();
        $soa_no           = $this->sanitize($this->input->post('soa_no'));
        $billing_period   = $this->sanitize($this->input->post('billing_period'));
        $date             = $this->sanitize($this->input->post('curr_date'));
        $remarks          = $this->sanitize($this->input->post('remarks'));
        $total_payable    = $this->sanitize($this->input->post('total'));
        $total_payable    = str_replace(",", "", $total_payable);
        $payor            = $trade_name;
        $payee            = $this->sanitize($this->input->post('payee'));
        $tender_typeDesc  = $this->sanitize($this->input->post('tender_typeDesc'));
        $file_name        =  $tenant_id . $timeStamp . '.pdf';

        $charge_id        = $this->input->post('charge_id');
        $description      = $this->input->post('desc');
        $doc_no           = $this->input->post('doc_no');
        $retro_doc_no     = $this->input->post('retro_doc_no');
        $due_date         = $this->input->post('due_date');
        $posting_date     = $this->input->post('posting_date');
        $amount           = $this->input->post('amount');
        $balance          = $this->input->post('balance');
        $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('tender_amount')));
        $amount_paid_for_invoice = $amount_paid;
        $gl_amountPaid           = $amount_paid;
        $gl_retro_amountPaid     = $amount_paid;
        $sl_retro_amountPaid     = $amount_paid;
        $sl_amountPaid           = $amount_paid;

        if ($amount_paid > 0)
        {
            $this->db->trans_start(); // Transaction function starts here!!!

            // ============================ PDF =============================== //

            $store_code      = $this->app_model->tenant_storeCode($tenant_id);
            $store_details   = $this->app_model->store_details(trim($store_code));
            $details_soa     = $this->app_model->details_soa($tenant_id);
            $lessee_info     = $this->app_model->get_lesseeInfo($tenant_id, $contract_no);
            $collection_date = $this->app_model->get_collectionDate($soa_no);
            $daysOfMonth     = date('t', strtotime($date));

            $pdf = new FPDF('p','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');
            $logoPath = getcwd() . '/assets/other_img/';

            // ==================== Receipt Header ================== //

            foreach ($store_details as $detail)
            {
               $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
               $pdf->ln();
               $pdf->setFont ('times','B',14);
               $pdf->cell(75, 6, " ", 0, 0, 'L');
               $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
               $store_name = $detail['store_name'];
               $pdf->SetTextColor(0, 0, 0);
               $pdf->SetFillColor(35, 35, 35);
               $pdf->cell(35, 6, " ", 0, 0, 'L');
               $pdf->ln();
               $pdf->setFont ('times','',14);
               $pdf->cell(15, 0, " ", 0, 0, 'L');
               $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

               $pdf->ln();
               $pdf->ln();
            }

            foreach ($details_soa as $detail)
            {
                $pdf->setFont('times','',10);
                $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Soa No.", 0, 0, 'L');
                $pdf->cell(60, 6, $soa_no, 1, 0, 'L');

                $pdf->ln();
                $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Date", 0, 0, 'L');
                $pdf->cell(60, 6, $date, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "TIN", 0, 0, 'L');
                $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');

                $pdf->ln();
                $pdf->ln();
            }

            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            // =================== Receipt Charges Table ============= //
            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
            $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
            $pdf->cell(30, 8, "Charges Type", 0, 0, 'C');
            $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
            $pdf->setFont('times','',10);





            $sl_entryForPreop = $this->app_model->get_slentryForPreop($tender_typeDesc, $tenant_id);
            foreach ($sl_entryForPreop as $preop)
            {
                if ($sl_amountPaid > 0)
                {
                    $penaltyDetails = $this->app_model->get_penalty($tenant_id, $contract_no);
                    foreach ($penaltyDetails as $penalty)
                    {
                        if ($penalty['balance'] > 0 && $sl_amountPaid > 0)
                        {
                            $credit = 0;
                            $balance = 0;
                            if ($sl_amountPaid >= $penalty['balance'])
                            {
                                $credit = $penalty['balance'];
                                $balance = 0;
                            }
                            else
                            {
                                $credit = $sl_amountPaid;
                                $balance = abs($penalty['balance'] - $sl_amountPaid);
                            }

                            $entryData = array(
                                'posting_date'  =>  $date,
                                'document_type' =>  'Payment',
                                'ref_no'        =>  $penalty['ref_no'],
                                'doc_no'        =>  $receipt_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $penalty['description'],
                                'debit'         =>  $credit,
                                'balance'       =>  $balance,
                                'due_date'      =>  $penalty['due_date']
                            );

                            $this->app_model->insert('ledger', $entryData);

                            $reverseEntry = array(
                                'posting_date'  =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type' =>  'Payment',
                                'ref_no'        =>  $preop['ref_no'],
                                'doc_no'        =>  $receipt_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $tender_typeDesc . "-"  . " Deduction",
                                'credit'        =>  $credit,
                                'balance'       =>  $balance

                            );
                            $this->app_model->insert('ledger', $reverseEntry);
                            $sl_amountPaid -= $credit;
                        }
                    }
                }
            }





            $gl_penalties = $this->app_model->get_glPenalties($tenant_id);

            foreach ($gl_penalties as $penalty)
            {
                $penalty_amount = $penalty['balance'];
                if ($gl_amountPaid > 0)
                {
                    $gl_entryForPreop = $this->app_model->get_glentryForPreop($tender_typeDesc, $tenant_id);
                    foreach ($gl_entryForPreop as $preop)
                    {
                        if ($penalty_amount > 0 && $gl_amountPaid > 0)
                        {
                            $credit = 0;
                            if ($gl_amountPaid >= $penalty['balance'])
                            {
                                $credit = $penalty['balance'];
                            }
                            else
                            {
                                $credit = $gl_amountPaid;
                            }

                            $reverseEntry = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $preop['ref_no'],
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($preop['gl_code']),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  $credit,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $reverseEntry);
                            $this->app_model->insert('subsidiary_ledger', $reverseEntry);

                            $credit_Receivable = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $penalty['ref_no'],
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $penalty['gl_accountID'],
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $credit,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $credit_Receivable);
                            $this->app_model->insert('subsidiary_ledger', $credit_Receivable);

                            $gl_amountPaid -= $credit;
                            $penalty_amount -= $credit;

                        }
                    }
                }
            }



            $doc_no = $this->sort_ascending($doc_no);
            if ($doc_no)
            {
                for ($i=0; $i < count($doc_no); $i++)
                {
                    $chargesDetails = $this->app_model->gl_chargesDetails($tenant_id, $doc_no[$i]);
                    foreach ($chargesDetails as $detail)
                    {
                        $pdf->ln();
                        $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                        $pdf->cell(30, 8, $doc_no[$i], 0, 0, 'C');

                        if ($detail['tag'] == 'Basic Rent')
                        {
                            $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                        }
                        elseif ($detail['tag'] == 'Other')
                        {
                            $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                        }
                        elseif ($detail['tag'] == 'Penalty')
                        {
                            $pdf->cell(30, 8, "Penalty-" . $trade_name, 0, 0, 'C');
                        }
                        $pdf->cell(30, 8, $detail['posting_date'], 0, 0, 'C');
                        $pdf->cell(30, 8, $detail['due_date'], 0, 0, 'C');
                        $pdf->cell(30, 8, number_format($detail['balance'], 2), 0, 0, 'R');

                        $gl_entryForPreop = $this->app_model->get_glentryForPreop($tender_typeDesc, $tenant_id);
                        foreach ($gl_entryForPreop as $preop)
                        {
                            if ($detail['balance'] > 0 && $gl_amountPaid > 0)
                            {
                                $credit;
                                if ($gl_amountPaid >= $detail['balance'])
                                {
                                    $credit = $detail['balance'];
                                }
                                else
                                {
                                    $credit = $gl_amountPaid;
                                }

                                $reverseEntry = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $preop['ref_no'],
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($preop['gl_code']),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $credit,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );
                                $this->app_model->insert('general_ledger', $reverseEntry);
                                $this->app_model->insert('subsidiary_ledger', $reverseEntry);

                                $credit_Receivable = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $detail['ref_no'],
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $detail['gl_accountID'],
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $credit,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );
                                $this->app_model->insert('general_ledger', $credit_Receivable);
                                $this->app_model->insert('subsidiary_ledger', $credit_Receivable);

                                $detail['balance'] -= $preop['amount'];
                                $gl_amountPaid -= $credit;
                            }
                        }
                    }
                }

            // =========================================================================== //

               // ============== Add Entry to Ledger ============== //

                //Todo Ledger
                $doc_no = $this->sort_ascending($doc_no);

                for ($i=0; $i < count($doc_no); $i++)
                {
                    if ($doc_no[$i] != "")
                    {
                        $ledger_item = $this->app_model->get_ledgerEntry($tenant_id, $doc_no[$i]);

                        foreach ($ledger_item as $item)
                        {
                            $credit;
                            $balance;
                            $sl_entryForPreop = $this->app_model->get_slentryForPreop($tender_typeDesc, $tenant_id);
                            foreach ($sl_entryForPreop as $preop)
                            {
                                if ($item['balance'] > 0 && $sl_amountPaid > 0)
                                {
                                    $expanded_taxes = $this->app_model->get_expandedTaxes($tenant_id, $doc_no[$i]);
                                    foreach ($expanded_taxes as $value) // Add the Expanded Withholding Tax amount to sledger_amountPaid(Binogo ni)
                                    {
                                        $preop['amount'] += $value['debit'];
                                        $this->app_model->delete('ledger', 'id', $value['id']);
                                    }

                                    if ($sl_amountPaid >= $item['balance'])
                                    {
                                        $credit  = $item['balance'];
                                        $balance = 0;
                                    }
                                    else
                                    {
                                        $credit  = $sl_amountPaid;
                                        $balance = abs($item['balance'] - $sl_amountPaid);
                                    }

                                    $entryData = array(
                                        'posting_date'  =>  $date,
                                        'document_type' =>  'Payment',
                                        'ref_no'        =>  $item['ref_no'],
                                        'doc_no'        =>  $receipt_no,
                                        'tenant_id'     =>  $tenant_id,
                                        'contract_no'   =>  $contract_no,
                                        'description'   =>  $item['description'],
                                        'debit'         =>  $credit,
                                        'balance'       =>  $balance,
                                        'due_date'      =>  $item['due_date']
                                    );

                                    $this->app_model->insert('ledger', $entryData);

                                    $reverseEntry = array(
                                        'posting_date'  =>  $date,
                                        'document_type' =>  'Payment',
                                        'ref_no'        =>  $preop['ref_no'],
                                        'doc_no'        =>  $receipt_no,
                                        'tenant_id'     =>  $tenant_id,
                                        'contract_no'   =>  $contract_no,
                                        'description'   =>  $tender_typeDesc . "-" . $item['charges_type'] . " Deduction",
                                        'credit'        =>  $credit,
                                        'balance'       =>  $balance

                                    );
                                    $this->app_model->insert('ledger', $reverseEntry);
                                    $item['balance'] -= $preop['amount'];
                                    $sl_amountPaid -= $credit;
                                }
                            }
                        }
                    }
                }
            }

            if ($doc_no)
            {
                // ===== Charges deduction in invoicing table ====== //
                $doc_no = $this->sort_ascending($doc_no);
                for ($i=0; $i < count($doc_no); $i++)
                {
                    if ($doc_no[$i] != "")
                    {
                        if ($amount_paid_for_invoice > 0) // Check if Amount Paid has value
                        {
                            $docNo_total = $this->app_model->total_perDocNo($tenant_id, $doc_no[$i]);
                            $amount_paid_for_invoice -= $docNo_total;
                            if ($amount_paid_for_invoice >= 0)
                            {
                                $balance = 0;
                                $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                            }
                            else
                            {
                                $balance = abs($amount_paid_for_invoice);
                                $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                            }
                        }
                    }
                }
            }



            if ($retro_doc_no)
            {
                $retro_charges = $this->app_model->get_glRetroPayment($tenant_id);

                foreach ($retro_charges as $retro)
                {
                    $pdf->ln();
                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                    $pdf->cell(30, 8, $retro['doc_no'], 0, 0, 'C');
                    $pdf->cell(30, 8, $retro['tag'], 0, 0, 'C');
                    $pdf->cell(30, 8, $retro['posting_date'], 0, 0, 'C');
                    $pdf->cell(30, 8, $retro['due_date'], 0, 0, 'C');
                    $pdf->cell(30, 8, number_format($retro['balance'], 2), 0, 0, 'R');

                    $gl_entryForPreop = $this->app_model->get_glentryForPreop($tender_typeDesc, $tenant_id);

                    foreach ($gl_entryForPreop as $preop)
                    {
                        if ($retro['balance'] > 0 && $gl_retro_amountPaid > 0)
                        {
                            $credit;
                            if ($gl_retro_amountPaid >= $retro['balance'])
                            {
                                $credit = $retro['balance'];
                            }
                            else
                            {
                                $credit = $gl_retro_amountPaid;
                            }

                            $reverseEntry = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $preop['ref_no'],
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($preop['gl_code']),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  $credit,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $reverseEntry);
                            $this->app_model->insert('subsidiary_ledger', $reverseEntry);

                            $credit_Receivable = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $retro['ref_no'],
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $retro['gl_accountID'],
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $credit,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );
                            $this->app_model->insert('general_ledger', $credit_Receivable);
                            $this->app_model->insert('subsidiary_ledger', $credit_Receivable);

                            $retro['balance'] -= $preop['amount'];
                            $gl_retro_amountPaid -= $credit;

                        }
                    }
                }

                // to LEDGER
                $retro_ledger = $this->app_model->get_invoiceRetro($tenant_id);

                foreach ($retro_ledger as $retro)
                {
                    $credit;
                    $balance;
                    $sl_entryForPreop = $this->app_model->get_slentryForPreop($tender_typeDesc, $tenant_id);
                    foreach ($sl_entryForPreop as $preop)
                    {
                        if ($retro['balance'] > 0 && $sl_retro_amountPaid > 0)
                        {
                            if ($sl_retro_amountPaid >= $retro['balance'])
                            {
                                $credit = $retro['balance'];
                                $balance = 0;
                            }
                            else
                            {
                                $credit = $sl_retro_amountPaid;
                                $balance = abs($retro['balance'] - $preop['amount']);
                            }

                            $entryData = array(
                                'posting_date'  =>  $date,
                                'document_type' =>  'Payment',
                                'ref_no'        =>  $retro['ref_no'],
                                'doc_no'        =>  $receipt_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $retro['description'],
                                'debit'         =>  $credit,
                                'balance'       =>  $balance,
                                'due_date'      =>  $retro['due_date']
                            );

                            $this->app_model->insert('ledger', $entryData);

                            $reverseEntry = array(
                                'posting_date'  =>  $date,
                                'document_type' =>  'Payment',
                                'ref_no'        =>  $preop['ref_no'],
                                'doc_no'        =>  $receipt_no,
                                'tenant_id'     =>  $tenant_id,
                                'contract_no'   =>  $contract_no,
                                'description'   =>  $tender_typeDesc . "-" . $item['charges_type'] . " Deduction",
                                'credit'        =>  $credit,
                                'balance'       =>  $balance

                            );
                            $this->app_model->insert('ledger', $reverseEntry);

                            $retro['balance'] -= $preop['amount'];
                            $sl_retro_amountPaid -= $credit;
                        }
                    }
                }
            }




            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
            $pdf->cell(100, 8, "Payment Date:" . $date, 0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
            $pdf->cell(30, 4, $tender_typeDesc, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
            $pdf->cell(30, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
            $pdf->ln();

            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
            $pdf->cell(30, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
            $pdf->cell(30, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
            $pdf->cell(30, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
            if($amount_paid - $total_payable >= 0)
            {
                $pdf->cell(30, 4, "P " . "0.00", 0, 0, 'L');
            }
            else
            {
                $pdf->cell(30, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');
            }
            $pdf->ln();
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
            $pdf->cell(30, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
            $pdf->cell(30, 4, "P " . '0.00', 0, 0, 'L');

            $pdf->ln();
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
            $pdf->cell(30, 4, $trade_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
            $pdf->cell(30, 4, $payee, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(30, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Document #: ", 0, 0, 'L');
            $pdf->cell(30, 4, $receipt_no, 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   '',
                'billing_period'   =>   $billing_period,
                'tender_typeDesc'  =>   $tender_typeDesc,
                'soa_no'           =>   $soa_no,
                'amount_due'       =>   $total_payable,
                'amount_paid'      =>   $amount_paid,
                'bank'             =>   '',
                'check_no'         =>   '',
                'check_date'       =>   '',
                'payor'            =>   $trade_name,
                'payee'            =>   $payee,
                'receipt_doc'      =>   $file_name
            );

            $this->app_model->insert('payment_scheme', $paymentScheme);

            $paymentData = array(
                'posting_date' =>   $date,
                'soa_no'       =>   $soa_no,
                'amount_paid'  =>   $amount_paid,
                'tenant_id'    =>   $tenant_id,
                'doc_no'       =>   $receipt_no
            );

            $this->app_model->insert('payment', $paymentData);

            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');

            $this->db->trans_complete(); // End of transaction function
            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $error = array('action' => 'Saving Payment', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                $this->app_model->insert('error_log', $error);
                $response['msg'] = 'DB_error';
            }
            else
            {
                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }
        }
        else
        {
            $response['msg'] = 'No Amount';
        }

        echo json_encode($response);
    }
}


public function generate_soaCredentials()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        // Format JSON POST
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $jsonstring = file_get_contents ( 'php://input' );
        $arr        = json_decode($jsonstring,true);


        $trade_name = $arr["trade_name"];
        $result     = $this->app_model->generate_soaCredentials($trade_name);
        echo json_encode($result);
    }
}

public function get_invoiceBasic()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_invoiceBasic($tenant_id);
        echo json_encode($result);
    }
}


public function get_invoiceRetro()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_invoiceRetro($tenant_id);
        echo json_encode($result);
    }
}

public function get_invoiceOther()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_invoiceOther($tenant_id);
        echo json_encode($result);
    }
}


public function get_invoiceOtherCharges()
{
    $tenant_id = $this->uri->segment(3);
    $result    = $this->app_model->get_invoiceOtherCharges($tenant_id);
    echo json_encode($result);
}


public function get_tenantpreopCharges()
{
    $tenant_id = $this->uri->segment(3);
    $result    = $this->app_model->get_tenantpreopCharges($tenant_id);
    echo json_encode($result);
}

public function reprint_soa()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['flashdata']      = $this->session->flashdata('message');
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/reprint_soa');
        $this->load->view('leasing/footer');
    } else {
        redirect('ctrl_leasing/');
    }
}


public function get_tenantSoa()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        // Format JSON POST
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $jsonstring = file_get_contents ( 'php://input' );
        $arr        = json_decode($jsonstring,true);


        $trade_name = $arr["param"];
        $result     = $this->app_model->get_tenantSoa($trade_name);
        echo json_encode($result);
    }
}


public function cancel_soa()
{
    if ($this->session->userdata('leasing_logged_in'))
    {

        $soa_no          = $this->uri->segment(3);
        $collection_date = $this->uri->segment(4);
        $var             = 1;
        if ($this->app_model->check_SOACancellation($soa_no, $collection_date))
        {

            $this->app_model->delete('ledger', 'doc_no', $soa_no);
            $this->app_model->delete('general_ledger', 'doc_no', $soa_no);
            $this->app_model->delete('subsidiary_ledger', 'doc_no', $soa_no);
            $this->app_model->delete('soa', 'soa_no', $soa_no);
            $this->app_model->delete('soa_file', 'soa_no', $soa_no);
            $this->app_model->delete('monthly_receivable_report', 'doc_no', $soa_no);
            $this->app_model->cancel_latepaymentSOA($soa_no);
            $this->session->set_flashdata('message', 'Deleted');
            redirect('Leasing_transaction/reprint_soa');
        }
        else
        {
            $this->session->set_flashdata('message', 'SOA cannot be deleted');
            redirect('Leasing_transaction/reprint_soa');
        }


    }
}

public function payment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Supervisor')
        {
            $data['flashdata']      = $this->session->flashdata('message');
            // $data['recepit_no']  = $this->app_model->generate_receiptNo();
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['payee']          = $this->app_model->my_store();
            $data['store_id']       = $this->session->userdata('user_group');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/payment');
            $this->load->view('leasing/footer');
        } else {
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            $this->session->set_flashdata('message', 'Restriction');
            redirect($prev_url);
        }
    } else {
        redirect('ctrl_leasing/');
    }
}


public function unreg_fundTransfer()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $data['trans_no']       = $this->app_model->generate_UTFTransactionNo(FALSE);
        $data['current_date']   = $this->_currentDate;
        $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
        $data['flashdata']      = $this->session->flashdata('message');
        $this->load->view('leasing/header', $data);
        $this->load->view('leasing/unreg_fundTransfer');
        $this->load->view('leasing/footer');
    }
    else
    {
        redirect('ctrl_leasing/');
    }
}


public function get_bankName()
{
    $bank_code = str_replace("%20", " ", $this->uri->segment(3));
    $result = $this->app_model->get_bankName($bank_code);
    echo json_encode($result);

}

public function get_paymentReceipt()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $result = $this->app_model->generate_receiptNo();
        echo json_encode($result);
    }
}


public function get_unCloseRentDue()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_unCloseRentDue($tenant_id);
        echo json_encode($result);
    }
}

public function get_paymentBasic()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_paymentBasic($tenant_id);
        echo json_encode($result);
    }
}


public function get_glBasicPayment()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_glBasicPayment($tenant_id);
        echo json_encode($result);
    }
}


public function get_glRetroPayment()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_glRetroPayment($tenant_id);
        echo json_encode($result);
    }
}

public function get_glOtherPayment()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_glOtherPayment($tenant_id);
        echo json_encode($result);
    }
}


public function get_advanceDeposit()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_advanceDeposit($tenant_id);
        echo json_encode($result);
    }
}

public function get_paymentOther()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_paymentOther($tenant_id);
        echo json_encode($result);
    }
}

public function get_penalty()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        $tenant_id   = $this->uri->segment(3);
        $contract_no = $this->uri->segment(4);
        $result      = $this->app_model->get_penalty($tenant_id, $contract_no);
        echo json_encode($result);
    }
}

public function get_soaDocs()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
    {
        // Format JSON POST
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $jsonstring = file_get_contents ( 'php://input' );
        $arr        = json_decode($jsonstring,true);


        $trade_name = $arr["trade_name"];
        $result     = $this->app_model->get_soaDocs($trade_name);
        echo json_encode($result);
    } else {
        redirect('ctrl_leasing/');
    }
}



public function generate_primaryDetails()
{
    if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in') || $this->session->userdata('cfs_logged_in'))
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $jsonstring = file_get_contents ( 'php://input' );
        $arr        = json_decode($jsonstring,true);


        $trade_name = $arr["trade_name"];
        $result     = $this->app_model->generate_primaryDetails($trade_name);
        echo json_encode($result);
    } else {
        redirect('ctrl_leasing/');
    }
}


public function get_unclearedPayment()
{
    if ($this->session->userdata('leasing_logged_in'))
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_unclearedPayment($tenant_id);
        echo json_encode($result);
        }
    }


    public function get_internalUnclearedPayment()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in') || $this->session->userdata('cfs_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result    = $this->app_model->get_internalUnclearedPayment($tenant_id);
            echo json_encode($result);
        }
    }

    public function get_unclearedAdvance()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result    = $this->app_model->get_unclearedAdvance($tenant_id);
            echo json_encode($result);
        }
    }


    public function SOA_done()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result    = $this->app_model->SOA_done($tenant_id);
            echo json_encode($result);
        }
    }


    public function get_storeBankCode()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('cfs_logged_in') || $this->session->userdata('recon_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result    = $this->app_model->get_storeBankCode($tenant_id);
            echo json_encode($result);
        }
    }


    public function preop_payment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Supervisor')
            {
                $data['flashdata']      = $this->session->flashdata('message');
                $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
                $data['payment_docNo']  = $this->app_model->payment_docNo();
                $data['payee']          = $this->app_model->my_store();
                $this->load->view('leasing/header', $data);
                $this->load->view('leasing/preop_payment');
                $this->load->view('leasing/footer');
            } else {
                $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
                $this->session->set_flashdata('message', 'Restriction');
                redirect($prev_url);
            }
        } else {
            redirect('ctrl_leasing/');
        }
    }


    public function save_payment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response              = array();
            $date                  = new DateTime();
            $timeStamp             = $date->getTimestamp();

            //                     =============== Basic Data ============== //

            $tenant_id             = $this->sanitize($this->input->post('tenant_id'));
            $trade_name            = $this->sanitize($this->input->post('trade_name'));
            $contract_no           = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type          = $this->sanitize($this->input->post('tenancy_type'));
            $receipt_no            = "PR" . strtoupper($this->sanitize($this->input->post('receipt_no')));
            $soa_no                = $this->sanitize($this->input->post('soa_no'));
            $billing_period        = $this->sanitize($this->input->post('billing_period'));
            $date                  = $this->sanitize($this->input->post('curr_date'));
            $remarks               = $this->sanitize($this->input->post('remarks'));
            $total_payable         = $this->sanitize($this->input->post('total'));
            $total_payable         = str_replace(",", "", $total_payable);


            //                     =============== From Table Data ============== //
            $charge_id             = $this->input->post('charge_id');
            $description           = $this->input->post('desc');
            $doc_no                = $this->input->post('doc_no');
            $due_date              = $this->input->post('due_date');
            $posting_date          = $this->input->post('posting_date');
            $amount                = $this->input->post('amount');
            $balance               = $this->input->post('balance');
            $retro_doc_no          = $this->input->post('retro_doc_no');
            $preop_doc_no          = $this->input->post('preop_doc_no');
            $penalty_doc_no        = $this->input->post('penalty_doc_no');

            //                     =============== Payment Scheme =============== //

            $tender_typeCode       = $this->sanitize($this->input->post('tender_typeCode'));
            $tender_typeDesc       = $this->sanitize($this->input->post('tender_typeDesc'));
            $amount_paid           = $this->sanitize($this->input->post('amount_paid'));
            $amount_paid           = str_replace(",", "", $amount_paid);
            $bank                  = $this->sanitize($this->input->post('bank'));
            $bank_code             = $this->sanitize($this->input->post('bank_code'));
            $check_no              = $this->sanitize($this->input->post('check_no'));
            $check_date            = $this->sanitize($this->input->post('check_date'));
            $payor                 = $this->sanitize($this->input->post('payor'));
            $payee                 = $this->sanitize($this->input->post('payee'));

            $advanceDeposit_amount = 0.00;

            if ($tender_typeDesc == 'JV payment - Business Unit' || $tender_typeDesc == 'JV payment - Subsidiary')
            {
                $bank = '';
                $bank_code = '';
            }

            $supp_doc = "";


            if (($tender_typeDesc == 'Cash' || $tender_typeDesc == 'Check') && ($bank == '' || $bank == '? undefined:undefined ?'))
            {
                $response['msg'] = 'Required';
            }
            else
            {
                if ($amount_paid > 0)
                {

                    if ($tender_typeDesc != 'Cash')
                    {
                        for ($i=0; $i < count($_FILES["supp_doc"]['name']); $i++)
                        {
                            $targetPath  = getcwd() . '/assets/payment_docs/';
                            $tmpFilePath = $_FILES['supp_doc']['tmp_name'][$i];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $tenant_id . $timeStamp . $_FILES['supp_doc']['name'][$i];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);

                                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'receipt_no' => $receipt_no);
                                $this->app_model->insert('payment_supportingdocs', $data);
                            }
                        }
                    }

                    $advance_payment         = 0;
                    $file_name               =  $tenant_id . $timeStamp . '.pdf';
                    $tender_amount           = $amount_paid;
                    $amount_paid_for_invoice = $amount_paid;
                    $sledger_amountPaid      = $amount_paid;
                    $gledger_amountPaid      = $amount_paid;



                    $this->db->trans_start(); // Transaction function starts here!!!

                    // ============================ PDF =============================== //

                    $store_code      = $this->app_model->tenant_storeCode($tenant_id);
                    $store_details   = $this->app_model->store_details(trim($store_code));
                    $details_soa     = $this->app_model->details_soa($tenant_id);
                    $lessee_info     = $this->app_model->get_lesseeInfo($tenant_id, $contract_no);
                    $collection_date = $this->app_model->get_collectionDate($soa_no);
                    $daysOfMonth     = date('t', strtotime($date));
                    $pdc_status = '';


                    if ($tender_typeDesc == 'Check')
                    {
                        if ($check_date > $date)
                        {
                            $pdc_status = 'PDC';
                        }
                    }


                    $pdf = new FPDF('p','mm','A4');
                    $pdf->AddPage();
                    $pdf->setDisplayMode ('fullpage');
                    $logoPath = getcwd() . '/assets/other_img/';

                    // ==================== Receipt Header ================== //

                    foreach ($store_details as $detail)
                    {
                        $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                        $pdf->ln();
                        $pdf->setFont ('times','B',14);
                        $pdf->cell(75, 6, " ", 0, 0, 'L');
                        $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                        $store_name = $detail['store_name'];
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFillColor(35, 35, 35);
                        $pdf->cell(35, 6, " ", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->setFont ('times','',14);
                        $pdf->cell(15, 0, " ", 0, 0, 'L');
                        $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

                        $pdf->ln();
                        $pdf->ln();
                    }


                    foreach ($details_soa as $detail)
                    {
                        $pdf->setFont('times','',10);
                        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Soa No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $soa_no, 1, 0, 'L');

                        $pdf->ln();
                        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                        $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Date", 0, 0, 'L');
                        $pdf->cell(60, 6, $date, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                        $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                        $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "TIN", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');

                        $pdf->ln();
                        $pdf->ln();
                    }


                        $pdf->ln();
                        $pdf->setFont ('times','B',10);
                        $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
                        $pdf->ln();
                        $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();

                        $pdf->setFont ('times','B',16);
                        $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
                        $pdf->ln();
                        $pdf->ln();


                        $pdf->ln();


                    // =================== Receipt Charges Table ============= //
                        $pdf->setFont('times','B',10);
                        $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
                        $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
                        $pdf->cell(30, 8, "Charges Type", 0, 0, 'C');
                        $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
                        $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
                        $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
                        $pdf->setFont('times','',10);


                    if ($doc_no)
                    {
                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            $chargesDetails = $this->app_model->gl_chargesDetails($tenant_id, $doc_no[$i]);
                            foreach ($chargesDetails as $detail)
                            {
                                $pdf->ln();
                                $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                $pdf->cell(30, 8, $doc_no[$i], 0, 0, 'C');

                                if ($detail['tag'] == 'Basic Rent')
                                {
                                    $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                                }
                                elseif ($detail['tag'] == 'Other')
                                {
                                    $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                                }
                                elseif ($detail['tag'] == 'Penalty')
                                {
                                    $pdf->cell(30, 8, "Penalty-" . $trade_name, 0, 0, 'C');
                                }
                                $pdf->cell(30, 8, $detail['posting_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, $detail['due_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, number_format($detail['balance'], 2), 0, 0, 'R');
                            }
                        }


                        // ================= Penalty deduction ============== //



                        if ($penalty_doc_no)
                        {
                            $penaltyDetails = $this->app_model->get_penalty($tenant_id, $contract_no);

                            foreach ($penaltyDetails as $penalty)
                            {
                                if ($amount_paid > 0)
                                {
                                    $amount_paid -= $penalty['balance'];
                                    $sledger_amountPaid -= $penalty['balance'];

                                    if ($amount_paid >= 0)
                                    {
                                        $penaltyEntry = array(
                                            'posting_date'  =>  $date,
                                            'document_type' =>  'Payment',
                                            'ref_no'        =>  $penalty['ref_no'],
                                            'doc_no'        =>  $receipt_no,
                                            'tenant_id'     =>  $tenant_id,
                                            'contract_no'   =>  $contract_no,
                                            'description'   =>  $penalty['description'],
                                            'debit'         =>  $penalty['balance'],
                                            'balance'       =>  0
                                        );

                                        $this->app_model->insert('ledger', $penaltyEntry);


                                    } else {
                                        $penaltyEntry = array(
                                            'posting_date'  =>  $date,
                                            'document_type' =>  'Payment',
                                            'ref_no'        =>  $penalty['ref_no'],
                                            'doc_no'        =>  $receipt_no,
                                            'tenant_id'     =>  $tenant_id,
                                            'contract_no'   =>  $contract_no,
                                            'description'   =>  $penalty['description'],
                                            'debit'         =>  $amount_paid,
                                            'balance'       =>  $penalty['balance'] - $amount_paid
                                        );

                                        $this->app_model->insert('ledger', $penaltyEntry);
                                    }

                                    //delete from tmp_latepaymentpenalty
                                    $this->app_model->delete_tmp_latepaymentpenalty($penalty['doc_no'], $penalty['tenant_id']);
                                }
                            }


                            $gl_penalties = $this->app_model->get_glPenalties($tenant_id);

                            foreach ($gl_penalties as $penalty)
                            {

                                if ($gledger_amountPaid > 0)
                                {
                                    $gledger_amountPaid -= $penalty['balance'];

                                    if ($gledger_amountPaid >= 0)
                                    {
                                        if ($tender_typeDesc == 'Check')
                                        {
                                            if ($check_date > $date)
                                            {
                                                // If PDC

                                                $penalty_PDC = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_PDC);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_PDC);

                                                $penalty_AR = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'credit'            =>  -1 * $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'status'            =>  $pdc_status,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_AR);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                            }
                                            else
                                            {
                                                // If Dated Check

                                                $penalty_CIB = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_CIB);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                                $penalty_AR = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $penalty['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'credit'            =>  -1 * $penalty['balance'],
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );

                                                $this->app_model->insert('general_ledger', $penalty_AR);
                                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                            }

                                        }
                                        elseif ($tender_typeDesc == 'Cash')
                                        {
                                            $penalty_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                        {
                                            $penalty_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_JV);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_JV);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                        {
                                            $penalty_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_JV);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_JV);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                        elseif ($tender_typeDesc == 'Bank to Bank')
                                        {
                                            $penalty_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                            $penalty_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $penalty['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $penalty['balance'],
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $penalty_AR);
                                            $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                        }
                                    }
                                    else
                                    {
                                        $penalty_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $penalty['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gledger_amountPaid,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );

                                        $this->app_model->insert('general_ledger', $penalty_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                        $penalty_AR = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $penalty['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gledger_amountPaid,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );

                                        $this->app_model->insert('general_ledger', $penalty_AR);
                                        $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                                    }
                                }
                            }
                        }


                        // =========================================================================== //


                        // ============== Add Entry to Ledger ============== //

                        if ($doc_no)
                        {
                           $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no[$i] != "")
                            {
                                if ($sledger_amountPaid > 0) // Check if Amount Paid has value
                                {
                                    $ledger_item    = $this->app_model->get_ledgerEntry($tenant_id, $doc_no[$i]);
                                    $expanded_taxes = $this->app_model->get_expandedTaxes($tenant_id, $doc_no[$i]);

                                    foreach ($expanded_taxes as $value) // Add the Expanded Withholding Tax amount to sledger_amountPaid(Binogo ni)
                                    {
                                        $sledger_amountPaid += $value['debit'];
                                        $this->app_model->delete('ledger', 'id', $value['id']);
                                    }

                                    foreach ($ledger_item as $item)
                                    {
                                        if ($sledger_amountPaid >= 1)
                                        {
                                            $credit;
                                            $balance;

                                            if ($sledger_amountPaid >= $item['balance'])
                                            {
                                                $credit  = $item['balance'];
                                                $balance = 0;
                                            }
                                            else
                                            {
                                                $credit  = $sledger_amountPaid;
                                                $balance = abs($item['balance'] - $sledger_amountPaid);
                                            }

                                            $sledger_amountPaid -= $item['balance'];

                                            $entryData = array(
                                                'posting_date'  =>  $date,
                                                'document_type' =>  'Payment',
                                                'ref_no'        =>  $item['ref_no'],
                                                'doc_no'        =>  $receipt_no,
                                                'tenant_id'     =>  $tenant_id,
                                                'contract_no'   =>  $contract_no,
                                                'description'   =>  $item['description'],
                                                'debit'         =>  $credit,
                                                'balance'       =>  $balance,
                                                'due_date'      =>  $item['due_date']
                                            );

                                            $this->app_model->insert('ledger', $entryData);
                                        }
                                    }
                                }
                            }
                        }

                        // ================== Closing GL Accounts ================ //

                        if ($doc_no)
                        {
                            $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no != "")
                            {
                                if ($gledger_amountPaid > 0)
                                {
                                    $gl_entries = $this->app_model->gl_chargesDetails($tenant_id, $doc_no[$i]);

                                    foreach ($gl_entries as $entry)
                                    {
                                        $credit;
                                        if ($gledger_amountPaid >= $entry['balance'])
                                        {
                                            $credit = $entry['balance'];
                                        }
                                        else
                                        {
                                            $credit = $gledger_amountPaid;
                                        }

                                        $gledger_amountPaid -= $entry['balance'];

                                        if ($tender_typeDesc == 'Cash')
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }
                                        elseif ($tender_typeDesc == 'Check')
                                        {
                                            if ($check_date > $date)
                                            {
                                                // If PDC

                                                $entry_PDC = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $entry['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $credit,
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );
                                                $this->app_model->insert('general_ledger', $entry_PDC);
                                                $this->app_model->insert('subsidiary_ledger', $entry_PDC);
                                            }
                                            else
                                            {
                                                $entry_CIB = array(
                                                    'posting_date'      =>  $date,
                                                    'transaction_date'  =>  $this->_currentDate,
                                                    'document_type'     =>  'Payment',
                                                    'ref_no'            =>  $entry['ref_no'],
                                                    'doc_no'            =>  $receipt_no,
                                                    'tenant_id'         =>  $tenant_id,
                                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                    'company_code'      =>  $this->session->userdata('company_code'),
                                                    'department_code'   =>  '01.04',
                                                    'debit'             =>  $credit,
                                                    'bank_name'         =>  $bank,
                                                    'bank_code'         =>  $bank_code,
                                                    'prepared_by'       =>  $this->session->userdata('id')
                                                );
                                                $this->app_model->insert('general_ledger', $entry_CIB);
                                                $this->app_model->insert('subsidiary_ledger', $entry_CIB);

                                            }

                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                        {
                                            $entry_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_JV);
                                            $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                        }
                                        elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                        {
                                            $entry_JV = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_JV);
                                            $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                        }
                                        elseif ($tender_typeDesc == 'Bank to Bank')
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }


                                        if ($entry['tag'] == 'Basic Rent')
                                        {
                                            $credit_RR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'status'            =>  $pdc_status,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $credit_RR);
                                            $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                        }
                                        elseif ($entry['tag'] == 'Other')
                                        {
                                            $ar_code = '10.10.01.03.03';
                                            if ($this->app_model->is_AGCSubsidiary($tenant_id)) {
                                                $ar_code = '10.10.01.03.04';
                                            }

                                            $credit_AR = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $entry['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID($ar_code),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'credit'            =>  -1 * $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'status'            =>  $pdc_status,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );

                                            $this->app_model->insert('general_ledger', $credit_AR);
                                            $this->app_model->insert('subsidiary_ledger', $credit_AR);
                                        }
                                    }
                                }
                            }
                        }


                        // ===== Charges deduction in invoicing table ====== //

                        if ($doc_no)
                        {
                            $doc_no = $this->sort_ascending($doc_no);
                        }

                        for ($i=0; $i < count($doc_no); $i++)
                        {
                            if ($doc_no[$i] != "")
                            {
                                if ($amount_paid_for_invoice > 0) // Check if Amount Paid has value
                                {
                                    $docNo_total             = $this->app_model->total_perDocNo($tenant_id, $doc_no[$i]);
                                    $amount_paid_for_invoice -= $docNo_total;
                                    if ($amount_paid_for_invoice >= 0)
                                    {
                                        $balance = 0;
                                        $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                                    }
                                    else
                                    {
                                        $balance = abs($amount_paid_for_invoice);
                                        $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $receipt_no);
                                    }
                                }
                            }
                        }
                    }// End of If has document Number



                    if ($retro_doc_no)
                    {
                        // If has Retro Rental
                        if ($amount_paid > 0)
                        {

                            $retro_charges = $this->app_model->get_glRetroPayment($tenant_id);
                            foreach ($retro_charges as $retro)
                            {
                                if ($amount_paid > 0)
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['doc_no'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['tag'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['posting_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $retro['due_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, number_format($retro['balance'], 2), 0, 0, 'R');


                                    $credit;
                                    if ($gledger_amountPaid >= $retro['balance'])
                                    {
                                        $credit = $retro['balance'];
                                    }
                                    else
                                    {
                                        $credit = $gledger_amountPaid;
                                    }


                                    if ($tender_typeDesc == 'Cash')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                    }
                                    elseif ($tender_typeDesc == 'Check')
                                    {
                                        if ($check_date > $date)
                                        {
                                            // If PDC

                                            $entry_PDC = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $retro['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_PDC);
                                            $this->app_model->insert('subsidiary_ledger', $entry_PDC);

                                        }
                                        else
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $retro['ref_no'],
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $credit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }

                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                    {
                                        $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                    {
                                        $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $retro['ref_no'],
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $credit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }

                                    $credit_RR = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $retro['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $credit,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  $pdc_status,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );
                                    $this->app_model->insert('general_ledger', $credit_RR);
                                    $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                    $amount_paid -= $credit;
                                }
                            }


                            // to SL

                            $retro_ledger = $this->app_model->get_invoiceRetro($tenant_id);

                            foreach ($retro_ledger as $item)
                            {
                                if ($sledger_amountPaid > 0)
                                {
                                    $credit;
                                    $balance;

                                    if ($sledger_amountPaid >= $item['balance'])
                                    {
                                        $credit  = $item['balance'];
                                        $balance = 0;
                                    }
                                    else
                                    {
                                        $credit  = $sledger_amountPaid;
                                        $balance = abs($item['balance'] - $sledger_amountPaid);
                                    }

                                    $sledger_amountPaid -= $item['balance'];

                                    $entryData = array(
                                        'posting_date'  =>  $date,
                                        'document_type' =>  'Payment',
                                        'ref_no'        =>  $item['ref_no'],
                                        'doc_no'        =>  $receipt_no,
                                        'tenant_id'     =>  $tenant_id,
                                        'contract_no'   =>  $contract_no,
                                        'description'   =>  $item['description'],
                                        'debit'         =>  $credit,
                                        'balance'       =>  $balance,
                                        'due_date'      =>  $item['due_date']
                                    );

                                    $this->app_model->insert('ledger', $entryData);
                                }
                            }
                        }
                    }



                    if ($preop_doc_no) // IF has Preoperation Charges
                    {
                        $preop_data = $this->app_model->get_preopdata($tenant_id);

                        if ($gledger_amountPaid > 0)
                        {
                            foreach ($preop_data as $preop)
                            {
                                if ($gledger_amountPaid > 0)
                                {
                                    $pdf->ln();
                                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['doc_no'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['description'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['posting_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, $preop['due_date'], 0, 0, 'C');
                                    $pdf->cell(30, 8, number_format($preop['amount'], 2), 0, 0, 'R');

                                    $sl_refNo = $this->app_model->generate_refNo();
                                    $gl_refNo = $this->app_model->gl_refNo();
                                    //todo

                                    $debit;
                                    $balance;
                                    if ($gledger_amountPaid >= $preop['amount'])
                                    {
                                       $debit   = $preop['amount'];
                                       $balance = 0;
                                    }
                                    else
                                    {
                                        $balance = abs($preop['amount'] - $gledger_amountPaid);
                                        $debit   = $gledger_amountPaid;
                                    }

                                    $gl_code;
                                    $doc_type;
                                    if ($preop['description'] == 'Advance Rent')
                                    {
                                        $gl_code  = '10.20.01.01.02.01';
                                        $doc_type = 'Advance Payment';
                                    }
                                    elseif ($preop['description'] == 'Security Deposit' || $preop['description'] == 'Security Deposit - Kiosk and Cart')
                                    {
                                        $gl_code  = '10.20.01.01.03.12';
                                        $doc_type = 'Payment';
                                    }
                                    elseif ($preop['description'] == 'Construction Bond')
                                    {
                                        $gl_code  = '10.20.01.01.03.10';
                                        $doc_type = 'Payment';
                                    }

                                    if ($tender_typeDesc == 'Cash')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                                    }
                                    elseif ($tender_typeDesc == 'Check')
                                    {
                                        if ($check_date > $date)
                                        {
                                            // if PDC
                                            $entry_PDC = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_refNo,
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $debit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')

                                            );
                                            $this->app_model->insert('general_ledger', $entry_PDC);
                                            $this->app_model->insert('subsidiary_ledger', $entry_PDC);
                                        }
                                        else
                                        {
                                            $entry_CIB = array(
                                                'posting_date'      =>  $date,
                                                'transaction_date'  =>  $this->_currentDate,
                                                'document_type'     =>  'Payment',
                                                'ref_no'            =>  $gl_refNo,
                                                'doc_no'            =>  $receipt_no,
                                                'tenant_id'         =>  $tenant_id,
                                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                                'company_code'      =>  $this->session->userdata('company_code'),
                                                'department_code'   =>  '01.04',
                                                'debit'             =>  $debit,
                                                'bank_name'         =>  $bank,
                                                'bank_code'         =>  $bank_code,
                                                'prepared_by'       =>  $this->session->userdata('id')
                                            );
                                            $this->app_model->insert('general_ledger', $entry_CIB);
                                            $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                        }
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Business Unit')
                                    {
                                       $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                                    {
                                       $entry_JV = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_JV);
                                        $this->app_model->insert('subsidiary_ledger', $entry_JV);
                                    }
                                    elseif ($tender_typeDesc == 'Bank to Bank')
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $date,
                                            'transaction_date'  =>  $this->_currentDate,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $gl_refNo,
                                            'doc_no'            =>  $receipt_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $debit,
                                            'bank_name'         =>  $bank,
                                            'bank_code'         =>  $bank_code,
                                            'prepared_by'       =>  $this->session->userdata('id')
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                    }

                                    $preop_entry = array(
                                        'posting_date'      =>    $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>    'Payment',
                                        'ref_no'            =>    $gl_refNo,
                                        'doc_no'            =>    $receipt_no,
                                        'tenant_id'         =>    $tenant_id,
                                        'gl_accountID'      =>    $this->app_model->gl_accountID($gl_code),
                                        'company_code'      =>    $this->session->userdata('company_code'),
                                        'department_code'   =>    '01.04',
                                        'credit'            =>    -1 * $debit,
                                        'bank_name'         =>    $bank,
                                        'bank_code'         =>    $bank_code,
                                        'status'            =>  $pdc_status,
                                        'prepared_by'       =>    $this->session->userdata('id')
                                    );
                                    $this->app_model->insert('general_ledger', $preop_entry);
                                    $this->app_model->insert('subsidiary_ledger', $preop_entry);

                                    $sl_preOp = array(
                                        'posting_date'      =>    $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>    $doc_type,
                                        'ref_no'            =>    $sl_refNo,
                                        'doc_no'            =>    $receipt_no,
                                        'tenant_id'         =>    $tenant_id,
                                        'contract_no'       =>    $contract_no,
                                        'description'       =>    $preop['description'] . "-" . $trade_name,
                                        'charges_type'      =>    $preop['description'],
                                        'debit'             =>    $debit,
                                        'balance'           =>    $balance
                                    );
                                    $this->app_model->insert('ledger', $sl_preOp);


                                    $gledger_amountPaid -= $preop['amount'];

                                    if ($gledger_amountPaid >= 0)
                                    {
                                        $this->app_model->delete('tmp_preoperationcharges', 'id', $preop['id']);
                                    }
                                    else
                                    {
                                        $data = array('amount' => ABS($gledger_amountPaid));
                                        $this->app_model->update($data, $preop['id'], 'tmp_preoperationcharges');
                                    }
                                }
                            }
                        }
                    }



                    if ($tender_amount > $total_payable)
                    {
                        $advance_payment = $tender_amount - $total_payable;

                        if ($advance_payment >= 1)
                        {
                            $dataLedger = array(
                                'posting_date'       =>  $date,
                                'transaction_date'   =>  $date,
                                'document_type'      =>  'Advance Payment',
                                'doc_no'             =>  $receipt_no,
                                'ref_no'             =>  $this->app_model->generate_refNo(),
                                'tenant_id'          =>  $tenant_id,
                                'contract_no'        =>  $contract_no,
                                'description'        =>  'Advance Payment-' . $trade_name,
                                'debit'              =>  $advance_payment,
                                'credit'             =>  0,
                                'balance'            =>  $advance_payment
                            );

                            $this->app_model->insert('ledger', $dataLedger);


                            //======== Unearned Rent for Advance Payment =========//

                            $advance_refNo = $this->app_model->gl_refNo();


                            if ($tender_typeDesc == 'Cash')
                            {

                                $advance_CIB = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_CIB);
                                $this->app_model->insert('subsidiary_ledger', $advance_CIB);
                            }
                            elseif ($tender_typeDesc == 'Check')
                            {
                                if ($check_date > $date)
                                {

                                    $advance_PDC = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $advance_refNo,
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $advance_payment,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('general_ledger', $advance_PDC);
                                    $this->app_model->insert('subsidiary_ledger', $advance_PDC);

                                }
                                else
                                {
                                    $advance_CIB = array(
                                        'posting_date'      =>  $date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $advance_refNo,
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $advance_payment,
                                        'bank_name'         =>  $bank,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('general_ledger', $advance_CIB);
                                    $this->app_model->insert('subsidiary_ledger', $advance_CIB);
                                }
                            }
                            elseif ($tender_typeDesc == 'JV payment - Business Unit')
                            {
                                $advance_JV = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_JV);
                                $this->app_model->insert('subsidiary_ledger', $advance_JV);
                            }
                            elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                            {
                                $advance_JV = array(
                                    'posting_date'      =>  $date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $advance_refNo,
                                    'doc_no'            =>  $receipt_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $advance_payment,
                                    'bank_name'         =>  $bank,
                                    'bank_code'         =>  $bank_code,
                                    'prepared_by'       =>  $this->session->userdata('id')
                                );

                                $this->app_model->insert('general_ledger', $advance_JV);
                                $this->app_model->insert('subsidiary_ledger', $advance_JV);
                            }


                            $advance_unearned = array(
                                'posting_date'      =>  $date,
                                'transaction_date'  =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $advance_refNo,
                                'doc_no'            =>  $receipt_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  -1 * $advance_payment,
                                'bank_name'         =>  $bank,
                                'bank_code'         =>  $bank_code,
                                'status'            =>  $pdc_status,
                                'prepared_by'       =>  $this->session->userdata('id')
                            );

                            $this->app_model->insert('general_ledger', $advance_unearned);
                            $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                             // For Montly Receivable Report
                            $reportData = array(
                                'tenant_id'     =>  $tenant_id,
                                'doc_no'        =>  $receipt_no,
                                'posting_date'  =>  $date,
                                'description'   =>  'Advance Payment',
                                'amount'        =>  $advance_payment
                            );

                            $this->app_model->insert('monthly_receivable_report', $reportData);
                        }

                    }


                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();


                    $pdf->setFont('times','B',10);
                    $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
                    $pdf->cell(100, 8, "Payment Date:" . $date, 0, 0, 'L');
                    $pdf->ln();

                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $tender_typeDesc, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
                    $pdf->ln();

                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $bank, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($tender_amount, 2), 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $check_no, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
                    if($tender_amount - $total_payable >= 0)
                    {
                        $pdf->cell(30, 4, "P " . "0.00", 0, 0, 'L');
                    }
                    else
                    {
                        $pdf->cell(60, 4, "P " . number_format(abs($tender_amount - $total_payable), 2), 0, 0, 'L');
                    }
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $check_date, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($advance_payment + $advanceDeposit_amount, 2), 0, 0, 'L');

                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $payor, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $payee, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();

                    if ($tender_typeDesc != 'Cash')
                    {
                        $paymentScheme = array(
                            'tenant_id'        =>   $tenant_id,
                            'contract_no'      =>   $contract_no,
                            'tenancy_type'     =>   $tenancy_type,
                            'receipt_no'       =>   $receipt_no,
                            'tender_typeCode'  =>   $tender_typeCode,
                            'tender_typeDesc'  =>   $tender_typeDesc,
                            'soa_no'           =>   $soa_no,
                            'amount_due'       =>   $total_payable,
                            'amount_paid'      =>   $tender_amount,
                            'bank'             =>   $bank,
                            'check_no'         =>   $check_no,
                            'check_date'       =>   $check_date,
                            'payor'            =>   $payor,
                            'payee'            =>   $payee,
                            'receipt_doc'      =>   $file_name
                        );

                        $this->app_model->insert('payment_scheme', $paymentScheme);
                    }
                    else
                    {
                        $paymentScheme = array(
                            'tenant_id'        =>   $tenant_id,
                            'contract_no'      =>   $contract_no,
                            'tenancy_type'     =>   $tenancy_type,
                            'receipt_no'       =>   $receipt_no,
                            'tender_typeCode'  =>   $tender_typeCode,
                            'billing_period'   =>   $billing_period,
                            'tender_typeDesc'  =>   $tender_typeDesc,
                            'soa_no'           =>   $soa_no,
                            'amount_due'       =>   $total_payable,
                            'amount_paid'      =>   $tender_amount,
                            'bank'             =>   $bank,
                            'check_no'         =>   $check_no,
                            'check_date'       =>   $check_date,
                            'payor'            =>   $payor,
                            'payee'            =>   $payee,
                            'receipt_doc'      =>   $file_name
                        );

                        $this->app_model->insert('payment_scheme', $paymentScheme);
                    }

                    $paymentData = array(
                        'posting_date' =>   $date,
                        'soa_no'       =>   $soa_no,
                        'amount_paid'  =>   $tender_amount,
                        'tenant_id'    =>   $tenant_id,
                        'doc_no'       =>   $receipt_no
                    );

                    $this->app_model->insert('payment', $paymentData);


                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','',10);
                    $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');

                    $this->db->trans_complete(); // End of transaction function

                    if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                    {
                        $this->db->trans_rollback(); // If failed rollback all queries
                        $error = array('action' => 'Saving Payment', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                        $this->app_model->insert('error_log', $error);
                        $response['msg'] = 'DB_error';
                    }
                    else
                    {

                        $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                        $pdf->Output('assets/pdf/' . $file_name , 'F');
                        $response['msg'] = "Success";
                    }



                    // ========= Check if payment is delayed to apply penalty ========= //

                    if (!$this->app_model->is_penaltyExempt($tenant_id) && $billing_period != 'Upon Signing of Notice')
                    {
                        if (date('Y-m-d', strtotime($date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
                        {
                            $daydiff             = floor((abs(strtotime($date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                            $sundays             = $this->app_model->get_sundays($collection_date, $date);
                            $daydiff             = $daydiff - $sundays;
                            $penalty_latepayment = ($tender_amount * .02 * $daydiff) / $daysOfMonth;

                            $penaltyEntry = array(
                                'tenant_id'     =>  $tenant_id,
                                'posting_date'  =>  $date,
                                'contract_no'   =>  $contract_no,
                                'doc_no'        =>  $receipt_no,
                                'description'   =>  'Late Payment-' . $trade_name,
                                'amount'        =>  round($penalty_latepayment, 2)
                            );
                            $this->app_model->insert('tmp_latepaymentpenalty', $penaltyEntry);
                        }
                    }

                }
                else
                {
                    $response['msg'] = 'Required';
                }
            }
            echo json_encode($response);
        }
    } // End Save Payment


    public function save_unreg_fundTransfer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response         = array();
            $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
            $trade_name       = $this->sanitize($this->input->post('trade_name'));
            $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
            $contract_no      = $this->sanitize($this->input->post('contract_no'));
            $bank_code        = $this->input->post('bank_code');
            $bank_name        = $this->sanitize($this->input->post('bank_name'));
            $posting_date     = $this->sanitize($this->input->post('posting_date'));
            $billing_period   = $this->sanitize($this->input->post('billing_period'));
            $transaction_date = $this->_currentDate;
            $total_amount     = str_replace(",", "", $this->sanitize($this->input->post('total_amount')));
            $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
            $trans_no         = $this->app_model->generate_UTFTransactionNo(TRUE);
            $soa_no           = $this->input->post('soa_no');
            $doc_no           = $this->input->post('doc_no');
            $preop_doc_no     = $this->input->post('preop_doc_no');
            $retro_doc_no     = $this->input->post('retro_doc_no');
            $collection_date  = $this->app_model->get_latestCollectionDate($tenant_id);
            $receipt_no       = "";


            if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
            {
               $response['msg'] = 'Required';
            }
            else
            {
                $this->db->trans_start(); // Transaction function starts here!!!

                $sl_refNo       = $this->app_model->generate_refNo();
                $gl_refNo       = $this->app_model->gl_refNo();


                $tenant_penalty = $this->app_model->get_penalty($tenant_id, $contract_no);

                $sl_amount      = $amount_paid;
                $gl_amount      = $amount_paid;
                $invoice_amount = $amount_paid;


                $data_utf = array(
                    'tenant_id'      => $tenant_id,
                    'bank_code'      => $bank_code,
                    'bank_name'      => $bank_name,
                    'posting_date'   => $posting_date,
                    'amount_payable' => $total_amount,
                    'amount_paid'    => $amount_paid
                );
                $this->app_model->insert('uft_payment', $data_utf);

                // Closing of penalty to tenant ledger
                if ($tenant_penalty)
                {
                    foreach ($tenant_penalty as $penalty)
                    {
                        if ($sl_amount > 0)
                        {
                            if ($sl_amount >= $penalty['balance'])
                            {
                                $penaltyEntry = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'UFT-Payment',
                                    'ref_no'           =>  $penalty['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'contract_no'      =>  $contract_no,
                                    'description'      =>  $penalty['description'],
                                    'debit'            =>  $penalty['balance'],
                                    'balance'          =>  0,
                                    'bank_code'        =>  $bank_code,
                                    'bank_name'        =>  $bank_name,
                                    'prepared_by'      =>  $this->_user_id
                                );
                                $this->app_model->insert('ledger', $penaltyEntry);
                            }
                            else
                            {
                                $penaltyEntry = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'UFT-Payment',
                                    'ref_no'           =>  $penalty['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'contract_no'      =>  $contract_no,
                                    'description'      =>  $penalty['description'],
                                    'debit'            =>  $sl_amount,
                                    'balance'          =>  $penalty['balance'] - $sl_amount,
                                    'bank_code'        =>  $bank_code,
                                    'bank_name'        =>  $bank_name,
                                    'prepared_by'      =>  $this->_user_id
                                );
                                $this->app_model->insert('ledger', $penaltyEntry);
                            }

                            $sl_amount -= $penalty['balance'];
                        }

                        //delete from tmp_latepaymentpenalty
                        $this->app_model->delete_tmp_latepaymentpenalty($penalty['doc_no'], $penalty['tenant_id']);
                    }
                }


                // Closing of penalty to General Ledger
                $gl_penalties = $this->app_model->get_glPenalties($tenant_id);

                if ($gl_penalties)
                {
                    foreach ($gl_penalties as $penalty)
                    {

                        if ($gl_amount > 0)
                        {
                        	$closingRefNo = $this->app_model->generate_ClosingRefNo();

                            if ($gl_amount >= $penalty['balance'])
                            {
                                $penalty_CIB = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $penalty['balance'],
                                    'bank_code'         =>  $bank_code,
                                    'bank_name'         =>  $bank_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_CIB);
                                $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                $penalty_AR = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $penalty['balance'],
                                    'bank_code'         =>  $bank_code,
                                    'status'            =>  'AR Clearing',
                                    'bank_name'         =>  $bank_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_AR);
                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                            }
                            else
                            {
                                $penalty_CIB = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $gl_amount,
                                    'bank_code'         =>  $bank_code,
                                    'bank_name'         =>  $bank_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_CIB);
                                $this->app_model->insert('subsidiary_ledger', $penalty_CIB);

                                $penalty_AR = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $gl_amount,
                                    'bank_code'         =>  $bank_code,
                                    'status'            =>  'AR Clearing',
                                    'bank_name'         =>  $bank_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_AR);
                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                            }

                            $gl_amount -= $penalty['balance'];
                        }
                    }
                }


                if ($doc_no)
                {
                    $doc_no = $this->sort_ascending($doc_no);
                }

                for ($i=0; $i < count($doc_no); $i++)
                {
                    // Closing Rent Receivables to Tenant Ledger
                    $sl_tenantRR  = $this->app_model->sl_tenantRR($tenant_id, $doc_no[$i]);

                    if ($sl_tenantRR)
                    {
                        if ($sl_amount > 0)
                        {
                            foreach ($sl_tenantRR as $rr)
                            {
                                if ($sl_amount > 0)
                                {
                                    if ($sl_amount >= $rr['balance'])
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'UFT-Payment',
                                            'ref_no'           =>  $rr['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $rr['description'],
                                            'debit'            =>  $rr['balance'],
                                            'balance'          =>  0,
                                            'bank_code'        =>  $bank_code,
                                            'bank_name'        =>  $bank_name,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }
                                    else
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'UFT-Payment',
                                            'ref_no'           =>  $rr['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $rr['description'],
                                            'debit'            =>  $sl_amount,
                                            'balance'          =>  $rr['balance'] - $sl_amount,
                                            'bank_code'        =>  $bank_code,
                                            'bank_name'        =>  $bank_name,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }

                                    $sl_amount -= $rr['balance'];
                                }
                            }
                        }
                    }

                    // Closing Rent Receivables to General Ledger
                    $tenant_RR = $this->app_model->get_tenantRR($tenant_id, $doc_no[$i]);

                    if ($tenant_RR)
                    {
                        foreach ($tenant_RR as $rr)
                        {
                            if ($gl_amount > 0)
                            {
                            	$closingRefNo = $this->app_model->generate_ClosingRefNo();

                                if ($gl_amount >= $rr['balance'])
                                {
                                    $entry_CIB = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $rr['balance'],
                                        'bank_code'         =>  $bank_code,
                                        'bank_name'         =>  $bank_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );
                                    $this->app_model->insert('general_ledger', $entry_CIB);
                                    $this->app_model->insert('subsidiary_ledger', $entry_CIB);
                                    $entry_rr = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $rr['balance'],
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  'RR Clearing',
                                        'bank_name'         =>  $bank_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );

                                    $this->app_model->insert('general_ledger', $entry_rr);
                                    $this->app_model->insert('subsidiary_ledger', $entry_rr);
                                }
                                else
                                {
                                    $entry_CIB = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $gl_amount,
                                        'bank_code'         =>  $bank_code,
                                        'bank_name'         =>  $bank_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );
                                    $this->app_model->insert('general_ledger', $entry_CIB);
                                    $this->app_model->insert('subsidiary_ledger', $entry_CIB);

                                    $entry_rr = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $gl_amount,
                                        'status'            =>  'RR Clearing',
                                        'bank_code'         =>  $bank_code,
                                        'bank_name'         =>  $bank_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );

                                    $this->app_model->insert('general_ledger', $entry_rr);
                                    $this->app_model->insert('subsidiary_ledger', $entry_rr);
                                }

                                $gl_amount -= $rr['balance'];
                            }
                        }
                    }



                    $sl_tenantAR = $this->app_model->get_slOtherCharges($tenant_id, $doc_no[$i]);

                    // Closing Account Receivables to Tenant Ledger
                    if ($sl_tenantAR)
                    {
                        if ($sl_amount > 0)
                        {
                            foreach ($sl_tenantAR as $ar)
                            {
                                if ($sl_amount > 0)
                                {
                                    $expanded_taxes = $this->app_model->get_expandedTaxes($tenant_id, $ar['doc_no']);
                                    foreach ($expanded_taxes as $value)
                                    {
                                        $sl_amount += $value['debit'];
                                        $this->app_model->delete('ledger', 'id', $value['id']);
                                    }
                                    if ($sl_amount >= $ar['balance'])
                                    {
                                        $ar_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'UFT-Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $ar['description'],
                                            'debit'            =>  $ar['balance'],
                                            'balance'          =>  0,
                                            'bank_code'        =>  $bank_code,
                                            'bank_name'        =>  $bank_name,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $ar_entry);
                                    }
                                    else
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'UFT-Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $ar['description'],
                                            'debit'            =>  $sl_amount,
                                            'balance'          =>  $ar['balance'] - $sl_amount,
                                            'bank_code'        =>  $bank_code,
                                            'bank_name'        =>  $bank_name,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }

                                    $sl_amount -= $ar['balance'];
                                }
                            }
                        }
                    }


                    $tenant_AR = $this->app_model->get_tenantAR($tenant_id, $doc_no[$i]);

                    // Closing Account Receivables to General Ledger
                    if ($tenant_AR)
                    {
                        if ($gl_amount > 0)
                        {
                            foreach ($tenant_AR as $ar)
                            {
                                if ($gl_amount > 0)
                                {
                                	$closingRefNo = $this->app_model->generate_ClosingRefNo();

                                    if ($gl_amount >= $ar['balance'])
                                    {
                                        $entry_CIB = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'     =>  $this->session->userdata('company_code'),
                                            'department_code'  =>  '01.04',
                                            'debit'            =>  $ar['balance'],
                                            'bank_code'        =>  $bank_code,
                                            'bank_name'        =>  $bank_name,
                                            'prepared_by'      =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);

                                        $entry_ar = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $ar['balance'],
                                            'bank_code'         =>  $bank_code,
                                            'status'            =>  'AR Clearing',
                                            'bank_name'         =>  $bank_name,
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );

                                        $this->app_model->insert('general_ledger', $entry_ar);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ar);
                                    }
                                    else
                                    {
                                        $entry_CIB = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $gl_amount,
                                            'bank_code'         =>  $bank_code,
                                            'bank_name'         =>  $bank_name,
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );
                                        $this->app_model->insert('general_ledger', $entry_CIB);
                                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);

                                        $entry_ar = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_amount,
                                            'bank_code'         =>  $bank_code,
                                            'status'            =>  'AR Clearing',
                                            'bank_name'         =>  $bank_name,
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );

                                        $this->app_model->insert('general_ledger', $entry_ar);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ar);
                                    }

                                    $gl_amount -= $ar['balance'];
                                }
                            }
                        }
                    }


                } // end doc_no for loop



                if ($doc_no)
                {
                    // ===== Charges deduction in invoicing table ====== //
                    $doc_no = $this->sort_ascending($doc_no);

                    for ($i=0; $i < count($doc_no); $i++)
                    {
                        if ($doc_no[$i] != "")
                        {
                            if ($invoice_amount > 0) // Check if Amount Paid has value
                            {
                                $docNo_total    = $this->app_model->total_perDocNo($tenant_id, $doc_no[$i]);
                                $invoice_amount -= $docNo_total;
                                if ($invoice_amount >= 0)
                                {
                                    $balance = 0;
                                    $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $trans_no);
                                }
                                else
                                {
                                    $balance = abs($invoice_amount);
                                    $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $trans_no);
                                }
                            }
                        }
                    }
                }


                if ($retro_doc_no)
                {
                    // If has Retro Rental
                    if ($amount_paid > 0)
                    {
                        $retro_charges = $this->app_model->get_glRetroPayment($tenant_id);
                        foreach ($retro_charges as $retro)
                        {
                            if ($gl_amount > 0)
                            {
                                $credit;
                                if ($gl_amount >= $retro['balance'])
                                {
                                    $credit = $retro['balance'];
                                }
                                else
                                {
                                    $credit = $gl_amount;
                                }

                                $closingRefNo = $this->app_model->generate_ClosingRefNo();

                                $entry_CIB = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Payment',
                                    'ref_no'           =>  $retro['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'     =>  $this->session->userdata('company_code'),
                                    'department_code'  =>  '01.04',
                                    'debit'            =>  $credit,
                                    'bank_name'        =>  $bank_name,
                                    'bank_code'        =>  $bank_code,
                                    'prepared_by'      =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $entry_CIB);
                                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                                $credit_RR = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Payment',
                                    'ref_no'           =>  $retro['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                    'company_code'     =>  $this->session->userdata('company_code'),
                                    'department_code'  =>  '01.04',
                                    'status'           =>  'RR Clearing',
                                    'credit'           =>  -1 * $credit,
                                    'bank_name'        =>  $bank_name,
                                    'bank_code'        =>  $bank_code,
                                    'prepared_by'      =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $credit_RR);
                                $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                $gl_amount -= $credit;
                            }
                        }


                        // to SL

                        $retro_ledger = $this->app_model->get_invoiceRetro($tenant_id);

                        foreach ($retro_ledger as $item)
                        {
                            if ($sl_amount > 0)
                            {
                                $credit;
                                $balance;

                                if ($sl_amount >= $item['balance'])
                                {
                                    $credit  = $item['balance'];
                                    $balance = 0;
                                }
                                else
                                {
                                    $credit  = $sl_amount;
                                    $balance = abs($item['balance'] - $sl_amount);
                                }

                                $sl_amount -= $item['balance'];

                                $entryData = array(
                                    'posting_date'     =>    $posting_date,
                                    'transaction_date' => $transaction_date,
                                    'document_type' =>  'UFT-Payment',
                                    'ref_no'        =>  $item['ref_no'],
                                    'doc_no'        =>  $trans_no,
                                    'tenant_id'     =>  $tenant_id,
                                    'contract_no'   =>  $contract_no,
                                    'description'   =>  $item['description'],
                                    'debit'         =>  $credit,
                                    'balance'       =>  $balance,
                                    'due_date'      =>  $item['due_date']
                                );

                                $this->app_model->insert('ledger', $entryData);

                                $sl_amount -= $credit;
                            }
                        }
                    }
                }

                if ($preop_doc_no) // If has preop -> UFT
                {
                    $preop_data = $this->app_model->get_preopdata($tenant_id);
                    if ($gl_amount > 0)
                    {
                        foreach ($preop_data as $preop)
                        {
                            if ($gl_amount > 0)
                            {
                                $sl_refNo = $this->app_model->generate_refNo();
                                $gl_refNo = $this->app_model->gl_refNo();

                                $debit;
                                $balance;
                                if ($gl_amount >= $preop['amount'])
                                {
                                   $debit   = $preop['amount'];
                                   $balance = 0;
                                }
                                else
                                {
                                    $balance = abs($preop['amount'] - $gl_amount);
                                    $debit   = $gl_amount;
                                }

                                $gl_code;
                                $doc_type;
                                if ($preop['description'] == 'Advance Rent')
                                {
                                    $doc_type = 'Advance Payment';
                                    $gl_code  = '10.20.01.01.02.01';
                                }
                                elseif ($preop['description'] == 'Security Deposit' || $preop['description'] == 'Security Deposit - Kiosk and Cart')
                                {
                                    $doc_type = 'UFT-Payment';
                                    $gl_code  = '10.20.01.01.03.12';
                                }
                                elseif ($preop['description'] == 'Construction Bond')
                                {
                                    $doc_type = 'UFT-Payment';
                                    $gl_code  = '10.20.01.01.03.10';
                                }

                                $closingRefNo = $this->app_model->generate_ClosingRefNo();


                                $entry_CIB = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Payment',
                                    'ref_no'           =>  $gl_refNo,
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'     =>  $this->session->userdata('company_code'),
                                    'department_code'  =>  '01.04',
                                    'debit'            =>  $debit,
                                    'bank_name'        =>  $bank_name,
                                    'bank_code'        =>  $bank_code,
                                    'prepared_by'      =>  $this->session->userdata('id'),
                                    'ft_ref'           =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $entry_CIB);
                                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                                $preop_entry = array(
                                    'posting_date'     =>    $posting_date,
                                    'transaction_date' =>    $transaction_date,
                                    'document_type'    =>    'Payment',
                                    'ref_no'           =>    $gl_refNo,
                                    'doc_no'           =>    $trans_no,
                                    'tenant_id'        =>    $tenant_id,
                                    'gl_accountID'     =>    $this->app_model->gl_accountID($gl_code),
                                    'company_code'     =>    $this->session->userdata('company_code'),
                                    'department_code'  =>    '01.04',
                                    'credit'           =>    -1 * $debit,
                                    'status'           =>    'Preop Clearing',
                                    'bank_name'        =>    $bank_name,
                                    'bank_code'        =>    $bank_code,
                                    'prepared_by'      =>    $this->session->userdata('id'),
                                    'ft_ref'           =>   $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $preop_entry);
                                $this->app_model->insert('subsidiary_ledger', $preop_entry);

                                $sl_preOp = array(
                                    'posting_date'      =>    $posting_date,
                                    'transaction_date'  =>    $transaction_date,
                                    'document_type'     =>    $doc_type,
                                    'ref_no'            =>    $sl_refNo,
                                    'doc_no'            =>    $trans_no,
                                    'tenant_id'         =>    $tenant_id,
                                    'contract_no'       =>    $contract_no,
                                    'description'       =>    $preop['description'] . "-" . $trade_name,
                                    'charges_type'      =>    $preop['description'],
                                    'debit'             =>    $debit,
                                    'balance'           =>    $balance
                                );
                                $this->app_model->insert('ledger', $sl_preOp);

                                $sl_amount -= $preop['amount'];
                                $gl_amount -= $preop['amount'];

                                if ($gl_amount >= 0)
                                {
                                    $this->app_model->delete('tmp_preoperationcharges', 'id', $preop['id']);
                                }
                                else
                                {
                                    $data = array('amount' => ABS($gl_amount));
                                    $this->app_model->update($data, $preop['id'], 'tmp_preoperationcharges');
                                }
                            }
                        }
                    }
                }


                if ($amount_paid > $total_amount)
                {
                    $advance_amount = $amount_paid - $total_amount;

                    if ($advance_amount >= 1)
                    {
                       $dataLedger = array(
                            'posting_date'       =>  $posting_date,
                            'transaction_date'   =>  $posting_date,
                            'document_type'      =>  'Advance Payment',
                            'doc_no'             =>  $trans_no,
                            'ref_no'             =>  $this->app_model->generate_refNo(),
                            'tenant_id'          =>  $tenant_id,
                            'contract_no'        =>  $contract_no,
                            'description'        =>  'Advance Payment-' . $trade_name,
                            'debit'              =>  $advance_amount,
                            'credit'             =>  0,
                            'balance'            =>  $advance_amount
                        );

                        $this->app_model->insert('ledger', $dataLedger);


                        $closingRefNo = $this->app_model->generate_ClosingRefNo();
                        $advance_refNo = $this->app_model->gl_refNo();
                        $advance_CIB = array(
                            'posting_date'     =>  $posting_date,
                            'transaction_date' =>  $transaction_date,
                            'document_type'    =>  'Payment',
                            'ref_no'           =>  $advance_refNo,
                            'doc_no'           =>  $trans_no,
                            'tenant_id'        =>  $tenant_id,
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                            'company_code'     =>  $this->session->userdata('company_code'),
                            'department_code'  =>  '01.04',
                            'tag'              =>  'Advance',
                            'debit'            =>  $advance_amount,
                            'bank_name'        =>  $bank_name,
                            'bank_code'        =>  $bank_code,
                            'prepared_by'      =>  $this->session->userdata('id'),
                            'ft_ref'           =>  $closingRefNo
                        );

                        $this->app_model->insert('general_ledger', $advance_CIB);
                        $this->app_model->insert('subsidiary_ledger', $advance_CIB);

                        $advance_RRC = array(
                            'posting_date'     =>  $posting_date,
                            'transaction_date' =>  $transaction_date,
                            'document_type'    =>  'Payment',
                            'ref_no'           =>  $advance_refNo,
                            'doc_no'           =>  $trans_no,
                            'tenant_id'        =>  $tenant_id,
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                            'company_code'     =>  $this->session->userdata('company_code'),
                            'department_code'  =>  '01.04',
                            'tag'              =>  'Advance',
                            'status'           =>  'URI Clearing',
                            'credit'           =>  -1 * $advance_amount,
                            'bank_name'        =>  $bank_name,
                            'bank_code'        =>  $bank_code,
                            'prepared_by'      =>  $this->session->userdata('id'),
                            'ft_ref'           =>  $closingRefNo
                        );
                        $this->app_model->insert('general_ledger', $advance_RRC);
                        $this->app_model->insert('subsidiary_ledger', $advance_RRC);



                    }
                }


                // ========= Check if payment is delayed to apply penalty ========= //

                if (!$this->app_model->is_penaltyExempt($tenant_id) && $billing_period != 'Upon Signing of Notice')
                {

                    if (date('Y-m-d', strtotime($posting_date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
                    {
                        $daysOfMonth         = date('t', strtotime($posting_date));
                        $daydiff             = floor((abs(strtotime($posting_date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                        $sundays             = $this->app_model->get_sundays($collection_date, $posting_date);
                        $daydiff             = $daydiff - $sundays;
                        $penalty_latepayment = ($amount_paid * .02 * $daydiff) / $daysOfMonth;

                        $penaltyEntry = array(
                            'tenant_id'     =>  $tenant_id,
                            'posting_date'  =>  $posting_date,
                            'contract_no'   =>  $contract_no,
                            'doc_no'        =>  $trans_no,
                            'description'   =>  'Late Payment-' . $trade_name,
                            'amount'        =>  round($penalty_latepayment, 2)
                        );
                        $this->app_model->insert('tmp_latepaymentpenalty', $penaltyEntry);
                    }

                }

                $paymentData = array(
                    'posting_date' =>   $posting_date,
                    'soa_no'       =>   $soa_no,
                    'amount_paid'  =>   $amount_paid,
                    'tenant_id'    =>   $tenant_id,
                    'doc_no'       =>  $trans_no,
                );

                $this->app_model->insert('payment', $paymentData);

                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'RR Clearing', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'DB_error';
                }
                else
                {
                    $response['msg'] = "Success";
                }
            }

            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function reg_fundTransfer()
    {   

        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['banks']          = $this->app_model->getAll('accredited_banks');
            $data['current_date']   = $this->_currentDate;
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['flashdata']      = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/ift_payment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    /*public function ift_payment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['banks']          = $this->app_model->getAll('accredited_banks');
            $data['current_date']   = $this->_currentDate;
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['flashdata']      = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/ift_payment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }*/


    public function save_reverseInternalPayment()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
        {
            $response         = array();
            $date             = new DateTime();
            $timeStamp        = $date->getTimestamp();
            $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
            $contract_no      = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
            $bank_code        = $this->sanitize($this->input->post('bank_code'));
            $amount           = $this->input->post('amount');
            $tender_type      = $this->input->post('tender_type');
            $bank_name        = $this->sanitize($this->input->post('bank_name'));
            $receipt_no       = $this->input->post('receipt_no');
            $trade_name       = $this->sanitize($this->input->post('trade_name'));
            $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
            $tender_amount    = $amount_paid;
            $transaction_date = $this->sanitize($this->input->post('transaction_date'));
            $check_date       = $this->sanitize($this->input->post('check_date'));
            $sl_refNo         = $this->input->post('ref_no');
            $id               = $this->input->post('id');
            $remarks          = "";
            $doc_nos          = $this->input->post('doc_no');
            $ds_no            = $this->input->post('ds_no');
            $total_payable    = str_replace(",", "", $this->sanitize($this->input->post('total_payable')));
            $advance_refNo    = $this->input->post('advance_refNo');

            $daysOfMonth      = date('t', strtotime($this->_currentDate));
            $clearing_postingDate;
            $store_name;
            $supp_doc = "";
            $gl_code = "";
            $pdc_status = "";

            // $doc_no = array_unique($doc_no);

            $file_name =  $tenant_id . $timeStamp . '.pdf';

            if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
            {
                $response['msg'] = 'Required';
            }
            else
            {
                try {

                    $this->db->trans_start(); // Transaction function starts here!!!
                    $store_code    = $this->app_model->tenant_storeCode($tenant_id);
                    $store_details = $this->app_model->store_details(trim($store_code));
                    $details_soa   = $this->app_model->details_soa($tenant_id);

                    // Upload Attchament

                    if ($tender_type == 'Check' || $tender_type == 'Bank to Bank') {
                        $targetPath    = getcwd() . '/assets/payment_docs/';
                        $supp_doc      = $this->upload_image($targetPath, 'deposit_slip', $tenant_id);
                    }



                    $pdf = new FPDF('p','mm','A4');
                    $pdf->AddPage();
                    $pdf->setDisplayMode ('fullpage');
                    $logoPath = getcwd() . '/assets/other_img/';
                    foreach ($store_details as $detail)
                    {
                        $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                        $pdf->ln();
                        $pdf->setFont ('times','B',14);
                        $pdf->cell(75, 6, " ", 0, 0, 'L');
                        $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                        $store_name = $detail['store_name'];
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFillColor(35, 35, 35);
                        $pdf->cell(35, 6, " ", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->setFont ('times','',14);
                        $pdf->cell(15, 0, " ", 0, 0, 'L');
                        $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

                        $pdf->ln();
                        $pdf->ln();
                    }

                    foreach ($details_soa as $detail)
                    {
                        $pdf->setFont('times','',10);
                        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "TIN No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');

                        $pdf->ln();
                        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                        $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Date", 0, 0, 'L');
                        $pdf->cell(60, 6, $transaction_date, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                        $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                        $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();
                    }


                    $pdf->ln();
                    $pdf->setFont ('times','B',10);
                    $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont ('times','B',16);
                    $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
                    $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
                    $pdf->cell(30, 8, "Description", 0, 0, 'C');
                    $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
                    $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
                    $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
                    $pdf->setFont('times','',10);


                    if ($tender_type == 'Cash' || $tender_type == 'Bank to Bank') {
                        $gl_code = '10.10.01.01.02';
                    } elseif ($tender_type == 'Check') {
                        if ($check_date > $transaction_date) {
                            $gl_code = '10.10.01.03.07.01';
                            $pdc_status = 'PDC';
                        } else {
                            $gl_code = '10.10.01.01.02';
                        }
                    } elseif ($tender_type == 'JV payment - Business Unit') {
                        $gl_code = $this->app_model->bu_entry();
                    } else {
                        $gl_code = '10.10.01.03.11';
                    }


                    for ($i=0; $i < count($id) ; $i++)
                    {
                        if ($tender_amount > 1)
                        {
                            $rr_forCLearing = $this->app_model->ARNTI_forClearing($id[$i]);

                            foreach ($rr_forCLearing as $value)
                            {
                                $pdf->ln();
                                $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                $pdf->cell(30, 8, $value['doc_no'], 0, 0, 'C');
                                if ($value['gl_code'] == '10.10.01.03.16') // For Baisc Rent
                                {
                                    $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                                }
                                else
                                {
                                    $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                                }


                                if ($tender_amount >= $value['amount'])
                                {
                                    $clearing_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  str_replace(",", "", $value['amount']),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                                    $rr_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * str_replace(",", "", $value['amount']),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>   $pdc_status,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $rr_entry);
                                }
                                else
                                {
                                    $clearing_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  str_replace(",", "", $tender_amount),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                                    $rr_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * str_replace(",", "", $tender_amount),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  $pdc_status,
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $rr_entry);

                                }

                                $tender_amount -= $value['amount'];
                                $pdf->cell(30, 8, $value['posting_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, $value['due_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, number_format($amount[$i], 2), 0, 0, 'R');
                            }
                        }

                    }

                    if ($tender_amount >= 1)
                    {
                        $advance_sl_refNo = $this->app_model->generate_refNo();
                        $advance_gl_refNo = $this->app_model->gl_refNo();

                        $entry_CIB = array(
                            'posting_date'      =>  $transaction_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_gl_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'debit'             =>  $tender_amount,
                            'bank_name'         =>  $bank_name,
                            'bank_code'         =>  $bank_code,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );
                        $this->app_model->insert('general_ledger', $entry_CIB);
                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                        $advance_unearned = array(
                            'posting_date'      =>  $transaction_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_gl_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'credit'            =>  -1 * $tender_amount,
                            'bank_name'         =>  $bank_name,
                            'bank_code'         =>  $bank_code,
                            'status'            =>   $pdc_status,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );

                        $this->app_model->insert('general_ledger', $advance_unearned);
                        $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                        $advance_ledger = array(
                            'posting_date'      =>    $transaction_date,
                            'transaction_date'  =>    $this->_currentDate,
                            'document_type'     =>    'Advance Payment',
                            'ref_no'            =>    $advance_sl_refNo,
                            'doc_no'            =>    $receipt_no,
                            'tenant_id'         =>    $tenant_id,
                            'contract_no'       =>    $contract_no,
                            'description'       =>    'Advance Payment' . "-" . $trade_name,
                            'debit'             =>    $tender_amount,
                            'balance'           =>    $tender_amount
                        );

                        $this->app_model->insert('ledger', $advance_ledger);

                        $reportData = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $receipt_no,
                            'posting_date'  =>  $transaction_date,
                            'description'   =>  'Advance Payment',
                            'amount'        =>  $tender_amount
                        );

                        $this->app_model->insert('monthly_receivable_report', $reportData);

                    }


                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
                    $pdf->cell(100, 8, "Payment Date:" . $transaction_date, 0, 0, 'L');
                    $pdf->ln();


                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
                    $pdf->cell(60, 4, 'Bank to Bank', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
                    $pdf->ln();

                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $bank_name, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
                    $pdf->cell(60, 4, '', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
                    if($amount_paid - $total_payable >= 0)
                    {
                        $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');
                    }
                    else
                    {
                        $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');
                    }
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
                    $pdf->cell(60, 4, '', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');

                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $trade_name, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $store_name, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','',10);
                    $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');


                    $paymentScheme = array(
                        'tenant_id'        =>   $tenant_id,
                        'contract_no'      =>   $contract_no,
                        'tenancy_type'     =>   $tenancy_type,
                        'receipt_no'       =>   $receipt_no,
                        'tender_typeCode'  =>   $tender_type,
                        'tender_typeDesc'  =>   'Bank to Bank',
                        'soa_no'           =>   $this->app_model->get_latestSOANo($tenant_id),
                        'amount_due'       =>   $total_payable,
                        'amount_paid'      =>   $amount_paid,
                        'bank'             =>   $bank_name,
                        'check_date'       =>   $check_date,
                        'payor'            =>   $trade_name,
                        'payee'            =>   $store_name,
                        'check_no'         =>   $ds_no,
                        'supp_doc'         =>   $supp_doc,
                        'receipt_doc'      =>   $file_name
                    );


                    $paymentSuppDocs = array(
                        'tenant_id'  => $tenant_id,
                        'receipt_no' => $receipt_no,
                        'file_name'  => $supp_doc
                    );


                     $reverse_internalPaymentData = array(
                        'tenant_id'    => $tenant_id,
                        'posting_date' => $transaction_date,
                        'amount'       => $amount_paid,
                        'doc_no'       => $receipt_no,
                        'bank_code'    => $bank_code
                    );

                    $this->app_model->insert('reverse_internalPayment', $reverse_internalPaymentData);
                    $this->app_model->insert('payment_supportingdocs', $paymentSuppDocs);
                    $this->app_model->insert('payment_scheme', $paymentScheme);
                    $this->db->trans_complete(); // End of transaction function

                }
                catch (Exception $e)
                {

                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'RR Clearing', 'error_msg' => $e->getMessage()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'DB_error';
                }

                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }

            echo json_encode($response);
        }
    }

    public function save_reg_fundTransfer()
    {
        if (!$this->session->userdata('leasing_logged_in'))
            JSONResponse(['type'=>'error', 'msg'=>'Unauthorize Access!']);

    
        $response         = array();
        $date             = new DateTime();
        $timeStamp        = $date->getTimestamp();

        $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
        $contract_no      = $this->sanitize($this->input->post('contract_no'));
        $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));

        $bank_code        = $this->sanitize($this->input->post('bank_code'));
        $bank_name        = $this->sanitize($this->input->post('bank_name'));
        $receipt_no       = "PR" . $this->sanitize($this->input->post('receipt_no'));

        $ds_no            = $this->input->post('ds_no');

        $trade_name       = $this->sanitize($this->input->post('trade_name'));
        $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
        $tender_amount    = $amount_paid;

        $posting_date = $this->sanitize($this->input->post('posting_date'));

        $ufts             = $this->input->post('ufts');
        
        $total_payable    = str_replace(",", "", $this->sanitize($this->input->post('total_payable')));

        $remarks          = "";


        $daysOfMonth      = date('t', strtotime($this->_currentDate));
        $store_name;


        $file_name =  $tenant_id . $timeStamp . '.pdf';

        $receipts = $this->db->query("SELECT `id` FROM `payment_scheme` WHERE `receipt_no` = '$receipt_no' OR `receipt_no` = 'PR".$receipt_no."'")->result_array();

        if(count($receipts)>0){
            JSONResponse(['type'=>'error', 'msg'=>'Receipt No already in use.']);
        }

        if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
        { 
            JSONResponse(['type'=>'error', 'msg'=>'Please fill all the required fields!']);
        }


    
        try {

            $this->db->trans_start(); // Transaction function starts here!!!
            $store_code    = $this->app_model->tenant_storeCode($tenant_id);

            $store_details = $this->app_model->store_details(trim($store_code));
            $store         = (object)($store_details ? $store_details[0] : []);
            $store_name    = $store->store_name;

            $details_soa   = $this->app_model->details_soa($tenant_id);
            $detail        = (object)($details_soa ? $details_soa[0] : []);

            // Upload Deposit Slip

            $targetPath    = getcwd() . '/assets/payment_docs/';
            $supp_doc      =  save_uploaded_file($targetPath, 'deposit_slip', $tenant_id);

            $pdf = new FPDF('p','mm','A4');
            $pdf->AddPage();
            $pdf->setDisplayMode ('fullpage');

            $logoPath = getcwd() . '/assets/other_img/'. $store->logo;

            $pdf->cell(20, 20, $pdf->Image($logoPath, 100, $pdf->GetY(), 15), 0, 0, 'C', false);
            $pdf->ln();
            $pdf->setFont ('times','B',14);
            $pdf->cell(75, 6, " ", 0, 0, 'L');
            $pdf->cell(40, 10, strtoupper($store_name), 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(35, 35, 35);
            $pdf->cell(35, 6, " ", 0, 0, 'L');
            $pdf->ln();
            $pdf->setFont ('times','',14);
            $pdf->cell(15, 0, " ", 0, 0, 'L');
            $pdf->cell(0, 10, $store->store_address, 0, 0, 'C');

            $pdf->ln();
            $pdf->ln();

            if($detail){
                $pdf->setFont('times','',10);
                $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "TIN No.", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->tin, 1, 0, 'L');

                $pdf->ln();
                $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Date", 0, 0, 'L');
                $pdf->cell(60, 6, $posting_date, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                $pdf->ln();
                $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                $pdf->cell(60, 6, $detail->corporate_name, 1, 0, 'L');
                $pdf->cell(5, 6, " ", 0, 0, 'L');
                $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                $pdf->ln();
                $pdf->ln();
            }


            $pdf->ln();
            $pdf->setFont ('times','B',10);
            $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont ('times','B',16);
            $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
            $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
            $pdf->cell(30, 8, "Description", 0, 0, 'C');
            $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
            $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
            $pdf->setFont('times','',10);


            foreach ($ufts as $key => $uft) {
                $uft = (object) $uft;

                $uft->amount = str_replace(",", "", $uft->amount);

                if($tender_amount > 0){

                    $pdf->ln();
                    $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                    $pdf->cell(30, 8, $uft->doc_no, 0, 0, 'C');

                    if ($uft->gl_accountID == 4){
                        $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                    }
                    elseif($uft->gl_accountID == 7){
                        $pdf->cell(30, 8, "Advance-" . $trade_name, 0, 0, 'C');
                    }
                    else{
                        $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                    }

                    $clearing_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $uft->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $uft->gl_accountID,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'debit'             =>  ($tender_amount >= $uft->amount ? $uft->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id'),
                        'status'            =>  $uft->status,
                        'ft_ref'            =>  $uft->ft_ref,
                    );

                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                    $rr_entry = array(
                        'posting_date'      =>  $posting_date,
                        'transaction_date'  =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $uft->ref_no,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $uft->gl_accountID,
                        'company_code'      =>  $store->company_code,
                        'department_code'   =>  '01.04',
                        'credit'            =>  -1 * ($tender_amount >= $uft->amount ? $uft->amount : $tender_amount),
                        'bank_name'         =>  $bank_name,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id'),
                        'ft_ref'            =>  $uft->ft_ref
                    );

                    $this->app_model->insert('subsidiary_ledger', $rr_entry);


                    $tender_amount -= $uft->amount;

                    $pdf->cell(30, 8, $uft->posting_date, 0, 0, 'C');
                    $pdf->cell(30, 8, $uft->due_date, 0, 0, 'C');
                    $pdf->cell(30, 8, number_format($uft->amount, 2), 0, 0, 'R');

                }
            }


            if ($tender_amount >= 1)
            {
                $advance_sl_refNo = $this->app_model->generate_refNo();
                $advance_gl_refNo = $this->app_model->gl_refNo();

                $entry_CIB = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );
                $this->app_model->insert('general_ledger', $entry_CIB);
                $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                $advance_unearned = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_gl_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * $tender_amount,
                    'bank_name'         =>  $bank_name,
                    'bank_code'         =>  $bank_code,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $advance_unearned);
                $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                $advance_ledger = array(
                    'posting_date'      =>    $posting_date,
                    'transaction_date'  =>    $this->_currentDate,
                    'document_type'     =>    'Advance Payment',
                    'ref_no'            =>    $advance_sl_refNo,
                    'doc_no'            =>    $receipt_no,
                    'tenant_id'         =>    $tenant_id,
                    'contract_no'       =>    $contract_no,
                    'description'       =>    'Advance Payment' . "-" . $trade_name,
                    'debit'             =>    $tender_amount,
                    'balance'           =>    $tender_amount
                );

                $this->app_model->insert('ledger', $advance_ledger);

                $reportData = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $receipt_no,
                    'posting_date'  =>  $posting_date,
                    'description'   =>  'Advance Payment',
                    'amount'        =>  $tender_amount
                );

                $this->app_model->insert('monthly_receivable_report', $reportData);

            }


            $pdf->ln();
            $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->ln();

            $pdf->setFont('times','B',10);
            $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
            $pdf->cell(100, 8, "Payment Date:" . $posting_date, 0, 0, 'L');
            $pdf->ln();


            $pdf->setFont('times','',10);
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
            $pdf->cell(60, 4, 'Bank to Bank', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
            $pdf->ln();

            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
            $pdf->cell(60, 4, $bank_name, 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
            $pdf->cell(60, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
            if($amount_paid - $total_payable >= 0)
            {
                $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');
            }
            else
            {
                $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');
            }
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
            $pdf->cell(60, 4, '', 0, 0, 'L');
            $pdf->cell(5, 4, " ", 0, 0, 'L');
            $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
            $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');

            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
            $pdf->cell(60, 4, $trade_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
            $pdf->cell(60, 4, $store_name, 0, 0, 'L');
            $pdf->ln();
            $pdf->cell(20, 4, "     ", 0, 0, 'L');
            $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
            $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $pdf->setFont('times','',10);
            $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->ln();
            $pdf->setFont('times','B',10);
            $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');


            $paymentScheme = array(
                'tenant_id'        =>   $tenant_id,
                'contract_no'      =>   $contract_no,
                'tenancy_type'     =>   $tenancy_type,
                'receipt_no'       =>   $receipt_no,
                'tender_typeCode'  =>   '',
                'tender_typeDesc'  =>   'Bank to Bank',
                'soa_no'           =>   $this->app_model->get_latestSOANo($tenant_id),
                'amount_due'       =>   $total_payable,
                'amount_paid'      =>   $amount_paid,
                'bank'             =>   $bank_name,
                'check_date'       =>   '',
                'payor'            =>   $trade_name,
                'payee'            =>   $store_name,
                'check_no'         =>   $ds_no,
                'supp_doc'         =>   $supp_doc,
                'receipt_doc'      =>   $file_name
            );


            $paymentSuppDocs = array(
                'tenant_id'  => $tenant_id,
                'receipt_no' => $receipt_no,
                'file_name'  => $supp_doc
            );

            $this->app_model->insert('payment_supportingdocs', $paymentSuppDocs);
            $this->app_model->insert('payment_scheme', $paymentScheme);
            $this->db->trans_complete(); // End of transaction function

        }
        catch (Exception $e)
        {

            $this->db->trans_rollback(); // If failed rollback all queries
            $error = array('action' => 'RR Clearing', 'error_msg' => $e->getMessage()); //Log error message to `error_log` table
            $this->app_model->insert('error_log', $error);
             
            JSONResponse(['type'=>'error', 'msg'=>$e->getMessage()]);
        }

        $file_dir = base_url() . 'assets/pdf/' . $file_name;

        $pdf->Output('assets/pdf/' . $file_name , 'F');

        JSONResponse(['type'=>'success', 'msg'=>'Transaction Complete', 'file_dir'=>$file_dir]);
    }


    /*public function save_reg_fundTransfer()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response         = array();
            $date             = new DateTime();
            $timeStamp        = $date->getTimestamp();
            $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
            $contract_no      = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
            $bank_code        = $this->sanitize($this->input->post('bank_code'));
            $amount           = $this->input->post('amount');
            $bank_name        = $this->sanitize($this->input->post('bank_name'));
            $receipt_no       = "PR" . $this->sanitize($this->input->post('receipt_no'));
            $trade_name       = $this->sanitize($this->input->post('trade_name'));
            $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
            $tender_amount    = $amount_paid;
            $transaction_date = $this->sanitize($this->input->post('transaction_date'));
            $sl_refNo         = $this->input->post('ref_no');
            $id               = $this->input->post('id');
            $remarks          = "";
            $doc_nos          = $this->input->post('doc_no');
            $ds_no            = $this->input->post('ds_no');
            $total_payable    = str_replace(",", "", $this->sanitize($this->input->post('total_payable')));

            $advance_refNo    = $this->input->post('advance_refNo');

            $daysOfMonth      = date('t', strtotime($this->_currentDate));
            $clearing_postingDate;
            $store_name;


            // $doc_no = array_unique($doc_no);


            $file_name =  $tenant_id . $timeStamp . '.pdf';

            if ($amount_paid == 0 || $bank_name == '' || $bank_name == '? undefined:undefined ?')
            {
                $response['msg'] = 'Required';
            }
            else
            {
                try {

                    $this->db->trans_start(); // Transaction function starts here!!!
                    $store_code    = $this->app_model->tenant_storeCode($tenant_id);
                    $store_details = $this->app_model->store_details(trim($store_code));
                    $details_soa   = $this->app_model->details_soa($tenant_id);

                    // Upload Deposit Slip

                    $targetPath    = getcwd() . '/assets/payment_docs/';
                    $supp_doc      = $this->upload_image($targetPath, 'deposit_slip', $tenant_id);

                    $pdf = new FPDF('p','mm','A4');
                    $pdf->AddPage();
                    $pdf->setDisplayMode ('fullpage');
                    $logoPath = getcwd() . '/assets/other_img/';
                    foreach ($store_details as $detail)
                    {
                        $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                        $pdf->ln();
                        $pdf->setFont ('times','B',14);
                        $pdf->cell(75, 6, " ", 0, 0, 'L');
                        $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                        $store_name = $detail['store_name'];
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFillColor(35, 35, 35);
                        $pdf->cell(35, 6, " ", 0, 0, 'L');
                        $pdf->ln();
                        $pdf->setFont ('times','',14);
                        $pdf->cell(15, 0, " ", 0, 0, 'L');
                        $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

                        $pdf->ln();
                        $pdf->ln();
                    }

                    foreach ($details_soa as $detail)
                    {
                        $pdf->setFont('times','',10);
                        $pdf->cell(30, 6, "Receipt No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $receipt_no, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "TIN No.", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');

                        $pdf->ln();
                        $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                        $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Date", 0, 0, 'L');
                        $pdf->cell(60, 6, $transaction_date, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $trade_name, 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Remarks", 0, 0, 'L');
                        $pdf->cell(60, 6, $remarks, 1, 0, 'L');
                        $pdf->ln();
                        $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                        $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                        $pdf->cell(5, 6, " ", 0, 0, 'L');
                        $pdf->cell(30, 6, "Total Payable", 0, 0, 'L');
                        $pdf->cell(60, 6, number_format($total_payable, 2), 1, 0, 'L');
                        $pdf->ln();
                        $pdf->ln();
                    }


                    $pdf->ln();
                    $pdf->setFont ('times','B',10);
                    $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($store_name) , 0, 0, 'R');
                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont ('times','B',16);
                    $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
                    $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
                    $pdf->cell(30, 8, "Description", 0, 0, 'C');
                    $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
                    $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
                    $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
                    $pdf->setFont('times','',10);


                    for ($i=0; $i < count($sl_refNo) ; $i++)
                    {
                        if ($tender_amount > 1)
                        {
                            $rr_forCLearing = $this->app_model->rr_forCLearing($tenant_id, $sl_refNo[$i]);

                            foreach ($rr_forCLearing as $value)
                            {
                                $pdf->ln();
                                $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                                $pdf->cell(30, 8, $value['doc_no'], 0, 0, 'C');
                                if ($value['gl_code'] == '10.10.01.03.16') // For Baisc Rent
                                {
                                    $pdf->cell(30, 8, "Basic-" . $trade_name, 0, 0, 'C');
                                }
                                else
                                {
                                    $pdf->cell(30, 8, "Other-" . $trade_name, 0, 0, 'C');
                                }


                                if ($tender_amount >= $value['amount'])
                                {
                                    $clearing_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  str_replace(",", "", $value['amount']),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  $value['status'],
                                        'ft_ref'            =>  $value['ft_ref'],
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                                    $rr_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * str_replace(",", "", $value['amount']),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'ft_ref'            =>  $value['ft_ref'],
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $rr_entry);
                                }
                                else
                                {
                                    $clearing_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  str_replace(",", "", $tender_amount),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'status'            =>  $value['status'],
                                        'ft_ref'            =>  $value['ft_ref'],
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $clearing_entry);

                                    $rr_entry = array(
                                        'posting_date'      =>  $transaction_date,
                                        'transaction_date'  =>  $this->_currentDate,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $value['ref_no'],
                                        'doc_no'            =>  $receipt_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $value['gl_accountID'],
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * str_replace(",", "", $tender_amount),
                                        'bank_name'         =>  $bank_name,
                                        'bank_code'         =>  $bank_code,
                                        'ft_ref'            =>  $value['ft_ref'],
                                        'prepared_by'       =>  $this->session->userdata('id')
                                    );

                                    $this->app_model->insert('subsidiary_ledger', $rr_entry);
                                }

                                $tender_amount -= $value['amount'];
                                $pdf->cell(30, 8, $value['posting_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, $value['due_date'], 0, 0, 'C');
                                $pdf->cell(30, 8, number_format($amount[$i], 2), 0, 0, 'R');
                            }
                        }

                    }

                    if ($tender_amount >= 1)
                    {
                        $advance_sl_refNo = $this->app_model->generate_refNo();
                        $advance_gl_refNo = $this->app_model->gl_refNo();

                        $entry_CIB = array(
                            'posting_date'      =>  $transaction_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_gl_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'debit'             =>  $tender_amount,
                            'bank_name'         =>  $bank_name,
                            'bank_code'         =>  $bank_code,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );
                        $this->app_model->insert('general_ledger', $entry_CIB);
                        $this->app_model->insert('subsidiary_ledger', $entry_CIB);


                        $advance_unearned = array(
                            'posting_date'      =>  $transaction_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_gl_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'credit'            =>  -1 * $tender_amount,
                            'bank_name'         =>  $bank_name,
                            'bank_code'         =>  $bank_code,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );

                        $this->app_model->insert('general_ledger', $advance_unearned);
                        $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                        $advance_ledger = array(
                            'posting_date'      =>    $transaction_date,
                            'transaction_date'  =>    $transaction_date,
                            'document_type'     =>    'Advance Payment',
                            'ref_no'            =>    $advance_sl_refNo,
                            'doc_no'            =>    $receipt_no,
                            'tenant_id'         =>    $tenant_id,
                            'contract_no'       =>    $contract_no,
                            'description'       =>    'Advance Payment' . "-" . $trade_name,
                            'debit'             =>    $tender_amount,
                            'balance'           =>    $tender_amount
                        );

                        $this->app_model->insert('ledger', $advance_ledger);

                        $reportData = array(
                            'tenant_id'     =>  $tenant_id,
                            'doc_no'        =>  $receipt_no,
                            'posting_date'  =>  $transaction_date,
                            'description'   =>  'Advance Payment',
                            'amount'        =>  $tender_amount
                        );

                        $this->app_model->insert('monthly_receivable_report', $reportData);

                    }


                    $pdf->ln();
                    $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                    $pdf->ln();

                    $pdf->setFont('times','B',10);
                    $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
                    $pdf->cell(100, 8, "Payment Date:" . $transaction_date, 0, 0, 'L');
                    $pdf->ln();


                    $pdf->setFont('times','',10);
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
                    $pdf->cell(60, 4, 'Bank to Bank', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($total_payable, 2), 0, 0, 'L');
                    $pdf->ln();

                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $bank_name, 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
                    $pdf->cell(60, 4, '', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
                    if($amount_paid - $total_payable >= 0)
                    {
                        $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');
                    }
                    else
                    {
                        $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');
                    }
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
                    $pdf->cell(60, 4, '', 0, 0, 'L');
                    $pdf->cell(5, 4, " ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
                    $pdf->cell(60, 4, "P " . number_format(abs($amount_paid - $total_payable), 2), 0, 0, 'L');

                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $trade_name, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $store_name, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(20, 4, "     ", 0, 0, 'L');
                    $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
                    $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();

                    $pdf->setFont('times','',10);
                    $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->setFont('times','B',10);
                    $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');


                    $paymentScheme = array(
                        'tenant_id'        =>   $tenant_id,
                        'contract_no'      =>   $contract_no,
                        'tenancy_type'     =>   $tenancy_type,
                        'receipt_no'       =>   $receipt_no,
                        'tender_typeCode'  =>   '',
                        'tender_typeDesc'  =>   'Bank to Bank',
                        'soa_no'           =>   $this->app_model->get_latestSOANo($tenant_id),
                        'amount_due'       =>   $total_payable,
                        'amount_paid'      =>   $amount_paid,
                        'bank'             =>   $bank_name,
                        'check_date'       =>   '',
                        'payor'            =>   $trade_name,
                        'payee'            =>   $store_name,
                        'check_no'         =>   $ds_no,
                        'supp_doc'         =>   $supp_doc,
                        'receipt_doc'      =>   $file_name
                    );


                    $paymentSuppDocs = array(
                        'tenant_id'  => $tenant_id,
                        'receipt_no' => $receipt_no,
                        'file_name'  => $supp_doc
                    );

                    $this->app_model->insert('payment_supportingdocs', $paymentSuppDocs);
                    $this->app_model->insert('payment_scheme', $paymentScheme);
                    $this->db->trans_complete(); // End of transaction function

                }
                catch (Exception $e)
                {

                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'RR Clearing', 'error_msg' => $e->getMessage()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'DB_error';
                }

                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";
            }

            echo json_encode($response);
        }
    }*/


    public function save_internalPayment()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
        {
            $response         = array();
            $tenancy_type     = $this->sanitize($this->input->post('tenancy_type'));
            $trade_name       = $this->sanitize($this->input->post('trade_name'));
            $tenant_id        = $this->sanitize($this->input->post('tenant_id'));
            $contract_no      = $this->sanitize($this->input->post('contract_no'));
            $store_name       = $this->sanitize($this->input->post('store_name'));
            $posting_date     = $this->sanitize($this->input->post('posting_date'));
            $billing_period   = $this->sanitize($this->input->post('billing_period'));
            $transaction_date = $this->_currentDate;
            $total_amount     = str_replace(",", "", $this->sanitize($this->input->post('total_amount')));
            $amount_paid      = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
            $trans_no         = $this->app_model->generate_InternalTransactionNo();
            $soa_no           = $this->input->post('soa_no');
            $doc_no           = $this->input->post('doc_no');
            $preop_doc_no     = $this->input->post('preop_doc_no');
            $retro_doc_no     = $this->input->post('retro_doc_no');
            $ds_no            = $this->sanitize($this->input->post('ds_no'));
            $collection_date  = $this->app_model->get_latestCollectionDate($tenant_id);
            $receipt_no       = "";


            if ($amount_paid == 0 || $store_name == '' || $store_name == '? undefined:undefined ?')
            {
               $response['msg'] = 'Required';
            }
            else
            {
                $this->db->trans_start(); // Transaction function starts here!!!

                $sl_refNo       = $this->app_model->generate_refNo();
                $gl_refNo       = $this->app_model->gl_refNo();


                $tenant_penalty = $this->app_model->get_penalty($tenant_id, $contract_no);

                $sl_amount      = $amount_paid;
                $gl_amount      = $amount_paid;
                $invoice_amount = $amount_paid;


                $data_internalPayment = array(
                    'tenant_id'    => $tenant_id,
                    'store'        => $store_name,
                    'posting_date' => $posting_date,
                    'doc_no'       => $trans_no,
                    'amount'       => $amount_paid
                );
                $this->app_model->insert('internal_payment', $data_internalPayment);

                // Closing of penalty to tenant ledger
                if ($tenant_penalty)
                {
                    foreach ($tenant_penalty as $penalty)
                    {
                        if ($sl_amount > 0)
                        {
                            if ($sl_amount >= $penalty['balance'])
                            {
                                $penaltyEntry = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Interal-Payment',
                                    'ref_no'           =>  $penalty['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'contract_no'      =>  $contract_no,
                                    'description'      =>  $penalty['description'],
                                    'debit'            =>  $penalty['balance'],
                                    'balance'          =>  0,
                                    'prepared_by'      =>  $this->_user_id
                                );
                                $this->app_model->insert('ledger', $penaltyEntry);
                            }
                            else
                            {
                                $penaltyEntry = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Interal-Payment',
                                    'ref_no'           =>  $penalty['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'contract_no'      =>  $contract_no,
                                    'description'      =>  $penalty['description'],
                                    'debit'            =>  $sl_amount,
                                    'balance'          =>  $penalty['balance'] - $sl_amount,
                                    'prepared_by'      =>  $this->_user_id
                                );
                                $this->app_model->insert('ledger', $penaltyEntry);
                            }

                            $sl_amount -= $penalty['balance'];
                        }

                        //delete from tmp_latepaymentpenalty
                        $this->app_model->delete_tmp_latepaymentpenalty($penalty['doc_no'], $penalty['tenant_id']);
                    }
                }


                // Closing of penalty to General Ledger
                $gl_penalties = $this->app_model->get_glPenalties($tenant_id);

                if ($gl_penalties)
                {
                    foreach ($gl_penalties as $penalty)
                    {

                        if ($gl_amount > 0)
                        {
                            $closingRefNo = $this->app_model->generate_ClosingRefNo();

                            if ($gl_amount >= $penalty['balance'])
                            {

                                $penalty_ARNTI = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  => $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $penalty['balance'],
                                    'status'            =>  $store_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_ARNTI);
                                $this->app_model->insert('subsidiary_ledger', $penalty_ARNTI);

                                $penalty_AR = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $penalty['balance'],
                                    'status'            =>  'ARNTI',
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_AR);
                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                            }
                            else
                            {
                                $penalty_ARNTI = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'debit'             =>  $gl_amount,
                                    'status'            =>  $store_name,
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_ARNTI);
                                $this->app_model->insert('subsidiary_ledger', $penalty_ARNTI);

                                $penalty_AR = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $transaction_date,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $penalty['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $gl_amount,
                                    'status'            =>  'ARNTI',
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );

                                $this->app_model->insert('general_ledger', $penalty_AR);
                                $this->app_model->insert('subsidiary_ledger', $penalty_AR);
                            }

                            $gl_amount -= $penalty['balance'];
                        }
                    }
                }


                if ($doc_no)
                {
                    $doc_no = $this->sort_ascending($doc_no);
                }

                for ($i=0; $i < count($doc_no); $i++)
                {
                    // Closing Rent Receivables to Tenant Ledger
                    $sl_tenantRR  = $this->app_model->sl_tenantRR($tenant_id, $doc_no[$i]);

                    if ($sl_tenantRR)
                    {
                        if ($sl_amount > 0)
                        {
                            foreach ($sl_tenantRR as $rr)
                            {
                                if ($sl_amount > 0)
                                {
                                    if ($sl_amount >= $rr['balance'])
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Internal-Payment',
                                            'ref_no'           =>  $rr['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $rr['description'],
                                            'debit'            =>  $rr['balance'],
                                            'balance'          =>  0,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }
                                    else
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Internal-Payment',
                                            'ref_no'           =>  $rr['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $rr['description'],
                                            'debit'            =>  $sl_amount,
                                            'balance'          =>  $rr['balance'] - $sl_amount,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }

                                    $sl_amount -= $rr['balance'];
                                }
                            }
                        }
                    }

                    // Closing Rent Receivables to General Ledger
                    $tenant_RR = $this->app_model->get_tenantRR($tenant_id, $doc_no[$i]);

                    if ($tenant_RR)
                    {
                        foreach ($tenant_RR as $rr)
                        {
                            if ($gl_amount > 0)
                            {
                                $closingRefNo = $this->app_model->generate_ClosingRefNo();

                                if ($gl_amount >= $rr['balance'])
                                {
                                    $entry_ARNTI = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $rr['balance'],
                                        'status'            =>  $store_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo

                                    );
                                    $this->app_model->insert('general_ledger', $entry_ARNTI);
                                    $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);

                                    $entry_rr = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $rr['balance'],
                                        'status'            =>  'ARNTI',
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo

                                    );

                                    $this->app_model->insert('general_ledger', $entry_rr);
                                    $this->app_model->insert('subsidiary_ledger', $entry_rr);
                                }
                                else
                                {
                                    $entry_ARNTI = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'debit'             =>  $gl_amount,
                                        'status'            =>  $store_name,
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );
                                    $this->app_model->insert('general_ledger', $entry_ARNTI);
                                    $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);

                                    $entry_rr = array(
                                        'posting_date'      =>  $posting_date,
                                        'transaction_date'  =>  $transaction_date,
                                        'document_type'     =>  'Payment',
                                        'ref_no'            =>  $rr['ref_no'],
                                        'doc_no'            =>  $trans_no,
                                        'tenant_id'         =>  $tenant_id,
                                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                        'company_code'      =>  $this->session->userdata('company_code'),
                                        'department_code'   =>  '01.04',
                                        'credit'            =>  -1 * $gl_amount,
                                        'status'            =>  'ARNTI',
                                        'prepared_by'       =>  $this->session->userdata('id'),
                                        'ft_ref'            =>  $closingRefNo
                                    );

                                    $this->app_model->insert('general_ledger', $entry_rr);
                                    $this->app_model->insert('subsidiary_ledger', $entry_rr);
                                }

                                $gl_amount -= $rr['balance'];
                            }
                        }
                    }


                    $sl_tenantAR = $this->app_model->get_slOtherCharges($tenant_id, $doc_no[$i]);

                    // Closing Account Receivables to Tenant Ledger
                    if ($sl_tenantAR)
                    {
                        if ($sl_amount > 0)
                        {
                            foreach ($sl_tenantAR as $ar)
                            {
                                if ($sl_amount > 0)
                                {
                                    $expanded_taxes = $this->app_model->get_expandedTaxes($tenant_id, $ar['doc_no']);
                                    foreach ($expanded_taxes as $value)
                                    {
                                        $sl_amount += $value['debit'];
                                        $this->app_model->delete('ledger', 'id', $value['id']);
                                    }
                                    if ($sl_amount >= $ar['balance'])
                                    {
                                        $ar_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Internal-Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $ar['description'],
                                            'debit'            =>  $ar['balance'],
                                            'balance'          =>  0,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $ar_entry);
                                    }
                                    else
                                    {
                                        $rr_entry = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Internal-Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'contract_no'      =>  $contract_no,
                                            'description'      =>  $ar['description'],
                                            'debit'            =>  $sl_amount,
                                            'balance'          =>  $ar['balance'] - $sl_amount,
                                            'prepared_by'      =>  $this->_user_id
                                        );
                                        $this->app_model->insert('ledger', $rr_entry);
                                    }

                                    $sl_amount -= $ar['balance'];
                                }
                            }
                        }
                    }


                    $tenant_AR = $this->app_model->get_tenantAR($tenant_id, $doc_no[$i]);

                    // Closing Account Receivables to General Ledger
                    if ($tenant_AR)
                    {
                        if ($gl_amount > 0)
                        {
                            foreach ($tenant_AR as $ar)
                            {
                                if ($gl_amount > 0)
                                {
                                    $closingRefNo = $this->app_model->generate_ClosingRefNo();
 
                                    if ($gl_amount >= $ar['balance'])
                                    {
                                        $entry_ARNTI = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'debit'             =>  $ar['balance'],
                                            'status'            =>  $store_name,
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );
                                        $this->app_model->insert('general_ledger', $entry_ARNTI);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);

                                        $entry_ar = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $ar['balance'],
                                            'status'            =>  'ARNTI',
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );

                                        $this->app_model->insert('general_ledger', $entry_ar);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ar);
                                    }
                                    else
                                    {
                                        $entry_ARNTI = array(
                                            'posting_date'     =>  $posting_date,
                                            'transaction_date' =>  $transaction_date,
                                            'document_type'    =>  'Payment',
                                            'ref_no'           =>  $ar['ref_no'],
                                            'doc_no'           =>  $trans_no,
                                            'tenant_id'        =>  $tenant_id,
                                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                            'company_code'     =>  $this->session->userdata('company_code'),
                                            'department_code'  =>  '01.04',
                                            'debit'            =>  $gl_amount,
                                            'status'           =>  $store_name,
                                            'prepared_by'      =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );
                                        $this->app_model->insert('general_ledger', $entry_ARNTI);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);

                                        $entry_ar = array(
                                            'posting_date'      =>  $posting_date,
                                            'transaction_date'  =>  $transaction_date,
                                            'document_type'     =>  'Payment',
                                            'ref_no'            =>  $ar['ref_no'],
                                            'doc_no'            =>  $trans_no,
                                            'tenant_id'         =>  $tenant_id,
                                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                                            'company_code'      =>  $this->session->userdata('company_code'),
                                            'department_code'   =>  '01.04',
                                            'credit'            =>  -1 * $gl_amount,
                                            'status'            =>  'ARNTI',
                                            'prepared_by'       =>  $this->session->userdata('id'),
                                            'ft_ref'            =>  $closingRefNo
                                        );

                                        $this->app_model->insert('general_ledger', $entry_ar);
                                        $this->app_model->insert('subsidiary_ledger', $entry_ar);
                                    }

                                    $gl_amount -= $ar['balance'];
                                }
                            }
                        }
                    }


                } // end doc_no for loop



                if ($doc_no)
                {
                    // ===== Charges deduction in invoicing table ====== //
                    $doc_no = $this->sort_ascending($doc_no);

                    for ($i=0; $i < count($doc_no); $i++)
                    {
                        if ($doc_no[$i] != "")
                        {
                            if ($invoice_amount > 0) // Check if Amount Paid has value
                            {
                                $docNo_total    = $this->app_model->total_perDocNo($tenant_id, $doc_no[$i]);
                                $invoice_amount -= $docNo_total;
                                if ($invoice_amount >= 0)
                                {
                                    $balance = 0;
                                    $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $trans_no);
                                }
                                else
                                {
                                    $balance = abs($invoice_amount);
                                    $this->app_model->updateInvoice_afterPayment($tenant_id, $doc_no[$i], $balance, $trans_no);
                                }
                            }
                        }
                    }
                }


                if ($retro_doc_no)
                {
                    // If has Retro Rental
                    if ($amount_paid > 0)
                    {
                        $retro_charges = $this->app_model->get_glRetroPayment($tenant_id);
                        foreach ($retro_charges as $retro)
                        {
                            if ($gl_amount > 0)
                            {   
                                
                                $credit;
                                if ($gl_amount >= $retro['balance'])
                                {
                                    $credit = $retro['balance'];
                                }
                                else
                                {
                                    $credit = $gl_amount;
                                }

                                $closingRefNo = $this->app_model->generate_ClosingRefNo();

                                $entry_ARNTI = array(
                                    'posting_date'    =>  $posting_date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'   =>  'Payment',
                                    'ref_no'          =>  $retro['ref_no'],
                                    'doc_no'          =>  $trans_no,
                                    'tenant_id'       =>  $tenant_id,
                                    'gl_accountID'    =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'    =>  $this->session->userdata('company_code'),
                                    'department_code' =>  '01.04',
                                    'debit'           =>  $credit,
                                    'status'          =>  $store_name,
                                    'prepared_by'     =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $entry_ARNTI);
                                $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);


                                $credit_RR = array(
                                    'posting_date'      =>  $posting_date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>  'Payment',
                                    'ref_no'            =>  $retro['ref_no'],
                                    'doc_no'            =>  $trans_no,
                                    'tenant_id'         =>  $tenant_id,
                                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                                    'company_code'      =>  $this->session->userdata('company_code'),
                                    'department_code'   =>  '01.04',
                                    'credit'            =>  -1 * $credit,
                                    'status'            =>  'ARNTI',
                                    'prepared_by'       =>  $this->session->userdata('id'),
                                    'ft_ref'            =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $credit_RR);
                                $this->app_model->insert('subsidiary_ledger', $credit_RR);
                                $gl_amount -= $credit;
                            }
                        }


                        // to SL

                        $retro_ledger = $this->app_model->get_invoiceRetro($tenant_id);

                        foreach ($retro_ledger as $item)
                        {
                            if ($sl_amount > 0)
                            {
                                $credit;
                                $balance;

                                if ($sl_amount >= $item['balance'])
                                {
                                    $credit  = $item['balance'];
                                    $balance = 0;
                                }
                                else
                                {
                                    $credit  = $sl_amount;
                                    $balance = abs($item['balance'] - $sl_amount);
                                }

                                $sl_amount -= $item['balance'];

                                $entryData = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $transaction_date,
                                    'document_type'    =>  'Internal-Payment',
                                    'ref_no'           =>  $item['ref_no'],
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'contract_no'      =>  $contract_no,
                                    'description'      =>  $item['description'],
                                    'debit'            =>  $credit,
                                    'balance'          =>  $balance,
                                    'due_date'         =>  $item['due_date']
                                );

                                $this->app_model->insert('ledger', $entryData);

                                $sl_amount -= $credit;
                            }
                        }
                    }
                }

                if ($preop_doc_no) // If has preop -> UFT
                {
                    $preop_data = $this->app_model->get_preopdata($tenant_id);
                    if ($gl_amount > 0)
                    {
                        foreach ($preop_data as $preop)
                        {
                            if ($gl_amount > 0)
                            {
                                $sl_refNo = $this->app_model->generate_refNo();
                                $gl_refNo = $this->app_model->gl_refNo();

                                $debit;
                                $balance;
                                if ($gl_amount >= $preop['amount'])
                                {
                                   $debit   = $preop['amount'];
                                   $balance = 0;
                                }
                                else
                                {
                                    $balance = abs($preop['amount'] - $gl_amount);
                                    $debit   = $gl_amount;
                                }

                                $gl_code;
                                $doc_type;
                                if ($preop['description'] == 'Advance Rent')
                                {
                                    $doc_type = 'Advance Payment';
                                    $gl_code  = '10.20.01.01.02.01';
                                }
                                elseif ($preop['description'] == 'Security Deposit' || $preop['description'] == 'Security Deposit - Kiosk and Cart')
                                {
                                    $doc_type = 'UFT-Payment';
                                    $gl_code  = '10.20.01.01.03.12';
                                }
                                elseif ($preop['description'] == 'Construction Bond')
                                {
                                    $doc_type = 'UFT-Payment';
                                    $gl_code  = '10.20.01.01.03.10';
                                }

                                $closingRefNo = $this->app_model->generate_ClosingRefNo();

                                $entry_ARNTI = array(
                                    'posting_date'     =>  $posting_date,
                                    'transaction_date' =>  $this->_currentDate,
                                    'document_type'    =>  'Payment',
                                    'ref_no'           =>  $gl_refNo,
                                    'doc_no'           =>  $trans_no,
                                    'tenant_id'        =>  $tenant_id,
                                    'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                                    'company_code'     =>  $this->session->userdata('company_code'),
                                    'department_code'  =>  '01.04',
                                    'debit'            =>  $debit,
                                    'status'           =>  $store_name,
                                    'prepared_by'      =>  $this->session->userdata('id'),
                                    'ft_ref'           =>  $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $entry_ARNTI);
                                $this->app_model->insert('subsidiary_ledger', $entry_ARNTI);


                                $preop_entry = array(
                                    'posting_date'      =>    $posting_date,
                                    'transaction_date'  =>  $this->_currentDate,
                                    'document_type'     =>    'Payment',
                                    'ref_no'            =>    $gl_refNo,
                                    'doc_no'            =>    $trans_no,
                                    'tenant_id'         =>    $tenant_id,
                                    'gl_accountID'      =>    $this->app_model->gl_accountID($gl_code),
                                    'company_code'      =>    $this->session->userdata('company_code'),
                                    'department_code'   =>    '01.04',
                                    'credit'            =>    -1 * $debit,
                                    'status'            =>  'ARNTI',
                                    'prepared_by'       =>    $this->session->userdata('id'),
                                    'ft_ref'            =>   $closingRefNo
                                );
                                $this->app_model->insert('general_ledger', $preop_entry);
                                $this->app_model->insert('subsidiary_ledger', $preop_entry);

                                $sl_preOp = array(
                                    'posting_date'      =>    $posting_date,
                                    'transaction_date'  =>    $transaction_date,
                                    'document_type'     =>    $doc_type,
                                    'ref_no'            =>    $sl_refNo,
                                    'doc_no'            =>    $trans_no,
                                    'tenant_id'         =>    $tenant_id,
                                    'contract_no'       =>    $contract_no,
                                    'description'       =>    $preop['description'] . "-" . $trade_name,
                                    'charges_type'      =>    $preop['description'],
                                    'debit'             =>    $debit,
                                    'balance'           =>    $balance
                                );
                                $this->app_model->insert('ledger', $sl_preOp);

                                $sl_amount -= $preop['amount'];
                                $gl_amount -= $preop['amount'];

                                if ($gl_amount >= 0)
                                {
                                    $this->app_model->delete('tmp_preoperationcharges', 'id', $preop['id']);
                                }
                                else
                                {
                                    $data = array('amount' => ABS($gl_amount));
                                    $this->app_model->update($data, $preop['id'], 'tmp_preoperationcharges');
                                }
                            }
                        }
                    }
                }


                if ($amount_paid > $total_amount)
                {
                    $advance_amount = $amount_paid - $total_amount;

                    if ($advance_amount >= 1)
                    {
                       $dataLedger = array(
                            'posting_date'       =>  $posting_date,
                            'transaction_date'   =>  $posting_date,
                            'document_type'      =>  'Advance Payment',
                            'doc_no'             =>  $trans_no,
                            'ref_no'             =>  $this->app_model->generate_refNo(),
                            'tenant_id'          =>  $tenant_id,
                            'contract_no'        =>  $contract_no,
                            'description'        =>  'Advance Payment-' . $trade_name,
                            'debit'              =>  $advance_amount,
                            'credit'             =>  0,
                            'balance'            =>  $advance_amount
                        );

                        $this->app_model->insert('ledger', $dataLedger);


                        $closingRefNo = $this->app_model->generate_ClosingRefNo();
                        $advance_refNo = $this->app_model->gl_refNo();

                        $advance_ANTI = array(
                            'posting_date'      =>  $posting_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_refNo,
                            'doc_no'            =>  $trans_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'tag'               =>  'Advance',
                            'debit'             =>  $advance_amount,
                            'status'            =>  $store_name,
                            'prepared_by'       =>  $this->session->userdata('id'),
                            'ft_ref'           =>  $closingRefNo
                        );

                        $this->app_model->insert('general_ledger', $advance_ANTI);
                        $this->app_model->insert('subsidiary_ledger', $advance_ANTI);

                        $advance_RRC = array(
                            'posting_date'      =>  $posting_date,
                            'transaction_date'  =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_refNo,
                            'doc_no'            =>  $trans_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'tag'               =>  'Advance',
                            'credit'            =>  -1 * $advance_amount,
                            'status'            =>  'ARNTI',
                            'prepared_by'       =>  $this->session->userdata('id'),
                            'ft_ref'           =>  $closingRefNo
                        );
                        $this->app_model->insert('general_ledger', $advance_RRC);
                        $this->app_model->insert('subsidiary_ledger', $advance_RRC);



                    }
                }


                // ========= Check if payment is delayed to apply penalty ========= //

                if (!$this->app_model->is_penaltyExempt($tenant_id) && $billing_period != 'Upon Signing of Notice')
                {

                    if (date('Y-m-d', strtotime($posting_date)) > date('Y-m-d', strtotime($collection_date . "+ 1 day")))
                    {
                        $daysOfMonth         = date('t', strtotime($posting_date));
                        $daydiff             = floor((abs(strtotime($posting_date . "- 1 days") - strtotime($collection_date))/(60*60*24)));
                        $sundays             = $this->app_model->get_sundays($collection_date, $posting_date);
                        $daydiff             = $daydiff - $sundays;
                        $penalty_latepayment = ($amount_paid * .02 * $daydiff) / $daysOfMonth;

                        $penaltyEntry = array(
                            'tenant_id'     =>  $tenant_id,
                            'posting_date'  =>  $posting_date,
                            'contract_no'   =>  $contract_no,
                            'doc_no'        =>  $trans_no,
                            'description'   =>  'Late Payment-' . $trade_name,
                            'amount'        =>  round($penalty_latepayment, 2)
                        );
                        $this->app_model->insert('tmp_latepaymentpenalty', $penaltyEntry);
                    }

                }



                $paymentData = array(
                    'posting_date' =>   $posting_date,
                    'soa_no'       =>   $soa_no,
                    'amount_paid'  =>   $amount_paid,
                    'tenant_id'    =>   $tenant_id,
                    'doc_no'       =>   $trans_no,
                );

                $this->app_model->insert('payment', $paymentData);

                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $error = array('action' => 'Internal Clearing', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->app_model->insert('error_log', $error);
                    $response['msg'] = 'DB_error';
                }
                else
                {
                    $response['msg'] = "Success";
                }
            }

            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function get_tenantDetails()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('cfs_logged_in'))
        {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET, POST, PUT');
            $jsonstring = file_get_contents ( 'php://input' );
            $arr        = json_decode($jsonstring,true);
            
            $trade_name   = $arr["trade_name"];
            $tenancy_type = $arr["tenancy_type"];
            $result       = $this->app_model->get_tenantDetails($trade_name, $tenancy_type);
            echo json_encode($result);
        }
    }


    public function admin_tenantDetails()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = str_replace("%20", " ", $this->uri->segment(3));
            $result    = $this->app_model->admin_tenantDetails($tenant_id);
            echo json_encode($result);
        }
    }

    public function get_dataForCreditMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $result    = $this->app_model->get_dataForCreditMemo($tenant_id);
            echo json_encode($result);
        }
    }

    public function get_invOtherforCreditMemo()
    {
        $tenant_id = $this->uri->segment(3);
        $result    = $this->app_model->get_invOtherforCreditMemo($tenant_id);
        echo json_encode($result);
    }


    public function credit_memo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['transaction_no'] = $this->app_model->get_CDTransactionNo();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/credit_memo');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function debit_memo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['transaction_no'] = $this->app_model->get_DMTransactionNo();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/debit_memo');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function credit_memoPage()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['tenant_id'] = $this->uri->segment(3);
            $data['doc_no']    = $this->uri->segment(4);
            $data['flashdata'] = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/credit_memoPage');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function save_creditMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $username        = $this->sanitize(str_replace("%20", " ", $this->uri->segment(3)));
            $password        = $this->sanitize(str_replace("%20", " ", $this->uri->segment(4)));
            $date            = new DateTime();
            $timeStamp       = $date->getTimestamp();
            $doc_no          = $this->sanitize($this->input->post('doc_no'));
            $tenancy_type    = $this->sanitize($this->input->post('tenancy_type'));
            $contract_no     = $this->sanitize($this->input->post('contract_no'));
            $trade_name      = $this->sanitize($this->input->post('trade_name'));
            $posting_date    = $this->input->post('posting_date');
            $due_date        = $this->sanitize($this->input->post('due_date'));
            $total           = $this->sanitize($this->input->post('total'));
            $total           = str_replace(",", "", $total);
            $charges_type    = $this->input->post('charges_type');
            $charges_code    = $this->input->post('charges_code');
            $description     = $this->input->post('description');
            $uom             = $this->input->post('uom');
            $unit_price      = $this->input->post('unit_price');
            $total_unit      = $this->input->post('total_unit');
            $actual_amount   = $this->input->post('actual_amount');
            $rental_type     = $this->input->post('rental_type');
            $tenant_id       = $this->sanitize($this->input->post('tenant_id'));
            $reason          = $this->sanitize($this->input->post('reason'));
            $reason          = $this->sanitize($this->input->post('reason'));
            $date_modified   = date('Y-m-d');
            $original_amount = $this->sanitize($this->input->post('orig_amount'));
            $original_amount = str_replace(",", "", $original_amount);
            $positive_amount = $this->sanitize($this->input->post('positive_amount'));
            $positive_amount = str_replace(",", "", $positive_amount);
            $negative_amount = $this->sanitize($this->input->post('negative_amount'));
            $negative_amount = str_replace(",", "", $negative_amount);


            $response = array();
            $data = array();

            $store_name   = $this->app_model->my_store();
            $store_code   = $this->app_model->tenant_storeCode($tenant_id);
            $tenancy_type = $this->app_model->tenant_tenancyType($tenant_id);

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor")
            {
                if ($this->app_model->managers_key($username, $password, $store_name))
                {

                    if (!$actual_amount)
                    {
                        $response['msg'] = 'No Charges Added';
                    }
                    else
                    {
                        //======== Delete old data before inserting new Invoice charges =======//
                        $this->app_model->delete_oldInvoice($tenant_id, $doc_no);

                        //==================== Inserting new Invoice charges =================//

                        for ($i=0; $i < count($actual_amount); $i++)
                        {
                            if ($charges_type[$i] == 'Basic/Monthly Rental')
                            {
                                $data = array(
                                    'tenant_id'     =>  $tenant_id,
                                    'trade_name'    =>  $trade_name,
                                    'doc_no'        =>  $doc_no,
                                    'posting_date'  =>  $posting_date,
                                    'due_date'      =>  $due_date,
                                    'store_code'    =>  $store_code,
                                    'flag'          =>  $tenancy_type,
                                    'charges_type'  =>  $charges_type[$i],
                                    'description'   =>  $description[$i],
                                    'uom'           =>  $uom[$i],
                                    'unit_price'    =>  str_replace(",", "", $unit_price[$i]),
                                    'total_unit'    =>  str_replace(",", "", $total_unit[$i]),
                                    'expected_amt'  =>  str_replace(",", "", $actual_amount[$i]),
                                    'actual_amt'    =>  str_replace(",", "", $total),
                                    'balance'       =>  str_replace(",", "", $total),
                                    'tag'           =>  'Posted'
                                );

                            }
                            elseif ($charges_type[$i] == 'Other')
                            {
                                $data = array(
                                    'tenant_id'        =>   $tenant_id,
                                    'trade_name'       =>   $trade_name,
                                    'doc_no'           =>   $doc_no,
                                    'posting_date'     =>   $posting_date,
                                    'due_date'         =>   $due_date,
                                    'store_code'       =>   $store_code,
                                    'flag'             =>   $tenancy_type,
                                    'charges_type'     =>   $charges_type[$i],
                                    'charges_code'     =>   $charges_code[$i],
                                    'description'      =>   $description[$i],
                                    'uom'              =>   $uom[$i],
                                    'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                                    'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                                    'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                                    'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                                    'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                                    'tag'              =>  'Posted'
                                );
                            }
                            else
                            {
                                $data = array(
                                    'tenant_id'        =>   $tenant_id,
                                    'trade_name'       =>   $trade_name,
                                    'doc_no'           =>   $doc_no,
                                    'posting_date'     =>   $posting_date,
                                    'due_date'         =>   $due_date,
                                    'store_code'       =>   $store_code,
                                    'flag'             =>   $tenancy_type,
                                    'charges_type'     =>   $charges_type[$i],
                                    'charges_code'     =>   $charges_code[$i],
                                    'description'      =>   $description[$i],
                                    'uom'              =>   $uom[$i],
                                    'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                                    'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                                    'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                                    'tag'              =>  'Posted'
                                );
                            }

                            $this->app_model->insert('invoicing', $data);
                        }


                        // ==================== Save to credit memo history ===================== //

                        $cdmemo = array(
                            'tenant_id'         =>  $tenant_id,
                            'reason'            =>  $reason,
                            'date_modified'     =>  $date_modified,
                            'original_amount'   =>  $orginal_amount,
                            'positive_amount'   =>  $positive_amount,
                            'negative_amount'   =>  $negative_amount,
                            'total_amount'      =>  $total,
                            'flag'              =>  $tenancy_type,
                            'modified_by'       =>  $this->session->userdata('id'),
                            'group'             =>  $this->session->userdata('user_group')
                        );

                        $this->app_model->insert('credit_memo', $cdmemo);
                        $response['msg'] = "Success";
                    }
                }
                else
                {
                   $response['msg'] = "Invalid Key";
                }

            }
            else
            {
                if (!$actual_amount)
                {
                    $response['msg'] = 'No Charges Added';
                }
                else
                {
                    //======== Delete old data before inserting new Invoice charges =======//
                    $this->app_model->delete_oldInvoice($tenant_id, $doc_no);

                    //==================== Inserting new Invoice charges =================//

                    for ($i=0; $i < count($actual_amount); $i++)
                    {
                        if ($charges_type[$i] == 'Basic/Monthly Rental')
                        {
                            $data = array(
                                'tenant_id'     =>  $tenant_id,
                                'trade_name'    =>  $trade_name,
                                'doc_no'        =>  $doc_no,
                                'posting_date'  =>  $posting_date,
                                'due_date'      =>  $due_date,
                                'store_code'    =>  $store_code,
                                'flag'          =>  $tenancy_type,
                                'charges_type'  =>  $charges_type[$i],
                                'description'   =>  $description[$i],
                                'uom'           =>  $uom[$i],
                                'unit_price'    =>  str_replace(",", "", $unit_price[$i]),
                                'total_unit'    =>  str_replace(",", "", $total_unit[$i]),
                                'expected_amt'  =>  str_replace(",", "", $actual_amount[$i]),
                                'actual_amt'    =>  str_replace(",", "", $total),
                                'balance'       =>  str_replace(",", "", $total),
                                'tag'           =>  'Posted'
                            );

                        }
                        elseif ($charges_type[$i] == 'Other')
                        {
                            $data = array(
                                'tenant_id'        =>   $tenant_id,
                                'trade_name'       =>   $trade_name,
                                'doc_no'           =>   $doc_no,
                                'posting_date'     =>   $posting_date,
                                'due_date'         =>   $due_date,
                                'store_code'       =>   $store_code,
                                'flag'             =>   $tenancy_type,
                                'charges_type'     =>   $charges_type[$i],
                                'charges_code'     =>   $charges_code[$i],
                                'description'      =>   $description[$i],
                                'uom'              =>   $uom[$i],
                                'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                                'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                                'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                                'actual_amt'       =>   str_replace(",", "", $actual_amount[$i]),
                                'balance'          =>   str_replace(",", "", $actual_amount[$i]),
                                'tag'              =>  'Posted'
                            );
                        }
                        else
                        {
                            $data = array(
                                'tenant_id'        =>   $tenant_id,
                                'trade_name'       =>   $trade_name,
                                'doc_no'           =>   $doc_no,
                                'posting_date'     =>   $posting_date,
                                'due_date'         =>   $due_date,
                                'store_code'       =>   $store_code,
                                'flag'             =>   $tenancy_type,
                                'charges_type'     =>   $charges_type[$i],
                                'charges_code'     =>   $charges_code[$i],
                                'description'      =>   $description[$i],
                                'uom'              =>   $uom[$i],
                                'unit_price'       =>   str_replace(",", "", $unit_price[$i]),
                                'total_unit'       =>   str_replace(",", "", $total_unit[$i]),
                                'expected_amt'     =>   str_replace(",", "", $actual_amount[$i]),
                                'tag'              =>  'Posted'
                            );
                        }

                        $this->app_model->insert('invoicing', $data);
                    }


                    // ==================== Save to credit memo history ===================== //

                    $cdmemo = array(
                        'tenant_id'         =>  $tenant_id,
                        'reason'            =>  $reason,
                        'date_modified'     =>  $date_modified,
                        'original_amount'   =>  $orginal_amount,
                        'positive_amount'   =>  $positive_amount,
                        'negative_amount'   =>  $negative_amount,
                        'total_amount'      =>  $total,
                        'flag'              =>  $tenancy_type,
                        'modified_by'       =>  $this->session->userdata('id'),
                        'group'             =>  $this->session->userdata('user_group')
                    );

                    $this->app_model->insert('credit_memo', $cdmemo);
                    $response['msg'] = "Success";
                }
            }


            echo json_encode($response);
        }
    }

    function sort_ascending(array $arr)
    {
        $arr_size = sizeof($arr);
        for ($i = 1; $i < $arr_size; $i++)
        {
            for ($j = $arr_size - 1; $j >= $i; $j--)
            {
                if($arr[$j-1] > $arr[$j])
                {
                    $tmp = $arr[$j - 1];
                    $arr[$j - 1] = $arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }

        return $arr;
    }

    public function general_ledger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/general_ledger');
            $this->load->view('leasing/footer');

        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function get_generalLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_generalLedger();

            $result_withbalance = array();
            $prev_docno = '';
            $running_balance = 0;

            foreach($result as $value)
            {
        
                if($value['doc_no'] != $prev_docno)
                {
                    $result_withbalance[] =
                    [
                        'id'            => $value['id'],
                        'trade_name'    => $value['trade_name'],
                        'document_type' => $value['document_type'],
                        'doc_no'        => $value['doc_no'],
                        'gl_code'       => $value['gl_code'],
                        'gl_account'    => $value['gl_account'],
                        'posting_date'  => $value['posting_date'],
                        'debit'         => $value['debit'],
                        'credit'        => $value['credit'],
                        'balance'       => $value['debit'],
                    ];

                    $running_balance = $value['debit'];
                }
                else
                {
                    if($value['credit'] == '0.00' || $value['credit'] == null)
                    {
                        $running_balance = $running_balance + $value['debit'];
                        $result_withbalance[] = 
                        [
                            'id'            => $value['id'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'doc_no'        => $value['doc_no'],
                            'gl_code'       => $value['gl_code'],
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $running_balance,
                        ];
                    }
                    else
                    {
                        $running_balance = $running_balance - $value['credit'];
                        $result_withbalance[] = 
                        [
                            'id'            => $value['id'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'doc_no'        => $value['doc_no'],
                            'gl_code'       => $value['gl_code'],
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $running_balance,
                        ];
                    }
                }
                $prev_docno = $value['doc_no'];
            }

            echo json_encode($result_withbalance);
        }
    }


    public function upload_image($targetPath, $image_name, $tenant_id)
    {
        $date      = new DateTime();
        $timeStamp = $date->getTimestamp();
        $filename;
        
        $tmpFilePath = $_FILES[$image_name]['tmp_name'];
            //Make sure we have a filepath
        if ($tmpFilePath != "")
        {
            //Setup our new file path
            $filename    = $tenant_id . $timeStamp . $_FILES[$image_name]['name'];
            $newFilePath = $targetPath . $filename;
            //Upload the file into the temp dir
            move_uploaded_file($tmpFilePath, $newFilePath);
        }

        return $filename;
    }


    public function get_monthly_charges()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $result = $this->app_model->get_monthly_charges();
            echo json_encode($result);
        }
    }

    public function prev_electricity_reading()
    {
        $tenant_id = $this->sanitize($this->uri->segment(3));
        $result    = $this->app_model->prev_electricity_reading($tenant_id);
        echo json_encode($result);
    }
    
    public function prev_water_reading()
    {
        $tenant_id = $this->sanitize($this->uri->segment(3));
        $result    = $this->app_model->prev_water_reading($tenant_id);
        echo json_encode($result);
    }




    public function populate_ltlocationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace("%20", " ", $this->uri->segment(3));
            $floor_name = str_replace("%20", " ", $this->uri->segment(4));
            $result     = $this->app_model->populate_ltlocationCode($store_name, $floor_name);
            echo json_encode($result);
        }
    }


    public function populate_sltlocationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace("%20", " ", $this->uri->segment(3));
            $floor_name = str_replace("%20", " ", $this->uri->segment(4));
            $result     = $this->app_model->populate_sltlocationCode($store_name, $floor_name);
            echo json_encode($result);
        }
    }

    public function populate_stlocationCode()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $store_name = str_replace("%20", " ", $this->uri->segment(3));
            $floor_name = str_replace("%20", " ", $this->uri->segment(4));
            $result     = $this->app_model->populate_stlocationCode($store_name, $floor_name);
            echo json_encode($result);
        }
    }

    public function get_locationCodeInfo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $location_code = str_replace("%20", "", $this->uri->segment(3));
            $result        = $this->app_model->get_locationCodeInfo($location_code);
            echo json_encode($result);
        }
    }


    public function basic_creditMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $ledger_id      = $this->input->post('ledger_id');
            $amount         = str_replace(",", "", $this->input->post('credit_amount'));
            $this->db->trans_start(); // Transaction function starts here!!!
            $transaction_no = $this->app_model->get_CDTransactionNo();
            $ledgerEntry    = $this->app_model->get_ledgerData($ledger_id);
            $vat            = $this->app_model->get_vat();
            $wht            = $this->app_model->get_wht();
            $wht_amount     = ($amount / 1.07) * .05;
            $vat_amount     = ($amount / 1.07) * .12;

            foreach ($ledgerEntry as $item)
            {
                $balance = $item['remaining_balance'] - $amount;
                $dataLedger = array(
                    'posting_date'      =>  $item['posting_date'],
                    'document_type'     =>  'Credit Memo',
                    'ref_no'            =>  $item['ref_no'],
                    'doc_no'            =>  '',
                    'tenant_id'         =>  $item['tenant_id'],
                    'contract_no'       =>  $item['contract_no'],
                    'description'       =>  $item['description'],
                    'credit'             =>  0,
                    'debit'             =>  $amount,
                    'balance'           =>  $balance,
                    'due_date'          =>  $item['due_date'],
                    'charges_type'      =>  'Basic'
                );

                // Invoice adjustments
                $this->app_model->basic_invoiceCreditMemo($item['tenant_id'], $item['due_date'], $item['doc_no'], $balance, $amount);

                // GL adjustments
                $rr_creditMemo = $this->app_model->rr_creditMemo($item['tenant_id'], $item['doc_no']);
                $vatable = $this->app_model->is_vatable($item['tenant_id']);

                foreach ($rr_creditMemo as $data)
                {
                    if ($data['gl_account'] == 'Rent Receivables')
                    {
                        $rent_receivable = array(
                            'posting_date'     =>  $data['posting_date'],
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Credit Memo',
                            'ref_no'           =>  $data['ref_no'],
                            'doc_no'           =>  '',
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'credit'           =>  $amount,
                            'tag'              =>  'Basic Rent',
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->insert('general_ledger', $rent_receivable);
                        $this->app_model->insert('subsidiary_ledger', $rent_receivable);
                    }
                    elseif ($data['gl_account'] == 'VAT Output' && $vatable == 'Added')
                    {

                        $vat_entry = array(
                            'posting_date'     =>  $data['posting_date'],
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Credit Memo',
                            'ref_no'           =>  $data['ref_no'],
                            'doc_no'           =>  '',
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'debit'            =>  $vat_amount,
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->insert('general_ledger', $vat_entry);
                        $this->app_model->insert('subsidiary_ledger', $vat_entry);
                    }
                    elseif ($data['gl_account'] == 'Creditable WHT Receivable')
                    {


                        $wht_entry = array(
                            'posting_date'     =>  $data['posting_date'],
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Credit Memo',
                            'ref_no'           =>  $data['ref_no'],
                            'doc_no'           =>  '',
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'credit'           =>  -1 * $wht_amount,
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->insert('general_ledger', $wht_entry);
                        $this->app_model->insert('subsidiary_ledger', $wht_entry);
                    }
                    elseif ($data['gl_account'] == 'Rent Income')
                    {
                        if ($vatable == 'Added')
                        {
                            $ri_amount = ($amount + $wht_amount) - $vat_amount;
                        }
                        else
                        {
                            $ri_amount = ($amount + $wht_amount);
                        }

                        $ri_entry = array(
                            'posting_date'     =>  $data['posting_date'],
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Credit Memo',
                            'ref_no'           =>  $data['ref_no'],
                            'doc_no'           =>  '',
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('20.60.01'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'debit'            =>  $ri_amount,
                            'prepared_by'      =>  $this->_user_id
                        );
                        $this->app_model->insert('general_ledger', $ri_entry);
                        $this->app_model->insert('subsidiary_ledger', $ri_entry);
                    }
                }
                $this->app_model->insert('ledger', $dataLedger);
            }



            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $this->session->set_flashdata('message', 'DB Error');
                redirect('Leasing_transaction/credit_memo');
            }
            else
            {
                $this->session->set_flashdata('message', 'Saved');
                redirect('Leasing_transaction/credit_memo');
            }


        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function basic_debitMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $ledger_id      = $this->input->post('ledger_id');
            $amount         = str_replace(",", "", $this->sanitize($this->input->post('debit_amount')));
            $transaction_no = $this->app_model->get_DMTransactionNo();
            $RR_amount;

            $this->db->trans_start(); // Transaction function starts here!!!
            $gl_refno       = $this->app_model->gl_refNo();
            $sl_refno       = $this->app_model->generate_refNo();
            $ledgerEntry    = $this->app_model->get_ledgerData($ledger_id);
            $vat            = $this->app_model->get_vat();
            $wht            = $this->app_model->get_wht();
            $wht_amount     = $amount * .05;
            $vat_amount     = $amount * .12;

            foreach ($ledgerEntry as $item)
            {

                // GL adjustments
                $rr_creditMemo = $this->app_model->rr_creditMemo($item['tenant_id'], $item['doc_no']);
                $vatable       = $this->app_model->is_vatable($item['tenant_id']);

                foreach ($rr_creditMemo as $data)
                {
                    if ($data['gl_account'] == 'Rent Receivables')
                    {
                        if ($vatable == 'Added')
                        {
                            $RR_amount = ($amount - $wht_amount) + $vat_amount;
                        }
                        else
                        {
                            $RR_amount = ($amount - $wht_amount);
                        }
                        $rent_receivable = array(
                            'posting_date'     =>  $this->_currentDate,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Invoice',
                            'ref_no'           =>  $gl_refno,
                            'due_date'         =>  $data['due_date'],
                            'doc_no'           =>  $transaction_no,
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.03.16'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'debit'            =>  $RR_amount,
                            'tag'              =>  'Basic Rent',
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->insert('general_ledger', $rent_receivable);
                        $this->app_model->insert('subsidiary_ledger', $rent_receivable);

                        $invoiceData = array(
                            'tenant_id'        =>   $data['tenant_id'],
                            'doc_no'           =>   $transaction_no,
                            'posting_date'     =>   $this->_currentDate,
                            'transaction_date' =>   $this->_currentDate,
                            'due_date'         =>   $data['due_date'],
                            'store_code'       =>   $this->session->userdata('store_code'),
                            'charges_type'     =>   'Basic/Monthly Rental',
                            'description'      =>   'Basic Debit Memo',
                            'expected_amt'     =>   $amount,
                            'balance'          =>   $RR_amount,
                            'actual_amt'       =>   $RR_amount,
                            'total_gross'      =>   $RR_amount,
                            'tag'              =>   'Posted'
                        );

                         $this->app_model->insert('invoicing', $invoiceData);

                        $this->app_model->basic_invoiceDebitMemo($data['tenant_id'], $data['due_date'], $data['doc_no']);


                         // For Montly Receivable Report
                        $RR_reportData = array(
                            'tenant_id'     =>  $data['tenant_id'],
                            'doc_no'        =>  $transaction_no,
                            'posting_date'  =>  $this->_currentDate,
                            'description'   =>  'Net Rental',
                            'amount'        =>  $RR_amount
                        );
                        $this->app_model->insert('monthly_receivable_report', $RR_reportData);

                    }
                    elseif ($data['gl_account'] == 'VAT Output' && $vatable == 'Added')
                    {

                        $vat_entry = array(
                            'posting_date'     =>  $this->_currentDate,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Invoice',
                            'ref_no'           =>  $gl_refno,
                            'due_date'         =>  $data['due_date'],
                            'doc_no'           =>  $transaction_no,
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'credit'           =>  -1 * $vat_amount,
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->vat_invoiceDebitMemo($item['tenant_id'], $item['due_date'], $item['doc_no'], $vat_amount);
                        $this->app_model->insert('general_ledger', $vat_entry);
                        $this->app_model->insert('subsidiary_ledger', $vat_entry);

                        // For Montly Receivable Report
                        $VAT_reportData = array(
                            'tenant_id'     =>  $data['tenant_id'],
                            'doc_no'        =>  $transaction_no,
                            'posting_date'  =>  $this->_currentDate,
                            'description'   =>  'VAT',
                            'amount'        =>  $vat_amount
                        );
                        $this->app_model->insert('monthly_receivable_report', $VAT_reportData);

                    }
                    elseif ($data['gl_account'] == 'Creditable WHT Receivable')
                    {

                        $wht_entry = array(
                            'posting_date'     =>  $this->_currentDate,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Invoice',
                            'ref_no'           =>  $gl_refno,
                            'doc_no'           =>  $transaction_no,
                            'due_date'         =>  $data['due_date'],
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'debit'            =>  $wht_amount,
                            'prepared_by'      =>  $this->_user_id
                        );

                        $this->app_model->wht_invoiceDebitMemo($item['tenant_id'], $item['due_date'], $item['doc_no'], $wht_amount);
                        $this->app_model->insert('general_ledger', $wht_entry);
                        $this->app_model->insert('subsidiary_ledger', $wht_entry);

                        // For Montly Receivable Report
                        $WHT_reportData = array(
                            'tenant_id'     =>  $data['tenant_id'],
                            'doc_no'        =>  $transaction_no,
                            'posting_date'  =>  $this->_currentDate,
                            'description'   =>  'WHT',
                            'amount'        =>  $wht_amount
                        );
                        $this->app_model->insert('monthly_receivable_report', $WHT_reportData);

                    }
                    elseif ($data['gl_account'] == 'Rent Income')
                    {

                        $ri_entry = array(
                            'posting_date'     =>  $this->_currentDate,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'    =>  'Invoice',
                            'ref_no'           =>  $gl_refno,
                            'due_date'         =>  $data['due_date'],
                            'doc_no'           =>  $transaction_no,
                            'tenant_id'        =>  $data['tenant_id'],
                            'gl_accountID'     =>  $this->app_model->gl_accountID('20.60.01'),
                            'company_code'     =>  $data['company_code'],
                            'department_code'  =>  $data['department_code'],
                            'credit'           =>  -1 * $amount,
                            'prepared_by'      =>  $this->_user_id
                        );
                        $this->app_model->insert('general_ledger', $ri_entry);
                        $this->app_model->insert('subsidiary_ledger', $ri_entry);


                        // For Montly Receivable Report
                        $RI_reportData = array(
                            'tenant_id'     =>  $data['tenant_id'],
                            'doc_no'        =>  $transaction_no,
                            'posting_date'  =>  $this->_currentDate,
                            'description'   =>  'Basic Rent',
                            'amount'        =>  $amount
                        );
                        $this->app_model->insert('monthly_receivable_report', $RI_reportData);
                    }
                }


                $balance = $item['remaining_balance'] + $RR_amount;
                $dataLedger = array(
                    'posting_date'      =>  $this->_currentDate,
                    'transaction_date'  =>  $this->_currentDate,
                    'document_type'     =>  'Debit Memo',
                    'ref_no'            =>  $sl_refno,
                    'doc_no'            =>  $transaction_no,
                    'due_date'          =>  $item['due_date'],
                    'tenant_id'         =>  $item['tenant_id'],
                    'contract_no'       =>  $item['contract_no'],
                    'description'       =>  $item['description'],
                    'debit'             =>  0,
                    'credit'            =>  $RR_amount,
                    'balance'           =>  $balance,
                    'due_date'          =>  $item['due_date'],
                    'charges_type'      =>  'Basic'
                );


            }

            $this->app_model->insert('ledger', $dataLedger);

            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $this->session->set_flashdata('message', 'DB Error');
                redirect('Leasing_transaction/debit_memo');
            }
            else
            {
                $this->session->set_flashdata('message', 'Saved');
                redirect('Leasing_transaction/debit_memo');
            }
        }
    }


    public function other_creditMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $amount         = str_replace(",", "", $this->input->post('credit_amount'));
            $ledger_id      = $this->input->post('ledger_id');
            $transaction_no = $this->app_model->get_CDTransactionNo();
            $ledgerEntry    = $this->app_model->get_ledgerData($ledger_id);
            foreach ($ledgerEntry as $item)
            {
                $dataLedger = array(
                    'posting_date'      =>  $item['posting_date'],
                    'document_type'     =>  'Credit Memo',
                    'ref_no'            =>  $item['ref_no'],
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'contract_no'       =>  $item['contract_no'],
                    'description'       =>  $item['description'],
                    'credit'            =>  0,
                    'debit'             =>  $amount,
                    'balance'           =>  $item['remaining_balance'] - $amount,
                    'due_date'          =>  $item['due_date'],
                    'charges_type'      =>  'Other'
                );

                $description = explode("-", $item['description']);
                $this->app_model->other_invoiceCreditMemo($description[2], $item['due_date'], $item['doc_no'], $amount);


                // GL adjustments
                $gl_code  = $this->gl_code($description[2]);
                $gl_refNo = $this->app_model->get_docNo_refNo($item['tenant_id'], $item['doc_no']);

                $AR_entry = array(
                    'posting_date'      =>  $item['posting_date'],
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Credit Memo',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * str_replace(",", "", $amount),
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $AR_entry);
                $this->app_model->insert('subsidiary_ledger', $AR_entry);

                $other_entry = array(
                    'posting_date'      =>  $item['posting_date'],
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Credit Memo',
                    'ref_no'            =>  $gl_refNo,
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  str_replace(",", "", $amount),
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $other_entry);
                $this->app_model->insert('subsidiary_ledger', $other_entry);
            }

            $this->app_model->insert('ledger', $dataLedger);
            $this->session->set_flashdata('message', 'Saved');
            redirect('Leasing_transaction/credit_memo');

        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function other_debitMemo()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {

            $amount         = str_replace(",", "", $this->input->post('debit_amount'));

            $ledger_id      = $this->input->post('ledger_id');
            $gl_refno       = $this->app_model->gl_refNo();
            $sl_refno       = $this->app_model->generate_refNo();
            $transaction_no = $this->app_model->get_DMTransactionNo();

            $this->db->trans_start(); // Transaction function starts here!!!
            $ledgerEntry    = $this->app_model->get_ledgerData($ledger_id);
            foreach ($ledgerEntry as $item)
            {
                $dataLedger = array(
                    'posting_date'      =>  $this->_currentDate,
                    'document_type'     =>  'Debit Memo',
                    'ref_no'            =>  $sl_refno,
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'contract_no'       =>  $item['contract_no'],
                    'description'       =>  $item['description'],
                    'debit'             =>  0,
                    'credit'            =>  $amount,
                    'balance'           =>  $item['remaining_balance'] + $amount,
                    'due_date'          =>  $item['due_date'],
                    'charges_type'      =>  'Other'
                );

                $AR_entry = array(
                    'posting_date'      =>  $this->_currentDate,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.03'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $amount,
                    'tag'               =>  'Other',
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $AR_entry);
                $this->app_model->insert('subsidiary_ledger', $AR_entry);

                $description = explode("-", $item['description']);
                $gl_code     = $this->gl_code($description[2]);

                $other_entry = array(
                    'posting_date'      =>  $this->_currentDate,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $transaction_no,
                    'tenant_id'         =>  $item['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID($gl_code),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * str_replace(",", "", $amount),
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $other_entry);
                $this->app_model->insert('subsidiary_ledger', $other_entry);




                $invoiceData = array(
                    'tenant_id'        =>   $item['tenant_id'],
                    'doc_no'           =>   $transaction_no,
                    'posting_date'     =>   $this->_currentDate,
                    'transaction_date' =>   $this->_currentDate,
                    'due_date'         =>   $item['due_date'],
                    'store_code'       =>   $this->session->userdata('store_code'),
                    'charges_type'     =>   'Other',
                    'description'      =>   $description[2],
                    'expected_amt'     =>   $amount,
                    'actual_amt'       =>   $amount,
                    'balance'          =>   $amount,
                    'tag'              =>   'Posted',
                    'status'           =>   'Debited'
                );

                $this->app_model->insert('invoicing', $invoiceData);

                // For Montly Receivable Report
                $reportData = array(
                    'tenant_id'     =>  $item['tenant_id'],
                    'doc_no'        =>  $transaction_no,
                    'posting_date'  =>  $this->_currentDate,
                    'description'   =>  $description[2],
                    'amount'        =>  $amount
                );
                $this->app_model->insert('monthly_receivable_report', $reportData);


            }

            $this->app_model->insert('ledger', $dataLedger);

            $this->db->trans_complete(); // End of transaction function

            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $this->session->set_flashdata('message', 'DB Error');
                redirect('Leasing_transaction/debit_memo');
            }
            else
            {
                $this->session->set_flashdata('message', 'Saved');
                redirect('Leasing_transaction/debit_memo');
            }


        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function tenant_ledger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);

            if ($this->session->userdata('user_type') == 'Administrator')
            {
                $this->load->view('leasing/admin_TL');
            }
            else
            {
                $this->load->view('leasing/tenant_ledger');
            }
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }

    public function get_tenantLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = str_replace("%20", "", $this->uri->segment(3));
            $result    = $this->app_model->get_tenantLedger($tenant_id);
            echo json_encode($result);
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function get_subsidiaryLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = str_replace("%20", "", $this->uri->segment(3));
            $result    = $this->app_model->get_subsidiaryLedger($tenant_id);
            
            $result_withbalance = array();
            $prev_refno = '';
            $beggining_balance = 0;
            $running_balance = 0;
            
            foreach ($result as  $value) 
            {
                
                if($value['ref_no'] != $prev_refno)
                {
                    $result_withbalance[] =
                    [
                        'entry_no'      => $value['entry_no'],
                        'trade_name'    => $value['trade_name'],
                        'document_type' => $value['document_type'],
                        'due_date'      => $value['due_date'],
                        'doc_no'        => $value['doc_no'],
                        'ref_no'        => $value['ref_no'],
                        'gl_code'       => $value['gl_code'],
                        'bank_code'     => $value['bank_code'],
                        'gl_account'    => $value['gl_account'],
                        'posting_date'  => $value['posting_date'],
                        'debit'         => $value['debit'],
                        'credit'        => $value['credit'],
                        'balance'       => $value['debit'],          
                    ];
 
                    $running_balance = $value['debit'];
                }
                else
                {
                    if($value['debit'] == "0.00" || $value['debit'] == null)
                    {
                        $new_running_balance = $running_balance - $value['credit'];

                        $result_withbalance[] =
                        [
                            'entry_no'      => $value['entry_no'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'gl_code'       => $value['gl_code'],
                            'bank_code'     => $value['bank_code'],
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $new_running_balance,          
                        ];

                        $running_balance = $new_running_balance;

                    }
                    else
                    {

                        $new_running_balance = $running_balance + $value['debit'];

                        $result_withbalance[] =
                        [
                            'entry_no'      => $value['entry_no'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'gl_code'       => $value['gl_code'],
                            'bank_code'     => $value['bank_code'],
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $new_running_balance,          
                        ];

                        $running_balance = $new_running_balance;
                    }
                }
                $prev_refno = $value['ref_no'];

            }

            echo json_encode($result_withbalance);
        }
    }

    public function export_tenantLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->uri->segment(3);
            $query     = $this->app_model->export_tenantLedger($tenant_id);
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename  = $tenant_id . "_sl" . $timeStamp;
            
            $result_withbalance = array('data' => []);
            $prev_refno = '';
            $beggining_balance = 0;
            $running_balance = 0;
            
            foreach ($query as  $value) 
            {
               
                
                if($value['ref_no'] != $prev_refno)
                {
                    $result_withbalance[] =
                    [
                        'entry_no'      => $value['entry_no'],
                        'trade_name'    => $value['trade_name'],
                        'document_type' => $value['document_type'],
                        'due_date'      => $value['due_date'],
                        'doc_no'        => $value['doc_no'],
                        'ref_no'        => $value['ref_no'],
                        'gl_code'       => $value['gl_code'],
                      
                        'gl_account'    => $value['gl_account'],
                        'posting_date'  => $value['posting_date'],
                        'debit'         => number_format($value['debit'],2),
                        'credit'        => number_format($value['credit'],2),
                        'balance'       => number_format($value['debit'],2),         
                    ];
 
                    $running_balance = $value['debit'];
                }
                else
                {
                    if($value['debit'] == "0.00" || $value['debit'] == null)
                    {
                        $new_running_balance = $running_balance - $value['credit'];

                        $result_withbalance[] =
                        [
                            'entry_no'      => $value['entry_no'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'gl_code'       => $value['gl_code'],
                         
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => number_format($value['debit'],2),
                            'credit'        => number_format($value['credit'],2),
                            'balance'       => number_format($new_running_balance,2),        
                        ];

                        $running_balance = $new_running_balance;

                    }
                    else
                    {

                        $new_running_balance = $running_balance + $value['debit'];

                        $result_withbalance[] =
                        [
                            'entry_no'      => $value['entry_no'],
                            'trade_name'    => $value['trade_name'],
                            'document_type' => $value['document_type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'gl_code'       => $value['gl_code'],
                         
                            'gl_account'    => $value['gl_account'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => number_format($value['debit'],2),
                            'credit'        => number_format($value['credit'],2),
                            'balance'       => number_format($new_running_balance,2),         
                        ];

                        $running_balance = $new_running_balance;
                    }
                }
                $prev_refno = $value['ref_no'];

            }



            $this->excel->to_exceltenantledger($result_withbalance, $filename);
        }
    }

    public function export_generalLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $query     = $this->app_model->get_generalLedger();
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename  = "gl" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }


    public function export_unclosePDC()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $query     = $this->app_model->get_unclosedPDC();
            $date      = new DateTime();
            $timeStamp = $date->getTimestamp();
            $filename  = "unclosedPDC" . $timeStamp;
            $this->excel->to_excel($query, $filename);
        }
    }

    public function unclosed_PDC()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/unclosed_PDC');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing');
        }
    }


    public function closePDC()
    {
        if ($this->session->userdata('user_type') == 'Accounting Staff' || $this->session->userdata('user_type') == 'Bank Recon')
        {
            $gl_id        = $this->uri->segment(3);
            $receipt_no   = $this->app_model->get_receiptNo($gl_id);
            $PDC_data     = $this->app_model->get_PDCToClose($receipt_no);
            $amount       = str_replace(",", "", $this->input->post('amount'));
            $doc_no       = $this->app_model->closingPDC_docNo();
            $posting_date = $this->sanitize($this->input->post('posting_date'));
            $tenant_id = '';
            $or_number = '';
            foreach ($PDC_data as $value)
            {
                $tenant_id = $value['tenant_id'];
                $or_number = $value['doc_no'];

                $CIB = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $value['ref_no'],
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $value['tenant_id'],
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                    'company_code'      =>  $value['company_code'],
                    'department_code'   =>  $value['department_code'],
                    'debit'             =>  $value['amount'],
                    'bank_name'         =>  $value['bank_name'],
                    'bank_code'         =>  $value['bank_code'],
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $CIB);
                $this->app_model->insert('subsidiary_ledger', $CIB);

                $PDC = array(
                    'posting_date'      =>  $posting_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $value['ref_no'],
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $value['tenant_id'],
                    'gl_accountID'      =>  $value['gl_accountID'],
                    'company_code'      =>  $value['company_code'],
                    'department_code'   =>  $value['department_code'],
                    'credit'            =>  -1 * $value['amount'],
                    'bank_name'         =>  $value['bank_name'],
                    'bank_code'         =>  $value['bank_code'],
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $PDC);
                $this->app_model->insert('subsidiary_ledger', $PDC);
            }


            $pdc_closingData = array(
                'tenant_id'    => $tenant_id,
                'posting_date' => $posting_date,
                'or_number'    => $or_number,
                'doc_no'       => $doc_no,
                'amount'       => $amount
            );

            $this->app_model->insert('pdc_closing', $pdc_closingData);


            $this->session->set_flashdata('message', 'Saved');

            if ($this->session->userdata('user_type') == 'Bank Recon') {
                redirect('ctrl_recon/unclosedPDC');
            } else {
                redirect('Leasing_transaction/unclosed_PDC');
            }

            
        }
        else
        {
            $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; //===== get the previous URL ====//
            $this->session->set_flashdata('message', 'Deleted');
            redirect($prev_url);
        }
    }

    public function get_unclosedPDC()
    {
        if ($this->session->userdata('leasing_logged_in') || $this->session->userdata('recon_logged_in'))
        {
            $result = $this->app_model->get_unclosedPDC();
            echo json_encode($result->result_array());
        }
    }

    public function penalties()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/penalties');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }

    public function tenant_penalties()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id = $this->sanitize($this->uri->segment(3));
            $result    = $this->app_model->tenant_penalties($tenant_id);
            echo json_encode($result);
        }
    }


    public function print_tenantLedger()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $tenant_id     = $this->uri->segment(3);
            $query         = $this->app_model->export_tenantLedger($tenant_id);
            $response      = array();
            $date          = new DateTime();
            $timeStamp     = $date->getTimestamp();
            $tenantDetails = $this->app_model->get_tenantDetails($tenant_id, '');
            $file_name     =  $tenant_id . '_ledger' . $timeStamp . '.pdf';

            if ($query)
            {
                $result        = $query->result_array();
                $store_code    = $this->app_model->tenant_storeCode($tenant_id);
                $store_details = $this->app_model->store_details(trim($store_code));


                $pdf = new FPDF('L','mm','A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');
                $logoPath = getcwd() . '/assets/other_img/';


                foreach ($store_details as $detail)
                {

                    $pdf->cell(0, 20, $pdf->Image($logoPath . $detail['logo'], 145, $pdf->GetY(), 15), 0, 0, 'C', false);
                    $pdf->ln();
                    $pdf->setFont ('times','B',14);
                    $pdf->cell(15, 6, " ", 0, 0, 'L');
                    $pdf->cell(0, 10, strtoupper($detail['store_name']), 0, 0, 'C');
                    $store_name = $detail['store_name'];
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFillColor(35, 35, 35);
                    $pdf->cell(35, 6, " ", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->setFont ('times','',14);
                    $pdf->cell(15, 0, " ", 0, 0, 'L');
                    $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');
                    $pdf->ln();
                    $pdf->setFont('times','B',16);
                    $pdf->cell(10, 0, " ", 0, 0, 'L');
                    $pdf->cell(0, 6, "Tenant Ledger", 0, 0, 'C');
                    $pdf->ln();
                    $pdf->ln();
                }



                foreach ($tenantDetails as $detail)
                {
                    $pdf->setFont('times','',10);
                    $pdf->cell(40, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Tenancy Type", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['tenancy_type'], 1, 0, 'L');
                    $pdf->cell(5, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Address", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['address'], 1, 0, 'L');

                    $pdf->ln();
                    $pdf->cell(40, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Tenant ID", 0, 0, 'L');
                    $pdf->cell(60, 6, $tenant_id, 1, 0, 'L');
                    $pdf->cell(5, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "TIN", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['tin'], 1, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(40, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Trade Name", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['trade_name'], 1, 0, 'L');
                    $pdf->cell(5, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Contract No.", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['contract_no'], 1, 0, 'L');
                    $pdf->ln();
                    $pdf->cell(40, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Corporate Name", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['corporate_name'], 1, 0, 'L');
                    $pdf->cell(5, 6, " ", 0, 0, 'L');
                    $pdf->cell(30, 6, "Rental Type", 0, 0, 'L');
                    $pdf->cell(60, 6, $detail['rental_type'], 1, 0, 'L');

                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                    $pdf->ln();
                }

                $header = array('Entry No.', 'Document No.', 'Document Type', 'Referrence No.', 'Description', 'Posting Date', 'Due Date', 'Debit', 'Credit', 'Balance');
                $pdf->SetTextColor(201, 201, 201);
                $pdf->setFont('times','B',10);
                $pdf->SetFillColor(0, 0, 0);

                $pdf->cell(15, 8, 'Entry #', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Document No.', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Document Type', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Referrence No.', 1, 0, 'C', TRUE);
                $pdf->cell(54, 8, 'Description', 1, 0, 'C', TRUE);
                $pdf->cell(25, 8, 'Posting Date', 1, 0, 'C', TRUE);
                $pdf->cell(20, 8, 'Due Date', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Debit', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Credit', 1, 0, 'C', TRUE);
                $pdf->cell(28, 8, 'Balance', 1, 0, 'C', TRUE);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->setFont('times','',10);

                foreach ($result as $value)
                {
                    $pdf->ln();
                    $pdf->cell(15, 8, $value['id'], 1, 0, 'L');
                    $pdf->cell(28, 8, $value['doc_no'], 1, 0, 'L');
                    $pdf->cell(28, 8, $value['document_type'], 1, 0, 'L');
                    $pdf->cell(28, 8, $value['ref_no'], 1, 0, 'L');
                    $pdf->cell(54, 8, $value['description'], 1, 0, 'L');
                    $pdf->cell(25, 8, $value['posting_date'], 1, 0, 'L');
                    $pdf->cell(20, 8, $value['due_date'], 1, 0, 'L');
                    $pdf->cell(28, 8, number_format($value['debit'], 2), 1, 0, 'R');
                    $pdf->cell(28, 8, number_format($value['credit'], 2), 1, 0, 'R');
                    $pdf->cell(28, 8, number_format($value['balance'], 2), 1, 0, 'R');

                }

                $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                $pdf->Output('assets/pdf/' . $file_name , 'F');
                $response['msg'] = "Success";

            }
            else
            {
                $response['msg'] = 'No Entry';
            }
            echo json_encode($response);
        }
    }

    public function waive_penalty()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $sl_id = $this->uri->segment(3);

            $tenant_id;
            $posting_date;
            $doc_no;
            $amount;
            $response = array();

            $this->db->trans_start();

            $sl_entry = $this->app_model->get_SLEntry($sl_id);

            foreach ($sl_entry as $entry)
            {

                $sl_reverse = array(
                    'posting_date'    =>  $this->_currentDate,
                    'document_type'   =>  'Waive Penalty',
                    'ref_no'          =>  $entry['ref_no'],
                    'tenant_id'       =>  $entry['tenant_id'],
                    'contract_no'     =>  $entry['contract_no'],
                    'description'     =>  $entry['description'],
                    'debit'           =>  $entry['amount'],
                    'balance'         =>  0
                );

                $tenant_id    = $entry['tenant_id'];
                $posting_date = $entry['posting_date'];
                $doc_no       = $entry['doc_no'];
                $amount       = $entry['amount'];
                $this->app_model->insert('ledger', $sl_reverse);
            }


            $this->app_model->delete('invoicing', 'doc_no', $doc_no);
            $gl_entry   = $this->app_model->GLPenaltyEntry($tenant_id, $posting_date, $doc_no, $amount);

            // Upload Waiver
            $targetPath = getcwd() . '/assets/other_img/';
            $waiver     = $this->upload_image($targetPath, 'waiver', $tenant_id);


            foreach ($gl_entry as $value)
            {

                $account_receivable = array(
                    'posting_date'     =>  $this->_currentDate,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'    =>  'Credit Memo',
                    'ref_no'           =>  $value['ref_no'],
                    'tenant_id'        =>  $tenant_id,
                    'gl_accountID'     =>  $value['gl_accountID'],
                    'company_code'     =>  $value['company_code'],
                    'department_code'  =>  $value['department_code'],
                    'credit'           =>  -1 * $value['debit'],
                    'tag'              =>  'Penalty',
                    'prepared_by'      =>  $this->session->userdata('id')
                );


                $this->app_model->insert('general_ledger', $account_receivable);
                $this->app_model->insert('subsidiary_ledger', $account_receivable);


                $mipenalties = array(
                    'posting_date'      =>  $this->_currentDate,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Credit Memo',
                    'ref_no'            =>  $value['ref_no'],
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                    'company_code'      =>  $value['company_code'],
                    'department_code'   =>  $value['department_code'],
                    'debit'             =>  $value['debit'],
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $mipenalties);
                $this->app_model->insert('subsidiary_ledger', $mipenalties);
                $this->app_model->delete_tmppenalty($tenant_id, $doc_no, $posting_date);
            }

            $waived_penalties  = array(
                'sl_id'         =>  $sl_id,
                'attachment'    =>  $waiver,
                'prepared_by'   =>  $this->session->userdata('id')
            );

            $this->app_model->insert('waived_penalties', $waived_penalties);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'DB Error';
            } else {
                $response['msg'] = 'Success';
            }
            echo json_encode($response);
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }



    public function gl_code($description)
    {
        $gl_code;
        if ($description == 'Common Usage Charges')
        {
            $gl_code = '20.80.01.08.03';
        }
        elseif ($description == 'Electricity')
        {
            $gl_code = '20.80.01.08.02';
        }
        elseif ($description == 'Aircon')
        {
            $gl_code = '20.80.01.08.04';
        }
        elseif ($description == 'Chilled Water')
        {
            $gl_code = '20.80.01.08.05';
        }
        elseif ($description == 'Late submission of Deposit Slip' || $description == 'Late Payment Penalty' || $description == 'Penalty')
        {
            $gl_code = '20.80.01.08.01';
        }
        elseif ($description == 'Water')
        {
            $gl_code = '20.80.01.08.08';
        }
        else
        {
            $gl_code = '20.80.01.08.07';
        }

        return $gl_code;
    }



    public function check_totalPayableForSOA($tenant_id, $contract_no, $tenant_type, $due_date, $retro_due_date, $preop_doc_no)
    {
        if ($this->session->userdata('leasing_logged_in'))
        {	
        	//TEMPORARILY REMOVE PENALTY DUE TO COVID
            //$is_penaltyExempt = $this->app_model->is_penaltyExempt($tenant_id);
        	$is_penaltyExempt = true;

            $overall_amount  = 0.00;
            $advance_amount  = 0;
            $tenant_advances = $this->app_model->get_totalAdvance($tenant_id, $contract_no);
            if ($tenant_advances)
            {
                foreach ($tenant_advances as $amount)
                {
                   $advance_amount += $amount['advance'];
                   $advance_date   = $amount['transaction_date'];
                }
            }



            $is_advanceDeduction    = false;
            $orginal_advancePayment = $advance_amount;
            $GL_advanceAmount       = $advance_amount;
            $SL_advanceAmount       = $advance_amount;
            $store_code             = $this->app_model->tenant_storeCode($tenant_id);
            $store_details          = $this->app_model->store_details(trim($store_code));
            // $soa_no                 = $this->app_model->get_soaNo();
            $store_name             = "";
            $trade_name;
            $contract_no;
            $rental_type            = "";
            $tenancy_type           = "";
            $rent_percentage;
            $preop_total            = 0;

            // For Preoparation Charges
            if ($preop_doc_no)
            {
                $preop_data = $this->app_model->get_preopdata($tenant_id);
                if ($preop_data)
                {
                    $preop_total = 0;
                    foreach ($preop_data as $preop)
                    {
                        $preop_total    += $preop['amount'];
                        $overall_amount += $preop['amount'];
                    }
                }
            }

            if ($retro_due_date)
            {
                $retro_data = $this->app_model->get_invoiceRetro($tenant_id);
                if ($retro_data)
                {
                    foreach ($retro_data as $retro)
                    {
                        $overall_amount += $retro['balance'];
                    }
                }
            }

            // =================== Receipt Charges Breakdown ============= //



            $due_date = array_values(array_unique($due_date));
            $due_date = $this->sort_ascending($due_date);

            if ($due_date)
            {
                $latest_dueDate = max($due_date);
            }

            for ($i=0; $i < count($due_date); $i++)
            {
                //===== Calculate due_date difference to determine the appropriate penalty ===== //
                $daylen               = 60*60*24;
                $daysDiff             = (strtotime($latest_dueDate) - strtotime($due_date[$i]))/$daylen;
                $daysDiff             = $daysDiff / 20;
                $daysDiff             = floor($daysDiff);
                $current_due          = strtotime($due_date[$i]);
                $basic_rent           = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Basic/Monthly Rental');
                $rental_increment     = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Rent Incrementation');
                $discount             = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Discount');
                $basic                = $this->app_model->chargesDetails($tenant_id, $due_date[$i], 'Basic');
                $other_charges        = $this->app_model->other_chargeDetails($tenant_id, $due_date[$i]);
                $total_perDuedate     = $this->app_model->previous_totalPerDueDate($tenant_id, $due_date[$i]);
                $total_payableDuedate = $this->app_model->total_payableDuedate($tenant_id, $due_date[$i]);
                $overall_amount       += $total_perDuedate;

                if ($i != count($due_date)-1)  // Check current due date
                {

                    // Previous Due Date
                    $previous_totalPerDueDate = $this->app_model->previous_totalPerDueDate($tenant_id, $due_date[$i]);
                    $previous_totalPerDueDate = $this->app_model->previous_totalPerDueDate($tenant_id, $due_date[$i]);
                    $nopenalty_amount         = $this->app_model->previous_withNoPenalty($tenant_id, $due_date[$i]);
                    $penalty                  = 0;


                    if (!$is_penaltyExempt)
                    {
                        if ($previous_totalPerDueDate > 1)
                        {
                            if ($daysDiff != 0)
                            {
                                $percent_penalty;
                                $additional_penalty;
                                $penalty_carryOver;
                                $previous_totalPerDueDate -= $nopenalty_amount;
                                if ($daysDiff == 1)
                                {

                                    $percent_penalty    = 2;
                                    $penalty            = $previous_totalPerDueDate * .02;
                                    $additional_penalty = $penalty;
                                }
                                elseif($daysDiff >= 2)
                                {

                                    $percent_penalty    = 3;
                                    $penalty            = $previous_totalPerDueDate * .03;
                                    $additional_penalty = $penalty;
                                }

                            }
                            $overall_amount += $penalty;
                        }
                    }


                } // End of Previous Due Date
            } //End of Due Date Loop


            // If has waived penalties
            $waived_penalty = $this->app_model->get_waivedPenalty($tenant_id, min($due_date), max($due_date));
            if ($waived_penalty > 1)
            {
                $overall_amount = $overall_amount - $waived_penalty;
            }

            // To show advance amount to SOA
            if ($tenant_advances)
            {
                $amount_to_show = 0;
                foreach ($tenant_advances as $amount)
                {
                    $amount_to_show += $amount['advance'];
                }

                if ($amount_to_show > 0)
                {
                    $overall_amount -= $preop_total;
                    $overall_amount = $overall_amount - $amount_to_show;

                    if ($overall_amount < 0)
                    {
                        $overall_amount = $preop_total;
                    }
                    else
                    {
                        $overall_amount += $preop_total;
                    }
                }
            }
            return $overall_amount;
        }
    }

    public function recognize_rentDue()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['details']        = $this->app_model->get_wofDetails();
            $data['doc_no']         = $this->app_model->get_WOFDocNo();
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/recognize_rentDue');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function save_recognizeRentDue()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response         = array();
            $RI_amount        = str_replace(",", "", $this->input->post('RI_amount'));
            $VAT_amount       = str_replace(",", "", $this->input->post('VAT_amount'));
            $WHT_amount       = str_replace(",", "", $this->input->post('WHT_amount'));
            $CC_amount        = str_replace(",", "", $this->input->post('CC_amount'));
            $EC_amount        = str_replace(",", "", $this->input->post('EC_amount'));
            $OT_amount        = str_replace(",", "", $this->input->post('OT_amount'));
            $penalty_amount   = str_replace(",", "", $this->input->post('penalty_amount'));
            $service_req      = str_replace(",", "", $this->input->post('service_req'));
            $notary_fee       = str_replace(",", "", $this->input->post('notary_fee'));
            $np_adjustment    = str_replace(",", "", $this->input->post('np_adjustment'));
            $transaction_date = $this->input->post('transaction_date');
            $doc_no           = $this->input->post('doc_no');
            $tenant_id        = $this->input->post('tenant_id');
            $ARNTI_amount     = (($RI_amount + $VAT_amount  + $CC_amount + $EC_amount + $OT_amount + $penalty_amount + $service_req + $notary_fee) - $np_adjustment) - $WHT_amount;

            $this->db->trans_start(); // Transaction function starts here!!!

            $gl_refno = $this->app_model->gl_refNo();

            $ARNTI_entry = array(
                'posting_date'      =>  $transaction_date,
                'transaction_date' =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refno,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'debit'             =>  $ARNTI_amount,
                'prepared_by'       =>  $this->_user_id
            );
            $ARNTI_WOF  = array('doc_no' => $doc_no, 'description' => 'Account Receivable', 'amount' => $ARNTI_amount);
            $this->app_model->insert('wof_transactions', $ARNTI_WOF);
            $this->app_model->insert('general_ledger', $ARNTI_entry);
            $this->app_model->insert('subsidiary_ledger', $ARNTI_entry);


            $WHT_entry = array(
                'posting_date'      =>  $transaction_date,
                'transaction_date' =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refno,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.06.05'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'debit'             =>  $WHT_amount,
                'prepared_by'       =>  $this->_user_id
            );
            $WHT_WOF  = array('doc_no' => $doc_no, 'description' => 'Creditable Withholding Taxes', 'amount' => $WHT_amount);
            $this->app_model->insert('wof_transactions', $WHT_WOF);
            $this->app_model->insert('general_ledger', $WHT_entry);
            $this->app_model->insert('subsidiary_ledger', $WHT_entry);

            $RI_entry = array(
                'posting_date'      =>  $transaction_date,
                'transaction_date' =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refno,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('20.60.01'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'credit'            =>  $RI_amount * -1,
                'prepared_by'       =>  $this->_user_id
            );
            $RI_WOF  = array('doc_no' => $doc_no, 'description' => 'Rent Income', 'amount' => $RI_amount);
            $this->app_model->insert('wof_transactions', $RI_WOF);
            $this->app_model->insert('general_ledger', $RI_entry);
            $this->app_model->insert('subsidiary_ledger', $RI_entry);


            $VAT_entry = array(
                'posting_date'      =>  $transaction_date,
                'transaction_date' =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refno,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.01.14'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'credit'            =>  $VAT_amount  * -1,
                'prepared_by'       =>  $this->_user_id
            );
            $VAT_WOF  = array('doc_no' => $doc_no, 'description' => 'VAT Output', 'amount' => $VAT_amount);
            $this->app_model->insert('wof_transactions', $VAT_WOF);
            $this->app_model->insert('general_ledger', $VAT_entry);
            $this->app_model->insert('subsidiary_ledger', $VAT_entry);

            $CC_entry = array(
                'posting_date'      =>  $transaction_date,
                'transaction_date' =>  $this->_currentDate,
                'document_type'     =>  'Invoice',
                'ref_no'            =>  $gl_refno,
                'doc_no'            =>  $doc_no,
                'tenant_id'         =>  $tenant_id,
                'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.07'),
                'company_code'      =>  $this->session->userdata('company_code'),
                'department_code'   =>  '01.04',
                'credit'            =>  $CC_amount * -1,
                'prepared_by'       =>  $this->_user_id
            );
            $CC_WOF  = array('doc_no' => $doc_no, 'description' => 'Cashier Charges', 'amount' => $CC_amount);
            $this->app_model->insert('wof_transactions', $CC_WOF);
            $this->app_model->insert('general_ledger', $CC_entry);
            $this->app_model->insert('subsidiary_ledger', $CC_entry);

            if ($EC_amount > 0)
            {
                $EC_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.02'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $EC_amount * -1,
                    'prepared_by'       =>  $this->_user_id
                );
                $EC_WOF  = array('doc_no' => $doc_no, 'description' => 'Electricity Charges', 'amount' => $EC_amount);
                $this->app_model->insert('wof_transactions', $EC_WOF);
                $this->app_model->insert('general_ledger', $EC_entry);
                $this->app_model->insert('subsidiary_ledger', $EC_entry);
            }


            if ($OT_amount > 0)
            {
                $OT_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.07'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $OT_amount * -1,
                    'prepared_by'       =>  $this->_user_id
                );
                $OT_WOF  = array('doc_no' => $doc_no, 'description' => 'Overtime Charges', 'amount' => $OT_amount);
                $this->app_model->insert('wof_transactions', $OT_WOF);
                $this->app_model->insert('general_ledger', $OT_entry);
                $this->app_model->insert('subsidiary_ledger', $OT_entry);
            }



            if ($penalty_amount > 0)
            {
                $penalty_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.01'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $penalty_amount * -1,
                    'prepared_by'       =>  $this->_user_id
                );
                $penalty_WOF  = array('doc_no' => $doc_no, 'description' => 'Penalty', 'amount' => $penalty_amount);
                $this->app_model->insert('wof_transactions', $penalty_WOF);
                $this->app_model->insert('general_ledger', $penalty_entry);
                $this->app_model->insert('subsidiary_ledger', $penalty_entry);
            }



            if ($service_req > 0)
            {
                $penalty_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.07'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $service_req * -1,
                    'prepared_by'       =>  $this->_user_id
                );
                $penalty_WOF  = array('doc_no' => $doc_no, 'description' => 'Penalty', 'amount' => $penalty_amount);
                $this->app_model->insert('wof_transactions', $penalty_WOF);
                $this->app_model->insert('general_ledger', $penalty_entry);
                $this->app_model->insert('subsidiary_ledger', $penalty_entry);
            }


            if ($notary_fee > 0)
            {
                $notary_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.07'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  $notary_fee * -1,
                    'prepared_by'       =>  $this->_user_id
                );
                $notaryFee_WOF  = array('doc_no' => $doc_no, 'description' => 'Notary Fee', 'amount' => $notary_fee);
                $this->app_model->insert('wof_transactions', $notaryFee_WOF);
                $this->app_model->insert('general_ledger', $notary_entry);
                $this->app_model->insert('subsidiary_ledger', $notary_entry);
            }



            if ($np_adjustment > 0)
            {
                $adjustment_entry = array(
                    'posting_date'      =>  $transaction_date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Invoice',
                    'ref_no'            =>  $gl_refno,
                    'doc_no'            =>  $doc_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('20.80.01.08.07'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'debit'             =>  $np_adjustment,
                    'prepared_by'       =>  $this->_user_id
                );
                $adjustment_WOF  = array('doc_no' => $doc_no, 'description' => 'Cashier Posting Adjustment', 'amount' => $notary_fee);
                $this->app_model->insert('wof_transactions', $adjustment_WOF);
                $this->app_model->insert('general_ledger', $adjustment_entry);
                $this->app_model->insert('subsidiary_ledger', $adjustment_entry);
            }


            $this->db->trans_complete(); // End of transaction function



            if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
            {
                $this->db->trans_rollback(); // If failed rollback all queries
                $response['msg'] = 'Error';
            }
            else
            {
                $response['msg'] = 'Success';
            }

            echo json_encode($response);
        }

    }



    public function closing_rentDue()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['flashdata']      = $this->session->flashdata('message');
            $data['details']        = $this->app_model->get_wofDetails();
            $data['doc_no']         = $this->app_model->get_WOFDocNo();
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/closing_rentDue');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function save_closingRentDue()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $response = array();


            $tenant_id        = $this->input->post('tenant_id');
            $contract_no      = $this->input->post('contract_no');
            $trade_name       = $this->input->post('trade_name');
            $entry_id         = $this->input->post('entry_id');
            $transaction_date = $this->input->post('transaction_date');
            $JV_no            = $this->sanitize($this->input->post('JV_no'));
            $tender_amount    = str_replace(",", "", $this->sanitize($this->input->post('tender_amount')));
            $supp_doc         = "";
            $entry_id         = $this->sort_ascending($entry_id);

            if ($tender_amount > 0 && count($entry_id) > 0)
            {
                $this->db->trans_start(); // Transaction function starts here!!!

                $targetPath = getcwd() . '/assets/payment_docs/';
                $supp_doc   = $this->upload_image($targetPath, 'JV_doc', $tenant_id);

                for ($i=0; $i < count($entry_id); $i++)
                {
                    if ($tender_amount > 0)
                    {
                        $gl_entries = $this->app_model->selectWhere('general_ledger', $entry_id[$i]);
                        foreach ($gl_entries as $entry)
                        {
                            $rentDue_amount = ABS($entry['debit']);
                            $JV_entry = array(
                                'posting_date'      =>  $transaction_date,
                                'transaction_date' =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $entry['ref_no'],
                                'doc_no'            =>  $JV_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'debit'             =>  $rentDue_amount,
                                'prepared_by'       =>  $this->_user_id
                            );

                            $this->app_model->insert('general_ledger', $JV_entry);
                            $this->app_model->insert('subsidiary_ledger', $JV_entry);

                            $ARNTI_entry = array(
                                'posting_date'      =>  $transaction_date,
                                'transaction_date' =>  $this->_currentDate,
                                'document_type'     =>  'Payment',
                                'ref_no'            =>  $entry['ref_no'],
                                'doc_no'            =>  $JV_no,
                                'tenant_id'         =>  $tenant_id,
                                'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.04'),
                                'company_code'      =>  $this->session->userdata('company_code'),
                                'department_code'   =>  '01.04',
                                'credit'            =>  $rentDue_amount * -1,
                                'prepared_by'       =>  $this->_user_id
                            );

                            $this->app_model->insert('general_ledger', $ARNTI_entry);
                            $this->app_model->insert('subsidiary_ledger', $ARNTI_entry);


                            $paymentScheme = array(
                                'tenant_id'        =>   $tenant_id,
                                'contract_no'      =>   $contract_no,
                                'tenancy_type'     =>   'Long Term',
                                'receipt_no'       =>   $JV_no,
                                'tender_typeCode'  =>   '80',
                                'billing_period'   =>   '',
                                'tender_typeDesc'  =>   'JV Payment - Business Unit',
                                'soa_no'           =>   '',
                                'amount_due'       =>   $rentDue_amount,
                                'amount_paid'      =>   $rentDue_amount,
                                'bank'             =>   '',
                                'check_no'         =>   '',
                                'check_date'       =>   '',
                                'payor'            =>   $trade_name,
                                'payee'            =>   $this->session->userdata('store_code'),
                                'receipt_doc'      =>   $supp_doc
                            );

                            $this->app_model->insert('payment_scheme', $paymentScheme);

                            $tender_amount -= $rentDue_amount;
                        }
                    }
                } // End of for loop

                $this->db->trans_complete(); // End of transaction function
                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $response['msg'] = 'Error';
                }
                else
                {
                    $response['msg'] = 'Success';
                }
            }
            else
            {
                $response['msg'] = 'Required';
            }

            echo json_encode($response);
        }
    }

    public function advance_payment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['payee']          = $this->app_model->my_store();
            $data['store_id']       = $this->session->userdata('user_group');
            $data['flashdata']      = $this->session->flashdata('message');
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/advance_payment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function record_internalPayment() {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['payee']          = $this->app_model->my_store();
            $data['store_id']       = $this->session->userdata('user_group');
            $data['flashdata']      = $this->session->flashdata('message');
            $data['trans_no']       = $this->app_model->generate_InternalTransactionNo();
            $data['stores']          = $this->app_model->get_stores();
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $this->load->view('leasing/header', $data);
            $this->load->view('leasing/record_internalPayment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function reverse_internalPayment() {

        if ($this->session->userdata('leasing_logged_in'))
        {
            $data['banks']          = $this->app_model->getAll('accredited_banks');
            $data['trans_no']       = $this->app_model->generate_reverseInternalTransactionNo();
            $data['current_date']   = $this->_currentDate;
            $data['expiry_tenants'] = $this->app_model->get_expiryTenants();
            $data['flashdata']      = $this->session->flashdata('message');
            $this->load->view('leasing/header', $data);
            $this->load->view('cfs/closing_internal_payment');
            $this->load->view('leasing/footer');
        }
        else
        {
            redirect('ctrl_leasing/');
        }
    }


    public function save_advancePayment()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date         = new DateTime();
            $timeStamp    = $date->getTimestamp();
            $response     = array();
            $tenant_id    = $this->sanitize($this->input->post('tenant_id'));
            $receipt_no   = "PR" . strtoupper($this->sanitize($this->input->post('receipt_no')));
            $amount_paid  = str_replace(",", "", $this->sanitize($this->input->post('amount_paid')));
            $trade_name   = $this->sanitize($this->input->post('trade_name'));
            $contract_no  = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type = $this->sanitize($this->input->post('tenancy_type'));
            $date         = $this->sanitize($this->input->post('curr_date'));
            $file_name    =  $tenant_id . $timeStamp . '.pdf';

            // =============== Payment Scheme =============== //

            $tender_typeCode = $this->sanitize($this->input->post('tender_typeCode'));
            $tender_typeDesc = $this->sanitize($this->input->post('tender_typeDesc'));
            $amount_paid     = $this->sanitize($this->input->post('amount_paid'));
            $amount_paid     = str_replace(",", "", $amount_paid);
            $bank            = $this->sanitize($this->input->post('bank'));
            $bank_code       = $this->sanitize($this->input->post('bank_code'));
            $check_no        = $this->sanitize($this->input->post('check_no'));
            $check_date      = $this->sanitize($this->input->post('check_date'));
            $payor           = $this->sanitize($this->input->post('payor'));
            $payee           = $this->sanitize($this->input->post('payee'));
            $supp_doc        = "";
            $pdc_status      = "";

            if (($tender_typeDesc == 'Cash' || $tender_typeDesc == 'Check' || $tender_typeDesc == 'Bank to Bank') && $bank == '')
            {
                $response['msg'] = 'Required';
            }
            else
            {
                if ($tender_typeDesc != 'Cash')
                {
                    for ($i=0; $i < count($_FILES["supp_doc"]['name']); $i++)
                    {
                        $targetPath = getcwd() . '/assets/payment_docs/';
                        $tmpFilePath = $_FILES['supp_doc']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename = $tenant_id . $timeStamp . $_FILES['supp_doc']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'receipt_no' => $receipt_no);
                            $this->app_model->insert('payment_supportingdocs', $data);
                        }
                    }
                }

                $this->db->trans_start(); // Transaction function starts here!!!

                if ($tender_typeDesc == 'Check')
                {
                    if ($check_date > $date)
                    {
                        $pdc_status = 'PDC';
                    }
                }


                $dataLedger = array(
                    'posting_date'       =>  $date,
                    'transaction_date'   =>  $date,
                    'document_type'      =>  'Advance Payment',
                    'doc_no'             =>  $receipt_no,
                    'ref_no'             =>  $this->app_model->generate_refNo(),
                    'tenant_id'          =>  $tenant_id,
                    'contract_no'        =>  $contract_no,
                    'description'        =>  'Advance Payment-' . $trade_name,
                    'debit'              =>  $amount_paid,
                    'credit'             =>  0,
                    'balance'            =>  $amount_paid
                );

                $this->app_model->insert('ledger', $dataLedger);


                //======== Unearned Rent for Advance Payment =========//

                $advance_refNo = $this->app_model->gl_refNo();


                if ($tender_typeDesc == 'Cash' || $tender_typeDesc == 'Bank to Bank')
                {

                    $advance_CIB = array(
                        'posting_date'      =>  $date,
                        'transaction_date' =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $advance_refNo,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'debit'             =>  $amount_paid,
                        'bank_name'         =>  $bank,
                        'bank_code'         =>  $bank_code,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('general_ledger', $advance_CIB);
                    $this->app_model->insert('subsidiary_ledger', $advance_CIB);

                }
                elseif ($tender_typeDesc == 'Check')
                {
                    if ($check_date > $date)
                    {

                        $advance_PDC = array(
                            'posting_date'      =>  $date,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.07.01'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'debit'             =>  $amount_paid,
                            'bank_name'         =>  $bank,
                            'bank_code'         =>  $bank_code,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );

                        $this->app_model->insert('general_ledger', $advance_PDC);
                        $this->app_model->insert('subsidiary_ledger', $advance_PDC);
                    }
                    else
                    {
                        $advance_CIB = array(
                            'posting_date'      =>  $date,
                            'transaction_date' =>  $this->_currentDate,
                            'document_type'     =>  'Payment',
                            'ref_no'            =>  $advance_refNo,
                            'doc_no'            =>  $receipt_no,
                            'tenant_id'         =>  $tenant_id,
                            'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.01.02'),
                            'company_code'      =>  $this->session->userdata('company_code'),
                            'department_code'   =>  '01.04',
                            'debit'             =>  $amount_paid,
                            'bank_name'         =>  $bank,
                            'bank_code'         =>  $bank_code,
                            'prepared_by'       =>  $this->session->userdata('id')
                        );

                        $this->app_model->insert('general_ledger', $advance_CIB);
                        $this->app_model->insert('subsidiary_ledger', $advance_CIB);
                    }
                }
                elseif ($tender_typeDesc == 'JV payment - Business Unit')
                {
                    $advance_JV = array(
                        'posting_date'      =>  $date,
                        'transaction_date' =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $advance_refNo,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $this->app_model->gl_accountID($this->app_model->bu_entry()),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'debit'             =>  $amount_paid,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('general_ledger', $advance_JV);
                    $this->app_model->insert('subsidiary_ledger', $advance_JV);
                }
                elseif ($tender_typeDesc == 'JV payment - Subsidiary')
                {
                    $advance_JV = array(
                        'posting_date'      =>  $date,
                        'transaction_date' =>  $this->_currentDate,
                        'document_type'     =>  'Payment',
                        'ref_no'            =>  $advance_refNo,
                        'doc_no'            =>  $receipt_no,
                        'tenant_id'         =>  $tenant_id,
                        'gl_accountID'      =>  $this->app_model->gl_accountID('10.10.01.03.11'),
                        'company_code'      =>  $this->session->userdata('company_code'),
                        'department_code'   =>  '01.04',
                        'debit'             =>  $amount_paid,
                        'prepared_by'       =>  $this->session->userdata('id')
                    );

                    $this->app_model->insert('general_ledger', $advance_JV);
                    $this->app_model->insert('subsidiary_ledger', $advance_JV);
                }


                $advance_unearned = array(
                    'posting_date'      =>  $date,
                    'transaction_date' =>  $this->_currentDate,
                    'document_type'     =>  'Payment',
                    'ref_no'            =>  $advance_refNo,
                    'doc_no'            =>  $receipt_no,
                    'tenant_id'         =>  $tenant_id,
                    'gl_accountID'      =>  $this->app_model->gl_accountID('10.20.01.01.02.01'),
                    'company_code'      =>  $this->session->userdata('company_code'),
                    'department_code'   =>  '01.04',
                    'credit'            =>  -1 * $amount_paid,
                    'status'            =>  $pdc_status,
                    'prepared_by'       =>  $this->session->userdata('id')
                );

                $this->app_model->insert('general_ledger', $advance_unearned);
                $this->app_model->insert('subsidiary_ledger', $advance_unearned);


                // For Accountability Report
                $this->app_model->insert_accReport($tenant_id, 'Advance Deposit', $amount_paid, $date, $tender_typeDesc);


                 // For Montly Receivable Report
                $reportData = array(
                    'tenant_id'     =>  $tenant_id,
                    'doc_no'        =>  $receipt_no,
                    'posting_date'  =>  $date,
                    'description'   =>  'Advance Payment',
                    'amount'        =>  $amount_paid
                );

                $this->app_model->insert('monthly_receivable_report', $reportData);


                // ============================ PDF =============================== //

                $store_code    = $this->app_model->tenant_storeCode($tenant_id);
                $store_details = $this->app_model->store_details(trim($store_code));
                $details_soa   = $this->app_model->details_soa($tenant_id);
                $lessee_info   = $this->app_model->get_lesseeInfo($tenant_id, $contract_no);

                $daysOfMonth = date('t', strtotime($date));


                $pdf = new FPDF('p','mm','A4');
                $pdf->AddPage();
                $pdf->setDisplayMode ('fullpage');
                $logoPath = getcwd() . '/assets/other_img/';

                // ==================== Receipt Header ================== //

                foreach ($store_details as $detail)
                {
                    $pdf->cell(20, 20, $pdf->Image($logoPath . $detail['logo'], 100, $pdf->GetY(), 15), 0, 0, 'C', false);
                    $pdf->ln();
                    $pdf->setFont ('times','B',14);
                    $pdf->cell(75, 6, " ", 0, 0, 'L');
                    $pdf->cell(40, 10, strtoupper($detail['store_name']), 0, 0, 'L');
                    $store_name = $detail['store_name'];
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFillColor(35, 35, 35);
                    $pdf->cell(35, 6, " ", 0, 0, 'L');
                    $pdf->ln();
                    $pdf->setFont ('times','',14);
                    $pdf->cell(15, 0, " ", 0, 0, 'L');
                    $pdf->cell(0, 10, $detail['store_address'], 0, 0, 'C');

                    $pdf->ln();
                    $pdf->ln();
                }


                $pdf->ln();
                $pdf->setFont ('times','B',10);
                $pdf->cell(0, 5, "Please make all checks payable to " . strtoupper($payee) , 0, 0, 'R');
                $pdf->ln();
                $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                $pdf->ln();
                $pdf->ln();

                $pdf->setFont ('times','B',16);
                $pdf->cell(0, 6, "Payment Receipt", 0, 0, 'C');
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();


                // =================== Receipt Charges Table ============= //
                $pdf->setFont('times','B',10);
                $pdf->cell(30, 8, "Doc. Type", 0, 0, 'C');
                $pdf->cell(30, 8, "Document No.", 0, 0, 'C');
                $pdf->cell(30, 8, "Charges Type", 0, 0, 'C');
                $pdf->cell(30, 8, "Posting Date", 0, 0, 'C');
                $pdf->cell(30, 8, "Due Date", 0, 0, 'C');
                $pdf->cell(30, 8, "Total Amount Due", 0, 0, 'C');
                $pdf->setFont('times','',10);


                $pdf->ln();
                $pdf->cell(30, 8, "Payment", 0, 0, 'C');
                $pdf->cell(30, 8, $receipt_no, 0, 0, 'C');
                $pdf->cell(30, 8, "Advance Payment", 0, 0, 'C');
                $pdf->cell(30, 8, $date, 0, 0, 'C');
                $pdf->cell(30, 8, "", 0, 0, 'C');
                $pdf->cell(30, 8, $amount_paid, 0, 0, 'C');

                $pdf->ln();
                $pdf->cell(0, 5, "__________________________________________________________________________________________________________", 0, 0, 'L');
                $pdf->ln();


                $pdf->setFont('times','B',10);
                $pdf->cell(150, 8, "Payment Scheme:", 0, 0, 'L');
                $pdf->cell(100, 8, "Payment Date:" . $date, 0, 0, 'L');
                $pdf->ln();

                $pdf->setFont('times','',10);
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Description: ", 0, 0, 'L');
                $pdf->cell(60, 4, $tender_typeDesc, 0, 0, 'L');
                $pdf->cell(5, 4, " ", 0, 0, 'L');
                $pdf->cell(30, 4, "Total Payable: ", 0, 0, 'L');
                $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');
                $pdf->ln();

                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Bank: ", 0, 0, 'L');
                $pdf->cell(60, 4, $bank, 0, 0, 'L');
                $pdf->cell(5, 4, " ", 0, 0, 'L');
                $pdf->cell(30, 4, "Amount Paid: ", 0, 0, 'L');
                $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');
                $pdf->ln();
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Check Number: ", 0, 0, 'L');
                $pdf->cell(60, 4, $check_no, 0, 0, 'L');
                $pdf->cell(5, 4, " ", 0, 0, 'L');
                $pdf->cell(30, 4, "Balance: ", 0, 0, 'L');
                $pdf->cell(60, 4, "P " . "0.00", 0, 0, 'L');

                $pdf->ln();
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Check Date: ", 0, 0, 'L');
                $pdf->cell(60, 4, $check_date, 0, 0, 'L');
                $pdf->cell(5, 4, " ", 0, 0, 'L');
                $pdf->cell(30, 4, "Advance: ", 0, 0, 'L');
                $pdf->cell(60, 4, "P " . number_format($amount_paid, 2), 0, 0, 'L');

                $pdf->ln();
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Payor: ", 0, 0, 'L');
                $pdf->cell(60, 4, $payor, 0, 0, 'L');
                $pdf->ln();
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "Payee: ", 0, 0, 'L');
                $pdf->cell(60, 4, $payee, 0, 0, 'L');
                $pdf->ln();
                $pdf->cell(20, 4, "     ", 0, 0, 'L');
                $pdf->cell(30, 4, "OR #: ", 0, 0, 'L');
                $pdf->cell(60, 4, $receipt_no, 0, 0, 'L');

                $pdf->ln();
                $pdf->ln();
                $pdf->ln();

                if ($tender_typeDesc != 'Cash')
                {
                    $paymentScheme = array(
                        'tenant_id'        =>   $tenant_id,
                        'contract_no'      =>   $contract_no,
                        'tenancy_type'     =>   $tenancy_type,
                        'receipt_no'       =>   $receipt_no,
                        'tender_typeCode'  =>   $tender_typeCode,
                        'tender_typeDesc'  =>   $tender_typeDesc,
                        'amount_due'       =>   $amount_paid,
                        'amount_paid'      =>   $amount_paid,
                        'bank'             =>   $bank,
                        'check_no'         =>   $check_no,
                        'check_date'       =>   $check_date,
                        'payor'            =>   $payor,
                        'payee'            =>   $payee,
                        'supp_doc'         =>   $supp_doc,
                        'receipt_doc'      =>   $file_name
                    );

                    $this->app_model->insert('payment_scheme', $paymentScheme);
                }
                else
                {
                    $paymentScheme = array(
                        'tenant_id'        =>   $tenant_id,
                        'contract_no'      =>   $contract_no,
                        'tenancy_type'     =>   $tenancy_type,
                        'receipt_no'       =>   $receipt_no,
                        'tender_typeCode'  =>   $tender_typeCode,
                        'tender_typeDesc'  =>   $tender_typeDesc,
                        'amount_due'       =>   $amount_paid,
                        'amount_paid'      =>   $amount_paid,
                        'bank'             =>   $bank,
                        'check_no'         =>   $check_no,
                        'check_date'       =>   $check_date,
                        'payor'            =>   $payor,
                        'payee'            =>   $payee,
                        'receipt_doc'      =>   $file_name
                    );

                    $this->app_model->insert('payment_scheme', $paymentScheme);
                }


                

                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();

                $pdf->setFont('times','',10);
                $pdf->cell(0, 4, "Prepared By: _____________________      Check By:______________________", 0, 0, 'L');
                $pdf->ln();
                $pdf->ln();
                $pdf->ln();
                $pdf->setFont('times','B',10);
                $pdf->cell(0, 4, "Thank you for your prompt payment!", 0, 0, 'L');

                $this->db->trans_complete(); // End of transaction function

                if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                {
                    $this->db->trans_rollback(); // If failed rollback all queries
                    $response['msg'] = 'Error';
                }
                else
                {
                    $response['file_name'] = base_url() . 'assets/pdf/' . $file_name;
                    $pdf->Output('assets/pdf/' . $file_name , 'F');
                    $response['msg'] = "Success";
                }
                echo json_encode($response);
            }
        }
    }


    public function renew()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $date                 = new DateTime();
            $timeStamp            = $date->getTimestamp();
            $response[]           = array();
            $prospect_id          = $this->sanitize($this->input->post('prospect_id'));
            $tenant_incrementID   = $this->sanitize($this->input->post('tenant_incrementID'));
            $store_code           = $this->app_model->get_storeCode($prospect_id);
            $floor_location       = $this->sanitize($this->input->post('floor_location'));
            $tenant_id            = $this->sanitize($this->input->post('tenant_id'));
            $contract_no          = $this->sanitize($this->input->post('contract_no'));
            $tenancy_type         = $this->sanitize($this->input->post('tenancy_type'));
            $tin                  = $this->sanitize($this->input->post('tin'));
            $rental_type          = $this->sanitize($this->input->post('rental_type'));
            $rent_percentage      = $this->sanitize($this->input->post('rent_percentage'));
            $sales                = $this->sanitize($this->input->post('sales'));
            $location_code        = $this->sanitize($this->input->post('location_code'));
            $slots_id             = $this->sanitize($this->input->post('slots_id'));
            $floor_area           = str_replace(",", "", $this->input->post('floor_area'));
            $area_classification  = $this->sanitize($this->input->post('area_classification'));
            $area_type            = $this->sanitize($this->input->post('area_type'));
            $rent_period          = $this->sanitize($this->input->post('rent_period'));
            $location_desc        = $this->sanitize($this->input->post('location_desc'));
            $opening_date         = $this->sanitize($this->input->post('opening_date'));
            $expiry_date          = $this->sanitize($this->input->post('expiry_date'));
            $increment_percentage = $this->sanitize($this->input->post('increment_percentage'));
            $increment_frequency  = $this->sanitize($this->input->post('increment_frequency'));
            $tenant_type          = $this->input->post('tenant_type');
            $is_vat               = $this->input->post('plus_vat');
            $less_wht             = $this->input->post('less_wht');
            $vat_percentage       = $this->input->post('vat_percentage');
            $wht_percentage       = $this->input->post('wht_percentage');
            $vat_agreement        = $this->input->post('vat_agreement');
            $penalty_exempt       = $this->sanitize($this->input->post('penalty_exempt'));
            $bir_doc              = "";
            $basic_rental         = $this->sanitize($this->input->post('basic_rental'));
            $basic_rental         = str_replace(",", "", $basic_rental);
            $sdiscount            = $this->input->post('sdiscount');
            $store_name           = $this->input->post('store_name');
            $monthly_charges      = $this->input->post('monthly_charges');

            if ($is_vat == 'on') {$is_vat = 'Added';} else {$vat_percentage = 0;}
            if ($less_wht == 'on') {$less_wht = 'Added';} else {$wht_percentage = 0;}
            if ($increment_percentage == 'None') {$increment_percentage = 0;}
            if ($penalty_exempt != '1') {$penalty_exempt = 0;}

            $username = str_replace("%20", " ", $this->uri->segment(3));
            $password = str_replace("%20", " ", $this->uri->segment(4));

            if ($this->session->userdata('user_type') != "Administrator" && $this->session->userdata('user_type') != "Store Manager" && $this->session->userdata('user_type') != "Supervisor" && $this->session->userdata('user_type') != "Corporate Documentation Officer" && $this->session->userdata('user_type') != "Documentation Officer" && $this->session->userdata('user_type') != "Corporate Leasing Supervisor")
            {
                if ($this->app_model->managers_key($username, $password, $store_name))
                {
                    if (($rental_type == 'Percentage'  || $rental_type == 'Fixed Plus Percentage' || $rental_type == 'Fixed/Percentage w/c Higher') && $rent_percentage == '0')
                    {
                        $response['msg'] = "Please fill out all required fields.";
                    }
                    else
                    {
                        if ($tenant_type == 'Private Entities')
                        {
                            if ($is_vat != "Added")
                            {
                                $targetPath = getcwd() . '/assets/bir/';
                                $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                                //Make sure we have a filepath
                                if ($tmpFilePath != "")
                                {
                                    //Setup our new file path
                                    $filename = $timeStamp . $_FILES['bir_doc']['name'];
                                    $newFilePath = $targetPath . $filename;
                                    //Upload the file into the temp dir
                                    move_uploaded_file($tmpFilePath, $newFilePath);
                                    $bir_doc = $filename;
                                }
                            }
                        }

                        if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
                        {

                            for ($i=0; $i < count($_FILES["supporting_doc"]['name']); $i++)
                            {
                                $targetPath  = getcwd() . '/assets/other_img/';
                                $tmpFilePath = $_FILES['supporting_doc']['tmp_name'][$i];
                                //Make sure we have a filepath
                                if ($tmpFilePath != "")
                                {
                                    //Setup our new file path
                                    $filename    = $timeStamp . $_FILES['supporting_doc']['name'][$i];
                                    $newFilePath = $targetPath . $filename;
                                    //Upload the file into the temp dir
                                    move_uploaded_file($tmpFilePath, $newFilePath);

                                    $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                                    $this->app_model->insert('tenanttype_supportingdocs', $data);
                                }
                            }
                        }

                        $this->db->trans_start(); // Transaction function starts here!!!



                        $locationData = array(
                            'tenancy_type'          =>  $tenancy_type,
                            'slots_id'              =>  $slots_id,
                            'floor_id'              =>  $floor_location,
                            'location_code'         =>  $location_code,
                            'location_desc'         =>  $location_desc,
                            'floor_area'            =>  $floor_area,
                            'area_classification'   =>  $area_classification,
                            'area_type'             =>  $area_type,
                            'rent_period'           =>  $rent_period,
                            'rental_rate'           =>  $basic_rental,
                            'status'                =>  'Active',
                            'modified_by'           =>  $this->session->userdata('id'),
                            'date_modified'         =>  $this->_currentDate
                        );


                        $this->app_model->insert('location_code', $locationData);

                        $locationCode_id = $this->app_model->get_locationCode_id($location_code);
                        $data = array(
                            'prospect_id'          =>  $prospect_id,
                            'tenant_id'            =>  $tenant_id,
                            'locationCode_id'      =>  $locationCode_id,
                            'store_code'           =>  $store_code,
                            'rental_type'          =>  $rental_type,
                            'contract_no'          =>  $contract_no,
                            'tin'                  =>  $tin,
                            'rent_percentage'      =>  $rent_percentage,
                            'opening_date'         =>  $opening_date,
                            'expiry_date'          =>  $expiry_date,
                            'tenant_type'          =>  $tenant_type,
                            'increment_percentage' =>  $increment_percentage,
                            'increment_frequency'  =>  $increment_frequency,
                            'is_vat'               =>  $is_vat,
                            'wht'                  =>  $less_wht,
                            'sales'                =>  $sales,
                            'vat_percentage'       =>  $vat_percentage,
                            'wht_percentage'       =>  $wht_percentage,
                            'vat_agreement'        =>  $vat_agreement,
                            'penalty_exempt'       =>  $penalty_exempt,
                            'bir_doc'              =>  $bir_doc,
                            'basic_rental'         =>  $basic_rental,
                            'tenancy_type'         =>  $tenancy_type,
                            'status'               =>  'Active',
                            'flag'                 =>  'Posted',
                            'created_at'           =>  $this->_currentDate,
                            'prepared_by'          =>  $this->session->userdata('id')

                        );


                        $this->app_model->insert('tenants', $data);

                        $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

                        for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
                        {
                            $targetPath  = getcwd() . '/assets/contract_docs/';
                            $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename = $tenant_id . $timeStamp . $_FILES['contract_docs']['name'][$i];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);

                                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                                $this->app_model->insert('contract_docs', $data);
                            }
                        }

                        if ($sdiscount > 0)
                        {
                            for ($i=0; $i < count($sdiscount); $i++)
                            {
                                $discountData = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i], 'status' => 'Active');
                                $this->app_model->insert('selected_discount', $discountData);
                            }
                        }

                        if ($monthly_charges > 0)
                        {
                            for ($i=0; $i < count($monthly_charges); $i++)
                            {

                                $charges = explode("_", $monthly_charges[$i]);
                                $charges_id = $charges[0];
                                $unit_price = str_replace(",", "",  $charges[1]);
                                $charges_uom = $charges[2];
                                $selected_charges = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charges_id, 'unit_price' => $unit_price, 'uom' => $charges_uom,  'flag' => 'Active');
                                $this->app_model->insert('selected_monthly_charges', $selected_charges);
                            }
                        }


                        // Update Terminated contract status = 'Renewed'

                        $update_status = array('status'  =>  'Renewed');
                        $this->app_model->update_where($update_status, 'tenant_id', $tenant_incrementID, 'terminated_contract');



                        $this->db->trans_complete(); // End of transaction function

                        if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                        {
                            $this->db->trans_rollback(); // If failed rollback all queries
                            $error = array('action' => 'Saving Long Term Contract', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                            $this->app_model->insert('error_log', $error);
                        }
                        else
                        {
                            $this->db->query("UPDATE `prospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
                            $response['msg'] = "Success";
                            $response['tenancy_type'] = $tenancy_type;
                        }
                    }
                }
                else
                {
                    $response['msg'] = "Invalid Key";
                }
            }
            else
            {

                if (($rental_type == 'Percentage'  || $rental_type == 'Fixed Plus Percentage' || $rental_type == 'Fixed/Percentage w/c Higher') && $rent_percentage == '0')
                {
                    $response['msg'] = "Please fill out all required fields.";
                } else {

                    if ($tenant_type == 'Private Entities')
                    {
                        if ($is_vat != "Added")
                        {
                            $targetPath  = getcwd() . '/assets/bir/';
                            $tmpFilePath = $_FILES['bir_doc']['tmp_name'];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $timeStamp . $_FILES['bir_doc']['name'];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);
                                $bir_doc = $filename;
                            }
                        }
                    }


                    if ($tenant_type == 'Cooperative' || $tenant_type == 'Government Agencies(w/ Basic)' || $tenant_type == 'Government Agencies(w/o Basic)')
                    {

                        for ($i=0; $i < count($_FILES["supporting_doc"]['name']); $i++)
                        {
                            $targetPath  = getcwd() . '/assets/other_img/';
                            $tmpFilePath = $_FILES['supporting_doc']['tmp_name'][$i];
                            //Make sure we have a filepath
                            if ($tmpFilePath != "")
                            {
                                //Setup our new file path
                                $filename    = $timeStamp . $_FILES['supporting_doc']['name'][$i];
                                $newFilePath = $targetPath . $filename;
                                //Upload the file into the temp dir
                                move_uploaded_file($tmpFilePath, $newFilePath);

                                $data = array('tenant_id' => $tenant_id, 'file_name' => $filename);
                                $this->app_model->insert('tenanttype_supportingdocs', $data);
                            }
                        }

                    }

                    $this->db->trans_start(); // Transaction function starts here!!!


                    $locationData = array(
                        'tenancy_type'          =>  $tenancy_type,
                        'slots_id'              =>  $slots_id,
                        'floor_id'              =>  $floor_location,
                        'location_code'         =>  $location_code,
                        'location_desc'         =>  $location_desc,
                        'floor_area'            =>  $floor_area,
                        'area_classification'   =>  $area_classification,
                        'area_type'             =>  $area_type,
                        'rent_period'           =>  $rent_period,
                        'rental_rate'           =>  $basic_rental,
                        'status'                =>  'Active',
                        'modified_by'           =>  $this->session->userdata('id'),
                        'date_modified'         =>  $this->_currentDate
                    );


                    $this->app_model->insert('location_code', $locationData);

                    $locationCode_id = $this->app_model->get_locationCode_id($location_code);

                    $data = array(
                        'prospect_id'          =>  $prospect_id,
                        'tenant_id'            =>  $tenant_id,
                        'locationCode_id'      =>  $locationCode_id,
                        'store_code'           =>  $store_code,
                        'rental_type'          =>  $rental_type,
                        'contract_no'          =>  $contract_no,
                        'tin'                  =>  $tin,
                        'tenant_type'          =>  $tenant_type,
                        'rent_percentage'      =>  $rent_percentage,
                        'opening_date'         =>  $opening_date,
                        'expiry_date'          =>  $expiry_date,
                        'increment_percentage' =>  $increment_percentage,
                        'increment_frequency'  =>  $increment_frequency,
                        'is_vat'               =>  $is_vat,
                        'wht'                  =>  $less_wht,
                        'sales'                =>  $sales,
                        'vat_percentage'       =>  $vat_percentage,
                        'wht_percentage'       =>  $wht_percentage,
                        'vat_agreement'        =>  $vat_agreement,
                        'penalty_exempt'       =>  $penalty_exempt,
                        'bir_doc'              =>  $bir_doc,
                        'basic_rental'         =>  $basic_rental,
                        'tenancy_type'         =>  $tenancy_type,
                        'created_at'           =>  $this->_currentDate,
                        'flag'                 =>  'Posted',
                        'status'               =>  'Active'
                    );


                    $this->app_model->insert('tenants', $data);

                    $tenantCounter_id = $this->app_model->get_tenantCounterID($tenant_id);

                    for ($i=0; $i < count($_FILES["contract_docs"]['name']); $i++)
                    {
                        $targetPath  = getcwd() . '/assets/contract_docs/';
                        $tmpFilePath = $_FILES['contract_docs']['tmp_name'][$i];
                        //Make sure we have a filepath
                        if ($tmpFilePath != "")
                        {
                            //Setup our new file path
                            $filename    = $tenant_id . $timeStamp . $_FILES['contract_docs']['name'][$i];
                            $newFilePath = $targetPath . $filename;
                            //Upload the file into the temp dir
                            move_uploaded_file($tmpFilePath, $newFilePath);

                            $data = array('tenant_id' => $tenant_id, 'file_name' => $filename, 'flag' => $tenancy_type);
                            $this->app_model->insert('contract_docs', $data);
                        }
                    }



                    if ($sdiscount > 0)
                    {
                        for ($i=0; $i < count($sdiscount); $i++)
                        {
                            $discountData = array('tenant_id' => $tenantCounter_id, 'discount_id' => $sdiscount[$i]);
                            $this->app_model->insert('selected_discount', $discountData);
                        }
                    }


                    if ($monthly_charges > 0)
                    {
                        for ($i=0; $i < count($monthly_charges); $i++)
                        {
                            $charges          = explode("_", $monthly_charges[$i]);
                            $charges_id       = $charges[0];
                            $unit_price       = str_replace(",", "",  $charges[1]);
                            $charges_uom = $charges[2];
                            $selected_charges = array('tenant_id' => $tenant_id, 'monthly_chargers_id' => $charges_id, 'unit_price' => $unit_price, 'uom' => $charges_uom,  'flag' => 'Active');
                            $this->app_model->insert('selected_monthly_charges', $selected_charges);
                        }
                    }


                    // Update Terminated contract status = 'Renewed'

                    $update_status = array('status'  =>  'Renewed');
                    $this->app_model->update_where($update_status, 'tenant_id', $tenant_incrementID, 'terminated_contract');


                    $this->db->trans_complete(); // End of transaction function

                    if ($this->db->trans_status() === FALSE) // Check if the function is failed or succeed
                    {
                        $this->db->trans_rollback(); // If failed rollback all queries
                        $error = array('action' => 'Saving Long Term Contract', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                        $this->app_model->insert('error_log', $error);
                    }
                    else
                    {
                        $this->db->query("UPDATE `prospect` SET `status` = 'On Contract' WHERE `id` = '$prospect_id'");
                        $response['msg'] = "Success";
                        $response['tenancy_type'] = $tenancy_type;
                    }
                }

            }

            echo json_encode($response);

        }
    }


    public function get_paymentDocs()
    {
        if ($this->session->userdata('leasing_logged_in'))
        {
            $receipt_no = str_replace("%20", " ", $this->uri->segment(3));
            $result     = $this->app_model->get_paymentDocs($receipt_no);

            echo json_encode($result);
        }
    }


    function check_strLength($str, $length)
    {
        if(strlen($str) > $length)
        {
            $str = substr($str, 0, $length) . '...';
        }

        return $str;
    }

    // public function transafer_paymentDocs()
    // {
    //     $result = $this->app_model->get_forTransfer();

    //     foreach ($result as $value)
    //     {
    //         $data = array(
    //             'tenant_id'     =>  $value['tenant_id'],
    //             'receipt_no'    =>  $value['receipt_no'],
    //             'file_name'     =>  $value['supp_doc']
    //         );

    //         $this->app_model->insert('payment_supportingdocs', $data);
    //     }
    // }






    public function get_invoice_no(){
        var_dump($this->app_model->get_docNo(false));
    }
    public function get_soa_no(){
        var_dump($this->app_model->get_soaNo(false));
    }
    public function generate_refNo(){
        var_dump($this->app_model->generate_refNo(false));
    }

    public function gl_refNo(){
        var_dump($this->app_model->gl_refNo(false));
    }

    public function get_uft_no(){
        var_dump($this->app_model->generate_UTFTransactionNo(false));
    }

    public function get_closing_ref_no(){
        var_dump($this->app_model->generate_ClosingRefNo(false));
    }

    public function test2(){

        $sequence = getSequenceNo2(
            [   
                'table'         => 'sequence_glref',
                'code'          => "GL",
                'number'        => '26749',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "General Ledger Reference No. Sequence"
            ],
            [
                'table' =>  'general_ledger',
                'column' => 'ref_no'
            ],
            false
        );

        $uft = $this->app_model->gl_refNo(false);

        var_dump($sequence, $uft);

    }
}

/* End of file Leasing_transaction.php */
/* Location: ./application/controllers/Leasing_transaction.php */
