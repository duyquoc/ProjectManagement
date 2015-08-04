<div class="row">
    <div class="col-lg-12">
        <?php
        $department = isset($_GET['dept']) ? $_GET['dept'] : '';
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url() . 'settings/add_custom_field', $attributes);
        ?>
        <section class="panel panel-default">
            <div class="panel-body">

                <?php if ($department != '') { $submit = "button_add_field"; ?>

                    <input type="hidden" name="deptid" value="<?php echo $department; ?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('custom_field_name') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_username') ?>" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('field_type') ?> <span class="text-danger">*</span> </label>
                        <div class="col-lg-8">
                            <select name="type" class="form-control">
                                <option value="text"><?= lang('text_field') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
                    <?php
                    $fields = $this->db->where(array('deptid' => $department))->get('fields')->result();
                    if (!empty($fields)) : ?>
                        <?php foreach ($fields as $key => $f) : ?>
                            <label class="label label-danger"><a class="text-white" href="<?= base_url() ?>settings/edit_custom_field/<?= $f->id ?>" data-toggle="ajaxModal" title = ""><?= $f->name ?></a></label>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <?php } else { $submit = "select_department"; ?>

                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?= lang('department') ?> <span class="text-danger">*</span> </label>
                        <div class="col-lg-6">
                            <div class="m-b">
                                <select name="targetdept" class="form-control" required >
                                    <?php
                                    $departments = $this->db->where(array('deptid >' => '0'))->get('departments')->result();
                                    if (!empty($departments)) { ?>
                                        <?php foreach ($departments as $d) : ?>
                                            <option value="<?= $d->deptid ?>"><?= ucfirst($d->deptname) ?></option>
                                        <?php endforeach; } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="panel-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
                </div>
            </div>
        </section>
        </form>
    </div>
</div>