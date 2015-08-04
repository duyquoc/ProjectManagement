<script type="text/javascript">
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            <?php
            $tasks = Applib::retrieve(Applib::$tasks_table, array('project' => $project_id));
            ?>
            eventClick:  function(event, jsEvent, view) {
                    $('#modalTitle').html(event.title);
                    $('#modalBody').html(event.description);
                    $('#taskUrl').attr('href',event.url);
                    $('#fullCalModal').modal();
                    return false;
                },
            eventSources: [
                {
                    events: [// put the array in the `events` property
                    <?php foreach ($tasks as $key => $t) { ?>
                            {
                                title  : '<?= addslashes($t->task_name) ?>',
                                        description: '<?= $t->description ?>',
                                start  : '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                        end: '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                url: '<?= base_url('projects/view/' . $t->project . '?group=tasks&view=task&id=' . $t->t_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#7266BA',
                    textColor: 'white'
                }
                // additional sources
            ]
        });
    });
</script>