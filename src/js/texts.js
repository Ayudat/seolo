function seolo_theText()
{
    return $("span.seolo-text[data-key='" + $('#seolo-text').data('key') + "']");
}

$(document).ready(function() {
    $('span.seolo-text').click(function() {
        $('span.seolo-text').removeClass('editing');
        $('#seolo-text .panel-body').removeClass('has-error has-success');
        $(this).addClass('editing');
        $('#seolo-text').
            data('key', $(this).data('key')).
            fadeIn().
            find('textarea').val( $(this).html().replace('<br>', "\n") );
        $('#seolo-text #text-id').text( $(this).data('key') );
        $('#seolo-text #saved').html( $(this).html() );
    });

    $('#seolo-text .close').click(function() {
        seolo_theText().removeClass('editing').html( $('#seolo-text #saved').html() );
        $('#seolo-text').fadeOut();
    });

    $('#seolo-text #save, #seolo-text #test').click(function() {
        var action = $(this).attr('id'); //save or test

        $('#seolo-text .panel-body').removeClass('has-error has-success');

        $.ajax({
            url: $('#seolo-text').data('saveurl'),
            type: 'post',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                key: $('#seolo-text').data('key'),
                content: $('#seolo-text textarea').val(),
                action: action
            },
            success: function(result)
            {
                if ('html_error' == result)
                {
                    $('#seolo-text .panel-body').addClass('has-error');
                } else {
                    $('#seolo-text .panel-body').addClass('has-success');
                    seolo_theText().html( $('#seolo-text textarea').val().replace("\n", '<br>') );
                    if ('save' == action)
                    {
                        seolo_theText().removeClass('editing');
                        $('#seolo-text').fadeOut('fast');
                    }
                    seolo_theText().css('opacity', 0).animate({opacity:1});
                }
            }
        });
    });

    $('#seolo-text #pos').click(function() {
        if (!$('#seolo-text').hasClass('right')) {
            $(this).html('&lt;');
            $('#seolo-text').addClass('right');
        } else {
            $(this).html('&gt;');
            $('#seolo-text').removeClass('right');
        }
    });

    $('#seolo-text .btn').click(function() {
        setTimeout("$('#seolo-text .btn#" + $(this).attr('id') + "').blur()", 256);
    });
});
