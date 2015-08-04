<section id="content">
	<section class="hbox stretch">
		
		<aside class="aside-md bg-white b-r" id="subNav">
		<header class="dk header b-b">
				
				<p class="h4"><?=lang('all_messages')?></p>
			</header>
			<section class="vbox">
				<section class="scrollable">
					<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						
							 <section id="setting-nav" class="hidden-xs">
                      <ul class="nav nav-pills nav-stacked no-radius">
                        <li class="<?php echo ($group == 'inbox') ? 'active' : '';?>">
                          <a href="<?=base_url()?>messages?group=inbox"> <i class="fa fa-fw fa-envelope"></i>
                            <?=lang('inbox')?>
                          </a>
                        </li>
                        <li class="<?php echo ($group == 'sent') ? 'active' : '';?>">
                          <a href="<?=base_url()?>messages?group=sent"> <i class="fa fa-fw fa-exchange"></i>
                            <?=lang('sent')?>
                          </a>
                        </li>
                        <li class="<?php echo ($group == 'favourites') ? 'active' : '';?>">
                          <a href="<?=base_url()?>messages?group=favourites"> <i class="fa fa-fw fa-star"></i>
                            <?=lang('favourites')?>
                          </a>
                        </li>
                        <li class="<?php echo ($group == 'trash') ? 'active' : '';?>">
                          <a href="<?=base_url()?>messages?group=trash"> <i class="fa fa-fw fa-trash-o"></i>
                            <?=lang('trash')?>
                          </a>
                        </li>



                        </ul>
                        </section>
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
									<a class="btn btn-sm btn-primary" href="<?=base_url()?>messages/send/?group=sent" title="<?=lang('send_message')?>" data-placement="top">
									<i class="fa fa-envelope"></i> <?=lang('send_message')?></a>
								</div>
							</div>
							<div class="col-sm-4 m-b-xs">
								<?php echo form_open(base_url().'messages/search/'); ?>
								<div class="input-group">
									<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('keyword')?>">
									<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
									</span>
								</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable hover w-f">
						<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
							<?php $this->load->helper('text'); ?>
							<ul class="list-group no-radius m-b-none m-t-n-xxs list-group-alt list-group-lg">
								<?php
									$group = isset($_GET['group']) ? $_GET['group'] : FALSE;
									switch ($group) {
										case 'sent':
											$this->load->view('group/sent');
											break;
										case 'inbox':
											$this->load->view('group/inbox');
											break;
										case 'favourites':
											$this->load->view('group/favourites');
											break;
										case 'trash':
											$this->load->view('group/trash');
											break;
										default:
											$this->load->view('group/inbox');
											break;
									}
								 ?>

								</ul>
							</div></section>
							


							</section></aside>
						</section>
					</aside>
				</section>
				<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
			</section>