<section id="content">
  <section class="hbox stretch">
 
    <!-- .aside -->
    <aside>
      <section class="vbox">
        <header class="header bg-white b-b b-light">
          <a href="#aside" data-toggle="class:show" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> <?=lang('new_company')?></a>
          <p><?=lang('registered_clients')?></p>
        </header>
        <section class="scrollable wrapper">
          <div class="row">
            <div class="col-lg-12">
              <section class="panel panel-default">
                <div class="table-responsive">
                  <table id="table-clients" class="table table-striped m-b-none AppendDataTables">
                    <thead>
                      <tr>
                        
                        <th><?=lang('company_name')?> </th>
                        <th><?=lang('contacts')?></th>
                        <th class="hidden-sm"><?=lang('primary_contact')?></th>
                        <th><?=lang('website')?> </th>
                        <th><?=lang('email')?> </th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr> </thead> <tbody>
                      <?php
                      if (!empty($companies)) {
                      foreach ($companies as $client) { ?>
                      <tr>
                        <td><a href="<?=base_url()?>companies/view/details/<?=$client->co_id?>" class="text-info">
                        <?=$client->company_name?></a></td>
                        <td><span class="badge bg-success" title="<?=lang('contacts')?>"><?=$this->applib->count_rows('account_details',array('company'=>$client->co_id))?></span></td>
                        <td class="hidden-sm"><?=$this->user_profile->get_profile_details($client->primary_contact,'fullname')?></td>
                        <td><a href="<?=$client->company_website?>" class="text-info" target="_blank">
                        <?=$client->company_website?></a>
                      </td>
                      <td><?=$client->company_email?></td>
                      <td>
                        <a href="<?=base_url()?>companies/view/details/<?=$client->co_id?>" class="btn btn-default btn-xs" title="<?=lang('details')?>"><i class="fa fa-home"></i> </a>
                        <a href="<?=base_url()?>companies/view/delete/<?=$client->co_id?>" class="btn btn-default btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>"><i class="fa fa-trash-o"></i></a>
                      </td>
                    </tr>
                    <?php } } ?>
                    
                    
                  </tbody>
                </table>

              </div>
            </section>            
          </div>
        </div>
      </section>

    </section>
  </aside>
  <!-- /.aside -->

  <!-- .aside -->
  <aside class="aside-lg bg-white b-l hide" id="aside">
    <section class="vbox">
      <section class="scrollable wrapper">
      <?php
      echo form_open(base_url().'companies/create'); ?>
      <?php echo validation_errors(); ?>
      <input type="hidden" name="company_ref" value="<?=$this->applib->generate_string()?>">
      <div class="form-group">
        <label><?=lang('company_name')?> <span class="text-danger">*</span></label>
        <input type="text" name="company_name" placeholder="<?=lang('company_placeholder_name')?>" value="<?=set_value('company_name')?>" class="input-sm form-control">
      </div>
      <div class="form-group">
        <label><?=lang('company_email')?> <span class="text-danger">*</span></label>
        <input type="email" placeholder="<?=lang('company_placeholder_email')?>" name="company_email" value="<?=set_value('company_email')?>" class="input-sm form-control">
      </div>
      <div class="form-group">
        <label><?=lang('phone')?> </label>
        <input type="text" placeholder="<?=lang('phone')?>" value="<?=set_value('company_phone')?>" name="company_phone"  class="input-sm form-control">
      </div>
      <div class="form-group">
        <label><?=lang('company_domain')?> </label>
        <input type="text" placeholder="<?=lang('company_domain')?>" value="<?=set_value('company_website')?>" name="company_website"  class="input-sm form-control">
      </div>
      <div class="form-group">
        <label><?=lang('address')?></label>
        <textarea name="company_address" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <label><?=lang('city')?> </label>
        <input type="text" placeholder="<?=lang('company_placeholder_city')?>" value="<?=set_value('city')?>" name="city" class="input-sm form-control">
      </div>
      
      <div class="form-group">
        <label><?=lang('country')?> </label>
        <select class="select2-option" style="width:200px" name="country" > 
          <optgroup label="Default Country"> 
          <option value="<?=$this->config->item('company_country')?>"><?=$this->config->item('company_country')?></option>
          </optgroup> 
          <optgroup label="<?=lang('other_countries')?>"> 
            <?php foreach ($countries as $country): ?>
            <option value="<?=$country->value?>"><?=$country->value?></option>
            <?php endforeach; ?>
          </optgroup> 
          </select> 
      </div>
      
        <div class="form-group">
            <label><?=lang('language')?></label>
            <select name="language" class="form-control">
            <?php foreach ($languages as $lang) : ?>
            <option value="<?=$lang->name?>"<?=($this->config->item('language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label><?=lang('currency')?></label>
            <select name="currency" class="form-control">
            <?php foreach ($currencies as $cur) : ?>
            <option value="<?=$cur->code?>"<?=($this->config->item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
            <?php endforeach; ?>
            </select>
        </div>
      
      <button type="submit" class="btn btn-sm btn-success"><?=lang('add_company')?></button>
      <hr>
    </form>
   
  </section></section>
   
</aside>
<!-- /.aside -->
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>