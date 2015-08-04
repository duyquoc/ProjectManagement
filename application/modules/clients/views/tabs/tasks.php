<header class="header b-b b-light hidden-print">
<a href="<?=base_url()?>clients/tasks/add/<?=$this->uri->segment(4)*1200?>"  data-toggle="ajaxModal" title="<?=lang('add_task')?>" class="btn btn-sm btn-info pull-right"><?=lang('request_task')?></a> 
 </header>



<ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border"> 
			<?php
				if (!empty($project_tasks)) {
				foreach ($project_tasks as $key => $task) { ?>
		<li class="list-group-item"> 


		<a href="#" class="thumb-sm pull-left m-r-sm"> <img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($task->added_by,'avatar')?>" class="img-circle"> </a>
			<a href="#" class="clear"> <small class="pull-right"><?php
								$today = time();
								$activity_day = strtotime($task->date_added) ;
								echo $this->user_profile->get_time_diff($today,$activity_day);
							?> <?=lang('ago')?></small> <strong class="block"><?=ucfirst($this->user_profile->get_profile_details($task->added_by,'fullname'))?></strong> 
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
<small class="pull-right"><?=lang('logged_hours')?>: <?=round($task->logged_time/3600,2)?> <?=lang('hours')?></small>



<div class="comment-action m-t-sm">
<?=lang('time_status')?> : 
<?php
if ($task->timer_status == 'On') { ?>
 
 <label class="label bg-danger"> <i class="fa fa-clock-o fa-spin text-white"></i> <?=lang('timer_started')?> </label>

<?php }else{ ?> 
 <i class="fa fa-clock-o text-muted text"></i> <?=lang('timer_off')?>

<?php } ?>
</div>


 						
		</li> 
		
				<?php } }else{		echo lang('nothing_to_display');	} ?>
	 
		</ul> 