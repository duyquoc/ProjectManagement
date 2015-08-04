<header class="header b-b b-light hidden-print">
    <a href="bugs/view/add" data-toggle="ajaxModal" class="btn btn-sm btn-info pull-right"><?=lang('new_bug')?></a>
</header>
<table class="table table-striped b-t b-light text-sm hover">
	<thead>
		<tr>
			<th><?=lang('bug_no')?></th>
			<th><?=lang('reporter')?></th>
			<th><?=lang('bug_status')?></th>
			<th><?=lang('priority')?></th>
			<th><?=lang('date')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (!empty($bugs)) {
			foreach ($bugs as $key => $bug) { ?>
			<tr class="success">
				<td><a class="text-info" href="<?=base_url()?>bugs/view/details/<?=$bug->bug_id?>"><?=$bug->issue_ref?></a></td>
				<td><?=ucfirst($bug->username)?></td>
				<td><?=$bug->bug_status?></td>
				<td><?=$bug->priority?></td>
				<td><?=strftime(config_item('date_format'), strtotime($bug->reported_on));?></td>
			</tr>
		<?php } } else{ ?>
			<tr>
				<td></td><td><?=lang('nothing_to_display')?></td><td></td><td></td><td></td>
			</tr>
		<?php } ?>		
	</tbody>
</table>