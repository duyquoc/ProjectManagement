<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_item')?></h4>
		</div>
		
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'estimates/items/insert',$attributes); ?>
          <input type="hidden" name="estimate" value="<?=$estimate?>">

		<div class="modal-body">

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select id="template-item" name="item" class="form-control">
					<?php
					if (!empty($saved_items)) {
                                            $qnt = [];
                                            $c = 1;
                                            $q = '';
					foreach ($saved_items as $key => $item) { ?>
						<option value="<?=$item->item_id?>"><?=$item->item_name?> - <?=$item->unit_cost?></option>
                                                <?php if ($c == 1) { $q = $item->quantity; }; $qnt[] = "'".$item->item_id."' : '".$item->quantity."'"; $c += 1; ?>
					<?php } } ?>					
				</select>
				</div>
				</div>


				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('quantity')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input id="template-quantity" type="text" class="form-control" placeholder="2" name="quantity" value="<?=$q?>">
				</div>
				</div>

			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('button_color')?>"><?=lang('add_item')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script>

var qnt = {<?php echo implode(", ", $qnt); ?>};

$('#template-item').on('change',function(){
    var cval = $(this).val();
    $('#template-quantity').val(qnt[cval]);
});

</script>