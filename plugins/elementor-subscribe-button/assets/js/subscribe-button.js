jQuery(document).ready(function($) {
    $(document).on('click', '.esb-subscribe-button', function(e) {
        e.preventDefault();
        var button = $(this);

        button.prop('disabled', true);

        $.ajax({
            url: esb_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'esb_handle_subscribe',
                nonce: esb_ajax_object.nonce,
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.subscribed) {
                        button.text('Unsubscribe');
                    } else {
                        button.text('Subscribe');
                    }
                    // Simple animation
                    button.fadeOut(100).fadeIn(100);
                } else {
                    alert('Error: ' + response.data);
                }
                button.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                alert('An unexpected error occurred.');
                button.prop('disabled', false);
            }
        });
    });
});
