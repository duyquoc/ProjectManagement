<table class="table table-striped b-t b-light text-sm">
			<thead>
				<tr>
					<th><?=lang('project_code')?></th>
					<th><?=lang('project_name')?></th>
					<th><?=lang('due_date')?> </th>
					<th><?=lang('progress')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php
								if (!empty($user_projects)) {
				foreach ($user_projects as $key => $project) { ?>
				<tr>
					<td><a class="text-info" href="<?=base_url()?>projects/view/details/<?=$project->project_id?>">
					<?=$project->project_code?></a></td>
					<td><?=$project->project_title?> </td>
					<td><?=strftime(config_item('date_format'), strtotime($project->due_date));?> </td>
					<td><div class="progress progress-xs m-t-xs progress-striped active m-b-none">
				<div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="<?=$project->progress?>%" style="width: <?=$project->progress?>%">
											</div>
										</div>
					</td>
					<td>
					<a href="<?=base_url()?>projects/view/edit/<?=$project->project_id?>" class="btn btn-default btn-xs">
					<i class="fa fa-edit" title="<?=lang('edit_project')?>"></i> </a>
					</td>
				</tr>
				<?php  }} else{ ?>
				<tr>
					<td></td><td><?=lang('nothing_to_display')?></td><td></td><td></td><td></td>
				</tr>
				<?php } ?>
				
				
				
			</tbody>
		</table>