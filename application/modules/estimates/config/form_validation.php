<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(  
                'add_estimate_item' => array(
                                    array(
                                            'field' => 'estimate_id',
                                            'label' => 'Invoice',
                                            'rules' => 'required'
                                         ),
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
                 'add_item' => array(
                                    array(
                                            'field' => 'estimate_id',
                                            'label' => 'Estimate',
                                            'rules' => 'required'
                                         ),
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
                'insert_items' => array(
                                    array(
                                            'field' => 'item',
                                            'label' => 'Item Name',
                                            'rules' => 'required'
                                         )
                                    ),
                'edit_estimate' => array(
                                    array(
                                            'field' => 'client',
                                            'label' => 'Client',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'due_date',
                                            'label' => 'Due Date',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'reference_no',
                                            'label' => 'Ref No',
                                            'rules' => 'required'
                                         )
                                    ),
                'add_estimate' => array(
                                    array(
                                            'field' => 'reference_no',
                                            'label' => 'Reference',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'client',
                                            'label' => 'Client',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'due_date',
                                            'label' => 'Due Date',
                                            'rules' => 'required'
                                         )
                                    )        
);