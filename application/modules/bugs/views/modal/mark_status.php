<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('change_bug_status')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'bugs/mark_status',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('assign_bug_warning')?></p>
			 
          				<input type="hidden" name="bug_id" value="<?=$bug_id?>">
          				<input type="hidden" name="issue_ref" value="<?=$issue_ref?>">
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('bug_status')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="bug_status" class="form-control">
						<option value="Unconfirmed"><?=lang('unconfirmed')?></option>
						<option value="Confirmed"><?=lang('confirmed')?></option>
						<option value="In Progress"><?=lang('in_progress')?></option>
						<option value="Resolved"><?=lang('resolved')?></option>
				</select>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('change_status')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->