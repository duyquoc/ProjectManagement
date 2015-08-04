<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('upload_file')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'clients/bug_files/add',$attributes); ?>
          <input type="hidden" name="bug" value="<?=$bug?>">
          <input type="hidden" name="issue_ref" value="<?=$issue_ref?>">
          <input type="hidden" name="assigned_to" value="<?=$assigned_to?>">

		<div class="modal-body">
			<p><?=lang('email_sending_warning')?></p>
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('file_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="file" name="userfile">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control"><?=lang('description')?></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success"><?=lang('upload_file')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->