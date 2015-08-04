<!-- Start -->
<section id="content">
	<section class="hbox stretch">
		
		<aside class="aside-md bg-white b-r hidden-print" id="subNav">
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
							<li class="b-b b-light <?php if($e->est_id == $this->uri->segment(4)){ echo "bg-light dk"; } ?>">
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
						<header class="header bg-white b-b clearfix hidden-print">
							<div class="row m-t-sm">
								<div class="col-sm-8 m-b-xs">
									<?php
									if (!empty($estimate_details)) {
									foreach ($estimate_details as $key => $estimate) { ?>
									<a data-original-title="<?=lang('print_estimate')?>" data-toggle="tooltip" data-placement="top" href="#" class="btn btn-sm btn-info" onClick="window.print();">
									<i class="fa fa-print"></i> </a>
									
									<?php
									if ($estimate->invoiced == 'No') { ?>
									<div class="btn-group">
										<button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
										<?=lang('more_actions')?>
										<span class="caret"></span></button>
										<ul class="dropdown-menu">
											
											
											<li><a href="<?=base_url()?>clients/estimates/status/declined/<?=$estimate->est_id?>/<?=$estimate->reference_no?>"><?=lang('mark_as_declined')?></a></li>
											<li><a href="<?=base_url()?>clients/estimates/status/accepted/<?=$estimate->est_id?>/<?=$estimate->reference_no?>"><?=lang('mark_as_accepted')?></a></li>
											
										</ul>
									</div>
									<?php } ?>
									
								</div>
								<div class="col-sm-4 m-b-xs">
									<a href="<?=base_url()?>fopdf/estimate/<?=$estimate->est_id?>/<?=$estimate->reference_no?>" class="btn btn-sm btn-dark pull-right">
									<i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
									
								</div>
							</div> </header>
							
							<section class="scrollable wrapper w-f">
								<!-- Start Display Details -->
								<?php
								if(!$this->session->flashdata('message')){
								if(strtotime($estimate->due_date) < time() AND $estimate->invoiced == 'No'){ ?>
								<div class="alert alert-info hidden-print">
									<button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
									<?=lang('estimate_overdue')?>
								</div>
								<?php } } ?>
								<section class="scrollable wrapper">
									<div class="row">
										<?php
											if ($estimate->invoiced == 'Yes') {	$est_status = 'INVOICED'; $label = 'success'; }elseif ($estimate->emailed == 'Yes' AND $estimate->status == 'Pending') {
											$est_status = 'SENT'; $label = 'info';	}elseif ($estimate->status != 'Pending') {
													$est_status = strtoupper($estimate->status); $label = 'primary'; } else{	$est_status = 'DRAFT'; $label = 'dark';	}
										?>
										<div class="col-xs-6">
											<p class="h4"><strong><?=$this->config->item('company_name')?></strong></p>
											
											<?=$this->config->item('company_address')?><br>
											<?=$this->config->item('company_city')?><br>
											<?=$this->config->item('company_country')?><br>
											<?=lang('phone')?>: <a href="tel:<?=$this->config->item('company_phone')?>"><?=$this->config->item('company_phone')?></a><br>

											<p class="h4"><strong><?=lang('bill_to')?>:</strong></p>
						<?=ucfirst($this->applib->company_details($estimate->client,'company_name'))?> <br>
						<?=ucfirst($this->applib->company_details($estimate->client,'company_address'))?> <br>
						<?=ucfirst($this->applib->company_details($estimate->client,'city'))?> ,
						<?=ucfirst($this->applib->company_details($estimate->client,'country'))?> <br>
						<?=lang('vat')?> : <?=ucfirst($this->applib->company_details($estimate->client,'VAT'))?> <br>


										</div>
										<div class="col-xs-6 text-right">
											<p class="h4"><?=$estimate->reference_no?></p>
											<p class="m-t m-b">
											<?=lang('estimate_date')?>: <strong><?=strftime(config_item('date_format'), strtotime($estimate->date_saved));?></strong><br>
											<?=lang('expiry_date')?>: <strong><?=strftime(config_item('date_format'), strtotime($estimate->due_date));?></strong><br>
											<?=lang('estimate_status')?>: <span class="label bg-<?=$label?>"><?=$est_status?> </span><br>
											</p>
										</div>
									</div>
									
									<div class="line"></div>
									<table class="table"><thead>
										<tr>
											<th><?=lang('description')?> </th>
											<th width="60"><?=lang('qty')?> </th>
											<th width="140"><?=lang('unit_price')?> </th>
											<th width="90"><?=lang('total')?> </th>
										</tr> </thead> <tbody>
										<?php
										if (!empty($estimate_items)) {
										foreach ($estimate_items as $key => $item) { ?>
										<tr>
											<td><?=$item->item_desc?> </td>
											<td><?=$item->quantity?></td>
											<td><?=$cur->symbol?> <?=number_format($item->unit_cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></td>
											<td><?=$cur->symbol?> <?=number_format($item->total_cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></td>
										</tr>
										<?php } } ?>
										<?php
										$est_tax = $estimate->tax?$estimate->tax:$this->config->item('default_tax');
										$estimate_cost = $this->user_profile->estimate_payable($estimate->est_id);
										$tax = ($est_tax/100) * $estimate_cost;
										?>
										<tr>
											<td colspan="3" class="text-right"><strong><?=lang('sub_total')?></strong></td>
											<td><?=$cur->symbol?> <?=number_format($estimate_cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></td>
										</tr>
										<tr>
											<td colspan="3" class="text-right no-border"><strong><?=lang('tax')?></strong></td>
											<td><?=$cur->symbol?> <?=number_format($tax,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?> </td>
										</tr>
										<tr>
											<td colspan="3" class="text-right no-border"><strong><?=lang('total')?></strong></td>
											<td><strong><?=$cur->symbol?> <?=number_format($estimate_cost+$tax,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></td>
										</tr>
									</tbody>
								</table>
							</section>
							<p><?=$estimate->notes?></p>
							<?php } } ?>
							<!-- End display details -->
						</section>
						</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>
						<!-- end -->