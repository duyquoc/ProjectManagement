<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('invoice_settings')?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('invoice_color')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="invoice_color" class="form-control" style="width:260px" value="<?=config_item('invoice_color')?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('invoice_prefix')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="invoice_prefix" class="form-control" style="width:260px" value="<?=config_item('invoice_prefix')?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('invoices_due_after')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="invoices_due_after" class="form-control" style="width:260px" data-toggle="tooltip" data-placement="top" data-original-title="<?=lang('days')?>" value="<?=config_item('invoices_due_after')?>" required>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('invoice_start_number')?></label>
                        <div class="col-lg-7">
                            <input type="text" name="invoice_start_no" class="form-control" style="width:260px" value="<?=config_item('invoice_start_no')?>">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">PDF Engine</label>
                        <div class="col-lg-2">
                            <select name="pdf_engine" class="form-control">
                                <option value="invoicr"<?=(config_item('pdf_engine') == 'invoicr'? ' selected="selected"': '')?>>Invoicr</option>
                                <option value="mpdf"<?=(config_item('pdf_engine') == 'mpdf'? ' selected="selected"': '')?>>mPDF</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('display_invoice_badge')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="display_invoice_badge" />
                                <input type="checkbox" <?php if(config_item('display_invoice_badge') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="display_invoice_badge">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('automatic_email_on_recur')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="automatic_email_on_recur" />
                                <input type="checkbox" <?php if(config_item('automatic_email_on_recur') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="automatic_email_on_recur">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('show_item_tax')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="show_invoice_tax" />
                                <input type="checkbox" <?php if(config_item('show_invoice_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="show_invoice_tax">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('invoice_logo')?></label>
                        <div class="col-lg-9">
                            <input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
                        </div>
                    </div>
                    <div class="form-group terms">
                        <label class="col-lg-3 control-label"><?=lang('default_terms')?></label>
                        <div class="col-lg-9">
                            <textarea class="form-control foeditor" name="default_terms"><?=config_item('default_terms')?></textarea>
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