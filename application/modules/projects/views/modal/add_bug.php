<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_bug')?></h4>
		</div>
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/bugs/add',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_ref')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=random_string('nozero', 6);?>" name="issue_ref" readonly>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" required placeholder="<?=lang('issue_title')?>" name="issue_title">
				</div>
				</div>

				<?php if ($role == '1') { ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reporter')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="reporter" class="form-control">
					<?php
					 $users = $this -> db -> get(Applib::$user_table) -> result();
					if (!empty($users)) {
					foreach ($users as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst($user->username)?></option>
					<?php } } ?>	
				</select>
				</div>
				</div>
				<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('priority')?> </label>
				<div class="col-lg-8">
				<select name="priority" class="form-control">
				 	<option value="low"><?=lang('low')?></option>	
				 	<option value="medium"><?=lang('medium')?></option>
				 	<option value="high"><?=lang('high')?></option>				
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('severity')?> </label>
				<div class="col-lg-8">
				<select name="severity" class="form-control">
						<option value="<?=lang('minor')?>"><?=lang('minor')?></option>
						<option value="<?=lang('major')?>"><?=lang('major')?></option>
						<option value="<?=lang('show_stopper')?>"><?=lang('show_stopper')?></option>
						<option value="<?=lang('must_be_fixed')?>"><?=lang('must_be_fixed')?></option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="bug_description" class="form-control" placeholder="<?=lang('detailed_description')?>" required></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reproducibility')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="reproducibility" class="form-control" placeholder="<?=lang('steps_causing_bug')?>" required></textarea>
				</div>
				</div>
			

			<?php if ($role != '2') { ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> </label>
				<div class="col-lg-8">
				<select name="assigned_to" class="form-control">
				<option value="-"><?=lang('not_assigned')?></option>
				 <?php 
				 $assign_to = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project),'assign_to'); 
				 $assign_to = unserialize($assign_to);
				 if (!empty($assign_to)) {
				 foreach ($assign_to as $value) { ?>
				 	<option value="<?=$value?>"><?=ucfirst(Applib::login_info($value)->username)?></option>
                            <?php } ?>
                    <?php } ?>							
				</select>
				</div>
				</div>

				<?php } ?>

				
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('new_bug')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->