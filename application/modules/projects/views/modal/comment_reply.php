<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('comment_reply')?></h4>
		</div>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/replies',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">
			 <input type="hidden" name="comment" value="<?=$comment?>">

				

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
				<div class="col-lg-9">
				<textarea name="message" class="form-control" placeholder="<?=lang('type_message')?>"></textarea>
				</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('post_reply')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->