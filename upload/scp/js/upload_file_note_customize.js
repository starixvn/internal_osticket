$(document).ready(function() {
    var errorHandler = function(event, id, fileName, reason) {
        //qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };
    var intCount = 0;
    var intTotal = 0;
    var name_attachment = '';
    var uploader2 = new qq.FineUploader({
        element: $('#manualUploadNote')[0],
        autoUpload: false,
        uploadButtonText: "Select Files",
        text: {
                uploadButton: '<i class="icon-plus icon-white"></i> Select Files To Upload'
          },
        request: {
            endpoint: "upload_file_note.php",
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
                name_attachment_new = $('#attachment_name_note').val();
                if(responseJSON.file_name != 'undifined') {
                    $('#attachment_name_note').val(name_attachment_new + responseJSON.file_name + ',');
                }
                // submit form after finish
                if(intCount == intTotal) {                    
                    $('#frm-note').submit();
                    intCount = 0;
                }
            }
        },
         showMessage: function(message) {
            // Using Twitter Bootstrap's classes and jQuery selector and method
            $('#manualUploadNote').append('<div class="alert alert-error">' + message + '</div>');
          },
        debug: true
    });

    $('#insert-note').click(function() {
        intTotal = $('ul.qq-upload-list').find('li').size();       
        if(intTotal != 0) {
           uploader2.uploadStoredFiles();          
        } else {
            	$('#frm-note').submit();
        }
    });
});