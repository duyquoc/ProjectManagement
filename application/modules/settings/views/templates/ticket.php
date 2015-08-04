<div class="m-b-sm">
<?php
$setting_email = isset($_GET['email'])?$_GET['email']:'ticket_staff_email';
?>
		         
	<div class="btn-group">
		<a href="<?=base_url()?>settings/?settings=templates&group=ticket&email=ticket_staff_email" class="<?php if($setting_email == 'ticket_staff_email'){ echo "active"; } ?> btn btn-default"><?=lang('ticket_staff_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=ticket&email=ticket_client_email" class="<?php if($setting_email == 'ticket_client_email'){ echo "active"; } ?> btn btn-default"><?=lang('ticket_client_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=ticket&email=ticket_reply_email" class="<?php if($setting_email == 'ticket_reply_email'){ echo "active"; } ?> btn btn-default"><?=lang('ticket_reply_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=ticket&email=ticket_closed_email" class="<?php if($setting_email == 'ticket_closed_email'){ echo "active"; } ?> btn btn-default"><?=lang('ticket_closed_email')?></a>
	</div>              
 </div>

		              <!-- Start Form -->
<?=$this->load->view('templates/ticket/'.$setting_email);?>
				<!-- End Form -->

