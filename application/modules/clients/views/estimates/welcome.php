<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
		
		<p class="h4"><?=lang('all_estimates')?></p>
		</header>


			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<ul class="nav">
			<?php
			if (!empty($estimates)) {
			foreach ($estimates as $key => $e) { 
			if ($e->status == 'Declined'){ $e_status = "Declined"; $label = "danger"; }
			elseif($e->invoiced == 'Yes') { $e_status = "Invoiced"; $label = "success";	}
			elseif($e->status == 'Accepted') { $e_status = "Accepted"; $label = "info";	}
			else{ $e_status = "Pending"; $label = "default"; }
			?>

				<li class="b-b b-light">
				<a href="<?=base_url()?>clients/estimates/details/<?=$e->est_id?>">
				<?=ucfirst($this->applib->company_details($e->client,'company_name'))?>
				<?php $cur = $this->applib->currencies($this->applib->company_details($e->client,'currency')); ?>
				<div class="pull-right">
				<?=$cur->symbol?> <?=number_format($this->user_profile->estimate_payable($e->est_id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>
				</div> <br>
				<small class="block small text-muted"><?=$e->reference_no?> | <?=strftime(config_item('date_format'), strtotime($e->date_saved));?> <span class="label label-<?=$label?>"><?=$e_status?></span></small>

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
						<?php  echo form_open(base_url().'clients/estimates/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('reference_no')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
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