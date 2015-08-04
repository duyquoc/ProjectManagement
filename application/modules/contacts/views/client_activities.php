	
	<div class="table-responsive">
		<table class="table table-striped b-t b-light text-sm">
			<thead>
				<tr>					
					<th><?=lang('module')?></th>
					<th><?=lang('activity')?> </th>
					<th><?=lang('activity_date')?></th>
				</tr> </thead> <tbody>
				<?php
					if (!empty($user_activities)) {
					foreach ($user_activities as $key => $a) { ?>
				<tr>
				<td><?=strtoupper($a->module)?></td>
				<td>
                                    <?php 
                                    if (lang($a->activity) != '') {
                                        if (!empty($a->value1)) {
                                            if (!empty($a->value2)){
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
                                            } else {
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
                                            }
                                        } else { echo lang($a->activity); }
                                    } else { echo $a->activity; } 
                                    ?> 
                                </td>
				<td><?=$a->activity_date?></td>
			</tr>
			<?php }} else{ ?>
				 <tr>
								<td></td><td>Nothing to display here</td><td></td>
								</tr>
				<?php } ?>
			
	

 </tbody>
</table>
</div>
