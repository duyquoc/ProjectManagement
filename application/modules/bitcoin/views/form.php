<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Paying <strong><?=$invoice_info['currency']?> <?=number_format($invoice_info['amount'],2)?></strong> for Invoice #<?=$invoice_info['item_name']?> Via Bitcoin</h4>
		</div>		
		<div class="modal-body">
<?php
			 $attributes = array('id'=>'payment-form','class' => 'bs-example form-horizontal');
          /*echo form_open('bitcoin/send',$attributes);*/ ?>

		<?php // Show PHP errors, if they exist:
		$this->load->library('tank_auth');
		$client = $this->tank_auth->get_user_id();
		function round_up ( $value, $precision ) { 
			$pow = pow ( 10, $precision ); 
			return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
		}
		$btc_amount = file_get_contents("https://blockchain.info/tobtc?currency=".$invoice_info['currency']."&value=".$invoice_info['amount']);
		$btc_amount = round_up($btc_amount, 3);
		$blockchain_api = "https://blockchain.info/api/receive?method=create&cors=true&format=plain&address=".$this->config->item('bitcoin_address')."&shared=false&callback=".base_url()."bitcoin%2Fsuccess%3Fusdamount%3D".$invoice_info['amount']."%26invoicename%3D".$invoice_info['item_name']."%26btcamount%3D".$btc_amount."%26invoice%3D".$invoice_info['item_number']."%26client%3D".$client;
		$recieve_api = file_get_contents($blockchain_api);
		
		$decoded = json_decode($recieve_api);
		$btc_address = $decoded->input_address;
		if (isset($errors) && !empty($errors) && is_array($errors)) {
			echo '<div class="alert alert-error"><h4>Error!</h4>The following error(s) occurred:<ul>';
			foreach ($errors as $e) {
				echo "<li>$e</li>";
			}
			echo '</ul></div>';	
		}?>
		
		<div id="payment-errors"></div>
		<input type="hidden" name="invoice_id" value="<?=$invoice_info['item_number']?>">
		<input type="hidden" name="amount" value="<?=number_format($invoice_info['amount'],2)?>">
		<input type="hidden" name="btc_amount" value="<?=$btc_amount ?>">

		
		<h4>Send <?=$btc_amount?> BTC to <a href="bitcoin:<?=$btc_address?>?amount=<?=$btc_amount?>"><?=$btc_address?></a></h4>
		<br>
		<div class="alert alert-info" style="align:center">Your invoice will be marked as paid automatically.</div>
				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		</div>
				
			
		</div>
		
		</form>





	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
