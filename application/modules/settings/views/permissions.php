<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('permission_settings')?> <?php
                if (isset($_GET['staff'])) {
                    echo ' for '.ucfirst(Applib::get_table_field('users',array('id'=>$_GET['staff']),'username'));
                }
                ?>
            </header>
            <?php
            if (isset($_GET['staff'])) {
                $data['user_id'] = $_GET['staff'];
                $this -> load -> view('permissions/edit_permissions', $data);
            }else{
                $this -> load -> view('permissions/staff');
            }
            ?>
        </section>
    </div>
    <!-- End Form -->
</div>
