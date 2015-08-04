<div class="panel-body">
    <?php
    $attributes = array('class' => 'bs-example form-horizontal');
    echo form_open('settings/permissions', $attributes); ?>
        <input type="hidden" name="settings" value="permissions">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <!-- checkbox -->
        <?php
        $permission = $this -> db -> where(array('status'=>'active')) -> get('permissions') -> result();
        $current_json_permissions = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'allowed_modules');
        if ($current_json_permissions == NULL) {
            $current_json_permissions = '{"settings":"permissions"}';
        }
        $current_permissions = json_decode($current_json_permissions, true);
        foreach ($permission as $key => $p) { ?>
            <div class="checkbox">
                <label class="checkbox-custom">
                    <input type="hidden" value="off" name="<?=$p->name?>" />
                    <input name="<?=$p->name?>" <?php
                    if ( array_key_exists($p->name, $current_permissions) && $current_permissions[$p->name] == 'on') {
                        echo "checked=\"checked\"";
                    }
                    ?>  type="checkbox">
                    <?=lang($p->name)?>
                </label>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>

        <?php } ?>

        <div class="form-group">
            <div class="col-lg-1 col-lg-10">
                <button type="submit" class="btn btn-sm btn-primary"><?=lang('save_changes')?></button>
            </div>
        </div>
    </form>
</div>