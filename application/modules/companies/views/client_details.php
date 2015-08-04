<?php $this->applib->set_locale(); ?>
<section id="content">
	<section class="hbox stretch">
		<?php
										if (!empty($client_details)) {
		foreach ($client_details as $key => $i) { ?>
		<!-- .aside -->
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b b-light">
					<a href="#aside" data-toggle="class:show" class="btn btn-sm btn-dark pull-right"><i class="fa fa-edit"></i> <?=lang('edit')?></a>

					<p><?=$i->company_name?> - <?=lang('details')?> </p>
				</header>
				<section class="scrollable wrapper">
					<section class="panel panel-default">	
					<span class="text-danger"><?=$this->session->flashdata('form_errors')?></span>
						
						<div class="panel-body">	
						


							<!-- Details START -->
							<div class="col-md-6">
								<div class="group">
									<h4 class="subheader text-muted"><?=lang('contact_details')?></h4>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('company_name')?></div>
										<div class="col-md-6"><?=$i->company_name?></div>
									</div>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('contact_person')?></div>
										<div class="col-md-6"><?=($i->primary_contact) ? Applib::profile_info($i->primary_contact)->fullname : ''?></div>
									</div>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('email')?></div>
										<div class="col-md-6"><?=$i->company_email?></div>
									</div>
								</div>
								<div class="group">
									<h4 class="subheader text-muted"><?=lang('other_details')?></h4>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('city')?></div>
										<div class="col-md-6"><?=$i->city?></div>
									</div>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('country')?></div>
										<div class="col-md-6 text-success"><?=$i->country?></div>
									</div>									
									
									
								</div>
								
							</div>
							<div class="col-md-6">
								<div class="group">
									<div class="row" style="margin-top: 5px">
										<div class="rec-pay col-md-12">
											<h4 class="subheader text-muted"><?=lang('received_amount')?></h4>
											<h3 class="amount text-danger cursor-pointer"><strong>
                                 <?php $cur = $this->applib->client_currency($i->co_id); ?><?=$cur->symbol?>
											<?=number_format($this->user_profile->client_paid($i->co_id),2,$this->config->item('decimal_separator'),config_item('thousand_separator'))?>
											</strong></h3>
											<div class="row inline-fields">
										<div class="col-md-4"><?=lang('address')?></div>
										<div class="col-md-6"><?=$i->company_address?></div>
									</div>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('phone')?></div>
                                                                                <div class="col-md-6"><a href="tel:<?=$i->company_phone?>"><?=$i->company_phone?></a></div>
									</div>
											<div class="row inline-fields">
										<div class="col-md-4"><?=lang('website')?></div>
										<div class="col-md-6"><a href="<?=$i->company_website?>" class="text-info" target="_blank"><?=$i->company_website?></a></div>
									</div>
									<div class="row inline-fields">
										<div class="col-md-4"><?=lang('vat')?></div>
										<div class="col-md-6"><?=$i->VAT?></div>
									</div>
									<div class="row inline-fields">


										<div class="col-md-4">
				<a href="#additional_info" class="btn btn-sm btn-success" data-toggle="class:show"><i class="fa fa-info-circle"></i> <?=lang('show_account_details')?></a></div>


										<div class="col-md-6">
										<div id="additional_info" class="hide">
										<div class="bg-white">
																			
										<?=lang('account_username')?> : <?=$i->account_username?><br>
										<?=lang('account_password')?> : <?=$i->account_password?><br>
										<?=lang('port')?> : <?=$i->port?><br>
										<?=lang('hostname')?> : <?=$i->hostname?><br>
										<?=lang('hosting_company')?> : <?=$i->hosting_company?><br>
										</div>
										</div>
										</div>
									
									</div>
									<!-- End Additional Info -->
										</div>
									</div>
								</div>
							</div>
							
							<!-- Details END -->
						</div>

						<div class="panel-body">
							<!-- Client Contacts -->
							<div class="col-md-12">
							<section class="panel panel-default">
                <header class="panel-heading">
                <a href="<?=base_url()?>contacts/add/<?=$i->co_id?>" class="btn btn-xs btn-info pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_contact')?></a>

                <i class="fa fa-user"></i> <?=lang('contacts')?></header>

                

			<table id="table-client-details-1" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('avatar_image')?></th>
					<th><?=lang('full_name')?></th>
					<th><?=lang('email')?></th>
					<th><?=lang('phone')?> </th>
					<th><?=lang('mobile_phone')?> </th>
					<th>Skype</th>
					<th class="col-date"><?=lang('last_login')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php
								if (!empty($client_contacts)) {
				foreach ($client_contacts as $key => $contact) { ?>
				<tr>
                                    <td><a class="thumb-sm avatar">

				<?php
					$user_email = Applib::login_info($contact->user_id)->email;
					$gravatar_url = $this -> applib -> get_gravatar($user_email);
					 if(config_item('use_gravatar') == 'TRUE' AND $this -> applib -> get_any_field(Applib::$profile_table,array('user_id'=>$contact->user_id),'use_gravatar') == 'Y'){ ?>
					<img src="<?=$gravatar_url?>" class="img-circle">
					<?php }else{ ?>
					<img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($contact->user_id)->avatar?>" class="img-circle">
				<?php } ?>
                                        </a></td>
					<td><?=$contact->fullname?></td>
					<td class="text-info" ><?=$contact->email?> </td>
                    <td><a href="tel:<?=$contact->phone?>"><?=$contact->phone?></a></td>
                    <td><a href="tel:<?=$contact->mobile?>"><?=$contact->mobile?></a></td>
                    <td><a href="skype:<?=$contact->skype?>?call"><?=$contact->skype?></a></td>
					<?php
					if ($contact->last_login == '0000-00-00 00:00:00') {
						$login_time = "-";
					}else{ $login_time = strftime(config_item('date_format')." %H:%M:%S", strtotime($contact->last_login)); } ?>
					<td><?=$login_time?> </td>				
					<td>
					
					<a href="<?=base_url()?>companies/make_primary/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-default btn-xs" title="<?=lang('primary_contact')?>" >
					<i class="fa fa-chain <?php if ($i->primary_contact == $contact->user_id) { echo "text-danger"; } ?>"></i> </a>
					<a href="<?=base_url()?>contacts/view/update/<?=$contact->user_id?>" class="btn btn-default btn-xs" title="<?=lang('edit')?>"  data-toggle="ajaxModal">
					<i class="fa fa-edit"></i> </a>
					<a href="<?=base_url()?>users/account/delete/<?=$contact->user_id?>" class="btn btn-default btn-xs" title="<?=lang('delete')?>" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i> </a>
					</td>
				</tr>
				<?php  } } ?>
				
				
				
			</tbody>
		</table>
							</section></div>

							<!-- Client Invoices -->
							<div class="col-md-6">
							<section class="panel panel-default">
                <header class="panel-heading"><i class="fa fa-list"></i> <?=strtoupper(lang('invoices'))?> </header>
		<table id="table-client-details-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('reference_no')?></th>
					<th><?=lang('date_issued')?></th>
					<th><?=lang('due_date')?> </th>
					<th class="col-currency"><?=lang('amount')?> </th>
				</tr> </thead> <tbody>
				<?php
                setlocale(LC_ALL, config_item('locale').".UTF-8");
				if (!empty($client_invoices)) {
				foreach ($client_invoices as $key => $invoice) { ?>
				<tr>
					<td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>
					<td><?=strftime(config_item('date_format'), strtotime($invoice->date_saved));?> </td>
					<td><?=strftime(config_item('date_format'), strtotime($invoice->due_date));?> </td>
					<td><small><?php $cur = $this->applib->currencies($invoice->currency); ?><?=$cur->symbol?></small> 
                <?=number_format($this->applib->invoice_payable($invoice->inv_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?> </td>
				</tr>
				<?php  } } ?>
				
				
				
			</tbody>
		</table></section>
                                                            
        <section class="panel panel-default">
                <header class="panel-heading"><i class="fa fa-link"></i> <?=strtoupper(lang('links'))?> </header>
                <table id="table-client-details-3" class="table table-striped b-t b-light text-sm AppendDataTables">
                        <thead>
                           <tr>
                            <th><?=lang('link_title')?></th>
                            <th class="col-options no-sort"><?=lang('options')?></th>
                          </tr> 
                             </thead>
                                <tbody>
                                     <?php if (!empty($client_links)) {
                                        foreach ($client_links as $link) { ?>
                                            <tr>
                                             <td>
                                             <img class="favicon" src="http://www.google.com/s2/favicons?domain=<?=$link->link_url;?>" />
                                            <a href="<?=base_url()?>projects/view/<?=$link->project_id?>?group=links&view=link&id=<?=$link->link_id?>"><?=$link->link_title?></a></td>
                                            <td>
                                            <?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_links',$link->project_id)) {  ?>
                                            <a href="<?=base_url()?>projects/links/pin/<?=$link->project_id;?>/<?=$link->link_id?>" title="<?=lang('link_pin');?>" class="foAjax btn btn-xs <?=($i->co_id == $link->client ? 'btn-danger':'btn-default');?> btn"><i class="fa fa-thumb-tack"></i>
                                            </a>
                                    <?php } ?>
                                        <a href="<?=$link->link_url?>" target="_blank" title="<?=$link->link_title?>" class="btn btn-xs btn-primary"><i class="fa fa-external-link text-white"></i></a>
                                </td>
                                        </tr>
                                            <?php  } } ?>
                                            </tbody>
                                </table>
        </section>
                                                            
                                                            
							</div>
							<!-- Client Projects -->
							<div class="col-md-6">
							<section class="panel panel-default">
                        <header class="panel-heading"><i class="fa fa-suitcase"></i> <?=strtoupper(lang('projects'))?> </header>
                                <table id="table-client-details-4" class="table table-striped b-t b-light text-sm AppendDataTables">
                                <thead>
                                        <tr>
                                                <th><?=lang('project_code')?></th>
                                                <th><?=lang('project_name')?></th>
                                                <th><?=lang('progress')?> </th>
                                        </tr> </thead> <tbody>
                                        <?php
                                        if (!empty($client_projects)) {
                                        foreach ($client_projects as $key => $project) { ?>
                                        <tr>
                                                <td><a class="text-info" href="<?=base_url()?>projects/view/<?=$project->project_id?>">
                                                <?=$project->project_code?></a></td>
                                                <td><?=$project->project_title?> </td>
                                                <td><div class="progress progress-xs m-t-xs progress-striped active m-b-none">
                                        <div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="<?=$project->progress?>%" style="width: <?=$project->progress?>%">
                                                                                                </div>
                                                                                        </div>
                                                </td>
                                        </tr>
                                        <?php  } } ?>
                                        </tbody>
                                </table>
							</section></div>
                                                        <!-- End -->
						</div>
					</section>
				</section>
			</section>
		</aside>
		<!-- /.aside -->
		<!-- .aside -->
		<aside class="aside-lg bg-white b-l hide" id="aside">
			<section class="vbox">
				<section class="scrollable wrapper">
					<?php
					echo form_open(base_url().'companies/update'); ?>
					<?php echo validation_errors(); ?>
					<input type="hidden" name="company_ref" value="<?=$i->company_ref?>">
					<input type="hidden" name="co_id" value="<?=$i->co_id?>">
					<div class="form-group">
						<label><?=lang('company_name')?> <span class="text-danger">*</span></label>
						<input type="text" name="company_name" value="<?=$i->company_name?>" class="input-sm form-control" required>
					</div>
					<div class="form-group">
						<label><?=lang('company_email')?> <span class="text-danger">*</span></label>
						<input type="email" name="company_email" value="<?=$i->company_email?>" class="input-sm form-control" required>
					</div>
					<div class="form-group">
						<label><?=lang('phone')?> </label>
						<input type="text" value="<?=$i->company_phone?>" name="company_phone"  class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('website')?> </label>
						<input type="text" value="<?=$i->company_website?>" name="company_website"  class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('address')?> <span class="text-danger">*</span></label>
						<textarea name="company_address" class="form-control"><?=$i->company_address?></textarea>
					</div>
					<div class="form-group">
						<label><?=lang('city')?> </label>
						<input type="text" value="<?=$i->city?>" name="city" class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('vat')?> </label>
						<input type="text" value="<?=$i->VAT?>" name="VAT" class="input-sm form-control">
					</div>
					<!-- Start Additional fields -->
					<p><a href="#additional_fields" data-toggle="class:show"><?=lang('additional_fields')?></a></p>
					<div id="additional_fields" class="hide">

					<div class="form-group">
						<label><?=lang('account_username')?> </label>
						<input type="text" value="<?=$i->account_username?>" name="account_username" class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('account_password')?> </label>
						<input type="password" value="<?=$i->account_password?>" name="account_password" class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('port')?> </label>
						<input type="text" value="<?=$i->port?>" name="port" class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('hostname')?> </label>
						<input type="text" value="<?=$i->hostname?>" name="hostname" class="input-sm form-control">
					</div>
					<div class="form-group">
						<label><?=lang('hosting_company')?> </label>
						<input type="text" value="<?=$i->hosting_company?>" name="hosting_company" class="input-sm form-control">
					</div>
					</div> 
					<!-- End Additional Fields -->
					<div class="form-group">
						<label><?=lang('country')?> </label>
						<select class="select2-option" style="width:200px" name="country" >
							<optgroup label="<?=lang('selected_country')?>">
								<option value="<?=$i->country?>"><?=$i->country?></option>
							</optgroup>
							<optgroup label="<?=lang('other_countries')?>">
								<?php foreach ($countries as $country): ?>
								<option value="<?=$country->value?>"><?=$country->value?></option>
								<?php endforeach; ?>
							</optgroup>
						</select>
					</div>
                                        <div class="form-group">
                                            <label><?=lang('language')?> <span class="text-danger">*</span></label>
                                            <select name="language" class="form-control">
                                            <?php foreach ($languages as $lang) : ?>
                                            <option value="<?=$lang->name?>"<?=($i->language == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                            <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php $currency = $this->applib->currencies($i->currency); ?>
                                        <div class="form-group">
                                            <label><?=lang('currency')?></label>
                                            <select name="currency" class="form-control">
                                            <?php foreach ($currencies as $cur) : ?>
                                            <option value="<?=$cur->code?>"<?=($currency->code == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                            <?php endforeach; ?>
                                            </select>
                                        </div>
					<button type="submit" class="btn btn-sm btn-success"><?=lang('save_changes')?></button>
					<hr>
				</form>
				
			</section></section>
			
		</aside>
		<!-- /.aside -->
		<?php }} ?>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>