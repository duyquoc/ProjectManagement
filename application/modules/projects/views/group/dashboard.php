<?php $this->applib->set_locale(); ?>
<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <div class="panel-body">
        <?php
        $all_tasks = Applib::count_num_rows(Applib::$tasks_table,
                      array('project'=>$project_id));

        $done_tasks = Applib::count_num_rows(Applib::$tasks_table,
                      array('project'=>$project_id,'task_progress >='=>'100'));

        $in_progress = Applib::count_num_rows(Applib::$tasks_table,
                      array('project'=>$project_id,'task_progress <'=>'100'));

        if ($all_tasks > 0) {
        $perc_done = ($done_tasks/$all_tasks) *100;
        $perc_progress = ($in_progress/$all_tasks)*100;
        }else{
        $perc_done = $perc_progress = 0;
        }
        $progress =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'progress');
        $project_hours = $this -> applib -> pro_calculate('project_hours',$project_id);
        $project_cost = $this -> applib -> pro_calculate('project_cost',$project_id);
        $username = $this -> tank_auth -> get_username();
        ?>
        <div class="progress progress-xs m-t-sm">
          <div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%"></div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <ul class="list-group no-radius">
              <li class="list-group-item">
              <span class="pull-right"><?=Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'project_title')?></span><?=lang('project_name')?></li>
              <?php if ($role == '1' OR $role == '2' OR $this -> applib -> allowed_module('view_project_clients',$username)){ ?>
              <li class="list-group-item">
                <?php
                $client = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'client');
                $cur = $this->applib->client_currency($client);
                ?>
              <span class="pull-right"><?=Applib::get_table_field(Applib::$companies_table,array('co_id'=>$client),'company_name')?></span><?=lang('client_name')?></li>
              <?php } ?>
              <li class="list-group-item">
              <span class="pull-right"><?=strftime(config_item('date_format'), strtotime(Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'start_date')))?></span><?=lang('start_date')?></li>
              <li class="list-group-item">
                <?php
                $due_date = Applib::get_table_field(Applib::$projects_table,
                            array('project_id'=>$project_id),'due_date');
                $due_time = strtotime($due_date);
                $current_time = time();
                ?>
                <span class="pull-right"><?=strftime(config_item('date_format'), strtotime($due_date))?>
                <?php if ($current_time > $due_time AND $progress < 100){ ?>
                <span class="badge bg-danger"><?=lang('overdue')?></span>
                <?php } ?>
              </span><?=lang('due_date')?></li>
            </ul>
          </div>
          <!-- End details C1-->
          <div class="col-lg-6">
            <ul class="list-group no-radius">
              <li class="list-group-item">
                <span class="pull-right"><strong><?=$project_hours?> <?=lang('hours')?></strong></span><?=lang('logged_hours')?>
              </li>
              <?php if ($role == '1' OR $role == '2' OR $this -> applib -> allowed_module('view_project_cost',$username)){ ?>
              <li class="list-group-item">
                <span class="pull-right">
                <strong><?=$cur->symbol?>
                <?=number_format($project_cost,2,config_item('decimal_separator'),config_item('thousand_separator'))?>
                </strong>
        <?php
                if (Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'fixed_rate') == 'No') { ?>
            <small class="small text-muted">
          <?=Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'hourly_rate')."/".lang('hour')?>
        <?php } ?>
            </small>
                </span> 
                <?=lang('project_cost')?>
              </li>
              <?php } ?>
              <?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_team_members',$project_id)) { ?>
              <li class="list-group-item">
                <?php $assigned_users = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'assign_to'); ?>
                <span class="pull-right">
                <small class="small">
                <?php foreach (unserialize($assigned_users) as $value) {
                    $users[] = ucfirst(Applib::login_info($value)->username);
                } echo implode(", ",$users); ?>
                </small>
                </span><?=lang('assigned_to')?>
              </li>
              <?php } ?>
              <li class="list-group-item">
                <span class="pull-right"><span class="label label-success"> <?=Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'timer')?></span></span><?=lang('timer_status')?>
              </li>
            </ul>
          </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <?php $desc = Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'description'); ?>
      <blockquote class="small text-muted"><?=nl2br($desc)?></blockquote>
      <!-- End details -->
    </div>
  </section>
</div>
</div>
<!-- End ROW 1 -->
<div class="row">
<div class="col-lg-6">
  <section class="panel panel-default">
  <header class="panel-heading"><?=$all_tasks?> <?=lang('tasks')?></header>
  <section class="panel-body">
    <div class="text-center">
      <div class="inline ">
        <div class="easypiechart text-success" data-percent="<?=$perc_done?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#8ec165" data-rotate="0" data-scale-Color="false" data-size="115" data-animate="2000">
          <span class="h2 step font-bold"><?=$perc_done?></span>%
          <div class="easypie-text text-muted"><?=lang('done_tasks')?></div>
        </div>
        <div class="font-bold m-t"><?=lang('total')?> <?=$done_tasks?></div>
      </div>
      <div class="inline ">
        <div class="easypiechart text-info" data-percent="<?=$perc_progress?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#4cc0c1" data-rotate="0" data-scale-Color="false" data-size="115" data-animate="2000">
          <span class="h2 step font-bold"><?=$perc_progress?></span>%
          <div class="easypie-text text-muted"><?=lang('in_progress')?></div>
        </div>
        <div class="font-bold m-t"><?=lang('total')?> <?=$in_progress?></div>
      </div>
      
    </div>
  </section>
