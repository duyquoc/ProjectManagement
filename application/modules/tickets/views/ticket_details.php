<!--Start -->
<section id="content">
    <section class="hbox stretch">

        <aside class="aside-md bg-white b-r hidden-print" id="subNav">
            <header class="dk header b-b">
                <a href="<?=base_url()?>tickets/add" data-original-title="<?=lang('create_ticket')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-default btn-sm pull-right"><i class="fa fa-plus"></i></a>
                <p class="h4"><?=lang('all_tickets')?></p>
            </header>

            <section class="vbox">
                <section class="scrollable">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                        <?=$this->load->view('sidebar/tickets',$tickets)?>
                    </div></section>
            </section>
        </aside>

        <aside>
            <section class="vbox">
                <?php
                if (!empty($ticket_details)) {
                    foreach ($ticket_details as $key => $t) { ?>
                        <header class="header bg-white b-b clearfix hidden-print">
                            <div class="row m-t-sm">
                                <div class="col-sm-8 m-b-xs">
                                    <?php if ($role != '2') { ?>
                                    <a href="<?=base_url()?>tickets/edit/<?=$t->id?>" class="btn btn-sm btn-dark">
                                        <i class="fa fa-pencil"></i> <?=lang('edit_ticket')?></a>

                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-<?=config_item('button_color')?> dropdown-toggle" data-toggle="dropdown">
                                            <?=lang('change_status')?>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <?php
                                            $statuses = $this -> db -> get('status') -> result();
                                            if (!empty($statuses)) {
                                                foreach ($statuses as $key => $s) { ?>
                                                    <li><a href="<?=base_url()?>tickets/status/<?=$t->id?>/?status=<?=$s->status?>"><?=ucfirst($s->status)?></a></li>
                                                <?php } } ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if ($role == '1') { ?>
                                    <div class="col-sm-4 m-b-xs pull-right">
                                        <a href="<?=base_url()?>tickets/delete/<?=$t->id?>" class="btn btn-sm btn-dark pull-right" data-toggle="ajaxModal">
                                            <i class="fa fa-trash-o"></i> <?=lang('delete_ticket')?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </header>

                        <section class="scrollable wrapper">
                            <!-- Start Display Details -->

                            <div class="row">
                                <section class="panel">
                                    <div class="col-lg-4">
                                        <ul class="list-group no-radius small">
                                            <?php
                                            if($t->status == 'open'){ $s_label = 'danger'; }elseif($t->status=='closed'){ $s_label = 'success'; }else{ $s_label = 'default';}
                                            ?>

                                            <li class="list-group-item"><span class="pull-right"><?=$t->ticket_code?></span><?=lang('ticket_code')?></li>


                                            <li class="list-group-item">
                                                <?=lang('reporter')?>
                                                <span class="pull-right">
            <a class="thumb-xs avatar pull-left">
                <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$t->reporter),'use_gravatar') == 'Y'){
                    $user_email = Applib::login_info($t->reporter)->email; ?>
                    <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                <?php }else{ ?>
                    <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($t->reporter)->avatar?>" class="img-circle">
                <?php } ?>

                <?=(Applib::profile_info($t->reporter)->fullname) ?
                    Applib::profile_info($t->reporter)->fullname :
                    Applib::login_info($t->reporter)->username
                ?>
            </a>
        </span>
                                            </li>

                                            <li class="list-group-item">
	    <span class="pull-right">
	    	<?=Applib::get_table_field(Applib::$departments_table,array('deptid'=>$t->department),'deptname')?>
	    </span><?=lang('department')?>
                                            </li>
                                            <li class="list-group-item">
    	<span class="pull-right"><label class="label label-<?=$s_label?>"><?=ucfirst($t->status)?></label>
    	</span><?=lang('status')?>
                                            </li>

                                            <li class="list-group-item"><span class="pull-right"><?=$t->priority?></span><?=lang('priority')?></li>

                                            <?php if($t->attachment != NULL){ ?>
                                                <li class="list-group-item"><span class="pull-right">
    <a href="<?=base_url()?>tickets/download_file/<?=$t->id?>"><?=lang('download')?></a></span><?=lang('attachment')?></li>
                                            <?php } ?>
                                            <li class="list-group-item"><span class="pull-right"><?=$t->created?></span><?=lang('created')?></li>

                                            <?php
                                            $additional = json_decode($t->additional, true);

                                            if (is_array($additional))
                                                foreach ($additional as $key => $value)
                                                {
                                                    $result = $this -> db -> where('uniqid', $key) -> get(Applib::$custom_fields_table);
                                                    $row = $result -> row_array();
                                                    echo '<li class="list-group-item"><span class="pull-right">' .$this -> encrypt -> decode($value).'</span>'.$row['name'] .'</li>';



                                                }
                                            ?>


                                            <div class="line line-dashed line-lg pull-in"></div>

                                            Subject: <?=$t->subject?>
                                            <div class="line line-dashed line-lg pull-in"></div>
                                            <?=nl2br($t->body)?>
                                        </ul>
                                    </div>
                                    <!-- End details C1-->
                                    <div class="col-lg-8">
                                        <?php
                                        $user = $this->tank_auth->get_user_id();
                                        ?>

                                        <section class="comment-list block">
                                            <!-- ticket replies -->

                                            <!-- comment form -->
                                            <article class="comment-item media" id="comment-form">
                                                <a class="pull-left thumb-sm avatar">
                                                    <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user),'use_gravatar') == 'Y'){
                                                        $user_email = Applib::login_info($user)->email; ?>
                                                        <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                                                    <?php }else{ ?>
                                                        <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($user)->avatar?>" class="img-circle">
                                                    <?php } ?>
                                                </a>
                                                <section class="media-body">

                                                    <section class="panel panel-default">
                                                        <?php
                                                        $attributes = 'class="m-b-none"';
                                                        echo form_open(base_url().'tickets/reply',$attributes); ?>
                                                        <input type="hidden" name="ticketid" value="<?=$t->id?>">
                                                        <input type="hidden" name="ticket_code" value="<?=$t->ticket_code?>">
                                                        <input type="hidden" name="replierid" value="<?=$user?>">
                                                        <textarea class="form-control no-border" name="reply" rows="3" placeholder="Ticket #<?=$t->ticket_code?> reply"></textarea>

                                                        <footer class="panel-footer bg-light lter">
                                                            <button class="btn btn-info pull-right btn-sm" type="submit"><?=lang('reply_ticket')?></button>
                                                            <ul class="nav nav-pills nav-sm">
                                                            </ul>
                                                        </footer>
                                                        </form>
                                                    </section>

                                                </section>
                                            </article>
                                            <?php
                                            if (!empty($ticket_replies)) {
                                                foreach ($ticket_replies as $key => $r) {
                                                    $role_id = Applib::login_info($r->replierid)->role_id;
                                                    $user_role = $this->tank_auth->user_role($role_id);
                                                    $username = Applib::login_info($r->replierid)->username;
                                                    if($user_role == 'admin'){ $role_label = 'danger'; }else{ $role_label = 'info';}
                                                    ?>
                                                    <article id="comment-id-1" class="comment-item">
                                                        <a class="pull-left thumb-sm avatar">


                                                            <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$r->replierid),'use_gravatar') == 'Y'){
                                                                $user_email = Applib::login_info($r->replierid)->email; ?>
                                                                <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                                                            <?php }else{ ?>
                                                                <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($r->replierid)->avatar?>" class="img-circle">
                                                            <?php } ?>


                                                        </a>
                                                        <span class="arrow left"></span>
                                                        <section class="comment-body panel panel-default">
                                                            <header class="panel-heading bg-white">
                                                                <a href="#"><?=ucfirst($username)?></a>
                                                                <label class="label bg-<?=$role_label?> m-l-xs"><?=ucfirst($user_role)?></label>
                          <span class="text-muted m-l-sm pull-right">
                            <i class="fa fa-clock-o"></i>
                              <?php
                              $today = time();
                              $reply_day = strtotime($r->time) ;
                              echo $this-> applib ->get_time_diff($today,$reply_day);
                              ?> <?=lang('ago')?>
                          </span>
                                                            </header>
                                                            <div class="panel-body">
                                                                <div class="small text-muted m-t-sm"><?=$r->body?></div>
                                                            </div>
                                                        </section>
                                                    </article>
                                                <?php } }else{ ?>
                                                <article id="comment-id-1" class="comment-item">
                                                    <section class="comment-body panel panel-default">
                                                        <div class="panel-body">
                                                            <p><?=lang('no_ticket_replies')?></p>
                                                        </div>
                                                    </section>
                                                </article>
                                            <?php } ?>
                                            <!-- End ticket replies -->

                                        </section>


                                    </div>
                                    <!-- End details -->
                                </section>
                            </div>


                            <!-- End display details -->
                        </section>

                    <?php } } ?>
            </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>
<!-- end