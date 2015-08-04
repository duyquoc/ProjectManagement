<div class="m-b-sm">
<?php
$setting_email = isset($_GET['email'])?$_GET['email']:'message_received';
?>
		         
	<div class="btn-group">
		<a href="<?=base_url()?>settings/?settings=templates&group=extra&email=message_received" class="<?php if($setting_email == 'message_received'){ echo "active"; } ?> btn btn-default"><?=lang('message_received')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=extra&email=estimate_email" class="<?php if($setting_email == 'estimate_email'){ echo "active"; } ?> btn btn-default"><?=lang('estimate_email')?></a>
	</div>              
 </div>

		              <!-- Start Form -->
<?=$this->load->view('templates/extra/'.$setting_email);?>
				<!-- End Form -->

