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
	'class'	=> 'form-control input-lg',
	'placeholder' => lang('username'),
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or Username';
} else if ($login_by_username) {
	$login_label = 'Username';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'placeholder' => lang('password'),
	'id'	=> 'inputPassword',
	'size'	=> 30,
	'class' => 'form-control input-lg'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>

<div class="content">
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">

		<div class="container aside-xxl">
		<a class="navbar-brand block" href="">
		<?php if (config_item('logo_or_icon') == 'logo_title') { ?>
			<img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>" class="m-r-sm">
			<?php } elseif ($this->config->item('logo_or_icon') == 'icon') { ?>
			<i class="fa <?=$this->config->item('site_icon')?>"></i>
			<?php } ?> <?=config_item('company_name')?>
		</a>

		 <section class="panel panel-default bg-white m-t-lg">
		<header class="panel-heading text-center"> <strong><?=lang('project_management_system')?></strong> 
			<?php  echo modules::run('sidebar/flash_msg');?>  
		</header>

		<?php 
		$attributes = array('class' => 'panel-body wrapper-lg');
		echo form_open('login',$attributes); ?>
			<div class="form-group">
				<label class="control-label"><?=lang('email_user')?></label>
				<?php echo form_input($login); ?>
				<span style="color: red;">
				<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=lang('password')?></label>
				<?php echo form_password($password); ?>
				<span style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
			</div>


	<?php if ($show_captcha) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()"><?=lang('get_another_captcha')?></a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><?=lang('get_an_audio_captcha')?></a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><?=lang('get_an_image_captcha')?></a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image"><?=lang('enter_the_words_above')?></div>
			<div class="recaptcha_only_if_audio"><?=lang('enter_the_numbers_you_hear')?></div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p><?=lang('enter_the_code_exactly')?></p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>

	<div class="checkbox">
				<label>
					<?php echo form_checkbox($remember); ?> <?=lang('this_is_my_computer')?>
				</label>
			</div>
 <a href="<?=base_url()?>auth/forgot_password" class="pull-right m-t-xs"><small><?=lang('forgot_password')?></small></a> 
			<button type="submit" class="btn btn-primary"><?=lang('sign_in')?></button>
			<div class="line line-dashed">
			</div> 
			<?php if (config_item('allow_client_registration') == 'TRUE'){ ?>
			<p class="text-muted text-center"><small><?=lang('do_not_have_an_account')?></small></p> 
			<a href="<?=base_url()?>auth/register/" class="btn btn-success btn-block"><?=lang('get_your_account')?></a>
			<?php } ?>
<?php echo form_close(); ?>

 </section>



	<!-- footer --> 

	<footer id="footer">
	<div class="text-center text-white padder">
		<p> <small><?=lang('powered_by')?>  <a href="http://codecanyon.net/item/freelancer-office/8870728">Freelancer Office</a> v<?=config_item('version')?> 
		<br>&copy; <?=date('Y')?> <a href="<?=config_item('company_domain')?>" target="_blank"><?=config_item('company_name')?></a> </small> </p>
	</div> 
	</footer>
	<!-- / footer -->

	</div> 


	</section>
</div>