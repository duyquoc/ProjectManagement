<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('new_item')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'items/add_item',$attributes); ?>
		<div class="modal-body">
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('item_name')?>" name="item_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_description')?> </label>
				<div class="col-lg-8">
				<textarea class="form-control" name="item_desc" placeholder="<?=lang('item_description')?>"></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('quantity')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="2" name="quantity">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('unit_price')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="350.00" name="unit_cost">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('tax_rate')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<select name="item_tax_rate" class="form-control m-b">
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
		<button type="submit" class="btn btn-primary"><?=lang('add_item')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->