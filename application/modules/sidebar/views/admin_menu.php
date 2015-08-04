<!-- .aside -->
<aside class="bg-<?=$this->config->item('sidebar_theme')?> b-r aside-md hidden-print" id="nav">
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
              <a href="<?=base_url()?>"> <i class="fa fa-dashboard icon"> <b class="bg-info"></b> </i>
              <span><?=lang('home')?></span> </a> </li>
            <li class="<?php if($page == lang('calendar')){echo  "active"; }?>">
              <a href="<?=base_url()?>calendar"> <i class="fa fa-calendar icon"> <b class="bg-info"></b> </i>
              <span><?=lang('calendar')?></span> </a> </li>
              <li class="<?php if($page == lang('clients')){echo  "active"; }?>">
                <a href="<?=base_url()?>companies"> <i class="fa fa-group icon"> <b class="bg-info"></b> </i>
                <span><?=lang('clients')?></span> </a> </li>
                
                <li class="<?php if($page == lang('item_lookups')){echo  "active"; }?>"> <a href="<?=base_url()?>items"> <i class="fa fa-list icon"> <b class="bg-info"></b> </i>
                <span><?=lang('item_lookups')?> </span> </a> </li>
                <li class="<?php if($page == lang('invoices') OR $page == lang('estimates') OR $page == lang('payments') OR $page == lang('tax_rates') OR $page == lang('add_invoice')){echo  "active"; }?>">
                  <a href="#" >
                    <i class="fa fa-shopping-cart icon"> <b class="bg-info"></b> </i>
                    <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i>
                    </span>
                  <span><?=lang('sales')?> </span> </a>
                  <ul class="nav lt">
                    <li class="<?php if($page == lang('invoices') OR $page == lang('chart') OR $page == lang('add_invoice')){echo "active"; } ?>"> <a href="<?=base_url()?>invoices" > <i class="fa fa-angle-right"></i>
                    <span><?=lang('invoices')?></span> </a> </li>
                    <li class="<?php if($page == lang('estimates')){echo "active"; } ?>"> <a href="<?=base_url()?>estimates" > <i class="fa fa-angle-right"></i>
                    <span><?=lang('estimates')?> </span> </a> </li>
                    
                    <li class="<?php if($page == lang('payments')){echo "active"; } ?>"> <a href="<?=base_url()?>invoices/payments" > <i class="fa fa-angle-right"></i>
                    <span><?=lang('payments_received')?> </span> </a> </li>
                    <li class="<?php if($page == lang('tax_rates')){echo "active"; } ?>"> <a href="<?=base_url()?>invoices/tax_rates" > <i class="fa fa-angle-right"></i>
                    <span><?=lang('tax_rates')?></span> </a> </li>
                  </ul> </li>
                  <?php
                  $timer_on = $this -> applib -> count_rows('projects',array('timer'=>'On'));
                  ?>
                  
                  <li class="<?php if($page == lang('projects')){echo  "active"; }?>"> <a href="<?=base_url()?>projects" > <?php if($timer_on > 0){?><b class="badge bg-danger pull-right"><?=$timer_on?> <i class="fa fa-refresh fa-spin"></i></b><?php } ?>
                    <i class="fa fa-coffee icon"> <b class="bg-info"></b> </i>
                  <span><?=lang('projects')?> </span> </a> </li>
                  <?php
                  $unread = $this -> user_profile -> count_rows('messages',array('user_to'=>$this->tank_auth->get_user_id(),'status' => 'Unread'));
                  ?>
                  <li class="<?php if($page == lang('messages')){echo  "active"; }?>"> <a href="<?=base_url()?>messages" >
                    <?php if($unread > 0){ ?><b class="badge bg-primary pull-right"><?=$unread?></b><?php } ?>  <i class="fa fa-envelope-o icon"> <b class="bg-info"></b> </i>
                  <span><?=lang('messages')?> </span> </a> </li>
                  
                  <li class="<?php if($page == lang('tickets')){echo  "active"; }?>"> <a href="<?=base_url()?>tickets" > <i class="fa fa-ticket icon"> <b class="bg-info"></b> </i>
                  <span><?=lang('tickets')?> </span> </a> </li>
                  
                  <li class="<?php if($page == lang('settings')){echo  "active"; }?>"> <a href="<?=base_url()?>settings" > <i class="fa fa-cogs icon"> <b class="bg-info"></b> </i>
                  <span><?=lang('settings')?> </span> </a> </li>
                  <li class="<?php if($page == lang('users')){echo  "active"; }?>"> <a href="<?=base_url()?>users/account" > <i class="fa fa-lock icon"> <b class="bg-info"></b> </i>
                  <span><?=lang('users')?> </span> </a> </li>
                  
                </ul> 


                    
                    




                </nav>
                <!-- / nav -->
              </div>
            </section>
            
    
  </section>
</aside>
<!-- /.aside -->