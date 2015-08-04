<!DOCTYPE html>
<html lang="<?=lang('lang_code')?>" class="bg-dark">
	<head>
		<meta charset="utf-8" />
		<link rel="shortcut icon" href="<?=base_url()?>resource/images/favicon.ico">
		<link rel="icon" type="image/png" href="<?=base_url()?>resource/images/favicon.png">

    	<title><?php  echo $template['title'];?></title>
		<meta name="description" content="<?=$this->config->item('site_desc')?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="stylesheet" href="<?=base_url()?>resource/css/app.v2.css" type="text/css" />
		<link rel="stylesheet" href="<?=base_url()?>resource/css/font.css" type="text/css" cache="false" />
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
      <?php  echo $template['body'];?>
      <!--main content end-->

	<script src="<?=base_url()?>resource/js/app.v2.js"></script>
	<!-- Bootstrap -->
	<!-- App -->
</body>
</html>