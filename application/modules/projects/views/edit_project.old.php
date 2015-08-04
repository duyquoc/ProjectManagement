<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<div class="wrapper b-b header"><?=lang('all_projects')?>
			</div>
			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
			<ul class="nav">
			<?php
				if (!empty($projects)) {
			foreach ($projects as $key => $p) { 
						$project_hours = $this->user_profile->project_hours($p->project_id);
						$hours_spent = round($project_hours, 1);
						$fix_rate = $this->user_profile->get_project_details($p->project_id,'fixed_rate');
						$hourly_rate = $this->user_profile->get_project_details($p->project_id,'hourly_rate');
						if ($fix_rate == 'No') {
							$cost = $hours_spent * $hourly_rate;
						}else{
							$cost = $this->user_profile->get_project_details($p->project_id,'fixed_price');
						}
					?>
				<li class="b-b b-light">
				<a href="<?=base_url()?>projects/view/details/<?=$p->project_id?>" data-toggle="tooltip" data-original-title="<?=$p->project_title?>">
				<?=ucfirst($this->applib->company_details($p->client,'company_name'))?>
				<div class="pull-right">
				<small class="text-muted"><strong><?=$this->config->item('default_currency')?> <?=number_format($cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small>
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
			<?php
								if (!empty($project_details)) {
				foreach ($project_details as $key => $project) { ?>
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
						<div class="btn-group">
						
						</div>
						<a class="btn btn-sm btn-dark" href="<?=base_url()?>projects/view/add" title="<?=lang('new_project')?>" data-original-title="<?=lang('new_project')?>" data-toggle="tooltip" data-placement="bottom">
						<i class="fa fa-plus"></i> <?=lang('new_project')?></a>
						<a class="btn btn-sm btn-danger" href="<?=base_url()?>projects/delete/<?=$project->project_id?>" title="<?=lang('delete_project')?>" data-toggle="ajaxModal" data-placement="bottom">
						<i class="fa fa-trash-o"></i> <?=lang('delete_project')?></a>
						</div>
						<div class="col-sm-4 m-b-xs">
						<?php  echo form_open(base_url().'projects/search'); ?>
							<div class="input-group">
								<input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?> <?=lang('project')?>">
								<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?>!</button>
								</span>
							</div>
							</form>
						</div>
					</div> </header>
					<section class="scrollable wrapper w-f">
					<!-- Start create project -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('project_details')?></header>
	<div class="panel-body">

<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/view/edit',$attributes); ?>
          <?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
			 <input type="hidden" name="project_id" value="<?=$project->project_id?>">
          				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=$project->project_title?>" name="project_title">
				</div>
				</div>		

				<div class="form-group">
			        <label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
			        <div class="col-lg-6">
			          <div class="m-b"> 
			          <select id="select2-option" style="width:260px" name="client" required> 
			          <optgroup label="Selected"> 
			          <option value="<?=$project->client?>"><?=ucfirst($this->applib->company_details($project->client,'company_name'))?></option>
			          </optgroup> 
			          <optgroup label="Clients"> 
			            <?php
			            if (!empty($clients)) {
			            foreach ($clients as $key => $c) { ?>
			            <option value="<?=$c->co_id?>"><?=strtoupper($c->company_name)?></option>
			            <?php }} ?>
			          </optgroup>  
			          </select> 
			          </div> 
			        </div>
			      </div>
				
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('start_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=$project->start_date?>" name="start_date" data-date-format="dd-mm-yyyy" >
				</div> 
				</div> 
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('due_date')?></label> 
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=$project->due_date?>" name="due_date" data-date-format="dd-mm-yyyy" >
				</div> 
				</div> 
				<div class="form-group"> 
				<label class="col-lg-3 control-label"><?=lang('progress')?></label>
				<div class="col-lg-8"> 
					<input class="slider slider-horizontal form-control" type="text" value="<?=$project->progress?>" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?=$project->progress?>" data-slider-orientation="horizontal" name="progress" > 
				</div>
				</div> 

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">


			<select id="select2-option" multiple="multiple" style="width:260px" name="assign_to[]" > 
    			<optgroup label="Staff"> 

    				<?php if (!empty($assign_to)) {
                           foreach ($assign_to as $user): ?>
        <option value="<?=$user->id?>" <?php
						   foreach (unserialize($project->assign_to) as $value) {
                           if ($user->id == $value) { ?>
                               selected = "selected"
                           <?php } else { }
						   } ?> ><?=ucfirst($user->username)?></option>
                            <?php endforeach ?>
                            <?php } ?>	
					</optgroup> 
    			</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label> 
				<div class="col-lg-3">
				<div class="checkbox"> 
					<label class="checkbox-custom"> 
					<input name="fixed_rate" id="fixed_rate" <?php if($project->fixed_rate == 'Yes'){ echo "checked=\"checked\""; }?> type="checkbox"> Yes 
					</label> 
					</div>
				</div> 
				</div> 
				<div id="hourly_rate" <?php if($project->fixed_rate == 'Yes'){ echo "style=\"display:none\""; }?>>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('hourly_rate')?>  (e.g 50 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->hourly_rate?>" name="hourly_rate">
				</div>
				</div>
				</div>
				<div id="fixed_price" <?php if($project->fixed_rate == 'No'){ echo "style=\"display:none\""; }?>>
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('fixed_price')?> (e.g 300 )</label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->fixed_price?>" name="fixed_price">
				</div>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('estimated_hours')?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate_hours">
				</div>
				</div>	

				
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> <?=lang('save_changes')?></button>


				
		</form>
		<?php } } ?>
</div>
</section>
</div>


<!-- End create project -->






					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->