$(document).ready(function() {
    var errorHandler = function(event, id, fileName, reason) {
        //qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };
    var intCount = 0;
    var intTotal = 0;
    var name_attachment = '';
    var uploader2 = new qq.FineUploader({
        element: $('#manualUploadModeExample')[0],
        autoUpload: false,
        uploadButtonText: "Select Files",
        text: {
                uploadButton: '<i class="icon-plus icon-white"></i> Select Files To Upload'
          },
        request: {
            endpoint: "upload_file_attachment.php",
            inputName: 'qqfile'                 
        },
        validation: {
            //allowedExtensions: ['jpeg', 'jpg', 'txt'],
            sizeLimit: 2097152 // 50 kB = 50 * 1024 bytes
        },
        callbacks: {
            onError: errorHandler,
            onComplete: function(id, fileName, responseJSON){         
                intCount++;             
                name_attachment_new = $('#attachment_name').val();
                if(responseJSON.file_name != 'undifined') {
                    $('#attachment_name').val(name_attachment_new + responseJSON.file_name + ',');
                }
                // submit form after finish
                if(intCount == intTotal) {                    
                    $('#frm_ticket').submit();
                    intCount = 0;
                }
            }
        },
         showMessage: function(message) {
            // Using Twitter Bootstrap's classes and jQuery selector and method
            $('#manualUploadModeExample').append('<div class="alert alert-error">' + message + '</div>');
          },
        debug: true
    });

    $('#insert_ticket').click(function() {
        intTotal = $('ul.qq-upload-list').find('li').size();       
        if(intTotal != 0) {
            var sport_type = $('#sport_chzn').find('a').find('span').html();
            var league_name = $('#league_chzn').find('a').find('span').html();
            var match_name = $('#match_chzn').find('a').find('span').html();
            if($('#note').val() != '' && $('#name').val() != '' && $('#subject').val() != ''
                && sport_type != 'Select Sport' && league_name != 'Select League' 
                && match_name != 'Select Match' && $('#issue').val() != '' 
                && $('#source').val() != '' && $('#deptId').val() != '') {
                uploader2.uploadStoredFiles();
            } else {
                $('#frm_ticket').submit();
            }            
        } else {
            	$('#frm_ticket').submit();
        }
    });
});

// delete attachment which added in a ticket
function delete_file(attachment_id, current_attachment_name)
{
    $('.file_'+attachment_id).remove();
    name_attachment = $('#attachment_name').val();
    name_attachment = name_attachment.replace(current_attachment_name, '');
    $('#attachment_name').val(name_attachment);

}
