<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('time_entry')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/timesheet/edit',$attributes);
          if (!empty($info)) {
              foreach ($info as $key => $i) { ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="cat" value="<?=$cat?>">
          <input type="hidden" name="timer_id" value="<?=$timer_id?>">
		<div class="modal-body">
		<?php
		if ($cat == 'tasks') { ?>
			<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php 
					$tasks = $this -> db -> where(array('project'=>$project)) -> get('tasks') -> result();
				?>
				<select name="task" class="form-control">
				<option value="<?=$i->task?>"><?=$this -> applib->get_any_field('tasks',array('t_id'=>$i->task),'task_name')?></option>
				<?php if (!empty($tasks)) {
              foreach ($tasks as $key => $t) {  ?>
					<option value="<?=$t->t_id?>"><?=$t->task_name?></option>
				<?php } } ?>
				</select>
				</div>
				</div>
		<?php } ?>

		<?php
		$start_time = date('d-m-Y H:i',$i->start_time);
		$end_time = date('d-m-Y H:i',$i->end_time);
		?>
				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('start_time')?></label>
                      <div class="col-sm-8">
                        <input type="text" class="combodate form-control" data-format="DD-MM-YYYY HH:mm" data-template="D  MMM  YYYY  -  HH : mm" name="start_time" value="<?=$start_time?>">
                      </div>

                    </div>


          		<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('stop_time')?></label>
                      <div class="col-sm-8">
                        <input type="text" class="combodate form-control" data-format="DD-MM-YYYY HH:mm" data-template="D  MMM  YYYY  -  HH : mm" name="end_time" value="<?=$end_time?>">
                      </div>

                    </div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('time_entry')?></button>
		<?php } } ?>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<!-- /.modal-dialog -->
<script src="<?=base_url()?>resource/js/libs/moment.min.js"></script>
<script src="<?=base_url()?>resource/js/combodate/combodate.js"></script>
<script type="text/javascript">
	$(function(){
		$('.combodate').combodate();
	});
</script>

