<!-- Start create project -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('project_details')?></header>
	<div class="panel-body">

<?php
$username = $this -> tank_auth -> get_username();

if ($role == '1' OR $this -> applib -> allowed_module('edit_all_projects',$username)){

if (!empty($project_details)) {
             foreach ($project_details as $key => $project) {
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/edit',$attributes); ?>
          <?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
			 <input type="hidden" name="project_id" value="<?=$project->project_id?>">
          			
                                <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('status')?> </label>
				<div class="col-lg-3">
					<select class="form-control" name="status">
                                            <option value="Active"<?=($project->status == 'Active' ? ' selected="selected"':'')?>><?=lang('active')?></option>
                                            <option value="On Hold"<?=($project->status == 'On Hold' ? ' selected="selected"':'')?>><?=lang('on_hold')?></option>
                                            <option value="Done"<?=($project->status == 'Done' ? ' selected="selected"':'')?>><?=lang('done')?></option>
                                        </select>
				</div>
				</div>
          			
                                <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
				</div>
				</div>
                         
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=$project->project_title?>" name="project_title">
				</div>
				</div>		

				<div class="form-group">
			        <label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
			        <div class="col-lg-6">
			          <div class="m-b"> 
			          <select  style="width:260px" class="form-control" name="client" required>
			          <option value="<?=$project->client?>"><?=ucfirst($this->applib->company_details($project->client,'company_name'))?></option>
			            <?php
			            $clients = $this -> db -> where(array('co_id >' => 0)) -> get('companies') -> result();
			            if (!empty($clients)) {
			            foreach ($clients as $key => $c) { ?>
			            <option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
			            <?php }} ?>
			          </select> 
			          </div> 
			        </div>
			      </div>
				
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('start_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($project->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('due_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($project->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 
				<div class="form-group"> 
				<label class="col-lg-3 control-label"><?=lang('progress')?></label>
				<div class="col-lg-8"> 
					<input class="slider slider-horizontal form-control" type="text" value="<?=$project->progress?>" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?=$project->progress?>" data-slider-orientation="horizontal" name="progress" > 
				</div>
				</div> 

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">

				<select class="select2-option" multiple="multiple" style="width:260px" name="assign_to[]" > 
    			<optgroup label="Staff">
    			<?php 
    				$assign_to = $this -> db -> where(array('role_id !=' => 2)) -> get(Applib::$user_table) -> result();
    				if (!empty($assign_to)) {
                           foreach ($assign_to as $user): ?>
        <option value="<?=$user->id?>" <?php foreach (unserialize($project->assign_to) as $value) {
                           if ($user->id == $value) { ?> selected = "selected" <?php } else { } } ?>>
                           <?=ucfirst($user->username)?>
        </option>
                            <?php endforeach ?>
                            <?php } ?>	
					</optgroup> 
    			</select>
				</div>
				</div>

				<div class="form-group">
                      <label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" <?php if($project->fixed_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="fixed_rate" id="fixed_rate" >
                          <span></span>
                        </label>
                      </div>
                    </div>

				
				<div id="hourly_rate" <?php if($project->fixed_rate == 'Yes'){ echo "style=\"display:none\""; }?>>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->hourly_rate?>" name="hourly_rate">
				</div>
				</div>
				</div>
				<div id="fixed_price" <?php if($project->fixed_rate == 'No'){ echo "style=\"display:none\""; }?>>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->fixed_price?>" name="fixed_price">
				</div>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('estimated_hours')?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate_hours">
				</div>
				</div>	

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control" placeholder="About the project" required><?=$project->description?></textarea>
				</div>
				</div>
                         
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> <?=lang('save_changes')?></button>


				
		</form>
		<?php } } }?>
</div>
</section>
</div>


<!-- End create project -->