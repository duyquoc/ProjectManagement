<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_file')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/files/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project_id?>">
          <input type="hidden" name="file_id" value="<?=$file_id?>">
		<div class="modal-body">
                    <?php foreach ($file_details as $f) : ?>
                    <?php   $icon = $this->applib->file_icon($f->ext); 
                            $real_url = base_url().$file_path; ?>
                    
                <div class="form-group">
                    <div class="col-lg-3">
                    <?php if ($f->is_image == 1) : ?>
                    <?php if ($f->image_width > $f->image_height) {
                        $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                        $style = 'height:100%; margin-left: -'.$ratio.'%';
                    } else {
                        $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                        $style = 'width:100%; margin-top: -'.$ratio.'%';
                    }  ?>
                        <div class="file-icon icon-large pull-right">
                            <a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal">
                            <img style="<?=$style?>" src="<?=$real_url?>" /></a>
                        </div>
                    <?php else : ?>
                        <div class="file-icon icon-large pull-right"><i class="fa <?=$icon?> fa-5x"></i></div>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-9">
                    <table class="table table-striped table-small">
                        <tbody>
                            <tr><td class="col-lg-3"><?=lang('file_name');?></td><td><?=$f->file_name;?></td></tr>
                            <tr><td><?=lang('size');?></td><td><?=$f->size;?></td></tr>
                            <?php if($f->is_image == 1) : ?>
                            <tr><td><?=lang('dimensions');?></td><td><?=$f->image_width;?>x<?=$f->image_height;?></td></tr>
                            <?php endif; ?>
                            <tr><td><?=lang('date');?></td><td><?=strftime(config_item('date_format')." %H:%M", strtotime($f->date_posted));?></td></tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" value="<?=$f->title;?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control" ><?=$f->description;?></textarea>
                    </div>
                </div>
                    <?php endforeach; ?>
		<div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
                    <button type="submit" class="btn btn-primary"><?=lang('save_file')?></button>
		</form>
		</div>
	        </div>
        </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->