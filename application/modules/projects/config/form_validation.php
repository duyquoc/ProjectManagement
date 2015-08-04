<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(                 
                'add_task' => array(
                                    array(
                                            'field' => 'task_name',
                                            'label' => 'Task Name',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'project',
                                            'label' => 'Project',
                                            'rules' => 'required'
                                         )
                                    ),
                'add_task_time' => array(
                                    array(
                                            'field' => 'task',
                                            'label' => 'Task',
                                            'rules' => 'required'
                                         )
                                    ),
                'add_milestone' => array(
                                    array(
                                            'field' => 'milestone_name',
                                            'label' => 'Milestone Name',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'project',
                                            'label' => 'Project',
                                            'rules' => 'required'
                                         )
                                    ),
                'add_link' => array(
                                    array(
                                            'field' => 'link_url',
                                            'label' => 'Link URL',
                                            'rules' => 'required'
                                         )
                                    ),
                'add_project' => array(
                                    array(
                                            'field' => 'project_code',
                                            'label' => 'Project Code',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'project_title',
                                            'label' => 'Project Title',
                                            'rules' => 'required'
                                         )
                                    ),
                'edit_project' => array(
                                    array(
                                            'field' => 'project_code',
                                            'label' => 'Project Code',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'project_title',
                                            'label' => 'Project Title',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'client',
                                            'label' => 'Client',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'assign_to',
                                            'label' => 'Assign To',
                                            'rules' => 'required'
                                         )

                                    )
);