<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p>Hello <?=$project_manager?></p>
    <p>A new bug has been reported by <?=$added_by?>. </p>
    <p>You can view the Bug using the Dashboard Page.</p>
        --------------------------
        <br>
        <a href="<?=base_url()?>">My Account</a>
<p>
Regards<br>
<?=$this->config->item('company_name')?> Team
</p>
</body>
</html>
