<?php

require_once '../inc/utils.php';

header('Content-Type: text/javascript');

?>

const units = ['bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
   
function niceBytes(x){

  let l = 0, n = parseInt(x, 10) || 0;

  while(n >= 1024 && ++l){
      n = n/1024;
  }
  
  return(n.toFixed(n < 10 && l > 0 ? 1 : 0) + ' ' + units[l]);
}

var upload = {

	req: null,

    dialog: function() {
        $('#upload_file').click();
       	return false;
    },
    
    prepare: function(file_obj) {
    	$('#exampleModal').on('hidden.bs.modal', event => {
    		console.log('modal event', event);
    		if(upload.req != null) {
    			upload.req.abort();
    			file_obj.value = '';
    			upload.req = null;
    		}
		});
		$('#modal-info').html('');
    	$('#exampleModal').modal('show');

    	if(file_obj.files[0].type != 'application/pdf'){
    		console.log('not a pdf');
			$('#modal-progress').hide();
        	$('#modal-info').html('not a pdf');
    		file_obj.value = "";
    		return false;
    	}
    	if(file_obj.files[0].size > 20 * 1024 * 1024){
    		console.log('file too big');
			$('#modal-progress').hide();
        	$('#modal-info').html('file too big');
    		file_obj.value = "";
    		return false;
    	}
	    var FD = new FormData($('#upload_form')[0]);
	    $(FD).serializeArray();
	    upload.req = $.ajax({
	        url: '/inc/service.php',
	        type: 'POST',
	        data: FD,
	        cache: false,
	        contentType: false,
	        processData: false,
	        success: function(data){
	        	if(data != '') {
			        var result = JSON.parse(data);
			        if(result.err_msg == '') {
			        	document.location.href = '/<?php echo $lang; ?>/docs/' + result.pdf_id;
			        } else {
                		$('#modal-progress').hide();
			        	$('#modal-info').html(result.err_msg);
			        }
			    } else {
			    	console.log('empty result!');
			    }
	        },
	        xhr: function() {
	            var myXhr = $.ajaxSettings.xhr();
	            if (myXhr.upload) {
	                myXhr.upload.addEventListener('progress', function(e) {
                		var percent = parseInt(e.loaded / e.total * 100);
	                	console.log('upload', percent + '%');
                		$('#modal-info').html('Received: ' + niceBytes(e.loaded) + ' / ' +  niceBytes(e.total) + ' (' + percent + '%)');
						$('#modal-progress').show();
                		$('#modal-progress-bar').css({'width': percent + '%'});
                		//$('#modal-progress-bar').html(percent + '%');
	                	if(e.loaded >= e.total) {
	                		$('#modal-progress').hide();
	                		$('#modal-info').html('Preparing your document...');
	                	}
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

