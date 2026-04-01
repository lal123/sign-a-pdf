<?php

require_once '../inc/utils.php';

header('Content-Type: text/javascript');

?>
var upload = {

    dialog: function() {
        $('#upload_file').click();
       	return false;
    },
    
    prepare: function() {
	    var FD = new FormData($('#upload_form')[0]);
	    $(FD).serializeArray();
	    $.ajax({
	        url: '/inc/service.php',
	        type: 'POST',
	        data: FD,
	        cache: false,
	        contentType: false,
	        processData: false,
	        success: function(data){
		        var result = JSON.parse(data);
		        document.location.href = './?pdf_id=' + result.pdf_id;
	        },
	        xhr: function() {
	            var myXhr = $.ajaxSettings.xhr();
	            if (myXhr.upload) {
	                myXhr.upload.addEventListener('progress', function(e) {
	                	console.log('upload', e.loaded , e.total);
	                });
	            }
	            return myXhr;
	        }
		});
        return false;
    },
    
    dispose: function(pdf_id) {
    	console.log('PDF id : ' + pdf_id);
    },
    
    remove: function() {
    },
    
    share: function() {
    },
    
    warn: function(text) {
    },
    
    info: function(text) {
    }
    
}

