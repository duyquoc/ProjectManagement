<section class="panel panel-default">
  <?php
  $bug = isset($_GET['id']) ? $_GET['id'] : 0;
  $details = Applib::retrieve(Applib::$bugs_table,array('bug_id'=>$bug));
  if (!empty($details)) {
    foreach ($details as $key => $i) { 
    ?>
      <header class="header bg-white b-b clearfix">
        <div class="row m-t-sm">
          <div class="col-sm-12 m-b-xs">
            <a href="<?=base_url()?>projects/bugs/file/<?=$i->project?>/<?=$i->bug_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('attach_file')?></a>
            <?php if ($role == '1') { ?>
              <a href="<?=base_url()?>projects/bugs/edit/<?=$i->project?>/?id=<?=$i->bug_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark"><?=lang('edit_bug')?></a> 
            <?php } ?>
          </div>
        </div>
      </header>
      <div class="row">
        <div class="col-lg-12">
          <section class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-6">
                  <ul class="list-group no-radius">
                    <li class="list-group-item">
                      <span class="pull-right"><?=Applib::get_table_field(Applib::$projects_table,
                                            array('project_id'=>$i->project),'project_title')?> 
                      </span><?=lang('project')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><?=$i->issue_title?></span><?=lang('issue_title')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><?=$i->issue_ref?> </span><?=lang('issue_ref')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right">
                      <?=ucfirst(Applib::login_info($i->reporter)->username)?>
                      </span><?=lang('reporter')?>
                    </li>
                    <?php if ($role != '2') { ?>
                      <li class="list-group-item">
                        <span class="pull-right">
                        <?=ucfirst(Applib::login_info($i->assigned_to)->username)?>
                        </span><?=lang('assigned_to')?>
                      </li>
                    <?php } ?>   
                  </ul>
                </div>
                <!-- End details C1-->
                <div class="col-lg-6">
                  <ul class="list-group no-radius">
                    <li class="list-group-item">
                      <span class="pull-right"><?=$i->severity?></span><?=lang('severity')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><?=$i->bug_status?></span><?=lang('bug_status')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><?=ucfirst($i->priority)?> </span><?=lang('priority')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><?=strftime(config_item('date_format'), strtotime($i->reported_on));?></span><?=lang('reported_on')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right">
                      <span class="label label-success">
                        <?=strftime(config_item('date_format'), strtotime($i->last_modified));?>
                      </span>
                      </span><?=lang('last_modified')?>
                    </li>    
                  </ul>
                </div>
              </div>
              <!-- End details -->
                    <?php
                $this->load->helper('file');
                $files = Applib::retrieve(Applib::$bug_files_table,array('bug'=>$bug));

                  if (!empty($files)) {
                      foreach ($files as $key => $f) {
                  $icon = $this->applib->file_icon($f->file_ext);
                  $real_url = base_url().'resource/bug-files/'.$f->file_name;
                  ?>
                <div class="file-small">
                        <?php if ($f->is_image == 1) : ?>
                        <?php if ($f->image_width > $f->image_height) {
                            $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                            $style = 'height:100%; margin-left: -'.$ratio.'%';
                        } else {
                            $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                            $style = 'width:100%; margin-top: -'.$ratio.'%';
                        }  ?>
                            <div class="file-icon icon-small"><a href="<?=base_url()?>projects/bugs/preview/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a></div>
                        <?php else : ?>
                            <div class="file-icon icon-small"><i class="fa <?=$icon?> fa-lg"></i></div>
                        <?php endif; ?>

                        <a data-toggle="tooltip" data-placement="top" data-original-title="<?=$f->description?>" class="text-info" href="<?=base_url()?>projects/download/<?=$i->project?>?group=files&id=<?=$f->file_id?>">
                        <?=(empty($f->title) ? $f->file_name : $f->title)?>
                        </a>
                        <?php  if($f->uploaded_by == $this-> tank_auth -> get_user_id() OR $role == '1'){ ?>
                        <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/bugs/file/delete/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
                        <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/bugs/file/edit/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                        <?php } ?>
                        
                </div>
              <?php } } ?>
              <br/>
              <br/>
              <div class="line line-dashed line-lg pull-in"></div>
              <blockquote class="small text-muted"><?=$i->reproducibility?></blockquote>
              <blockquote><?=$i->bug_description?></blockquote>
            </div>
          </section>
          <!-- Start Comments -->
          <div class="row">
            <div class="col-lg-12">
              <section class="panel panel-body">
                <section class="comment-list block">
                  <article class="comment-item media" id="comment-form">
                    <?php
                    $user = $this -> tank_auth -> get_user_id();
                    ?>
                    <a class="pull-left thumb-sm avatar">

        <?php
          $user_email = Applib::login_info($user)->email;
          $gravatar_url = $this -> applib -> get_gravatar($user_email);
           if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user),'use_gravatar') == 'Y'){ ?>
          <img src="<?=$gravatar_url?>" class="img-circle">
          <?php }else{ ?>
          <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($user)->avatar?>" class="img-circle">
        <?php } ?> 
          </a>


                     
                    <section class="media-body">
                      <section class="panel panel-default">
                        <?php 
                        $attributes = 'class="m-b-none"';
                        echo form_open(base_url().'projects/bugs/comment/',$attributes); ?>
                          <input type="hidden" name="bug_id" value="<?=$bug?>">
                          <input type="hidden" name="project" value="<?=$i->project?>">
                          <textarea class="form-control no-border" name="comment" rows="3" placeholder="Issue #<?=$i->issue_ref?> reply"></textarea>
                          <footer class="panel-footer bg-light lter">
                             <button class="btn btn-info pull-right btn-sm" type="submit"><?=lang('post_comment')?></button>
                            <ul class="nav nav-pills nav-sm"></ul>
                          </footer>
                        </form>
                      </section>
                    </section>
                  </article>
                  <?php
                  $bug_comments = $this -> db -> where(array('bug_id'=>$bug)) 
                                        ->order_by('date_commented','desc') 
                                        -> get(Applib::$bug_comments_table) 
                                        -> result();

                    if (!empty($bug_comments)) {
                      foreach ($bug_comments as $key => $c) {
                        $role_id = Applib::login_info($c->comment_by)->role_id;
                        $user_role = $this->tank_auth->user_role($role_id);
                        $username = Applib::login_info($c->comment_by)->username;

                        if($user_role == 'admin'){ $role_label = 'danger'; }else{ $role_label = 'info';}
                  ?> 
                          <article id="comment-id-1" class="comment-item">
                            <a class="pull-left thumb-sm avatar">
                              <?php 
                              $user_email = Applib::login_info($c->comment_by)->email;
                              $gravatar_url = $this -> applib -> get_gravatar($user_email);

                              if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$c->comment_by),'use_gravatar') == 'Y'){ ?>
                                <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                              <?php }else{ ?>

      <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($c->comment_by)->avatar?>" class="img-circle">
                              
                              <?php } ?>
                            </a>
                            <span class="arrow left"></span>
                            <section class="comment-body panel panel-default">
                              <header class="panel-heading bg-white">
                                <a href="#"><?=ucfirst($username)?></a>
                                <label class="label bg-<?=$role_label?> m-l-xs"><?=$user_role?></label> 
                                <span class="text-muted m-l-sm pull-right">
                                  
                                  <?php
                                  $today = time();
                                  $comment_day = strtotime($c->date_commented) ;
                                  ?> <i class="fa fa-clock-o"></i><?=$this-> applib -> get_time_diff($today,$comment_day)?> <?=lang('ago')?>

                                   <?php
                    if($c->comment_by == $user){ ?>

                     <a href="<?=base_url()?>projects/bugs/delete_comment/<?=$c->c_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                    <?php } ?>
                                </span>
                              </header>

                              <div class="panel-body">
                                <div class="text-muted small"><?=$c->comment?></div>
                               
                              </div>

                            </section>
                          </article>
                        <?php } }else{ ?>
                          <article id="comment-id-1" class="comment-item">
                            <section class="comment-body panel panel-default">
                              <div class="panel-body">
                                <p>No comments found</p>
                              </div>
                            </section>
                          </article>
                        <?php } ?>
                      </section>
                    </section>
                  </div>
                </div>
              <!-- END COMMENTS -->
          <?php } } ?>
        </div> 
      </div>
    <!-- End ROW 1 -->
  </section>