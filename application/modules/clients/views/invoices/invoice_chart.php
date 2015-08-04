<?php
            $user = $this->tank_auth->get_user_id();
            $user_company = $this->user_profile->get_profile_details($user,'company');

            $client_payments = $this->user_profile->client_paid($user_company);
            $client_payable = $this->user_profile->client_payable($user_company) + $this->applib->client_tax($user_company);
            $outstanding = $client_payable - $client_payments;
            if ($outstanding < 0) {
              $outstanding = 0;
            }
            if ($client_payable > 0) {
              $perc_paid = round(($client_payments/$client_payable) *100,1);
                if ($perc_paid > 100) {
                  $perc_paid = '100';
                }
                 
              }else{ 
                $perc_paid = 0; 
              } 
              if ($client_payable == 0 AND $client_payments == 0) { $perc_outstanding = 0; }else{
                $perc_outstanding = 100 - $perc_paid;
              }
                      
          ?>
  <div class="col-md-12">
        <div class="row">
                 <div class="col-lg-4">
                  <section class="panel panel-default">
                    <header class="panel-heading"><?=lang('paid_amount')?></header>
                    <div class="panel-body text-center">             
                <div class="sparkline inline" data-type="pie" data-height="150" data-slice-colors="['#8EC165','#FFC333']">
                <?=$perc_paid?>,<?=$perc_outstanding?></div>
                      <div class="line pull-in"></div>
                      <div class="text-xs">
                        <i class="fa fa-circle text-warning"></i> <?=lang('outstanding')?> - <?=$perc_outstanding?>%
                        <i class="fa fa-circle text-success"></i> <?=lang('paid')?> - <?=$perc_paid?>%
                      </div>
                    </div>
                     <div class="panel-footer"><small><?=lang('total_outstanding')?> + <?=lang('tax')?> : <strong>
                     <?=$this->config->item('default_currency')?> <?=number_format($outstanding,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
                  </section>
                </div>
                <div class="col-lg-4">
                  <section class="panel panel-default">
                    <header class="panel-heading">
                     <?=lang('payments_sent')?>
                    </header>
                    <div class="panel-body text-center">
                      <div class="inline">
                        <div class="easypiechart" data-percent="<?=$perc_paid?>" data-line-width="6" data-loop="false" data-size="188">
                          <span class="h2 step"><?=$perc_paid?></span>%
                          <div class="easypie-text"><?=lang('received')?></div>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                    <small><?=lang('total_receipts')?> + <?=lang('tax')?>: <strong><?=$this->config->item('default_currency')?> 
                    <?=number_format($client_payments,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
                  </section>
                </div>
                <div class="col-lg-4">
                  <section class="panel panel-default">
                    <header class="panel-heading">
                      <?=lang('percentage_pending')?>
                    </header>
                    <div class="panel-body text-center">
                      <div class="inline">
                        <div class="easypiechart" data-percent="<?=$perc_outstanding?>" data-line-width="30" data-track-color="#eee" data-bar-color="#afcf6f" data-scale-color="#fff" data-size="188" data-line-cap='butt'>
                          <span class="h2 step"><?=$perc_outstanding?></span>%
                          <div class="easypie-text"><?=lang('pending')?></div>
                        </div>
                      </div>                      
                    </div>
                    <div class="panel-footer"><small><?=lang('invoice_amount')?> + <?=lang('tax')?>: <strong><?=$this->config->item('default_currency')?> <?=number_format($client_payable,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
                  </section>
                </div>
              </div>

       
        
    </div>