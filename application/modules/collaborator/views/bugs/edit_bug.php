<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('edit_bugs')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'collaborator/bug_view/edit',$attributes); ?>
          <?php
								if (!empty($bug_details)) {
				foreach ($bug_details as $key => $bug) { ?>
		<div class="modal-body">
			<p><?=lang('report_bug_message')?></p>
			 <input type="hidden" name="bug_id" value="<?=$bug->bug_id?>">
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_ref')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$bug->issue_ref?>" name="issue_ref" readonly>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('project')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="project" class="form-control">
				<option value="<?=$bug->project?>">Use Current</option>
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
					<option value="<?=$bug->priority?>">Use Current</option>
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
				<textarea name="description" class="form-control"><?=$bug->bug_description?></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
		<button type="submit" class="btn btn-primary"><?=lang('report_issue')?></button>
		</form>
		<?php }} ?>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->