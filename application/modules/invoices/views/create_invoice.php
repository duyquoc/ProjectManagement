
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
			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
						<div class="btn-group">
						
						</div>
						
						</div>
						<div class="col-sm-4 m-b-xs">
						
						</div>
					</div> </header>
					<section class="scrollable wrapper">

					 <!-- Start create invoice -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('invoice_details')?></header>
	<div class="panel-body">

<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/add',$attributes);
           ?>
			 <?php echo validation_errors(); ?>
			

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=config_item('invoice_prefix')?><?php 
						if(config_item('increment_invoice_number') == 'FALSE'){ 
						$this->load->helper('string');
						echo random_string('nozero', 6);
						}else{ echo $this -> applib -> generate_invoice_number(); } ?>" name="reference_no">
				</div>
				
				</div>

				

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
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
				<label class="col-lg-3 control-label"><?=lang('due_date')?></label> 
				<div class="col-lg-6">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 

				<div class="form-group">
			<label class="col-lg-3 control-label"><?=lang('default_tax')?> </label>
			<div class="col-lg-6">
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
					<label class="col-lg-3 control-label"><?=lang('discount')?> </label>
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
							    <label class="col-lg-3 control-label"><?=lang('allow_2checkout')?></label>
		                      	<div class="col-lg-4">
		                        	<label class="switch">
		                          		<input type="checkbox" name="allow_2checkout">
		                         		<span></span>
		                        	</label>
		                      </div>
            		</div>
							<div class="form-group">
							    <label class="col-lg-3 control-label"><?=lang('allow_paypal')?></label>
		                      	<div class="col-lg-4">
		                        	<label class="switch">
		                          		<input type="checkbox" name="allow_paypal">
		                         		<span></span>
		                        	</label>
		                      </div>
            				</div>
		                    <div class="form-group">
		                      	<label class="col-lg-3 control-label"><?=lang('allow_stripe')?></label>
		                      	<div class="col-lg-4">
		                        	<label class="switch">
										<input type="checkbox" name="allow_stripe">
										<span></span>
		                        	</label>
		                      	</div>
		                    </div>
            				<div class="form-group">
              					<label class="col-lg-3 control-label"><?=lang('allow_bitcoin')?></label>
              					<div class="col-lg-4">
                					<label class="switch">
							<input type="checkbox" name="allow_bitcoin">
			                        	<span></span>
                                                        </label>
                                                </div>
			                </div>
				
				<div class="form-group terms">
				<label class="col-lg-3 control-label"><?=lang('notes')?> </label>
				<div class="col-lg-9">
				<textarea name="notes" class="form-control foeditor"><?=$this->config->item('default_terms')?></textarea>
                                <input type="hidden" name="currency" value="" />
				</div>
				</div>


				

				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> <?=lang('create_invoice')?></button>


				
		</form>
</div>
</section>
</div>


<!-- End create invoice -->



					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->






