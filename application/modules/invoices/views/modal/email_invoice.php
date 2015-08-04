<div class="modal-dialog">
	<div class="modal-content">
	<?php
	foreach ($invoice_details as $key => $invoice) { ?>
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('email_invoice')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/email',$attributes); ?>
		<div class="modal-body">
			<input type="hidden" name="ref" value="<?=$invoice->reference_no?>">
			<input type="hidden" name="invoice_id" value="<?=$invoice->inv_id?>">
			<input type="hidden" name="client_name" value="<?=ucfirst($this->applib->company_details($invoice->client,'company_name'))?>">	
			<input type="hidden" name="invoice_currency" value="<?=Applib::get_table_field(Applib::$invoices_table,array(
				                                    'inv_id' => $invoice->inv_id
									), 'currency')?>">			
			
			<input type="hidden" name="amount" value="<?=number_format($this -> applib -> calculate('invoice_due',$invoice->inv_id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>">
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$this -> applib -> get_any_field('email_templates',array(
				                                    'email_group' => 'invoice_message'
									), 'subject');
									?> <?=$invoice->reference_no?>" name="subject">
				</div>
				</div>


				<input type="hidden" name="message" class="hiddenmessage">

				<div class="message" contenteditable="true">

				<?=Applib::get_table_field(Applib::$email_templates_table,array(
				                                    'email_group' => 'invoice_message'
									), 'template_body');
									?>
									</div>
				
				
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-dark" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="submit btn btn-<?=config_item('button_color')?>"><?=lang('email_invoice')?></button>
		</form>
		</div>
	</div>
	<?php } ?>
	<!-- /.modal-content -->


<script type="text/javascript">
	$(function(){
    $('.submit').click(function () {
        var mysave = $('.message').html();
        $('.hiddenmessage').val(mysave);
    });
});
</script>



</div>
<!-- /.modal-dialog -->