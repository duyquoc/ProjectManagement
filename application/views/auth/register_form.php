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
/*
**********************************************************************************
* Copyright: gitbench 2014
* Licence: Please check CodeCanyon.net for licence details. 
* More licence clarification available here: htttp://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
* CodeCanyon User: http://codecanyon.net/user/gitbench
* CodeCanyon Project: http://codecanyon.net/item/freelancer-office/8870728
* Package Date: 2014-09-24 09:33:11 
***********************************************************************************
*/

if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'class'	=> 'form-control input-lg',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'class'	=> 'form-control input-lg',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$fullname = array(
	'name'	=> 'fullname',
	'class'	=> 'form-control input-lg',
	'value'	=> set_value('fullname'),
);
$password = array(
	'name'	=> 'password',
	'class'	=> 'form-control input-lg',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'class'	=> 'form-control input-lg',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'class'	=> 'form-control input-lg',
	'maxlength'	=> 8,
);
?>


<div class="content">
<section id="content" class="m-t-lg wrapper-md animated fadeInDown">
		<div class="container aside-xxl">

		<a class="navbar-brand block" href="">

			<?php if ($this->config->item('logo_or_icon') == 'logo') { ?>
				<img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>" class="m-r-sm">
			<?php } elseif ($this->config->item('logo_or_icon') == 'icon') { ?>
				<i class="fa <?=$this->config->item('site_icon')?>"></i>
			<?php } ?>
			<?=$this->config->item('company_name')?>
		</a>
 
		 <section class="panel panel-default m-t-lg bg-white">
		<header class="panel-heading text-center"> 
		<strong><?=lang('sign_up_form')?> <?=$this->config->item('company_name')?></strong> </header>
		<?php 
		$attributes = array('class' => 'panel-body wrapper-lg');
		echo form_open($this->uri->uri_string(),$attributes); ?>
			<?php if ($use_username) { ?>
			<div class="form-group">
				<label class="control-label"><?=lang('username')?></label>
				<?php echo form_input($username); ?>
				<span style="color: red;"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></span>
			</div>
			<?php } ?>
			<div class="form-group">
				<label class="control-label"><?=lang('email')?></label>
				<?php echo form_input($email); ?>
				<span style="color: red;">
				<?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=lang('password')?> </label>
				<?php echo form_password($password); ?>
				<span style="color: red;"><?php echo form_error($password['name']); ?></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=lang('confirm_password')?> </label>
				<?php echo form_password($confirm_password); ?>
				<span style="color: red;"><?php echo form_error($confirm_password['name']); ?></span>
			</div>
			<div class="form-group">
				<label class="control-label"><?=lang('full_name')?></label>
				<?php echo form_input($fullname); ?>
				<span style="color: red;"><?php echo form_error($fullname['name']); ?><?php echo isset($errors[$fullname['name']])?$errors[$fullname['name']]:''; ?></span>
			</div>
			<table>

	<?php if ($captcha_registration == 'TRUE') {
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
		<td><?php echo form_input($captcha); ?></td>
		<span style="color: red;"><?php echo form_error($captcha['name']); ?></span>
	</tr>
	<?php }
	} ?>
</table>
			<div class="line line-dashed"></div> 
			 <button type="submit" class="btn btn-primary"><?=lang('sign_up')?></button>
			<div class="line line-dashed">
			</div>
			<p class="text-muted text-center"><small><?=lang('already_have_an_account')?></small></p> 
			<a href="<?=base_url()?>auth/login" class="btn btn-danger btn-block"><?=lang('sign_in')?></a>
		
<?php echo form_close(); ?>
</section>
	</div> </section>


	</div>