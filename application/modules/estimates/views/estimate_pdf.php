<?php
        function stripAccents($string) {
                $chars = array("Ά"=>"Α","ά"=>"α","Έ"=>"Ε","έ"=>"ε","Ή"=>"Η","ή"=>"η","Ί"=>"Ι","ί"=>"ι","Ό"=>"Ο","ό"=>"ο","Ύ"=>"Υ","ύ"=>"υ","Ώ"=>"Ω","ώ"=>"ω");
                foreach ($chars as $find => $replace) {
                    $string = str_replace($find, $replace, $string);
                }
                return $string;
        }

        $color = config_item('estimate_color');
        
$this->applib->set_locale();
$username = $this->tank_auth->get_username(); 
        if (!empty($estimate_details)) {
            foreach ($estimate_details as $key => $est) {
                $l = $this->applib->company_details($est->client, 'language');
                $lang2 = $this->lang->load('fx_lang', $l, TRUE, FALSE, '', TRUE);
                $cur = $this->applib->currencies($est->currency);
        ?>
<html>
<head>
<style>
body {
    font-family: dejavusanscondensed;
    font-size: 10pt;
    line-height: 13pt;
    color: #777777;
}
p { 
    margin: 0pt;
}
td { 
    vertical-align: top; 
}
.items td {
    border: 0.1mm solid #ffffff;
    background-color: #F5F5F5;
    padding: 10px;
}
table thead td {
    border-bottom: 0.2mm solid <?=$color?>;
    vertical-align: bottom;
    text-align: center;
    text-transform: uppercase;
    font-size: 7pt;
    font-weight: bold;
    background-color: #FFFFFF;
    color:#111111;
}

</style>
</head>
<body>
<?php 
$watermark = $lang2[str_replace(" ","_",strtolower($est->status))];
$watermark = mb_strtoupper(stripAccents($watermark));
?>
<watermarktext content="<?=$watermark?>" alpha="0.05" />
<htmlpageheader name="myheader">
    <div style="height:120px">
<table width="100%"><tr>
<td width="50%"><img style="max-height:100px; max-width: 35%;" src="<?= base_url() ?>resource/images/logos/<?= config_item('invoice_logo') ?>" ></td>
<td width="50%" style="text-align: right;"><span style="font-weight: bold; font-size: 20pt; text-transform: uppercase; color: #111111;"><?=stripAccents($lang2['estimate'])?></td>
</tr></table>
        </div>
</htmlpageheader>

<htmlpagefooter name="myfooter">
<div style="font-size: 9pt; text-align: right; padding-top: 3mm; ">
<?=$lang2['page']?> {PAGENO} <?=$lang2['page_of']?> {nb}
</div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />

<div style="margin-bottom: 20px; margin-top: 10px;">
<table width="100%">
    <tr>
        <td width="62%"></td>
        <td width="25%" style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;"><?=stripAccents($lang2['reference_no'])?>:</td>
        <td width="13%" style="text-align: right; font-size: 9pt;"><?= $est->reference_no ?></td>
    </tr>
    <tr>
        <td width="62%"></td>
        <td width="25%" style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;"><?=stripAccents($lang2['invoice_date'])?>:</td>
        <td width="13%" style="text-align: right; font-size: 9pt;"><?= strftime(config_item('date_format'), strtotime($est->date_saved)); ?></td>
    </tr>
    <tr>
        <td width="62%"></td>
        <td width="25%" style="color: <?=$color?>; text-align: left; font-size: 9pt; text-transform: uppercase;"><?=stripAccents($lang2['valid_until'])?>:</td>
        <td width="13%" style="text-align: right; font-size: 9pt;"><?= strftime(config_item('date_format'), strtotime($est->due_date)); ?></td>
    </tr>
</table>
</div>

<div style="margin-bottom: 20px;">
<table width="100%" cellpadding="10" style="vertical-align: top;">
    <tr>
        <td width="45%" style="border-bottom:0.2mm solid <?=$color?>; font-size: 9pt; font-weight:bold; color: <?=$color?>; text-transform: uppercase;"><?= stripAccents($lang2['received_from']) ?></td>
        <td width="10%">&nbsp;</td>
        <td width="45%" style="border-bottom:0.2mm solid <?=$color?>; font-size: 9pt; font-weight:bold; color: <?=$color?>; text-transform: uppercase;"><?= stripAccents($lang2['bill_to']) ?></td>
    </tr>
<tr>
<td width="45%">
    <span style="font-size: 11pt; font-weight: bold; color: #111111;"><?= (config_item('company_legal_name_' . $l) ? config_item('company_legal_name_' . $l) : config_item('company_legal_name')) ?>
        </span><br/>
            <?= (config_item('company_address_' . $l) ? config_item('company_address_' . $l) : config_item('company_address')) ?>
            <br/>
            <?= (config_item('company_city_' . $l) ? config_item('company_city_' . $l) : config_item('company_city')) ?>
            <?= config_item('company_zip_code') ?>
            <br/>
            <?= (config_item('company_country_' . $l) ? config_item('company_country_' . $l) : config_item('company_country')) ?>
            <br/>
            <span class="col-xs-3 no-gutter"><?= $lang2['phone'] ?>: </span>
            <?= config_item('company_phone') ?>
            <br/>
            <?php if (config_item('company_phone_2') != '') : ?>
                <span class="col-xs-3 no-gutter"><?= $lang2['phone'] ?> 2: </span><?= config_item('company_phone_2') ?><br/>
            <?php endif; ?>
            <span class="col-xs-3 no-gutter"><?= $lang2['company_vat'] ?>: </span><?= config_item('company_vat') ?><br/>
</td>
<td width="10%">&nbsp;</td>
<td width="45%">
        <span style="font-size: 11pt; font-weight: bold; color: #111111;"><?= ucfirst($this->applib->company_details($est->client, 'company_name')) ?></span><br/>
            <?= ucfirst($this->applib->company_details($est->client, 'company_address')) ?><br/>
            <?= ucfirst($this->applib->company_details($est->client, 'city')) ?><br/>
            <?= ucfirst($this->applib->company_details($est->client, 'country')) ?> <br/>
            <?php $phone = $this->applib->company_details($est->client, 'company_phone'); ?>
            <?php if ($phone != '') : ?>
            <span class="col-xs-3 no-gutter"><?= $lang2['phone'] ?>: </span><a href="tel:<?= $phone ?>"><?= $phone ?></a><br/>
            <?php endif; ?>
            <?php $vat = $this->applib->company_details($est->client, 'VAT'); ?>
            <?php if ($vat != '') : ?>
            <span class="col-xs-3 no-gutter"><?= $lang2['company_vat'] ?>:</span><?=$vat?> <br/>
            <?php endif; ?>
</td>
</tr>       
</table>
</div>

<table class="items" width="100%" style="border-spacing:3px; font-size: 9pt; border-collapse: collapse;" cellpadding="10">
<thead>
<tr>
    <td width="45%" style="text-align: left; color: #111111;"><?= stripAccents($lang2['item_name']) ?> </td>
    <td width="10%"><?= stripAccents($lang2['qty']) ?> </td>
    <td width="15%"><?= stripAccents($lang2['unit_price']) ?> </td>
    <td width="15%"><?= stripAccents($lang2['tax']) ?> </td>
    <td width="15%"><?= stripAccents($lang2['total']) ?> </td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->
<?php
if (!empty($estimate_items)) {
    foreach ($estimate_items as $key => $item) { ?>
    <tr>
        <td width="44%" style="text-align: left;"><div style="margin-bottom:6px; font-weight:bold; color: #111111;"><?= $item->item_name?></div>
        <?= nl2br($item->item_desc) ?></td>
        <td width="10%" style="text-align: center;"><?=  number_format($item->quantity,0) ?></td>
        <td width="12%" style="text-align: right;"><?= number_format($item->unit_cost, 2, config_item('decimal_separator'), config_item('thousand_separator')) ?></td>
        <td width="12%" style="text-align: right;"><?= number_format($item->item_tax_total, 2, config_item('decimal_separator'), config_item('thousand_separator')) ?></td>
        <td width="12%" style="text-align: right;"><?= number_format($item->total_cost, 2, config_item('decimal_separator'), config_item('thousand_separator')) ?>
        </td>
    </tr>
<?php }
} ?>
    
<tr>
    <td colspan="3" style="background-color:#ffffff;"></td>
    <td style="font-size: 8pt; color: #111111;"><strong><?= $lang2['total'] ?></strong></td>
    <td style="font-weight: bold; color: #111111; text-align: right;"><?= $cur->symbol ?> <?= number_format($this->applib-> est_calculate('estimate_cost', $est->est_id), 2, config_item('decimal_separator'), config_item('thousand_separator')) ?></td>
</tr>
<?php if ($est->tax > 0.00): ?>
    <tr>
        <td colspan="3" style="background-color:#ffffff;"></td>
        <td style="font-size: 8pt; color: #111111;">
            <strong><?= $lang2['tax'] ?> <?php echo ($est->tax * 100) / 100; ?>%</strong></td>
        <td style="font-weight: bold; text-align: right; color: #111111;"><?= $cur->symbol ?> <?= number_format($this->applib->est_calculate('tax', $est->est_id), 2, config_item('decimal_separator'), config_item('thousand_separator')) ?> </td>
    </tr>
<?php endif ?>
<?php if ($est->discount > 0) { ?>
    <tr>
        <td colspan="3" style="background-color:#ffffff;"></td>
        <td style="font-size: 8pt; color: #111111;">
            <strong><?= $lang2['discount'] ?> - <?php echo $est->discount; ?>%</strong></td>
        <td style="font-weight: bold; text-align: right; color: #111111;"><?= $cur->symbol ?> <?= number_format($this->applib->calculate('discount', $est->est_id), 2, config_item('decimal_separator'), config_item('thousand_separator')) ?> </td>
    </tr>
    <?php } ?>
<tr>
    <td colspan="3" style="background-color:#ffffff;"></td>
    <td style="font-size: 8pt; color: #111111; background-color: <?=$color?>; color:#ffffff;"><strong><?= $lang2['estimate_cost'] ?></strong></td>
    <td style="font-weight: bold; color: #111111; text-align: right; background-color: <?=$color?>; color:#ffffff;"><?= $cur->symbol ?> <?= number_format($this->applib->est_calculate('estimate_amount', $est->est_id), 2, config_item('decimal_separator'), config_item('thousand_separator')) ?></td>
</tr>
    
</tbody>
</table>
        <div style="margin-top:40px;">
            <h4 style="padding:5px 0; color: #111111; border-bottom: 0.2mm solid <?=$color?>; font-size:9pt; text-transform: uppercase;"><?= stripAccents($lang2['notes']) ?></h4>
            <?= $est->notes ?>
        </div>
    </body>
</html>
    <?php } // endforeach  ?>
<?php } // endif  ?>
