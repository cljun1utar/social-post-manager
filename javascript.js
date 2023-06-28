jQuery(document).ready(function($) {
    $('#logout-button').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'destroy_session'
            },
            success: function(response) {
                // redirect the user to the login page or display a message
                window.location.href = '/login';
            }
        });
    });
});