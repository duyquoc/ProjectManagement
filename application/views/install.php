<!DOCTYPE html>
<html lang="en" class="bg-dark">
  <head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="<?=base_url()?>resource/images/favicon.ico">
    <link rel="icon" type="image/png" href="<?=base_url()?>resource/images/favicon.png">

      <title>Freelancer Office Installer</title>
    <meta name="description" content="Freelancer Office project management system available on codecanyon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="<?=base_url()?>resource/css/app.v2.css" type="text/css" />
    <link rel="stylesheet" href="<?=base_url()?>resource/css/font.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="<?=base_url()?>resource/js/fuelux/fuelux.css" type="text/css" />
    <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js" cache="false">
    </script>
    <script src="js/ie/respond.min.js" cache="false">
    </script>
    <script src="js/ie/excanvas.js" cache="false">
    </script> <![endif]-->
  </head>
  <body> 
  
  <!--main content start-->
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">

    <div class="container" style="width:80%">
     <section class="panel panel-default bg-white m-t-lg">
    <header class="panel-heading text-center"> 
      <strong>Thank You for Purchasing Freelancer Office</strong>
    </header>

   <div class = "panel-body wrapper-lg">

   <?php
   $step1 = $step2 = $step3 = $step4 = '';
   $badge1 = $badge2 = $badge3 = $badge4 ='badge';
   if(isset($_GET['step'])){
   switch ($_GET['step']) {
     case '2':
      $step2 = 'active'; $badge2='badge badge-success';
       break;
     case '3':
      $step3 = 'active'; $badge3='badge badge-success';
       break;
     case '4':
      $step4 = 'active'; $badge4='badge badge-success';
       break;
   
     default:
       $step1 = 'active'; $badge1='badge badge-success';
       break;
   }
 }else $step1 = 'active'; $badge1='badge';
   ?>


     <div class="panel panel-default wizard">
                <div class="wizard-steps clearfix" id="form-wizard">
                  <ul class="steps">
                    <li class="<?=$step1?>"><span class="<?=$badge1?>">1</span>System Check</li>
                    <li class="<?=$step2?>"><span class="<?=$badge2?>">2</span>Database Settings</li>
                    <li class="<?=$step3?>"><span class="<?=$badge3?>">3</span>Envato Account</li>
                    <li class="<?=$step4?>"><span class="<?=$badge4?>">4</span>Basic Settings</li>
                  </ul>
                </div>
                <div class="step-content clearfix">

                <?php
                if($this->session->flashdata('message')){ ?>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=$this->session->flashdata('message')?>
                </div>
                <?php } ?>
                  
                    <div class="step-pane <?=$step1?>" id="step1">
                  

                      <?php 
$config_file = "./application/config/config.php";
$database_file = "./application/config/database.php";
$autoload_file = "./application/config/autoload.php";
$route_file = "./application/config/routes.php";
$htaccess_file = ".htaccess";
      $error = FALSE;
      if(phpversion() < "5.3"){ $error = TRUE; 
        echo "<div class='alert alert-danger'>Your PHP version is ".phpversion()."! PHP 5.3 or higher required!</div>"; }else{ 
        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> You are running PHP ".phpversion()."</div>";
      } 
      if(!extension_loaded('mysql')){$error = TRUE; echo "<div class='alert alert-danger'>Mysql PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mysql PHP extension loaded!</div>";}

      if(!extension_loaded('mysqli')){$error = TRUE; echo "<div class='alert alert-danger'>Mysqli PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mysqli PHP extension loaded!</div>";}

      if(!extension_loaded('mcrypt')){$error = TRUE; echo "<div class='alert alert-danger'>Mcrypt PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mcrypt PHP extension loaded!</div>";}
      
      if(!extension_loaded('mbstring')){$error = TRUE; echo "<div class='alert alert-danger'>MBString PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> MBString PHP extension loaded!</div>";}

      if(!extension_loaded('gd')){echo "<div class='alert alert-danger'>GD PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> GD PHP extension loaded!</div>";}
      if(!extension_loaded('pdo')){$error = TRUE; echo "<div class='alert alert-danger'>PDO PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> PDO PHP extension loaded!</div>";}

      if(!extension_loaded('dom')){echo "<div class='alert alert-danger'>DOM PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> DOM PHP extension loaded!</div>";}
      if(!extension_loaded('curl')){$error = TRUE; echo "<div class='alert alert-danger'>CURL PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> CURL PHP extension loaded!</div>";}

      if(!is_writeable($database_file)){$error = TRUE; echo "<div class='alert alert-danger'>Database File (application/config/database.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Database file is writeable!</div>";}
      if(!is_writeable($config_file)){$error = TRUE; echo "<div class='alert alert-danger'>Config File (application/config/config.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Config file is writeable!</div>";}
      if(!is_writeable($route_file)){$error = TRUE; echo "<div class='alert alert-danger'>Route File (application/config/routes.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Routes file is writeable!</div>";}
      if(!is_writeable($autoload_file)){$error = TRUE; echo "<div class='alert alert-danger'>Autoload File (application/config/autoload.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Autoload file is writeable!</div>";}
      if(!is_writeable($htaccess_file)){$error = TRUE; echo "<div class='alert alert-danger'>HTACCESS File (.htaccess) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> HTACCESS file is writeable!</div>";}

      if(!is_writeable("./resource/tmp")){echo "<div class='alert alert-danger'><i class='fa fa-times'></i> /resource/tmp folder is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> /resource/tmp folder is writeable!</div>";}

      if(ini_get('allow_url_fopen') != "1"){echo "<div class='alert alert-warning'><i class='fa fa-warning'></i> Allow_url_fopen is not enabled!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Allow_url_fopen is enabled!</div>";}
