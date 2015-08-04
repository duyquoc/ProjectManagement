<section id="content"> <section class="vbox"> 

<header class="header bg-white b-b b-light"> 

<p>Freelancer Office v.<?=$this->config->item('version')?> <?=$this->config->item('company_name')?> </p> </header> <section> <section class="hbox stretch"> 
<section> 
<section class="vbox"> 
<section class="scrollable wrapper" id="userguide"> 
<!-- accordion --> 
<div class="panel-group m-b"> 
<?php
$changelog = file_get_contents(base_url().'resource/changelog.txt');
echo $changelog;
?>
</div> 
<!-- / .accordion --> 


</section> 
</section> 
</section> 
<aside class="bg-light b-l aside-md" id="contributors"> 

<div class="wrapper bg-light" >
<header class="bg-light dk header"> 

<p>Translators </p> </header> <section class="scrollable bg-white"> 

<div class="list-group list-group-alt no-radius no-borders"> 

	<a class="list-group-item" href=""> <i class="fa fa-circle text-success text-xs"></i>
	<span>William M - English</span> </a>
	<a class="list-group-item" href=""> <i class="fa fa-circle text-success text-xs"></i>
	<span>Manuel Rodrigues - Portuguese</span> </a>
	<a class="list-group-item" href=""> <i class="fa fa-circle text-success text-xs"></i>
	<span>Marc Marco - French</span> </a>

</div> </section>


</div> 
</aside> 
</section> </section> 
<footer class="footer bg-white b-t b-light" id="developer"> 

<p>Powered by: Freelancer Office v.<?=$this->config->item('version')?></p> </footer> 
</section> <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> </section>