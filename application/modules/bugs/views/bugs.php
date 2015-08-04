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
				<li><a href="<?=base_url()?>bugs/view_by_status/unconfirmed">Unconfirmed</a></li>
				<li><a href="<?=base_url()?>bugs/view_by_status/confirmed">Confirmed</a></li>
				<li><a href="<?=base_url()?>bugs/view_by_status/progress">In Progress</a></li>
				<li><a href="<?=base_url()?>bugs/view_by_status/resolved">Resolved</a></li>
				<li class="divider"></li>
				<li><a href="<?=base_url()?>bugs/view_by_status/verified">Verified</a></li>
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

				<li class="b-b b-light">
				<a href="<?=base_url()?>bugs/view/details/<?=$bug->bug_id?>">
				<?=ucfirst($this->user_profile->get_profile_details($bug->reporter,'fullname')? $this->user_profile->get_profile_details($bug->reporter,'fullname'):$this->user_profile->get_user_details($bug->reporter,'username'))?>
				<div class="pull-right">
				BUG#<?=$bug->issue_ref?>
				</div> <br>
				<small class="block small text-muted"><?=$bug->project_code?> | <i class="fa fa-circle text-<?=$priority?> pull-right m-t-xs"></i> <span class="label <?=$label?>"><?=$bug->bug_status?></span></small>

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
						<a href="<?=base_url()?>bugs/view/add" data-toggle="ajaxModal" title="<?=lang('new_bug')?>" class="btn btn-sm btn-dark"><i class="fa fa-plus"></i> <?=lang('new_bug')?></a>
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
					</div> </header>
					<section class="scrollable wrapper w-f">
					<?php  echo modules::run('sidebar/flash_msg');?>
					<!-- Start Display chart -->


					 <!-- End display chart -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->


