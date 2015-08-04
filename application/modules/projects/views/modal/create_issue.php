<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">New Issue</h4>
		</div>
		<div class="modal-body">
			<p>We hope your issue will be resolved as soon as possible.</p>
			 <?php
          echo form_open(base_url().'exams/create_issue'); ?>
          <input type="hidden" name="r_url" value="<?=base_url()?>exams/transcripts">
          <input type="hidden" name="exam_id" value="<?=$exam_id?>">
				<div class="form-group">
					<label>Type your complain here</label>
					<textarea name="message" class="form-control" ></textarea>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
		<button type="submit" class="btn btn-info">Am through Here!</button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->