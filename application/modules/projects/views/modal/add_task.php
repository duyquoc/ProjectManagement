<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
	<style>
.datepicker{z-index:1151 !important;}
</style>	
					<?php
			$role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->tank_auth->get_user_id()),'role_id');
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/tasks/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input id="hidden-task-name" type="hidden" name="task_name">
					<input id="auto-task-name" type="text" class="typeahead form-control" placeholder="Task Name" name="task_name_auto">
				</div>
				</div>
<?php if($role != 2){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone')?></label>
				<div class="col-lg-8">
				<select name="milestone" class="form-control">
				<option value="0"><?=lang('none')?></option>
				<?php 
				$milestones = $this -> db-> where('project',$project) -> get('milestones') -> result();
				if (!empty($milestones)) {
					foreach ($milestones as $m) { ?>
						<option value="<?=$m->id?>"><?=$m->milestone_name?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?></label>
				<div class="col-lg-8">
				<textarea id="auto-description" name="description" class="form-control" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>

<?php if($role != 2){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('progress')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="progress" class="form-control">
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
<?php } ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_date')?> </label>
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> </label>
				<div class="col-lg-8">
					<input id="auto-estimate" type="text" class="form-control" placeholder="100" name="estimate">
				</div>
				</div>

<?php if($role != 2){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control" required>
				<option value="-"><?=lang('not_assigned')?></option>
				<?php 
				$assign_to =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project),'assign_to');
				if (!empty($assign_to)) {
					foreach (unserialize($assign_to) as $value) { ?>
						<option value="<?=$value?>"><?=ucfirst($this->user_profile->get_profile_details($value,'fullname'))?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>
<?php } ?>

<?php if($role != '2'){ ?>
				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('visible_to_client')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="visible">
                          <span></span>
                        </label>
                      </div>
                    </div>


				
<?php } ?>
				

				

				
			
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
    
    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var matches, substringRegex;
        matches = array();
        substrRegex = new RegExp(q, 'i');
        $.each(strs, function(i, str) {
          if (substrRegex.test(str)) {
            matches.push(str);
          }
        });
        cb(matches);
      };
    };

    $('#auto-task-name').on('keyup',function(){ $('#hidden-task-name').val($(this).val()); });

    $.ajax({
        url: base_url + 'projects/tasks/autotasks/',
        type: "POST",
        data: {},
        success: function(response){
            $('.typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 2
                },
                {
                name: "task_name",
                limit: 10,
                source: substringMatcher(response)
            });
            $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                $.ajax({
                    url: base_url + 'projects/tasks/autotask/',
                    type: "POST",
                    data: {name: suggestion},
                    success: function(response){
                        $('#hidden-task-name').val(response.task_name);
                        $('#auto-description').val(response.description);
                        $('#auto-estimate').val(response.hours);
                    }
                });
            });            
        }
    });
    </script>