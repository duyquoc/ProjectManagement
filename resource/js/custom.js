$(document).ready(function () {

    // Initialize page timers
    update_timer();

    // Selects the first tab of a group (otherwise none is selected)
    $('.nav-tabs li a').first().tab('show');

    // Initialize tooltip (probably obsolete)
    $('[data-toggle="tooltip"]').tooltip();

    // Resize current autoresizable textareas
    $('textarea.js-auto-size').textareaAutoSize();

    // Show/hide password on click
    $('#link-password').on('click', function () {
        if ($(this).attr('type') == 'password') {
            $(this).attr('type', 'text').blur();
        } else {
            $(this).attr('type', 'password').blur();
        }
    });
    
    // select2 
    if ($.fn.select2) {
        $(".select2-option").select2();
        $("#select2-tags").select2({
          tags:["red", "green", "blue"],
          tokenSeparators: [",", " "]}
        );
    }
    
    $('#add-translation').on('click', function () {
        var lang = $('#add-language').val();
        window.location.href = base_url+'settings/translations/add/'+lang+'/?settings=translations';
    });
    
    
});

function textarea_resize(el) {
    var lines = $(el).val().split(/\r\n|\r|\n/).length;
    var height = ((lines * 34) - ((lines - 1) * 10));
    $(el).css('height', height + 'px');
}

function update_timer() {
    $('.timer').each(function () {
        var time_start = $(this).attr('start');
        var timestamp = Math.floor(Date.now() / 1000);
        var passed = timestamp - time_start;
        var seconds = "0" + passed % 60;
        var minutes = "0" + (Math.floor(passed / 60) % 60);
        var hours = Math.floor(passed / 3600);
        var formattedTime = hours + ':' + minutes.substr(minutes.length - 2);
        $(this).find('span').html(formattedTime);
    });
    if ($('.timer').length > 0) {
        setTimeout('update_timer()', 1000);
    }
}
