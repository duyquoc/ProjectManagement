<section id="content"> <section class="vbox"> 
<section class="scrollable padder">
	<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
	<small><?=lang('welcome_back')?> , <?php
		echo $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'fullname') ? $this->user_profile->get_profile_details($this->tank_auth->get_user_id(),'fullname') : $this->tank_auth->get_username()?> </small>

	</ul>
	<?php
		$user = $this->tank_auth->get_user_id();
		$user_company = $this->user_profile->get_profile_details($user,'company');

		$client_outstanding = $this -> applib -> client_outstanding($user);
		
		$client_payments = $this->user_profile->get_sum('payments','amount',$array = array('paid_by'=>$user_company));

		$client_payable = $client_payments + $client_outstanding;

			if ($client_payable > 0 AND $client_payments > 0) {
				$perc_paid = round(($client_payments/$client_payable) * 100,1);
				if ($perc_paid > 100) {
						$perc_paid = '100';
					}
				}else{ 
						$perc_paid = 0; 
					}

		
		$total_projects = $this->user_profile->count_rows('projects',array('client'=>$user_company));
		$complete_projects = $this->user_profile->count_rows('projects',array('client'=>$user_company,'progress >='=>'100'));
				if ($total_projects > 0) {
					$perc_complete = round(($complete_projects/$total_projects) *100,1);
					$perc_open = 100 - $perc_complete;
				}else{
					$perc_complete = 0;
					$perc_open = 0;
				}



				
	?>
        <?php $cur = $this->applib->client_currency($user_company); ?>



	<div class="m-b-md"> 

	<?php if($client_outstanding > 0){ ?>
          
<div class="alert alert-info hidden-print">
<button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
<?=lang('your_balance_due')?>: <?=$cur->symbol?> <?=number_format($client_outstanding,2)?></strong>
</div>
<?php } ?>
		
		

	</div>
	<section class="panel panel-default">
		<div class="row m-l-none m-r-none bg-dark lter">
			<div class="col-sm-6 col-md-3 padder-v b-r b-light">
            <a class="clear" href="<?=base_url()?>projects">
				<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-danger"></i> <i class="fa fa-coffee fa-stack-1x text-white"></i>
				</span> 
					<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('projects',
					array('client'=>$this->user_profile->get_profile_details($user,'company')))?> </strong>
				</span> <small class="text-muted text-uc"><?=lang('projects')?> </small> </a>
			</div>
			<div class="col-sm-6 col-md-3 padder-v b-r b-light">
            <a class="clear" href="<?=base_url()?>clients/messages">
				<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-info"></i> <i class="fa fa-envelope fa-stack-1x text-white"></i>
				</span> 
					<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('messages',array('user_to'=>$user,'deleted'=>'No'))?> </strong>
				</span> <small class="text-muted text-uc"><?=lang('messages')?>  </small> </a>
			</div>


	<?php
$invoices = $this -> applib -> client_invoices($this->user_profile->get_profile_details($user,'company'));
$invoicestatus = 0;
foreach($invoices as $key => $invoicee){
if ($this-> applib ->payment_status($invoicee['inv_id']) != lang('fully_paid')){ $invoicestatus += 1;}
}
?>		
			<div class="col-sm-6 col-md-3 padder-v b-r b-light">
