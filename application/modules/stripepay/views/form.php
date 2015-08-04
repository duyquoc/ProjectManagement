<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Paying <strong><?=$invoice_info['currency']?> <?=number_format($invoice_info['amount'],2)?></strong> for Invoice #<?=$invoice_info['item_name']?> <?=lang('via_stripe')?></h4>
		</div>		
		<div class="modal-body">
<?php
			 $attributes = array('id'=>'payment-form','class' => 'bs-example form-horizontal');
          echo form_open('stripepay/authenticate',$attributes); ?>

		<?php // Show PHP errors, if they exist:
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

		<div class="alert alert-info">Enter the Card Number without spaces or hyphens.</div>

		 <div class="form-group">
				<label class="col-lg-4 control-label">Card Number</label>
				<div class="col-lg-5">
					<input type="text" size="20" class="form-control card-number input-medium" autocomplete="off" placeholder="5555555555554444">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label">CVC</label>
				<div class="col-lg-2">
					<input type="text" size="4" class="form-control card-cvc input-mini" autocomplete="off" placeholder="123">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label">Expiration (MM/YYYY)</label>
				<div class="col-lg-2">
					<input type="text" size="2" class="form-control card-expiry-month input-mini" autocomplete="off" placeholder="MM">
					
				</div>
				<div class="col-lg-2">
				<input type="text" size="4" class="form-control card-expiry-year input-mini" placeholder="YYYY">
				</div>
				</div>


				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success" id="submitBtn">Authenticate with Stripe</button>
		</div>
				
			
		</div>
		
		</form>

		<?php
if (isset($stripe)) { ?>

<script type="text/javascript" src="<?=base_url()?>resource/js/stripe/buy.js"></script>




<?php } ?>


	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->