<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
						<div class="btn-group">
						
						</div>
						
						<a class="btn btn-sm btn-dark" href="<?=base_url()?>contacts/add" data-toggle="ajaxModal" title="<?=lang('new_client')?>"><i class="fa fa-plus"></i> <?=lang('new_user')?></a>
						<a class="btn btn-sm btn-danger" href="<?=base_url()?>contacts" title="<?=lang('system_users')?>"><i class="fa fa-lock"></i> <?=lang('system_users')?></a>
						</div>
						<div class="col-sm-4 m-b-xs">
						
						</div >   
					</div> </header>
		<section class="scrollable wrapper">
					<section class="panel panel-default">
	<section class="scrollable w-f">
	
		
		<div class="panel-body"> 
		<?php
								if (!empty($user_details)) {
				foreach ($user_details as $key => $i) { ?>
				
		<!-- Start of tabs-->
<ul class="nav nav-tabs" id="stats">
<li class="active"><a href="#profile" data-toggle="tab"> <?=lang('profile')?> </a></li>


      <li><a href="<?=base_url()?>contacts/view/clientinvoices/<?=$i->id?>" data-target="#invoices" class="media_node active span" id="invoice_tab" data-toggle="tabajax" rel="tooltip"> <?=lang('invoices')?> </a></li>
      <li><a href="<?=base_url()?>contacts/view/clientprojects/<?=$i->id?>" data-target="#projects" class="media_node span" id="projects_tab" data-toggle="tabajax" rel="tooltip"> <?=lang('projects')?></a></li>

      <li><a href="<?=base_url()?>contacts/view/payments/<?=$i->id?>" data-target="#payments" class="media_node span" id="payments_tab" data-toggle="tabajax" rel="tooltip"><?=lang('recent_payments')?></a></li>

      <li><a href="<?=base_url()?>contacts/view/activities/<?=$i->id?>" data-target="#activities" class="media_node span" id="activities_tab" data-toggle="tabajax" rel="tooltip"><?=lang('recent_activities')?></a></li>
</ul>

<div class="tab-content">

<div class="tab-pane active" id="profile">
	<!-- Details START -->
<div class="col-md-6">
			<div class="group">
				<h4 class="subheader text-muted"><?=lang('contact_details')?></h4>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('company_name')?></div>
					<div class="col-md-6"><?=$i->company?></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('contact_person')?></div>
					<div class="col-md-6"><?=$i->fullname?$i->fullname:$i->username?></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('email')?></div>
					<div class="col-md-6"><?=$i->email?></div>
				</div>
			</div>	
			<div class="group">
				<h4 class="subheader text-muted"><?=lang('other_details')?></h4>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('country')?></div>
					<div class="col-md-6 text-success"><?=$i->country?></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('city')?></div>
					<div class="col-md-6"><?=$i->city?></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('vat')?></div>
					<div class="col-md-6"><?=$i->vat?></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('phone')?></div>
                                        <div class="col-md-6"><a href="tel:<?=$i->phone?>"><?=$i->phone?></a></div>
				</div>
				<div class="row inline-fields">
					<div class="col-md-4"><?=lang('portal_status')?></div>
					<div class="col-md-6 text-success"><?php if($i->activated == '1'){ echo "Enabled";}else{ echo "Disabled";}?></div>
				</div>
				</div>
			
		</div>
<div class="col-md-6">
			<div class="group">
				<div class="row" style="margin-top: 5px">
					<div class="rec-pay col-md-12">
						<h4 class="subheader text-muted"><?=lang('received_amount')?></h4>
                                                <?php $cur = $this->applib->client_currency($i->currency); ?>
						<h4 class="amount text-success cursor-pointer"><?=$cur->symbol?> <?=number_format($this->user_profile->client_paid($i->id),2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></h4>
						
						
					</div>
				</div>
			</div>
			</div>
          
<!-- Details END -->
</div> 


	<div class="tab-pane" id="invoices">      </div>
      <div class="tab-pane" id="projects">      </div>
      <div class="tab-pane" id="payments">      </div>
      <div class="tab-pane  urlbox span8" id="activities">      </div>
    </div> <?php } }?></div>

</section>



		</section>

		</section>




		</aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>