<a class="clear" href="<?=base_url()?>invoices">
<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-warning"></i> <i class="fa fa-suitcase fa-stack-1x text-white"></i>
</span>
<span class="h3 block m-t-xs"><strong><?=$invoicestatus?> </strong></span>
<small class="text-muted text-uc"><?=lang('invoices')?> </small> </a>
</div>


			<div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
            <a class="clear" href="<?=base_url()?>profile/activities">
				<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-success"></i> <i class="fa fa-calendar-o fa-stack-1x text-white"></i>
				</span> 
					<span class="h3 block m-t-xs"><strong><?=$this->user_profile->count_rows('activities',array('user'=>$user))?> </strong>
				</span> <small class="text-muted text-uc"><?=lang('activities')?>  </small> </a>
			</div>
		</div> </section>
		<div class="row">
			<div class="col-md-8">
				<section class="panel panel-default">
				<header class="panel-heading font-bold"> <?=lang('recent_projects')?></header>
				<div class="panel-body">
                <table class="table table-striped m-b-none text-sm">
						<thead>
							<tr>
								<th class="col-currency"><?=lang('project_cost')?></th>
								<th class="hidden-xs"><?=lang('progress')?></th>
								<th><?=lang('project_name')?> </th>
								<th class="col-options no-sort"><?=lang('options')?></th>
							</tr> </thead>
							<tbody>
								
								<?php
								if (!empty($projects)) {
								foreach ($projects as $key => $project) { ?>								
								<tr>
						<?php
							$project_cost = $this -> applib -> pro_calculate('project_cost',$project->project_id);
							if ($project->auto_progress == 'FALSE') {
								$progress = $project->progress;
							}else{
								$progress = round((($project->time_logged/3600)/$project->estimate_hours)*100,2);
							} 
						?>
							<td>
							<?=$cur->symbol?> 
							<?=number_format($project_cost,2,config_item('decimal_separator'),config_item('thousand_separator'))?>
							</td>
									<td class="hidden-xs">									
							<?php if ($progress >= 100) { $bg = 'success'; }else{ $bg = 'danger'; } ?>
										<div class="progress progress-xs progress-striped active">
											<div class="progress-bar progress-bar-<?=$bg?>" data-toggle="tooltip" data-original-title="<?=$progress?>%" style="width: <?=$progress?>%">
											</div>
										</div>
									</td>
									<td><?=$project->project_title?> </td>
									<td>
										<a class="btn  btn-success btn-xs" href="<?=base_url()?>projects/view/<?=$project->project_id?>">
										<i class="fa fa-suitcase text"></i> <?=lang('project')?></a>
									</td>
								</tr>
								<?php }
								}else{ ?>
								<tr>
									<td><?=lang('nothing_to_display')?></td><td></td><td></td>
								</tr>
								<?php } ?>
								
								
							</tbody>
						</table>

					</div> <footer class="panel-footer bg-white no-padder">
					<div class="row text-center no-gutter">
						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
							<?=$this->user_profile->count_rows('bugs',array('reporter'=>$user))?>
							</span> <small class="text-muted m-b block"><?=lang('reported_bugs')?></small>
						</div>

						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
	<?=$this->user_profile->count_rows('projects',array(
	                                   'client'=>$this->user_profile->get_profile_details($user,'company'),
									   'progress >='=>'100')
									   )?>
							</span> <small class="text-muted m-b block"><?=lang('complete_projects')?></small>
						</div>

						<div class="col-xs-3 b-r b-light">
							<span class="h4 font-bold m-t block">
							<?=$this->user_profile->count_rows('messages',array('user_to'=>$user,'status'=>'Unread'))?>
							</span> <small class="text-muted m-b block"><?=lang('unread_messages')?></small>
						</div>

						<?php 
						$ticketnumber = $this->user_profile->count_rows('tickets',array('reporter'=>$user, 'status'=>'open')) + $this->user_profile->count_rows('tickets',array('reporter'=>$user, 'status'=>'in progress'));
						?>

						<div class="col-xs-3">
							<span class="h4 font-bold m-t block">
							<?=$ticketnumber?>
							</span> <small class="text-muted m-b block"><?=lang('tickets')?></small>
						</div>



					</div> </footer>
				</section>
			</div>
			<div class="col-lg-4"> <section class="panel panel-default">
			<header class="panel-heading"><?=lang('payments')?> </header>
			<div class="panel-body text-center"> <h4><small> <?=lang('paid_amount')?> : </small>
				<?=$cur->symbol?>
				
				<?=number_format($this->user_profile->get_sum('payments','amount',array('paid_by'=>$user_company)),2,config_item('decimal_separator'),config_item('thousand_separator'))?> </h4>
				<small class="text-muted block">
				<?=lang('outstanding')?> : <?=$cur->symbol?> <?=number_format($client_outstanding,2,config_item('decimal_separator'),config_item('thousand_separator'))?>
				</small>
				<div class="inline">
					
				<div class="easypiechart" data-percent="<?=$perc_paid?>" data-line-width="16" data-loop="false" data-size="188">
						
						<span class="h2 step"><?=$perc_paid?></span>%
						<div class="easypie-text"><?=lang('paid')?>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer"><small><?=lang('invoice_amount')?>: <strong><?=$cur->symbol?>
			<?=number_format($client_payable,2,config_item('decimal_separator'),config_item('thousand_separator'))?></strong></small>
			</div> </section>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<!-- Start Charts -->
			<div class="row">
				<div class="col-lg-6">
					<section class="panel panel-default">
					<header class="panel-heading"><?=lang('my_projects')?></header>
					<div class="panel-body text-center">
						
						<h4><small></small><?=$total_projects?><small> <?=lang('projects')?></small></h4>
						<small class="text-muted block"><?=lang('complete_projects')?> - <strong><?=$perc_complete?>%</strong></small>
						<div class="sparkline inline" data-type="pie" data-height="150" data-slice-colors="['#99c7ce','#e1e1e1']">
						<?=$perc_complete?>,<?=$perc_open?></div>
						<div class="line pull-in"></div>
						<div class="text-xs">
							<i class="fa fa-circle text-info"></i> <?=lang('closed')?> - <?=$perc_complete?>%
							<i class="fa fa-circle text-muted"></i> <?=lang('open')?> - <?=$perc_open?>%
						</div>
					</div>
					<div class="panel-footer"><small><?=lang('projects_completion')?></small></div>
				</section>
			</div>
			<!-- Start Tickets -->
			<div class="col-lg-6">
				
				<section class="panel panel-default">
                    <header class="panel-heading">
                     <?=lang('recent_tickets')?>
                    </header>
                    <div class="panel-body">

                    <div class="list-group bg-white">
                 <?php
                 $tickets = $this -> db -> where('reporter',$user) -> order_by('created','desc') -> get('tickets',7) -> result();
					if (!empty($tickets)) {
					foreach ($tickets as $key => $ticket) {
						if($ticket->status == 'open'){ $badge = 'danger'; }elseif($ticket->status == 'closed'){ $badge = 'success'; }else{ $badge = 'dark'; } 
					 ?>
                    <a href="<?=base_url()?>tickets/view/<?=$ticket->id?>" data-original-title="<?=$ticket->subject?>" data-toggle="tooltip" data-placement="top" title = "" class="list-group-item">
                    <?=$ticket->ticket_code?> - <small class="text-muted"><?=lang('priority')?>: <?=$ticket->priority?> <span class="badge bg-<?=$badge?> pull-right"><?=$ticket->status?></span></small>
                    </a>
                    <?php  } } ?>
                  </div>

                    </div>
                    
                  </section>
			</div>
			<!-- End Tickets -->
		</div>

	</div>
	<div class="col-md-4"> <section class="panel panel-default b-light">
		<div class="panel-body">
			<section class="comment-list block">
				<?php
									if (!empty($activities)) {
				foreach ($activities as $key => $activity) { ?>
				<article id="comment-id-1" class="comment-item">
					<span class="fa-stack pull-left m-l-xs">
					<a class="pull-left thumb-sm">

							
	<?php 
		if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$activity->user),'use_gravatar') == 'Y'){
										$user_email = $this->user_profile->get_user_details($activity->user,'email'); ?>
										<img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
										<?php }else{ ?>
										<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($activity->user,'avatar')?>" class="img-circle">
										<?php } ?>
					</a>
					</span> <section class="comment-body m-b-lg">
						<header> <a href="#"><strong><?=$this->user_profile->get_profile_details($activity->user,'fullname')?$this->user_profile->get_profile_details($activity->user,'fullname'):$this->user_profile->get_user_details($activity->user,'username')?></strong></a>
						<span class="text-muted text-xs"> <?php
								$today = time();
								$activity_day = strtotime($activity->activity_date) ;
								echo $this->user_profile->get_time_diff($today,$activity_day);
					?> <?=lang('ago')?></span> </header>
					<div>
                        <?php
                            if (lang($activity->activity) != '') {
                                if (!empty($activity->value1)) {
                                    if (!empty($activity->value2)){
                                        echo sprintf(lang($activity->activity), '<em>'.$activity->value1.'</em>', '<em>'.$activity->value2.'</em>');
                                        } else {
                                        echo sprintf(lang($activity->activity), '<em>'.$activity->value1.'</em>');
                                        }
                                    } else { echo lang($activity->activity); }
                                } else { echo $activity->activity; } 
                            ?>
                     </div>
				</section>
			</article>
			<?php }} ?>
			
		</section>
	</div>
	
</section>
</div>
</div>
</section>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>