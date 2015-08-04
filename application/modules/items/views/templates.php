<section id="content"> <section class="vbox"> <section class="scrollable padder">
	<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
		<li><a href="<?=base_url()?>"><i class="fa fa-home"></i> <?=lang('home')?></a></li>
		<li class="active"><a href="<?=base_url()?>items"><?=lang('templates')?></a></li>
	</ul>
	<div class="row">
	<!-- Project Tasks -->
	<div class="col-lg-6">
	<section class="panel panel-default">
	<header class="panel-heading"> <i class="fa fa-navicon"></i> <a href="<?=base_url()?>items/save_task" class="btn btn-xs btn-primary pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('add_task')?></a> <?=lang('project_tasks')?></header>
	
	<div class="table-responsive">
		<table id="table-templates-1" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('task_name')?></th>
					<th><?=lang('visible')?> </th>
					<th><?=lang('estimated_hours')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php
								if (!empty($project_tasks)) {
				foreach ($project_tasks as $key => $task) { ?>
				<tr>
					<td>
					<a class="text-info" href="#" data-original-title="<?=$task->task_desc?>" data-toggle="tooltip" data-placement="top">
					<?=$task->task_name?></a></td>
					<td><?=$task->visible?></td>
					<td><strong><?=$task->estimate_hours?> <?=lang('hours')?></strong></td>
					<td>
					<a href="<?=base_url()?>items/edit_task/<?=$task->template_id?>" class="btn btn-default btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-edit"></i></a>
					<a href="<?=base_url()?>items/delete_task/<?=$task->template_id?>" class="btn btn-dark btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php  } } ?>
				
				
				
			</tbody>
		</table>
	</div>


</div>
<!-- End Project Tasks -->
<!-- Invoice Items -->
	<div class="col-lg-6">
	<section class="panel panel-default">
	<header class="panel-heading"> <i class="fa fa-navicon"></i> <?=lang('invoice_items')?> 
	<a href="<?=base_url()?>items/add_item" class="btn btn-xs btn-primary pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('new_item')?></a></header>

	<div class="table-responsive">
		<table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
				<th><?=lang('item_name')?></th>
					<th><?=lang('unit_price')?> </th>
					<th><?=lang('qty')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php
								if (!empty($invoice_items)) {
				foreach ($invoice_items as $key => $item) { ?>
				<tr>
				<td><a class="text-info" href="#" data-original-title="<?=$item->item_desc?>" data-toggle="tooltip" data-placement="top" title = ""><?=$item->item_name?></a></td>
					<?php $cur_d = $this->applib->currencies(config_item('default_currency')); ?>
                                        <td><?=$cur_d->symbol?> <?=number_format($item->unit_cost,2)?></td>
					<td><?=$item->quantity?></td>
					<td>
					<a href="<?=base_url()?>items/edit_item/<?=$item->item_id?>" class="btn btn-default btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-edit"></i></a>
					<a href="<?=base_url()?>items/delete_item/<?=$item->item_id?>" class="btn btn-dark btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php  } } ?>
				
				
				
			</tbody>
		</table>
	</div>


</div>
<!-- End Invoice Items -->
</div>

	</section>
</section>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>