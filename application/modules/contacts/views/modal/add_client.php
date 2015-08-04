<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_contact')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'auth/register_user',$attributes); ?>
          
		
		<div class="modal-body">
			 <input type="hidden" name="r_url" value="<?=base_url()?>companies/view/details/<?=$company?>">
			 <input type="hidden" name="company" value="<?=$company?>">
			 <input type="hidden" name="role" value="2">

			 <div class="alert alert-info" id='validation_check'></div> 

			 <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=set_value('fullname')?>" placeholder="E.g John Doe" name="fullname" required>
				</div>
				</div>
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('username')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">

				<div class="input-group">
                          <input class="form-control" id='username' type="text" value="<?=set_value('username')?>" placeholder="johndoe" name="username" required>
                          <span class="input-group-btn">
                            <button class="btn btn-default" id='check_username' type="button">Check</button>
                          </span>
                        </div>
					
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('email')?></label>
				<div class="col-lg-8">

				<div class="input-group">
                          <input class="form-control" id='email' type="email" value="<?=set_value('email')?>" placeholder="me@domin.com" name="email" required>
                          <span class="input-group-btn">
                            <button class="btn btn-default" id='check_email' type="button">Check</button>
                          </span>
                        </div>

				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('password')?> </label>
				<div class="col-lg-8">
					<input type="password" class="form-control" value="<?=set_value('password')?>" name="password">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('confirm_password')?> </label>
				<div class="col-lg-8">
					<input type="password" class="form-control" value="<?=set_value('confirm_password')?>" name="confirm_password">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('phone')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=set_value('phone')?>" name="phone" placeholder="+52 782 983 434">
				</div>
		
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('add_contact')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
    $(document).ready(function() {  
    	jQuery('#validation_check').addClass('hide');      
            //the min chars for username  
            var min_chars = 4;      
            //result texts  
            var characters_error = 'Minimum amount of chars is <?=config_item('username_min_length')?>';  
            var checking_html = 'Checking...';  
            $('#check_username').click(function(){ 
            jQuery('#validation_check').addClass('show'); 
                if($('#username').val().length < min_chars){    
                    $('#validation_check').html(characters_error);  
                }else{  
                    $('#validation_check').html(checking_html);  
                    check_username_availability();  
                }  
            });  
            $('#check_email').click(function(){ 
            jQuery('#validation_check').addClass('show'); 
                    $('#validation_check').html(checking_html);  
                    check_email_availability();  
            }); 
      
      });  
      
    //function to check username availability  
    function check_username_availability(){ 
            var username = $('#username').val(); 
            $.post("<?=base_url()?>contacts/username_check", { username: username },  
                function(result){   
                    if(result == 1){   
                        $('#validation_check').html('<i class="fa fa-check"></i> ' + username + ' is available');  
                    }else{  
                        $('#validation_check').html('<i class="fa fa-warning"></i> ' + username + ' is not available');  
                    }  
            });  
      
    }  
    function check_email_availability(){ 
            var email = $('#email').val(); 
            $.post("<?=base_url()?>contacts/email_check", { email: email },  
                function(result){   
                    if(result == 1){   
                        $('#validation_check').html('<i class="fa fa-check"></i> ' + email + ' doesn\'t exist');  
                    }else{  
                        $('#validation_check').html('<i class="fa fa-warning"></i> ' + email + ' already exists');  
                    }  
            });  
      
    }  
    </script>