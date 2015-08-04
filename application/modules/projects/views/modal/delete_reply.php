<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_comment')?></h4>
		</div><?php
			echo form_open(base_url().'projects/delete_reply'); ?>
		<div class="modal-body">
			<p><?=lang('delete_message_warning')?></p>
			
			<input type="hidden" name="reply_id" value="<?=$details[0]->reply_id?>">
			<input type="hidden" name="parent_comment" value="<?=$details[0]->parent_comment?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-primary"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->