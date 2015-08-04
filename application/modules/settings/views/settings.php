<section id="content">
    <section class="hbox stretch">
        <aside class="aside aside-md bg-white b-l b-r small">
            <section class="vbox">
                <header class="dk header b-b">
                    <button class="btn btn-icon btn-default btn-sm pull-right visible-xs m-r-xs" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></button>
                    <p class="h4">Settings</p>
                </header>
                <section>
                    <section>
                        <section id="setting-nav" class="hidden-xs">
                            <ul class="nav nav-pills nav-stacked no-radius">
                                <li class="<?php echo ($load_setting == 'general') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=general">
                                        <i class="fa fa-fw fa-info-circle"></i>
                                        <?=lang('company_details')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'system') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=system">
                                        <i class="fa fa-fw fa-desktop"></i>
                                        <?=lang('system_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'email') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=email">
                                        <!-- <span class="badge badge-hollow pull-right">4</span> -->
                                        <i class="fa fa-fw fa-envelope-o"></i>
                                        <?=lang('email_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'payments') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=payments">
                                        <i class="fa fa-fw fa-dollar"></i>
                                        <?=lang('payment_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'templates') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=templates">
                                        <i class="fa fa-fw fa-pencil-square"></i>
                                        <?=lang('email_templates')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'permissions') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=permissions">
                                        <i class="fa fa-fw fa-lock"></i>
                                        <?=lang('staff_permissions')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'invoice') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=invoice">
                                        <i class="fa fa-fw fa-money"></i>
                                        <?=lang('invoice_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'estimate') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=estimate">
                                        <i class="fa fa-fw fa-file-o"></i>
                                        <?=lang('estimate_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'departments') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=departments">
                                        <i class="fa fa-fw fa-sitemap"></i>
                                        <?=lang('departments')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'theme') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=theme">
                                        <i class="fa fa-fw fa-code"></i>
                                        <?=lang('theme_settings')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'fields') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=fields">
                                        <i class="fa fa-fw fa-star-o"></i>
                                        <?=lang('custom_fields')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($load_setting == 'translations') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=translations">
                                        <i class="fa fa-fw fa-globe"></i>
                                        <?=lang('translations')?>
                                    </a>
                                </li>
                            </ul>
                        </section>
                    </section>
                </section>
            </section>
        </aside>

        <aside>
            <section class="vbox">

                <header class="header bg-white b-b clearfix">
                    <div class="row m-t-sm">
                        <div class="col-sm-8 m-b-xs">
                            <?php if($load_setting == 'templates'){  ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info" title="Filter" data-toggle="dropdown"><i class="fa fa-cogs"></i> <?=lang('choose_template')?><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=user"><?=lang('account_emails')?></a></li>
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=bugs"><?=lang('bug_emails')?></a></li>
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=project"><?=lang('project_emails')?></a></li>
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=invoice"><?=lang('invoicing_emails')?></a></li>
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=ticket"><?=lang('ticketing_emails')?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?=base_url()?>settings/?settings=templates&group=extra"><?=lang('extra_emails')?></a></li>
                                    </ul>
                                </div>
                            <?php }
                            $set = array('theme','customize');
                            if( in_array($load_setting, $set)){  ?>
                                <a href="<?=base_url()?>settings/?settings=customize" class="btn btn-primary"><i class="fa fa-code text"></i>
                                    <span class="text"><?=lang('custom_css')?></span>
                                </a>
                            <?php }
                            $set = array('system', 'validate');
                            if( in_array($load_setting, $set)){  ?>
                                <a href="<?=base_url()?>settings/database" class="btn btn-danger"><i class="fa fa-cloud-download text"></i>
                                    <span class="text"><?=lang('database_backup')?></span>
                                </a>
                                <a href="<?=base_url()?>settings/vE" class="btn btn-primary <?=(config_item('valid_license') == 'TRUE') ? 'disabled' : '';?>"><i class="fa fa-credit-card text"></i>
                                    <span class="text">Validate Sale</span>
                                </a>
                            <?php } ?>

                            <?php if($load_setting == 'email'){  ?>
                                <a href="<?=base_url()?>settings/?settings=email&view=alerts" class="btn btn-info"><i class="fa fa-inbox text"></i>
                                    <span class="text"><?=lang('alert_settings')?></span>
                                </a>
                            <?php } ?>

                        </div>
                    </div>
                </header>
                <section class="scrollable wrapper">
                    <!-- Load the settings form in views -->
                    <?=$this -> load -> view($load_setting)?>
                    <!-- End of settings Form -->
                </section>
            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
</section>