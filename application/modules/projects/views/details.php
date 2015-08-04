<section id="content">
          <section class="hbox stretch">


<?php
 $username = $this -> tank_auth -> get_username();
 $this->load->helper('text');
                  if (!empty($project_details)) {
                  foreach ($project_details as $key => $p) { ?>

          <!-- Sidebar start -->
<aside class="aside aside-md bg-white small">
              <section class="vbox">
                <header class="dk header b-b">
                  <button class="btn btn-icon btn-default btn-sm pull-right visible-xs m-r-xs" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></button>
                  <p class="h4 text-muted"><?=word_limiter($p->project_title, 2);?></p>
                </header>
                <section class="scrollable bg-light">
                  <section class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

                    <section id="setting-nav" class="hidden-xs">
                      <ul class="nav nav-pills nav-stacked no-radius">
                        <li class="<?php echo ($group == 'dashboard') ? 'active' : '';?>">
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=dashboard"> 
                            <i class="fa fa-fw fa-dashboard"></i>
                            <?=lang('project_dashboard')?>
                          </a>
                        </li>
  <?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_team_members',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'teams') ? 'active' : '';?>">
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=teams">
                            <i class="fa fa-fw fa-group"></i>
                           <?=lang('team_members')?>
                          </a>
                        </li>
          <?php } 
 if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_milestones',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'milestones') ? 'active' : '';?>">
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=milestones">
                            <!-- <span class="badge badge-hollow pull-right">4</span> -->
                            <i class="fa fa-fw fa-rocket"></i>                            
                            <?=lang('milestones')?>
                          </a>
                        </li>
    <?php } 
    if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_tasks',$p->project_id)) {
                $timer_on = $this -> applib -> count_rows('tasks',array('project'=>$p->project_id,'timer_status'=>'On')); ?>
                        <li class="<?php echo ($group == 'tasks') ? 'active' : '';?>">
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>/?group=tasks">
                            <i class="fa fa-fw fa-tasks"></i>
                            <?=lang('project_tasks')?> <?php if($timer_on > 0){?><b class="badge bg-danger pull-right"><?=$timer_on?> <i class="fa fa-refresh fa-spin"></i></b><?php } ?>
                          </a>
                        </li>
    <?php }
    if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_files',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'files') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=files">
                            <i class="fa fa-fw fa-folder-open"></i>                            
                            <?=lang('project_files')?>
                          </a>
                        </li>
    <?php } 
    if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_links',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'links') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=links">
                            <i class="fa fa-fw fa-globe"></i>                            
                            <?=lang('project_links')?>
                          </a>
                        </li>
  <?php } 
  if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_timesheets',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'timesheets') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=timesheets">
                            <i class="fa fa-fw fa-clock-o"></i>                            
                            <?=lang('timesheets')?>
                          </a>
                        </li>
  <?php } 
  if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_bugs',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'bugs') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=bugs">
                            <i class="fa fa-fw fa-warning"></i>                            
                            <?=lang('project_bugs')?>
                          </a>
                        </li>
  <?php } 
  if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_history',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'history') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=history">
                            <i class="fa fa-fw fa-rss"></i>                            
                            <?=lang('project_history')?>
                          </a>
                        </li>
  <?php } 
  if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_comments',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'comments') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=comments">
                            <i class="fa fa-fw fa-comments-o"></i>                            
                            <?=lang('project_comments')?>
                          </a>
                        </li>
  <?php }
  if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_calendar',$p->project_id)) { ?>
                        <li class="<?php echo ($group == 'calendar') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=calendar">
                            <i class="fa fa-fw fa-calendar"></i>                            
                            <?=lang('project_calendar')?>
                          </a>
                        </li>
  <?php }
  if ($role == '1' OR $this -> applib -> allowed_module('view_project_notes',$username)){ ?>
                        <li class="<?php echo ($group == 'notes') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=notes">
                            <i class="fa fa-fw fa-pencil"></i>                            
                            <?=lang('project_notes')?>
                          </a>
                        </li>
  <?php }
  if ($role == '1') { ?>
                        <li class="<?php echo ($group == 'settings') ? 'active' : '';?>">                
                          <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=settings">
                            <i class="fa fa-fw fa-cog"></i>                            
                            <?=lang('project_settings')?>
                          </a>
                        </li>
                        <?php } ?>


                      </ul>
                      
                    </section>
                  </section>
                </section>
              </section>
            </aside>





 <!--  Sidebar end -->


            <aside class="bg-light lter b-l">
              <section class="vbox">
                <header class="header bg-white clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">
 <?php if ($role == '1' OR $this -> applib -> allowed_module('edit_all_projects',$username)){ ?>
                     <a href="<?=base_url()?>projects/view/<?=$p->project_id?>?group=<?=$group?>&action=edit" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> <?=lang('edit_project')?></a>

<?php } ?>

<?php if ($role == '1'){ ?>
    <a href="<?=base_url()?>projects/copy_project/<?=$p->project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> <?=lang('clone_project')?></a>

    <a href="<?=base_url()?>projects/invoice/<?=$p->project_id?>" class="btn btn-dark btn-sm" data-toggle="ajaxModal"><i class="fa fa-money"></i> <?=lang('invoice_project')?></a>
    <?php } 
if ($role != '2'){

if($p->timer == 'On') { $label = 'danger'; } else{ $label = 'success'; } 
      if ($p->timer == 'On') { ?>
      <a href="<?=base_url()?>projects/tracking/off/<?=$p->project_id?>" class="btn btn-sm btn-<?=$label?> "> <i class="fa fa-clock-o text-white"></i> <?=lang('stop_timer')?></a>
      <?php }else{ ?>
      <a href="<?=base_url()?>projects/tracking/on/<?=$p->project_id?>" class="btn btn-sm btn-<?=$label?> "> <i class="fa fa-clock-o text-white"></i> <?=lang('start_timer')?></a>
      <?php } ?>

<?php } ?>



<?php if ($role == '1' OR $this -> applib -> allowed_module('delete_projects',$username)){ ?>
<a href="<?=base_url()?>projects/delete/<?=$p->project_id?>?group=<?=$group?>" data-toggle="ajaxModal" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i> <?=lang('delete_project')?></a>
<?php } ?>
                    </div>
                  </div>
                </header>
                <section class="scrollable wrapper">
                    <!-- Load the settings form in views -->
                    <?php
                    if(isset($_GET['action']) == 'edit'){ 
                      $this -> load -> view('group/edit_project',$project_details); 
                    }
                    else{
                    $data['project_id'] = $p->project_id;
                    $this -> load -> view('group/'.$group,$data);
                  }
                    ?>
                    <!-- End of settings Form -->
                </section>
                
              </section>
            </aside>

            <?php } } ?>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
        </section>