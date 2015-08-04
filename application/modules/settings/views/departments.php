<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/departments', $attributes); ?>
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('departments')?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('department_name')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="deptname" class="form-control" style="width:260px" placeholder="Department Name" required>
                        </div>
                    </div>
                    <?php
                    $departments = $this -> db -> get('departments') -> result();
                    if (!empty($departments)) {
                        foreach ($departments as $key => $d) { ?>
                            <label class="label label-danger"><a class="text-white" href="<?=base_url()?>settings/edit_dept/<?=$d->deptid?>" data-toggle="ajaxModal" title = ""><?=$d->deptname?></a></label>
                        <?php } } ?>

                </div>
                <div class="panel-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>

