<section class="panel panel-default">
  <header class="header bg-white b-b clearfix">
    <div class="row m-t-sm">
      <div class="col-sm-12 m-b-xs">
        <?php
        if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_timesheets',$project_id)) { 
          $cat = isset($_GET['cat']) ? $_GET['cat'] : 'projects';
          ?>
            <div class="m-b-sm">
              <?php  if($role != '2'){ ?>
                <a href="<?=base_url()?>projects/timesheet/add_time/<?=$project_id?>?group=timesheets&cat=<?=$cat?>" data-toggle="ajaxModal" class="btn btn-dark btn-sm"><i class="fa fa-clock-o"></i> <?=lang('time_entry')?></a>
              <?php } ?>
              <div class="pull-right">
                <?php if ($cat == 'projects') { ?>
                  <a class="btn btn-info btn-sm" href="<?=base_url()?><?=uri_string()?>?group=timesheets&cat=tasks"><i class="fa fa-arrow-right"></i> <?=lang('switch_to_tasks_timesheet')?></a>
                <?php } elseif ($cat == 'tasks') { ?>
                  <a class="btn btn-success btn-sm" href="<?=base_url()?><?=uri_string()?>?group=timesheets&cat=projects"><i class="fa fa-arrow-right"></i> <?=lang('switch_to_project_timesheet')?></a>
                <?php } ?>
              </div>           
      </div>
    </div>
  </header>
  <?php
    if($cat == 'projects'){
        $data['project_id'] = $project_id;
        $this -> load -> view('group/sub_group/project_timelog',$data);
      } else {
        $data['project_id'] = $project_id;
        $this -> load -> view('group/sub_group/tasks_timelog',$data);
      }
  }?>
  <!-- End details -->
</section>