</section>
</div>
<!-- END TASKS -->
<?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_files',$project_id)) { ?>
<div class="col-sm-6">
<section class="panel panel-default">
<header class="panel-heading"><?=lang('recent_files')?></header>
<table class="table table-striped m-b-none">
  <thead>
    <tr>
      <th><?=lang('file_name')?></th>
      <th></th>
      <th width="70"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $files = Applib::retrieve(Applib::$files_table,array('project'=>$project_id),$limit = 5);
    if (!empty($files)) {
    foreach ($files as $key => $f) { 
  $icon = $this->applib->file_icon($f->ext);
  $path = $f->path;
  $file_path = ($path != NULL) 
                  ? base_url().'resource/project-files/'.$path.$f->file_name 
                  : base_url().'resource/project-files/'.$f->file_name;
  $real_url = $file_path;
        ?>
    <tr>
      <td>
                  <?php if ($f->is_image == 1) : ?>
        <?php if ($f->image_width > $f->image_height) {
            $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
            $style = 'height:100%; margin-left: -'.$ratio.'%';
        } else {
            $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
            $style = 'width:100%; margin-top: -'.$ratio.'%';
        }  ?>
            <div class="file-icon icon-small"><a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a></div>
        <?php else : ?>
            <div class="file-icon icon-small"><i class="fa <?=$icon?> fa-lg"></i></div>
        <?php endif; ?>

          
          <a href="<?=base_url()?>projects/files/download/<?=$f->file_id?>" data-original-title="<?=$f->description?>" data-toggle="tooltip" data-placement="top" title = "">
            <?php
            if (empty($f->title)) { 
                echo $this->applib->short_string($f->file_name, 10, 8, 22);
            } else {
                echo $this->applib->short_string($f->title, 20, 0, 22);
            }
            
            ?>
          
          </a></td>

      <td>
        <a class="btn btn-xs text-info" href="<?=base_url()?>projects/files/download/<?=$f->file_id?>"><i class="fa fa-download"></i></a>
      </td>
          <td class="text-success">
            <?=ucfirst(Applib::login_info($f->uploaded_by)->username)?>
          </td>
    </tr>
    <?php } } ?>
  </tbody>
</table>
</section>
</div>
<?php } ?>
<!-- END FILES -->
</div>
<!-- END ROW -->
<div class="row">
<?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_tasks',$project_id)) { ?>
<div class="col-sm-6">
<section class="panel panel-default">
<header class="panel-heading"><?=lang('recent_tasks')?></header>
<table class="table table-striped m-b-none">
<thead>
  <tr>
    <th><?=lang('action')?></th>
    <th><?=lang('task_name')?></th>
    <th width="70"></th>
  </tr>
</thead>
<tbody>
  <?php
  $tasks = Applib::retrieve(Applib::$tasks_table,array('project'=>$project_id,'task_progress <'=> 100),$limit = 10);
  if (!empty($tasks)) {
  foreach ($tasks as $key => $t) { ?>
  <tr>
    <td>
      <a class="btn btn-xs btn-info" href="<?=base_url()?>projects/view/<?=$t->project?>?group=tasks&view=task&id=<?=$t->t_id?>"><?=lang('preview')?></a>
    </td>
    <td class="<?php if($t->task_progress >= 100 ) { echo 'text-lt'; } ?>"><?=$t->task_name?></td>
    <td class="text-success"><?=ucfirst(Applib::login_info($t->added_by)->username)?></td>
  </tr>
  <?php } } ?>
</tbody>
</table>
</section>
</div>
<?php } ?>
<!-- END TASKS -->
<?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_bugs',$project_id)) { ?>
<div class="col-sm-6">
<section class="panel panel-default">
<header class="panel-heading"><?=lang('recent_bugs')?></header>
<table class="table table-striped m-b-none">
<thead>
<tr>
  <th><?=lang('action')?></th>
  <th><?=lang('issue_ref')?></th>
  <th width="70"></th>
</tr>
</thead>
<tbody>
<?php
$bugs = Applib::retrieve(Applib::$bugs_table,array('project'=>$project_id),$limit = 10);
if (!empty($bugs)) {
foreach ($bugs as $key => $b) { ?>
<tr>
  <td>
    <a class="btn btn-xs btn-info" href="<?=base_url()?>projects/view/<?=$project_id?>/?group=bugs&view=bug&id=<?=$b->bug_id?>"><?=lang('preview')?></a>
  </td>
  <td><?=$b->issue_ref?></td>
  <td class="text-success"><?=ucfirst(Applib::login_info($b->reporter)->username)?></td>
</tr>
<?php } } ?>
</tbody>
</table>
</section>
</div>
<?php } ?>
<!-- END FILES -->
</div>