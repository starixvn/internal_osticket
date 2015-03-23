// Validate ticket
$(document).ready(function(){        
    // validate value
    $("#frm_ticket").validate({
        invalidHandler: function(form, validator) {
            var sport_type = $('#sport_chzn').find('a').find('span').html();
            var league_name = $('#league_chzn').find('a').find('span').html();
            var match_name = $('#match_chzn').find('a').find('span').html();
            if(sport_type == 'Select Sport') {
                $('#mess_sport').html('This field is required.');
            } else {
                $('#mess_sport').html('');
            }            
            if(match_name == 'Select Match') {
                $('#mess_match').html('This field is required.');
            } else {
                $('#mess_match').html('');
            }
        },
        submitHandler: function(form) {                
            form.submit();             
        }
     });
});