<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_task')?></h4>
		</div>
		<style>
.datepicker{z-index:1151 !important;}
</style>
		<?php
					if (!empty($task_details)) {
					foreach ($task_details as $key => $task) { ?>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/edit',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="task_id" value="<?=$task->t_id?>">
			 <input type="hidden" name="project" value="<?=$task->project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->task_name?>" name="task_name">
				</div>
				</div>
				
<?php if($role != 2){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="">Select One</option>
				<?php 
				$milestones = Applib::retrieve(Applib::$milestones_table,array('project' => $project));
				if (!empty($milestones)) {
					foreach ($milestones as $m) { ?>
						<option value="<?=$m->id?>"<?=($task->milestone == $m->id ? ' selected="selected"' : '')?>><?=$m->milestone_name?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
<?php } ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control"><?=$task->description?></textarea>
				</div>
				</div>
<?php if($role != 2){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('progress')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
                                <input size="25" name="progress" id="task-progress" data-slider-id="task-progress" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="10" data-slider-value="<?=$task->task_progress?>"/>
				</div>
				</div>
<?php } ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($task->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$task->estimated_hours?>" name="estimate">
				</div>
				</div>

	<?php if($role == '1'){ ?>

	<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control">
				<?php error_reporting(0)?>
				 <?php if (!empty($assign_to)) {
				 foreach (unserialize($assign_to) as $value) { ?>				 
				 	<option value="<?=$value?>" <?php foreach (unserialize($task->assigned_to) as $user) { 
				 		if ($user == $value) { ?> selected = "selected" <?php } else { } } ?>>
				 		<?=ucfirst($this->user_profile->get_profile_details($value,'fullname'))?></option>
                            <?php } ?>
                    <?php } ?>							
				</select>
				</div>
				</div>
<?php } if($role != '2'){ ?>
				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('visible_to_client')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="visible" <?php if($task->visible == 'Yes'){ echo "checked=\"checked\""; }?>>
                          <span></span>
                        </label>
                      </div>
                    </div>


				
<?php } ?>
				

				

				
			
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
<script type="text/javascript">
        $('.datepicker-input').datepicker({ language: locale});
        $('#task-progress').slider({
                formatter: function(value) {
                        return value;
                }
        });    
</script>