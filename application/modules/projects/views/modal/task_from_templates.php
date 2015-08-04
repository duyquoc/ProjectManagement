<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
		<style>
.datepicker{z-index:1151 !important;}
</style>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/add_from_template',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
			<p><?=lang('email_sending_warning')?></p>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('templates')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="template_id" class="form-control">
					<?php
					if (!empty($saved_tasks)) {
					foreach ($saved_tasks as $key => $task) { ?>
						<option value="<?=$task->template_id?>"><?=ucfirst($task->task_name)?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="0">None</option>
					<?php
					$milestones = Applib::retrieve(Applib::$milestones_table,array('project' => $project));
					if (!empty($milestones)) {
					foreach ($milestones as $key => $m) { ?>
						<option value="<?=$m->id?>"><?=ucfirst($m->milestone_name)?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('add_task')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
        $('.datepicker-input').datepicker({ language: locale});
    </script>