<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*  
*   @author : William M
*   Freelancer Office
*   http://codecanyon.net/user/gitbench
*/


class Installer extends MX_Controller {


    function __construct()
    {
        parent::__construct();  
        $this->load->helper(array('url','file','curl'));
    }
    
    function index()
    {
        
            $this->load->view('install');
        
    }

    function _installed(){
            $this->_enable_system_access();
            $this->_change_routing();
            redirect();
    }

    function start(){
        $this->session->sess_destroy();
        redirect('installer/?step=2','refresh');
    }

    function db_setup(){

        $db_connect = $this->verify_db_connection();

        if($db_connect){

            $create_config = $this->_create_db_config();

            $this->_step_complete('database_setting','3');

            redirect('installer/?step=3');
        }else {
            $this->session->set_flashdata('message','Database connection failed please try again.');
            redirect('installer/?step=2');
        }
    }

    function verify(){
        $p = array();
        $env_data = $this->_valid_script();
        $p = json_decode($env_data,true);

        if(json_decode($env_data) == NULL){
            $this->session->set_flashdata('message','Your purchase code is Invalid. Please try again');
            redirect('installer/?step=3');
        }else{
            if($p['buyer'] != $this->input->post('set_envato_user')){
                $this->session->set_flashdata('message','Your envato username does not match the buyer username');
                redirect('installer/?step=3');
            }
            $this->session->set_userdata('purchase_code', $this->input->post('set_envato_license'));

            if(!$this->_get_db(config_item('version'))){
                 $this->session->set_flashdata('message','Server at http://gitbench.com is unreachable');
                    redirect('installer/?step=3');
            }
            if(!$this->_initialize_db(config_item('version'))){
                 $this->session->set_flashdata('message','Database import failed. Check the resource/tmp/install.sql');
                    redirect('installer/?step=3');
                }
        }

        $this->_step_complete('verify_purchase','4');

        redirect('installer/?step=4');
    }

    function complete(){

        $this->_enable_system_access();

        $this->_create_admin_account();

        $this->_change_routing();

        $this->_change_htaccess();

        $this->session->sess_destroy();

        if(is_file('./resource/tmp/install.sql')){
            unlink('./resource/tmp/install.sql');
        }
        redirect('');
    }

    function _step_complete($setting,$next_step){
            $formdata = array(
                $setting  => 'complete',
                'next_step'     =>  $next_step,
            );
        return $this->session->set_userdata($formdata);
    }

    function _create_db_config(){
        // Replace the database settings
            $dbdata = read_file('./application/config/database.php');
            $dbdata = str_replace('db_name', $this->input->post('set_database'), $dbdata);
            $dbdata = str_replace('db_user', $this->input->post('set_db_user'), $dbdata);
            $dbdata = str_replace('db_pass', $this->input->post('set_db_pass'), $dbdata);                     
            $dbdata = str_replace('db_host', $this->input->post('set_hostname'), $dbdata);
            write_file('./application/config/database.php', $dbdata);
    }

    function _valid_script(){
        $purchase_code = $this->input->post('set_envato_license');
        return remote_get_contents(UPDATE_URL.'verify.php?code='.$purchase_code);
    }

    function _get_db($version = NULL){
        if($version){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,UPDATE_URL.'folite/db/install.sql');

        $fp = fopen('./resource/tmp/install.sql', 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_exec ($ch);

        curl_close ($ch);
        fclose($fp);
        return TRUE;
            
        }
        return FALSE;
    }

