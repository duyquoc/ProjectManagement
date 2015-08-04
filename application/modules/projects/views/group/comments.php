<section class="panel ">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel panel-body">
        <section class="comment-list block">
          <article class="comment-item media" id="comment-form">
            <?php
              if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_comments',$project_id)) { 
              $user = $this -> tank_auth -> get_user_id();
              $project_title =Applib::get_table_field(Applib::$projects_table,array('project_id'=>$project_id),'project_title');
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
                  echo form_open(base_url().'projects/comment/',$attributes); ?>
                    <input type="hidden" name="project" value="<?=$project_id?>">
                    <textarea class="form-control no-border" name="comment" rows="3" placeholder="<?=$project_title?> <?=lang('comment')?>" required></textarea>            
                    <footer class="panel-footer bg-light lter">
                      <button class="btn btn-primary pull-right btn-sm" type="submit"><?=lang('post_comment')?></button>
                      <ul class="nav nav-pills nav-sm">
                      </ul>
                    </footer>
                  </form>
                </section>
              </section>
          </article>
          <?php
            $comments = $this -> db -> where(array('project'=>$project_id)) ->order_by('date_posted','desc') -> get('comments') -> result();
            if (!empty($comments)) {
              foreach ($comments as $key => $c) {
                $role_id = Applib::login_info($c->posted_by)->role_id;
                $user_role = $this->tank_auth->user_role($role_id);
                $username = Applib::login_info($c->posted_by)->username;
              if($user_role == 'admin'){ $role_label = 'danger'; }else{ $role_label = 'info';}
          ?> 
            <article id="comment-id-1" class="comment-item">
              <a class="pull-left thumb-sm avatar">

  <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$c->posted_by),'use_gravatar') == 'Y'){
  $user_email = Applib::login_info($c->posted_by)->email; ?>
  <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
  <?php }else{ ?>
  <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($c->posted_by)->avatar?>" class="img-circle">
  <?php } ?>
              </a>
              <span class="arrow left"></span>
              <section class="comment-body panel panel-default">
                <header class="panel-heading bg-white">
                  <a href="#">
                  <?=ucfirst(Applib::profile_info($c->posted_by)->fullname
                              ? Applib::profile_info($c->posted_by)->fullname
                              : $username)?>
                  </a>
                  <label class="label bg-<?=$role_label?> m-l-xs"><?=ucfirst($user_role)?> </label> 
                  <span class="text-muted m-l-sm pull-right">
                    <?php
                      $today = time();
                      $comment_day = strtotime($c->date_posted) ;
                    ?> <i class="fa fa-clock-o"></i><?=$this-> applib -> get_time_diff($today,$comment_day)?> <?=lang('ago')?>

                    <a href="<?=base_url()?>projects/replies?c=<?=$c->comment_id?>&p=<?=$project_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-comment text-primary"></i>
                    </a>
                    <?php
                    if($c->posted_by == $user){ ?>

                     <a href="<?=base_url()?>projects/delete_comment/<?=$c->comment_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                    <?php } ?>
                    

                  </span>
                </header>
                <div class="panel-body">
                  <div class="text-muted small"><?=$c->message?></div>
                    <div class="comment-action m-t-sm">

                    

                  </div>
                </div>




                <?php
                    $comment_replies = Applib::retrieve(Applib::$comment_replies_table,
                                          array('parent_comment' => $c->comment_id));

                  if (!empty($comment_replies)) {
                          foreach ($comment_replies as $key => $reply) { ?>
                      <article id="comment-id-2" class="comment-item comment-reply"> 
                      <a class="pull-left thumb-sm avatar"> 

                      <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$reply->replied_by),'use_gravatar') == 'Y'){
  $user_email = $this->user_profile->get_user_details($reply->replied_by,'email'); ?>
  <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
  <?php }else{ ?>
  <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($reply->replied_by)->avatar?>" class="img-circle">
  <?php } ?>
</a>


                          <span class="arrow left"></span>
                        <section class="comment-body panel panel-default text-sm">
                          <div class="panel-body">
                          <span class="text-muted m-l-sm pull-right"> 
                          <?php
                                          $today = time();
                                          $reply_day = strtotime($reply->date_posted) ;
                              ?> <i class="fa fa-clock-o"></i> <?=$this->user_profile->get_time_diff($today,$reply_day)?> ago

                              <?php
                    if($reply->replied_by == $user){ ?>

                     <a href="<?=base_url()?>projects/delete_reply/<?=$reply->reply_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                    <?php } ?>




                              </span> 
                          <span class="text-danger"><?=ucfirst(
                            Applib::profile_info($reply->replied_by)->fullname
                            ? Applib::profile_info($reply->replied_by)->fullname
                            : Applib::login_info($reply->replied_by)->username
                            )?></span>

                          <p><span class="text-dark"><?=$reply->reply_msg?></span></p>

                          </div>
                         </section>
                      </article>
                      <?php } } ?>
              </section>
            </article>
          <?php } }else{ ?>
            <article id="comment-id-1" class="comment-item">
              <section class="comment-body panel panel-default">
                <div class="panel-body">
                  <p><?=lang('no_comments_found')?></p>
                </div>
              </section>
            </article>
          <?php } } ?> 
        </section>
      </section>
    </div>
  </div>
</section>