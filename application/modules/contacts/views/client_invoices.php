<table class="table table-striped b-t b-light text-sm">
			<thead>
				<tr>
					<th><?=lang('reference_no')?></th>
					<th><?=lang('date_issued')?></th>
					<th><?=lang('due_date')?> </th>
					<th class="col-currency"><?=lang('amount')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php
								if (!empty($user_invoices)) {
				foreach ($user_invoices as $key => $invoice) { ?>
				<tr>
					<td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>">INV <?=$invoice->reference_no?></a></td>
					<td><?=strftime(config_item('date_format'), strtotime($invoice->date_saved));?> </td>
					<td><?=strftime(config_item('date_format'), strtotime($invoice->due_date));?> </td>
                                        <?php $cur = $this->applib->client_currency($invoice->currency); ?>
					<td><?=$cur->symbol?> <?=number_format($this->user_profile->invoice_payable($invoice->inv_id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?> </td>
					<td>
					<a href="<?=base_url()?>invoices/manage/edit/<?=$invoice->inv_id?>" class="btn btn-default btn-xs" data-original-title="<?=lang('edit_invoice')?>" data-toggle="tooltip">
					<i class="fa fa-edit"></i></a>

					<a href="<?=base_url()?>invoices/manage/delete/<?=$invoice->inv_id?>" data-toggle="ajaxModal" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php  }} else{ ?>
				<tr>
					<td></td><td><?=lang('nothing_to_display')?></td><td></td><td></td><td></td>
				</tr>
				<?php } ?>
				
				
				
			</tbody>
		</table>