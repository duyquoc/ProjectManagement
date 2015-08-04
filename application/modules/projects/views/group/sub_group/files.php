<?php $this->load->helper('file'); ?>
<section class="panel panel-default">
<header class="header bg-white b-b clearfix">
                  <div class="row m-t-sm">
                  <div class="col-sm-12 m-b-xs">
                  <a href="<?=base_url()?>projects/files/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('upload_file')?></a>

                     

                    </div>
                  </div>
                </header>
                <?=$this->session->flashdata('form_error')?>
    <div class="table-responsive">
                  <table id="table-files" class="table table-striped b-t b-light small">
                    <thead>
                      <tr>
                        <th width="45%"><?=lang('files')?></th>
                        <th class=""><?=lang('size')?></th>
                        <th class="col-date"><?=lang('date')?></th>
                        <th width="20%"><?=lang('user')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $this->load->helper('file');
                    $files = $this -> db -> where(array('project'=>$project_id)) -> get('files') -> result();
                    if (!empty($files)) {
                foreach ($files as $key => $f) {  
                  $icon = $this->applib->file_icon($f->ext);
                  $path = $f->path;
                  $fullpath = base_url().'resource/project-files/'.$path.$f->file_name;
                    if($path == NULL){
                        $fullpath = base_url().'resource/project-files/'.$f->file_name;
                    }

                  $real_url = $fullpath;
                  ?>
                      <tr class="file-item">
                        <td>
                            <?php if ($f->is_image == 1) : ?>
                            <?php if ($f->image_width > $f->image_height) {
                                $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                                $style = 'height:100%; margin-left: -'.$ratio.'%';
                            } else {
                                $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                                $style = 'width:100%; margin-top: -'.$ratio.'%';
                            }  ?>
                                <div class="file-icon"><a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a></div>
                            <?php else : ?>
                                <div class="file-icon"><i class="fa <?=$icon?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <a data-toggle="tooltip" data-placement="top" data-original-title="<?=$this->applib->short_string($f->file_name,25,5,30)?>" class="text-info" href="<?=base_url()?>projects/download/<?=$f->project?>?group=files&id=<?=$f->file_id?>">
                            <?=$f->title?>
                            <?php if ($f->is_image == 1) : ?>
                                <em><?=$f->image_width."x".$f->image_height?></em>
                                <?php endif; ?>
                            </a>
                            <p class="file-text"><?=$this->applib->short_string($f->description,100,0,100)?></p>
                        </td>
                        <td class=""><?=$f->size?> Kb</td>
                        <td class="col-date"><?=strftime(config_item('date_format')."<br> %H:%m",strtotime($f->date_posted));?></td>
                        <td>
                        <a class="pull-left thumb-sm avatar">
                        <?php
                          $user_email = Applib::login_info($f->uploaded_by)->email;
                          $gravatar_url = $this -> applib -> get_gravatar($user_email);
                           if(config_item('use_gravatar') == 'TRUE' AND $this -> applib -> get_any_field(Applib::$profile_table,array('user_id'=>$f->uploaded_by),'use_gravatar') == 'Y'){ ?>
                          <img src="<?=$gravatar_url?>" class="img-circle">
                          <?php }else{ ?>
                          <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($f->uploaded_by)->avatar?>" class="img-circle">
                        <?php } ?> 
                            <span class="label label-success">
                            <?=ucfirst(Applib::login_info($f->uploaded_by)->username)?>
                            </span>
                          </a>
                        </td>
                        <td>
                          <?php  if($f->uploaded_by == $this-> tank_auth -> get_user_id() OR $role == '1'){ ?>
                          <a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/files/delete/<?=$f->project?>?group=files&id=<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
                          <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/files/edit/<?=$f->project?>?group=files&id=<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
                          <?php } ?>
                          <a class="btn btn-xs btn-dark" href="<?=base_url()?>projects/download/<?=$f->project?>?group=files&id=<?=$f->file_id?>"><i class="fa fa-download"></i></a>
                        </td>
                        
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>

<!-- End details -->
 </section>