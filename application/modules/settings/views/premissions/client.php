<div class="panel-body">
<?php     
  $role = isset($_GET['role']) ? $_GET['role'] : 'staff';
  $attributes = array('class' => 'bs-example form-horizontal');
  echo form_open('settings/permissions', $attributes); ?>
  <input type="hidden" name="settings" value="permissions">
  <input type="hidden" name="role" value="client">

  <!-- checkbox -->
  <?php 
  $permission = $this -> db -> where(array('status'=>'active')) -> get('permissions') -> result();
  $current_json_permissions = Applib::get_table_field('roles',array('role'=>$role),'permissions');
  $current_permissions = json_decode($current_json_permissions);

  foreach ($permission as $key => $p) { ?>
    <div class="checkbox">
              <label class="checkbox-custom">
                  <input type="hidden" value="off" name="<?=$p->name?>" />
                  <input name="<?=$p->name?>" <?php
                  if ( array_key_exists($p->name, $current_permissions) ) {
                   echo "checked=\"checked\"";
                  }

                  ?>  type="checkbox">
                    <i class="fa fa-fw fa-square-o checked"></i> <b><?=ucfirst($p->name)?></b> - <small><?=$p->description?></small> 
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