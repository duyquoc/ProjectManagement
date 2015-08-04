<section id="content">
    <section class="hbox stretch">
        <aside class="aside-md bg-white b-r" id="subNav">
            <header class="dk header b-b">
                <p class="h4"><?=lang('all_messages')?></p>
            </header>
            <section class="vbox">
                <section class="scrollable w-f">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                        <section id="setting-nav" class="hidden-xs">
                            <ul class="nav nav-pills nav-stacked no-radius">
                                <li class="<?php echo ($group == 'inbox') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>messages?group=inbox"> <i class="fa fa-fw fa-envelope"></i>
                                        <?=lang('inbox')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($group == 'sent') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>messages?group=sent"> <i class="fa fa-fw fa-exchange"></i>
                                        <?=lang('sent')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($group == 'favourites') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>messages?group=favourites"> <i class="fa fa-fw fa-star"></i>
                                        <?=lang('favourites')?>
                                    </a>
                                </li>
                                <li class="<?php echo ($group == 'trash') ? 'active' : '';?>">
                                    <a href="<?=base_url()?>messages?group=trash"> <i class="fa fa-fw fa-trash-o"></i>
                                        <?=lang('trash')?>
                                    </a>
                                </li>
                            </ul>
                        </section>
                    </div>
                </section>
            </section>
        </aside>
        <!-- .aside -->
        <aside class="bg-light lter" id="email-list">
            <section class="vbox">
                <header class="header bg-white b-b clearfix">
                    <div class="row m-t-sm">
                        <div class="col-sm-4 col-sm-offset-8 m-b-xs">
                            <?php echo form_open(base_url().'messages/search/'); ?>
                                <div class="input-group">
                                    <input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('keyword')?>">
                                        <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
                                        </span>
                                </div>
                            </form>
                        </div>
                    </div> </header>
                <section class="scrollable wrapper w-f">
                    <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                        <?=$this->session->flashdata('form_error')?>
                        <?php

                        $attributes = array('class' => 'bs-example form-horizontal');
                        echo form_open(base_url().'messages/send',$attributes); ?>
                            <section class="panel panel-default">
                                <header class="panel-heading font-bold"><i class="fa fa-envelope"></i> <?=lang('message_notification')?></header>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?=lang('username')?> <span class="text-danger">*</span> </label>
                                        <div class="col-lg-9">
                                            <div class="m-b">
                                                <select class="select2-option" multiple="multiple" style="width:260px" required name="user_to[]" >
                                                    <optgroup label="<?=lang('clients')?>">
                                                        <?php foreach ($clients as $client): ?>
                                                            <option value="<?=$client->id?>"><?=ucfirst($client->username)?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                    <optgroup label="<?=lang('administrators')?>">
                                                        <?php foreach ($admins as $admin): ?>
                                                            <option value="<?=$admin->id?>"><?=ucfirst($admin->username)?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <textarea name="message" required class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-sm btn-primary"><?=lang('send_message')?></button>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </section>
                <footer class="footer b-t bg-white-only">
                    <form class="m-t-sm">
                        <div class="input-group">
                            <input class="input-sm form-control input-s-sm" placeholder="<?=lang('search')?>" type="text">
                            <div class="input-group-btn"> <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </footer>
            </section>
        </aside>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>