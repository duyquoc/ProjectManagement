<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<div class="wrapper b-b header"><?=lang('all_projects')?>
			</div>
			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<ul class="nav">
			<?php
			if (!empty($projects)) {
			foreach ($projects as $key => $p) { ?>
				<li class="b-b b-light">
				<a href="<?=base_url()?>collaborator/projects/details/<?=$p->project_id?>" data-toggle="tooltip" data-original-title="<?=$p->project_title?>">
				<?=ucfirst($this->applib->company_details($p->client,'company_name'))?>
				<div class="pull-right">
				<?php
						$task_time = $this->user_profile->get_sum('tasks','logged_time',array('project'=>$p->project_id));
						$project_time = $this->user_profile->get_sum('projects','time_logged',array('project_id'=>$p->project_id));
						$logged_time = ($task_time + $project_time)/3600;
						echo round($logged_time, 1);
									?> <?=lang('hours')?>
				</div> <br>
				<small class="block small text-muted"><?=$p->project_title?> <?php if($p->timer == 'On'){ ?><i class="fa fa-clock-o text-danger"></i> <?php } ?></small>

				</a> </li>
				<?php } } ?>
			</ul> 
			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
						<div class="btn-group">
						<a class="btn btn-sm btn-default" href="<?=current_url()?>" data-original-title="<?=lang('refresh')?>" data-toggle="tooltip" data-placement="top"><i class="fa fa-refresh"></i></a>
						</div>
						</div>
						<div class="col-sm-4 m-b-xs">
						<?php  echo form_open(base_url().'collaborator/projects/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search_project')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
								</span>
							</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable wrapper w-f">
					<!-- Start Display chart -->
					<?php  echo modules::run('sidebar/flash_msg');?>


					 <!-- End display chart -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->