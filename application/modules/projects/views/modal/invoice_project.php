<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-primary" >
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('invoice_project')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/invoice',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
		<?php
$project_title =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project),'project_title');
		?>
			<p>Project <strong> <?=$project_title?> </strong> will be converted to an Invoice.</p>
          		
				

			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('invoice_project')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->