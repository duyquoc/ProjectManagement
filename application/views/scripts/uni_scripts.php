<?php if (isset($datepicker)) { ?>
<script src="<?=base_url()?>resource/js/slider/bootstrap-slider.js" cache="false"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.min.js" cache="false"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/locales/bootstrap-datepicker.<?=(lang('lang_code') == 'en' ? 'en-GB': lang('lang_code'))?>.min.js" cache="false"></script>
<?php } ?>

<?php if (isset($form)) { ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js" cache="false"></script>
<script src="<?=base_url()?>resource/js/file-input/bootstrap-filestyle.min.js" cache="false"></script>
<script src="<?=base_url()?>resource/js/wysiwyg/jquery.hotkeys.js" cache="false"></script>
<script src="<?=base_url()?>resource/js/wysiwyg/bootstrap-wysiwyg.js" cache="false"></script>
<script src="<?=base_url()?>resource/js/wysiwyg/demo.js" cache="false"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/2.0.7/parsley.min.js" cache="false"></script>
<script src="<?=base_url()?>resource/js/parsley/parsley.extend.js" cache="false"></script>
<?php } ?>
<?php if ($this->uri->segment(2) == 'help') { ?>
 <!-- App --> 
<script src="//cdnjs.cloudflare.com/ajax/libs/intro.js/1.0.0/intro.min.js" cache="false"> </script>
<script src="<?=base_url()?>resource/js/intro/demo.js" cache="false"> </script>
<?php }  ?>

<?php
if (isset($datatables)) {
    $sort = strtoupper(config_item('date_picker_format'));
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.7/sorting/datetime-moment.js"></script>
<script type="text/javascript">
        jQuery.extend( jQuery.fn.dataTableExt.oSort, { 
            "currency-pre": function (a) {
                a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );        
                return parseFloat( a ); },     
            "currency-asc": function (a,b) {        
                return a - b; },     
            "currency-desc": function (a,b) {        
                return b - a; }
        });             
        $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
                for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                        oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                oSettings.oPreviousSearch.sSearch = '';

                if(typeof bDraw === 'undefined') bDraw = true;
                if(bDraw) this.fnDraw();
        }        

        $(document).ready(function() {

        $.fn.dataTable.moment('<?=$sort?>');
        $.fn.dataTable.moment('<?=$sort?> HH:mm');

        var oTable1 = $('.AppendDataTables').dataTable({
        "bProcessing": true,
        "sDom": "<'row'<'col-sm-4'l><'col-sm-8'f>r>t<'row'<'col-sm-4'i><'col-sm-8'p>>",
        "sPaginationType": "full_numbers",
        "iDisplayLength": <?=config_item('rows_per_table')?>,
        "oLanguage": {
                "sProcessing": "<?=lang('processing')?>",
                "sLoadingRecords": "<?=lang('loading')?>",
                "sLengthMenu": "<?=lang('show_entries')?>",
                "sEmptyTable": "<?=lang('empty_table')?>",
                "sZeroRecords": "<?=lang('no_records')?>",
                "sInfo": "<?=lang('pagination_info')?>",
                "sInfoEmpty": "<?=lang('pagination_empty')?>",
                "sInfoFiltered": "<?=lang('pagination_filtered')?>",
                "sInfoPostFix":  "",
                "sSearch": "<?=lang('search')?>:",
                "sUrl": "",
                "oPaginate": {
                        "sFirst":"<?=lang('first')?>",
                        "sPrevious": "<?=lang('previous')?>",
                        "sNext": "<?=lang('next')?>",
                        "sLast": "<?=lang('last')?>"
                }
        },
        "tableTools": {
                    "sSwfPath": "<?=base_url()?>resource/js/datatables/tableTools/swf/copy_csv_xls_pdf.swf",
              "aButtons": [
                      {
                      "sExtends": "csv",
                      "sTitle": "<?=$this->config->item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "xls",
                      "sTitle": "<?=$this->config->item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "pdf",
                      "sTitle": "<?=$this->config->item('company_name').' - '.lang('invoices')?>"
                  },
              ],
        },
        "aaSorting": [],
        "aoColumnDefs":[{
                    "aTargets": ["no-sort"]
                  , "bSortable": false
              },{
                    "aTargets": ["col-currency"]
                  , "sType": "currency"
              }]    
        });
            $("#table-tickets").dataTable().fnSort([[3,'desc']]);
            $("#table-tickets-archive").dataTable().fnSort([[3,'desc']]);
            $("#table-projects").dataTable().fnSort([[6,'asc']]);
            $("#table-projects-client").dataTable().fnSort([[4,'asc']]);
            $("#table-projects-archive").dataTable().fnSort([[5,'desc']]);
            $("#table-teams").dataTable().fnSort([[1,'asc']]);
            $("#table-milestones").dataTable().fnSort([[3,'desc']]);
            $("#table-milestone").dataTable().fnSort([[2,'desc']]);
            $("#table-tasks").dataTable().fnSort([[2,'desc']]);
            $("#table-files").dataTable().fnSort([[2,'desc']]);
            $("#table-links").dataTable().fnSort([[0,'asc']]);
            $("#table-project-timelog").dataTable().fnSort([[0,'desc']]);
            $("#table-tasks-timelog").dataTable().fnSort([[0,'desc']]);
            $("#table-clients").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-1").dataTable().fnSort([[1,'asc']]);
            $("#table-client-details-2").dataTable().fnSort([[2,'desc']]);
            $("#table-client-details-3").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-4").dataTable().fnSort([[1,'asc']]);
            $("#table-templates-1").dataTable().fnSort([[0,'asc']]); 
            $("#table-templates-2").dataTable().fnSort([[0,'asc']]); 
            $("#table-invoices").dataTable().fnSort([[3,'desc']]);
            $("#table-estimates").dataTable().fnSort([[3,'desc']]);
            $("#table-payments").dataTable().fnSort([[1,'desc']]);
            $("#table-users").dataTable().fnSort([[0,'asc']]);
            $("#table-rates").dataTable().fnSort([[0,'asc']]);
            $("#table-bugs").dataTable().fnSort([[0,'asc']]);
            $("#table-stuff").dataTable().fnSort([[0,'asc']]);
            $("#table-activities").dataTable().fnSort([[0,'desc']]);
            $("#table-strings").DataTable().page.len(-1).draw();
            if ($('#table-strings').length == 1) { $('#table-strings_length, #table-strings_paginate').remove(); $('#table-strings_filter input').css('width','200px'); }


        $('#save-translation').on('click', function (e) {
            e.preventDefault();
            oTable1.fnResetAllFilters();
            $.ajax({
                url: base_url+'settings/translations/save/?settings=translations',
                type: 'POST',
                data: { json : JSON.stringify($('#form-strings').serializeArray()) },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.backup-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_backed_up_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click', '.restore-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_restored_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.submit-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_submitted_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click','.active-translation',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) { isActive = 1; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { active: isActive },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        
        $('[data-rel=tooltip]').tooltip();
});
</script>
<?php }  ?>

<?php if (isset($iconpicker)) { ?>
<script type="text/javascript" src="<?=base_url()?>resource/js/iconpicker/fontawesome-iconpicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#site-icon').iconpicker().on('iconpickerSelected',function(event){ 
                $('#icon-preview').html('<i class="fa '+event.iconpickerValue+'"></i>');
            });
    });
