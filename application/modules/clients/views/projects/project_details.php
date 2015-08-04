<section id="content">
	<section class="vbox">
		<?php
						if (!empty($project_details)) {
		foreach ($project_details as $key => $project) { ?>
		<header class="header bg-white b-b clearfix hidden-print">
			<div class="row m-t-sm">
				<div class="col-sm-8 m-b-xs">
					<a href="<?=base_url()?>clients/projects/" class="btn btn-xs btn-dark lter">&laquo; Back</a> Project Title : <strong><?=$project->project_title?></strong>
				</div>
				<div class="col-sm-4 m-b-xs pull-right">
					<a href="<?=base_url()?>clients/bug_view/add" data-toggle="ajaxModal" class="btn btn-sm btn-dark "> <i class="fa fa-bug text-white"></i> <?=lang('new_bug')?></a>
					<a href="<?=base_url()?>clients/files/add/<?=$project->project_id * 1200?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark "> <i class="fa fa-upload text-white"></i> <?=lang('upload_file')?></a>
				</div>
			</div>
		</header>
		<section class="scrollable">
			<section class="hbox stretch">
				<aside class="aside-lg bg-light lter b-r">
					<section class="vbox">
						<section class="scrollable w-f">
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
								<div class="wrapper">
									<!-- Start -->
									<section class="panel panel-default">
									<header class="panel-heading"><?=lang('task_status')?></header>
									<div class="panel-body text-center">
										<?php
										$total_tasks = $this->user_profile->count_rows('tasks',array('project' => $project->project_id));
										$open_tasks = $this->user_profile->count_rows('tasks',array('project' => $project->project_id,'task_progress <'=>'100'));
										$closed_tasks = $this->user_profile->count_rows('tasks',array('project' => $project->project_id,'task_progress'=>'100'));
										if ($closed_tasks > 0 OR $open_tasks >0) {
										$perc_closed_tasks = round(($closed_tasks/$total_tasks)*100,1);
										$perc_open_tasks = round(($open_tasks/$total_tasks)*100,1);
										}else{
											$perc_closed_tasks = 0;
											$perc_open_tasks = 0;
										}
										?>
										<div class="sparkline inline" data-type="pie" data-height="150" data-slice-colors="['#8EC165','#FB6B5B']"><?=$perc_open_tasks?>,<?=$perc_closed_tasks ?></div>
										<div class="line pull-in"></div>
										<div class="text-xs"> <i class="fa fa-circle text-success"></i> <?=$perc_open_tasks?>% <?=lang('open')?> <i class="fa fa-circle text-danger"></i> <?=$perc_closed_tasks?>% <?=lang('closed')?></div>
									</div> </section>
									<!-- end -->
									
									<div class="panel wrapper panel-success">
										<?php
											$project_hours = $this->user_profile->project_hours($project->project_id);
											$hours_spent = round($project_hours, 1);
											$fix_rate = $this->user_profile->get_project_details($project->project_id,'fixed_rate');
											$hourly_rate = $this->user_profile->get_project_details($project->project_id,'hourly_rate');
											if ($fix_rate == 'No') {
												$cost = $hours_spent * $hourly_rate;
											}else{
												$cost = $this->user_profile->get_project_details($project->project_id,'fixed_price');
											}
										?>
										<div class="row">
											<div class="col-xs-6"> <a href="#"><?=lang('hours_spent')?>
												<span class="m-b-xs h4 block"></span>
											<small class="text-muted"><strong><?php echo $hours_spent?></strong></small> </a>
										</div>
										<div class="col-xs-6"> <a href="#"><?=lang('project_cost')?>
											<span class="m-b-xs h4 block"></span>
											<small class="text-muted">
                                                                                        <?php $cur = $this->applib->client_currency($project->client); ?>
										<strong><?=$cur->symbol?> <?=number_format($cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small> </a>
									</div>
								</div>
							</div>
							<div>
								
								<div class="progress progress-xs progress-striped active">
									<?php
									$task_time = $this->user_profile->get_sum('tasks','logged_time',array('project'=>$project->project_id));
									$project_time = $this->user_profile->get_sum('projects','time_logged',array('project_id'=>$project->project_id));
									$logged_time = ($task_time + $project_time)/3600;
									if ($logged_time > 0 AND $project->estimate_hours > 0) {
									$auto_calculated_progress = ($logged_time/$project->estimate_hours)*100;
									}else{
									$auto_calculated_progress = 0;
									}
															if ($project->auto_progress == 'FALSE') {
									$progress = $project->progress;
									}elseif($auto_calculated_progress >= 100){
									$progress = 100;
									}else{
									$progress = round($auto_calculated_progress,2);
									} ?>
									<div class="progress-bar progress-bar-info" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%">
									</div>
								</div>
							</div>
							<small class="text-muted"><?=lang('start_date')?>: <?=strftime(config_item('date_format'), strtotime($project->start_date))?></small><br>
							<small class="text-muted"><?=lang('due_date')?>: <?=strftime(config_item('date_format'), strtotime($project->due_date))?></small><br>
							<footer class="panel-footer bg-dark text-center">
								<div class="row pull-out">
									<div class="col-xs-6">
										<div class="padder-v">
											<span class="m-b-xs h4 block text-white">
											<?=$this->user_profile->count_rows('comments',$array = array(
											'project' => $this->uri->segment(4),
											'deleted' => 'No'
											)); ?>
											</span> <small class="text-muted"><?=lang('comments')?></small>
										</div>
									</div>
									<div class="col-xs-6 dk">
										<div class="padder-v">
											<span class="m-b-xs h4 block text-white">
											<?=$this->user_profile->count_rows('bugs',array('project' => $this->uri->segment(4)))?>
											</span> <small class="text-muted"><?=lang('bugs')?></small>
										</div>
									</div>
								</div> </footer>
								
							</div> </div></section> </section> </aside>
							<aside class="bg-white">
								<section class="vbox">
									
									<section class="scrollable w-f">
										<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
											<!-- Start Tabs -->
											<section class="panel panel-default">
												<div class="panel-body">
													<?php
													if ($this->session->flashdata('message') == FALSE AND strtotime($project->due_date) < time()) { ?>
													<div class="alert alert-danger">
														<button type="button" class="close" data-dismiss="alert">×</button>
														<i class="fa fa-info-sign"></i><?=lang('project_deadline_reached')?>
													</div>
													<?php } ?>
													<ul class="nav nav-tabs" id="stats">
														
														<li class="active"><a href="#tasks" id="tasks_tab" data-toggle="tab"> <?=lang('tasks')?> </a></li>
														<li><a href="<?=base_url()?>clients/tabs/files/<?=$project->project_id?>" data-target="#files" class="media_node span" id="files_tab" data-toggle="tabajax" rel="tooltip"> <?=lang('files')?></a></li>
														
														<li><a href="<?=base_url()?>clients/tabs/timesheet/<?=$project->project_id?>" data-target="#timesheet" class="media_node span" id="timesheet_tab" data-toggle="tabajax" rel="tooltip"><?=lang('timesheets')?></a></li>
														<li><a href="<?=base_url()?>clients/tabs/bugs/<?=$project->project_id?>" data-target="#bugs" class="media_node span" id="activities_tab" data-toggle="tabajax" rel="tooltip"><?=lang('bugs')?></a></li>
														
													</ul>
													<div class="tab-content">
														<div class="tab-pane active" id="tasks">
														<?php echo modules::run('clients/tabs/tasks');?></div>
														<div class="tab-pane" id="files"></div>
														<div class="tab-pane" id="timesheet"></div>
														<div class="tab-pane" id="bugs"></div>
														
													</div>
												</div>
											</section>
											<!-- End Tabs -->
										</div>
									</section>
								</section>
							</aside>
							<aside class="col-lg-4 b-l">
								<section class="vbox">
									<section class="scrollable w-f">
										<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
											<div class="col-lg-12">
												<h4 class="font-thin padder"><?=lang('latest_comments')?></h4>
												<!-- .comment-list -->
												<section class="comment-list block">
													<?php
															if (!empty($project_comments)) {
													foreach ($project_comments as $key => $comment) { ?>
													<article id="comment-id-1" class="comment-item">
														<a class="pull-left thumb-sm avatar">
														<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($comment->posted_by,'avatar')?>" class="img-circle"> </a>
														<span class="arrow left"></span>
														<section class="comment-body panel panel-default">
															<header class="panel-heading bg-white">
																<a href="#"><?=ucfirst($this->user_profile->get_profile_details($comment->posted_by,'fullname')?$this->user_profile->get_profile_details($comment->posted_by,'fullname'):$this->user_profile->get_user_details($comment->posted_by,'username'))?></a>
																<?php if($comment->posted_by == $this->tank_auth->get_user_id()){ ?><label class="label bg-light dker m-l-xs"><?=lang('you')?></label> <?php } ?>
																<span class="text-muted m-l-sm pull-right"> <i class="fa fa-clock-o"></i> <?php
																					$today = time();
																					$comment_day = strtotime($comment->date_posted) ;
																					echo $this->user_profile->get_time_diff($today,$comment_day);
															?> <?=lang('ago')?> </span> </header>
															<div class="panel-body">
																<div><small><?=$comment->message?></small></div>
																<div class="comment-action m-t-sm">
																	<?php
																	if ($comment->posted_by != $this->tank_auth->get_user_id()) { ?>
																	
																	<a href="#comment-form" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="<?=lang('comment')?>" title="<?=lang('comment')?>"> <i class="fa fa-comment text-muted"></i>  </a>
																	<?php } ?>
																	<a href="<?=base_url()?>clients/projects/replies?c=<?=$comment->comment_id?>&p=<?=$project->project_id?>" data-toggle="ajaxModal" title="<?=lang('reply')?>"  class="btn btn-default btn-xs"> <i class="fa fa-mail-reply text-muted"></i> </a>
																	<?php
																	if ($comment->posted_by == $this->tank_auth->get_user_id()) { ?>
																	<a href="<?=base_url()?>clients/projects/delcomment?c=<?=$comment->comment_id?>&p=<?=$project->project_id?>" data-toggle="ajaxModal" title="<?=lang('delete')?>"  class="btn btn-danger btn-xs"> <i class="fa fa-trash-o text-white"></i> </a>
																	<?php } ?>
																</div>
															</div>
															
														</section>
													</article>
													<?php
													$comment_replies = $this->project_model->comment_replies($comment->comment_id);
															if (!empty($comment_replies)) {
													foreach ($comment_replies as $key => $reply) { ?>
													<article id="comment-id-2" class="comment-item comment-reply"> <a class="pull-left thumb-sm avatar"> <img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($reply->replied_by,'avatar')?>" class="img-circle"> </a>
													<span class="arrow left"></span>
													<section class="comment-body panel panel-default text-sm">
														<div class="panel-body">
															<span class="text-muted m-l-sm pull-right">
															<i class="fa fa-clock-o"></i> <?php
																							$today = time();
																							$reply_day = strtotime($reply->date_posted) ;
																							echo $this->user_profile->get_time_diff($today,$reply_day);
															?> <?=lang('ago')?></span>
															<a href="#"><?=ucfirst($this->user_profile->get_profile_details($reply->replied_by,'fullname')?$this->user_profile->get_profile_details($reply->replied_by,'fullname'):$this->user_profile->get_user_details($reply->replied_by,'username'))?></a>
														<?=$reply->reply_msg?></div>
													</section>
												</article>
												<?php } } ?>
												<?php } }else{ ?>
												<p><?=lang('no_comment_found')?></p>
												<?php } ?>
												<!-- comment form -->
												<article class="comment-item media" id="comment-form">
													<a class="pull-left thumb-sm avatar">
													<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'avatar')?>" class="img-circle"></a>
													<section class="media-body">
														<?php
														$attributes = array('class' => 'class="m-b-none"');
														echo form_open(base_url().'clients/projects/comment?project='.$project->project_id, $attributes); ?>
														<input type="hidden" name="project_id" value="<?=$project->project_id?>">
														<input type="hidden" name="project_code" value="<?=$project->project_code?>">
														<div class="input-group">
															<input type="text" name="comment" class="form-control" required placeholder="<?=lang('type_comment_here')?>">
															<span class="input-group-btn">
															<button class="btn btn-primary" type="submit"><?=lang('post')?></button>
															</span>
														</div>
													</form>
												</section>
											</article>
										</section>
									</div>
								</div>
								<!-- / .comment-list -->
							</section>
						</section>
					</aside>
					<?php }} ?>
				</section>
			</section>
		</section>
		<a href="profile.html#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
	</section>