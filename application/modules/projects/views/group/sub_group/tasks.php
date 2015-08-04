<?php $this->applib->set_locale(); ?>
<section class="panel panel-default">
<header class="header bg-white b-b clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">
                  <a href="<?=base_url()?>projects/tasks/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('add_task')?></a> 
                  <?php  if($role == 1){ ?>
                  <a href="<?=base_url()?>projects/tasks/add_from_template/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark"><?=lang('from_templates')?></a> 
                  <?php } ?>

                     

                    </div>
                  </div>
                </header>
                <?php echo $this->session->flashdata('form_error');?>
    <div class="table-responsive">
                  <table id="table-tasks" class="table table-striped b-t b-light AppendDataTables">
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
                    if($role != '2'){ // get visible tasks
                    $tasks = $this -> db -> where(array('project'=>$project_id)) -> get('tasks') -> result();
                  }else{
                     $tasks = $this -> db -> where(array('project'=>$project_id,'visible'=>'Yes')) -> get('tasks') -> result();
                  }
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
<?php if($role == '1' OR $role == '3' OR $t->added_by == $this->tank_auth-> get_user_id()){ ?>

                        <a class="btn btn-xs btn-info" href="<?=base_url()?>projects/tasks/edit/<?=$t->t_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
<?php } if($role != 2){ ?>
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

<!-- End details -->
 </section>