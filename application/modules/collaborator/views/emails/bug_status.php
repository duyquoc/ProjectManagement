<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p>Hello</p>
    <p>Bug <?=$issue_ref?> has been marked as <?=$status?> by <?=$marked_by?>. </p>
    <p>You can view this bug by logging in to the portal using the link below.</p>
        --------------------------
        <br>
        <a href="<?=base_url()?>">Log In</a>
<p>
Regards<br>
<?=$this->config->item('company_name')?> Team
</p>
</body>
</html>
