<!-- Start -->
<section id="content">
  <section class="hbox stretch">
  
    <aside class="aside-md bg-white b-r" id="subNav">

      <div class="wrapper b-b header"><?=lang('project_notes')?>
      </div>
      <section class="vbox">
       <section class="scrollable w-f">
         <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
      <ul class="nav">
      <?php
        if (!empty($projects)) {
      foreach ($projects as $key => $p) { 
            $cur = $this->applib->client_currency($p->client);
            $project_hours = $this->user_profile->project_hours($p->project_id);
            $hours_spent = round($project_hours, 1);
            $fix_rate = $this->user_profile->get_project_details($p->project_id,'fixed_rate');
            $hourly_rate = $this->user_profile->get_project_details($p->project_id,'hourly_rate');
            if ($fix_rate == 'No') {
              $cost = $hours_spent * $hourly_rate;
            }else{
              $cost = $this->user_profile->get_project_details($p->project_id,'fixed_price');
            }
            ?>
        <li class="b-b b-light <?php if($p->project_id == $this->uri->segment(4)){ echo "bg-light dk"; } ?>">
        <a href="<?=base_url()?>projects/notebook/notes/<?=$p->project_id?>" data-toggle="tooltip" data-original-title="<?=$p->project_title?>">
        <?=ucfirst($this->applib->company_details($p->client,'company_name'))?>
       <div class="pull-right">
        <small class="text-muted"><strong><?=$cur->symbol?> <?=number_format($cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small>
        </div> <br>
        <small class="block small text-muted"><?=$p->project_title?>  <?php if($p->timer == 'On'){ ?><i class="fa fa-clock-o text-danger"></i> <?php } ?>  </small>

        </a> </li>
        <?php } }?>
      </ul> 
      </div></section>
      </section>
      </aside> 
      
      <aside>
      <section class="vbox">
        <header class="header bg-white b-b clearfix">
          <div class="row m-t-sm">
            <div class="col-sm-8 m-b-xs">
              
            <div class="btn-group">
            
            </div>
            <a class="btn btn-sm btn-dark" href="<?=base_url()?>projects/view/details/<?=$this->uri->segment(4)?>" title="<?=lang('project_details')?>" data-original-title="<?=lang('project_details')?>" data-toggle="tooltip" data-placement="top">
            <i class="fa fa-info-circle"></i> <?=lang('project_details')?></a>
            <a class="btn btn-sm btn-primary" href="<?=base_url()?>projects/view_projects/all" title="<?=lang('projects')?>" data-original-title="<?=lang('projects')?>" data-toggle="tooltip" data-placement="top">
            <i class="fa fa-coffee"></i> <?=lang('projects')?></a>
            <a class="btn btn-sm btn-default" href="<?=base_url()?>projects/view/add" title="<?=lang('new_project')?>" data-original-title="<?=lang('new_project')?>" data-toggle="tooltip" data-placement="top">
            <i class="fa fa-plus"></i> <?=lang('new_project')?></a>
            </div>
            <div class="col-sm-4 m-b-xs">
            <?php  echo form_open(base_url().'projects/search'); ?>
              <div class="input-group">
                <input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search_project')?>">
                <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit"><?=lang('go')?>!</button>
                </span>
              </div>
              </form>
            </div>
          </div> </header>
          <section class="scrollable wrapper w-f">
          
           <!-- Start Notebook -->

                   <section class="panel panel-default">
  <header class="panel-heading"> <i class="fa fa-pencil"></i> <?=lang('project_notes')?></header>
                         <?php echo form_open(base_url().'projects/notebook/savenote'); ?>
                         <input type="hidden" name="project" value="<?=$this->uri->segment(4)?>">
                         <input type="hidden" name="r_url" value="<?=uri_string()?>">
                         <aside>                       
                            <section class="paper">
                                <textarea type="text" class="form-control scrollable" name="notes" placeholder="Type your note here"><?=$this->user_profile->get_project_details($this->uri->segment(4),'notes')?></textarea>
                            </section>
                           
                        </aside>                        
                        </section>
                        <hr>
                         <button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
                            </form>


<!-- End Notebook -->
          </section>  




    </section> 
    </aside> 
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->