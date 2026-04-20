<?php

require_once '../inc/utils.php';

header('Content-Type: text/javascript');

$cache_expire = 60 * 60 * 24 * 30 * 3;
header("Pragma: public");
header("Cache-Control: max-age=" . $cache_expire);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT');

?>
var lang = '<?php echo $lang ?>';

var upload = {

    req: null,
    byte_units: <?php echo $tr['UPLOAD.BYTE_UNITS']; ?>,
   
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
            if(docs.conv != null) {
                clearTimeout(docs.conv);
                docs.conv = null;
                docs.delete(docs.pdf_id, 0);
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
        docs.pdf_id = null;
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
                        docs.pdf_id = result.pdf_id;
                        docs.convert(docs.pdf_id, 0, result.pages);
                    } else {
                        $('#modal-progress').hide();
                        $('#modal-info').html(result.err_msg);
                    }
                } else {
                }
            },
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        var percent = parseInt(e.loaded / e.total * 100);
                        $('#modal-info').html('<?php echo $tr['UPLOAD.BYTES_RECEIVED']; ?> :&nbsp; ' + upload.show_bytes(e.loaded) + ' / ' +  upload.show_bytes(e.total) + '&nbsp; (' + percent + '%)');
                        $('#modal-progress').show();
                        $('#modal-progress-bar').css({'width': percent + '%'});
                        $('#modal-progress').show();
                        if(e.loaded >= e.total) {
                            $('#modal-progress-bar').css({'width': '0px'});
                            upload.warn('<?php echo $tr['UPLOAD.WAITING_MSG']; ?>', false);
                        }
                    });
                }
                return myXhr;
            }
        });
        return false;
    },
    
    warn: function(message_text, hide_progress) {
        $('#modal-info').html(message_text);
        if(hide_progress) {
            $('#modal-progress').hide();
        }
    },
    
    info: function(text) {
    },
    
    show_bytes: function(x) {
        let l = 0, n = parseInt(x, 10) || 0;
        while(n >= 1024 && ++l) {
            n = n / 1024;
        }
        return(n.toFixed(n < 10 && l > 0 ? 1 : 0) + ' ' + upload.byte_units[l]);
    }

}

