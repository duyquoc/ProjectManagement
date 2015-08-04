<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('update_team')?></h4>
		</div>
		<?php if($role == '1'){ ?>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/team',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">
          		
	

	<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('team_members')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control">
				<?php error_reporting(0)?>
				 <?php
				 $assign_to = $this -> db -> where('role_id !=','2') -> get(Applib::$user_table) -> result();
				 $assigned_users =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project),'assign_to');
				 if (!empty($assign_to)) {
				 foreach ($assign_to as $value) { ?>				 
				 	<option value="<?=$value->id?>" <?php foreach (unserialize($assigned_users) as $user) { 
				 		if ($user == $value->id) { ?> selected = "selected" <?php } else { } } ?>>
				 		<?=ucfirst($this->user_profile->get_profile_details($value->id,'fullname'))?></option>
                            <?php } ?>
                    <?php } ?>							
				</select>
				<span class="help-block m-b-none"><?=lang('select_team_help')?></span>
				</div>

				</div>
				
				
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('update_team')?></button>
		</form>
		<?php } ?>

		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->