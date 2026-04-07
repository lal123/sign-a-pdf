<?php

require_once '../inc/utils.php';

header('Content-Type: text/javascript');

$cache_expire = 60 * 60 * 24 * 30 *3;
header("Pragma: public");
header("Cache-Control: max-age=" . $cache_expire);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT');

?>
const units = <?php echo $tr['UPLOAD.BYTE_UNITS']; ?>;
   
function niceBytes(x) {

    let l = 0, n = parseInt(x, 10) || 0;
    while(n >= 1024 && ++l){
        n = n / 1024;
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
        $('#uploadModal').on('hidden.bs.modal', event => {
            if(upload.req != null) {
                upload.req.abort();
                file_obj.value = '';
                upload.req = null;
            }
        });
        $('#modal-info').html('');
        $('#uploadModal').modal('show');
        if(file_obj.files[0].type != 'application/pdf'){
            upload.warn(decodeURIComponent('<?php echo rawurlencode($tr['UPLOAD.NOT_A_PDF']); ?>'), true);
            file_obj.value = "";
            return false;
        }
        if(file_obj.files[0].size > 20 * 1024 * 1024){
            upload.warn(decodeURIComponent('<?php echo rawurlencode($tr['UPLOAD.FILE_TOO_BIG']); ?>'), true);
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
                        document.location.href = '/<?php echo $lang; ?>/docs/' + result.pdf_id + '/';
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
                        $('#modal-info').html('<?php echo $tr['UPLOAD.BYTES_RECEIVED']; ?> :&nbsp; ' + niceBytes(e.loaded) + ' / ' +  niceBytes(e.total) + ' (' + percent + '%)');
                                        $('#modal-progress').show();
                        $('#modal-progress-bar').css({'width': percent + '%'});
                        //$('#modal-progress-bar').html(percent + '%');
                        if(e.loaded >= e.total) {
                            upload.warn('<?php echo $tr['UPLOAD.PREPARING_DOC']; ?>', false);
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
    
    warn: function(message_text, hide_progress) {
        $('#modal-info').html(message_text);
        if(hide_progress) {
            $('#modal-progress').hide();
        }
    },
    
    info: function(text) {
    }
    
}

var docs = {

    confirmDelete: function(pdf_id) {
        $('#deleteDocModal #actionConfirm').on('click', event => {
            $('#deleteDocModal').modal('hide');
            docs.delete(pdf_id);
        });
        $('#deleteDocModal').modal('show');
        return false;
    },

    delete: function(pdf_id) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: {'action': 'delete_doc', 'pdf_id': pdf_id, 'lang': '<?php echo $lang; ?>'}
        }).done(function(data) {
            eval(data);
        });
        return false;
    },

    sendSignDocForm: function(sign_inc) {
        var data = $('#signDocForm').serializeArray();  
        var vals = {};
        vals['sign_inc'] = sign_inc;
        var err = false;
        $.each(data, function(i, field) {
            vals[field.name] = field.value;
        });
        if(vals['sign_step'] == '1') {
            if(vals['sign_text'] == '') {
                $('#signText').addClass('is-invalid');
                err = true;
            }
        } else if((vals['sign_step'] == '3') && (vals['page_option'] == '3') && (vals['sign_inc'] == 1)) {
            if(vals['sign_pages'] == '') {
                err = true;
            } else {
                var sp = vals['sign_pages'].trim().split(/[ ,]+/)
                for(var i = 0 ; i< sp.length ; i++) {
                    if(!sp[i].match(/^([1-9]\d*)(\-[1-9]\d*)?$/)) {
                        err = true;
                    }
                }
            }
            if(err == true) {
                $('#signPages').addClass('is-invalid');
            }

        }
        if(err == false) {
            return docs.getSignStep(vals);
        }
        return false;
    },

    getSignStep: function(data) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: data
        }).done(function(data) {
            eval(data);
        });
        return false;
    },

    showSignPanel: function(sign_option) {
        $('.form-panel').hide();
        $('#formPanel' + sign_option).show();
        return true;
    }
}

var account = {

     confirmDelete: function(user_id) {
        $('#deleteAccountModal #actionConfirm').on('click', event => {
            $('#deleteAccountModal').modal('hide');
            account.delete(user_id);
        });
        $('#deleteAccountModal').modal('show');
        return false;
    },

    delete: function(user_id) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: {'action': 'delete_account', 'user_id': user_id, 'lang': '<?php echo $lang; ?>'}
        }).done(function(data) {
            eval(data);
        });
        return false;
    }
}