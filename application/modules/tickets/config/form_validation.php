<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(                 
                
                'add_ticket' => array(
                                    array(
                                            'field' => 'ticket_code',
                                            'label' => 'Ticket Code',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'subject',
                                            'label' => 'Subject',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'body',
                                            'label' => 'Message',
                                            'rules' => 'required'
                                         )
                                    ),
                'edit_ticket' => array(
                                    array(
                                            'field' => 'ticket_code',
                                            'label' => 'Ticket Code',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'subject',
                                            'label' => 'Subject',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'body',
                                            'label' => 'Message',
                                            'rules' => 'required'
                                         )
                                    ),
                
                
                'ticket_reply' => array(
                                    array(
                                            'field' => 'reply',
                                            'label' => 'Reply',
                                            'rules' => 'required'
                                         )
                                    )
                    
                                   
               );