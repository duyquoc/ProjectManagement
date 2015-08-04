<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_project')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/view/add',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('email_sending_warning')?></p>
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="PRO<?=random_string('nozero', 5);?>" name="project_code">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('project_title')?>" name="project_title">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('client')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="client" class="form-control">
					<?php
					if (!empty($users)) {
					foreach ($users as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst($user->username)?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="end_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 
				<div class="form-group"> 
				<label class="col-sm-2 control-label"><?=lang('progress')?></label>
				<div class="col-sm-10"> 
					<input class="slider slider-horizontal form-control" type="text" value="" data-slider-min="5" data-slider-max="20" data-slider-step="1" data-slider-value="10" data-slider-orientation="horizontal" > 
				</div>
				</div> 
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('create_project')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->