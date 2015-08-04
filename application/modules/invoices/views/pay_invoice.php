
<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
			 <?php
                $username = $this -> tank_auth -> get_username();
                if($role == '1' OR $this -> applib -> allowed_module('add_invoices',$username)) { ?>
		<a href="<?=base_url()?>invoices/add" data-original-title="<?=lang('new_invoice')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-<?=config_item('button_color')?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
		<?php } ?>
		<p class="h4"><?=lang('all_invoices')?></p>
		</header>

			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

			<?=$this->load->view('sidebar/invoices',$invoices)?>

			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix hidden-print">
							<div class="row m-t-sm">
								<div class="col-sm-8 m-b-xs">

						
									<?php
									if (!empty($invoice_details)) {
									foreach ($invoice_details as $key => $inv) { ?>
									
							<?php if($role == '1' OR $this -> applib -> allowed_module('edit_all_invoices',$username)) { ?>

									<a href="<?=base_url()?>invoices/items/insert/<?=$inv->inv_id?>" title="<?=lang('item_quick_add')?>" class="btn btn-sm btn-<?=config_item('button_color')?>" data-toggle="ajaxModal">
									<i class="fa fa-list-alt text-white"></i> <?=lang('items')?></a>
									<?php } ?>
							<?php if($role == '1' OR $this -> applib -> allowed_module('pay_invoice_offline',$username)) { ?>	
									<?php
									if ($this->user_profile->invoice_payable($inv->inv_id) > 0) { ?>
									<a class="btn btn-sm btn-<?=config_item('button_color')?>" href="<?=base_url()?>invoices/pay/<?=$inv->inv_id?>" 
										title="<?=lang('add_payment')?>"><i class="fa fa-credit-card"></i> <?=lang('pay_invoice')?>
									</a>
							<?php } ?>
									
									
									<div class="btn-group">
										<button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
										<?=lang('more_actions')?>
										<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<?php
											if ($this -> applib -> invoice_payable($inv->inv_id) > 0) { ?>
							<?php if($role == '1' OR $this -> applib -> allowed_module('email_invoices',$username)) { ?>
											<li>
												<a href="<?=base_url()?>invoices/email/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('email_invoice')?>"><?=lang('email_invoice')?></a>
											</li>
							<?php } ?>
							<?php if($role == '1' OR $this -> applib -> allowed_module('send_email_reminders',$username)) { ?>
											<li>
												<a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('send_reminder')?>"><?=lang('send_reminder')?></a>
											</li>
											<?php } ?>
											<li><a href="<?=base_url()?>invoices/timeline/<?=$inv->inv_id?>"><?=lang('invoice_history')?></a></li>
							<?php } ?>
											<li class="divider"></li>
							<?php if($role == '1' OR $this -> applib -> allowed_module('edit_all_invoices',$username)) { ?>
											<li><a href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>"><?=lang('edit_invoice')?></a></li>
							<?php } ?>
							<?php if($role == '1' OR $this -> applib -> allowed_module('delete_invoices',$username)) { ?>
											<li><a href="<?=base_url()?>invoices/delete/<?=$inv->inv_id?>" data-toggle="ajaxModal"><?=lang('delete_invoice')?></a></li>
							<?php } ?>
										</ul>
									</div>

									<?php }else{ ?>
									<div class="btn-group hidden-nav-xs">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-credit-card"></i> <?=lang('pay_invoice')?>
          <span class="caret">
          </span> </button>
          <ul class="dropdown-menu text-left">
            <li><a href="<?=base_url()?>paypal/pay/<?=$inv->inv_id?>" data-toggle="ajaxModal"
				title="<?=lang('via_paypal')?>"><?=lang('via_paypal')?></a></li>
            <li><a href="<?=base_url()?>stripepay/pay/<?=$inv->inv_id?>" data-toggle="ajaxModal" title="<?=lang('via_stripe')?>"><?=lang('via_stripe')?></a></li>
            
          </ul>
        </div>
        <?php } ?>



									
								</div>
								<div class="col-sm-4 m-b-xs pull-right">
									<a href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>" class="btn btn-sm btn-dark pull-right">
									<i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
								</div>
							</div> </header>
					<section class="scrollable wrapper">

					 <!-- Start create invoice -->
<div class="col-sm-12">
	<section class="panel panel-default">
	
	
	<div class="panel-body">
	

	<?php
		$attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'invoices/pay',$attributes);
        $inv_cur = Applib::get_table_field(Applib::$invoices_table, array('inv_id' => $invoice_id),'currency');
        $cur = $this->applib->currencies($inv_cur);
    ?>

			<input type="hidden" name="invoice" value="<?=$invoice_id?>">
			<input type="hidden" name="currency" value="<?=$inv_cur?>">
			 
          				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('trans_id')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=random_string('nozero', 6);?>" name="trans_id" readonly>
				</div>
				</div>
				<div class="form-group">
                                    
				<label class="col-lg-3 control-label"><?=lang('amount')?> (<?=$cur->symbol?>) <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=round($this -> applib -> calculate('invoice_due',$invoice_id),2)?>" name="amount">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('payment_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="payment_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('payment_method')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="payment_method" class="form-control">
					<?php
					if (!empty($payment_methods)) {
					foreach ($payment_methods as $key => $p_method) { ?>
						<option value="<?=$p_method->method_id?>"><?=$p_method->method_name?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('notes')?></label>
				<div class="col-lg-8">
				<textarea name="notes" class="form-control"></textarea>
				</div>
				</div>

				<div class="form-group">
                      <label class="col-lg-3 control-label"><?=lang('send_email')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="send_thank_you">
                          <span></span>
                        </label>
                      </div>
                    </div>
		<div class="modal-footer"> <a href="<?=base_url()?>invoices/view/<?=$invoice_id?>" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('button_color')?>"><?=lang('add_payment')?></button>
		</form>


		
</div>
</section>
</div>


<!-- End create invoice -->

<?php } } ?>

					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->






