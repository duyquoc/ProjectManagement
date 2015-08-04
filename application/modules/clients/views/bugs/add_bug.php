<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_bug')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'clients/bug_view/add',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('report_bug_message')?></p>
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_ref')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=random_string('nozero', 6);?>" name="issue_ref" readonly>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('project')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="project" class="form-control">
					<?php
					if (!empty($projects)) {
					foreach ($projects as $key => $project) { ?>
						<option value="<?=$project->project_id?>"><?=$project->project_title?></option>
					<?php } } ?>					
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('priority')?> </label>
				<div class="col-lg-8">
				<select name="priority" class="form-control">
					<option value="<?=lang('low')?>"><?=lang('low')?></option>
					<option value="<?=lang('medium')?>"><?=lang('medium')?></option>
					<option value="<?=lang('high')?>"><?=lang('high')?></option>
					<option value="<?=lang('critical')?>"><?=lang('critical')?></option>
				</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control">Issue Description</textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
		<button type="submit" class="btn btn-primary"><?=lang('report_issue')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->