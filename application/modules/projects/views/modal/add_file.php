<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('upload_file')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/files/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" required placeholder="<?=lang('file_title')?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control" placeholder="<?=lang('description')?>" ></textarea>
                    </div>
                </div>
                    
                <div id="file_container">
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <input type="file" name="projectfiles[]">
                        </div>
                    </div>
                </div>

		<div class="modal-footer">
                    <a href="#" class="btn btn-primary pull-left" id="add-new-file"><?=lang('upload_another_file')?></a>
                    <a href="#" class="btn btn-default pull-left" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
                    <button type="submit" class="btn btn-primary"><?=lang('upload_file')?></button>
		</form>
		</div>
	        </div>
        </div>
  
    <script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<input type='file' name='projectfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<input type='file' name='projectfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->