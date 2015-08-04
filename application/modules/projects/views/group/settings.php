<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading"> <i class="fa fa-pencil"></i> <?=lang('project_settings')?> - <span class="text-danger"><?=$this -> applib -> get_any_field('projects',array('project_id'=>$project_id),'project_title')?></span></header>
            <!-- Start Settings -->
            <?php
            if ($role == '1') { ?>
                  <div class="panel-body project-settings">
  
    <!-- checkbox -->
    <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open('projects/settings', $attributes);
                $project_permissions = $this -> db -> where(array('id >'=>'0')) -> get('fx_project_settings') -> result();
                $current_json_permissions = $this -> applib -> get_any_field('projects',array('project_id'=>$project_id),'settings');
                if ($current_json_permissions == NULL) {
                    $current_json_permissions = '{"settings":"on"}';
                }
                $current_permissions = json_decode($current_json_permissions);

                foreach ($project_permissions as $key => $p) { ?>
                    <div class="checkbox">
                        <label class="checkbox-custom">
                            <input name="<?=$p->setting?>" <?php
                            if ( array_key_exists($p->setting, $current_permissions) ) {
                                echo "checked=\"checked\"";
                            }

                            ?>  type="checkbox">
                            <?=lang($p->setting)?>
                        </label>
                    </div>

                    <div class="line line-dashed line-lg pull-in"></div>

                <?php } ?>
                        
    <input type="hidden" name="project_id" value="<?=$project_id?>">
     <button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
    </div>
        </form>


<!-- End Settings -->

<?php } ?>
        </section>
    </div>
</div>