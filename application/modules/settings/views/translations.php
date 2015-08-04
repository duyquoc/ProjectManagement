    
    <?php if (!isset($language)) : ?>
<section class="panel panel-default">
<header class="panel-heading font-bold"><i class="fa fa-cogs"></i> <?=lang('translations')?></header>
            <div class="row"><div class="panel-body">
            <div class="pull-right add-translation">
                    <select id="add-language" class="select2-option" name="language">
                    <?php foreach ($available as $loc) : ?>
                    <option value="<?=str_replace(" ", "_", $loc->language)?>"><?=ucwords($loc->language)?></option>
                    <?php endforeach; ?>
                    </select>
                <button id="add-translation" class="btn btn-info"><?=lang('add_translation')?></button>
            </div>
        </div></div>
            <div class="table-responsive">
              <table id="table-translations" class="table table-striped b-t b-light AppendDataTables">
                <thead>
                        <tr>
                        <th class="col-xs-1 no-sort"><?=lang('icon')?></th>
                        <th class="col-xs-2"><?=lang('language')?></th>
                        <th class="col-xs-4"><?=lang('progress')?></th>
                        <th class="col-xs-1"><?=lang('done')?></th>
                        <th class="col-xs-1"><?=lang('total')?></th>
                        <th class="col-options no-sort col-xs-3"><?=lang('options')?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach($languages as $l) : 
                        $st = $translation_stats;
                        $total = $st[$l->name]['total'];
                        $translated = $st[$l->name]['translated'];
                        $pc = intval(($translated/$total)*1000) / 10;
                    ?>
                    <tr>
                        <td class=""><img src="<?=base_url('resource/images/flags/'.$l->icon)?>.gif" /></td>
                        <td class=""><a href="<?=base_url()?>settings/translations/view/<?=$l->name?>/?settings=translations"><?=ucwords(str_replace("_"," ", $l->name))?></a></td>
                        <td>
                            <div class="progress">
                            <?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
                            <div class="progress-bar progress-bar-<?=$bar?>" role="progressbar" aria-valuenow="<?=$pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pc?>%;">
                            <?=$pc?>%
                            </div>
                            </div>                        
                        </td>
                        <td class=""><?=$translated?></td>
                        <td class=""><?=$total?></td>
                        <td class="">
                          <a data-rel="tooltip" data-original-title="<?=lang('submit_translation_note')?>" class="submit-translation btn btn-xs btn-default" href="#" data-href="<?=base_url()?>settings/translations/<?=$l->name?>/submit/?settings=translations"><i class="fa fa-envelope-o"></i></a>
                          <a data-rel="tooltip" data-original-title="<?=lang('backup')?>" class="backup-translation btn btn-xs btn-default" href="#" data-href="<?=base_url()?>settings/translations/backup/<?=$l->name?>/?settings=translations"><i class="fa fa-download"></i></a>
                          <a data-rel="tooltip" data-original-title="<?=lang('restore')?>" class="restore-translation btn btn-xs btn-default" href="#" data-href="<?=base_url()?>settings/translations/restore/<?=$l->name?>/?settings=translations"><i class="fa fa-upload"></i></a>
                          <a data-rel="tooltip" data-original-title="<?=($l->active == 1 ? lang('deactivate') : lang('activate') )?>" class="active-translation btn btn-xs btn-<?=($l->active == 0 ? 'default' : 'success' )?>" href="#" data-href="<?=base_url()?>settings/translations/active/<?=$l->name?>/?settings=translations"><i class="fa fa-eye"></i></a>
                          <a data-rel="tooltip" data-original-title="<?=lang('edit')?>" class="btn btn-xs btn-info" href="<?=base_url()?>settings/translations/view/<?=$l->name?>/?settings=translations"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
</section>
    
    
    <?php elseif (!isset($language_file)) : ?> 