</script>
<?php } ?>

<?php if (isset($sortable)) { ?>
<script type="text/javascript" src="<?=base_url()?>resource/js/sortable/jquery-sortable.js"></script>
<script type="text/javascript">
    var timer;
    $('.sorted_table').sortable({
        cursorAt: { top: 0, left: 0 },
        containerSelector: 'table',
        cursor: 'pointer',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(timer); timer = setTimeout('saveOrder()', 1000); }
    });
    
    function saveOrder() {
        var data = $('.sorted_table').sortable("serialize").get();
        var items = JSON.stringify(data);
        var table = $('.sorted_table').attr('type');
        $.ajax({
            url: "<?=base_url()?>"+table+"/items/reorder/",
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });

    }
</script>
<?php } ?>


<?php if (isset($stripe)) { ?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<?php echo '<script type="text/javascript">Stripe.setPublishableKey("' .config_item('stripe_public_key'). '");</script>'; ?>


<?php } ?>

<?php if (isset($calendar)) { ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/js/bootstrap-modal.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.5/js/bootstrap-modalmanager.min.js"></script>
 <?=$this->load->view('sub_group/calendarjs')?>
<?php } ?>

<?php if (isset($fullcalendar)) { ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/gcal.js"></script>
<?php $lang = lang('lang_code'); if ($lang == 'en') { $lang = 'en-gb'; } ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/lang/<?=$lang?>.js"></script>

<?php
$tasks = $this->db->join('projects','project = project_id')->get('tasks')->result();
$payments = $this->db->join('invoices','invoice = inv_id')->join('companies','paid_by = co_id')->get('payments')->result();
$invoices = $this->db->join('companies','client = co_id')->get('invoices')->result();
$estimates = $this->db->join('companies','client = co_id')->get('estimates')->result();
$projects = $this->db->join('companies','client = co_id')->get('projects')->result();
$gcal_api_key = config_item('gcal_api_key');
$gcal_id = config_item('gcal_id');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            googleCalendarApiKey: '<?=$gcal_api_key?>',
            eventSources: [
                {
                    events: [
                    <?php foreach ($tasks as $t) { ?>
                            {
                                title  : '<?=lang('task')?>: <?= addslashes($t->task_name) ?>',
                                description: '<?= $t->description ?>',
                                start  : '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                url: '<?= base_url('projects/view/' . $t->project . '?group=tasks&view=task&id=' . $t->t_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#7266BA',
                    textColor: 'white'
                },
                {
                    events: [
                    <?php foreach ($payments as $p) { $cur = $this->applib->currencies($p->currency); ?>
                            {
                                title  : '<?=lang('payment')?>: <?=addslashes($p->company_name)."  (".$cur->symbol." ".$p->amount.")"?>',
                                description: '<?= $p->notes ?>',
                                start  : '<?= date('Y-m-d', strtotime($p->payment_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($p->payment_date)) ?>',
                                url: '<?= base_url('invoices/payments/details/' . $p->p_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#78ae54',
                    textColor: 'white'
                },
                {
                    events: [
                    <?php foreach ($invoices as $i) { $cur = $this->applib->currencies($i->currency); ?>
                            {
                                title  : '<?=lang('invoice')?>: <?=$i->reference_no." ".$i->company_name?>',
                                description: '<?= $i->notes ?>',
                                start  : '<?= date('Y-m-d', strtotime($i->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($i->due_date)) ?>',
                                url: '<?= base_url('invoices/view/' . $i->inv_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#DE4E6C',
                    textColor: 'white'
                },
                {
                    events: [
                    <?php foreach ($estimates as $e) { ?>
                            {
                                title  : '<?=lang('estimate')?>: <?=$e->reference_no." ".$e->company_name?>',
                                description: '<?= $e->notes ?>',
                                start  : '<?= date('Y-m-d', strtotime($e->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($e->due_date)) ?>',
                                url: '<?= base_url('estimates/view/' . $e->est_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#E8AE00',
                    textColor: 'white'
                },
                {
                    events: [
                    <?php foreach ($projects as $j) { ?>
                            {
                                title  : '<?=lang('project')?>: <?=$j->project_code." ".$j->company_name?>',
                                description: '<?= $j->description ?>',
                                start  : '<?= date('Y-m-d', strtotime($j->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($j->due_date)) ?>',
                                url: '<?= base_url('projects/view/' . $j->project_id) ?>'
                            },
                    <?php } ?>
                    ],
                    color: '#11a7db',
                    textColor: 'white'
                },
                {
                    googleCalendarId: '<?=$gcal_id?>'
                }
            ]
        });
    });
</script>
<?php } ?>


<?php if (isset($set_fixed_rate)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fixed_rate").click(function(){
            //if checked
            if($("#fixed_rate").is(":checked")){
                $("#fixed_price").show("fast");
                $("#hourly_rate").hide("fast");
                }else{
                    $("#fixed_price").hide("fast");
                    $("#hourly_rate").show("fast");
                }
        });
    });
</script>
<?php } ?>

<?php if (isset($postmark_config)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#use_postmark").click(function(){
            //if checked
            if($("#use_postmark").is(":checked")){
                $("#postmark_config").show("fast");
                }else{
                    $("#postmark_config").hide("fast");
                }
        });
    });
</script>
<?php } ?>

 <?php
if($this->session->flashdata('message')){ 
$message = $this->session->flashdata('message');
$alert = $this->session->flashdata('response_status');
    ?>
<script type="text/javascript">
    $(document).ready(function(){
toastr.<?=$alert?>("<?=$message?>", "<?=lang('response_status')?>");
toastr.options = {
  "closeButton": true,
  "debug": false,
  "positionClass": "toast-bottom-right",
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
});
</script>
<?php } ?>
<?php if (isset($typeahead)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        
        var substringMatcher = function(strs) {
          return function findMatches(q, cb) {
            var matches, substringRegex;
            matches = array();
            substrRegex = new RegExp(q, 'i');
            $.each(strs, function(i, str) {
              if (substrRegex.test(str)) {
                matches.push(str);
              }
            });
            cb(matches);
          };
        };
        var scope = $('#auto-item-name').attr('data-scope');
        
        $('#auto-item-name').on('keyup',function(){ $('#hidden-item-name').val($(this).val()); });
        
        $.ajax({
            url: base_url + scope + '/autoitems/',
            type: "POST",
            data: {},
            success: function(response){
                $('.typeahead').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 2
                    },
                    {
                    name: "item_name",
                    limit: 10,
                    source: substringMatcher(response)
                });
                $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                    $.ajax({
                        url: base_url + scope + '/autoitem/',
                        type: "POST",
                        data: {name: suggestion},
                        success: function(response){
                            $('#hidden-item-name').val(response.item_name);
                            $('#auto-item-desc').val(response.item_desc).trigger('keyup');
                            $('#auto-quantity').val(response.quantity);
                            $('#auto-unit-cost').val(response.unit_cost);
                        }
                    });
                });            
            }
        });
        
        
    });  
</script>
<?php } ?>
