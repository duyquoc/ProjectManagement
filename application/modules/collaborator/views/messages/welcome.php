<section id="content">
	<section class="hbox stretch">
		
		<aside class="aside-md bg-white b-r" id="subNav">
			<div class="wrapper b-b header"><?=lang('all_messages')?>
			</div>
			<section class="vbox">
				<section class="scrollable w-f">
					<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						<ul class="nav">
							<?php
							if (!empty($users)) {
							foreach ($users as $key => $user) { ?>
							<li class="b-b b-light">
								<a href="<?=base_url()?>collaborator/conversation/view/<?=$user->id*1200?>">
							<i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i><?=ucfirst($this->user_profile->get_profile_details($user->user_from,'fullname')?$this->user_profile->get_profile_details($user->user_from,'fullname'): $user->username)?></a></li>
							<?php }} ?>
						</ul>
					</div></section>
				</section>
			</aside>
			<!-- .aside -->
			<aside class="bg-light lter b-l" id="email-list">
				<section class="vbox">
					<header class="header bg-white b-b clearfix">
						<div class="row m-t-sm">
							<div class="col-sm-8 m-b-xs">
								
								<div class="btn-group">
									<a class="btn btn-sm btn-dark" href="<?=base_url()?>collaborator/conversation/send" title="<?=lang('send_message')?>" data-placement="top">
									<i class="fa fa-envelope"></i> <?=lang('send_message')?></a>
								</div>
							</div>
							<div class="col-sm-4 m-b-xs">
							<?php echo form_open(base_url().'collaborator/messages/search/'); ?>
								<div class="input-group">
									<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('keyword')?>">
									<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
									</span>
								</div>
								</form>
							</div>
						</div> </header>
						<section class="scrollable hover">
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

							<?php $this->load->helper('text'); ?>

								<ul class="list-group no-radius m-b-none m-t-n-xxs list-group-alt list-group-lg">
					<?php
						if (!empty($messages)) {
						foreach ($messages as $key => $msg) { ?>
							<li class="list-group-item">
								<a class="thumb-xs pull-left m-r-sm">
										<?php 
										if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$msg->user_from),'use_gravatar') == 'Y'){
										$user_email = $this->user_profile->get_user_details($msg->user_from,'email'); ?>
										<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
										<?php }else{ ?>
										<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($msg->user_from,'avatar')?>" class="img-circle">
										<?php } ?>
								</a>


					<a href="<?=base_url()?>collaborator/conversation/view/<?=$msg->user_from*1200?>" class="clear">
					<small class="pull-right text-muted">
					<?php $today = time(); $activity_day = strtotime($msg->date_received) ;
						echo $this->user_profile->get_time_diff($today,$activity_day);
					?> <?=lang('ago')?></small>
					<strong><?=ucfirst($this->user_profile->get_profile_details($msg->user_from,'fullname')?$this->user_profile->get_profile_details($msg->user_from,'fullname'):$msg->username)?></strong> 
					<?php if($msg->status == 'Unread'){ ?><span class="label label-sm bg-success text-uc"><?=lang('unread')?></span><?php } ?>
					
					<span><?php
					$longmsg = $msg->message;
					$message = word_limiter($longmsg, 2);
					echo $message;
					?></span> </a> </li>
									<?php }} ?>
								</ul>
							</div></section>
			 </section></aside>
		</section> 
		</aside> 
		</section> 
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>