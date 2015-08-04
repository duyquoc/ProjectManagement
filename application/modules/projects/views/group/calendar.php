<section class="scrollable">
<?php 
if ($role == '1' OR $role == '3' OR $this -> applib -> project_setting('show_project_calendar',$project_id)) { ?>
    <div class="calendar" id="calendar">
    </div>
    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></button>
                    <a id="taskUrl" class="btn btn-primary text-white"><?=lang('view_task')?></a>
                </div>
            </div>
        </div>
    </div>
        <?php } ?>
</section>