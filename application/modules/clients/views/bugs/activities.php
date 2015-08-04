<!-- Timeline START -->
															<section class="panel panel-default">
																<div class="panel-body">
																	<div class="timeline">
														
															<?php
					if (!empty($activities)) {
					foreach ($activities as $key => $a) { ?>
					<?php
					if ($a->activity_id % 2 == 0) { ?>
		<article class="timeline-item">
		<div class="timeline-caption">
			<div class="panel panel-default">
				<div class="panel-body">
					<span class="arrow left"></span>
					<span class="timeline-icon"><i class="fa <?=$a->icon?> time-icon bg-dark"></i>
					</span>
					<span class="timeline-date"><?=strftime("%b %d, %Y %H:%M:%S", strtotime($a->activity_date)) ?></span> 
					<h5><a href="<?=base_url()?>clients/view/details/<?=$a->id*1200?>"><?=ucfirst($a->username)?></a> </h5>
					<p>
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
                                </p>
				</div>
			</div>
		</div> </article>
					<?php }else{ ?>
		  <article class="timeline-item alt">
		<div class="timeline-caption">
			<div class="panel panel-default">
				<div class="panel-body">
					<span class="arrow right"></span>
					<span class="timeline-icon"><i class="fa <?=$a->icon?> time-icon bg-info"></i></span>
					<span class="timeline-date"><?=strftime("%b %d, %Y %H:%M:%S", strtotime($a->activity_date)) ?></span> 
					<h5><a href="#"><?=ucfirst($a->username)?></a></h5>
					<p>
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
                                </p>
				</div>
			</div>
		</div> 
		</article> 
		<?php } ?>
		<?php } } else{ echo lang('nothing_to_display'); } ?>


																	<div class="timeline-footer"><a href="#"><i class="fa fa-plus time-icon inline-block bg-dark"></i></a>
																		</div>
																	</div>
																</div>
															</section>