var docs = {

    conv: null,
    pdf_id: null,
    req: null,
    preload: null,
    compNum: null,

    convert: function(pdf_id, signed, pages) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: {'action': 'convert_doc', 'pdf_id': pdf_id, 'signed': signed, 'pages': pages, 'lang': lang}
        }).done(function(data) {
            eval(data);
        });
    },

    confirmDelete: function(pdf_id) {
        $('#deleteDocModal #actionConfirm').on('click', event => {
            $('#deleteDocModal').modal('hide');
            docs.delete(pdf_id, 1);
        });
        $('#deleteDocModal').modal('show');
        return false;
    },

    delete: function(pdf_id, redirect) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: {'action': 'delete_doc', 'pdf_id': pdf_id, 'redirect': redirect, 'lang': lang}
        }).done(function(data) {
            eval(data);
        });
        return false;
    },

    initChangePage: function(page) {
        docs.compNum = 0;
        docs.preload = [];
        docs.changePage(page);
    },

    changePage: function(page) {
        var pages_numb = $('.page-container').length;
        if((page < 1 ) || (page > pages_numb)) return false; 
        $.each($('.page-container'), function(index, item) {
            if(!docs.preload.hasOwnProperty(index)) {
                docs.preload[index] = new Image();
                docs.preload[index].src = $(item).find('img.page-preview').attr('src');
                docs.preload[index].onload = function() {
                    docs.compNum++;                
                }
            }
        });
        if(docs.compNum < pages_numb) {
            setTimeout('docs.changePage(' + page + ')', 250);
        } else {
            var target_page = $('.page-container').eq(page - 1);
            $('html, body').animate({scrollTop: (target_page.position().top - 220) + 'px'}, 'fast', function(){});
        }
        return false;
    },

    adaptNavBar:function() {
        var docPos = $('html, body').scrollTop();
        var page = 0;
        $.each($('.page-container'), function(index, item) {
            var pagePos = $(item).position().top;
            if(pagePos > (docPos + 230)) {
                return false;
            }
            page++;
        });
        $('#navPage').val(Math.max(page, 1));
        return false;
    },

    prepareSignFile: function(file_obj) {
        $('#signFile').removeClass('is-invalid');
        var file_type = file_obj.files[0].type;
        var file_size = file_obj.files[0].size;
        if((file_type != 'image/gif') && (file_type != 'image/jpeg') && (file_type != 'image/png')) {
            $('#signFile').addClass('is-invalid');
            file_obj.value = "";
            return false;
        }
        if(file_size > 1 * 1024 * 1024){
            $('#signFile').addClass('is-invalid');
            file_obj.value = "";
            return false;
        }
        return false;
    },

    sendSignDocForm: function(sign_inc) {
        var data = $('#signDocForm').serializeArray();  
        var vals = {};
        err = false;
        vals['sign_inc'] = sign_inc;
        var err = false;
        $.each(data, function(i, field) {
            vals[field.name] = field.value;
        });
        if(vals['sign_step'] == '1') {
            if(vals['sign_option'] == 2) {
                if($('#signFile').val() == '') {
                    $('#signFile').addClass('is-invalid');
                    err = true;
                } else if($('#signFile').hasClass('is-invalid')){
                    err = true;
                } else {
                    data = new FormData($('#signDocForm')[0]);
                    $(data).serializeArray();
                    return docs.getSignStep(data);
                }
            } else if(vals['sign_option'] == 3) {
                if(vals['sign_text'] == '') {
                    $('#signText').addClass('is-invalid');
                    err = true;
                } else {
                    data = new FormData($('#signDocForm')[0]);
                    $(data).serializeArray();
                    return docs.getSignStep(data);
                }
            }
        } else if((vals['sign_step'] == '2')) {
            if((vals['sign_option'] == 1) && (vals['sign_inc'] == 1)) {
                vals['sign_data'] = sign.canvas.toDataURL();
                vals['sign_width'] = sign.canvas.width;
                vals['sign_height'] = sign.canvas.height;
                //err = true;
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

    initSign: function(pdf_id) {
        return docs.getSignStep({'action': 'get_sign_step', 'pdf_id': pdf_id, 'sign_step': 0, 'sign_inc': 1, 'sign_option': 4, 'page_option': 1, 'sign_pages': '', 'lang': lang})
    },

    prepareDownload: function(pdf_id) {
        $('#downloadDocModal').on('hidden.bs.modal', event => {
            if(docs.req != null) {
                docs.req.abort();
                docs.req = null;
            }
        });
        $('#downloadDocModal').modal('show');
        data = {'action': 'doc_download', 'pdf_id': pdf_id, 'lang': lang};
        docs.req = $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: data
        }).done(function(data) {
            eval(data);
        });
        return false;
    },

    download: function(pdf_id) {
        document.location.href = '/inc/download.php?file=' + pdf_id + '&lang=' + lang;
        return false;
    }
}

