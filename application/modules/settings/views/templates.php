<?php
$template_group = isset($_GET['group'])?$_GET['group']:'user';
switch ($template_group) {
    case "bugs": $default = "bug_assigned"; break;
    case "extra": $default = "estimate_email"; break;
    case "invoice": $default = "invoice_message"; break;
    case "project": $default = "project_assigned"; break;
    case "ticket": $default = "ticket_client_email"; break;
    case "user": $default = "activate_account"; break;
}
$setting_email = isset($_GET['email'])?$_GET['email']:$default;

$email['bugs'] = array("bug_assigned","bug_status","bug_comment","bug_file","bug_reported");
$email['extra'] = array("estimate_email","message_received");
$email['invoice'] = array("invoice_message","invoice_reminder","payment_email");
$email['project'] = array("project_assigned","project_comment","project_complete","project_file","project_updated","task_assigned","task_updated");
$email['ticket'] = array("ticket_client_email","ticket_closed_email","ticket_reply_email","ticket_staff_email");
$email['user'] = array("activate_account","change_email","forgot_password","registration","reset_password");

$attributes = array('class' => 'bs-example form-horizontal');
echo form_open('settings/templates?settings=templates&group='.$template_group.'&email='.$setting_email, $attributes);
?>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('email_templates')?></header>
                <div class="panel-body">
                    <div class="m-b-sm">
                        <div class="btn-group">
                            <?php foreach ($email[$template_group] as $temp) :
                                $lang = $temp;
                                switch($temp) {
                                    case "registration": $lang = 'register_email'; break;
                                    case "bug_comment": $lang = 'bug_comments'; break;
                                    case "project_file": $lang = 'project_files'; break;
                                    case "project_comment": $lang = 'project_comments'; break;
                                    case "project_assigned": $lang = 'project_assignment'; break;
                                    case "task_assigned": $lang = 'task_assignment'; break;
                                } ?>
                                <a href="<?=base_url()?>settings/?settings=templates&group=<?=$template_group;?>&email=<?=$temp;?>" class="<?php if($setting_email == $temp){ echo "active"; } ?> btn btn-default"><?=lang($lang)?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" name="email_group" value="<?=$setting_email;?>">
                    <input type="hidden" name="return_url" value="<?=base_url()?>settings/?settings=templates&group=<?=$template_group;?>&email=<?=$setting_email;?>">
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('subject')?></label>
                        <div class="col-lg-12">
                            <input class="form-control" name="subject" value="<?=$this -> applib -> get_any_field('email_templates',array(
                                'email_group' => $setting_email
                            ), 'subject')?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('message')?></label>
                        <div class="col-lg-12">
                    <textarea class="form-control foeditor" name="email_template">
                    <?=$this -> applib -> get_any_field('email_templates',array(
                        'email_group' => $setting_email
                    ), 'template_body')?></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>