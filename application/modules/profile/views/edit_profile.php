<section id="content"> <section class="vbox">
  <header class="header bg-white b-b b-light">
  <p><?=lang('edit_profile_text')?> <small>(<?=$this->tank_auth->get_username()?>)</small></p> </header>
  <section class="scrollable wrapper">

    <div class="row">
      <div class="col-lg-6">
         <!-- Profile Form -->
        <section class="panel panel-default">
      <header class="panel-heading font-bold"><?=lang('profile_details')?></header>
      <div class="panel-body">
        <?php
        if (!empty($profile)) {
        foreach ($profile as $key => $p) { ?>
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
         echo form_open(uri_string(),$attributes); ?>
         <?php echo validation_errors(); ?>

        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="fullname" value="<?=$p->fullname?>" required>
          </div>
        </div>
        <input type="hidden" value="<?=$p->company?>" name="co_id">

        <?php
        if ($p->company > 0) {
        $company_email = Applib::get_table_field(Applib::$companies_table,
                          array('co_id' => $p->company), 'company_email');

        $company_address = Applib::get_table_field(Applib::$companies_table,
                          array('co_id' => $p->company), 'company_address');

        $company_vat = Applib::get_table_field(Applib::$companies_table,
                          array('co_id' => $p->company), 'vat');

        $company_name = Applib::get_table_field(Applib::$companies_table,
                          array('co_id' => $p->company), 'company_name');
        ?>
         <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_name]" value="<?=$company_name?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_email')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_email]" value="<?=$company_email?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_address')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_address]" value="<?=$company_address?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_vat')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[vat]" value="<?=$company_vat?>">
          </div>
        </div>
        <?php } ?>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('phone')?></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="phone" value="<?=$p->phone?>">
          </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?=lang('language')?></label>
            <div class="col-lg-8">
                <select name="language" class="form-control">
                <?php foreach ($languages as $lang) : ?>
                <option value="<?=$lang->name?>"<?=($p->language == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('locale')?></label>
                <div class="col-lg-8">
                        <select class="select2-option form-control" name="locale">
                        <?php foreach ($locales as $loc) : ?>
                        <option value="<?=$loc->locale?>"<?=($p->locale == $loc->locale ? ' selected="selected"' : '')?>><?=$loc->name?></option>
                        <?php endforeach; ?>
                        </select>
                </div>
        </div>
        
        <button type="submit" class="btn btn-sm btn-dark"><?=lang('update_profile')?></button>
      </form>
      <?php } } ?>


      <h4 class="page-header"><?=lang('change_email')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'auth/change_email',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">
     <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('password')?></label>
          <div class="col-lg-8">
          <input type="password" class="form-control" name="password" placeholder="<?=lang('password')?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('new_email')?></label>
          <div class="col-lg-8">
          <input type="email" class="form-control" name="email" placeholder="<?=lang('new_email')?>" required>
          </div>
        </div>
        
        <button type="submit" class="btn btn-sm btn-success"><?=lang('change_email')?></button>
      </form>


    </div>
  </section>
  <!-- /profile form -->
</div>
<div class="col-lg-6">
      
        <!-- Account Form -->
        <section class="panel panel-default">
      <header class="panel-heading font-bold"><?=lang('account_details')?></header>
      <div class="panel-body">
        <?php
        echo form_open(base_url().'auth/change_password'); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">
        <div class="form-group">
          <label><?=lang('old_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="old_password" placeholder="<?=lang('old_password')?>" required>
        </div>
        <div class="form-group">
          <label><?=lang('new_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="new_password" placeholder="<?=lang('new_password')?>" required>
        </div>
         <div class="form-group">
          <label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="confirm_new_password" placeholder="<?=lang('confirm_password')?>" required>
        </div>
        
        <button type="submit" class="btn btn-sm btn-dark"><?=lang('change_password')?></button>
      </form>

<h4 class="page-header"><?=lang('avatar_image')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart(base_url().'profile/changeavatar',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">

        <div class="form-group">
                      <label class="col-lg-3 control-label"><?=lang('use_gravatar')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" <?php if(Applib::get_table_field(Applib::$profile_table,array('user_id'=>$this->tank_auth -> get_user_id()),'use_gravatar') == 'Y'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
                          <span></span>
                        </label>
                      </div>
        </div>

       <div class="form-group">
        <label class="col-lg-3 control-label"><?=lang('avatar_image')?></label>
        <div class="col-lg-9">
          <input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
        </div>
      </div>
        <button type="submit" class="btn btn-sm btn-success"><?=lang('change_avatar')?></button>
      </form>

      <h4 class="page-header"><?=lang('change_username')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'auth/change_username',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">
     
        <div class="form-group">
          <label class="col-lg-3 control-label"><?=lang('new_username')?></label>
          <div class="col-lg-7">
          <input type="text" class="form-control" name="username" placeholder="<?=lang('new_username')?>" required>
          </div>
        </div>
        
        <button type="submit" class="btn btn-sm btn-danger"><?=lang('change_username')?></button>
      </form>


    </div>
  </section>
  <!-- /Account form -->
  
    </div>
  </div> </section> </section> <a href="widgets.html#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>