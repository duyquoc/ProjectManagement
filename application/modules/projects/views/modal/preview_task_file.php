<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=$file->title?></h4>
        </div>
        <div class="modal-body">
            <img width="538" src="<?=base_url()?><?=$file_path?>"
                 alt="<?=$file->original_name?>"/>
        </div>
    </div>
</div>