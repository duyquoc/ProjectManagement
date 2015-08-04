<section id="content"> <section class="vbox"> <section class="scrollable padder">
	<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
		<li><a href="<?=base_url()?>"><i class="fa fa-home"></i> <?=lang('home')?></a></li>
		<li><a href="<?=base_url()?>projects/view_projects/all"><?=lang('projects')?></a></li>
		<li class="active"><?=lang('edit_project')?></li>
	</ul>
	<?php  echo modules::run('sidebar/flash_msg');?>

<!-- Start create project -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('project_details')?></header>
	<div class="panel-body">
<?php
								if (!empty($project_details)) {
				foreach ($project_details as $key => $project) { ?>
<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'collaborator/projects/edit',$attributes); ?>
			 <input type="hidden" name="project_id" value="<?=$project->project_id?>">
          				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=$project->project_title?>" readonly name="project_title">
				</div>
				</div>	
				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('start_date')?></label> 
				<div class="col-lg-3">
				<input type="text" class="form-control" value="<?=strftime(config_item('date_format'), strtotime($project->start_date));?>" readonly>
				</div> 
				</div> 
				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('due_date')?></label> 
				<div class="col-lg-3">
				<input type="text" class="form-control" value="<?=strftime(config_item('date_format'), strtotime($project->due_date));?>" readonly>
				</div> 
				</div> 
				<div class="form-group"> 
				<label class="col-lg-2 control-label"><?=lang('progress')?></label>
				<div class="col-lg-8"> 
					<input class="slider slider-horizontal form-control" type="text" value="<?=$project->progress?>" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?=$project->progress?>" data-slider-orientation="horizontal" name="progress" > 
				</div>
				</div> 


				<div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('estimated_hours')?></label>
				<div class="col-lg-2">
					<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate">
				</div>
				</div>	

				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> <?=lang('save_changes')?></button>


				
		</form>
		<?php } } ?>
</div>
</section>
</div>


<!-- End create project -->
</section>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>