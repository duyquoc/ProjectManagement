<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * *********************************************************************************
 * Copyright: gitbench 2014
 * Licence: Please check CodeCanyon.net for licence details. 
 * More licence clarification available here: htttp://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
 * CodeCanyon User: http://codecanyon.net/user/gitbench
 * CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
 * Package Date: 2014-09-24 09:33:11 
 * **********************************************************************************
 */

class Bugs extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->module('layouts');
        $this->load->library(array('tank_auth', 'template', 'form_validation'));
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        $this->user = $this->tank_auth->get_user_id();
        $this->username = $this->tank_auth->get_username(); // Set username
        $this->user_role = Applib::login_info($this->user)->role_id;
        if (!$this->user) {
            $this->applib->redirect_to('login', 'error', lang('access_denied'));
        }
        $this->template->title(lang('projects') . ' - ' . config_item('company_name'));
        $this->page = lang('projects');

       
    }

    function add() {
        if ($this->input->post()) {

            if ($this->user_role != '1') {
                $_POST['reporter'] = $this->user;
            }
            if ($this->user_role != '1') {
                $_POST['assigned_to'] = '-';
            }
            $_POST['last_modified'] = date("Y-m-d H:i:s");

            $bug_id = Applib::create(Applib::$bugs_table, $_POST);
            // log activity
            $this->_log_activity('activity_issue_added', $this->user, 'bugs', $bug_id, 'fa-bug', $this->input->post('issue_title'), '');

            if (config_item('notify_bug_assignment') == 'TRUE' AND $this->user_role == '1') {
                $this->_notify_assigned_bug($bug_id);
            }

            $this->_reported_notification($bug_id);

            $this->applib->redirect_to('projects/view/' . $_POST['project'] . '/?group=bugs', 'success', lang('bug_assigned_successfully'));
        } else {
            $data['role'] = $this->user_role;
            $data['project'] = $this->uri->segment(4);
            $this->load->view('modal/add_bug', isset($data) ? $data : NULL);
        }
    }

    function _notify_assigned_bug($bug) {

        $message = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_assigned'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_assigned'), 'subject');

        $assigned_user = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'assigned_to');
        $email = Applib::login_info($assigned_user)->email;

        $issue_title = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'issue_title');
        $issue_project = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'project');

        $project_title = Applib::get_table_field(Applib::$projects_table, 
                            array('project_id' => $issue_project), 'project_title');


        $issue_title = str_replace("{ISSUE_TITLE}", $issue_title, $message);
        $assigned_by = str_replace("{ASSIGNED_BY}", ucfirst($this->username), $issue_title);
        $issue_project_title = str_replace("{PROJECT_TITLE}", $project_title, $assigned_by);
        $site_url = str_replace("{SITE_URL}", base_url(), $issue_project_title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $site_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);


        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function _reported_notification($bug) {
        $issue_title = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'issue_title');
        $project = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'project');


        $message = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_reported'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_reported'), 'subject');

        $title = str_replace("{ISSUE_TITLE}", $issue_title, $message);
        $added_by = str_replace("{ADDED_BY}", $this->username, $title);
        $project_url = str_replace("{BUG_URL}", base_url() . 'projects/view/' . $project . '?group=bugs&view=bug&id=' . $bug, $added_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $project_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $assigned_to = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'assigned_to');

        $params['recipient'] = Applib::login_info($assigned_to)->email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function edit() {
        if ($this->input->post()) {

            $_POST['last_modified'] = date("Y-m-d H:i:s");

            Applib::update(Applib::$bugs_table,array('bug_id' => $_POST['bug_id']),$this->input->post());
             // log activity


            $this->_log_activity('activity_issue_edited', $this->user, 'bugs', $_POST['bug_id'], 'fa-edit', $this->input->post('issue_title'), '');

            $this->applib->redirect_to('projects/view/' . $_POST['project'] . '/?group=bugs&view=bug&id=' . $_POST['bug_id'], 'success', lang('bug_assigned_successfully'));
        } else {
            $data['role'] = $this->user_role;
            $bug_id = isset($_GET['id']) ? $_GET['id'] : '';
            $data['project'] = $this->uri->segment(4);
            $data['bug'] = $bug_id;
            $data['bug_info'] =  Applib::retrieve(Applib::$bugs_table,array('bug_id' => $bug_id));

            $this->load->view('modal/edit_bug', isset($data) ? $data : NULL);
        }
    }

    function file() {
        $action = $this->uri->segment(4);
        if ($this->input->post()) {
            Applib::is_demo();

                if ($action == 'edit') {
                    $project = $this->input->post('project', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $bug = $this->input->post('bug', TRUE);

                    $data = array(
                        "title" => $title,
                        "description" => $this->input->post('description'),
                    );

                    $this->db->where('file_id', $file_id)->update(Applib::$bug_files_table, $data);

                    $this->_log_activity('activity_edited_file_bug', $this->user, 'bugs', $bug, 'fa-file', $this->input->post('issue_title'), '');


                    $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs&view=bug&id=' . $bug,'success',lang('file_edited_successfully'));
                    
                } elseif ($action == 'delete') {
                    $this->load->helper("file");
                    $project = $this->input->post('project', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $bug = $this->input->post('bug', TRUE);
                    $file_name = Applib::get_table_field(Applib::$bug_files_table,array('file_id' => $file_id),'file_name');

                    
                    $this->db->delete(Applib::$bug_files_table, array('file_id' => $file_id));

                    if(file_exists('./resource/bug-files/'.$file_name)){
                        unlink('./resource/bug-files/'.$file_name);
                    }

                     $this->_log_activity('activity_deleted_file_bug', $this->user, 'bugs', $bug, 'fa-trash', $file_name, '');


                    $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs&view=bug&id=' . $bug,'success', lang('file_deleted'));
                } else {
                    $project = $this->input->post('project', TRUE);
                    $bug = $this->input->post('bug', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $description = $this->input->post('description', TRUE);

                    $config['upload_path'] = './resource/bug-files/';
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if (!$this->upload->do_multi_upload("bugfiles")) {
                        $this->applib->redirect_to('projects/view/' . $project . '?group=bugs', 'error', lang('operation_failed'));
                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach ($fileinfs as $findex => $fileinf) {
                            $data = array(
                                'bug' => $bug,
                                'title' => $title,
                                'description' => $description,
                                'file_name' => $fileinf['file_name'],
                                'file_ext' => $fileinf['file_ext'],
                                'size' => $fileinf['file_size'],
                                'is_image' => $fileinf['is_image'],
                                'image_width' => $fileinf['image_width'],
                                'image_height' => $fileinf['image_height'],
                                'original_name' => $fileinf['client_name'],
                                'uploaded_by' => $this->user,
                            );
                            $file_id = Applib::create(Applib::$bug_files_table,$data);
                        }

                        $this->_log_activity('activity_uploaded_file_bug', $this->user, 'BUGS', $project, 'fa-file', $title, '');

                        $this->_upload_notification($bug);
                        $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs&view=bug&id=' . $bug, 'success', lang('file_uploaded_successfully'));
                    }
                }
            
        } else {
            if ($action == 'edit') {
                $data['bug'] = Applib::retrieve(Applib::$bug_files_table,array('file_id' => $this->uri->segment(5)));
                $data['bug'] = $data['bug'][0]->bug;
                $data['file_id'] = $this->uri->segment(5);
                $data['project'] = $this->uri->segment(6);
                $data['file_details'] = Applib::retrieve(Applib::$bug_files_table,
                                        array('file_id' => $this->uri->segment(5)));
                $this->load->view('modal/bug_edit_file', isset($data) ? $data : NULL);
                return;
            }
            if ($action == 'delete') {
                $data['bug'] = Applib::retrieve(Applib::$bug_files_table,
                                array('file_id' => $this->uri->segment(5)));
                $data['bug'] = $data['bug'][0]->bug;
                $data['file_id'] = $this->uri->segment(5);
                $data['project'] = $this->uri->segment(6);
                $this->load->view('modal/bug_delete_file', isset($data) ? $data : NULL);
                return;
            }
            $data['project'] = $this->uri->segment(4);
            $data['bug'] = $this->uri->segment(5);
            $this->load->view('modal/add_bug_file', isset($data) ? $data : NULL);
        }
    }

    function _upload_notification($bug) {
        $issue_title = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'issue_title');
        $project = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'project');


        $message = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_file'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_file'), 'subject');

        $uploaded_by = str_replace("{UPLOADED_BY}", $this->username, $message);
        $title = str_replace("{ISSUE_TITLE}", $issue_title, $uploaded_by);
        $project_url = str_replace("{BUG_URL}", base_url() . 'projects/view/' . $project . '?group=bugs&view=bug&id=' . $bug, $title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $project_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $assigned_to = Applib::get_table_field('bugs', array('bug_id' => $bug), 'assigned_to');

        $params['recipient'] = Applib::login_info($assigned_to)->email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function status() {
        $status = isset($_GET['s']) ? $_GET['s'] : '';
        $bug = isset($_GET['id']) ? $_GET['id'] : '';
        $project = $this->uri->segment(4);

        switch ($status) {
            case 'confirmed':
                $this->db->set('bug_status', 'Confirmed')->where('bug_id', $bug)->update(Applib::$bugs_table);
                break;
            case 'in_progress':
                $this->db->set('bug_status', 'In Progress')->where('bug_id', $bug)->update(Applib::$bugs_table);
                break;
            case 'resolved':
                $this->db->set('bug_status', 'Resolved')->where('bug_id', $bug)->update(Applib::$bugs_table);
                break;
            case 'verified':
                $this->db->set('bug_status', 'Verified')->where('bug_id', $bug)->update(Applib::$bugs_table);
                break;
            default:
                $this->db->set('bug_status', 'Unconfirmed')->where('bug_id', $bug)->update(Applib::$bugs_table);
                break;
        }
        if (config_item('notify_bug_status') == 'TRUE') {
            $this->_notify_bug_status_change($bug, $project, $status);
        }

        $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs', 'success', lang('bug_status_changed'));
    }

    function _notify_bug_status_change($bug, $project, $status) {

        $message = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_status'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_status'), 'subject');

        $assigned_user = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'assigned_to');
        $email = Applib::login_info($assigned_user)->email;

        $issue_title = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'issue_title');


        $email_issue_title = str_replace("{ISSUE_TITLE}", $issue_title, $message);
        $assigned_by = str_replace("{STATUS}", strtoupper($status), $email_issue_title);
        $marker_by = str_replace("{MARKED_BY}", $this->username, $assigned_by);
        $bug_url = str_replace("{BUG_URL}", base_url() . 'projects/view/' . $project . '?group=bugs&view=bug&id=' . $bug, $marker_by);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $bug_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);


        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function preview(){
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);
            $file =  $this->db->select()
                ->from(Applib::$bug_files_table)
                ->where('file_id', $file_id)
                ->get()
                ->row();
            if ($file)
            {
                if(file_exists('./resource/bug-files/'.$file->file_name)){
                    $data['file'] = $file;
                    $data['project_id'] = $project_id;
                    $this->load->view('modal/preview_bug_file', $data);
                }else{
                    $this->session->set_flashdata('message',$this->lang->line('operation_failed'));
                    redirect('projects/view/'.$project_id);
                }
            }
            else
            {
                $this->session->set_flashdata('message',$this->lang->line('operation_failed'));
                redirect('projects/view/'.$project_id);
            }
        }
    }

    function comment() {
        if ($this->input->post()) {
            $_POST['comment_by'] = $this->user;
            $project = $_POST['project'];
            unset($_POST['project']);

            $comment_id = Applib::create(Applib::$bug_comments_table, $_POST);

            $bug = $_POST['bug_id'];

            $title = Applib::get_table_field(Applib::$bugs_table, 
                                    array('bug_id' => $bug), 'issue_title');

            // Send email to client and assigned user
            if (config_item('notify_bug_comments') == 'TRUE') {

                $bug_assigned_to = Applib::get_table_field(Applib::$bugs_table, 
                                    array('bug_id' => $bug), 'assigned_to');

                $bug_reporter = Applib::get_table_field(Applib::$bugs_table, 
                                    array('bug_id' => $bug), 'reporter');

                if ($this->user == $bug_assigned_to) {
                    $this->_notify_bug_comment($bug, $project, 'reporter');
                } else {
                    $this->_notify_bug_comment($bug, $project, 'staff');
                }
            }


            $this->_log_activity('bug_comment_add', $this->user, 'bugs', $bug, 'fa-comment', $title, '');

            $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs&view=bug&id=' . $_POST['bug_id'], 'success', lang('activity_bug_comment_add'));
        } 
    }


    function delete_comment(){
        if($this->input->post()){
            $comment_by = Applib::get_table_field(Applib::$bug_comments_table,
                                array('c_id' => $this->input->post('bug_id')),'comment_by');
            if($this->user == $comment_by){
                Applib::delete(Applib::$bug_comments_table,array('c_id' => $this->input->post('bug_id')));
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('comment_deleted'));
            }

            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $id = $this->uri->segment(4);
            $data['details'] = Applib::retrieve(Applib::$bug_comments_table,array('c_id' => $id));
            $this->load->view('modal/delete_bug_comment',isset($data) ? $data : NULL);
        }
    }

    function _notify_bug_comment($bug, $project, $notify) {

        $message = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_comment'), 'template_body');

        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                    array('email_group' => 'bug_comment'), 'subject');

        if ($notify == 'reporter') {
            $reporter = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'reporter');
            $email = Applib::login_info($reporter)->email;
        } else {
            $assigned_user = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'assigned_to');
            $email = Applib::login_info($assigned_user)->email;
        }

        $issue_title = Applib::get_table_field(Applib::$bugs_table, array('bug_id' => $bug), 'issue_title');


        $posted_by = str_replace("{POSTED_BY}", $this->username, $message);
        $email_issue_title = str_replace("{ISSUE_TITLE}", $issue_title, $posted_by);
        $comment_url = str_replace("{COMMENT_URL}", base_url() . 'projects/view/' . $project . '?group=bugs&view=bug&id=' . $bug, $email_issue_title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $comment_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);


        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email', $params);
    }

    function download() {
        $this->load->helper('download');
        $project = $this->uri->segment(4);
        $file_id = $this->uri->segment(5);
        $file_name = Applib::get_table_field(Applib::$bug_files_table, array('file_id' => $file_id), 'file_name');
        if (file_exists('./resource/bug-files/' . $file_name)) {
            $data = file_get_contents('./resource/bug-files/' . $file_name); // Read the file's contents
            force_download($file_name, $data);
        } else {
            $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs', 'error', lang('operation_failed'));
        }
    }

    function delete() {
        if ($this->input->post()) {
            $project = $this->input->post('project');
            $bug_id = $_POST['bug_id'];
            $issue_title = Applib::get_table_field(Applib::$bugs_table,array('bug_id' => $bug_id),'issue_title');

            $bug_files = Applib::retrieve(Applib::$bug_files_table,array('bug' => $bug_id));

            foreach ($bug_files as $file) {
                if(file_exists('./resource/bug-files/'.$file->file_name)){
                     unlink('./resource/bug-files/'.$file->file_name);
                 }
            }

            Applib::delete(Applib::$bug_comments_table,array('bug_id' => $bug_id));
            Applib::delete(Applib::$bug_files_table,array('bug' => $bug_id));
            Applib::delete(Applib::$bugs_table,array('bug_id' => $bug_id));

            $this->_log_activity('activity_bug_delete', $this->user, 'bugs', $bug_id, 'fa-bug', $issue_title, '');

            $this->applib->redirect_to('projects/view/' . $project . '/?group=bugs', 'success', lang('issue_deleted_successfully'));
        } else {
            $bug_id = isset($_GET['id']) ? $_GET['id'] : '';

            $data['project'] = $this->uri->segment(4);
            $data['bug_id'] = $bug_id;
            $this->load->view('modal/delete_bug', $data);
        }
    }

    function _log_activity($activity, $user, $module, $module_field_id, $icon, $value1 = '', $value2 = '') {

        $params = array(
            'user'              => $user,
            'module'            => $module,
            'module_field_id'   => $module_field_id,
            'activity'          => $activity,
            'icon'              => $icon,
            'value1'            => $value1,
            'value2'            => $value2
        );
        Applib::create(Applib::$activities_table,$params);
    }

}

/* End of file projects.php */