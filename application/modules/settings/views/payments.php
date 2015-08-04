<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?=$this->session->flashdata('form_error')?>
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open('settings/update', $attributes); ?>
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('payment_settings')?></header>
                <div class="panel-body">

                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('paypal_email')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="email" name="paypal_email" class="form-control" value="<?=$this->config->item('paypal_email')?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('paypal_ipn_url')?> </label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="<?=lang('change_if_necessary')?>" value="<?=$this->config->item('paypal_ipn_url')?>" name="paypal_ipn_url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('paypal_cancel_url')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="<?=lang('change_if_necessary')?>"  value="<?=$this->config->item('paypal_cancel_url')?>" name="paypal_cancel_url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('paypal_success_url')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="<?=lang('change_if_necessary')?>"  value="<?=$this->config->item('paypal_success_url')?>" name="paypal_success_url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('paypal_live')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="paypal_live" />
                                <input type="checkbox" <?php if(config_item('paypal_live') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="paypal_live">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">2checkout Publishable Key</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=config_item('2checkout_publishable_key')?>" name="2checkout_publishable_key">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">2checkout Private Key</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=config_item('2checkout_private_key')?>" name="2checkout_private_key">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">2checkout Seller ID</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=config_item('2checkout_seller_id')?>" name="2checkout_seller_id">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('stripe_private_key')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=$this->config->item('stripe_private_key')?>" name="stripe_private_key">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('stripe_public_key')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=$this->config->item('stripe_public_key')?>" name="stripe_public_key">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('bitcoin_address')?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=$this->config->item('bitcoin_address')?>" name="bitcoin_address">
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