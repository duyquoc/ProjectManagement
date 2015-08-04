<!-- .aside -->
<aside class="bg-<?=config_item('sidebar_theme')?> b-r aside-md hidden-print" id="nav">
  <section class="vbox">
  
   <?php if(config_item('enable_languages') == 'TRUE'){ ?>
    <header class="header bg-dark text-center clearfix">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-info btn-icon" title="<?=lang('languages')?>"><i class="fa fa-globe"></i></button>
        <div class="btn-group hidden-nav-xs">
          <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"> <?=lang('languages')?>
          <span class="caret">
          </span> </button>
          <!-- Load Languages -->
            <ul class="dropdown-menu text-left">
            <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
            <li>
                <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?=base_url()?>resource/images/flags/<?=$lang->icon?>.gif" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  /> <?=ucwords(str_replace("_"," ", $lang->name))?>
                </a>
            </li>
            <?php endif; endforeach; ?>
            </ul>
        </div>
      </div>
    </header>
<?php } ?>

      <section class="scrollable">
        <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
          <!-- nav -->
          <nav class="nav-primary hidden-xs">
            <ul class="nav">
              <li class="<?php if($page == lang('home')){echo  "active"; }?>">
                <a href="<?=base_url()?>clients"> <i class="fa fa-dashboard icon"> <b class="bg-info"></b> </i>
              <span><?=lang('home')?></span> </a> </li>

              <?php
              $user_id = $this->tank_auth -> get_user_id();
              $client_co = Applib::profile_info($user_id)->company;
              $timer_on = Applib::count_num_rows(Applib::$projects_table,array('timer'=>'On','client' => $client_co));
                ?>

              <li class="<?php if($page == lang('projects')){echo  "active"; }?>"> <a href="<?=base_url()?>projects" > <?php if($timer_on > 0){?><b class="badge bg-danger pull-right"><?=$timer_on?> <i class="fa fa-refresh fa-spin"></i></b><?php } ?> <i class="fa fa-coffee icon"> <b class="bg-info"></b> </i>
              <span><?=lang('projects')?> </span> </a> </li>

              <li class="<?php if($page == lang('messages')){echo  "active"; }?>"> <a href="<?=base_url()?>clients/messages" > <b class="badge bg-success pull-right"><?=Applib::count_num_rows(Applib::$messages_table,array('user_to'=>$this->tank_auth->get_user_id(),'status' => 'Unread'))?></b> <i class="fa fa-envelope-o icon"> <b class="bg-info"></b> </i>
              <span><?=lang('messages')?> </span> </a> </li> 

              <li class="<?php if($page == lang('invoices')){echo  "active"; }?>"> <a href="<?=base_url()?>invoices" > <i class="fa fa-list icon"> <b class="bg-info"></b> </i>
                <span><?=lang('invoices')?> </span> </a> </li> 

                <li class="<?php if($page == lang('estimates')){echo  "active"; }?>"> <a href="<?=base_url()?>estimates" > <i class="fa fa-list-alt icon"> <b class="bg-info"></b> </i>
                <span><?=lang('estimates')?> </span> </a> </li> 

                <li class="<?php if($page == lang('payments')){echo  "active"; }?>"> <a href="<?=base_url()?>clients/payments" > <i class="fa fa-money icon"> <b class="bg-info"></b> </i>
                <span><?=lang('payments_sent')?> </span> </a> </li> 
              
              



              <li class="<?php if($page == lang('tickets')){echo  "active"; }?>"> <a href="<?=base_url()?>tickets" > <i class="fa fa-ticket icon"> <b class="bg-info"></b> </i>
                <span><?=lang('tickets')?> </span> </a> </li>   

                          

              

             
                
                
              </ul> </nav>
              <!-- / nav -->
            </div>
          </section>
         

  
</section>
</aside>
<!-- /.aside -->