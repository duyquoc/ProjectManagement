<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p>Hello <?=$client?></p>
    <p>Project : <?=$project_title?> - <?=$project_code?> has been completed. </p>
    <p>You can view the project by logging into your portal Account.</p>
        --------------------------
        <br>
        <a href="<?=base_url()?>">Log In</a>
        <br>
        --------------------------
        <br>
        Project Overview:
		Hours Logged # :  <?=$project_hours?> hours
<p>
Regards<br>
<?=$this->config->item('company_name')?> Team
</p>
</body>
</html>
