<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('assign_bug')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'bugs/assign_to',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('assign_bug_warning')?></p>
			 
          				<input type="hidden" name="bug_id" value="<?=$bug_id?>">
          				<input type="hidden" name="issue_ref" value="<?=$issue_ref?>">
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to" class="form-control">
					<?php
					if (!empty($users)) {
					foreach ($users as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst($user->username)?></option>
					<?php } } ?>	
				</select>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
		<button type="submit" class="btn btn-primary"><?=lang('assign_bug')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->