<?php
      $total_receipts = $this->applib->get_sum('payments','amount',$array = array('inv_deleted' => 'No'));
      $invoice_amount = $this->applib->get_sum('items','total_cost',$array = array('total_cost >' => '0'));
      $total_sales = $invoice_amount + $this->applib->total_tax();
      $outstanding = $total_sales - $total_receipts;
      if ($outstanding < 0) {
       $outstanding = 0;
      }
      if ($total_sales > 0) {
            $perc_paid = ($total_receipts/$total_sales)*100;
            if ($perc_paid > 100) {
              $perc_paid = '100';
            }else{
              $perc_paid = round($perc_paid,1);
            }
            $perc_outstanding = round(100 - $perc_paid,1);
          }else{ $perc_paid = 0; $perc_outstanding = 0;}         
          ?>
  <div class="col-md-12">
        <div class="row">
                 <div class="col-lg-4">
                  <section class="panel panel-default">
                    <header class="panel-heading"><?=lang('revenue_collection')?></header>
                    <div class="panel-body text-center">             
                <div class="sparkline inline" data-type="pie" data-height="150" data-slice-colors="['#8EC165','#FFC333']">
                <?=$perc_paid?>,<?=$perc_outstanding?></div>
                      <div class="line pull-in"></div>
                      <div class="text-xs">
                        <i class="fa fa-circle text-warning"></i> <?=lang('outstanding')?> - <?=$perc_outstanding?>%
                        <i class="fa fa-circle text-success"></i> <?=lang('paid')?> - <?=$perc_paid?>%
                      </div>
                    </div>
                     <div class="panel-footer"><small><?=lang('total_outstanding')?> : <strong>
                     <?=$this->config->item('default_currency')?> <?=number_format($outstanding,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
                  </section>
                </div>
                <div class="col-lg-4">
                  <section class="panel panel-default">
                    <header class="panel-heading">
                     <?=lang('percentage_received')?>
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
                    <small><?=lang('total_receipts')?> : <strong><?=$this->config->item('default_currency')?> 
                    <?=number_format($total_receipts,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
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
                    <div class="panel-footer"><small><?=lang('total_sales')?>: <strong><?=$this->config->item('default_currency')?> <?=number_format($total_sales,2,$this->config->item('decimal_separator'),$this->config->item('thousand_separator'))?></strong></small></div>
                  </section>
                </div>
              </div>

        <div class="row">
        <section class="panel panel-default">
  
  <header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('revenue_collection')?> - <?=$this->config->item('default_currency')?></header>
  <div class="panel-body">
        <!-- Start Chart -->
        <div style="width:98%">
      <div>
        <canvas id="invoice_revenue" height="280" width="600"></canvas>
      </div>
    </div>
    </div>
    </section>
                <!-- End Chart -->
              </div> 
        
    </div>