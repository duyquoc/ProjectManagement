<section id="content">
	<section class="hbox stretch">
		
		<aside class="aside-md bg-white b-r" id="subNav">
			<div class="wrapper b-b header"><?=lang('all_messages')?></div>
			<section class="vbox">
				<section class="scrollable w-f">
					<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						<ul class="nav">
							<?php
							if (!empty($users)) {
							foreach ($users as $key => $user) { ?>
							<li class="b-b b-light <?php if($user->user_from == $this->uri->segment(4)/1200){ echo "bg-light dk"; } ?>">
								<a href="<?=base_url()?>clients/conversation/view/<?=$user->id*1200?>">
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
									<a class="btn btn-sm btn-dark" href="<?=base_url()?>clients/conversation/send" title="<?=lang('send_message')?>" data-placement="top">
									<i class="fa fa-envelope"></i> <?=lang('send_message')?></a>
								</div>
							</div>
							<div class="col-sm-4 m-b-xs">
							<?php echo form_open(base_url().'clients/messages/search/'); ?>
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


					<div class="panel-body">
						<!-- message form -->
						<article class="comment-item media" id="comment-form"> 
						<a class="pull-left thumb-sm avatar">

							
				<?php
				$user_id = $this->tank_auth->get_user_id(); 
		if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'use_gravatar') == 'Y'){
										$user_email = Applib::login_info($user_id)->email; ?>
										<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
										<?php }else{ ?>
										<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'avatar')?>" class="img-circle">
										<?php } ?>

						</a> <section class="media-body">
						<?php
							$attributes = array('class' => 'class="m-b-none"');
								echo form_open(base_url().'clients/conversation/send/'.$this->uri->segment(4)/1200, $attributes); ?>
								<input type="hidden" name="user_to" value="<?=$this->uri->segment(4)/1200?>">
								<input type="hidden" name="r_url" value="<?=current_url()?>">

								<section class="panel panel-default"> 
									<textarea class="form-control no-border" rows="3" name="message" placeholder="Enter your message here"></textarea>
									<footer class="panel-footer bg-light lter">
									<button class="btn btn-dark pull-right btn-sm" type="submit"><?=lang('send_message')?></button> 
									<ul class="nav nav-pills nav-sm"> 
									 
									</ul> </footer> 
								</section>
						</form> </section> </article>
						
						<!-- Conversation Start -->
						<section class="comment-list block">
							<?php
													if (!empty($conversations)) {
							foreach ($conversations as $key => $msg) { ?>
							<article id="comment-id-1" class="comment-item"> 

							<a class="pull-left thumb-sm avatar">

							
			<?php 
				if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$msg->user_from),'use_gravatar') == 'Y'){
				$user_email = Applib::login_info($msg->user_from)->email; ?>
				<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
					<?php }else{ ?>
				<img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($msg->user_from)->avatar?>" class="img-circle">
				<?php } ?>
										
						</a> 

							<span class="arrow left"></span>
								<section class="comment-body panel panel-default">
									<header class="panel-heading bg-white"> <a href="#">
									<?=ucfirst(Applib::profile_info($msg->user_from)->fullname ? Applib::profile_info($msg->user_from)->fullname :$msg->username)?></a>

								<span class="text-muted m-l-sm pull-right"> <i class="fa fa-clock-o"></i> 
														<?php
																$today = time();
																$received = strtotime($msg->date_received) ;
																echo $this->user_profile->get_time_diff($today,$received);
																?> <?=lang('ago')?> 
								<?php 
								if ($msg->user_to == $this->tank_auth->get_user_id()) { ?>
				<a href="<?=base_url()?>clients/conversation/delete/<?=$msg->msg_id*1200?>/<?=$this->uri->segment(4)?>" data-toggle="ajaxModal" class="btn btn-danger btn-xs active"><i class="fa fa-trash-o text-active"></i> 
				</a><?php } ?>
								</span>
									</header>
								<div class="panel-body"><div><?=nl2br($msg->message);?></div>
								
								</div> 
								</section>
						</article>
						<?php } } ?>
						<!-- .message-end -->
						
					</section>
					</div>



							</div></section>
			 </section></aside>
		</section> 
		</aside> 
		</section> 
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>