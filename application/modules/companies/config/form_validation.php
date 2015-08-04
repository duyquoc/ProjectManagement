<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(  
                'add_client' => array(
                                    array(
                                            'field' => 'company_ref',
                                            'label' => 'Company Ref',
                                            'rules' => 'required|is_unique[companies.company_ref]'
                                         ),
                                    array(
                                            'field' => 'company_name',
                                            'label' => 'Company Name',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'company_email',
                                            'label' => 'Company Email',
                                            'rules' => 'required'
                                         )
                                    )
);