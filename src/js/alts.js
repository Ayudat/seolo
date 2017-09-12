function seolo_alt_tooltip(obj, show)
{
    var alt = obj.attr('alt');
    if (!alt)
    {
        obj.tooltip('destroy');
    } else {
        if ('tooltip' == obj.data('toggle'))
        {
            obj.attr('title', ''+alt).tooltip('fixTitle');
            if (show) obj.tooltip('show');
        } else {
            obj.data('toggle', 'tooltip').data('placement', 'bottom').attr('title', ''+alt).tooltip({
				template: '<div class="tooltip seolo-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
			});
        }
    }
}

$(document).ready(function() {
    $('img.seolo').each(function() {
        //if the img has an anchor as parent, disable it
        var parent = $(this).parent();
        if (parent.attr('href')) parent.attr('href', 'javascript:void(0)');

        seolo_alt_tooltip($(this), false);

        $('#seolo-alt .alert').hide();

        $(this).click(function() {
            $('.tooltip').fadeOut();
            $('#seolo-alt input').val( $(this).attr('alt') );
            $('#seolo-alt #alt-id').text( $(this).data('seolokey') );
            $('#seolo-alt').
                data('key', $(this).data('seolokey')).
                find('input').val( $(this).attr('alt') );

            $('#seolo-alt').modal({backdrop: 'static'});
            //$('#seolo-alt').modal();
        });
    });

    $('#seolo-alt .close').click(function() {
        $('#seolo-alt').modal('hide');
    });

    $('#seolo-alt #save').click(function() {
        $.ajax({
            url: $('#seolo-alt').data('saveurl'),
            type: 'post',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                key: $('#seolo-alt').data('key'),
                content: $('#seolo-alt input').val()
            },
            success: function(result)
            {
                var theImg = $("img[data-seolokey='" + $('#seolo-alt').data('key') + "']");
                theImg.attr('alt', result);
                seolo_alt_tooltip(theImg, true);
                setTimeout("$('img.seolo').tooltip('hide')", 2000);
                $('#seolo-alt').modal('hide');
            }
        });
    });
});
