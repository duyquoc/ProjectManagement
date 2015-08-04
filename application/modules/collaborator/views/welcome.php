<section id="content">
	<section class="vbox">
		<section class="scrollable padder">
			<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
				<small><?=lang('welcome_back')?> ,
				<?php
				$user_id = $this->tank_auth->get_user_id();
				$names = Applib::profile_info($user_id)->fullname ? Applib::profile_info($user_id)->fullname : $this->tank_auth->get_username();
				echo $names ?> </small>
			</ul>
			
			<section class="panel panel-default">
				<div class="row m-l-none m-r-none bg-dark lter">
					<div class="col-sm-6 col-md-3 padder-v b-r b-light">
						<a class="clear" href="<?=base_url()?>projects">
							<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-danger"></i> <i class="fa fa-coffee fa-stack-1x text-white"></i>
							</span>
							<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('assign_projects',array('assigned_user'=>$user_id))?> </strong>
						</span> <small class="text-muted text-uc"><?=lang('assigned_projects')?> </small> </a>
					</div>
					<div class="col-sm-6 col-md-3 padder-v b-r b-light">
						<a class="clear" href="<?=base_url()?>collaborator/messages">
							<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-info"></i> <i class="fa fa-envelope fa-stack-1x text-white"></i>
							</span>
							<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('messages',array('user_to'=>$user_id,'deleted'=>'No'))?> </strong>
						</span> <small class="text-muted text-uc"><?=lang('messages')?>  </small> </a>
					</div>
					<div class="col-sm-6 col-md-3 padder-v b-r b-light">
						<a class="clear" href="<?=base_url()?>tickets">
							<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-warning"></i> <i class="fa fa-ticket fa-stack-1x text-white"></i>
							</span>
							<?php
							$dept = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'department');
							?>
							<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('tickets',array('department'=>$dept))?>  </strong></span>
						<small class="text-muted text-uc"><?=lang('tickets')?>  </small> </a>
					</div>
					<div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
						<a class="clear" href="<?=base_url()?>profile/activities">
							<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-success"></i> <i class="fa fa-calendar-o fa-stack-1x text-white"></i>
							</span>
							<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('activities',array('user'=>$user_id))?> </strong>
						</span> <small class="text-muted text-uc"><?=lang('activities')?>  </small> </a>
					</div>
				</div> </section>
				<div class="row">
					<div class="col-md-8">
						<section class="panel panel-default">
						<header class="panel-heading font-bold"> <?=lang('recent_projects')?></header>
						<div class="panel-body">
							
							<table class="table table-striped m-b-none text-sm">
								<thead>
									<tr>
										<th><?=lang('progress')?></th>
										<th><?=lang('project_name')?> </th>
										<th class="col-options no-sort"><?=lang('options')?></th>
									</tr> </thead>
									<tbody>
										
										<?php
										if (!empty($projects)) {
										foreach ($projects as $key => $project) { ?>
										<tr>
											<?php
											if ($project->auto_progress == 'FALSE') {
											$progress = $project->progress;
											}else{
											$progress = round((($project->time_logged/3600)/$project->estimate_hours)*100,2);
											} ?>
											<td>
												<?php if ($progress >= 100) { $bg = 'success'; }else{ $bg = 'danger'; } ?>
												<div class="progress progress-xs progress-striped active">
													<div class="progress-bar progress-bar-<?=$bg?>" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%">
													</div>
												</div>
											</td>
											<td><?=$project->project_title?> </td>
											<td>
												<a class="btn  btn-success btn-xs" href="<?=base_url()?>projects/view/<?=$project->project_id?>">
												<i class="fa fa-suitcase text"></i> <?=lang('project')?></a>
											</td>
										</tr>
										<?php }
										}else{ ?>
										<tr>
											<td><?=lang('nothing_to_display')?></td><td></td><td></td>
										</tr>
										<?php } ?>
										
										
									</tbody>
								</table>
							</div> <footer class="panel-footer bg-white no-padder">
							<div class="row text-center no-gutter">
								<div class="col-xs-3 b-r b-light">
									<span class="h4 font-bold m-t block">
									<?=$this->user_profile->count_rows('bugs',array('reporter'=>$user_id))?>
									</span> <small class="text-muted m-b block"><?=lang('reported_bugs')?></small>
								</div>
								<div class="col-xs-3 b-r b-light">
									<span class="h4 font-bold m-t block">
									<?=$this->user_profile->count_rows('projects',array('progress >='=>'100','assign_to'=>$user_id))?>
									</span> <small class="text-muted m-b block"><?=lang('complete_projects')?></small>
								</div>
								<div class="col-xs-3 b-r b-light">
									<span class="h4 font-bold m-t block">
									<?=$this->user_profile->count_rows('messages',array('user_to'=>$user_id,'status'=>'Unread'))?>
									</span> <small class="text-muted m-b block"><?=lang('unread_messages')?></small>
								</div>
								<div class="col-xs-3">
									<span class="h4 font-bold m-t block">
									<?=$this->user_profile->count_rows('comments',array('posted_by'=>$user_id))?>
									</span> <small class="text-muted m-b block"><?=lang('project_comments')?></small>
								</div>
							</div> </footer>
						</section>
					</div>
					
					<div class="col-lg-4">
						<section class="panel panel-default">
							<div class="panel-body">
								<div class="clearfix text-center m-t">
									<div class="inline">
										<div style="width: 130px; height: 130px; line-height: 130px;" class="easypiechart easyPieChart" data-percent="100" data-line-width="5" data-bar-color="#FB6B5B" data-track-color="#f5f5f5" data-scale-color="false" data-size="130" data-line-cap="butt" data-animate="1000">
											<div class="thumb-lg">
												<?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'use_gravatar') == 'Y'){
												$user_email = Applib::login_info($user_id)->email; ?>
												<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
												<?php }else{ ?>
												<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($user_id,'avatar')?>" class="img-circle">
												<?php } ?>
												
											</div>
										<canvas width="130" height="130"></canvas></div>
										<div class="h4 m-t m-b-xs"><?=$names?></div>
										<?php
										$deptid = Applib::get_table_field(Applib::$profile_table,
													array('user_id'=>$user_id),'department');
										$deptname = '';
										if($deptid > 0){
											$deptname = Applib::get_table_field(Applib::$departments_table,
													array('deptid'=>$deptid),'deptname');
										}

										

										$project_timers = $this -> db -> where('user',$user_id)
																-> get(Applib::$project_timer_table) 
																-> result();
										$task_timers = $this -> db -> where('user',$user_id)
																-> get('tasks_timer') 
																-> result();

										$project_hours[] = array();
										$task_hours[] = array();
												foreach ($project_timers as $key => $p_elapsed) {
														$project_hours[] = round(($p_elapsed -> end_time - $p_elapsed -> start_time)/3600,2);
												}
												if(is_array($project_hours)){
																$total_project_hours = array_sum($project_hours); }
														else{
																$total_project_hours = 0;
																		}
															foreach ($task_timers as $key => $t_elapsed) {
														$task_hours[] = round(($t_elapsed -> end_time - $t_elapsed -> start_time)/3600,2);
												}
												if(is_array($task_hours)){
																$total_task_hours = array_sum($task_hours); }
														else{
																$total_task_hours = 0;
																		}
										?>
										<small class="text-muted m-b"><?=$deptname?></small>
									</div>
								</div>
							</div>
							<footer class="panel-footer bg-danger lter text-center">
								<div class="row pull-out">
									<div class="col-xs-6 dk">
										<div class="padder-v">
											<span class="m-b-xs h3 block text-white"><?=$total_project_hours?></span>
											<small class="text-muted"><?=lang('project_hours')?></small>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="padder-v">
											<span class="m-b-xs h3 block text-white"><?=$total_task_hours?></span>
											<small class="text-muted"><?=lang('task_hours')?></small>
										</div>
									</div>
								</div>
							</footer>
						</section>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<section class="panel panel-default b-light">
						<header class="panel-heading"><?=lang('recent_tasks')?></header>
						<div class="panel-body">
							
							
							<div class="list-group bg-white">
								<?php
													if (!empty($tasks_assigned)) {
								foreach ($tasks_assigned as $key => $task) { ?>
								<a href="<?=base_url()?>projects/view/<?=$task->project?>?group=tasks&view=task&id=<?=$task->t_id?>" class="list-group-item">
									<?=$task->task_name?> - <small class="text-muted"><?=$this->applib->get_project_details($task->project,'project_title')?></small>
								</a>
								<?php } } ?>
							</div>
						</div>
					</section>
				</div>
				<div class="col-md-4">
					<section class="panel panel-default b-light">
						<div class="panel-body">
							<section class="comment-list block">
								<?php
													if (!empty($activities)) {
								foreach ($activities as $key => $activity) { ?>
								<article id="comment-id-1" class="comment-item">
									<span class="fa-stack pull-left m-l-xs">
									<a class="pull-left thumb-sm">
										<?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$activity->user),'use_gravatar') == 'Y'){
										$user_email = Applib::login_info($activity->user)->email; ?>
										<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
										<?php }else{ ?>
										<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($activity->user,'avatar')?>" class="img-circle">
										<?php } ?>
									</a>
									</span>
									<section class="comment-body m-b-lg">
										<header> <a href="#"><strong>
										<?=
										Applib::profile_info($activity->user)->fullname 
										? Applib::profile_info($activity->user)->fullname
										: Applib::login_info($activity->user)->username
										?></strong></a>
										<span class="text-muted text-xs"> <?php
												$today = time();
												$activity_day = strtotime($activity->activity_date) ;
												echo $this->user_profile->get_time_diff($today,$activity_day);
									?> <?=lang('ago')?></span> 
									</header>
									<div>
                        <?php
                            if (lang($activity->activity) != '') {
                                if (!empty($activity->value1)) {
                                    if (!empty($activity->value2)){
                                        echo sprintf(lang($activity->activity), '<em>'.$activity->value1.'</em>', '<em>'.$activity->value2.'</em>');
                                        } else {
                                        echo sprintf(lang($activity->activity), '<em>'.$activity->value1.'</em>');
                                        }
                                    } else { echo lang($activity->activity); }
                                } else { echo $activity->activity; } 
                            ?>
                                                </div>
								</section>
							</article>
							<?php }}else{
								echo lang('no_activity_found');
							} ?>
							
						</section>
					</div>
					
				</section>
			</div>
		</div>
	</section>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>