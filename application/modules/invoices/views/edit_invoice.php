<!-- Start -->
<section id="content">
	<section class="hbox stretch">
		<aside class="aside-md bg-white b-r" id="subNav">
			<header class="dk header b-b">
				<a href="<?=base_url()?>invoices/add" data-original-title="<?=lang('new_invoice')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-<?=config_item('button_color')?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
				<p class="h4"><?=lang('all_invoices')?></p>
			</header>
			<section class="vbox">
				<section class="scrollable w-f">
					<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
						<?=$this->load->view('sidebar/invoices',$invoices)?>
					</div>
				</section>
			</section>
		</aside>
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							<?php if (!empty($invoice_details)) {
							foreach ($invoice_details as $key => $i) { ?>
							<div class="btn-group">
								<a href="<?=base_url()?>invoices/view/<?=$i->inv_id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="top" class="btn btn-<?=config_item('button_color')?> btn-sm"><i class="fa fa-info-circle"></i> <?=lang('invoice_details')?></a>
							</div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<?php  echo form_open(base_url().'invoices/manage/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?>!</button>
								</span>
							</div>
						</form>
					</div>
				</div>
			</header>
			<section class="scrollable wrapper">
				<!-- Start create invoice -->
				<div class="col-sm-12">
					<section class="panel panel-default">
					<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('invoice_details')?> - <?=$i->reference_no?></header>
					<div class="panel-body">
						<?php
							$attributes = array('class' => 'bs-example form-horizontal');
						echo form_open(base_url().'invoices/edit',$attributes); ?>
						<input type="hidden" name="inv_id" value="<?=$i->inv_id?>">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<input type="text" class="form-control" value="<?=$i->reference_no?>" name="reference_no">
							</div>
							<a href="#recurring" class="btn btn-xs btn-info" data-toggle="class:show"><?=lang('recurring')?></a>
						</div>
						<!-- Start discount fields -->
						<div id="recurring" class="hide">
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('recur_frequency')?> </label>
								<div class="col-lg-4">
									<select name="r_freq" class="form-control">
										<option value="none"><?=lang('none')?></option>
										<option value="7D"<?=($i->recur_frequency == "7D" ? ' selected="selected"' : '')?>><?=lang('week')?></option>
										<option value="1M"<?=($i->recur_frequency == "1M" ? ' selected="selected"' : '')?>><?=lang('month')?></option>
										<option value="3M"<?=($i->recur_frequency == "3M" ? ' selected="selected"' : '')?>><?=lang('quarter')?></option>
										<option value="6M"<?=($i->recur_frequency == "6M" ? ' selected="selected"' : '')?>><?=lang('six_months')?></option>
										<option value="1Y"<?=($i->recur_frequency == "1Y" ? ' selected="selected"' : '')?>><?=lang('year')?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('start_date')?></label>
								<div class="col-lg-8">
									<?php if ($i->recurring == 'Yes') {
										$recur_start_date = date('d-m-Y',strtotime($i->recur_start_date));
										$recur_end_date = date('d-m-Y',strtotime($i->recur_end_date));
									}else{
										$recur_start_date = date('d-m-Y');
										$recur_end_date = date('d-m-Y');
									}
									?>
									<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($recur_start_date));?>" name="recur_start_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('end_date')?></label>
								<div class="col-lg-8">
									<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($recur_end_date));?>" name="recur_end_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div>
							</div>
						</div>
						<!-- End discount Fields -->
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
							<div class="col-lg-6">
								<div class="m-b">
									<select class="select2-option" style="width:260px" name="client" >
										<optgroup label="<?=lang('clients')?>">
											<option value="<?=$i->client?>"><?=ucfirst($this->applib->company_details($i->client,'company_name'))?></option>
											<?php
											if (!empty($clients)) {
											foreach ($clients as $client): ?>
											<option value="<?=$client->co_id?>"><?=strtoupper($client->company_name)?></option>
											<?php endforeach; } ?>
										</optgroup>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('due_date')?></label>
							<div class="col-lg-8">
								<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($i->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('default_tax')?> </label>
							<div class="col-lg-4">
								<div class="input-group m-b">
									<span class="input-group-addon">%</span>
									<input class="form-control " type="text" value="<?=$i->tax?>" name="tax">
								</div>
							</div>
							<a href="#discounts" class="btn btn-xs btn-info" data-toggle="class:show"><?=lang('discount')?></a>
						</div>
						<!-- Start discount fields -->
						<div id="discounts" class="hide">
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('discount')?> </label>
								<div class="col-lg-4">
									<div class="input-group m-b">
										<span class="input-group-addon">%</span>
										<input class="form-control " type="text" value="<?=$i->discount?>" name="discount">
									</div>
								</div>
							</div>
						</div>
						<!-- End discount Fields -->

						<div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('currency')?></label>
                        <div class="col-lg-7">
                            <select name="currency" class="form-control">
                                <?php $cur = $this->applib->currencies($i->currency); ?>
                            <?php foreach ($currencies as $cur) : ?>
                            <option value="<?=$cur->code?>"<?=($i->currency == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                </div>

						<div class="form-group">
							    <label class="col-lg-3 control-label"><?=lang('allow_2checkout')?></label>
		                      	<div class="col-lg-4">
		                        	<label class="switch">
		                          		<input type="checkbox" name="allow_2checkout" <?php if($i->allow_2checkout == 'Yes'){ echo "checked=\"checked\""; }?>>
		                         		<span></span>
		                        	</label>
		                      </div>
            		</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('allow_paypal')?></label>
							<div class="col-lg-4">
								<label class="switch">
									<input type="checkbox" name="allow_paypal" <?php if($i->allow_paypal == 'Yes'){ echo "checked=\"checked\""; }?>>
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('allow_stripe')?></label>
							<div class="col-lg-4">
								<label class="switch">
									<input type="checkbox" name="allow_stripe" <?php if($i->allow_stripe == 'Yes'){ echo "checked=\"checked\""; }?>>
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('allow_bitcoin')?></label>
							<div class="col-lg-4">
								<label class="switch">
									<input type="checkbox" name="allow_bitcoin" <?php if($i->allow_bitcoin == 'Yes'){ echo "checked=\"checked\""; }?>>
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('notes')?> </label>
							<div class="col-lg-8">
								<textarea name="notes" class="form-control foeditor"><?=$i->notes?></textarea>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-<?=config_item('button_color')?>"> <?=lang('save_changes')?></button>
					</form>
					<?php } } ?>
				</div>
			</section>
		</div>
		<!-- End create invoice -->
	</section>
</section>
</aside>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<!-- end -->