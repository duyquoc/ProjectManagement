<!-- Start -->
<section id="content">
  <section class="hbox stretch">
    <aside>
      <section class="vbox">
        <section class="scrollable wrapper w-f">
          <section class="panel panel-default">
            <header class="panel-heading"><?=lang('all_tickets')?>
              <a href="<?=base_url()?>tickets/add" class="btn btn-xs btn-info pull-right"><?=lang('create_ticket')?></a>
                  <?php if ($archive) : ?>
                <a href="<?=base_url()?>tickets" class="btn btn-xs btn-success pull-right"><?=lang('view_active')?></a></header>
                <?php else: ?>
              <a href="<?=base_url()?>tickets?view=archive" class="btn btn-xs btn-dark pull-right"><?=lang('view_archive')?></a></header>
              <?php endif; ?>
            </header>
              <div class="table-responsive">
                <table id="table-tickets<?=($archive ? '-archive':'')?>" class="table table-striped b-t b-light AppendDataTables">
                  <thead>
                    <tr>
                    <th class="col-options no-sort" width="30"><?=lang('options')?></th>                      
                    <th class="col-options no-sort" width="70"><?=lang('ticket_code')?></th>
                      <th><?=lang('subject')?></th>
                      <th class="col-date"><?=lang('date')?></th>
                      <?php if ($role == '1') { ?>
                      <th><?=lang('reporter')?></th>
                      <?php } ?>
                      <th><?=lang('department')?></th>
                      <th width="70"><?=lang('status')?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (!empty($tickets)) {
                      foreach ($tickets as $key => $t) {
                    if($t->status == 'open'){ $s_label = 'danger'; }elseif($t->status=='closed'){ $s_label = 'success'; }else{ $s_label = 'default';}
                    ?>
                    <tr>
                      <td>
                        <div class="btn-group">
                          <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-cogs"></i> <?=lang('options')?>
                          <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <li><a href="<?=base_url()?>tickets/view/<?=$t->id?>"><?=lang('preview_ticket')?></a></li>
                            <?php if ($role == '1') { ?>
                            <li><a href="<?=base_url()?>tickets/edit/<?=$t->id?>"><?=lang('edit_ticket')?></a></li>
                            <li><a href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><?=lang('delete_ticket')?></a></li>
                                <?php if ($archive) : ?>
                                <li><a href="<?=base_url()?>tickets/archive/<?=$t->id?>/0"><?=lang('move_to_active')?></a></li>  
                                <?php else: ?>
                                <li><a href="<?=base_url()?>tickets/archive/<?=$t->id?>/1"><?=lang('archive_ticket')?></a></li>    
                                <?php endif; ?>
                            <?php } ?>      
                          </ul>
                        </div>
                      </td>
                      <td><span class="label label-success"><?=$t->ticket_code?></span></td>
                      <td><a class="text-info" href="<?=base_url()?>tickets/view/<?=$t->id?>"><?=$t->subject?></a></td>
                      <td><?=strftime(config_item('date_format'), strtotime($t->created));?></td>
                      <?php if ($role == '1') { ?>

                      <td>
                        <a class="pull-left thumb-sm avatar">
                          <?php if(config_item('use_gravatar') == 'TRUE' AND 
                            Applib::profile_info($t->reporter)->use_gravatar == 'Y'){
                          $user_email =  Applib::login_info($t->reporter)->email; ?>
                          <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                          <?php }else{ ?>
                          <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($t->reporter)->avatar?>" class="img-circle">
                          <?php } ?>
                        
                      <?=(Applib::profile_info($t->reporter)->fullname) ? 
    Applib::profile_info($t->reporter)->fullname : 
    Applib::login_info($t->reporter)->username
    ?>
                      </a>
                      </td>

                      <?php } ?>
                      <td><?=$this -> applib->get_any_field('departments',array('deptid'=>$t->department),'deptname')?></td>
                      <td><span class="label label-<?=$s_label?>"><?=ucfirst($t->status)?></span> </td>
                    </tr>
                    <?php } } ?>
                  </tbody>
                </table>
              </div>
            </section>
          </section>
          
          
          </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>
          <!-- end -->