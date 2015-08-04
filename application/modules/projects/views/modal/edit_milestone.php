<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_milestone')?></h4>
		</div>
		<?php 
		$role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->tank_auth->get_user_id()),'role_id');
		if($role == '1'){ ?>

		<?php
			if (!empty($details)) {
			foreach ($details as $key => $m) { 
			
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/milestones/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$m->project?>">
          <input type="hidden" name="id" value="<?=$m->id?>">
		<div class="modal-body">
		
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$m->milestone_name?>" name="milestone_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> </label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control"><?=$m->description?></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?></label>
				<div class="col-lg-8">
					<input type="text" class="input-sm input-s datepicker-input form-control" value="<?=strftime(config_item('date_format'), strtotime($m->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?></label>
				<div class="col-lg-8">
					<input type="text" class="input-sm input-s datepicker-input form-control" value="<?=strftime(config_item('date_format'), strtotime($m->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>
				
<?php 
		} 
	} 
?>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('edit_milestone')?></button>
		</form>
		<?php } ?>
		</div>
<script type="text/javascript">
        $('.datepicker-input').datepicker({ language: locale });
</script>
          
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->