?>   

<div class="actions pull-left">
                    <a href="<?php echo base_url()?>index.php/installer/start" class="btn btn-success btn-sm">Next</a>
                  </div>
  
                    </div>

                    <div class="step-pane <?=$step2?>" id="step2">

                    <form class="m-b-sm form-horizontal" id='database' method="post" action="<?=base_url()?>index.php/installer/db_setup" novalidate="novalidate">

                    <div class="form-group">
                          <label class="col-lg-3 control-label">Database Host</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control"  placeholder="localhost" name="set_hostname">
                          </div>
                        </div>
                      <div class="form-group">
                          <label class="col-lg-3 control-label">Database Name</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control"  placeholder="db_freelancer" name="set_database">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-lg-3 control-label">Database Username</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="db_freelancer_user" name="set_db_user">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-lg-3 control-label">Database Password</label>
                          <div class="col-lg-7">
                            <input type="password" class="form-control" placeholder="db_freelancer_@@" name="set_db_pass">
                          </div>
                        </div>

                        


                    

                    <div class="actions pull-left">
                    <a href="<?php echo base_url()?>index.php/installer" class="btn btn-success btn-sm">Previous</a>
                    <button type="submit" class="btn btn-success btn-sm">Next</button>
                  </div> 

                  </form>
                  </div>


            

                    <div class="step-pane <?=$step3?>" id="step3">

                    <form class="m-b-sm form-horizontal" id="verify" method="post" action="<?=base_url()?>index.php/installer/verify" novalidate="novalidate">
                       <div class="form-group">
                          <label class="col-lg-3 control-label">Envato Username</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="gitbench" name="set_envato_user">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-lg-3 control-label">Purchase Code</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" name="set_envato_license">
                            <span class="help-block m-b-none">Your purchase code from Envato <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-" target="_blank">Read More</a></span>
                          </div>
                        </div>

                         <div class="actions pull-left">
                    <button type="submit" class="btn btn-success btn-sm">Next</button>
                  </div> 

                  </form>

                    </div>

                   

        
                     <div class="step-pane <?=$step4?>" id="step4">

                     <form class="m-b-sm form-horizontal" id="complete" method="post" action="<?=base_url()?>index.php/installer/complete" novalidate="novalidate">

                     <?php
                        $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
                        $base_url .= "://".$_SERVER['HTTP_HOST'];
                        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']); 

                        ?>

                        <div class="form-group">
                          <label class="col-lg-3 control-label">Company Domain</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?=$base_url?>" name="set_base_url">
                          </div>
                      </div>

                     <div class="form-group">
                          <label class="col-lg-3 control-label">Full Name</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="John Doe" name="set_admin_fullname">
                          </div>
                        </div>

                     <div class="form-group">
                          <label class="col-lg-3 control-label">Admin Username</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="johndoe" name="set_admin_username">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-lg-3 control-label">Admin Password</label>
                          <div class="col-lg-7">
                            <input type="password" class="form-control" placeholder="password" name="set_admin_pass">
                          </div>
                        </div>


                        <div class="form-group">
                          <label class="col-lg-3 control-label">Admin Email</label>
                          <div class="col-lg-7">
                            <input type="email" class="form-control" placeholder="admin@gitbench.com" name="set_admin_email">
                          </div>
                        </div>

                     <div class="form-group">
                          <label class="col-lg-3 control-label">Company Name</label>
                          <div class="col-lg-7">
                            <input type="text" class="form-control" placeholder="gitbench" name="set_company_name">
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-lg-3 control-label">Company Email</label>
                          <div class="col-lg-7">
                            <input type="email" class="form-control" placeholder="info@gitbench.com" name="set_company_email">
                          </div>
                      </div>


                      <div class="actions pull-left">
                    <button type="submit" class="btn btn-success btn-sm">Complete</button>
                  </div> 

                  </form>  

                    </div>    

                               
                 
                  

                </div>
        </div>

        </div>
        </section>
        </div>
        </section>
      <!--main content end-->
<script src="<?=base_url()?>resource/js/jquery-2.1.1.min.js"></script>
  <script src="<?=base_url()?>resource/js/app.v2.js"></script>
  <script src="<?=base_url()?>resource/js/jquery.validate.min.js"></script>

   <script>
  $(function() {
    $("#database").validate({
        rules: {
            set_hostname: "required",
            set_database: "required",
            set_db_user: "required",
            set_db_pass: "required"
        },
        
        // Specify the validation error messages
        messages: {
            set_hostname: "Please enter your hostname usually localhost",
            set_database: "Please specify your database name",
            set_db_user: "Please specify your database username",
            set_db_pass: "Please specify your database user password"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

     $("#verify").validate({
        rules: {
            set_envato_user: "required",
            set_envato_license: "required",
        },
        
        // Specify the validation error messages
        messages: {
            set_envato_user: "We need your envato username to verify purchase",
            set_envato_license: "Enter your envato purchase code here"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

     $("#complete").validate({
        rules: {
            set_admin_username: "required",
            set_admin_fullname: "required",
            set_admin_pass: "required",
            set_admin_email: {
                required: true,
                email: true
            },
            set_company_name: "required",
            set_company_email: {
                required: true,
                email: true
            },
        },
        
        // Specify the validation error messages
        messages: {
            set_admin_username: "Please enter admin username",
            set_admin_fullname: "Set your admin full name",
            set_admin_pass: "Set your admin password",
            set_admin_email: "Set admin email address",
            set_company_name: "Set your company name",
            set_company_email: "Enter your company email address e.g info@domain.com",
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

  

  
  <!-- Bootstrap -->
  <!-- App -->
</body>
</html>