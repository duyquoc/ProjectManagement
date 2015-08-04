<?php $this->applib->set_locale(); ?>
<div  id="activity">
  <ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border">
    <?php
    if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_history',$project_id)) {
    $activities = $this-> db -> where(array('module'=>'projects','module_field_id'=>$project_id)) -> order_by('activity_date','desc') -> get('activities') -> result();
    if (!empty($activities)) {
    foreach ($activities as $key => $a) { ?>
    <li class="list-group-item">
      <a class="thumb-sm pull-left m-r-sm">

        <?php
          $user_email = Applib::login_info($a->user)->email;
          $gravatar_url = $this -> applib -> get_gravatar($user_email);
           if(config_item('use_gravatar') == 'TRUE' AND Applib::profile_info($a->user)->use_gravatar == 'Y'){ ?>
          <img src="<?=$gravatar_url?>" class="img-circle">
          <?php }else{ ?>
          <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($a->user)->avatar?>" class="img-circle">
        <?php } ?> 
          </a>

        
      <a  class="clear">
        <small class="pull-right"><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($a->activity_date)) ?></small>
        <strong class="block"><?=ucfirst($this -> applib -> get_any_field('users',array('id' => $a->user), 'username'))?></strong>
        <small>
        <?php 
        if (lang($a->activity) != '') {
            if (!empty($a->value1)) {
                if (!empty($a->value2)){
                    echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
                } else {
                    echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
                }
            } else { echo lang($a->activity); }
        } else { echo $a->activity; } 
        ?> 
        </small>
      </a>
    </li>
    <?php } } }?>
  </ul>
</div>