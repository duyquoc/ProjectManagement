<!-- Start -->
<section id="content">
  <section class="hbox stretch">
    
    <aside class="aside-md bg-white b-r" id="subNav">
      <header class="dk header b-b">
        
        <p class="h4"><?=lang('all_payments')?></p>
      </header>
      <section class="vbox">
        <section class="scrollable w-f">
          <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
            <ul class="nav">
              <?php
              if (!empty($payments)) {
              foreach ($payments as $key => $p) { ?>
              <li class="b-b b-light <?php if($p->p_id == $this->uri->segment(4)){ echo "bg-light dk"; } ?>">
                <a href="<?=base_url()?>clients/payments/details/<?=$p->p_id?>">
                  <?=$this->user_profile->get_invoice_details($p->invoice,'reference_no')?>
                  <div class="pull-right">
                  <?php $cur = $this->applib->currencies($p->currency);  ?>
                    <?=$cur->symbol?> <?=number_format($p->amount,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?>
                  </div> <br>
                  <small class="block small text-info"><?=$p->trans_id?> </small>
                </a> </li>
                <?php } } ?>
              </ul>
            </div></section>
          </section>
        </aside>
        
        <aside>
          <section class="vbox">
            <header class="header bg-white b-b clearfix">
              <div class="row m-t-sm">
                <div class="col-sm-8 m-b-xs">
                  
                  
                </div>
                <div class="col-sm-4 m-b-xs">
                  <?php  echo form_open(base_url().'clients/payments/search'); ?>
                  <div class="input-group">
                    <input type="text" class="input-sm form-control" name="keyword" placeholder="<?=lang('search')?>">
                    <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button>
                    </span>
                  </div>
                </form>
              </div>
            </div> </header>
            <section class="scrollable wrapper">
              <!-- Start Payment -->
              
              <?php
              if (!empty($payment_details)) {
              foreach ($payment_details as $key => $i) { ?>
              <div class="column content-column">
                <div class="details-page" style="margin:45px 25px 25px 8px">
                  <div class="details-container clearfix" style="margin-bottom:20px">
                    <div style="font-size:10pt;">
                      
                      <div style="padding:35px;">
                        <div style="padding-bottom:35px;border-bottom:1px solid #eee;width:100%;">
                          <div>
                            <div style="text-transform: uppercase;font-weight: bold;">
                              <?=$this->config->item('company_name')?>
                            </div>
                            <span style="color:#999"><?=$this->config->item('company_address')?></span>
                          </div>
                          <div style="clear:both;"></div>
                        </div>
                        <div style="padding:35px 0 50px;text-align:center">
                          <span style="text-transform: uppercase; border-bottom:1px solid #eee;font-size:13pt;"><?=lang('payments_sent')?></span>
                        </div>
                        <div style="width: 70%;float: left;">
                          <div style="width: 100%;padding: 11px 0;">
                            <div style="color:#999;width:35%;float:left;"><?=lang('payment_date')?></div>
                            <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;"><?=strftime("%d %b %Y", strtotime($i->created_date));?></div>
                            <div style="clear:both;"></div>
                          </div><div style="width: 100%;padding: 10px 0;">
                          <div style="color:#999;width:35%;float:left;"><?=lang('transaction_id')?></div>
                          <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;min-height:22px"><?=$i->trans_id?></div>
                          <div style="clear:both;"></div>
                        </div>
                      </div>
                      <div style="text-align:center;color:white;float:right;background:#78ae54;width: 25%;
                        padding: 20px 5px;">
                        <span> <?=lang('amount_received')?></span><br>
                        <?php $cur2 = $this->applib->currencies($i->currency);  ?>
                        <span style="font-size:16pt;"><?=$cur2->symbol?> <?=number_format($i->amount,2,config_item('decimal_separator'),config_item('thousand_separator'))?></span>
                        </div><div style="clear:both;"></div>
                        <div style="padding-top:10px">
                          <div style="width:75%;border-bottom:1px solid #eee;float:right"><strong><a href="#">
                          <?=ucfirst($this -> applib -> company_details($i->paid_by,'company_name'))?></a></strong></div>
                          <div style="color:#999;width:25%"><?=lang('received_from')?></div>
                        </div>
                        <div style="padding-top:25px">
                          <div style="width:75%;border-bottom:1px solid #eee;float:right"><?=Applib::get_table_field(Applib::$payment_methods_table,array('method_id' => $i->payment_method),'method_name')?></div>
                          <div style="color:#999;width:25%"><?=lang('payment_mode')?></div>
                        </div>
                        <div style="padding-top:25px">
                          <div style="width:75%;border-bottom:1px solid #eee;float:right"><?=$i->notes?></div>
                          <div style="color:#999;width:25%"><?=lang('notes')?></div>
                        </div>
                        
                        <div style="margin-top:10px">
                          <div style="width:100%">
                            <div style="width:50%;float:left"><h4><?=lang('payment_for')?></h4></div>
                            <div style="clear:both;"></div>
                          </div>
                          
                          <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                              <tr style="height:40px;background:#f5f5f5">
                                <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                                  <?=lang('invoice_code')?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                  <?=lang('invoice_date')?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                  <?=lang('invoice_amount')?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                  <?=lang('paid_amount')?>
                                </td>
                              </tr>
                            </thead>
                            <tbody>
                              <tr style="border-bottom:1px solid #ededed">
                                <td style="padding: 10px 0px 10px 10px;" valign="top">
                                <?=$this->user_profile->get_invoice_details($i->invoice,'reference_no')?></td>
                                <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;" valign="top">
                                  <?=strftime("%d %b %Y", strtotime($this->user_profile->get_invoice_details($i->invoice,'date_saved')))?>
                                </td>
                                <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;" valign="top">
                                  <span>
                                  <?=$cur2->symbol?>
                                   <?=number_format($this -> applib -> calculate('invoice_cost',$i->invoice),2,config_item('decimal_separator'),config_item('thousand_separator'))?>
                                    </span>
                                </td>
                                <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;" valign="top">
                                  <span><?=$cur2->symbol?> <?=number_format($i->amount,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  
                </div>
              </div>
              <?php } } ?>
              <!-- End Payment -->
            </section>
            </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>
            <!-- end -->