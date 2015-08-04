<!-- Start Notebook -->
<?php if ($role != '2') { ?>
<section class="panel panel-default">
<header class="panel-heading"> <i class="fa fa-pencil"></i> <?=lang('project_notes')?></header>
<?php echo form_open(base_url().'projects/notebook/savenote'); ?>
<input type="hidden" name="project" value="<?=$project_id?>">
<aside>
    <section class="paper">
        <textarea type="text" class="form-control scrollable" name="notes" placeholder="<?=lang('type_your_note_here')?>"><?=$this-> applib ->get_project_details($project_id,'notes')?></textarea>
    </section>
    
</aside>
</section>
<hr>
<button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
</form>
<!-- End Notebook -->
<?php } ?>