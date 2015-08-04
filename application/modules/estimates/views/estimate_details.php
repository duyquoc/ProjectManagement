<?php $this->applib->set_locale(); ?>
<section id="content">
    <section class="hbox stretch">
        <?php
        $username = $this -> tank_auth -> get_username();
        ?>

        <aside>
            <section class="vbox">
                <header class="header bg-white b-b clearfix hidden-print">
                    <div class="row m-t-sm">
                        <div class="col-sm-8 m-b-xs">
                            <?php
                            if (!empty($estimate_details)) {
                            foreach ($estimate_details as $key => $estimate) { ?>

                            <?php
                            $l = $this->applib->company_details($estimate->client,'language');
                            $lang2 = $this->lang->load('fx_lang', $l, TRUE, FALSE, '', TRUE);
                            ?>
                            <a data-original-title="<?=lang('print_estimate')?>" data-toggle="tooltip" data-placement="top" href="#" class="btn btn-sm btn-default" onClick="window.print();">
                                <i class="fa fa-print"></i> </a>

                            <?php if($role == '1' OR $this -> applib -> allowed_module('edit_estimates',$username)) { ?>
                                <a href="<?=base_url()?>estimates/items/insert/<?=$estimate->est_id?>" title="<?=lang('item_quick_add')?>" class="btn btn-sm btn-<?=config_item('button_color')?>" data-toggle="ajaxModal">
                                    <i class="fa fa-list-alt text-white"></i> <?=lang('items')?></a>

                                <a data-original-title="<?=lang('convert_to_invoice')?>" data-toggle="tooltip" data-placement="top" class="btn btn-sm btn-success <?php if($estimate->invoiced == 'Yes' OR $estimate->client == '0'){ echo "disabled"; } ?>" href="<?=base_url()?>estimates/action/convert/<?=$estimate->est_id?>" data-toggle="ajaxModal"
                                   title="<?=lang('convert_to_invoice')?>">
                                    <?=lang('convert_to_invoice')?></a>


                                <?php if($estimate->show_client == 'Yes'){ ?>
                                <a class="btn btn-sm btn-success" href="<?=base_url()?>estimates/hide/<?=$estimate->est_id?>" title="<?=lang('hide_to_client')?>"><i class="fa fa-eye-slash"></i> <?=lang('hide_to_client')?>
                                    </a><?php }else{ ?>
                                    <a class="btn btn-sm btn-dark" href="<?=base_url()?>estimates/show/<?=$estimate->est_id?>" title="<?=lang('show_to_client')?>"><i class="fa fa-eye"></i> <?=lang('show_to_client')?>
                                    </a>
                                <?php } } ?>


                            <div class="btn-group">
                                <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                    <?=lang('more_actions')?>
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">

                                    <?php if($role == '1' OR $this -> applib -> allowed_module('edit_estimates',$username)) { ?>
                                        <li><a href="<?=base_url()?>estimates/edit/<?=$estimate->est_id?>"><?=lang('edit_estimate')?></a></li>
                                        <li><a href="<?=base_url()?>estimates/email/<?=$estimate->est_id?>" data-toggle="ajaxModal"><?=lang('email_estimate')?></a></li>
                                        <li><a href="<?=base_url()?>estimates/timeline/<?=$estimate->est_id?>"><?=lang('estimate_history')?></a></li>
                                    <?php } ?>
                                    <li><a href="<?=base_url()?>estimates/action/status/declined/<?=$estimate->est_id?>"><?=lang('mark_as_declined')?></a></li>
                                    <li><a href="<?=base_url()?>estimates/action/status/accepted/<?=$estimate->est_id?>"><?=lang('mark_as_accepted')?></a></li>

                                    <?php if($role == '1' OR $this -> applib -> allowed_module('delete_estimates',$username)) { ?>
                                        <li class="divider"></li>
                                        <li><a href="<?=base_url()?>estimates/delete/<?=$estimate->est_id?>" data-toggle="ajaxModal"><?=lang('delete_estimate')?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                        <div class="col-sm-4 m-b-xs">
                            <?php
                            if ($estimate->client != 0) { ?>
                                <?php if (config_item('pdf_engine') == 'invoicr') : ?>
                                <a href="<?=base_url()?>fopdf/estimate/<?=$estimate->est_id?>" class="btn btn-sm btn-dark pull-right"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
                                <?php elseif(config_item('pdf_engine') == 'mpdf') : ?>
                                <a href="<?=base_url()?>estimates/pdf/<?=$estimate->est_id?>" class="btn btn-sm btn-dark pull-right"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
                                <?php endif; ?>
                            <?php } ?>

                        </div>
                    </div> </header>

                <!-- Start Display Details -->

                <section class="scrollable wrapper ie-details">
                    <!-- Start Display Details -->

                    <?php
                    if(!$this->session->flashdata('message')){
                        if(strtotime($estimate->due_date) < time() AND $estimate->status == 'Pending'){ ?>
                            <div class="alert alert-warning hidden-print">
                                <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                                <?=lang('estimate_overdue')?>
                            </div>
                        <?php } } ?>

                    <section class="scrollable wrapper">
                        <div class="row">
                            <div class="col-xs-6">
                                <img class="ie-logo" src="<?=base_url()?>resource/images/logos/<?=config_item('invoice_logo')?>" >

                            </div>
                            <div class="col-xs-6 text-right">
                                <p class="h4"><?=$estimate->reference_no?></p>
                                <p><?=$lang2['estimate_date']?><span class="col-xs-3 no-gutter-right pull-right"><strong><?=strftime(config_item('date_format'), strtotime($estimate->date_saved));?></strong></span></p>
                                <p><?=$lang2['valid_until']?><span class="col-xs-3 no-gutter-right pull-right"><strong><?=strftime(config_item('date_format'), strtotime($estimate->due_date));?></strong></span></p>
                                <p><?=$lang2['payment_status']?><span class="col-xs-3 no-gutter-right pull-right"><span class="label bg-success"><?=$estimate->status?></span></span></p>
                                <p></p>
                            </div>
                        </div>


                        <div class="well m-t">
                            <div class="row">
                                <div class="col-xs-6">
                                    <strong><?=$lang2['received_from']?>:</strong>

                                    <h4><?=(config_item('company_legal_name_'.$l) != '') ? config_item('company_legal_name_'.$l) : config_item('company_legal_name')?></h4>
                                    <p><?=(config_item('company_address_'.$l) != '') ? config_item('company_address_'.$l) : config_item('company_address')?><br>
                                        <?=(config_item('company_city_'.$l) != '') ? config_item('company_city_'.$l) : config_item('company_city')?>
                                        <?php if (config_item('company_zip_code') != '') : ?>
                                            , <?=config_item('company_zip_code')?>
                                        <?php endif; ?>
                                        <br>
                                        <?=(config_item('company_country_'.$l) != '') ? config_item('company_country_'.$l) : config_item('company_country')?><br>
                                        <span class="col-xs-3 no-gutter"><?=$lang2['phone']?>: </span><a href="tel:<?=$this->config->item('company_phone')?>"><?=$this->config->item('company_phone')?></a><br>
                                        <?php if (config_item('company_phone_2') != '') : ?>
                                            <span class="col-xs-3 no-gutter"><?=$lang2['phone']?> 2:</span><a href="tel:<?=$this->config->item('company_phone_2')?>"><?=$this->config->item('company_phone_2')?></a><br>
                                        <?php endif; ?>
                                        <span class="col-xs-3 no-gutter"><?=$lang2['company_vat']?>:</span><?=$this->config->item('company_vat')?><br>
                                    </p>
                                </div>
                                <div class="col-xs-6">
                                    <strong><?=$lang2['bill_to']?>:</strong>
                                    <h4><?=ucfirst($this->applib->company_details($estimate->client,'company_name'))?> <br></h4>
                                    <p>
                                        <?=ucfirst($this->applib->company_details($estimate->client,'company_address'))?><br>
                                        <?=ucfirst($this->applib->company_details($estimate->client,'city'))?><br>
                                        <?=ucfirst($this->applib->company_details($estimate->client,'country'))?> <br>
                                        <span class="col-xs-3 no-gutter"><?=$lang2['phone']?>:</span><?=$this->applib->company_details($estimate->client,'company_phone')?> <br>
                                        <span class="col-xs-3 no-gutter"><?=$lang2['company_vat']?>:</span><?=$this->applib->company_details($estimate->client,'VAT')?> <br>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="line"></div>
                        <table class="table sorted_table" type="estimates"><thead>
                            <tr>
                                <th width="20%"><?=$lang2['item_name']?> </th>
                                <th width="25%"><?=$lang2['description']?> </th>
                                <th width="10%"><?=$lang2['qty']?> </th>
                                <th width="10%"><?=$lang2['tax_rate']?> </th>
                                <th width="10%" class="text-right"><?=$lang2['unit_price']?> </th>
                                <th width="10%" class="text-right"><?=$lang2['tax']?> </th>
                                <th width="15%" class="text-right"><?=$lang2['total']?> </th>
                            </tr> </thead> <tbody>
                            <?php
                            if (!empty($estimate_items)) {
                                foreach ($estimate_items as $key => $item) { ?>
                                    <tr class="sortable" data-name="<?=$item->item_name?>" data-id="<?=$item->item_id?>">
                                        <td>
                                            <?php
                                            $item_name = $item->item_name ? $item->item_name : $item->item_desc;
                                            if($role == '1' OR $this -> applib -> allowed_module('edit_estimates',$username)) { ?>
                                                <a class="text-info" href="<?=base_url()?>estimates/items/edit/<?=$item->item_id?>" data-toggle="ajaxModal"><?=$item_name?></a>
                                            <?php }else{ ?>
                                                <?=$item_name?>
                                            <?php } ?>
                                        </td>

                                        <td><small class="small text-muted"><?=nl2br($item->item_desc) ?></small> </td>
                                        <td><?=$item->quantity?></td>
                                        <td><?=$item->item_tax_rate?>%</td>
                                        <td class="text-right"><?=number_format($item->unit_cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></td>

                                        <td class="text-right"><?=number_format($item->item_tax_total,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></td>

                                        <td class="text-right"><?=number_format($item->total_cost,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>
                                            <?php if($role == '1' OR $this -> applib -> allowed_module('edit_estimates',$username)) { ?>
                                                <a class="hidden-print" href="<?=base_url()?>estimates/items/delete/<?=$item->item_id?>/<?=$item->estimate_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                            <?php if($role == '1' OR $this -> applib -> allowed_module('edit_estimates',$username)) { ?>
                                <tr class="hidden-print">
                                    <?php
                                    $attributes = array('class' => 'bs-example form-horizontal');
                                    echo form_open(base_url().'estimates/items/add', $attributes); ?>
                                    <input type="hidden" name="estimate_id" value="<?=$estimate->est_id?>">
                                    <input type="hidden" name="item_order" value="<?=count($estimate_items)+1?>">
                                    <input id="hidden-item-name" type="hidden" name="item_name" value="<?=count($estimate_items)+1?>">
                                    <td><input id="auto-item-name" data-scope="estimates" type="text" name="item_name_auto" placeholder="Item Name" class="typeahead form-control"></td>
                                    <td><textarea id="auto-item-desc" data-autoresize rows="1" name="item_desc" placeholder="Item Description" class="form-control autoresize"></textarea></td>
                                    <td><input id="auto-quantity" type="text" name="quantity" placeholder="1" class="form-control"></td>
                                    <td>
                                        <select name="item_tax_rate" class="form-control m-b">
                                            <option value="0.00"><?=lang('none')?></option>
                                            <?php
                                            if (!empty($rates)) {
                                                foreach ($rates as $key => $tax) { ?>
                                                    <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                                                <?php } } ?>
                                        </select>
                                    </td>
                                    <td><input id="auto-unit-cost" type="text" name="unit_cost" required placeholder="50.56" class="form-control"></td>

                                    <td><input type="text" name="tax" placeholder="0.00" readonly="" class="form-control"></td>

                                    <td><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> <?=lang('save')?></button></td>
                                    </form>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="6" class="text-right no-border"><strong><?=$lang2['sub_total']?></strong></td>
                                <td> <?=number_format($this -> applib -> est_calculate('estimate_cost',$estimate->est_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right no-border">
                                    <strong><?=$lang2['tax']?> <?php echo $estimate->tax;?>%</strong></td>
                                <td><?=number_format($this -> applib -> est_calculate('tax',$estimate->est_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?> </td>
                            </tr>
                            <?php if($estimate->discount > 0){ ?>
                                <tr>
                                    <td colspan="6" class="text-right no-border">
                                        <strong><?=$lang2['discount']?> - <?php echo $estimate->discount;?>%</strong></td>
                                    <td><?=number_format($this -> applib -> est_calculate('discount',$estimate->est_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?> </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="6" class="text-right no-border"><strong><?=$lang2['total']?></strong></td>
                                <?php $cur = $this->applib->currencies($estimate->currency); ?>
                                <td><?=$cur->symbol?> <?=number_format($this -> applib -> est_calculate('estimate_amount',$estimate->est_id),2,config_item('decimal_separator'),config_item('thousand_separator'))?></td>
                            </tr>
                            </tbody>
                        </table>
                    </section>
                    <p><blockquote><?=$estimate->notes?></blockquote></p>
                </section>
                <?php } } ?>
                <!-- End display details -->






            </section>




    </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>


<?php unset($lang2); ?>
<!-- end -->