<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_user')?></h4>
		</div><?php
			echo form_open(base_url().'users/account/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_user_warning')?></p>
			
			<input type="hidden" name="user_id" value="<?=$user_id?>">
			<?php
			$company = $this->user_profile->get_profile_details($user_id,'company');
			if ($company >= 1) {
				$redirect = 'companies/view/details/'.$company;
			}else{
				$redirect = 'users/account';				
			}
			?>
			<input type="hidden" name="r_url" value="<?=base_url()?><?=$redirect?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->