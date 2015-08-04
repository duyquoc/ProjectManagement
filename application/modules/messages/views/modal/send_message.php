<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">New Message</h4>
		</div>
		<div class="modal-body">
			<p>We'll send a copy of your message via email to the recipient</p>
			 <?php

			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'messages/conversation/send',$attributes); ?>

          <input type="hidden" name="r_url" value="<?=base_url()?>messages">
          <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('username')?></label>
				<div class="col-lg-9">
					<div class="m-b"> 
					<select class="select2-option" style="width:260px" name="user_to" > 
					<optgroup label="<?=lang('clients')?>"> 
					<?php foreach ($clients as $client): ?>
					<option value="<?=$client->id?>"><?=ucfirst($client->username)?></option>
					<?php endforeach; ?>
					</optgroup> 
					<optgroup label="<?=lang('administrators')?>"> 
						<?php foreach ($admins as $admin): ?>
						<option value="<?=$admin->id?>"><?=ucfirst($admin->username)?></option>
						<?php endforeach; ?>
					</optgroup> 
					</select> 
					</div> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('message')?> <span class="text-danger">*</span></label> 
				<div class="col-lg-9">
					<textarea name="message" class="form-control" ></textarea>
				</div>
			</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
		<button type="submit" class="btn btn-primary">Send Message</button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->