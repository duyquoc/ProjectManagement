<div class="m-b-sm">
<?php
$setting_email = isset($_GET['email'])?$_GET['email']:'payment_email';
?>
		         
	<div class="btn-group">
		<a href="<?=base_url()?>settings/?settings=templates&group=invoice&email=payment_email" class="<?php if($setting_email == 'payment_email'){ echo "active"; } ?> btn btn-default"><?=lang('payment_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=invoice&email=invoice_message" class="<?php if($setting_email == 'invoice_message'){ echo "active"; } ?> btn btn-default"><?=lang('invoice_message')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=invoice&email=invoice_reminder" class="<?php if($setting_email == 'invoice_reminder'){ echo "active"; } ?> btn btn-default"><?=lang('invoice_reminder')?></a>
	</div>              
 </div>

		              <!-- Start Form -->
<?=$this->load->view('templates/invoice/'.$setting_email);?>
				<!-- End Form -->

