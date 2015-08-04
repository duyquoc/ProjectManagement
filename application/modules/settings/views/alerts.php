<div class="row">
    <!-- Start Form -->
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-inbox"></i> <?=lang('alert_settings')?></header>
                <div class="panel-body">
                    <?php
                    $attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
                    echo form_open('settings/update', $attributes); ?>
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('email_account_details')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="email_account_details" />
                                <input type="checkbox" <?php if(config_item('email_account_details') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_account_details">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('email_staff_tickets')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="email_staff_tickets" />
                                <input type="checkbox" <?php if(config_item('email_staff_tickets') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_staff_tickets">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_bug_assignment')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_bug_assignment" />
                                <input type="checkbox" <?php if(config_item('notify_bug_assignment') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_assignment">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_bug_comments')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_bug_comments" />
                                <input type="checkbox" <?php if(config_item('notify_bug_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_comments">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_bug_status')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_bug_status" />
                                <input type="checkbox" <?php if(config_item('notify_bug_status') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_status">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_project_assignments')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_project_assignments" />
                                <input type="checkbox" <?php if(config_item('notify_project_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_assignments">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_project_comments')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_project_comments" />
                                <input type="checkbox" <?php if(config_item('notify_project_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_comments">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_project_files')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_project_files" />
                                <input type="checkbox" <?php if(config_item('notify_project_files') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_files">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_task_assignments')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_task_assignments" />
                                <input type="checkbox" <?php if(config_item('notify_task_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_task_assignments">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 control-label"><?=lang('notify_message_received')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_message_received" />
                                <input type="checkbox" <?php if(config_item('notify_message_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_message_received">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-5 col-lg-10">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> <?=lang('save_changes')?></button>
                        </div>
                    </div>
                    </form>
                </div> </section>
        </div>
    <!-- End Form -->
</div>