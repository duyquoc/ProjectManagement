<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
**********************************************************************************
* Copyright: gitbench 2014
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

// Includes all users operations
class Items extends MX_Controller {

	function __construct()
	{
		
		parent::__construct();		
		$this -> load -> module('layouts');	
		$this->load->library(array('tank_auth','template','form_validation'));
		$this -> form_validation -> set_error_delimiters('<span style="color:red">', '</span><br>');

		$this -> user = $this->tank_auth->get_user_id();
		$this -> username = $this -> tank_auth -> get_username(); // Set username
		if (!$this -> user) {
			$this -> applib -> redirect_to('auth/login','error',lang('access_denied'));			
		}
		$this->items_table = 'items';
		$this->saved_items_table = 'items_saved';
		$this->load->model('invoice_model', 'items');
		$this->rates_table = 'tax_rates';
		
	}

	function add(){
		if ($this->input->post()) {

		$invoice_id = $_POST['invoice_id'];

		if ($this -> form_validation -> run('invoices','add_item') == FALSE)
		{	
				$_POST = '';
				$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
		}else{	
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = ($_POST['item_tax_rate'] / 100) *  $sub_total;
			$_POST['total_cost'] = $sub_total + $_POST['item_tax_total'];
			unset($_POST['tax']);

				if($this -> items -> add($this->items_table,$_POST)){
					$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
				}
			}
		}
	}

	function edit(){
		if ($this->input->post()) {

		$invoice_id = $this -> applib->get_any_field($this->items_table,array('item_id'=>$_POST['item_id']),'invoice_id');
		if ($this -> form_validation -> run('invoices','add_item') == FALSE)
		{	
				$_POST = '';
				$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
		}else{	
			
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$_POST['item_tax_rate'] = $this->input->post('item_tax_rate');
			$_POST['item_tax_total'] = ($_POST['item_tax_rate'] / 100) *  $sub_total;
			$_POST['total_cost'] = $sub_total + $_POST['item_tax_total'];

				if($this -> items -> update($this->items_table, array('item_id' => $_POST['item_id']),$_POST)){
					$this -> applib -> redirect_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
				}
			}
		}else{
			$item = $this->uri->segment(4);
			$data['item_details'] = $this -> items -> retrieve($this->items_table,array('item_id' => $item),$limit = NULL, $offset = 0,$sort = NULL);
			$data['rates'] = $this -> items -> retrieve($this->rates_table,array('tax_rate_id !='=>'0'), $limit = NULL, $offset = 0, $sort = NULL);


			$this->load->view('modal/edit_item',$data);
		}
	}

	function insert()
	{
		if ($this->input->post()) {
			$invoice = $this->input->post('invoice');

			if ($this->form_validation->run('invoices','insert_items') == FALSE)
			{
					$this -> applib -> redirect_to('invoices/view/'.$invoice,'error',lang('operation_failed'));
			}else{	
			$item = $this->input->post('item');
			$saved_item = $this -> db -> where(array('item_id'=>$item)) -> get($this->saved_items_table) -> row();
                        $items = $this->db->where('invoice_id',$invoice)->get('items')->result();

			$form_data = array(
			                'invoice_id' => $invoice,
			                'item_name'  => $saved_item->item_name,
			                'item_desc' => $saved_item->item_desc,
			                'unit_cost' => $saved_item->unit_cost,
			                'item_tax_rate' => $saved_item->item_tax_rate,
			                'item_tax_total' => $saved_item->item_tax_total,
			                'quantity' => $saved_item->quantity,
			                'total_cost' => $saved_item->total_cost,
                                        'item_order' => count($items) + 1
			            );
			if($this -> items -> add($this->items_table,$form_data)){
					$this -> applib -> redirect_to('invoices/view/'.$invoice,'success',lang('item_added_successfully'));
				}
			}
		}else{
			$data['invoice'] = $this->uri->segment(4);
			$data['items'] = $this -> items -> retrieve($this->saved_items_table,array('item_id !=' => 0),$limit = NULL, $offset = 0,$sort = NULL);
			$this->load->view('modal/quickadd',$data);
		}
	}

	function delete(){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					$invoice = $this->input->post('invoice', TRUE);
					if($this -> items -> delete($this->items_table,array('item_id' => $item_id))){
						$this -> applib -> redirect_to('invoices/view/'.$invoice,'success',lang('item_deleted_successfully'));
					}
		}else{
			$data['item_id'] = $this->uri->segment(4);
			$data['invoice'] = $this->uri->segment(5);
			$this->load->view('modal/delete_item',$data);
		}
	}

	function reorder(){
                if ($this->input->post() ){
                        $items = $this->input->post('json', TRUE);
                        $items = json_decode($items);
                        foreach ($items[0] as $ix => $item) {
                            $this->items->update($this->items_table, array('item_id' => $item->id),array("item_order"=>$ix+1));
                        }
                }
                $data['json'] = array();
                $this->load->view('json',isset($data) ? $data : NULL);
	}
        
}

/* End of file invoices.php */