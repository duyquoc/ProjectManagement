<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_link')?></h4>
		</div>
<style>
.datepicker{z-index:1151 !important;}
</style>
                <?php
                $role = Applib::login_info($this->tank_auth->get_user_id())->role_id;
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'projects/links/add',$attributes); ?>
                <input type="hidden" name="project_id" value="<?=$project_id?>">
                <div class="modal-body">
                    
                <?php if($role == '1'){ ?>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_url')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                            <input type="text" class="form-control" required placeholder="http://" name="link_url">
                            
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_title')?> </label>
                    <div class="col-lg-8">
                            <input type="text" class="form-control" placeholder="Link Title" name="link_title">
                            <small class="block small text-muted"><?=lang('add_link_auto')?></small>
                    </div>
                    </div>
                <?php } ?>
                    <div class="form-group">
                    <label class="col-lg-4 control-label"><?=lang('description')?> </label>
                    <div class="col-lg-8">
                        <textarea name="description" class="form-control" placeholder="<?=lang('description')?>"></textarea>
                    </div>
                    </div>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-primary"><?=lang('add_link')?></button>
		</form>
		</div>
	</div>

	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->