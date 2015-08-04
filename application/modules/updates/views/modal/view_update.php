  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=$update->title?></h4>
      </div>
      <div id="update-body" class="modal-body">
          <div id="update-description"><?=$update->description?></div>
          <div id="update-installation" style="display: none;">
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">0%</span>
                  </div>
                </div>
              <div class="message"></div>
          </div>
        
      </div>
      <div id="update-footer" class="modal-footer">
        <button id="close-update" type="button" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></button>
        <?php if ($update->installed == 0) : ?>
        <a id="install-update" class="btn btn-danger" href="#" data-build="<?=$update->build?>"><?=lang('install')?></a>
        <?php endif; ?>
      </div>
    </div>

  </div>

<script>
    function instProgress(pc, message) {
        $('#update-body span').html(pc+'%');
        $('#update-body .message').html(message);
        $('#update-body .progress-bar').css('width',pc+'%');
        $('#update-body .progress-bar').attr('aria-valuenow',pc);
    }
    
    function dbSync(build) {
        instProgress(0,'<?=lang('update_db_structure')?>');
            $.ajax({
                url: base_url+'db/sync/',
                type: 'POST',
                data: {check : 'TRUE'},
                success: function(data) {
                    dbFill(build);
                }
            });
    }
    function dbFill(build) {
        instProgress(10,'<?=lang('update_db_data')?>');
            $.ajax({
                url: base_url+'db/upgrade/',
                type: 'POST',
                data: {},
                success: function(data) {
                    getUpdate(build);
                }
            });
    }
    function getUpdate(build) {
        instProgress(30,'<?=lang('update_file_download')?>');
            $.ajax({
                url: base_url+'updates/download/',
                type: 'POST',
                data: {build : build},
                success: function(data) {
                    installUpdate(build);
                }
            });
    }
    function installUpdate(build) {
        instProgress(70,'<?=lang('update_install')?>');
            $.ajax({
                url: base_url+'updates/install/',
                type: 'POST',
                data: {build : build},
                success: function(data) {
                    installComplete();
                }
            });
    }
    
    function installComplete() {
        instProgress(100,'<?=lang('update_reload')?>');
        setTimeout("location.reload(true);",1000);
    }
    
    $('#install-update').on('click',function(){
        $('#update-installation, #update-description, #update-footer').toggle();
        var build = $(this).attr('data-build');
        dbSync(build);
    });

</script>