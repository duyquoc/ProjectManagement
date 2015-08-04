<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
		
		<p class="h4"><?=lang('all_invoices')?></p>
		</header>


			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<ul class="nav">
			<?php
			if (!empty($invoices)) {
			foreach ($invoices as $key => $invoice) { 
			if ($this->invoice->payment_status($invoice->inv_id) == lang('fully_paid')){ $invoice_status = lang('fully_paid'); $label = "success"; }
			elseif($invoice->emailed == 'Yes') { $invoice_status = lang('sent'); $label = "info";	}
			else{ $invoice_status = lang('draft'); $label = "default"; }
			?>

				<li class="b-b b-light">
				<a href="<?=base_url()?>collaborator/inv_manage/details/<?=$invoice->inv_id?>">
				<?=ucfirst($this->applib->company_details($invoice->client,'company_name'))?>
				<div class="pull-right">
				<?=$invoice->currency?> <?=number_format($this->applib->invoice_payable($invoice->inv_id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>
				</div> <br>
				<small class="block small text-muted"><?=$invoice->reference_no?> | <?=strftime(config_item('date_format'), strtotime($invoice->date_saved));?> <span class="label label-<?=$label?>"><?=$invoice_status?></span></small>

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
						<?php  echo form_open(base_url().'collaborator/inv_manage/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search_invoice')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
								</span>
							</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable wrapper w-f">
					<?php  echo modules::run('sidebar/flash_msg');?>
					<!-- Start Display chart -->
					
					 <?php  echo modules::run('collaborator/invoices/index');?>


					 <!-- End display chart -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->