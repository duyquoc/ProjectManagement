<div class="m-b-sm">
<?php
$setting_email = isset($_GET['email'])?$_GET['email']:'bug_assigned';
?>
		         
	<div class="btn-group">
		<a href="<?=base_url()?>settings/?settings=templates&group=bugs&email=bug_assigned" class="<?php if($setting_email == 'bug_assigned'){ echo "active"; } ?> btn btn-default"><?=lang('bug_assigned')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=bugs&email=bug_status" class="<?php if($setting_email == 'bug_status'){ echo "active"; } ?> btn btn-default"><?=lang('bug_status')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=bugs&email=bug_comment" class="<?php if($setting_email == 'bug_comment'){ echo "active"; } ?> btn btn-default"><?=lang('bug_comments')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=bugs&email=bug_file" class="<?php if($setting_email == 'bug_file'){ echo "active"; } ?> btn btn-default"><?=lang('bug_file')?></a>
		<a href="<?=base_url()?>settings/?settings=templates&group=bugs&email=bug_reported" class="<?php if($setting_email == 'bug_reported'){ echo "active"; } ?> btn btn-default"><?=lang('bug_reported')?></a>
	</div>              
 </div>

		              <!-- Start Form -->
<?=$this->load->view('templates/bug/'.$setting_email);?>
				<!-- End Form -->

