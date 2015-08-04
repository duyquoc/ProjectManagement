<section class="panel panel-default">
  <header class="header bg-white b-b clearfix">
    <div class="row m-t-sm">
      <div class="col-sm-12 m-b-xs">
        <?php if($role == '1'){ ?>
        <a href="<?=base_url()?>projects/team/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('update_team')?></a>
        <?php } ?>
      </div>
    </div>
  </header>
  <div class="table-responsive">
    <?php if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_team_members',$project_id)) { ?>
    <table id="table-teams" class="table table-striped b-t b-light AppendDataTables">
      <thead>
        <tr>
          <th class="col-sm-2"><?=lang('username')?></th>
          <?php if($role == '1'){ ?>
          <th class="col-sm-3"><?=lang('full_name')?></th>
          <th class="col-sm-2"><?=lang('phone')?></th>
          <?php } ?>
          <th class="col-sm-2"><?=lang('city')?></th>
          <th class="col-sm-3"><?=lang('email')?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $assigned_users =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'assign_to');
        error_reporting(0);
        foreach (unserialize($assigned_users) as $value) { ?>
        <tr>
        <td>
        <a class="pull-left thumb-sm avatar">

        <?php
          $user_email = Applib::login_info($value)->email;
          $gravatar_url = $this -> applib -> get_gravatar($user_email);
           if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$value),'use_gravatar') == 'Y'){ ?>
          <img src="<?=$gravatar_url?>" class="img-circle">
          <?php }else{ ?>
          <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($value)->avatar?>" class="img-circle">
        <?php } ?> 
            <?=ucfirst(Applib::get_table_field(Applib::$user_table,array('id'=>$value),'username'))?>
          </a>


       
        </td>
        <?php if($role == '1'){ ?>
        <td><?=Applib::get_table_field(Applib::$profile_table,array('user_id'=>$value),'fullname')?></td>
        <td><?=Applib::get_table_field(Applib::$profile_table,array('user_id'=>$value),'phone')?></td>
        <?php } ?>
        <td><?=Applib::get_table_field(Applib::$profile_table,array('user_id'=>$value),'city')?></td>
        <?php $email = Applib::get_table_field(Applib::$user_table,array('id'=>$value),'email')?>
        <td><a href="mailto:<?=$email?>"><?=$email?></a></td>
      </tr>
      <?php }  ?>
    </tbody>
  </table>
  <?php } ?>
  <!-- End view team members -->
</div>
<!-- End details -->
</section>