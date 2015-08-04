<?php $this->applib->set_locale(); ?>
<section class="panel panel-default">
  <?php
  $link_id = isset($_GET['id']) ? $_GET['id'] : 0;
  $details = $this -> db -> where(array('link_id'=>$link_id)) -> get('links') -> result();
  if (!empty($details)) {
    foreach ($details as $key => $link) {
      if($link->project_id == $project_id){
      ?>
    <header class="header bg-white b-b clearfix">
      <div class="row m-t-sm">
        <div class="col-sm-12 m-b-xs">
          <?php if($role == '1'){ ?>
            <a href="<?=base_url()?>projects/links/edit/<?=$link->link_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-dark"><?=lang('edit_link')?></a> 
            <a href="<?=base_url()?>projects/link/delete/<?=$link->project_id?>/<?=$link->link_id?>" data-toggle="ajaxModal" title="<?=lang('delete_link')?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o text-white"></i> <?=lang('delete_link')?></a>
          <?php }?>
        </div>
      </div>
    </header>
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-7">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=$link->link_title?> </span><?=lang('link_title')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><a href="<?=$link->link_url?>" target="_blank"><?=$link->link_url?> </a> </span><?=lang('link_url')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><?=$this -> applib->get_any_field('projects',array('project_id'=>$link->project_id),'project_title');?></span><?=lang('project')?>
            </li>
          </ul>
        </div>
        <!-- End details C1-->
        <div class="col-lg-5">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=$link->username?></span><?=lang('username')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><input id="link-password" class="discreet" type="password" value="<?=$link->password?>" /></span><?=lang('password')?>
            </li>
          </ul>
        </div>
      </div>
      <p><blockquote class="small text-muted"><?=$link->description?></blockquote></p>
    </div>
    <!-- End details -->
    <!-- End ROW 1 -->
  <?php } } } ?>
</section>