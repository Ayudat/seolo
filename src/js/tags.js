var seolo_tagTab = $(document).find('title');
var seolo_tagTitle = $('meta[property="og:title"]');
var seolo_tagDescription = $('meta[property="og:description"]');

function seolo_tagsChars()
{
    $('#seolo-tag_tab_chars').text( $('#seolo-tag_tab').val().length );
    $('#seolo-tag_title_chars').text( $('#seolo-tag_title').val().length );
    $('#seolo-tag_description_chars').text( $('#seolo-tag_description').val().length );
}

$(document).ready(function() {
    $('#seolo-tags').click(function() {
        $('#seolo-tag_tab').val(seolo_tagTab.text());
        $('#seolo-tag_title').val(seolo_tagTitle.attr('content'));
        $('#seolo-tag_description').val(seolo_tagDescription.attr('content'));

        seolo_tagsChars();

        $('#seolo-tags-edit .modal-footer .alert').hide();
        $('#seolo-tags-edit .panel-body, #seolo-tags-edit .modal-footer .btn').show();
        $('#seolo-tags-edit').fadeIn();
    });

    $('#seolo-tags-edit .close').click(function() {
        $('#seolo-tags-edit').fadeOut();
    });

    $(document).on('change keyup paste', '#seolo-tag_tab, #seolo-tag_title, #seolo-tag_description', function() {
        seolo_tagsChars();
    });

    $('#seolo-tags-edit #save').click(function() {
        $.ajax({
            url: $('#seolo-tags-edit').data('saveurl'),
            type: 'post',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                route: $('#seolo-tags-edit').data('route'),
                tab: $('#seolo-tag_tab').val(),
                title: $('#seolo-tag_title').val(),
                description: $('#seolo-tag_description').val()
            },
            success: function(result)
            {
                if ('route_error' != result)
                {
                    var data = JSON.parse(result);
                    seolo_tagTab.text(data.tab);
                    seolo_tagTitle.attr('content', data.title);
                    seolo_tagDescription.attr('content', data.description);
                    $('#seolo-tags-edit .modal-footer .alert').fadeIn();
                    setTimeout("$('#seolo-tags-edit').fadeOut()", 2000);
                }
            }
        });
    });

    //$('#seolo-tag').trigger('click');
});
