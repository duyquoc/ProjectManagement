<?php $this->applib->set_locale(); ?>
<section class="panel panel-default">
  <?php
  $milestone = isset($_GET['id']) ? $_GET['id'] : 0;
  $details = $this -> db -> where(array('id'=>$milestone)) -> get('milestones') -> result();
  if (!empty($details)) {
    foreach ($details as $key => $m) {
      if($m->project == $project_id){
      ?>
    <header class="header bg-white b-b clearfix">
      <div class="row m-t-sm">
        <div class="col-sm-12 m-b-xs">
          <?php if($role == '1'){ ?>
           <a href="<?=base_url()?>projects/milestones/add_task/<?=$m->id?>/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('add_task')?></a>

            <a href="<?=base_url()?>projects/milestones/edit/<?=$m->id?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark"><?=lang('edit_milestone')?></a> 

            <a href="<?=base_url()?>projects/milestones/delete/<?=$m->project?>/<?=$m->id?>" data-toggle="ajaxModal" title="<?=lang('delete_milestone')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o text-white"></i> <?=lang('delete_milestone')?></a>
          <?php }?>
        </div>
      </div>
    </header>
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-2"><?=lang('progress')?></div>
        <div class="col-lg-10">
          <?php
          $progress = $this -> applib -> cal_milestone_progress($m->id);
          ?>
          <div class="progress progress-xs m-t-sm"> 
            <div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=$m->milestone_name?> </span><?=lang('milestone_name')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><?=$this -> applib->get_any_field('projects',array('project_id'=>$m->project),'project_title');?></span><?=lang('project')?>
            </li>
          </ul>
        </div>
        <!-- End details C1-->
        <div class="col-lg-6">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=strftime(config_item('date_format'), strtotime($m->start_date))?></span><?=lang('start_date')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right">
                <?php
                    $due_date = $m->due_date;
                    $due_time = strtotime($due_date);
                    $current_time = time();
                ?>
                <?=strftime(config_item('date_format'), strtotime($due_date))?>
                <?php if ($current_time > $due_time){ ?>
                  <span class="badge bg-danger"><?=lang('overdue')?></span>
                <?php } ?>
              </span><?=lang('due_date')?>
            </li>
          </ul>
        </div>
      </div>
      <p><blockquote class="small text-muted"><?=$m->description?></blockquote></p>
    </div>
    <!-- Start Milestone Tasks -->
    <header class="header bg-white b-b clearfix">
      <div class="row m-t-sm">
        <div class="col-sm-12 m-b-xs"><strong><?=lang('milestone_tasks')?></strong></div>
      </div>
    </header>
    <div class="table-responsive">
      <table id="table-milestone" class="table table-striped b-t b-light AppendDataTables">
        <thead>
          <tr>
            <th><?=lang('timer')?></th>
            <th><?=lang('task_name')?></th>
            <th class="col-date"><?=lang('due_date')?></th>
            <th><?=lang('progress')?></th>
            <th class="col-options no-sort"><?=lang('options')?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tasks = $this -> db -> where(array('milestone'=>$m->id)) -> get('tasks') -> result();
          if (!empty($tasks)) {
            foreach ($tasks as $key => $t) { 
              if ($t->timer_status == 'Off') {  $timer = 'success'; }else{ $timer = 'danger'; }
            ?>
            <tr>
              <td><span class="label label-<?=$timer?>"><?=$t->timer_status?></span></td>
              <td> <a class="text-info <?php if($t->task_progress >= 100 ) { echo 'text-lt'; } ?>" href="<?=base_url()?>projects/view/<?=$t->project?>?group=tasks&view=task&id=<?=$t->t_id?>"><?=$t->task_name?></a></td>
              <td><?=strftime(config_item('date_format'), strtotime($t->due_date))?></td>
              <td>
                <div class="inline ">
                  <div class="easypiechart text-success" data-percent="<?=$t->task_progress?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#<?php if($t->task_progress == 100){ echo '8ec165';}else{ echo 'fb6b5b'; } ?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                    <span class="small text-muted"><?=$t->task_progress?>%</span>
                  </div>
                </div>
              </td>
              <td>
                <a class="btn btn-xs btn-info" href="<?=base_url()?>projects/tasks/edit/<?=$t->t_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                <?php  if($role != 2){ ?>
                  <?php
                    if ($t->timer_status == 'On') { ?>
                      <a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/tasks/tracking/off/<?=$t->project?>/<?=$t->t_id?>"><?=lang('stop_timer')?> </a> 
                    <?php }else{ ?>
                      <a class="btn btn-xs btn-success" href="<?=base_url()?>projects/tasks/tracking/on/<?=$t->project?>/<?=$t->t_id?>"><?=lang('start_timer')?> </a> 
                  <?php } ?>
                <?php } ?>
              </td>
            </tr>
          <?php } } ?>
        </tbody>
      </table>
    </div>
    <!-- End Milestone Tasks -->
    <!-- End details -->
    <!-- End ROW 1 -->
  <?php } } } ?>
</section>