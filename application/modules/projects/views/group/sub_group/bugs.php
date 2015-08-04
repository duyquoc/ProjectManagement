<section class="panel panel-default">


<header class="header bg-white b-b clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">
                  <a href="<?=base_url()?>projects/bugs/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('new_bug')?></a> 

                     

                    </div>
                  </div>
                </header>
    <div class="table-responsive">
                  <table id="table-bugs" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('issue_title')?></th>
                        <th><?=lang('reporter')?></th>
                        <th><?=lang('status')?></th>
                        <th><?=lang('severity')?></th>
                        <?php if ($role != '2') { ?>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $bugs = Applib::retrieve(Applib::$bugs_table,array('project'=>$project_id));
                    if (!empty($bugs)) {
              foreach ($bugs as $key => $b) { 
                $issue_title = $b->issue_title ? $b->issue_title : $b->issue_ref;

                switch ($b->bug_status) {
                  case 'Resolved':
                    $status_label = 'success'; 
                      break;
                    case 'Verified':
                      $status_label = 'success'; 
                      break;
                    case 'Confirmed':
                     $status_label = 'info';
                      break;
                    case 'In Progress':
                         $status_label = 'primary'; 
                      break;
                  default:
                     $status_label = 'default'; 
                    break;
                }
                ?>
            
                      <tr>                        
                        <td><a class="text-info" href="<?=base_url()?>projects/view/<?=$b->project?>?group=bugs&view=bug&id=<?=$b->bug_id?>" data-original-title="<?=$b->bug_description?>" data-toggle="tooltip" data-placement="top" title = ""><?=$issue_title?></a></td>
                        <td class="small">


          <a class="pull-left thumb-sm avatar">

        <?php
          $user_email = Applib::login_info($b->reporter)->email;
          $gravatar_url = $this -> applib -> get_gravatar($user_email);
           if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$b->reporter),'use_gravatar') == 'Y'){ ?>
          <img src="<?=$gravatar_url?>" class="img-circle">
          <?php }else{ ?>
      <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($b->reporter)->avatar?>" class="img-circle">
        <?php } ?> 
            <span class="label label-success">
            <?=ucfirst(Applib::login_info($b->reporter)->username)?>
            </span>
          </a>

          </td>


          
                        <td><span class="label label-<?=$status_label?>"><?=ucfirst($b->bug_status)?></span></td>
                        <td><?=ucfirst($b->severity)?></td>
<?php if ($role != '2') { ?>
                        <td>
                        <div class="btn-group">
                    <button class="btn btn-xs btn-<?=config_item('button_color')?> dropdown-toggle" data-toggle="dropdown">
                    <?=lang('change_status')?>
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                          
<li>
  <a href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=unconfirmed">
          <?=lang('unconfirmed')?>
  </a>
</li>
<li>
  <a href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=confirmed">
          <?=lang('confirmed')?>
  </a>
</li>
<li>
  <a href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=in_progress">
            <?=lang('in_progress')?>
  </a>
</li>
<li>
  <a href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=resolved">
            <?=lang('resolved')?>
  </a>
</li>
<li>
  <a href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=verified">
            <?=lang('verified')?>
  </a>
</li>
                </ul>
              </div>
                        <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/bugs/edit/<?=$b->project?>/?id=<?=$b->bug_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                          <a class="btn btn-xs btn-dark" href="<?=base_url()?>projects/bugs/delete/<?=$b->project?>/?id=<?=$b->bug_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
                        </td>
          <?php } ?>
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>

<!-- End details -->
 </section>