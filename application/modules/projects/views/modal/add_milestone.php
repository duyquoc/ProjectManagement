<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_milestone')?></h4>
		</div>
<style>
.datepicker{z-index:1151 !important;}
</style>
					<?php
			$role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->tank_auth->get_user_id()),'role_id');
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/milestones/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
		<?php if($role == '1'){ ?>
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('milestone_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="Milestone Name" name="milestone_name">
				</div>
				</div>
<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> </label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control" placeholder="<?=lang('description')?>"></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('start_date')?> </label>
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >

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
		<button type="submit" class="btn btn-primary"><?=lang('add_milestone')?></button>
		</form>
		</div>
	</div>

<script type="text/javascript">
        $('.datepicker-input').datepicker({ language: locale });
</script>



	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->