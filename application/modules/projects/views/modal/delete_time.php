<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-warning"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_time')?></h4>
		</div><?php
			echo form_open(base_url().'projects/timesheet/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_time_warning')?></p>
			
			<input type="hidden" name="project" value="<?=$project?>">
			<input type="hidden" name="timer_id" value="<?=$timer_id?>">
			<input type="hidden" name="cat" value="<?=$cat?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->