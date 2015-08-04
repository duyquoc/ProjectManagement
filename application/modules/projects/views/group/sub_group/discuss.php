
<section class="panel panel-default">
<?php
$bug = isset($_GET['id']) ? $_GET['id'] : 0;
$details = $this -> db -> where(array('bug_id'=>$bug)) -> get('bugs') -> result();
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
                  
                  <a href="<?=base_url()?>projects/view/<?=$i->project?>/?group=bugs&view=bug&id=<?=$i->bug_id?>" class="btn btn-sm btn-dark"><?=lang('bug_details')?></a> 

                     

                    </div>
                  </div>
                </header>

                <div class="row">
<div class="col-lg-12">

<section class="panel panel-body">

<section class="comment-list block">
<article class="comment-item media" id="comment-form">
<?php
$user = $this -> tank_auth -> get_user_id();
$myavatar = Applib::profile_info($user)->avatar;
?>
                      <a class="pull-left thumb-sm avatar"><img src="<?=base_url()?>resource/avatar/<?=$myavatar?>" class="img-circle"></a>
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
                            <ul class="nav nav-pills nav-sm">
                            </ul>
                          </footer>
                          </form>
                        </section>
                      
                      </section>
                    </article>
<?php
$bug_comments = $this -> db -> where(array('bug_id'=>$bug)) 
                      ->order_by('date_commented','desc') 
                      -> get('bug_comments') 
                      -> result();

                  if (!empty($bug_comments)) {
                  foreach ($bug_comments as $key => $c) {
  $avatar = Applib::profile_info($c->comment_by)->avatar;
  $role_id = Applib::login_info($c->comment_by)->role_id;
  $user_role = $this->tank_auth->user_role($role_id);
  $username = Applib::login_info($c->comment_by)->username;
if($user_role == 'admin'){ $role_label = 'danger'; }else{ $role_label = 'info';}
?> 
                    <article id="comment-id-1" class="comment-item">
                      <a class="pull-left thumb-sm avatar">
                        <img src="<?=base_url()?>resource/avatar/<?=$avatar?>" class="img-circle">
                      </a>
                      <span class="arrow left"></span>
                      <section class="comment-body panel panel-default">
                        <header class="panel-heading bg-white">
                          <a href="#"><?=ucfirst($username)?></a>
                          <label class="label bg-<?=$role_label?> m-l-xs"><?=$user_role?></label> 
                          <span class="text-muted m-l-sm pull-right">
                            <i class="fa fa-clock-o"></i>
                            <?php
                $today = time();
                $comment_day = strtotime($c->date_commented) ;
                echo $this-> applib -> get_time_diff($today,$comment_day);
              ?> <?=lang('ago')?>
                          </span>
                        </header>
                        <div class="panel-body">
                          <div class="text-muted small"><?=$c->comment?></div>
                          <div class="comment-action m-t-sm">
                            
                          </div>
                        </div>
                      </section>
                    </article>

                   <?php } }else{ ?>
                      <article id="comment-id-1" class="comment-item">
                      <section class="comment-body panel panel-default">
                      <p>No comments found</p>
                      </section>
                      </article>
                      <?php } ?>
                   
                  </section>

                  <?php } } ?>
                  </section>
                  </div>
                  </div>
                  </section>