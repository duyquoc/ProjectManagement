<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	 <?php $username = $this -> tank_auth -> get_username(); ?>

		<aside class="aside-md bg-white b-r" id="subNav">
		 <header class="dk header b-b">
                 
                  <p class="h4 text-muted"><?=lang('all_projects')?></p>
                </header>

			<section class="vbox">
			 <section class="scrollable">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<ul class="nav">
			<?php
				if (!empty($projects)) {
			foreach ($projects as $key => $p) { 
$project_cost = $this -> applib -> pro_calculate('project_cost',$p->project_id);
					?>
				<li class="b-b b-light">
				<a href="<?=base_url()?>projects/view/<?=$p->project_id?>" data-toggle="tooltip" data-original-title="<?=$p->project_title?>">
				<?php if($role == '1' OR $this -> applib -> allowed_module('view_project_clients',$username)){ ?>
				<?=ucfirst(Applib::get_table_field('companies',array('co_id'=>$p->client),'company_name'))?>
				<?php }else{ echo $p->project_code; } ?>
				<div class="pull-right">
				<small class="text-muted">
				<?php if($this -> applib -> allowed_module('view_project_cost',$username) OR $role == '1'){ ?>
                                    <?php $cur_d = $this->applib->currencies($p->currency); ?>
				<strong><?=$cur_d->symbol?> <?=number_format($project_cost,2,config_item('decimal_separator'),config_item('thousand_separator'))?></strong>
				<?php } ?>
				</small>
				</div> <br>
				<small class="block text-muted"><?=$p->project_title?> 
				<?php if($p->timer == 'On'){ ?><i class="fa fa-clock-o text-danger"></i> <?php } ?></small>

				</a> </li>
				<?php } }?>
			</ul> 
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
						
						</div>
					</div> </header>
					<section class="scrollable wrapper">
					<!-- Start Project Form -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('project_details')?></header>
	<div class="panel-body">
<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/add',$attributes); ?>
			 <?=$this->session->flashdata('form_error') ?>
          				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=config_item('project_prefix')?><?=random_string('nozero', 5);?>" name="project_code">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" placeholder="<?=lang('project_title')?>" name="project_title">
				</div>
				</div>	

<?php if ($role == '1' OR $this -> applib -> allowed_module('add_projects',$username)) { ?>
				<div class="form-group">
			        <label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
			        <div class="col-lg-6">
			          <select class="select2-option form-control" name="client"  required> 
			          
			            <?php
			            if (!empty($clients)) {
			            foreach ($clients as $key => $c) { ?>
			            <option value="<?=$c->co_id?>"><?=strtoupper($c->company_name)?></option>
			            <?php }} ?>
			          </select> 
			        </div>
			      </div>	


			      <div class="form-group"> 
				<label class="col-lg-3 control-label"><?=lang('progress')?></label>
				<div class="col-lg-8"> 
					<input class="slider slider-horizontal form-control" type="text" value="" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="0" data-slider-orientation="horizontal" name="progress" > 
				</div>
				</div> 
				
<?php if ($role == '1') { ?>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
				    <!-- Build your select: -->
				<select class="select2-option" multiple="multiple" required style="width:260px" name="assign_to[]" > 
    			<optgroup label="<?=lang('admin_staff')?>"> 
    				<?php
					if (!empty($assign_to)) {
					foreach ($assign_to as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst($user->username)?></option>
					<?php } } ?>	
					</optgroup> 
    			</select>
				</div>
				</div>

				<?php } ?>

				<?php } ?>


				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('start_date')?> <span class="text-danger">*</span></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('due_date')?> <span class="text-danger">*</span></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div> 
				</div> 

				<div class="form-group">
                      <label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" id="fixed_rate" name="fixed_rate">
                          <span></span>
                        </label>
                      </div>
                    </div>
				
				

				
				<div id="hourly_rate">
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" placeholder="50" name="hourly_rate">
				</div>
				</div>
				</div>

				<div id="fixed_price" style="display:none">
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" placeholder="300" name="fixed_price">
				</div>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('estimated_hours')?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="3" name="estimate_hours">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="description" class="form-control" placeholder="<?=lang('description')?>" required></textarea>
				</div>
				</div>
				
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?=lang('create_project')?></button>


				
		</form>
</div>
</section>
</div>

					 

					 <!-- End Project Form -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->