setInterval(() => {
    $.ajax({
        url: '/refresh-session',
        type: 'GET',
        success: function (data) {
            // Update the CSRF token in the meta tag and any form hidden input fields
            $('meta[name="csrf-token"]').attr('content', data.token);
            $('input[name="_token"]').val(data.token);
            console.log('Session and CSRF token refreshed successfully');
        },
        error: function (error) {
            console.error('Error refreshing session:', error);
        }
    });
}, 600000);
