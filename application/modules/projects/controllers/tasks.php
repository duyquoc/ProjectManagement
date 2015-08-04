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

class Tasks extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->module('layouts');
        $this->load->library(array('tank_auth', 'template', 'form_validation'));
        $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');

        $this->user = $this->tank_auth->get_user_id();
        $this->username = $this->tank_auth->get_username(); // Set username
        if (!$this->user) {
            $this->applib->redirect_to('auth/login', 'error', lang('access_denied'));
        }
        $this->user_role = Applib::login_info($this->user)->role_id;

        $this->template->title(lang('projects') . ' - ' . config_item('company_name') . ' ' . config_item('version'));
        $this->page = lang('projects');
    }

    function edit() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);
            $task_id = $this->input->post('task_id', TRUE);
            if ($this->form_validation->run('projects', 'add_task') == FALSE) {
                 Applib::make_flashdata(array(
                        'form_error'=> validation_errors()
                        ));
                $this->applib->redirect_to('projects/view/' . $project . '?group=tasks&view=task&id='.$task_id, 'error', lang('task_update_failed'));
            } else {
                $form_data = array(
                    'task_name' => $this->input->post('task_name'),
                    'project' => $this->input->post('project'),
                    'due_date' => date_format(date_create_from_format(config_item('date_php_format'), $this->input->post('due_date')), 'Y-m-d'),
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                );
                if ($this->user_role != '2') {
                    if ($this->input->post('visible') == 'on') {
                        $visible = 'Yes';
                    } else {
                        $visible = 'No';
                    }
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                    $form_data['task_progress'] = $this->input->post('progress');

                    if ($this->user_role == '1') {
                        $assign = $this->input->post('assigned_to');
                        Applib::delete(Applib::$assign_tasks_table,array('task_assigned' => $task_id));

                        foreach ($assign as $key => $value) {
                            $dt = array(
                                'assigned_user' => $value,
                                'project_assigned' => $project,
                                'task_assigned' => $task_id
                                );
                            Applib::create(Applib::$assign_tasks_table,$dt);
                        }
                        $assigned_to = serialize($this->input->post('assigned_to'));
                        $form_data['assigned_to'] = $assigned_to;
                    }
                }
                Applib::update(Applib::$tasks_table,array('t_id' => $task_id),$form_data);

                if (isset($assigned_to)) {
                    if (config_item('notify_task_assignments') == 'TRUE') {
                        // Send email notification
                        $this->_task_changed_notification($project, $this->input->post('task_name'), $assigned_to);
                    }
                }
                $this->_log_activity($project, 'activity_edited_a_task', $icon = 'fa-tasks', $this->input->post('task_name')); //log activity

                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('task_update_success'));
                redirect('projects/view/' . $project . '?group=tasks&view=task&id=' . $task_id);
            }
        } else {
            $task = $this->uri->segment(4);
            $data['role'] = $this->user_role;
            $data['fuelux'] = TRUE;
            $data['project'] = Applib::get_table_field(Applib::$tasks_table, array('t_id' => $task), 'project');
            $data['assign_to'] = Applib::get_table_field(Applib::$projects_table, array('project_id' => $data['project']), 'assign_to');
            $data['task_details'] = Applib::retrieve(Applib::$tasks_table,array('t_id' => $task));
            $this->load->view('modal/edit_task', isset($data) ? $data : NULL);
        }
    }

    function add() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);
            if ($this->form_validation->run('projects', 'add_task') == FALSE) {
                 Applib::make_flashdata(array(
                        'form_error'=> validation_errors()
                        ));
                $this->applib->redirect_to('projects/view/' . $project . '?group=tasks', 'error', lang('task_add_failed'));
            } else {


                $assign = $this->input->post('assigned_to');
                $assigned_to = serialize($this->input->post('assigned_to'));
                $form_data = array(
                    'task_name' => $this->input->post('task_name'),
                    'project' => $this->input->post('project'),
                    'due_date' => date_format(date_create_from_format(config_item('date_php_format'), $this->input->post('due_date')), 'Y-m-d'),
                    'assigned_to' => $assigned_to,
                    'task_progress' => $this->input->post('progress'),
                    'description' => $this->input->post('description'),
                    'estimated_hours' => $this->input->post('estimate'),
                    'added_by' => $this->user,
                );
                if ($this->user_role != '2') {
                    if ($this->input->post('visible') == 'on') {
                        $visible = 'Yes';
                    } else {
                        $visible = 'No';
                    }
                    $form_data['visible'] = $visible;
                    $form_data['milestone'] = $this->input->post('milestone');
                    $form_data['task_progress'] = $this->input->post('progress');
                } else {
                    $form_data['visible'] = 'Yes';
                }
                $task_id = Applib::create(Applib::$tasks_table, $form_data);

                foreach ($assign as $key => $value) {
                    $dt = array(
                        'assigned_user' => $value,
                        'project_assigned' => $project,
                        'task_assigned' => $task_id
                        );
                    Applib::create(Applib::$assign_tasks_table,$dt);
                }

                if (config_item('notify_task_assignments') == 'TRUE') {
                    //send notification to assigned user
                    $this->_assigned_notification($project, $this->input->post('task_name'), $assigned_to);
                }

                $this->_log_activity($project, 'activity_added_new_task', $icon = 'fa-tasks', $this->input->post('task_name')); //log activity

                $this->applib->redirect_to('projects/view/' . $project . '?group=tasks', 'success', lang('task_add_success'));
            }
        } else {
            $data['project'] = $this->uri->segment(4);
            $this->load->view('modal/add_task', isset($data) ? $data : NULL);
        }
    }

    function add_from_template() {
        if ($this->input->post()) {

            $project = $this->input->post('project', TRUE);

            $template_id = $this->input->post('template_id', TRUE);
            $template = $this->db->where(array('template_id' => $template_id))->get(Applib::$saved_tasks_table)->row();

            $assigned_to = Applib::get_table_field(Applib::$projects_table, array('project_id' => $project), 'assign_to');

            $form_data = array(
                'task_name' => $template->task_name,
                'milestone' => $_POST['milestone'],
                'project' => $project,
                'assigned_to' => $assigned_to,
                'visible' => $template->visible,
                'due_date' => date_format(date_create_from_format(config_item('date_php_format'), $this->input->post('due_date')), 'Y-m-d'),
                'task_progress' => 0,
                'description' => $template->task_desc,
                'estimated_hours' => $template->estimate_hours ? $template->estimate_hours : 0,
                'added_by' => $this->user,
            );
            Applib::create(Applib::$tasks_table,$form_data);

            if (config_item('notify_task_assignments') == 'TRUE') {
                //send notification to assigned user
                $this->_assigned_notification($project, $this->input->post('task_name'), $assigned_to);
            }

            $this->_log_activity($project, 'activity_added_new_task', $icon = 'fa-tasks', $template->task_name); //log activity

            $this->applib->redirect_to('projects/view/' . $project . '?group=tasks', 'success', lang('task_add_success'));
        } else {
            $data['project'] = $this->uri->segment(4);
            $data['saved_tasks'] = Applib::retrieve(Applib::$saved_tasks_table,array());
            $this->load->view('modal/task_from_templates', isset($data) ? $data : NULL);
        }
    }

    function file() {
        $action = $this->uri->segment(4);
        if ($this->input->post()) {
            Applib::is_demo();

                if ($action == 'edit') {
                    $project = $this->input->post('project', TRUE);
                    $title = $this->input->post('title', TRUE);
                    $task = $this->input->post('task', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);

                    $data = array(
                        "title" => $title,
                        "description" => $this->input->post('description'),
                    );
                    Applib::update(Applib::$task_files_table,array('file_id'=>$file_id),$data);

                    $this->_log_activity($project, 'activity_edited_file_task', $icon = 'fa-file', $title); 
                    Applib::make_flashdata(array(
                        'response_status'=>'success',
                        'message'=> lang('file_edited_successfully')
                        ));

                    redirect('projects/view/' . $project . '?group=tasks&view=task&id=' . $task);

                }elseif ($action == 'delete') {
                    $project = $this->input->post('project', TRUE);
                    $task = $this->input->post('task', TRUE);
                    $file_id = $this->input->post('file_id', TRUE);
                    $file_data = $this->db->where('file_id',$file_id)->get(Applib::$task_files_table)->row();

                    $file_name = $file_data->file_name;
                    $file_title = $file_data->title;
                    Applib::delete(Applib::$task_files_table, array('file_id' => $file_id));

                    $path = $file_data->path;
                    $fullpath = './resource/project-files/'.$path.$file_data->file_name;

                    if($path == NULL){
                        $fullpath = './resource/project-files/'.$file_data->file_name;
                    }
                    
                    if($file_name)
                        unlink($fullpath);

                    $this->_log_activity($project, 'activity_deleted_task_file', $icon = 'fa-file', $file_title); //log activity

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('file_deleted'));

                    redirect('projects/view/' . $project . '?group=tasks&view=task&id=' . $task);
                } else {
                    //file uploading
                    $project = $this->input->post('project', TRUE);
                    $task = $this->input->post('task', TRUE);
                    
                    $p = $this->db->where('project_id',$project)->get(Applib::$projects_table)->result();
                    $p = $p[0];
                    $path = date("Y-m-d",  strtotime($p->date_created))."_".$project."_".$p->project_code.'/';
                    $fullpath = './resource/project-files/'.$path;
                    Applib::create_dir($fullpath);
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = config_item('allowed_files');
                    $config['max_size'] = config_item('file_max_size');
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload');

                    $this->upload->initialize($config);

                    if (!$this->upload->do_multi_upload("taskfiles")) {
                        Applib::make_flashdata(array(
                        'form_error'=> $this->upload->display_errors('<span style="color:red">', '</span><br>')
                        ));

                        $this->session->set_flashdata('response_status', 'error');
                        $this->session->set_flashdata('message', lang('operation_failed'));
                        redirect('projects/view/'.$project.'?group=tasks&view=task&id='.$task);
                    } else {

                        $fileinfs = $this->upload->get_multi_upload_data();
                        foreach ($fileinfs as $findex => $fileinf) {
                            $data = array(
                                'task' => $task,
                                'title' => $this->input->post('title'),
                                'description' => $this->input->post('description'),
                                'path' => $path,
                                'file_name' => $fileinf['file_name'],
                                'file_ext' => $fileinf['file_ext'],
                                'size' => $fileinf['file_size'],
                                'is_image' => $fileinf['is_image'],
                                'image_width' => $fileinf['image_width'],
                                'image_height' => $fileinf['image_height'],
                                'original_name' => $fileinf['client_name'],
                                'uploaded_by' => $this->tank_auth->get_user_id(),
                            );

                            $this->db->insert('task_files', $data);
                            $file_id = $this->db->insert_id();
                        }
                        if (config_item('notify_project_files') == 'TRUE') {
                            //send notification to assigned user
                            $this->_upload_notification($project);
                        }
                        $this->_log_activity($project, 'activity_added_task_file', $icon = 'fa-file', $this->input->post('title')); //log activity
                        Applib::make_flashdata(array(
                            'response_status' => 'success',
                            'message'         => lang('file_uploaded_successfully')
                            ));
                        redirect('projects/view/' . $project . '?group=tasks&view=task&id=' . $task);
                    }
                }
        } else {
            if ($action == 'edit') {
                $data['file_id'] = $this->uri->segment(5);


                $file = Applib::retrieve(Applib::$task_files_table,array('file_id' => $data['file_id']));
                $data['task'] = $file[0]->task;
                $path = $file[0]->path;
                $fullpath = './resource/project-files/'.$path.$file[0]->file_name;

                    if($path == NULL){
                        $fullpath = './resource/project-files/'.$file[0]->file_name;
                    }
                $data['project'] = $this->uri->segment(6);
                $data['file_details'] = $file;
                $data['fullpath'] = $fullpath;
                $this->load->view('modal/task_edit_file', isset($data) ? $data : NULL);
                return;
            }
            if ($action == 'delete') {
                
                $data['file_id'] = $this->uri->segment(5);
                $data['task'] = Applib::retrieve(Applib::$task_files_table,array('file_id'=>$data['file_id']));
                $data['task'] = $data['task'][0]->task;
                $data['project'] = $this->uri->segment(6);
                $this->load->view('modal/task_delete_file', isset($data) ? $data : NULL);
                return;
                
                
            }
            $data['project'] = $this->uri->segment(4);
            $data['task'] = $this->uri->segment(5);
            $this->load->view('modal/task_add_file', isset($data) ? $data : NULL);
        }
    }

    function _upload_notification($project) {
        $project_title = Applib::get_table_field('projects', array('project_id' => $project), 'project_title');


        $message = Applib::get_table_field('email_templates', array('email_group' => 'project_file'), 'template_body');
        $subject = Applib::get_table_field('email_templates', array('email_group' => 'project_file'), 'subject');

        $uploaded_by = str_replace("{UPLOADED_BY}", $this->username, $message);
        $title = str_replace("{PROJECT_TITLE}", $project_title, $uploaded_by);
        $project_url = str_replace("{PROJECT_URL}", base_url() . 'projects/view/' . $project . '/?group=tasks', $title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $project_url);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        $assigned_to = Applib::get_table_field('projects', array('project_id' => $project), 'assign_to');

        if (!empty($assigned_to)) {
            foreach (unserialize($assigned_to) as $value) {
                $params['recipient'] = $this->user_profile->get_user_details($value, 'email');

                $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
                $params['message'] = $message;

                $params['attached_file'] = '';

                modules::run('fomailer/send_email', $params);
            }
        }
    }

    function tracking() {
        $action = ucfirst($this->uri->segment(4));
        $project = $this->uri->segment(5);
        $task = $this->uri->segment(6);
        if ($action == 'Off') {
            if (!$this->_timer_started_by($task)) {
                $this->applib->redirect_to($_SERVER["HTTP_REFERER"], 'error', lang('timer_not_allowed'));
            }

            $task_start = Applib::get_table_field(Applib::$tasks_table, array('t_id' => $task), 'start_time'); //task start time
            $task_logged_time = $this->applib->task_time_spent($task);
            $time_logged = (time() - $task_start) + $task_logged_time; //time already logged
            $dt = array(
                'timer_status' => $action,
                'logged_time' => $time_logged,
                'start_time' => ''
                );
            Applib::update(Applib::$tasks_table,array('t_id' => $task),$dt);
            $this->_log_timesheet($project, $task, $task_start, time()); //log timesheet
            $message = 'timer_stopped_success';
        } else {
            $dt = array(
                'timer_status' => $action,
                'timer_started_by' => $this->user,
                'start_time' => time()
                );
            Applib::update(Applib::$tasks_table,array('t_id' => $task),$dt);
            $message = 'timer_started_success';
        }
        $this->applib->redirect_to('projects/view/' . $project . '?group=tasks', 'success', lang($message));
    }

    function _timer_started_by($task) {
        $started_by = Applib::get_table_field(Applib::$tasks_table, array('t_id' => $task), 'timer_started_by');
        if ($started_by == $this->user OR $this->user_role == '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

   

    function download() {

        $file_id = $this->uri->segment(4);
        $this->load->helper('download');
        $file_name = Applib::get_table_field(Applib::$task_files_table, array('file_id' => $file_id), 'file_name');
        $path = Applib::get_table_field(Applib::$task_files_table, array('file_id' => $file_id), 'path');
        $fullpath = './resource/project-files/'.$path.$file_name;

        if($path == NULL){
            $fullpath = './resource/project-files/'.$file_name;
        }

        if ($file_name == '') {
            $this->applib->redirect_to($_SERVER["HTTP_REFERER"], 'error', lang('operation_failed'));
        }
        if (file_exists($fullpath)) {
            $data = file_get_contents($fullpath); // Read the file's contents
            force_download($file_name, $data);
        }
    }

    function preview() {
        if (!$this->input->post()) {
            $file_id = $this->uri->segment(4);
            $project_id = $this->uri->segment(5);

            $file = $this->db->select()
                    ->from(Applib::$task_files_table)
                    ->where('file_id', $file_id)
                    ->get()
                    ->row();
            $path = $file->path;
            $fullpath = './resource/project-files/'.$path.$file->file_name;

                if($path == NULL){
                    $fullpath = './resource/project-files/'.$file->file_name;
                }

            if ($file) {
                if (file_exists($fullpath) ){
                    $data['file'] = $file;
                    $data['file_path'] = $fullpath;
                    $data['project_id'] = $project_id;
                    $this->load->view('modal/preview_task_file', $data);
                } else {
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect('projects/view/' . $project_id);
                }
            } else {
                $this->session->set_flashdata('message', lang('operation_failed'));
                redirect('projects/view/' . $project_id);
            }
        }
    }

    function delete() {
        if ($this->input->post()) {
            $project = $this->input->post('project', TRUE);

            $task = $this->input->post('task_id');
            $task_files = Applib::retrieve(Applib::$task_files_table,array('task' => $task));
            foreach ($task_files as $file) {
                unlink('./resource/project-files/'.$file->file_name);
            }
            Applib::delete(Applib::$tasks_table,array('t_id' => $task));
            Applib::delete(Applib::$task_timer_table,array('task' => $task));
            Applib::delete(Applib::$task_files_table,array('task' => $task));

            $this->applib->redirect_to('projects/view/' . $project . '?group=tasks', 'success', lang('task_deleted'));
        } else {
            $data['task_id'] = $this->uri->segment(5);
            $data['project'] = $this->uri->segment(4);
            $this->load->view('modal/delete_task', $data);
        }
    }
    
        function autotasks() {
                $query = 'SELECT * FROM (
                            SELECT task_name FROM fx_tasks
                            UNION ALL 
                            SELECT task_name FROM fx_saved_tasks
                            ) a 
                            GROUP BY task_name 
                            ORDER BY task_name ASC';
                $names = $this->db->query($query)->result();
                $name = array();
                foreach ($names as $n) {
                    $name[] = $n->task_name;
                }
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
        }
        function autotask() {
                $name = $_POST['name'];
                $query = "SELECT * FROM (
                            SELECT task_name, description, estimated_hours as hours FROM fx_tasks
                            UNION ALL 
                            SELECT task_name, task_desc as description, estimate_hours as hours FROM fx_saved_tasks
                            ) a 
                            WHERE a.task_name = '".$name."'";
                $names = $this->db->query($query)->result();
                $name = $names[0];
                $data['json'] = $name;
                $this->load->view('json',isset($data) ? $data : NULL);
        }

    function _task_changed_notification($project, $task_name, $assigned_to) {
        $project_title = Applib::get_table_field(Applib::$projects_table, 
                            array('project_id' => $project), 'project_title');

        $message = Applib::get_table_field(Applib::$email_templates_table, 
                            array('email_group' => 'task_updated'), 'template_body');
        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                            array('email_group' => 'task_updated'), 'subject');

        $task_name = str_replace("{TASK_NAME}", $task_name, $message);
        $assigned_by = str_replace("{ASSIGNED_BY}", $this->username, $task_name);
        $title = str_replace("{PROJECT_TITLE}", $project_title, $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url() . 'projects/view/' . $project . '?group=tasks', $title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        if (!empty($assigned_to)) {
            foreach (unserialize($assigned_to) as $value) {
                $params['recipient'] = Applib::login_info($value)->email;
                $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                modules::run('fomailer/send_email', $params);
            }
        }
    }

    function _assigned_notification($project, $task_name, $assigned_to) {
        $project_title = Applib::get_table_field(Applib::$projects_table, 
                        array('project_id' => $project), 'project_title');
        $message = Applib::get_table_field(Applib::$email_templates_table, 
                        array('email_group' => 'task_assigned'), 'template_body');
        $subject = Applib::get_table_field(Applib::$email_templates_table, 
                        array('email_group' => 'task_assigned'), 'subject');

        $task_name = str_replace("{TASK_NAME}", $task_name, $message);
        $assigned_by = str_replace("{ASSIGNED_BY}", $this->username, $task_name);
        $title = str_replace("{PROJECT_TITLE}", $project_title, $assigned_by);
        $link = str_replace("{PROJECT_URL}", base_url() . 'projects/view/' . $project . '?group=tasks', $title);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);

        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);

        if (!empty($assigned_to)) {
            foreach (unserialize($assigned_to) as $value) {
                $params['recipient'] = Applib::login_info($value)->email;

                $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
                $params['message'] = $message;

                $params['attached_file'] = '';
                modules::run('fomailer/send_email', $params);
            }
        }
    }

    function _log_timesheet($project, $task, $start_time, $end_time) {
        $data = array(
            'pro_id' => $project,
            'task' => $task,
            'user' => $this->user,
            'start_time' => $start_time,
            'end_time' => $end_time
            );
        Applib::create(Applib::$task_timer_table,$data);
    }

    function _log_activity($project, $activity, $icon, $value1 = '', $value2 = '') {
        $data = array(
            'module' => 'projects',
            'module_field_id' => $project,
            'user' => $this->user,
            'activity' => $activity,
            'icon' => $icon,
            'value1' => $value1,
            'value2' => $value2
            );
        Applib::create(Applib::$activities_table,$data);
    }

}

/* End of file tasks.php */