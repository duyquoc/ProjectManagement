<!-- Start -->
	<section id="content">
		<section class="hbox stretch">
			<aside class="aside-md bg-white b-r" id="subNav">
				<header class="dk header b-b">			
					<div class="btn-group pull-right">
						<button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?=lang('filter')?>
						<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">				
						<li><a href="<?=base_url()?>bugs/view_by_status/unconfirmed"><?=lang('unconfirmed')?></a></li>
						<li><a href="<?=base_url()?>bugs/view_by_status/confirmed"><?=lang('confirmed')?></a></li>
						<li><a href="<?=base_url()?>bugs/view_by_status/progress"><?=lang('in_progress')?></a></li>
						<li><a href="<?=base_url()?>bugs/view_by_status/resolved"><?=lang('resolved')?></a></li>
						<li class="divider"></li>
						<li><a href="<?=base_url()?>bugs/view_by_status/verified"><?=lang('verified')?></a></li>
						</ul>
					</div>
					<p class="h4"><?=lang('all_bugs')?></p>
				</header>
				<section class="vbox">
				 	<section class="scrollable w-f">
				   		<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
							<ul class="nav">
								<?php
									if (!empty($bugs)) {
									foreach ($bugs as $key => $bug) { 
										if ($bug->bug_status == 'Verified') { $label = 'bg-success'; }elseif ($bug->bug_status == 'Resolved') {
											$label = 'bg-primary'; }elseif ($bug->bug_status == 'In Progress') { $label = 'bg-dark'; }
											elseif ($bug->bug_status == 'Confirmed') { $label = 'bg-info'; }else{ $label = 'bg-danger';
										}
										if ($bug->priority == 'Critical') { $priority = 'danger'; }elseif ($bug->priority == 'High') {
											$priority = 'inverse'; }else{	$priority = 'dark';	}	?>
									<li class="b-b b-light <?php if($bug->bug_id == $this->uri->segment(4)){ echo "bg-light dk"; } ?>">
										<a href="<?=base_url()?>bugs/view/details/<?=$bug->bug_id?>">
											<?=ucfirst($this->user_profile->get_profile_details($bug->reporter,'fullname')? $this->user_profile->get_profile_details($bug->reporter,'fullname'):$this->user_profile->get_user_details($bug->reporter,'username'))?>
											<div class="pull-right">
												BUG#<?=$bug->issue_ref?>
											</div>
											<br>
											<small class="block small text-muted"><?=$bug->project_code?> | <i class="fa fa-circle text-<?=$priority?> pull-right m-t-xs"></i> <span class="label <?=$label?>"><?=$bug->bug_status?></span></small>
										</a>
									</li>
								<?php } } ?>
							</ul> 
						</div>
					</section>
				</section>
			</aside> 
			<aside>
				<section class="vbox">
					<?php
					if (!empty($bug_details)) {
						foreach ($bug_details as $key => $bug) { ?>
							<header class="header bg-white b-b clearfix">
								<div class="row m-t-sm">
									<div class="col-sm-8 m-b-xs">	
										<div class="btn-group">
											<a href="<?=base_url()?>bugs/view/add" data-toggle="ajaxModal" title="<?=lang('new_bug')?>" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> <?=lang('new_bug')?></a>
										</div>
										<a href="<?=base_url()?>bugs/view/edit/<?=$bug->bug_id?>" data-toggle="ajaxModal" title="<?=lang('edit_bug')?>" class="btn btn-sm btn-dark"><i class="fa fa-pencil"></i></a>
										<a href="<?=base_url()?>bugs/delete/<?=$bug->bug_id?>" data-toggle="ajaxModal" title="<?=lang('delete_bug')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
										<div class="btn-group">
											<button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"><?=lang('set_status')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="<?=base_url()?>bugs/mark_status?b=<?=$bug->bug_id?>&s=unconfirmed&ref=<?=$bug->issue_ref?>"><?=lang('unconfirmed')?></a></li>
												<li><a href="<?=base_url()?>bugs/mark_status?b=<?=$bug->bug_id?>&s=confirmed&ref=<?=$bug->issue_ref?>"><?=lang('confirmed')?></a></li>	
												<li><a href="<?=base_url()?>bugs/mark_status?b=<?=$bug->bug_id?>&s=progress&ref=<?=$bug->issue_ref?>"><?=lang('in_progress')?></a></li>
												<li><a href="<?=base_url()?>bugs/mark_status?b=<?=$bug->bug_id?>&s=resolved&ref=<?=$bug->issue_ref?>"><?=lang('resolved')?></a></li>
												<li class="divider"></li>
												<li><a href="<?=base_url()?>bugs/mark_status?b=<?=$bug->bug_id?>&s=verified&ref=<?=$bug->issue_ref?>"><?=lang('verified')?></a></li>
											</ul>
										</div>
									</div>
									<div class="col-sm-4 m-b-xs">
										<?php  echo form_open(base_url().'bugs/search'); ?>
											<div class="input-group">
												<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?>">
												<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?>!</button>
												</span>
											</div>
										</form>
									</div>
								</div>
							</header>
							<section class="scrollable wrapper w-f">
								<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
									<!-- Start bug details -->
										<section class="panel ">
											<ul class="nav nav-tabs" id="stats"> 
												<li class="active"><a href="#comments" data-toggle="tab"><?=strtoupper(lang('comments'))?></a></li> 
												<li class="">
													<a href="<?=base_url()?>bugs/tabs/attachments/<?=$bug->bug_id?>" data-target="#attachments" data-toggle="tabajax" rel="tooltip">
													<?=strtoupper(lang('attachments'))?></a>
												</li> 
												<li class="">
													<a href="<?=base_url()?>bugs/tabs/activities/<?=$bug->bug_id?>" data-target="#activities" data-toggle="tabajax" rel="tooltip">
													<?=strtoupper(lang('activities'))?></a>
												</li> 
												<li class="">
													<a href="<?=base_url()?>bugs/tabs/details/<?=$bug->bug_id?>" data-target="#details" data-toggle="tabajax" rel="tooltip"><?=lang('bug_details')?></a>
												</li> 
											</ul> 
											<div class="panel-body"> 
												<div class="tab-content"> 
													<div class="tab-pane active" id="comments">
														<!-- Start Comments -->
															<section class="scrollable w-f">
																<div class="col-lg-12">
																	<!-- .comment-list -->
																		<section class="comment-list block">
																			<!-- comment form -->
																				<article class="comment-item media">
																					<a class="pull-left thumb-sm avatar">
																						<?php if(config_item('use_gravatar') == 'TRUE'){
																							$user_email = $this->user_profile->get_user_details($comment->comment_by,'email'); ?>
																							<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
																						<?php }else{ ?>
																							<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($c->comment_by,'avatar')?>" class="img-circle">
																						<?php } ?>
																					</a>
																					<section class="media-body">
																						<?php   
																						$attributes = array('class' => 'm-b-none');
																						echo form_open(base_url().'bugs/comment?bug='.$bug->bug_id, $attributes); ?>
																							<input type="hidden" name="bug" value="<?=$bug->bug_id?>">
																							<section class="panel">
																								<div class="panel-default">
																									<textarea class="form-control no-border" rows="3" name="comment" placeholder="Enter your message here"></textarea>
																									<footer class="panel-footer bg-light lter">
																										<button class="btn btn-success pull-right btn-sm" type="submit"><?=lang('post_comment')?></button> 
																										<ul class="nav nav-pills nav-sm"> 
																											<li><a href="<?=base_url()?>bugs/files/add/<?=$this->uri->segment(4)*1200?>" data-toggle="ajaxModal">
																												<i class="fa fa-paperclip text-dark"></i> <?=lang('attach_file')?></a>
																											</li>  
																										</ul>
																									</footer>
																								</div>
																							</section>
																						</form>
																					</section>
																				</article>
																			<!-- End Comment Form -->
																			<?php
																			if (!empty($bug_comments)) {
																				foreach ($bug_comments as $key => $comment) { ?>
																					<article id="comment-id-1" class="comment-item">
																						<a class="pull-left thumb-sm avatar">
																							<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($comment->comment_by,'avatar')?>" class="img-circle">
																						</a>
																						<span class="arrow left"></span>
																						<section class="comment-body panel panel-default">
																							<header class="panel-heading bg-white">
																								<a href="#"><?=ucfirst($this->user_profile->get_profile_details($comment->comment_by,'fullname')?$this->user_profile->get_profile_details($comment->comment_by,'fullname'):$this->user_profile->get_user_details($comment->comment_by,'username'))?></a>
																								<?php if($comment->comment_by == $this->tank_auth->get_user_id()){ ?><label class="label bg-danger m-l-xs"><?=lang('you')?></label> <?php } ?>
																								<span class="text-muted m-l-sm pull-right"> <i class="fa fa-clock-o"></i>
																									<?php
																										$today = time();
																										$comment_day = strtotime($comment->date_commented) ;
																										echo $this->user_profile->get_time_diff($today,$comment_day);
																									?><?=lang('ago')?> 
																								</span>
																							</header>
																							<div class="panel-body">
																								<div><small><?=$comment->comment?></small></div>
																								<div class="comment-action m-t-sm">
																								</div>
																							</div>
																						</section>
																					</article>
																			<?php } }else{ ?>
																				<p><?=lang('no_comment_found')?></p>
																			<?php } ?>
																		</section>
																	<!-- / .comment-list -->
																</div>
															</section>
														<!-- End Comments -->
													</div> 
													<div class="tab-pane" id="attachments"></div> 
													<div class="tab-pane " id="activities"></div> 
													<div class="tab-pane " id="details"></div>
												</div> 
											</div>
										</section>
									<!-- End bug details -->
								</div>
							</section>
						<?php } } ?>
				</section>
			</aside>
		</section>
		<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
	</section>
<!-- end -->