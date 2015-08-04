
<section class="panel panel-default">
	<header class="panel-heading">
		Project Timesheet </header>
	<table class="table table-striped m-b-none text-sm hover">
		<thead>
			<tr>
				<th><?=lang('start_time')?></th>
					<th><?=lang('stop_time')?></th>
					<th><?=lang('total_time')?></th>
			</tr> </thead> <tbody>
			<?php
								if (!empty($timesheets)) {
				foreach ($timesheets as $key => $t) { ?>

				<tr class="success">
				<td><?=strftime("%b %d, %Y %H:%M:%S", $t->start_time)?></td>
				<td><?=strftime("%b %d, %Y %H:%M:%S", $t->end_time)?></td>
				<td><?php
				if (($t->end_time - $t->start_time)/3600 < 1) {
					echo round(($t->end_time - $t->start_time)/60,0).' '.lang('minutes');
				}else{ ?>
				<?=round(($t->end_time - $t->start_time)/3600,2)?> <?=lang('hours')?><?php } ?></td>
				</tr>
				<?php  }} else{ ?>
				<tr>
					<td></td><td><?=lang('nothing_to_display')?></td><td></td>
				</tr>
				<?php } ?>
				
			
	
	 </tbody>
</table> </section>

<section class="panel panel-default">
	<header class="panel-heading">
		Tasks Timesheet </header>
	<table class="table table-striped m-b-none text-sm hover">
		<thead>
			<tr>
				<th><?=lang('start_time')?></th>
					<th><?=lang('stop_time')?></th>
					<th><?=lang('total_time')?></th>
			</tr> </thead> <tbody>
			<?php
								if (!empty($tasks_log)) {
				foreach ($tasks_log as $key => $task) { ?>

				<tr>
				<td><?=strftime("%b %d, %Y %H:%M:%S", $task->start_time)?></td>
				<td><?=strftime("%b %d, %Y %H:%M:%S", $task->end_time)?></td>
				<td><?php
				if (($task->end_time - $task->start_time)/3600 < 1) {
					echo round(($task->end_time - $task->start_time)/60,0).' '.lang('minutes');
				}else{ ?>
				<?=round(($task->end_time - $task->start_time)/3600,2)?> <?=lang('hours')?><?php } ?></td>
				</tr>
				<?php  }} else{ ?>
				<tr>
					<td></td><td><?=lang('nothing_to_display')?></td><td></td>
				</tr>
				<?php } ?>
				
			
	
	 </tbody>
</table> </section>