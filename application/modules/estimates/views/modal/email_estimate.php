<div class="modal-dialog">
	<div class="modal-content">
	<?php
	foreach ($estimate_details as $key => $estimate) { ?>
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('email_estimate')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'estimates/email',$attributes); ?>
		<div class="modal-body">
			<input type="hidden" name="ref" value="<?=$estimate->reference_no?>">
			<input type="hidden" name="estimate_id" value="<?=$estimate->est_id?>">
			<input type="hidden" name="client_name" value="<?=ucfirst($this->applib->company_details($estimate->client,'company_name'))?>">	
			<input type="hidden" name="estimate_currency" value="<?=Applib::get_table_field(Applib::$estimates_table,array('est_id' => $estimate->est_id), 'currency')?>">			
			
			<input type="hidden" name="amount" value="<?=number_format($this -> applib -> est_calculate('estimate_amount',$estimate->est_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?>">
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$this -> applib -> get_any_field('email_templates',array(
				                                    'email_group' => 'estimate_email'
									), 'subject');
									?> <?=$estimate->reference_no?>" name="subject">
				</div>
				</div>

				<input type="hidden" name="message" class="hiddenmessage">

				<div class="message" contenteditable="true">

				<?=Applib::get_table_field(Applib::$email_templates_table,array('email_group' => 'estimate_email'
									), 'template_body');
									?>
									</div>
				
				
				
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="submit btn btn-<?=config_item('button_color')?>"><?=lang('email_estimate')?></button>
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