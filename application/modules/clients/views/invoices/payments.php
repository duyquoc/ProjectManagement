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
              <li class="b-b b-light">
                <a href="<?=base_url()?>clients/payments/details/<?=$p->p_id?>">
                  <?=$this->user_profile->get_invoice_details($p->invoice,'reference_no')?>
                  <div class="pull-right">
                    <?php $cur = $this->applib->currencies($p->currency); ?>
                    <?=$cur->symbol?> <?=number_format($p->amount,2,config_item('decimal_separator'),config_item('thousand_separator'))?>
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
            <section class="scrollable wrapper w-f">
              <!-- Start Payment -->
              
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
  <?php
      $user = $this -> tank_auth -> get_user_id();                        
      $company = $this->user_profile->get_profile_details($user,'company');

      $client_outstanding = $this -> applib -> client_outstanding($user);
    
      $client_payments = $this -> applib -> get_sum('payments','amount',$array = array('paid_by'=>$company));

      $client_payable = $client_payments + $client_outstanding;
      $cur2 = $this->applib->client_currency($company);
  ?>
                        <div style="width: 70%;float: left;">
                          <div style="width: 100%;padding: 11px 0;">
                            <div style="color:#999;width:35%;float:left;"><?=lang('invoice_amount')?></div>
                            <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;"><?=$cur2->symbol?> <?=number_format($client_payable,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></div>
                            <div style="clear:both;"></div>
                          </div><div style="width: 100%;padding: 10px 0;">
                          <div style="color:#999;width:35%;float:left;"><?=lang('payments_sent')?></div>
                          <div style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;min-height:22px"><?=$cur2->symbol?> <?=number_format($client_payments,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></div>
                          <div style="clear:both;"></div>
                        </div>
                      </div>
                      <div style="text-align:center;color:white;float:right;background:#FC8174;width: 25%;
                        padding: 20px 5px;">
                        <span> <?=lang('amount_received')?></span><br>
                        <span style="font-size:16pt;"><?=$cur2->symbol?> <?=number_format($client_payments,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></span>
                        </div><div style="clear:both;"></div>
                        <div style="padding-top:10px">
                          <div style="width:75%;border-bottom:1px solid #eee;float:right"><strong>
                            
                            <?php
                            $query = $this->db->where('paid_by',$company)->get('payments');
                            if ($query->num_rows() > 0){
                            $row = $query->last_row('array'); ?>
                            <a href="<?=base_url()?>invoices/view/<?=$row['invoice']?>">
                              <?php echo $this->user_profile->get_invoice_details($row['invoice'],'reference_no'); ?>
                            </a>
                            <?php }else{
                            echo 'NULL';
                            }
                          ?></strong></div>
                          <div style="color:#999;width:25%"><?=lang('recent_invoice')?></div>
                        </div>
                        <div style="padding-top:25px">
                          <div style="width:75%;border-bottom:1px solid #eee;float:right"><?=$cur2->symbol?> <?=number_format($client_outstanding,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></div>
                          <div style="color:#999;width:25%"><?=lang('outstanding')?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  
                </div>
              </div>
              <!-- End Payment -->
            </section>
            </section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>
            <!-- end -->