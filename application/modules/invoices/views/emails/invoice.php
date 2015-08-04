<!DOCTYPE html>
<html lang="<?=lang('lang_code')?>" class="app">

<head> 
    <meta charset="utf-8" /> 
    <meta name="description" content="">
    <meta name="author" content="<?=$this->config->item('site_author')?>">
    <meta name="keyword" content="<?=$this->config->item('site_desc')?>">
    <link rel="shortcut icon" href="<?=base_url()?>resource/images/favicon.ico">
	<link rel="icon" type="image/png" href="<?=base_url()?>resource/images/favicon.png">

    <title>Invoice </title>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 

<link rel="stylesheet" href="<?=base_url()?>resource/css/app.inv.v2.css" type="text/css" /> 

 
</head>

<body> 
<section class="vbox"> 

<section> 
<section class="hbox stretch"> 

<section id="content">
	<section class="hbox stretch">
		
			<?php
					if (!empty($invoice_details)) {
			foreach ($invoice_details as $key => $inv) { ?>
			<aside>
			<section class="vbox">
				
					
					<section class="scrollable wrapper w-f">
					

					
					<section class="scrollable wrapper">

					<div class="row text-left"> 

					<div class="col-xs-6"> 
						<h4><strong><?=$this->config->item('company_name')?></strong></h4>
						<p><?=$this->config->item('company_address')?><br>
						<?=lang('phone')?>: <a href="tel:<?=$this->config->item('company_phone')?>"><?=$this->config->item('company_phone')?></a></p>
					</div>
						<div class="col-xs-12 text-right">
						<h4>INV #<?=$inv->reference_no?></h4> 
						<p class="m-t m-b">
					<?=lang('invoice_date')?>: <strong><?=strftime(config_item('date_format'), strtotime($inv->date_saved));?></strong><br>
					<?=lang('due_date')?>: <strong><?=strftime(config_item('date_format'), strtotime($inv->due_date));?></strong><br> 
					<?=lang('payment_status')?>: <?=$payment_status?> <br> 
					<?=lang('bill_to')?>: <strong><?=ucfirst($this->user_profile->get_profile_details($inv->id,'fullname')?$this->user_profile->get_profile_details($inv->id,'fullname') : $inv->username)?></strong> </p>
					</div>
					</div>

					
					<div class="line"></div>
					<table class="table"><thead>
					<tr style="background-color:#000; color:#fff;">
					<th><?=lang('description')?> </th>
					<th width="60"><?=lang('qty')?> </th>					
					<th width="140"><?=lang('unit_price')?> </th>
					<th width="90"><?=lang('amount')?> </th>
					</tr> </thead> <tbody>
					<?php
					if (!empty($invoice_items)) {
					foreach ($invoice_items as $key => $item) { ?>
					<tr>
						<td><?=nl2br($item->item_desc) ?></td>
						<td><?=$item->quantity?></td> 						
						<td><?=$this->config->item('default_currency_symbol')?> <?=number_format($item->unit_cost,2)?></td>
						<td><?=$this->config->item('default_currency_symbol')?> <?=number_format($item->total_cost,2)?></td>
					</tr>
					<?php } } ?>
					
					<?php
					$inv_tax = $inv->tax?$inv->tax:$this->config->item('default_tax');
					$invoice_cost = $this->user_profile->invoice_payable($inv->inv_id);
					$payment_made = $this->user_profile->invoice_payment($inv->inv_id);
					$tax = ($inv_tax/100) * $invoice_cost;
					?>
					<tr>
						<td colspan="3" class="text-right"><strong><?=lang('sub_total')?></strong></td>
						<td><strong><?=$this->config->item('default_currency_symbol')?> <?=number_format($invoice_cost,2)?>
						</strong></td>
					</tr>
					<tr>
						<td colspan="3" class="text-right no-border"><strong><?=lang('tax')?></strong></td>
						<td><?=$this->config->item('default_currency_symbol')?> <?=number_format($tax,2)?></td>
					</tr>
					<tr>
					<td colspan="3" class="text-right no-border"><strong><?=lang('balance_due')?></strong></td>
					<td><strong><?=$this->config->item('default_currency_symbol')?> <?=number_format(($invoice_cost + $tax) - $payment_made,2)?></strong></td>
					</tr>
					</tbody>
					</table>
					</section> 


					<p><?=$inv->notes?></p>


					<?php } } ?>






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>




  
</section> 
</section> 
</section> 
</body>
</html>