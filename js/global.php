<?php

require_once '../inc/constant.php';

header('Content-Type: text/javascript');

?>
var upload = {

    dialog: function() {
        
        $('#upload_file').click();
        
        return false;
        
    },
    
    prepare: function() {
        
        $('#upload_form').submit();
        
        return false;
    },
    
    dispose: function(img_id, img_w, img_h) {
        
        var img_html = '<img src="<?php echo UPLOAD_DIR; ?>/' + img_id + '.gif" width="' + img_w + '" height="' + img_h + '" border="0" alt="" />';
        var adjust_x = 8;
        var adjust_y = parseInt(img_h/2);
        $('#toon_list_container').removeClass('selected');
        var toon_html = '<span class="toon-item selected"><span class="write-point" style="background-position: ' + (adjust_x - 8) + 'px ' + (adjust_y - 8) + 'px;"></span>' + img_html + '</span>';
        $('#toon_list_container').html(toon_html);
        $('#hidden_adjust_x').val(adjust_x);
        $('#hidden_adjust_y').val(adjust_y);
        $('#hidden_toon_img_id').val(img_id);
        if((img_w > 94) || (img_h > 94)) {
            if(img_w > img_h) {
                img_h = parseInt(img_h / img_w * 94);
                img_w = 94;
            } else {
                img_w = parseInt(img_w / img_h * 94);
                img_h = 94;
            }
        }
        $('#step4.step-bubble .part.middle .content.toon-preview').html('<span class="toon-item selected"></span>');
        $('#step4.step-bubble .part.middle .content.toon-preview .toon-item').html(img_html);
        $('#step4.step-bubble .part.middle .content.toon-preview .toon-item').css({'width': img_w + 'px', 'height': img_h + 'px'});
        $('#step4.step-bubble .part.middle .content.toon-preview .toon-item img').css({'width': img_w + 'px', 'height': img_h + 'px'});
        $('#step4.step-bubble .toon-list .toon-item > .write-point').click(function(e) {
            var toon = $(this).parent();
            var toon_id = toon.attr('toon_id');
            var offset = $(this).offset();
            var adjust_x = parseInt(e.pageX - offset.left);
            var adjust_y = parseInt(e.pageY - offset.top);
            $.ajax({'method': 'POST', 'url': 'inc/service.php', 'data': {'action' : 'write_point', 'toon_id': toon_id, 'adjust_x': adjust_x, 'adjust_y': adjust_y}}).done(function(data) {
                eval(data);
            });
        });
        
        return false;
    },
    
    remove: function() {
        var toonSpan = $('#step4 .part.middle .content.toon-list .toon-item[toon_id=' + toons.toon_id + ']');
        if(toonSpan.length != 0) {
            var version = $('#hidden_version').val();
            var toon_id = (toonSpan.eq(0).attr('toon_id'));
            $.ajax({'method': 'POST', 'url': 'inc/service.php', 'data': {'action' : 'remove_toon', 'version': version, 'toon_id': toon_id}}).done(function(data) {
                eval(data);
            });
        }
    },
    
    share: function() {
        var toonSpan = $('#step4 .part.middle .content.toon-list .toon-item[toon_id=' + toons.toon_id + ']');
        if(toonSpan.length != 0) {
            var version = $('#hidden_version').val();
            var toon_id = (toonSpan.eq(0).attr('toon_id'));
            var toon_shared = 1 - parseInt(toonSpan.eq(0).attr('toon_shared'));
            $.ajax({'method': 'POST', 'url': 'inc/service.php', 'data': {'action' : 'share_toon', 'version': version, 'toon_id': toon_id, 'shared': toon_shared}}).done(function(data) {
                eval(data);
            });
        }
    },
    
    warn: function(text) {
        $('#notice2 span.tooltips span').html(text);
        $('#notice2 span.tooltips span').fadeIn(400, function(){
        });
    },
    
    info: function(text) {
        $('#notice3 span.tooltips span').html(text);
        var toon = $('#step4 .part.middle').find('.toon-item.selected');
        if(toon) {
            var p = toon.position();
            var l = p.left;
            var t = p.top;
            var w = toon.width();
            var h = toon.height();
            var r = $('#step4 div.content.toon-list').width();
            var s = $('#step4 div.content.toon-list').height();
            var c = (s > 200 ? Math.max(118, s - t - h + 63) : Math.max(128, s - t - h + 47));
            var g = Math.max(18, l + parseInt(w / 2) - (r > 291 ? 20 : -11));
            //console.log({l: l, t: t, w: w, h: h, r: r, s: s, c: c, g: g});
            $('#notice3').css({'bottom': c + 'px'});
            $('#notice3.notice span.tooltips span:before').addRule('left:' + g + 'px');
            $('#notice3.notice span.tooltips span:after').addRule('left:' + g + 'px');
        }
        $('#notice3 span.tooltips span').fadeIn(400, function(){
        });
    }
    
}

