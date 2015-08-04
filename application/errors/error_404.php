<!DOCTYPE html>
<html lang="en" class="app">
<head>
  <meta charset="utf-8" />
  <title>Not found - 404 Error</title>
  <meta name="description" content="Powered by Freelancer Office available on codecanyon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="./resource/css/app.v2.css" type="text/css" />
  <link rel="stylesheet" href="./resource/css/font.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body class="">
    <section id="content">
    <div class="row m-n">
      <div class="col-sm-4 col-sm-offset-4">
        <div class="text-center m-b-lg">
          <h1 class="h text-white animated fadeInDownBig">404</h1>
        </div>
        <div class="list-group m-b-sm bg-white m-b-lg">
          <a href="javascript:history.back();" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <i class="fa fa-fw fa-home icon-muted"></i> Back to Homepage
          </a>
          <a href="#" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <span class="badge bg-success"><?=config_item('company_phone')?></span>
            <i class="fa fa-fw fa-phone icon-muted"></i> Call us
          </a>
          <a href="#" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <span class="badge bg-primary"><?=config_item('company_domain')?></span>
            <i class="fa fa-fw fa-phone icon-muted"></i> Main Website
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>Powered by <a href="http://codecanyon.net/item/freelancer-office/8870728">Freelancer Office</a> v<?=config_item('version')?> &copy; <?=date('Y')?></small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
  <script src="<?=base_url()?>resource/js/jquery-2.1.1.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?=base_url()?>resource/js/app.v2.js"></script>
  <!-- App -->
</body>
</html>