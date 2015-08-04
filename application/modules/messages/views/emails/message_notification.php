<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p>Hello <?=$recipient_username?></p>
    <p>You have received one message from <?=$this->tank_auth->get_username()?>. </p>
        --------------------------
        <br>
        <?=$message?>
<p>
Regards<br>
<?=$this->config->item('company_name')?> Team
</p>
</body>
</html>
