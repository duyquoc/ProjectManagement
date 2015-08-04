<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('edit_item')?></h4>
		</div>
		<?php
			if (!empty($item_details)) {
			foreach ($item_details as $key => $item) { ?>

		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'estimates/items/edit',$attributes); ?>
          <input type="hidden" name="item_id" value="<?=$item->item_id?>">
           <input type="hidden" name="estimate_id" value="<?=$item->estimate_id?>">
		<div class="modal-body">
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$item->item_name?>" name="item_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_description')?> </label>
				<div class="col-lg-8">
				<textarea class="form-control" name="item_desc"><?=$item->item_desc?></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('quantity')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$item->quantity?>" name="quantity">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('unit_price')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$item->unit_cost?>" name="unit_cost">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('tax_rate')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<select name="item_tax_rate" class="form-control m-b">
						<option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option>
						<option value="0.00"><?=lang('none')?></option>
						<?php
						if (!empty($rates)) {
						foreach ($rates as $key => $tax) { ?>
                          <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                          <?php } } ?>
                        </select>
				</div>
				</div>
				
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
		</form>
		</div>
		<?php } } ?>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->