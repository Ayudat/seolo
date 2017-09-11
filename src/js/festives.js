$(document).ready(function() {
    $('#seolo-festives').click(function() {
        $.ajax({
            url: $('#seolo-festives-edit').data('geturl'),
            success: function(result)
            {
                $('#seolo-txtfestives').val(result).
                    closest('.form-group').removeClass('has-error').
                    find('.help-block').html('');

                $('#seolo-festives-edit .modal-footer .alert').hide();
                $('#seolo-festives-edit .panel-body, #seolo-festives-edit .modal-footer .btn').show();
                $('#seolo-festives-edit').fadeIn(function() {
                    var o = $('#seolo-txtfestives');
                    var len = o.val().length;
                    o.focus();
                    o[0].setSelectionRange(len, len);
                    o.scrollTop(o[0].scrollHeight);
                });
            }
        });
    });

    $('#seolo-festives-edit .close').click(function() {
        $('#seolo-festives-edit').fadeOut();
    });

    $('#seolo-festives-edit #save').click(function() {
        $.ajax({
            url: $('#seolo-festives-edit').data('saveurl'),
            type: 'post',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                seolo_txtfestives: $('#seolo-txtfestives').val()
            },
            success: function(result)
            {
                if (result) //any echo is an error
                {
                    $('#seolo-txtfestives').
                        closest('.form-group').addClass('has-error').
                        find('.help-block').html(result);
                } else {
                    //if there is an error of a previous try, quit
                    $('#seolo-txtfestives').
                        closest('.form-group').removeClass('has-error').
                        find('.help-block').html('');

                    //show ok
                    $('#seolo-festives-edit .modal-footer .alert').fadeIn();
                    setTimeout("$('#seolo-festives-edit').fadeOut();", 2000);
                }
            }
        });
    });

    //$('#seolo-festives').trigger('click');
});
