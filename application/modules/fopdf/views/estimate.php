<?php
//Set default date timezone
date_default_timezone_set(config_item('timezone'));
if (!empty($estimate_details)) {
			foreach ($estimate_details as $key => $e) {
                            
// Get client info
$client = $this->db->where('co_id',$e->client)->get('companies')->result();
$currency = $this->applib->currencies($client[0]->currency);
$language = $this->applib->languages($client[0]->language);

$estimate = new invoicr("A4",$currency->symbol,$language->code);
//$invoice->AddFont('lato','','lato.php');
$estimate->currency = $currency->symbol;
$lang = $estimate->getLanguage($language->code);

$estimate->setNumberFormat(
                           config_item('decimal_separator'),
                           config_item('thousand_separator'));
//Set your logo
$estimate->setLogo("resource/images/logos/".config_item('invoice_logo'));
//Set theme color
$estimate->setColor(config_item('estimate_color'));
//Set type
$estimate->setType($lang['estimate']);
//Set reference

$temp_est = $this->db->where('est_id',$e->est_id)->get('estimates')->result();
$cur_e = $this->applib->currencies($temp_est[0]->currency);
                            
$estimate->setReference($e->reference_no);
//Set date
$estimate->setDate(strftime(config_item('date_format'), strtotime($e->date_saved)));
//Set due date
$estimate->setDue(strftime(config_item('date_format'), strtotime($e->due_date)));
//Set from
$sfx = "_".$language->name;
$estimate->setFrom(array(
                   (config_item('company_legal_name'.$sfx) ? config_item('company_legal_name'.$sfx) : config_item('company_legal_name')),
                   (config_item('company_address'.$sfx) ? config_item('company_address'.$sfx) : config_item('company_address')),
                   (config_item('company_city'.$sfx) ? config_item('company_city'.$sfx) : config_item('company_city')),
                   (config_item('company_country'.$sfx) ? config_item('company_country'.$sfx) : config_item('company_country')),
                   (config_item('company_phone'.$sfx) ? config_item('company_phone'.$sfx) : config_item('company_phone'))
                   ));
//Set to
$estimate->setTo(array(
			     $this -> applib -> company_details($e->client,'company_name'),
				   $this -> applib -> company_details($e->client,'company_address'),
				   $this -> applib -> company_details($e->client,'city'),
				   $this -> applib -> company_details($e->client,'country'),
				   $this -> applib -> company_details($e->client,'company_phone')
				   ));
// Calculate estimate
$sub_total = $this -> applib -> est_calculate('estimate_cost',$e->est_id);
$tax = $this -> applib -> est_calculate('tax',$e->est_id);
$discount = $this -> applib -> est_calculate('discount',$e->est_id);
$estimate_amount = $this -> applib -> est_calculate('estimate_amount',$e->est_id);
//Add items
if (!empty($estimate_items)) {
					foreach ($estimate_items as $key => $item) { 
            if(config_item('show_estimate_tax') == 'TRUE'){ $show_tax = $item->item_tax_total; } else{ $show_tax = false; }
$estimate->addItem(
                   $item->item_name,
                   $item->item_desc,
                   $item->quantity,
                   $show_tax,
                   $item->unit_cost,
                   false,
                   $item->total_cost
                   );
} } 
//Add totals
$estimate->addTotal($lang['total']." ",$sub_total);

$estimate->addTotal($lang['vat']." - ".$e->tax."%",$tax);

if($e->discount != 0){
  $estimate->addTotal($lang['discount']." - ".$e->discount."%",$discount);
}

$estimate->addTotal($lang['estimate_cost']." ",$estimate_amount,true);
//Set badge
if (config_item('display_estimate_badge') == 'TRUE') {
    $es = strtolower(str_replace(" ", "_", $e->status));
    $estimate->addBadge($e->status);
}
//Add title
$estimate->addTitle($lang['notes']);
//Add Paragraph
$estimate->addParagraph($e->notes);
//Set footer note
$estimate->setFooternote($this->config->item('company_name'));

if(isset($attach)){ 
  $render = 'F';
    $estimate->render('./resource/tmp/'.lang('estimate').' '.$e->reference_no.'.pdf',$render);
 }else{ 
  $render = 'D';
    $estimate->render($lang['estimate'].' '.$e->reference_no.'.pdf',$render);
}

} }