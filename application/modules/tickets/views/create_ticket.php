
<!-- Start -->


<section id="content">
	<section class="hbox stretch">
	
		<aside class="aside-md bg-white b-r" id="subNav">

			
			<header class="dk header b-b">
		<a href="<?=base_url()?>tickets/add" data-original-title="<?=lang('new_ticket')?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-<?=config_item('button_color')?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
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
							
						<div class="btn-group">
						
						</div>
						
						</div>
						<div class="col-sm-4 m-b-xs">
						
						</div>
					</div> </header>
					<section class="scrollable wrapper">

					 <!-- Start create invoice -->
<div class="col-sm-12">
	<section class="panel panel-default">
	<header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?></header>
	<div class="panel-body">
	<?php echo $this->session->flashdata('form_error'); ?>

	<?php if(isset($_GET['dept'])){ 
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'tickets/add/?dept='.$_GET['dept'],$attributes);
           ?>
			 
			 <input type="hidden" name="department" value="<?=$_GET['dept']?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" style="width:260px" value="<?php  
						$this->load->helper('string');
						echo strtoupper(random_string('alnum', 7)); ?>" name="ticket_code">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-7">
					<input type="text" class="form-control" style="width:260px" placeholder="Sample Ticket Subject" name="subject" required>
				</div>
				</div>
				<?php if ($role == '1') { ?>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select class="select2-option" style="width:260px" name="reporter" >
					<optgroup label="<?=lang('clients')?>"> 
					<?php 
					if (!empty($clients)) {
						foreach ($clients as $client): $cl = $this -> db -> where(array("user_id"=> $client->id)) -> get(Applib::$profile_table) -> result_array(); ?>
					<option value="<?=$client->id?>"><?=$cl[0]["fullname"] ?></option>
					<?php endforeach; } ?>
					</optgroup> 
					</select> 
					</div> 
				</div>
			</div>
			<?php } ?>

			

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="priority" style="width:260px" class="form-control">
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
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> </label>
				<div class="col-lg-8">
				<textarea name="body" class="form-control" placeholder="<?=lang('message')?>"><?php echo set_value('body');?></textarea>
				
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('attachment')?></label>
				<div class="col-lg-6">
					<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
				</div>
			</div>

			
			<?php
			$dept = isset($_GET['dept']) ? $_GET['dept'] : 0;
		$additional = $this -> db -> where(array('deptid'=> $dept)) -> get("fields") -> result_array();
if (is_array($additional) && !empty($additional))
{
	

	foreach ($additional as $item)
	{
		echo '<div class="form-group">';
		echo ' <label class="col-lg-3 control-label"> ' . $item['name'] . '</label>';
		echo ' <div class="col-lg-3">';
		if ($item['type'] == 'text')
		{
			echo ' <input type="text" class="form-control" name="' . $item['uniqid'] . '">  ';
		}
		echo ' </div>';
		echo ' </div>';
	}
	
	
}
?>	<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-ticket"></i> <?=lang('create_ticket')?></button>


				
		</form>

		<?php }else{ 
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'tickets/add',$attributes); ?>

          <div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="dept" class="form-control" required>
					<?php 
					$departments = Applib::retrieve(Applib::$departments_table,array('deptid >'=>'0'));
					if (!empty($departments)) {
						foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"><?=strtoupper($d->deptname)?></option>
					<?php endforeach; } ?>
					</select> 
					</div> 
				</div>
			</div>
<button type="submit" class="btn btn-sm btn-success"><?=lang('select_department')?></button>

</form>
		<?php } ?>
</div>
</section>
</div>


<!-- End create invoice -->



					</section>  




		</section> </aside> </section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>



<!-- end -->






