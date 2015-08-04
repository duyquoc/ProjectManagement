
<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
<?php
    $username = $this -> tank_auth -> get_username();
    if($role == '1' OR $this -> applib -> allowed_module('add_estimates',$username)) { ?>
		<a href="<?=base_url()?>estimates/add" data-original-title="<?=lang('create_estimate')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-default btn-sm pull-right"><i class="fa fa-plus"></i></a>
<?php } ?>
		<p class="h4"><?=lang('all_estimates')?></p>
		</header>


			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			
			   <?=$this->load->view('sidebar/estimates',$estimates)?>

			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
						
						</div>
						<div class="col-sm-4 m-b-xs">
						<?php  echo form_open(base_url().'estimates/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?>!</button>
								</span>
							</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable wrapper w-f">



					<!-- Start create estimate -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('estimate_details')?></header>
	<div class="panel-body">

<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'estimates/add',$attributes); ?>
			 
          		<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=config_item('estimate_prefix')?><?=random_string('nozero', 5);?>" name="reference_no">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select class="select2-option" style="width:260px" name="client" >
					<optgroup label="<?=lang('clients')?>"> 
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
				<label class="col-lg-2 control-label"><?=lang('due_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div>	

				<div class="form-group">
			<label class="col-lg-2 control-label"><?=lang('default_tax')?> </label>
			<div class="col-lg-4">
				<div class="input-group m-b">
					<span class="input-group-addon">%</span>
					<input class="form-control " type="text" value="<?=$this->config->item('default_tax')?>" name="tax">
				</div>
				
			</div>
			<a class="btn btn-sm btn-info" href="#discounts" data-toggle="class:show"><?=lang('discount')?></a>
		</div>	

		<!-- Start discount fields -->
					
					<div id="discounts" class="hide">

					<div class="form-group">
					<label class="col-lg-2 control-label"><?=lang('discount')?> </label>
					<div class="col-lg-4">
						<div class="input-group m-b">
							<span class="input-group-addon">%</span>
							<input class="form-control " type="text" value="0" name="discount">
						</div>
					</div>
				</div>

					</div> 
					<!-- End discount Fields -->


				
				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('notes')?> </label>
				<div class="col-lg-8">
				<textarea name="notes" class="form-control"><?=config_item('estimate_terms')?></textarea>
				</div>
				</div>
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?=lang('create_estimate')?></button>


				
		</form>
</div>
</section>
</div>


<!-- End create estimate -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->