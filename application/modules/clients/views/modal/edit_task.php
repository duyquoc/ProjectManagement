<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_task')?></h4>
		</div>
		<?php
					if (!empty($task_details)) {
					foreach ($task_details as $key => $task) { ?>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'collaborator/tasks/edit',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('email_sending_warning')?></p>
			 <input type="hidden" name="task_id" value="<?=$task->t_id?>">
			 <input type="hidden" name="project" value="<?=$task->project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->task_name?>" name="task_name">
				</div>
				</div>



				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('visible_to_client')?></label> 
				<div class="col-lg-8">
				<div class="checkbox"> 
					<label class="checkbox-custom"> 
					<input name="visible" checked="checked" type="checkbox"> Yes 
					</label> 
					</div>
				</div> 
				</div> 

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('progress')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="progress" class="form-control">
				<option value="<?=$task->progress?>"><?=$task->progress?> %</option>
					<option value="0">0 %</option>
					<option value="10">10 %</option>
					<option value="20">20 %</option>
					<option value="30">30 %</option>
					<option value="40">40 %</option>
					<option value="50">50 %</option>
					<option value="60">60 %</option>
					<option value="70">70 %</option>
					<option value="80">80 %</option>
					<option value="90">90 %</option>
					<option value="100">100 %</option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->estimated_hours?>" name="estimate">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control"><?=$task->description?></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('edit_task')?></button>
		</form>
		<?php } } ?>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->