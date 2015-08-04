<?php
//Set default date timezone
date_default_timezone_set(config_item('timezone'));

if (!empty($invoice_details)) {
			foreach ($invoice_details as $key => $inv) { 

// Get invoice and client info
$client = $this->db->where('co_id',$inv->client)->get('companies')->result();
$currency = $this->applib->currencies($client[0]->currency);
$language = $this->applib->languages($client[0]->language);


$invoice = new invoicr("A4",$currency->symbol,$language->code);
//$invoice->AddFont('lato','','lato.php');
$invoice->currency = $currency->symbol;
$lang = $invoice->getLanguage($language->code);

//Set number formatting
$invoice->setNumberFormat(
                          config_item('decimal_separator'),
                          config_item('thousand_separator')
                          );
//Set your logo
$invoice->setLogo("resource/images/logos/".config_item('invoice_logo')); // $invoice->setLogo(image,maxwidth,maxheight);
//Set theme color
$invoice->setColor(config_item('invoice_color'));
//Set type
$invoice->setType($lang['invoice']);
//Set reference

$invoice->setReference($inv->reference_no);
//Set date
$invoice->setDate(
                  strftime(config_item('date_format'), 
                  strtotime($inv->date_saved))
                  );
//Set due date
$invoice->setDue(
                 strftime(config_item('date_format'),
                 strtotime($inv->due_date))
                 );

//Set from
$sfx = "_".$language->name;
$invoice->setFrom(array(
                   (config_item('company_legal_name'.$sfx) ? config_item('company_legal_name'.$sfx) : config_item('company_legal_name')),
                   (config_item('company_address'.$sfx) ? config_item('company_address'.$sfx) : config_item('company_address')),
                   (config_item('company_city'.$sfx) ? config_item('company_city'.$sfx) : config_item('company_city')),
                   (config_item('company_country'.$sfx) ? config_item('company_country'.$sfx) : config_item('company_country')),
                   config_item('company_vat')
                   ));
//Set to
$invoice->setTo(array(
     $this -> applib -> company_details($inv->client,'company_name'),
	   $this -> applib -> company_details($inv->client,'company_address'),
	   $this -> applib -> company_details($inv->client,'city'),
	   $this -> applib -> company_details($inv->client,'country'),
	   $this -> applib -> company_details($inv->client,'VAT')
                 ));
// Calculate Invoice
$sub_total = $this -> applib -> calculate('invoice_cost',$inv->inv_id);


$tax = $this -> applib -> calculate('tax',$inv->inv_id);


$discount = $this -> applib -> calculate('discount',$inv->inv_id);
$paid = $this -> applib -> calculate('paid_amount',$inv->inv_id);
$invoice_cost = $this -> applib -> calculate('invoice_due',$inv->inv_id);
//Add items
if (!empty($invoice_items)) {
					foreach ($invoice_items as $key => $item) { 
                    if(config_item('show_invoice_tax') == 'TRUE'){ 
                      $show_tax = $item->item_tax_total; 
                    } else{ 
                      $show_tax = false; 
                    }
$invoice->addItem(
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
$invoice->addTotal(
          $lang['total']." ",$sub_total);

if ($inv->tax > 0.00) {
$invoice->addTotal($lang['tax']." - ".$inv->tax."%",$tax);
}

if($inv->discount != 0){
  $invoice->addTotal($lang['discount']." - ".$inv->discount."%",$discount);
}

$invoice->addTotal($lang['paid']." ",$paid);

$invoice->addTotal($lang['balance_due']." ",$invoice_cost,true);
//Set badge
if (config_item('display_invoice_badge') == 'TRUE') {
    $ps = strtolower(str_replace(" ", "_", $payment_status));
 $invoice->addBadge($payment_status);
}

//Add title
$invoice->addTitle($lang['payment_information']);
//Add Paragraph
$invoice->addParagraph($inv->notes);
//Set footer note
$invoice->setFooternote(config_item('company_name'));

if(isset($attach)){ 
  $render = 'F'; 
  $invoice->render('./resource/tmp/'.lang('invoice').' '.$inv->reference_no.'.pdf',$render);
}else{ 
  $render = 'D'; 
  $invoice->render($lang['invoice'].' '.$inv->reference_no.'.pdf',$render);
}
//Render

} }