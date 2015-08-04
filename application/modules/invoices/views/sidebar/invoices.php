<ul class="nav"><?php
							if (!empty($invoices)) {
							foreach ($invoices as $key => $invoice) {
							if ($this-> applib ->payment_status($invoice->inv_id) == lang('fully_paid')){ $invoice_status = lang('fully_paid'); $label = "success"; }
								elseif($invoice->emailed == 'Yes') { $invoice_status = lang('sent'); $label = "info";	}
							else{ $invoice_status = lang('draft'); $label = "default"; }
							?>
							<li class="b-b b-light <?php if($invoice->inv_id == $this->uri->segment(3)){ echo "bg-light dk"; } ?>">
								<a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>">
									<?=ucfirst($this->applib->company_details($invoice->client,'company_name'))?>
									<div class="pull-right">
                                                                            <?php $cur = $this->applib->currencies($invoice->currency); ?>
										<?=$cur->symbol?> <?=number_format($this->user_profile->invoice_payable($invoice->inv_id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>
									</div> <br>
									<small class="block small text-muted"><?=$invoice->reference_no?> <span class="label label-<?=$label?>"><?=$invoice_status?></span></small>
								</a> </li>
								<?php } } ?>
							</ul>