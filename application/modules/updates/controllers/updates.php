<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Updates extends MX_Controller {

   
    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') {
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('logout');
        }
        $this->load->helper('curl');
        $this->load->helper('file');
        $this->clean_old_files();
    }

    function index() {
        $this->load->module('layouts');
        $this->load->library('template');

        $this->template->title(lang('updates') . ' - ' . config_item('company_name'));

        $data['page'] = lang('settings');


        $installed_version = config_item('version');
        $releases = json_decode(remote_get_contents(UPDATE_URL . 'version.php'), true);

        Applib::switchon();

        $data['latest_version'] = $releases['version'];
        $data['release_date'] = $releases['release_date'];
        $data['update_tips'] = $releases['update_tips'];

        $data['backups'] = get_filenames('./resource/backup/');
        $data['updates'] = $this->applib->get_updates();                
        $data['check'] = $this->db_check();
        $this->template
                ->set_layout('users')
                ->build('update', isset($data) ? $data : NULL);
    }

    function get_update($update = NULL) {

        if ($update) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, UPDATE_URL . 'files/' . $update);

            $fp = fopen('./resource/updates/' . $update, 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);

            curl_exec($ch);

            curl_close($ch);
            fclose($fp);
        }
        redirect('updates');
    }
    
    function download() {
        
        if (isset($_POST['build'])) { $build = $_POST['build']; } else { return FALSE; }
        $upd = $this->db->where('build',$build)->get('updates')->result();
        $update = $upd[0];
        
        set_time_limit(0);
        $fp = fopen('./resource/updates/' . $update->filename, 'w+');
        $url = UPDATE_URL."folite/".$update->filename;
        $ch = curl_init(str_replace(" ","%20",$url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $curldata = curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $data['json'] = $update;
        $this->load->view('json',isset($data) ? $data : NULL);
    }
    
    function view() {
            $build = $this->uri->segment(3);
            $update = $this->db->where('build',$build)->get('updates')->result();
            $data['update'] = $update[0];
            $this->load->view('modal/view_update',$data);
    }

    function backup() {
        $this->mysql_backup();
        if (!is_dir('./resource/backup/')) {
            Applib::make_flashdata(
                    array(
                        'response_status' => 'error',
                        'message' => 'Create a folder named backup in resource folder'
            ));
            redirect($_SERVER['HTTP_REFERER']);
        }
        if (!is_writeable("./resource/backup/")) {
            Applib::make_flashdata(
                    array(
                        'response_status' => 'error',
                        'message' => 'We are unable to write to resource/backup folder'
            ));
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->load->library('zip');
        $path = './';
        $this->zip->read_dir($path);
        $this->zip->archive('./resource/backup/freelancer_office_full_backup_' . date('Y-m-d') . '.zip');
        Applib::make_flashdata(
                array(
                    'response_status' => 'success',
                    'message' => 'Backup created and saved in resource/backup folder'
        ));
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    function check() {
        $this->applib->get_updates(TRUE);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function install() {
        
        if (isset($_POST['build'])) { $build = $_POST['build']; } else { return false; }
        $upd = $this->db->where('build',$build)->get('updates')->result();
        $upd = $upd[0];
        
        $zip = new ZipArchive;
        if ($zip->open('./resource/updates/'.$upd->filename) === TRUE) {
            $zip->extractTo('./');
            $zip->close();
        }
        
        $this->db->where('build',$build)->update('updates',array('installed' => 1))->result();
        $this->applib->get_updates();
        
        $data['json'] = array();
        $this->load->view('json',isset($data) ? $data : NULL);

    }

    function install2() {
        $releases = json_decode(remote_get_contents(UPDATE_URL . 'version.php'), true);

        $latest_version = $releases['version'];
        $zip = new ZipArchive;
        if ($zip->open('./resource/updates/' . $latest_version . '.zip') === TRUE) {
            $zip->extractTo('./');
            $zip->close();
            // perform db changes
            $this->migrate_db($latest_version);
            $response = 'success';
            $message = 'Software updated successfully.';
        } else {
            $response = 'error';
            $message = 'Please click on Download Updates to continue.';
        }

        Applib::make_flashdata(
                array(
                    'response_status' => $response,
                    'message' => $message
        ));
        redirect($_SERVER['HTTP_REFERER']);
    }

    function db_upgrade() {
        $this->load->dbforge();
        $this->load->database();
        
        $lines = file(UPDATE_URL.'folite/db/upgrade.sql', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $templine = "";
        foreach ($lines as $line)
        {
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $this->db->query($templine);
                $tmp[] = $templine;
                $templine = '';
            }           
        }
        $data['json'] = array();
        $this->load->view('json',isset($data) ? $data : NULL);
    }

    function db_update() {
        $this->db_sync();
        redirect($_SERVER['HTTP_REFERER']);
    }

    function migrate_db($version = NULL) {
        $this->load->dbforge();
        $this->load->database();
        $version = ($version == NULL) ? config_item('version') : $version;
        $this->db_sync();

        $file_content = remote_get_contents(UPDATE_URL.'folite/db/upgrade.sql');
        $this->db->query('USE ' . $this->db->database . ';');
        foreach (explode(";\n", $file_content) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $this->db->query($sql);
            }
        }
        return TRUE;
    }

    function db_json($settings = array()) {
        $this->load->database();
        $this->load->helper('file');
        $url = './resource/db.json';
        $db = array(
            "schema" => array(),
            "data" => array()
        );

        $tables = $this->db->query('SHOW TABLES')->result_array();
        foreach ($tables as $table) {
            foreach ($table as $k => $name) {
                $db['schema'][$name] = array();
                $columns = $this->db->query('SHOW FULL COLUMNS FROM `' . $name . '`')->result_array();
                foreach ($columns as $col) {
                    $rows = $this->db->query('SELECT * FROM `' . $name . '`')->result_array();
                    $db['data'][$name] = $rows;
                    $db['schema'][$name][$col['Field']] = $col;
                }
            }
        }
        if (isset($settings['return'])) {
            return $db;
        }
        if (isset($settings['url'])) {
            $url = $settings['url'];
        }
        $data = json_encode($db, JSON_UNESCAPED_UNICODE);
        write_file($url, $data);
    }

    function db_sync() {
        $this->load->database();
        $this->load->helper('file');
        $src = remote_get_contents(UPDATE_URL."folite/db/db.json");
        $src = json_decode($src, TRUE);
        $db = array();
        $log = array();

        // Get local tables
        $t = $this->db->query('SHOW TABLES')->result_array();
        foreach ($t as $tab) {
            foreach ($tab as $k => $name) {
                $db[$name] = array();
            }
        }
        
        // Insert missing tables
        foreach ($src['schema'] as $table => $cols) {
            if (!isset($db[$table])) {
                $query = "CREATE TABLE IF NOT EXISTS `".$table."` (";
                foreach ($cols as $col => $attr) {
                    $query .= "`".$col."`";
                    $query .= " " . $attr['Type'];
                    $query .= " " . ($attr['Null'] == "YES" ? " NULL" : " NOT NULL");
                    if ($attr['Collation'] != NULL) { $query .= " COLLATE " . $attr['Collation']; }
                    if ($attr['Default'] != NULL) { $query .= " DEFAULT '" . $attr['Default'] . "'"; 
                    } else { if ($attr['Null'] == "YES") { $query .= " DEFAULT NULL"; } }
                    $query .= ",";
                }
                $query = rtrim($query, ",");
                $query .= ");";
                $this->db->query($query);
            }
        }
        foreach ($db as $name => $content) {
            if (isset($src['schema'][$name])) {
                $columns = $this->db->query('SHOW FULL COLUMNS FROM `' . $name . '`')->result_array();
                foreach ($columns as $col) {
                    // Delete obsolete columns
                    if (!isset($src['schema'][$name][$col['Field']])) {
                        $this->db->query('ALTER TABLE `' . $name . '` DROP COLUMN `' . $col['Field'] . '`');
                    } else {
                        $db[$name][$col['Field']] = $col;
                    }
                }
                $previous = '';
                foreach ($src['schema'][$name] as $field => $info) {
                    
                    // Insert missing columns and synchronize existing ones
                    if (!isset($db[$name][$field])) {
                        $q = 'ALTER TABLE `' . $name . '` ADD COLUMN `' . $field . '`';
                        $q .= ' ' . $info['Type'];
                        $q .= ' ' . ($info['Null'] == 'YES' ? ' NULL' : ' NOT NULL');
                        if ($info['Collation'] != NULL) { $q .= ' COLLATE ' . $info['Collation']; }
                        if ($info['Default'] != NULL) { $q .= " DEFAULT '" . $info['Default'] . "'"; 
                        } else { if ($info['Null'] == 'YES') { $q .= " DEFAULT NULL"; } }
                        if ($previous == '') { $q .= " FIRST"; } else { $q .= " AFTER `" . $previous . "`"; }
                        $this->db->query($q);

                        if ($info['Key'] == 'PRI') {
                            $this->db->query("ALTER TABLE `" . $name . "` ADD KEY `Index 1` (`" . $field . "`);");
                        }
                        if ($info['Extra'] == 'auto_increment') {
                            $this->db->query("ALTER TABLE `" . $name . "` MODIFY `" . $field . "` " . $info['Type'] . " NOT NULL AUTO_INCREMENT");
                        }
                        $log['column']['added'][] = array("table" => $name, "column" => $field);
                    } else {
                        if (($info['Type'] != $db[$name][$field]['Type'] ||
                            $info['Null'] != $db[$name][$field]['Null'] || 
                            $info['Default'] != $db[$name][$field]['Default']) && 
                            $info['Key'] != 'PRI') {
                            $q = 'ALTER TABLE `' . $name . '` MODIFY COLUMN `' . $field . '`';
                            $q .= ' ' . $info['Type'];
                            $q .= ' ' . ($info['Null'] == 'YES' ? ' NULL' : ' NOT NULL');
                            if ($info['Default'] != NULL) { $q .= " DEFAULT '" . $info['Default'] . "'"; 
                            } else { if ($info['Null'] == 'YES') { $q .= " DEFAULT NULL"; } }
                            $this->db->query($q);
                            $log['column']['modified'][] = array("table" => $name, "column" => $field);
                        }
                    }
                    $previous = $field;
                }
            }
        }
        
        $data['json'] = $log;
        $this->load->view('json',isset($data) ? $data : NULL);
    }
    
    function db_check() {
        $this->load->database();
        $this->load->helper('file');
        $src = remote_get_contents(UPDATE_URL."folite/db/db.json");
        $src = json_decode($src, TRUE);
        $db = array();
        $log = array();

        // Get local tables
        $t = $this->db->query('SHOW TABLES')->result_array();
        foreach ($t as $tab) {
            foreach ($tab as $k => $name) {
                $db[$name] = array();
            }
        }
        
        // Insert missing tables
        foreach ($src['schema'] as $table => $cols) {
            if (!isset($db[$table])) {
                $log['table']['added'][] = $table;
            }
        }
        foreach ($db as $name => $content) {
            if (isset($src['schema'][$name])) {
                $columns = $this->db->query('SHOW FULL COLUMNS FROM `' . $name . '`')->result_array();
                foreach ($columns as $col) {
                    // Delete obsolete columns
                    if (!isset($src['schema'][$name][$col['Field']])) {
                        $log['column']['removed'][] = array("column" => $col['Field'], "table" => $name);
                    } else {
                        $db[$name][$col['Field']] = $col;
                    }
                }
                foreach ($src['schema'][$name] as $field => $info) {
                    
                    // Insert missing columns and synchronize existing ones
                    if (!isset($db[$name][$field])) {
                        $log['column']['added'][] = array("table" => $name, "column" => $field);
                    } else {
                        if (($info['Type'] != $db[$name][$field]['Type'] ||
                            $info['Null'] != $db[$name][$field]['Null'] || 
                            $info['Default'] != $db[$name][$field]['Default']) && 
                            $info['Key'] != 'PRI') {
                            $log['column']['modified'][] = array("table" => $name, "column" => $field);
                        }
                    }
                }
            }
        }
        
        return $log;
    }
    
    function mysql_backup() {
        $this->load->dbutil();
        $prefs = array('format' => 'zip', 'filename' => 'database-full-backup_' . date('Y-m-d'));

        $backup = & $this->dbutil->backup($prefs);

        if (!write_file('./resource/backup/database-full-backup_' . date('Y-m-d') . '.zip', $backup)) {
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', 'Database backup failed cannot write to /resource/database.backup folder.');
            redirect('updates');
        }
        $this->db_json(array("data" => TRUE, "url" => './resource/backup/database-full-backup_' . date('Y-m-d') . '.json'));
        return true;
    }

    public function clean_old_files() {
        if (is_dir('./UPDATES/')) {
            if (!rmdir('./UPDATES'))
                rename('./UPDATES', './delete_this');
        }
    }

}

/* End of file updater.php */