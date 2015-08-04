<?php $this->applib->set_locale(); ?>
<section class="panel panel-default">
<header class="header bg-white b-b clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">
                  <?php  if($role == 1){ ?>
                  <a href="<?=base_url()?>projects/links/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('add_link')?></a> 
                  <?php } ?>

                    </div>
                  </div>
                </header>
    <div class="table-responsive">
                  <table id="table-links" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('link_title')?></th>
                        <?php if($role == '1'){ ?>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    $client =Applib::get_table_field(Applib::$projects_table,
                             array('project_id'=>$project_id),'client');

                    $links = $this -> db -> where('project_id',$project_id)
                                   ->or_where('client',$client) 
                                   -> get(Applib::$links_table) 
                                   -> result();

                    if (!empty($links)) {
                    foreach ($links as $key => $link) { 
                        ?>
                      <tr>
                        <td>
                            <img class="favicon" src="http://www.google.com/s2/favicons?domain=<?=$link->link_url;?>" />
                            <a class="text-info" href="<?=base_url()?>projects/view/<?=$link->project_id?>?group=links&view=link&id=<?=$link->link_id?>" data-original-title="<?=$link->description?>" data-toggle="tooltip" data-placement="right" title = ""><?=$link->link_title?></a>
                        <?php if (!empty($link->password)){ ?>
                           <i class="fa fa-lock pull-right"></i>
                        <?php } ?>
                        </td>
                        <?php  if($role == 1){ ?>
                        <td>
                        <a class="btn btn-xs btn-info" href="<?=base_url()?>projects/links/edit/<?=$link->link_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                        <a href="<?=base_url()?>projects/links/delete/<?=$link->project_id;?>/<?=$link->link_id?>" data-toggle="ajaxModal" title="<?=lang('delete_link')?>" class="btn btn-xs btn-dark"><i class="fa fa-trash-o text-white"></i></a>
                        <a href="<?=base_url()?>projects/links/pin/<?=$link->project_id;?>/<?=$link->link_id?>" title="<?=lang('link_pin');?>" class="foAjax btn btn-xs <?=($client == $link->client ? 'btn-danger':'btn-default');?> btn"><i class="fa fa-thumb-tack"></i></a>
                        <a href="<?=$link->link_url?>" target="_blank" title="<?=$link->link_title?>" class="btn btn-xs btn-primary"><i class="fa fa-external-link text-white"></i></a>
                        </td>
                        <?php } ?>
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>

<!-- End details -->
 </section>