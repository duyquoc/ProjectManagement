<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(  
                'add_item' => array(
                                    array(
                                            'field' => 'item_name',
                                            'label' => 'Item Name',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'quantity',
                                            'label' => 'Quantity',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'unit_cost',
                                            'label' => 'Unit Cost',
                                            'rules' => 'required'
                                         )
                                    ),
                'edit_item' => array(
                                    array(
                                            'field' => 'item_name',
                                            'label' => 'Item Name',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'quantity',
                                            'label' => 'Quantity',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'unit_cost',
                                            'label' => 'Unit Cost',
                                            'rules' => 'required'
                                         )
                                    )
);