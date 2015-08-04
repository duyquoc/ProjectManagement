<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(  
                'add_user' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|xss_clean|valid_email'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'Password', 'trim|xss_clean'
                                         ),
                                    array(
                                            'field' => 'confirm_password',
                                            'label' => 'Confirm Password',
                                            'rules' => 'trim|xss_clean|matches[password]'
                                         )
                                    )
);