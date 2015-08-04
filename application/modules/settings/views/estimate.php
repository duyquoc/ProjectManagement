<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('estimate_settings')?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('estimate_color')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="estimate_color" class="form-control" style="width:260px" value="<?=config_item('estimate_color')?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('estimate_prefix')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="estimate_prefix" class="form-control" style="width:260px" value="<?=config_item('estimate_prefix')?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('display_estimate_badge')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="display_estimate_badge" />
                                <input type="checkbox" <?php if(config_item('display_estimate_badge') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="display_estimate_badge">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('show_item_tax')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="show_estimate_tax" />
                                <input type="checkbox" <?php if(config_item('show_estimate_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="show_estimate_tax">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group terms">
                        <label class="col-lg-3 control-label"><?=lang('estimate_terms')?></label>
                        <div class="col-lg-9">
                            <textarea class="form-control foeditor" name="estimate_terms"><?=config_item('estimate_terms')?></textarea>
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