<!-- Start -->
<section id="content">
	<section class="hbox stretch">

<aside>
			<section class="vbox">

<section class="scrollable wrapper w-f">
	<section class="panel panel-default">
                <header class="panel-heading"><?=lang('tax_rates')?> 
                
                <a href="<?=base_url()?>invoices/tax_rates/add" data-toggle="ajaxModal" class="btn btn-xs btn-primary pull-right"><?=lang('new_tax_rate')?></a>
                
                </header>
                <div class="table-responsive">
                  <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('tax_rate_name')?></th>
                        <th><?=lang('tax_rate_percent')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($rates)) {
							foreach ($rates as $key => $r) { ?>
                      <tr>
                        <td><?=$r->tax_rate_name?></td>
                        <td><?=$r->tax_rate_percent?> %</td>
                        
                        <td>
                        <a class="btn btn-primary btn-sm" href="<?=base_url()?>invoices/tax_rates/edit/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('edit_rate')?>"><?=lang('edit_rate')?></a>
                <a class="btn btn-dark btn-sm" href="<?=base_url()?>invoices/tax_rates/delete/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('delete_rate')?>"><?=lang('delete_rate')?></a>
                        </td>
                      </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </section>
              </section>
	
		 



		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->