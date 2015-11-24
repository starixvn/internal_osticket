$(document).ready(function() {        
    $('.notifications-list').click(function() {
        if($('.tab-ticket').hasClass('selected')) {
            if($('.notifications-list .dropdown-menu').hasClass('open')) {
                $('.notifications-list .dropdown-menu').removeClass('open');
            } else {
                $('.notifications-list .dropdown-menu').addClass('open');
                var maxHeight = parseInt($('.notifications-list .dropdown-menu').css('max-height')),
                    height = $('.notifications-list .dropdown-menu').outerHeight();
                
                if(height > maxHeight){
                    $('.notifications-list .dropdown-menu').css('overflow', 'scroll');
                    $('.notifications-list .dropdown-menu').css('overflow-x', 'hidden');
                }
            }
        }
    });

    $('.notificationId').click(function() {
        var notificationId = parseInt($(this).attr('notification-id')),
            ticketId = parseInt($(this).attr('ticket-id')),
            staffId = parseInt($(this).attr('staff-id'));
        $.ajax({
            type: "GET",
            url: 'loadAjax.php',
            data: {
                ticket_id: ticketId,
                notification_id: notificationId,
                staff_id: staffId,
                type: 'update_notification'
            },
            dataType: "json",
            success: function(result){
                console.log(result);
                if(result) {
                    window.location.href = "tickets.php?id=" + ticketId;
                }
            },
            error: function(){
            }
        });
    });
});