var sign = {

    canvas: null,
    c: {f: false, x: null, y: null},
    prevObjId: null,

    adjust: function(pdf_id, signed_pdf_id, sign_id, page_option, sign_pages, sign_width, sign_height) {
        if(page_option == 2) {
            var target_pages = [0];    
        } else if(page_option == 3) {
            var sp = sign_pages.trim().split(/[ ,]+/)
            var target_pages = [];
            for(var i = 0 ; i< sp.length ; i++) {
                if(sp[i].match(/^([1-9]\d*)(\-[1-9]\d*)?$/)) {
                    var num_page = parseInt(sp[i]) - 1;
                    target_pages[i] = num_page;    
                }
            }
        } else {
            var target_pages = [$('.page-container').length - 1];    
        }
        if(target_pages[0] >= $('.page-container').length) {
            target_pages[0] = 0;
        }
        var target_page = $('.page-container').eq(target_pages[0]);
        var page_width = target_page.width();
        var page_height = target_page.height();
        var sign_ratio = sign_width / sign_height;
        if(sign_width > ((page_width - 100) / 3)) {
            sign_width = (page_width - 100) / 3;
            sign_height = parseInt(sign_width / sign_ratio);
        } else if(sign_height > ((page_height - 100) / 3)) {
            sign_height = (page_height - 100) / 3;
            sign_width = parseInt(sign_height * sign_ratio);
        }
        var page_id = target_page.attr('id');
        var signPreview = $('<div></div')
            .attr('id', 'signPreview');
        signPreview.html('<div class="helper ne"></div><div class="helper nw"></div><div class="helper sw"></div><div class="helper se"></div><div class="sign_cmd_bar"><span><a class="close bi bi-x-circle-fill" onmousedown="$(\'#signPreview\').remove(); return false;"></a><a class="check bi bi-check-circle-fill" onmousedown="return sign.validate({\'pdf_id\': \'' + pdf_id + '\', \'signed_pdf_id\': \'' + signed_pdf_id + '\', \'page_id\': \'' + page_id + '\', \'sign_id\': \'' + sign_id + '\', \'page_option\': \'' + page_option + '\', \'sign_pages\': \'' + sign_pages + '\'}); return false;"></a></span></div>');
        target_page.find('.page-content').append(signPreview);
        $('#signPreview').css({'display': 'inline-block', 'background-image': 'url(\'/uploads/sign/' + sign_id +'.png\'', 'width': sign_width + 'px', 'height': sign_height +'px'});
        $('#signPreview').resizable({handles: 'n,s,e,w,ne,se,nw,sw', stop: function (event, ui) { sign.moved(event, ui); }}).draggable({stop: function (event, ui) { sign.moved(event, ui); }});
        $('html, body').animate({scrollTop: (target_page.position().top + target_page.height() - sign_height - 120)+ 'px'}, 'fast', function(){});
    },

    confirmDelete: function(sign_file_id) {
        $('#deleteSignModal #actionConfirm').on('click', event => {
            $('#deleteSignModal').modal('hide');
            sign.delete(sign_file_id, 1);
        });
        $('#deleteSignModal').modal('show');
        return false;
    },

    delete: function(sign_file_id, redirect) {
        $.ajax({
            url: '/inc/service.php',
            type: 'POST',
            data: {'action': 'delete_sign', 'sign_file_id': sign_file_id, 'lang': lang}
        }).done(function(data) {
            eval(data);
        });
        return false;
    },

    download: function(sign_file_id, sign_name) {
        document.location.href = '/inc/download_sign.php?sign_file=' + sign_file_id + '&sign_name=' + sign_name + '&lang=' + lang;
        return false;
    },

    moved: function(event, ui) {
    },

    validate: function(vals) {
        $("*").css("cursor", "progress");
        var page = $('#' + vals['page_id'] + ' > .page-content > img');
        var sign = $('#signPreview');
        var data = {'action': 'sign_page', 'pdf_id': vals['pdf_id'], 'signed_pdf_id': vals['signed_pdf_id'], 'page_id': vals['page_id'], 'sign_id': vals['sign_id'], 'page_option': vals['page_option'], 'sign_pages': vals['sign_pages'], 'page_w': page.width(), 'page_h': page.height(), 'sign_w': sign.width(), 'sign_h': sign.height(), 'sign_x': sign.position().left, 'sign_y': sign.position().top, 'lang': lang}
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
    },

    drawCanvas: function(x, y) {
        if((sign.c.x != null) && (sign.c.y != null)) {
            ctx = sign.canvas.getContext("2d");
            ctx.lineWidth = sign.canvas.thickness;
            ctx.beginPath();
            ctx.moveTo(sign.c.x, sign.c.y);
            ctx.lineTo(x, y);
            ctx.strokeStyle = sign.canvas.color;
            ctx.stroke();
        }
        sign.c.x = x;
        sign.c.y = y;
    },

    clearCanvas: function() {
        ctx = sign.canvas.getContext("2d");
        ctx.clearRect(0, 0, sign.canvas.width, sign.canvas.height);
        return false;
    },

    loadCanvas: function(img_url) {
        ctx = sign.canvas.getContext("2d");
        var img = new Image();
        img.onload = function () {
            ctx.drawImage(img, 0, 0);
        };
        img.src = img_url;
        return false;
    },

    downloadCanvas() {
        const imageSrc = sign.canvas.toDataURL();
        const a = document.createElement('a');
        a.href = imageSrc;
        a.download = 'image.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    },

    initCanvas: function(c, t) {
        sign.canvas = document.getElementById("signCanvas");
        sign.canvas.width = $('#signCanvas').width();
        sign.canvas.height = $('#signCanvas').height();
        sign.canvas.color = c;
        sign.canvas.thickness = t;
        sign.canvas.addEventListener("mousedown", function (e) { sign.drawCanvas(e.offsetX, e.offsetY); sign.c.f = true; });
        sign.canvas.addEventListener("touchstart", function (e) { var rect = e.target.getBoundingClientRect(); for (const changedTouch of event.changedTouches) { sign.drawCanvas(changedTouch.pageX - rect.left, changedTouch.pageY - rect.top); } sign.c.f = true; });
        sign.canvas.addEventListener("mousemove", function (e) { if(!sign.c.f) { return false; } sign.drawCanvas(e.offsetX, e.offsetY); }); sign.canvas.addEventListener("touchmove", function (e) { if(!sign.c.f) { return false; } var rect = e.target.getBoundingClientRect(); for (const changedTouch of event.changedTouches) { sign.drawCanvas(changedTouch.pageX - rect.left, changedTouch.pageY - rect.top); } });
        sign.canvas.addEventListener("mouseover", function (e) { if(!sign.c.f) { return false; } else { sign.c.x = e.offsetX; sign.c.y = e.offsetY; } sign.drawCanvas(e.offsetX, e.offsetY); });
        sign.canvas.addEventListener("mouseup", function (e) { sign.c = {f: false, x: null, y: null}; });
        sign.canvas.addEventListener("touchend", function (e) { sign.c = {f: false, x: null, y: null}; });
        sign.canvas.addEventListener("touchcancel", function (e) { sign.c = {f: false, x: null, y: null}; });
        return false;
    },

    showColorPicker: function(obj, pos) {
        if(pos == 'top') {
            var x = $(obj).position().left + $(obj).width() / 2 - $('#colorpicker').width() / 2 - 2; 
            var y = Math.max($(obj).position().top - $('#colorpicker').height() - 22, 0);
        } else if (pos == 'right') {
            var x = $(obj).position().left + $(obj).width() + 10; 
            var y = $(obj).position().top + $(obj).height() / 2 - $('#colorpicker').height() / 2 - 8;
        }
        $('#colorpicker').removeClass('top').removeClass('right').addClass(pos);
        $('#colorpicker').css({'left': x + 'px', 'top': y + 'px'});
        $('#colorpicker').toggle();
        return false;
    },

    hideColorPicker: function() {
        $('#colorpicker').hide();
        return false;
    },

    showFontList: function(obj) {
        var x = $(obj).position().left + parseInt($(obj).width() / 2 - $('#textFontList').width() / 2)- 2; 
        var y = Math.max($(obj).position().top - $('#textFontList').height() - 2, 0);
        $('#textFontList').css({'left': x + 'px', 'top': y + 'px'});
        $('#textFontList').animate({scrollTop: '0px'}, 0);
        var col = $('#textColor').val();
        $('#textFontList > .fontItem').css({'background-color': '#ffffff', 'color' : col});
        $('#textFontList > .fontItem[font_filename=' + $('#textFont').val() + ']').css({'background-color': col, 'color': '#ffffff'});
        $('#textFontList').toggle();
        var font_y = Math.max($('#textFontList > .fontItem[font_filename=' + $('#textFont').val() + ']').position().top - parseInt($('#textFontList').height() / 2)  + parseInt($('#textFontList > .fontItem[font_filename=' + $('#textFont').val() + ']').height() / 2) + $('#textFontList').scrollTop(), 0);
        $('#textFontList').animate({scrollTop: font_y + 'px'}, 0);
        return false;
    },

    hideFontList: function() {
        $('#textFontList').hide();
        return false;
    },

    selectFont: function(font_filename, font_family, font_size, line_height, font_name) {
        $('#textFont').val(font_filename);
        $('#textFontPreview').css({'font-family': font_family, 'font-size': font_size, 'line-height': line_height});
        $('#textFontPreview').html(font_name);
        sign.hideFontList();
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
            data: {'action': 'delete_account', 'user_id': user_id, 'lang': lang}
        }).done(function(data) {
            eval(data);
        });
        return false;
    }
}

