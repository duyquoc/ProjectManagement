<?php $this->applib->set_locale(); ?>
<section id="content">
  <section class="hbox stretch">

<aside>
      <section class="vbox">

<section class="scrollable wrapper">
  <section class="panel panel-default">
                <header class="panel-heading"><?=lang('all_payments')?> 
                </header>
                <div class="table-responsive">
                  <table id="table-payments" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th class="col-options no-sort" width="30"><?=lang('options')?></th>
                        <th class="col-date"><?=lang('payment_date')?></th>
                        <th class="col-date"><?=lang('invoice_date')?></th>
                        <th><?=lang('invoice')?></th>
                        <th><?=lang('client')?></th>
                        <th class="col-currency"><?=lang('amount')?></th>
                        <th><?=lang('payment_method')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($payments)) {
              foreach ($payments as $key => $p) { ?>
                      <tr>
                      <?php
                        $currency = $this -> applib->get_any_field('invoices',array('inv_id'=>$p->invoice),'currency');
                        $invoice_date = $this -> applib->get_any_field('invoices',array('inv_id'=>$p->invoice),'date_saved');
                        $invoice_date = strftime(config_item('date_format'), strtotime($invoice_date));
                        ?>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                            <?=lang('options')?>
                            <span class="caret"></span></button>
                            <ul class="dropdown-menu">                      
                            <li><a href="<?=base_url()?>invoices/payments/details/<?=$p->p_id?>"><?=lang('view_payment')?></a></li>
                            <li><a href="<?=base_url()?>invoices/payments/edit/<?=$p->p_id?>"><?=lang('edit_payment')?></a></li>
                            <li><a href="<?=base_url()?>invoices/payments/delete/<?=$p->p_id?>" data-toggle="ajaxModal"><?=lang('delete_payment')?></a></li>
                            </ul>
                          </div>
                        </td>
                        <td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>
                        <td><?=$invoice_date?></td>
                        <td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$p->invoice?>"><?=$this -> applib->get_any_field('invoices',array('inv_id'=>$p->invoice),'reference_no')?></a></td>
                        
                        <td><?=$this -> applib->get_any_field('companies',array('co_id'=>$p->paid_by),'company_name')?></td>
                        <?php $cur = $this->applib->currencies($currency); ?>
                        <td><?=$cur->symbol?> <?=number_format($p->amount,2,config_item('decimal_separator'),config_item('thousand_separator'))?></td>
                        <td><?=$this -> applib->get_any_field('payment_methods',array('method_id'=>$p->payment_method),'method_name')?></td>
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </section>
              </section>
  
     



    </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->