<section class="panel panel-default">
<header class="panel-heading font-bold"><i class="fa fa-cogs"></i><?=lang('translations')?> - <?=ucwords($language)?></header>
            <div class="table-responsive">
              <table id="table-translations-files" class="table table-striped b-t b-light AppendDataTables">
                <thead>
                        <tr>
                        <th class="col-xs-2 no-sort"><?=lang('type')?></th>
                        <th class="col-xs-3"><?=lang('file')?></th>
                        <th class="col-xs-4"><?=lang('progress')?></th>
                        <th class="col-xs-1"><?=lang('done')?></th>
                        <th class="col-xs-1"><?=lang('total')?></th>
                        <th class="col-options no-sort col-xs-1"><?=lang('options')?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach($language_files as $file => $altpath) : 
                        $shortfile = str_replace("_lang.php", "", $file);
                        $st = $translation_stats[$language]['files'][$shortfile];
                        $fn = ucwords(str_replace("_"," ", $shortfile));
                        if ($shortfile == 'fx') { $fn = 'Main Application'; }
                        if ($shortfile == 'tank_auth') { $fn = 'Authenication'; }
                        $total = $st['total'];
                        $translated = $st['translated'];
                        $pc = intval(($translated/$total)*1000) / 10;
                    ?>
                    <tr>
                        <td class=""><?=($altpath == './system/language/' ? 'System':'Application')?></td>
                        <td class=""><a href="<?=base_url()?>settings/translations/edit/<?=$language?>/<?=$shortfile?>/?settings=translations"><?=$fn?></a></td>
                        <td>
                            <div class="progress">
                            <?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
                            <div class="progress-bar progress-bar-<?=$bar?>" role="progressbar" aria-valuenow="<?=$pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$pc?>%;">
                            <?=$pc?>%
                            </div>
                            </div>                        
                        </td>
                        <td class=""><?=$translated?></td>
                        <td class=""><?=$total?></td>
                        <td class="">
                          <a class="btn btn-xs btn-default" href="<?=base_url()?>settings/translations/edit/<?=$language?>/<?=$shortfile?>/?settings=translations"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
</section>

    <?php else : ?>
    
    <?php $attributes = array('class' => 'bs-example form-horizontal', 'id'=>'form-strings');
    echo form_open_multipart('settings/translations/save/'.$language.'/'.$language_file.'/?settings=translations', $attributes); ?> 
    <input type="hidden" name="_language" value="<?=$language?>">
    <input type="hidden" name="_file" value="<?=$language_file?>">
    
    <section class="panel panel-default">
    <header class="panel-heading font-bold"><i class="fa fa-cogs"></i>
    <?php 
    $fn = ucwords(str_replace("_"," ", $language_file));
    if ($language_file == 'fx') { $fn = 'Main Application'; }
    if ($language_file == 'tank_auth') { $fn = 'Authenication'; }
    
    $total = count($english);
    $translated = 0;
    if ($language == 'english') { $percent = 100; } else {
        foreach ($english as $key => $value) {
            if (isset($translation[$key]) && $translation[$key] != $value) { $translated++; }
        }
        $percent = intval(($translated / $total) * 100);
    }
    ?>
    <?=lang('translations')?> | <a href="<?=base_url()?>settings/translations/view/<?=$language?>/?settings=translations"><?=ucwords(str_replace("_"," ", $language))?></a> | <?=$fn?> | <?=$percent?>% <?=mb_strtolower(lang('done'))?>
    <button type="submit" id="save-translation" class="btn btn-xs btn-primary pull-right"><?=lang('save_translation')?></button>
    </header>
        <div class="table-responsive">
          <table id="table-strings" class="table table-striped b-t b-light AppendDataTables">
            <thead>
              <tr>
                <th class="col-xs-5">English</th>
                <th class="col-xs-7"><?=ucwords(str_replace("_"," ", $language))?></th>
              </tr>
            </thead>
            <tbody>
                <?php
                foreach ($english as $key => $value) : ?>
              <tr>
                <td><?=$value?></td>
                <td><input class="form-control" width="100%" type="text" value="<?=(isset($translation[$key]) ? $translation[$key] : $value)?>" name="<?=$key?>" /></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
    <?php endif; ?>

<!-- End details -->
 </section>
</form> 