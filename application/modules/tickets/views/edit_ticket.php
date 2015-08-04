
<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			<header class="dk header b-b">
		<a href="<?=base_url()?>tickets/add" data-original-title="<?=lang('create_ticket')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-<?=config_item('button_color')?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
		<p class="h4"><?=lang('all_tickets')?></p>
		</header>

			<section class="vbox">
			 <section class="scrollable w-f">
			   <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

			<?=$this->load->view('sidebar/tickets',$tickets)?>

			</div></section>
			</section>
			</aside> 
			
			<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							
							<?php
			if (!empty($ticket_details)) {
			foreach ($ticket_details as $key => $i) { ?>
						<div class="btn-group">
						<a href="<?=base_url()?>tickets/view/<?=$i->id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="top" class="btn btn-<?=config_item('button_color')?> btn-sm"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?></a>
						</div>
						
						</div>
						<div class="col-sm-4 m-b-xs">
						
						</div>
					</div> </header>
					<section class="scrollable wrapper">

					 <!-- Start create ticket -->
<div class="col-sm-12">
	<section class="panel panel-default">
	
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?> - <?=$i->ticket_code?></header>
	<div class="panel-body">
	
<!-- Start ticket form -->
<?php echo $this->session->flashdata('form_error'); ?>

	<?php 
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'tickets/edit/',$attributes);
           ?>
			 
			 <input type="hidden" name="id" value="<?=$i->id?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$i->ticket_code?>" name="ticket_code">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-7">
					<input type="text" class="form-control" value="<?=$i->subject?>" name="subject">
				</div>
				</div>

				

			

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="priority" class="form-control" >
					<?php 
					$priorities = $this -> db -> get('priorities') -> result();
					if (!empty($priorities)) {
						foreach ($priorities as $p): ?>
					<option value="<?=$p->priority?>"><?=lang(strtolower($p->priority))?></option>
					<?php endforeach; } ?>
					</select> 
					</div> 
				</div>
			</div>

			 <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('department')?> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="department" class="form-control" >
					<?php 
					$departments = $this -> db -> where(array('deptid >'=>'0')) -> get('departments') -> result();
					if (!empty($departments)) {
						foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"<?=($i->department == $d->deptid ? ' selected="selected"' : '')?>><?=strtoupper($d->deptname)?></option>
					<?php endforeach; } ?>
					</select> 
					</div> 
				</div>
			</div>


			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select class="select2-option" style="width:260px" name="reporter" >
					<optgroup label="<?=lang('clients')?>"> 
					<?php 
					if (!empty($clients)) {
						foreach ($clients as $client): ?>
					<option value="<?=$client->id?>"<?=($i->reporter == $client->id ? ' selected="selected"' : '')?>><?=Applib::profile_info($client->id)->fullname?></option>
					<?php endforeach; } ?>
					</optgroup> 
					</select> 
					</div> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> </label>
				<div class="col-lg-8">
				<textarea name="body" class="form-control"><?=$i->body?></textarea>
				
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('attachment')?></label>
				<div class="col-lg-6">
					<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
				</div>
			</div>
	<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-ticket"></i> <?=lang('edit_ticket')?></button>


				
		</form>



		<!-- End ticket -->
		<?php } } ?>
</div>
</section>
</div>


<!-- End create invoice -->



					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->






