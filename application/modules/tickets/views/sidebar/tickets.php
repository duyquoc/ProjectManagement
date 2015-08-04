<ul class="nav"><?php
							if (!empty($tickets)) {
							foreach ($tickets as $key => $t) {
							if($t->status == 'open'){ $s_label = 'danger'; }elseif($t->status=='closed'){ $s_label = 'success'; }else{ $s_label = 'default';} ?>
							<li class="b-b b-light <?php if($t->id == $this->uri->segment(3)){ echo "bg-light dk"; } ?>">
								<a href="<?=base_url()?>tickets/view/<?=$t->id?>">
									<?=$t->ticket_code?>
									<div class="pull-right">
									<?php if($t->status == 'closed'){ $label = 'success'; } else{ $label = 'danger'; } ?>
										<span class="label label-<?=$s_label?>"><?=ucfirst($t->status)?> </span>
									</div> <br>
									<small class="block small text-muted"><?=ucfirst(Applib::get_table_field(Applib::$user_table,array('id'=>$t->reporter),'username'))?> | <?=strftime(config_item('date_format'), strtotime($t->created));?> </small>
								</a> </li>
								<?php } } ?>
							</ul>