    function _create_admin_account(){
        $this->load->library('tank_auth');
        $this->db->truncate(Applib::$user_table); 
        $this->db->truncate(Applib::$profile_table); 
        $this->db->where('config_key','webmaster_email')->delete(Applib::$config_table);


        // Prepare system settings
        $username = $this->input->post('set_admin_username');
        $email = $this->input->post('set_admin_email');
        $password = $this->input->post('set_admin_pass');
        $fullname = $this->input->post('set_admin_fullname');
        $company = $this->input->post('set_company_name');
        $company_email = $this->input->post('set_company_email');
        $email_activation = FALSE;
        $base_url = $this->input->post('set_base_url');
        $purchase_code = $this->session->userdata('purchase_code');

        $codata = array('value' => $company);
        $this->db->where('config_key','company_name')->update(Applib::$config_table,$codata);

        $codata = array('value' => $purchase_code);
        $this->db->where('config_key','purchase_code')->update(Applib::$config_table,$codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key','smtp_user')->update(Applib::$config_table,$codata);

        $codata = array('value' => $company);
        $this->db->where('config_key','website_name')->update(Applib::$config_table,$codata);

        $codata = array('value' => 'TRUE');
        $this->db->where('config_key','valid_license')->update(Applib::$config_table,$codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key','company_email')->update(Applib::$config_table,$codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key','paypal_email')->update(Applib::$config_table,$codata);

        $codata = array('value' => $base_url);
        $this->db->where('config_key','company_domain')->update(Applib::$config_table,$codata);

        return $this->tank_auth->create_user(
            $username, 
            $email, 
            $password, 
            $fullname, 
            '-', 
            '1', 
            '', 
            $email_activation
        );
    }

    function _initialize_db($version = NULL){
         // Run the installer sql schema 
         $this->load->database();   

         $templine = '';
        // Read in entire file
            $lines = file('./resource/tmp/install.sql');
                foreach ($lines as $line)
                    {
                     if (substr($line, 0, 2) == '--' || $line == '')
                        continue;
                        $templine .= $line;
                        if (substr(trim($line), -1, 1) == ';')
                            {
                             $this->db->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                                        $templine = '';
                            }           
                            
                    }
                    return TRUE;
            
    }

    function _enable_system_access(){
        
            $confdata = read_file('./application/config/config.php');
            $confdata = str_replace(
                '$config[\'enable_hooks\'] = FALSE;', 
                '$config[\'enable_hooks\'] = TRUE;',
                $confdata);
            $confdata = str_replace(
                '$config[\'index_page\'] = \'index.php\';',
                '$config[\'index_page\'] = \'\';', 
                $confdata);
            $confdata = str_replace(
                '$config[\'sess_use_database\'] = FALSE;',
                '$config[\'sess_use_database\'] = TRUE;',
                $confdata);

            write_file('./application/config/config.php', $confdata);


            $libdata = read_file('./application/config/autoload.php');
            $libdata = str_replace(
                '$autoload[\'libraries\'] = array(\'session\');',
                '$autoload[\'libraries\'] = array(\'session\',\'database\',\'tank_auth\',\'user_profile\',\'applib\');',
                $libdata);
            write_file('./application/config/autoload.php', $libdata);

            
    }


    function _change_routing(){
         // Replace the default routing controller
        $rdata = read_file('./application/config/routes.php');
        $rdata = str_replace('installer','welcome',$rdata);
        write_file('./application/config/routes.php', $rdata);

        $data = 'Software installed';
        if (write_file('./resource/tmp/installed.txt', $data))
            {   
                return TRUE;
            }
    }

    function _change_htaccess(){
        

        $subfolder = str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']); 
            if(!empty($subfolder)){
                    
$input = '<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase '.$subfolder.'
RewriteCond %{REQUEST_URI} ^system.*
RewriteRule ^(.*)$ /index.php?/$1 [L]

RewriteCond %{REQUEST_URI} ^application.*
RewriteRule ^(.*)$ /index.php?/$1 [L]
                         
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L]
</IfModule>
                         
<IfModule !mod_rewrite.c>
ErrorDocument 404 /index.php
</IfModule>';

            $current = @file_put_contents('./.htaccess', $input);
        }
    }
    
    // -------------------------------------------------------------------------------------------------
    
    /* 
     * Database validation check from user input settings
     */
    function verify_db_connection()
    {
        $link   =   @mysqli_connect(
                        $this->input->post('set_hostname'),
                        $this->input->post('set_db_user'),
                        $this->input->post('set_db_pass'),
                        $this->input->post('set_database')
                    );
        if(!$link)
        {
            @mysqli_close($link);
            return false;
        }
        
        @mysqli_close($link);
        return true;
    }
    
}

/* End of file install.php */
/* Location: ./system/application/controllers/install.php */