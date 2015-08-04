<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Paying <strong><?=$invoice_info['currency']?> <?=number_format($invoice_info['amount'],2)?></strong> for Invoice #<?=$invoice_info['item_name']?> via 2Checkout</h4>
		</div>		
		<div class="modal-body">

        <?php
             $attributes = array('id'=>'2checkout','class' => 'bs-example form-horizontal');
          echo form_open('checkout/process',$attributes); ?>

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
        <input id="token" name="token" type="hidden" value="">

        <div class="form-group">
                <label class="col-lg-4 control-label">Card Number</label>
                <div class="col-lg-5">
                    <input type="text" id="ccNo" size="20" class="form-control card-number input-medium" autocomplete="off" placeholder="5555555555554444" required>
                </div>
        </div>

        <div class="form-group">
                <label class="col-lg-4 control-label">CVC</label>
                <div class="col-lg-2">
                    <input type="text" id="cvv" size="4" class="form-control card-cvc input-mini" autocomplete="off" placeholder="123" required>
                </div>
        </div>

        <div class="form-group">
                <label class="col-lg-4 control-label">Expiration (MM/YYYY)</label>
                <div class="col-lg-2">
                    <input type="text" size="2" id="expMonth" class="form-control input-mini" autocomplete="off" placeholder="MM" required>
                    
                </div>
                <div class="col-lg-2">
                <input type="text" size="4" id="expYear" class="form-control input-mini" placeholder="YYYY" required>
                </div>
        </div>

    <div class="modal-footer"> 
    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
    <button type="submit" class="btn btn-success" id="submitBtn">Process Payment</button>
    </div>
</form>
				
			
		</div>

		

	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
    // Called when token created successfully.
    var successCallback = function(data) {
        var myForm = document.getElementById('2checkout');

        // Set the token as the value for the token input
        myForm.token.value = data.response.token.token;

        // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
        myForm.submit();
    };

    // Called when token creation fails.
    var errorCallback = function(data) {
        if (data.errorCode === 200) {
            tokenRequest();
        } else {
            alert(data.errorMsg);
        }
    };

    var tokenRequest = function() {
        // Setup token request arguments
        var args = {
            sellerId: "<?=config_item('2checkout_seller_id')?>",
            publishableKey: "<?=config_item('2checkout_publishable_key')?>",
            ccNo: $("#ccNo").val(),
            cvv: $("#cvv").val(),
            expMonth: $("#expMonth").val(),
            expYear: $("#expYear").val()
        };

        // Make the token request
        TCO.requestToken(successCallback, errorCallback, args);
    };

    $(function() {
        // Pull in the public encryption key for our environment
        TCO.loadPubKey('sandbox');

        $("#2checkout").submit(function(e) {
            // Call our token request function
            tokenRequest();

            // Prevent form from submitting
            return false;
        });
    });
</script>