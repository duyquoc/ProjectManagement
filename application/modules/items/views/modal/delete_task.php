<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-warning"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_task')?></h4>
		</div><?php
			echo form_open(base_url().'items/delete_task'); ?>
		<div class="modal-body">
			<p><?=lang('delete_item_warning')?></p>
			<input type="hidden" name="r_url" value="<?=base_url()?>items">
			
			<input type="hidden" name="template_id" value="<?=$template_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->