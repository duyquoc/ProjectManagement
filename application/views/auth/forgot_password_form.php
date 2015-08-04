<style type="text/css">
@import url(//fonts.googleapis.com/css?family=Dosis:300|Lato:300,400,600,700|Roboto+Condensed:300,700|Open+Sans+Condensed:300,600|Open+Sans:400,300,600,700|Maven+Pro:400,700);
@import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");


.content:before {
  content: "";
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  z-index: -1;
  display: block;
  background-color: black;
  background-image: url("<?=base_url()?>resource/images/<?=config_item('login_bg')?>");
  width: 100%;
  height: 100%;
  background-size: cover;
  -webkit-filter: blur(2px);
  -moz-filter: blur(1px);
  -o-filter: blur(1px);
  -ms-filter: blur(1px);
  filter: blur(1px);
}

.content {
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(10, 10, 10, 0.5);
  margin: auto auto;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  -moz-box-shadow: 0 0 10px black;
  -webkit-box-shadow: 0 0 10px black;
  box-shadow: 0 0 10px black;
}
.panel{
	margin-bottom: 10px;
}
</style>



<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'class'	=> 'form-control input-lg',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>

<?php  echo modules::run('sidebar/flash_msg');?>  


<div class="content">
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">

		<div class="container aside-xxl">
<a class="navbar-brand block" href="">
		<img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>"> <?=config_item('company_name')?>
		</a>


		 
		 <section class="panel panel-default bg-white m-t-lg">
		<header class="panel-heading text-center"> <strong><?=lang('forgot_password')?> <?=$this->config->item('company_name')?></strong> </header>

		<?php 
		$attributes = array('class' => 'panel-body wrapper-lg');
		echo form_open($this->uri->uri_string(),$attributes); ?>
			<div class="form-group">
				<label class="control-label"><?=lang('email')?>/<?=lang('username')?></label>
				<?php echo form_input($login); ?>
				<span style="color: red;">
				<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
			</div>

 <a href="<?=base_url()?>auth/login" class="pull-right m-t-xs"><small><?=lang('remember_password')?>?</small></a> 
			<button type="submit" class="btn btn-danger"><?=lang('get_new_password')?></button>
			<div class="line line-dashed">
			</div> 
			<?php if ($this->config->item('allow_registration', 'tank_auth')){ ?>
			<p class="text-muted text-center"><small><?=lang('do_not_have_account')?></small></p> 
			<?php } ?>
			<a href="<?=base_url()?>auth/register/" class="btn btn-success btn-block"><?=lang('get_your_account')?></a>
<?php echo form_close(); ?>

 </section>
	</div> 
	</section>


	</div>
