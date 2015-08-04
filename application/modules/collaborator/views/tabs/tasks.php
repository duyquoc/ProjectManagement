<header class="header b-b b-light hidden-print">
<a href="<?=base_url()?>collaborator/tasks/add/<?=$this->uri->segment(4)*1200?>"  data-toggle="ajaxModal" title="<?=lang('add_task')?>" class="btn btn-sm btn-info pull-right"><?=lang('add_task')?></a> 
 </header>



<ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border"> 
			<?php
				if (!empty($project_tasks)) {
				foreach ($project_tasks as $key => $task) { ?>
		<li class="list-group-item"> 


		<a href="#" class="thumb-sm pull-left m-r-sm"> <img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($task->added_by,'avatar')?>" class="img-circle"> </a>
			<a href="<?=base_url()?>collaborator/tasks/edit/<?=$task->t_id?>" data-toggle="ajaxModal" title="<?=lang('edit_task')?>" class="clear"> <small class="pull-right"><?php
								$today = time();
								$activity_day = strtotime($task->date_added) ;
								echo $this->user_profile->get_time_diff($today,$activity_day);
							?> <?=lang('ago')?></small> <strong class="block"><?=ucfirst($this->user_profile->get_user_details($task->added_by,'username'))?></strong> 
							<?php
							if ($task->auto_progress == 'FALSE') {
								$progress = $task->task_progress;
							}else{
								$progress = round((($task->logged_time/3600)/$task->estimated_hours)*100,2);
							} ?>
							<small <?php if($progress == '100'){ ?>class="clear text-danger text-lt" id="todo-1" <?php } ?> ><?=$task->task_name?></small>
							<div class="progress progress-xs progress-striped active">							
							
							<?php if ($progress >= 100) { $bg = 'success'; }else{ $bg = 'danger'; } ?>
			<div class="progress-bar progress-bar-<?=$bg?>" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%">
			</div>
			</div>  </a>

<div><?=$task->description?>
</div> 
<small class="pull-right"><?=lang('estimate')?>: <?=$task->estimated_hours?> <?=lang('hours')?></small>



<div class="comment-action m-t-sm">
<?php
if ($task->auto_progress == 'FALSE') { ?>

	<a href="<?=base_url()?>collaborator/tasks/pilot/on/<?=$task->t_id?>/<?=$task->project*8600?>"  data-original-title="<?=lang('auto_progress_on')?>" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top"> <i class="fa fa-rocket text-white"></i></a>
<?php }else{ ?>
<a href="<?=base_url()?>collaborator/tasks/pilot/off/<?=$task->t_id?>/<?=$task->project*8600?>"  data-original-title="<?=lang('auto_progress_off')?>" class="btn btn-xs btn-dark" data-toggle="tooltip" data-placement="top"> <i class="fa fa-power-off text-white"></i></a>
<?php } ?> 

<?php
if ($task->timer_status == 'On') { ?>
 <a href="<?=base_url()?>collaborator/tasks/tracking/off/<?=$task->project?>/<?=$task->t_id?>" class="btn btn-danger btn-xs active"> <i class="fa fa-clock-o text-muted text"></i> <i class="fa fa-clock-o text-white text-active"></i> <?=lang('stop_timer')?> </a> 
 <a href="#" class="btn btn-default btn-xs"> <?=round($task->logged_time/3600,2)?> <?=lang('hours')?> </a> 

<?php }else{ ?>
 <a href="<?=base_url()?>collaborator/tasks/tracking/on/<?=$task->project?>/<?=$task->t_id?>" class="btn btn-success btn-xs active"> <i class="fa fa-clock-o text-muted text"></i> <i class="fa fa-clock-o text-white text-active"></i> <?=lang('start_timer')?> </a> <a href="#" class="btn btn-default btn-xs"> <?=round($task->logged_time/3600,2)?> <?=lang('hours')?> </a> 

<?php } ?>
</div>


 						
		</li> 
		
				<?php } }else{		echo lang('nothing_to_display');	} ?>
	 
		</ul> 