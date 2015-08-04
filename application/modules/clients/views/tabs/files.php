		<header class="header b-b b-light hidden-print">
<a href="<?=base_url()?>clients/files/add/<?=$this->uri->segment(4)*1200?>" data-toggle="ajaxModal" title="<?=lang('upload_file')?>" class="btn btn-sm btn-primary pull-right"><?=lang('upload_file')?></a> 
 </header>



<ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border"> 
			<?php
								if (!empty($project_files)) {
				foreach ($project_files as $key => $f) { ?>
		<li class="list-group-item"> 


		<a href="#" class="thumb-sm pull-left m-r-sm">
		<img src="<?=base_url()?>resource/avatar/<?=$this->user_profile->get_profile_details($f->uploaded_by,'avatar')?>" class="img-circle"> </a>
			<a href="#" class="clear"> <small class="pull-right"><?php
								$today = time();
								$activity_day = strtotime($f->date_posted) ;
								echo $this->user_profile->get_time_diff($today,$activity_day);
							?> <?=lang('ago')?></small> <strong class="block"><?=ucfirst($this->user_profile->get_user_details($f->uploaded_by,'username'))?></strong> 
							<small>
							<a href="<?=base_url()?>clients/files/download/<?=$f->file_id*1800?>/<?=$f->project*1200?>"><?=$f->file_name?></a></small>
							 </a>

<div><?=$f->description?>
</div> 
<div class="comment-action m-t-sm">
<a href="<?=base_url()?>clients/files/download/<?=$f->file_id*1800?>/<?=$f->project*1200?>" data-toggle="tooltip" data-original-title="<?=lang('download_file')?>"  title="<?=lang('download_file')?>" class="btn btn-dark btn-xs active">
<i class="fa fa-download text-white"></i> <?=lang('download')?></a>
<?php
if ($f->uploaded_by == $this->tank_auth->get_user_id()) { ?>
<a href="<?=base_url()?>clients/files/delete/<?=$f->file_id*1800?>/<?=$f->project*1200?>" data-toggle="ajaxModal" class="btn btn-danger btn-xs active">
<i class="fa fa-trash-o text-white"></i> </a>
<?php } ?>

</div>

 						
		</li> 
		
				<?php } } else{		echo lang('nothing_to_display');	} ?>
	 
		</ul> 