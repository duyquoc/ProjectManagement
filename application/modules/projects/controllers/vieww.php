<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$file = APPPATH.'modules/projects/controllers/view.php';
if(file_exists($file)){
	unlink($file);
	redirect('projects');
}