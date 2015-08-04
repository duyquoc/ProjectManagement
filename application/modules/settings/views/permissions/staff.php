<!-- Display staff -->
<div class="table-responsive">
    <table id="table-staff" class="AppendDataTables table table-striped m-b-none">
        <thead>
        <tr>
            <th><?=lang('full_name')?></th>
            <th><?=lang('username')?> </th>
            <th><?=lang('role')?> </th>
            <th class="hidden-sm"><?=lang('registered_on')?> </th>

            <th class="col-options no-sort"><?=lang('options')?></th>
        </tr> </thead> <tbody>
        <?php
        $this -> db -> join('account_details','account_details.user_id = users.id');
        $users = $this -> db -> where(array('role_id'=>'3')) -> get(Applib::$user_table) -> result();
        if (!empty($users)) {
            foreach ($users as $key => $user) { ?>
                <tr>

                    <td><?=$user->fullname?></td>
                    <td>

                        <a class="pull-left thumb-sm avatar">
                            <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user->id),'use_gravatar') == 'Y'){
                                $user_email = Applib::login_info($user->id)->email; ?>
                                <img src="<?=$this -> applib -> get_gravatar($user_email)?>" class="img-circle">
                            <?php }else{ ?>
                                <img src="<?=base_url()?>resource/avatar/<?=Applib::profile_info($user->id)->avatar?>" class="img-circle">
                            <?php } ?>
                            <?=ucfirst($user->username)?>
                        </a>

                    </td>
                    <td>
                        <span class="label label-primary"><?=ucfirst($this -> user_profile -> role_by_id($user->role_id))?></span></td>
                    <td class="hidden-sm"><?=strftime(config_item('date_format'), strtotime($user->created));?> </td>

                    <td>
                        <a href="<?=base_url()?>settings/?settings=permissions&staff=<?=$user->user_id?>" class="btn btn-default btn-sm" title="<?=lang('edit_permissions')?>"><i class="fa fa-edit"></i> <?=lang('edit_permissions')?> </a>

                    </td>
                </tr>
            <?php } } ?>


        </tbody>
    </table>
</div>

<!-- End staff -->