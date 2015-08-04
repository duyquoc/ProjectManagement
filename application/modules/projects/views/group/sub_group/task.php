<?php $this->applib->set_locale(); ?>
<section class="panel panel-default">
<?php
$task = isset($_GET['id']) ? $_GET['id'] : 0;
if($role != '2'){
$details = Applib::retrieve(Applib::$tasks_table,array('t_id'=>$task));
}else{
  $details = Applib::retrieve(Applib::$tasks_table,array('t_id'=>$task,'visible' => 'Yes'));
}

  if (!empty($details)) {
      foreach ($details as $key => $t) {
      if($t->project == $project_id){
      ?>
      
<header class="header bg-white b-b clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">

                  <a href="<?=base_url()?>projects/tasks/file/<?=$t->project?>/<?=$t->t_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('attach_file')?></a> 
  <?php if($role == '1' OR $role == '3' OR $t->added_by == $this->tank_auth-> get_user_id()){ ?>
                  <a href="<?=base_url()?>projects/tasks/edit/<?=$t->t_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark"><?=lang('edit_task')?></a>
  <?php } if($role == '1'){ ?> 
                  <a href="<?=base_url()?>projects/tasks/delete/<?=$t->project?>/<?=$t->t_id?>" data-toggle="ajaxModal" title="<?=lang('delete_task')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o text-white"></i> <?=lang('delete_task')?></a>
<?php } ?>
                     

                    </div>
                  </div>
                </header>

<div class="row">
<div class="col-lg-12">

<section class="panel panel-body">
<div class="col-lg-12">
<div class="col-lg-2"><?=lang('progress')?></div>
<div class="col-lg-10">
    <div class="progress progress-xs m-t-sm"> 
            <div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="<?=$t->task_progress?>%" style="width: <?=$t->task_progress?>%">
            </div>
    </div>
  </div>
  </div>

<?=$this->session->flashdata('form_error')?>
<div class="col-lg-6">
<ul class="list-group no-radius">
    <li class="list-group-item">
      <span class="pull-right"><?=$t->task_name?> </span><?=lang('task_name')?></li>

    <li class="list-group-item">
      <span class="pull-right">
      <?=Applib::get_table_field(Applib::$projects_table,array('project_id'=>$t->project),'project_title');?>
      </span>
      <?=lang('project')?>
    </li>

      <?php if($role != '2'){ ?> 
    <li class="list-group-item">
      <span class="pull-right"><?=$t->visible?></span><?=lang('visible_to_client')?></li>
      <?php } ?>
    <li class="list-group-item">
      <span class="pull-right"><?=strftime(config_item('date_format'), strtotime($t->due_date))?></span>
      <?=lang('due_date')?>
    </li>
       
</ul>
</div>
<!-- End details C1-->
<div class="col-lg-6">

<ul class="list-group no-radius">
    <li class="list-group-item">
      <span class="pull-right"><strong><?=$this-> applib -> get_time_spent($this->applib->task_time_spent($t->t_id))?></strong></span><?=lang('logged_hours')?></li>
    <li class="list-group-item">
      <span class="pull-right"><?=$t->estimated_hours?> <?=lang('hours')?></span><?=lang('estimated_hours')?> </li>

      <?php if($role != '2'){ ?> 

    <li class="list-group-item">
    <?php
      $assigned_users = Applib::get_table_field(Applib::$tasks_table,array('t_id'=>$t->t_id),'assigned_to');
    ?>
      <span class="pull-right">
      <small class="small">
<?php error_reporting(0);
foreach (unserialize($assigned_users) as $value) {
    $users[] = ucfirst(Applib::login_info($value)->username);
  } echo implode(", ",$users); ?>
  </small></span><?=lang('assigned_to')?></li>

  <?php } ?>

    <li class="list-group-item">
      <span class="pull-right"><span class="label label-success"> <?=$t->timer_status?></span></span><?=lang('timer_status')?></li>
       
</ul>

</div>

<div class="line line-dashed line-lg pull-in"></div>
<blockquote>
    <?=lang('milestone')?>: <a href="<?=base_url()?>projects/view/<?=$t->project?>/?group=milestones&view=milestone&id=<?=$t->milestone?>" class="text-primary">
    <?=($t->milestone) ? Applib::get_table_field(Applib::$milestones_table,
              array('id'=>$t->milestone),'milestone_name') : '';
    ?></a>
  </blockquote>
<p><blockquote><?=$t->description?></blockquote></p>
<!-- End details -->

    <?php
$this->load->helper('file');
$files = Applib::retrieve(Applib::$task_files_table,array('task'=>$task));

  if (!empty($files)) {
      foreach ($files as $key => $f) {
  $icon = $this->applib->file_icon($f->file_ext);
  $real_url = ($f->path != NULL) 
                  ? base_url().'resource/project-files/'.$f->path.$f->file_name 
                  : base_url().'resource/project-files/'.$f->file_name;
  ?>


<div class="file-small">
        <?php if ($f->is_image == 1) : ?>
        <?php if ($f->image_width > $f->image_height) {
            $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
            $style = 'height:100%; margin-left: -'.$ratio.'%';
        } else {
            $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
            $style = 'width:100%; margin-top: -'.$ratio.'%';
        }  ?>
            <div class="file-icon icon-small"><a href="<?=base_url()?>projects/tasks/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a></div>
        <?php else : ?>
            <div class="file-icon icon-small"><i class="fa <?=$icon?> fa-lg"></i></div>
        <?php endif; ?>

        <a data-toggle="tooltip" data-placement="top" data-original-title="<?=$f->description?>" class="text-info" href="<?=base_url()?>projects/tasks/download/<?=$f->file_id?>">
        <?=(empty($f->title) ? $f->file_name : $f->title)?>
        </a>
        <?php  if($f->uploaded_by == $this-> tank_auth -> get_user_id() OR $role == '1'){ ?>
        <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/tasks/file/delete/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
        <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/tasks/file/edit/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
        <?php } ?>
</div>

<?php } } ?>


<?php  } } ?>
 </section>
 <?php } ?>
  </div> 

  </div>
  <!-- End ROW 1 -->
  </section>

