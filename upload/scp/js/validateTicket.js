// Validate ticket
$(document).ready(function(){        
    // validate value
    $("#frm_ticket").validate({
        invalidHandler: function(form, validator) {
			var depts = $('.departments-select').val();
            var sport = $('#sport').val();
            var league = $('#league').val();
            var match_name = $('#match_chzn').find('a').find('span').html();
			
			if(depts) {
				$('#mess_department').html('');
			}
			else {
				$('#mess_department').html('This field is required.');
			}
            if(sport) {
                $('#mess_sport').html('');
            } else {
                $('#mess_sport').html('This field is required.');
            }
			if(league) {
				$('#mess_league').html('');
            } else {
                $('#mess_league').html('This field is required.');
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