<div class="m-b-sm">
<?php
$setting_email = isset($_GET['email'])?$_GET['email']:'register';
?>
		         
	<div class="btn-group">
		<a href="<?=base_url()?>settings/?settings=templates&group=user&email=register" class="<?php if($setting_email == 'register'){ echo "active"; } ?> btn btn-default"><?=lang('register_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=user&email=forgot_password" class="<?php if($setting_email == 'forgot_password'){ echo "active"; } ?> btn btn-default"><?=lang('forgot_password')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=user&email=change_email" class="<?php if($setting_email == 'change_email'){ echo "active"; } ?> btn btn-default"><?=lang('change_email')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=user&email=activate_account" class="<?php if($setting_email == 'activate_account'){ echo "active"; } ?> btn btn-default"><?=lang('activate_account')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=user&email=reset_password" class="<?php if($setting_email == 'reset_password'){ echo "active"; } ?> btn btn-default"><?=lang('reset_password')?></a>
	</div>              
 </div>

		              <!-- Start Form -->
<?=$this->load->view('templates/user/'.$setting_email);?>
				<!-- End Form -->

