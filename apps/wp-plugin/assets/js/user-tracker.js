jQuery(document).ready(function($) {
    const updateActivity = () => {
        $.ajax({
            url: userTrackerData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'update_user_activity',
                nonce: userTrackerData.nonce
            },
            success: function(response) {
                if (!response.success) {
                    console.error('Activity update failed:', response.data);
                }
            }
        });
    };

    // Update every 3 seconds
    setInterval(updateActivity, userTrackerData.updateInterval);

    // Handle page close/unload
    $(window).on('beforeunload', function() {
        const formData = new FormData();
        formData.append('action', 'user_offline');
        formData.append('nonce', userTrackerData.nonce);

        navigator.sendBeacon(userTrackerData.ajaxUrl, formData);
    });
});