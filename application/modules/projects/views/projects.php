<?php $this->applib->set_locale(); ?>
<section id="content">
  <section class="hbox stretch">
    <aside>
      <section class="vbox">
        <section class="scrollable wrapper w-f">
          <section class="panel panel-default">
            <header class="panel-heading"><?=($archive ? lang('project_archive') : lang('all_projects'));?> 
                <?php
                  $username = $this -> tank_auth -> get_username();
                  if ($this -> applib -> allowed_module('add_projects',$username) OR $role == '1' OR $role == '2' AND config_item('client_create_project') == 'TRUE') { ?>
                  <a href="<?=base_url()?>projects/add" class="btn btn-xs btn-primary pull-right"><?=lang('create_project')?></a>
                <?php } ?>
                  <?php if ($archive) : ?>
                <a href="<?=base_url()?>projects" class="btn btn-xs btn-success pull-right"><?=lang('view_active')?></a></header>
                <?php else: ?>
              <a href="<?=base_url()?>projects?view=archive" class="btn btn-xs btn-dark pull-right"><?=lang('view_archive')?></a></header>
              <?php endif; ?>
            </header>
            <div class="table-responsive">
              <table id="table-projects<?=($archive ? '-archive':'')?>" class="table table-striped b-t b-light AppendDataTables small">
                <thead>
                  <tr>
                  <th class="col-options no-sort col-xs-1"><?=lang('options')?></th>
                    <th class="col-title col-xs-3"><?=lang('project_title')?></th>
                    <?php if ($role == '1') { ?>
                    <th class="col-xs-2"><?=lang('client_name')?></th>
                    <?php } ?>
                   <?php if ($role == '1') { ?>
                    <th class="col-title col-xs-1"><?=lang('status')?></th>
                    <?php } ?>
                    <th class="col-options no-sort col-xs-1"><?=lang('timer')?></th>
                    <th class="col-date col-xs-1"><?=lang('start_date')?></th>
                    <th class="col-date col-xs-1"><?=lang('due_date')?></th>
                    <?php if ($role != '1') { ?>
                    <th class="col-xs-2"><?=lang('hours_spent')?></th>
                    <?php } ?>
                    <?php if($role != '3' OR $this -> applib -> allowed_module('view_project_cost',$username)){ ?>
                    <th class="col-currency col-xs-1"><?=lang('amount')?></th>
                    <?php } ?>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if (!empty($projects)) {
                    foreach ($projects as $key => $p) { 
                    if ($p->timer == 'Off') {  $timer = 'default'; }else{ $timer = 'danger'; }
                    ?>
                    <tr>
                    <td>
                        <div class="btn-group">
                          <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                            <?=lang('options')?>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">  
                            <li><a href="<?=base_url()?>projects/view/<?=$p->project_id?>"><?=lang('preview_project')?></a></li>
                            <li><a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=history"><?=lang('project_history')?></a></li>
                            <?php if ($role == '1' OR $this -> applib -> allowed_module('edit_all_projects',$username)){ ?>   
                              <li><a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=dashboard&action=edit"><?=lang('edit_project')?></a></li>
                                <?php if ($archive) : ?>
                                <li><a href="<?=base_url()?>projects/archive/<?=$p->project_id?>/0"><?=lang('move_to_active')?></a></li>  
                                <?php else: ?>
                                <li><a href="<?=base_url()?>projects/archive/<?=$p->project_id?>/1"><?=lang('archive_project')?></a></li>                                
                                <?php endif; ?>
                            <?php } ?>      
                            <?php if ($role == '1' OR $this -> applib -> allowed_module('delete_projects',$username)){ ?> 
                              <li><a href="<?=base_url()?>projects/delete/<?=$p->project_id?>" data-toggle="ajaxModal"><?=lang('delete_project')?></a></li>
                            <?php } ?>
                          </ul>
                        </div>
                      </td>
                      <td><a class="text-info" href="<?=base_url()?>projects/view/<?=$p->project_id?>"><?=$p->project_title?></a>
                            <?php
                            if (time() > strtotime($p->due_date) AND $p->progress < 100){ ?>
                            <span class="badge bg-danger pull-right"><?=lang('overdue')?></span>
                            <?php } ?>

                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-<?php echo ($p->progress >= 100 ) ? 'success' : 'primary'; ?>" data-toggle="tooltip" data-original-title="<?=$p->progress?>%" style="width: <?=$p->progress;?>%"></div>
                        </div>

                      </td>
                      <?php if ($role == '1') { ?>
                        <td><?=$this -> applib->get_any_field('companies',array('co_id'=>$p->client),'company_name')?></td>
                      <?php } ?>
                   <?php if ($role == '1') { ?>
                      <?php 
                        switch ($p->status) {
                            case 'Active': $badge = 'success'; break;
                            case 'On Hold': $badge = 'warning'; break;
                            case 'Done': $badge = 'default'; break;
                        }
                      ?>
                      <td><span class="badge bg-<?=$badge?>"><?=lang(str_replace(" ","_",strtolower($p->status)))?></span></td>
                    <?php } ?>
                      <td><span class="label label-<?=$timer?>"><?=$p->timer?></span></td>
                      <td><?=strftime(config_item('date_format'), strtotime($p->start_date))?></td>
                      <td><?=strftime(config_item('date_format'), strtotime($p->due_date))?></td>
                      <?php if ($role != '1') { ?>
                        <td><?=$this -> applib -> pro_calculate('project_hours',$p->project_id);?> <?=lang('hours')?></td>
                      <?php } ?>
                      <?php if($role != '3' OR $this -> applib -> allowed_module('view_project_cost',$username)){ ?>
                        <?php $cur = $this->applib->client_currency($p->client); ?>
                        <td><?=$cur->symbol?> <?=number_format($this -> applib -> pro_calculate('project_cost',$p->project_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?></td>
                      <?php } ?>
                      
                    </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>
          </section>
        </section>
      </section>
    </aside>
  </section>
  <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<!-- end -->