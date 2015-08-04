<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
		<a href="<?=base_url()?>invoices/add" data-original-title="<?=lang('new_invoice')?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-icon btn-default btn-sm pull-right"><i class="fa fa-plus"></i></a>
		<p class="h4"><?=lang('all_invoices')?></p>
		</header>


			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

			<?=$this->load->view('sidebar/invoices',$invoices)?>

			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							<div class="btn-group">
										<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
										<?=lang('sort_by')?>
										<span class="caret"></span></button>
								<ul class="dropdown-menu">											
									<li><a href="<?=base_url()?>invoices/?order_by=reference_no&order=desc">Reference No</a></li>
									<li><a href="<?=base_url()?>invoices/?order_by=due_date&order=desc">Due Date</a></li>
									<li><a href="<?=base_url()?>invoices/?order_by=tax&order=desc">Tax</a></li>
									<li><a href="<?=base_url()?>invoices/?order_by=status&order=desc">Status</a></li>
									<li><a href="<?=base_url()?>invoices/?order_by=date_sent&order=desc">Date Sent</a></li>
									<li><a href="<?=base_url()?>invoices/?order_by=viewed&order=desc">Viewed</a></li>
									<li class="divider"></li>
									<li><a href="<?=base_url()?>invoices/?order_by=date_saved&order=desc">Date Created</a></li>
								</ul>
							</div>
						
						</div>
						<div class="col-sm-4 m-b-xs">
						<?php  echo form_open(base_url().'invoices/manage/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?></button>
								</span>
							</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable wrapper w-f">
					<!-- Start Display chart -->
					
					 <?php  echo modules::run('invoices/chart');?>


					 <!-- End display chart -->






					</section> 