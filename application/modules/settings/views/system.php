<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('system_settings')?></header>
                <div class="panel-body">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Purchase Code <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text"  class="form-control" <?=(config_item('valid_license') == 'TRUE') ? 'readonly="readonly"' : '';?> value="Nulled by Voky">
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_language')?> <?=  config_item('default_language')?></label>
                        <div class="col-lg-4">
                            <select name="default_language" class="form-control">
                                <?php foreach ($languages as $lang) : ?>
                                    <option lang="<?=$lang->code?>" value="<?=$lang->name?>"<?=(config_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('locale')?></label>
                        <div class="col-lg-4">
                            <select class="select2-option" name="locale" class="form-control" required>
                                <?php foreach ($locales as $loc) : ?>
                                    <option lang="<?=$loc->code?>" value="<?=$loc->locale?>"<?=(config_item('locale') == $loc->locale ? ' selected="selected"' : '')?>><?=$loc->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('timezone')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-5">
                            <select class="select2-option" name="timezone" class="form-control" required>
                                <?php foreach ($timezones as $timezone => $description) : ?>
                                    <option value="<?=$timezone?>"<?=(config_item('timezone') == $timezone ? ' selected="selected"' : '')?>><?=$description?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_currency')?></label>
                        <div class="col-lg-3">
                            <select name="default_currency" class="form-control">
                                <?php $cur = $this->applib->currencies(config_item('default_currency')); ?>
                                <?php foreach ($currencies as $cur) : ?>
                                    <option value="<?=$cur->code?>"<?=(config_item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('default_tax')?></label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" value="<?=config_item('default_tax')?>" name="default_tax">
                        </div>
                    </div>
                    <?php $this->applib->set_locale(); $date_format = config_item('date_format'); ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('date_format')?></label>
                        <div class="col-lg-3">
                            <select name="date_format" class="form-control">
                                <option value="%d-%m-%Y"<?=($date_format == "%d-%m-%Y" ? ' selected="selected"' : '')?>><?=strftime("%d-%m-%Y", time())?></option>
                                <option value="%m-%d-%Y"<?=($date_format == "%m-%d-%Y" ? ' selected="selected"' : '')?>><?=strftime("%m-%d-%Y", time())?></option>
                                <option value="%Y-%m-%d"<?=($date_format == "%Y-%m-%d" ? ' selected="selected"' : '')?>><?=strftime("%Y-%m-%d", time())?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('decimal_separator')?></label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" value="<?=config_item('decimal_separator')?>" name="decimal_separator">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('thousand_separator')?></label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" value="<?=config_item('thousand_separator')?>" name="thousand_separator">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('enable_languages')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="enable_languages" />
                                <input type="checkbox" <?php if(config_item('enable_languages') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="enable_languages">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('use_gravatar')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="use_gravatar" />
                                <input type="checkbox" <?php if(config_item('use_gravatar') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('allow_client_registration')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="allow_client_registration" />
                                <input type="checkbox" <?php if(config_item('allow_client_registration') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="allow_client_registration">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('file_max_size')?> <span class="text-danger">*</span> </label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" value="<?=config_item('file_max_size')?>" name="file_max_size" data-type="digits" data-required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('allowed_files')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=config_item('allowed_files')?>" name="allowed_files">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('cron_key')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=config_item('cron_key')?>" name="cron_key">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('client_create_project')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="client_create_project" />
                                <input type="checkbox" <?php if(config_item('client_create_project') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="client_create_project">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('demo_mode')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="demo_mode" />
                                <input type="checkbox" <?php if(config_item('demo_mode') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="demo_mode">
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
                    </div>
                 </div>
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>