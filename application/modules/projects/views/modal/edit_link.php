<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_link')?></h4>
		</div>
		<?php 
		$role = Applib::get_table_field(Applib::$user_table,array('id'=>$this->tank_auth->get_user_id()),'role_id');
		if($role == '1'){ ?>

		<?php 
			if (!empty($details)) {
			foreach ($details as $key => $link) { 
			
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/links/edit',$attributes); ?>
          <input type="hidden" name="project_id" value="<?=$link->project_id?>">
          <input type="hidden" name="link_id" value="<?=$link->link_id?>">
		<div class="modal-body">
		
          		<div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_title')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->link_title?>" name="link_title">
                        </div>
                        </div>
          		<div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_url')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->link_url?>" name="link_url">
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('description')?> </label>
                        <div class="col-lg-8">
                        <textarea name="description" class="form-control"><?=$link->description?></textarea>
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('username')?></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->username?>" name="username">
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('password')?></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->password?>" name="password">
                        </div>
                        </div>
				
<?php 
		} 
	} 
?>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('edit_link')?></button>
		</form>
		<?php } ?>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->