<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('time_entry')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/timelog',$attributes); ?>
          <?php
				if (!empty($project_details)) {
				foreach ($project_details as $key => $p) { ?>
          <input type="hidden" name="project" value="<?=$p->project_id?>">
		<div class="modal-body">
			<p><?=lang('log_time_manually')?></p>

			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('project_name')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$p->project_title?>" readonly="">
				</div>
				</div> 

          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('hours_spent')?> (<?=lang('hours')?>) <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="100" name="logged_time">
				</div>
				</div>

				

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('currently_logged_time')?> (<?=lang('hours')?>)</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=round($logged_time/3600,2)?>" readonly="">
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
		<?php }} ?>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->