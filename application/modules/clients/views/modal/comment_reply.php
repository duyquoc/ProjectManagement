<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('comment_reply')?></h4>
		</div>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'clients/projects/replies',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('comment_email_notification')?></p>
			 <input type="hidden" name="project" value="<?=$project?>">
			 <input type="hidden" name="comment" value="<?=$comment?>">

				

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="message" class="form-control" placeholder="Type your Message"></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success"><?=lang('post_reply')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->