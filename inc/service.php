<?php

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    exit();
}

require_once 'utils.php';

$action = '';
if(isset($_POST['action'])) {
    $action = $_POST['action'];
}

$lang = 'en';
if(isset($_POST['lang'])) {
    $lang = $_POST['lang'];
}

$err_msg = '';

if(isset($_SESSION['text_color']) && ($_SESSION['text_color'] != '')) {
    $text_color = $_SESSION['text_color'];
} else if(isset($_COOKIE['text_color']) && ($_COOKIE['text_color'] != '')) {
    $text_color = $_COOKIE['text_color'];
} else {
    $text_color = '#000000';
}

if(isset($_SESSION['text_font']) && ($_SESSION['text_font'] != '')) {
    $text_font = $_SESSION['text_font'];
} else if(isset($_COOKIE['text_font']) && ($_COOKIE['text_font'] != '')) {
    $text_font = $_COOKIE['text_font'];
} else {
    $text_font = 'allegro-webfont';
}

switch($action) {
    case 'upload_doc':
        $max_docs_numb = ($is_signed_in ? USER_MAX_DOCS_NUMB : MAX_DOCS_NUMB);
        if($docs_numb >= $max_docs_numb) {
            $res = json_encode(['pdf_id' => '', 'err_msg' => strtr($tr['UPLOAD.MAX_DOCS_NUMB'], ['%%max_docs_numb%%' => $max_docs_numb]), 'name' => ''], JSON_UNESCAPED_UNICODE);
        } else { 
            $res = pdf_convert_to_png();
            $arr = json_decode($res, true);
            if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
                $pdf_id = $arr['pdf_id'];
                $pages = $arr['pages'];
                if($is_signed_in) {
                    $user_id = $user['user_id'];
                    model_doc_create(['user_id' => $user_id, 'pdf_id' => $pdf_id, 'name' => $arr['name'], 'size' => $arr['size'], 'pages' => $pages]);
                    $page_doc_id = db_insert_id();
                    for($page_index = 1 ; $page_index <= $pages ; $page_index++) {
                        $page_id = $pdf_id . ($pages > 1 ? '-' . ($page_index - 1 ) : '');
                        model_page_create(['page_id' => $page_id, 'page_doc_id' => $page_doc_id, 'page_index' => $page_index, 'page_available' => 1]);
                    }
                } else {
                    $_SESSION['docs'][$pdf_id]['name'] = $arr['name'];
                    $_SESSION['docs'][$pdf_id]['size'] = $arr['size'];
                    $_SESSION['docs'][$pdf_id]['pages'] = $pages;
                    for($page_numb = 1 ; $page_numb <= $pages ; $page_numb++) {
                        $page_id = $pdf_id . ($pages > 1 ? '-' . ($page_numb - 1 ) : '');
                        $_SESSION['docs'][$pdf_id]['page'][] = ['page_id' => $page_id, 'page_index' => $page_numb, 'page_available' => 1];
                    }
                    $_SESSION['docs'][$pdf_id]['signed'] = 0;
                    $_SESSION['docs'][$pdf_id]['time'] = time();
                }
            }
        }
        /*
        if($err_msg == '') {
            $page = 'docs';
            //pdf_convert_from_png($pdf_id);
        }
        */
        echo $res;
        break;
    case 'convert_doc':
        $pdf_id = $_POST['pdf_id'];
        $pages = $_POST['pages'];
        $signed = $_POST['signed'];
        $points = 0;
        if(isset($_SESSION['points']) && ($_SESSION['points'] != 0)) {
            $points = $_SESSION['points'];
        }
        $count = pdf_count_pages($pdf_id, $pages, $signed);
        $percent = ceil($count / $pages * 100);
        echo "$('#modal-info').html(decodeURIComponent('" . rawurlencode(($count == 0 ? $tr['UPLOAD.WAITING_MSG'] . ' ' . str_repeat('.', $points) : $tr['UPLOAD.PREPARING_DOC'] . " :&nbsp; {$count} / {$pages}&nbsp; ({$percent}%)")) . "'));\n";
        echo "$('#modal-progress').show();\n";
        echo "$('#modal-progress-bar').css({'width': {$percent} + '%'});\n";
        if($count >= $pages) {
            $_SESSION['points'] = 0;
            echo "document.location.href = '/{$lang}/docs/{$pdf_id}';\n";
        } else {
            $points++;
            $_SESSION['points'] = $points % 8;
            echo "docs.conv = setTimeout(\"docs.convert('{$pdf_id}', {$signed}, {$pages})\", 250);\n";
        }
        break;
    case 'delete_doc':
        $pdf_id = $_POST['pdf_id'];
        $redirect = $_POST['redirect'];
        if($is_signed_in) {
            if(!model_doc_delete($pdf_id)) {
                //??
            }
            $docs_numb = model_doc_get_numb($user['user_id']);
        } else {
            unset($_SESSION['docs'][$pdf_id]);
            $docs_numb = sizeof($_SESSION['docs']);
        }
        if($docs_numb > 0) {
            echo "$('.doc-small-preview[pdf_id=" . $pdf_id . "]').remove();\n";
            echo "$('.docs_numb').html('({$docs_numb})');\n";
            if($redirect == 1) {
                echo "document.location.href = '/{$lang}/docs';\n";
            }
        } else {
            echo "document.location.href = '/{$lang}/';\n";
        }
        break;
    case 'rotate_page':
        $page_id = $_POST['page_id'];
        $page_numb = $_POST['page_numb'];
        $doc_signed = $_POST['doc_signed'];
        $direction = $_POST['direction'];
        $res = sign_rotate_page($page_id, $doc_signed, $direction);
        $arr = json_decode($res, true);
        preg_match('/^([0-9a-f]{16})/', $page_id, $matches);
        list(, $pdf_id) = $matches;
        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '' )) {
            $rotated_page_id = $arr['rotated_page_id'];
            $img_src = $arr['img_src'];
            echo "$(\".page-container[page_id='{$page_id}']\").find('img.page-preview').attr('src', '" . $img_src . "');\n";
            echo "$(\".page-container[page_id='{$page_id}']\").attr('page_id', '{$rotated_page_id}');\n";
            if($is_signed_in) {
                model_doc_update_size($pdf_id, -1);
                model_page_switch_version($page_id, $rotated_page_id, true);
                $arr = model_doc_get_from_pdf_id($pdf_id);
                $doc_id = $arr['doc_id'];
                $page_enum = model_page_get_list_from_doc_id($doc_id);
            } else {
                $_SESSION['docs'][$pdf_id]['size'] = -1;
                foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                    if(($page_details['page_index'] == $page_numb) && ($page_details['page_available'] == 1)) {
                        $page_width = $page_details['page_width'];
                        $page_height = $page_details['page_height'];
                        $_SESSION['docs'][$pdf_id]['page'][$page_key]['page_available'] = 0;
                        break;
                    }
                }
                $_SESSION['docs'][$pdf_id]['page'][] = ['page_id' => $rotated_page_id, 'page_index' => $page_numb, 'page_available' => 1, 'page_width' => $page_height, 'page_height' => $page_width];
                $page_enum = $_SESSION['docs'][$pdf_id]['page'];
            }
            foreach($page_enum as $page_key => $page_details) {
                if($page_details['page_available'] == 1) {
                    echo "$(\".page-container[page_id='" . $page_details['page_id'] . "']\").find('.page-content').css({'width': '" . ($page_details['page_width'] > $page_details['page_height'] ? 100 : 75) . "%'});\n";
                }
            }
            echo "$('html, body').animate({scrollTop: ($(\".page-container[page_id='{$rotated_page_id}']\").position().top - 220) + 'px'}, 'fast', function(){});\n";
        }
        break;
    case 'delete_sign':
        $sign_file_id = $_POST['sign_file_id'];
        if($is_signed_in) {
            if(!model_sign_delete($sign_file_id)) {
                //??
            }
            $signs_numb = model_sign_get_numb($user['user_id']);
        } else {
            unset($_SESSION['signs'][$sign_file_id]);
            $signs_numb = sizeof($_SESSION['signs']);
        }
        if($signs_numb > 0) {
            echo "$('.sign-small-preview[sign_file_id=" . $sign_file_id . "]').remove();\n";
            echo "$('.signs_numb').html('({$signs_numb})');\n";
        } else {
            echo "document.location.href = '/{$lang}/';\n";
        }
        break;
    case 'delete_account':
        $user_id = $_POST['user_id'];
        $errors = [];
        if (utils_user_delete($user_id, $errors)) {
            echo "document.location.href = '/{$lang}/';\n";
        } else {
            echo "console.log('errors', " . json_encode($errors, JSON_UNESCAPED_UNICODE) . ");\n";
        }
        break;
    case 'get_sign_step':
        $arr = [];
        $arr['err_msg'] = '';
        $pdf_id = $_POST['pdf_id'];
        $doc_signed = $_POST['doc_signed'];
        $sign_step = $_POST['sign_step'];
        $sign_inc = $_POST['sign_inc'];
        $sign_option = $_POST['sign_option'];
        $page_option = $_POST['page_option'];
        $sign_pages = $_POST['sign_pages'];
        $pages = $_POST['pages'];
        switch($sign_step) {
            case 1:
                $sign_text = substr($_POST['sign_text'], 0, 50);
                $text_font = $_POST['text_font'];
                $text_color = $_POST['text_color'];
                $text_thickness = $_POST['text_thickness'];
                switch($sign_option) {
                    case 2 :
                        $res = sign_get_img_from_file();
                        $arr = json_decode($res, true);
                        //write_log('sign_post_file', print_r($arr, true));
                        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
                            $sign_id = $arr['sign_id'];
                            $sign_width = $arr['sign_width'];
                            $sign_height = $arr['sign_height'];
                            $sign_step+= $sign_inc;
                        }
                        break;
                    case 3 :
                        $res = sign_get_img_from_text($sign_text, $text_font, $text_color);
                        $arr = json_decode($res, true);
                        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
                            setcookie('text_font', $text_font, time() + 2 * 365 * 86400, '/');
                            $_SESSION['text_font'] = $text_font;
                            setcookie('text_color', $text_color, time() + 2 * 365 * 86400, '/');
                            $_SESSION['text_color'] = $text_color;
                            $sign_id = $arr['sign_id'];
                            $sign_width = $arr['sign_width'];
                            $sign_height = $arr['sign_height'];
                            $sign_step+= $sign_inc;
                        } else {
                            //echo "$('#globalError').html(decodeURIComponent('" . rawurlencode($arr['err_msg']) . "'));\n";
                        }
                        break;
                    case 4 :
                        $sign_step+= $sign_inc;
                        break;
                    case 1 :
                        setcookie('text_font', $text_font, time() + 2 * 365 * 86400, '/');
                        $_SESSION['text_font'] = $text_font;
                        setcookie('text_color', $text_color, time() + 2 * 365 * 86400, '/');
                        $_SESSION['text_color'] = $text_color;
                    default:
                        $sign_width = -1;
                        $sign_height = -1;
                        $sign_id = '';
                        $sign_step+= $sign_inc;
                }
                break;
            case 2:
                $sign_text = $_POST['sign_text'];
                $text_font = $_POST['text_font'];
                $text_color = $_POST['text_color'];
                $text_thickness = $_POST['text_thickness'];
                $sign_id = $_POST['sign_id'];
                $sign_width = $_POST['sign_width'];
                $sign_height = $_POST['sign_height'];
                switch($sign_option) {
                    case 1:
                        $sign_data = $_POST['sign_data'];
                        if($sign_inc == 1) {
                            $res = sign_get_img_from_data($sign_data);
                            $arr = json_decode($res, true);
                            $sign_id = $arr['sign_id'];
                        }
                        break;
                    case 4:
                        if($sign_inc == 1) {
                            $sign_details = model_sign_get_from_file_id($sign_id);
                            if(sizeof($sign_details) == 0) {
                                $arr['err_msg'] = $tr['UNEXPECTED_ERROR'];
                            }
                            $sign_width = $sign_details['sign_width'];
                            $sign_height = $sign_details['sign_height'];
                        }
                        break;
                    default:
                }
                if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
                    $sign_step+= $sign_inc;
                }
                break;
            case 3:
                $sign_text = $_POST['sign_text'];
                $text_font = $_POST['text_font'];
                $text_color = $_POST['text_color'];
                $text_thickness = $_POST['text_thickness'];
                $sign_id = $_POST['sign_id'];
                $sign_width = $_POST['sign_width'];
                $sign_height = $_POST['sign_height'];
                if($sign_inc == 1) {
                    if($page_option == 3) {
                        $sp = preg_split('/[ ,]+/', $sign_pages);
                        for($i = 0 ; $i < sizeof($sp) ; $i++) {
                            if(preg_match('/^([0-9]+)\-([0-9]+)$/', $sp[$i], $matches)) {
                                list(,$first, $last) = $matches;
                                for($j = $first ; $j <= $last ; $j++) {
                                    $pages_arr[] = $j;
                                }
                            } else {
                                $pages_arr[] = $sp[$i];
                            }
                        }
                        for($i = 0 ; $i < sizeof($pages_arr); $i++) {
                            if(($pages_arr[$i] < 1) || ($pages_arr[$i] > $pages)) {
                                $arr['err_msg'] = $tr['SIGN.PAGES.CUST.INVALID'];
                            }
                        }
                    }
                }
                if(($sign_inc == -1) || (!isset($arr['err_msg']) || ($arr['err_msg'] == ''))) {
                    $sign_step+= $sign_inc;
                }
                break;
            case 0:
            default:
                // default values
                $sign_text = ($is_signed_in ? $user['user_name'] : '');
                $text_thickness = 4;
                $sign_step+= $sign_inc;
        }
        if($sign_step >= 4) {

            if($is_signed_in) {
                $user_id = $user['user_id'];
                if($sign_option != 4) {
                    model_sign_create(['sign_user_id' => $user_id, 'sign_file_id' => $sign_id, 'sign_width' => $sign_width, 'sign_height' => $sign_height]);
                }
                $signs_numb = model_sign_get_numb($user_id);
            } else {
                $order = 1;
                if(isset($_SESSION['signs']) && is_array($_SESSION['signs']) && (sizeof($_SESSION['signs']) > 0)) {
                    $signs = $_SESSION['signs'];
                    uasort($signs, function($a, $b) {
                        return (intval($b['order']) > intval($a['order']) ? 1 : -1);
                    });
                    $order = array_values($signs)[0]['order'] + 1;
                }
                $_SESSION['signs'][$sign_id]['time'] = time();
                $_SESSION['signs'][$sign_id]['width'] = $sign_width;
                $_SESSION['signs'][$sign_id]['height'] = $sign_height;
                $_SESSION['signs'][$sign_id]['order'] = $order;
                $signs_numb = (isset($_SESSION['signs']) ? sizeof($_SESSION['signs']) : 0);
            }
            echo "$('.signs_numb').html('({$signs_numb})');\n";
            if(!$doc_signed) {
                $res = pdf_create_signed_doc();
                $arr = json_decode($res, true);
            } else {
                $arr = ['signed_pdf_id' => $pdf_id, 'err_msg' => ''];
            }
            if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
                $signed_pdf_id = $arr['signed_pdf_id'];
                echo "$('#signDocModal').modal('hide');\n";
                echo "sign.adjust('{$pdf_id}', '{$signed_pdf_id}', {$doc_signed}, $('#navPage').val(), '{$sign_id}', {$page_option}, '{$sign_pages}', {$sign_width}, {$sign_height});\n";
            }
        } else {
            ob_start();
            include(getcwd() . "/content/sign-doc-step{$sign_step}.php");
            $content = ob_get_contents();
            ob_end_clean();
            echo "$('#modalBody').html(decodeURIComponent('" . rawurlencode($content) . "'));\n";
            if($sign_step == 1) {
                echo "$('#signDocModal #backButton').hide();\n";
                echo "$.farbtastic('#colorpicker', function(col) {\n"
                    . "    $('#textColor').val(col);\n"
                    . "    $('.text-color-preview').css({'color': col});\n"
                    . "    $('#textFontPreview').css({'color': col});\n"
                    . "    $('#textFontList > .fontItem').css({'background-color': '#ffffff', 'color': col});\n"
                    . "    $('#textFontList > .fontItem.selected').css({'background-color': col, 'color': '#ffffff'});\n"
                    . "}).setColor('" . $text_color . "');\n";
                echo "$('#signDocModal').click(function(event){\n"
                . "    if(!$(event.target).hasClass('text-color-preview') && ($(event.target).parents('#colorpicker').length == 0)) sign.hideColorPicker();\n"
                . "    if(!$(event.target).hasClass('text-font-preview') && ($(event.target).parents('#textFontList').length == 0)) sign.hideFontList();\n"
                . "});\n";
                echo "$('#textFontList > .fontItem').on('mouseenter', function(e) {\n"
                    . "    var col = $('#textColor').val();\n"
                    . "    $('#textFontList > .fontItem').css({'background-color': '#ffffff', 'color' : col});\n"
                    . "    $(e.target).css({'background-color': col, 'color': '#ffffff'});\n"
                . "});\n";
                echo "$('#textFontList > .fontItem').on('click', function(e) {\n"
                    . "    $('#textFontList > .fontItem').removeClass('selected');\n"
                    . "    $(e.target).addClass('selected');\n"
                    . "    sign.selectFont($(e.target).attr('font_filename'), $(this).css('font-family'), $(this).css('font-size'), $(this).css('line-height'), $(this).html());\n"
                    . "});\n";
            } else {
                echo "$('#signDocModal #backButton').show();\n";
            }
            if(($sign_step == 2) && ($sign_option == 1)) {
                echo "sign.initCanvas('" . $text_color . "', '" . $text_thickness . "');\n";
                if($sign_inc == -1) {
                    echo "sign.loadCanvas('/uploads/sign/{$sign_id}.png');\n";
                }
            }
            echo "$('#signDocModal').modal('show');\n";
        }
        break;
    case 'prepare_sign':
        $pdf_id = $_POST['pdf_id'];
        $signed_pdf_id = $_POST['signed_pdf_id'];
        $doc_signed = $_POST['doc_signed'];
        $curr_page = $_POST['curr_page'];
        $page_index = $_POST['page_index'];
        $page_list = $_POST['page_list'];
        $page_id = $_POST['page_id'];
        $sign_id = $_POST['sign_id'];
        $page_option = $_POST['page_option'];
        $sign_pages = $_POST['sign_pages'];
        $pages = $_POST['pages'];
        $scrollTop = $_POST['scrollTop'];
        if(!$doc_signed) {
            $_SESSION['scrollTop'] = $scrollTop;
        }
        if($is_signed_in) {
            $doc = model_doc_get_from_pdf_id($pdf_id);
            model_doc_update_size($pdf_id, -1);
            model_page_confirm_list($doc['doc_id'], $page_list);
        } else {
            $_SESSION['docs'][$pdf_id]['size'] = -1;
            foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                $_SESSION['docs'][$pdf_id]['page'][$page_key]['page_available'] = (in_array($page_details['page_id'], $page_list) ? 1 : 0);
            }
        }
        echo "sign.validate({'pdf_id': '{$pdf_id}', 'signed_pdf_id': '{$signed_pdf_id}', 'doc_signed': {$doc_signed}, 'curr_page': {$curr_page}, 'page_id': '{$page_id}', 'sign_id': '{$sign_id}', 'page_index': 0, 'page_option': '{$page_option}', 'sign_pages': '{$sign_pages}', 'pages': {$pages}, 'lang': '{$lang}', 'scrollTop': '{$scrollTop}'});\n";
        break;
    case 'sign_page':
        $pdf_id = $_POST['pdf_id'];
        $signed_pdf_id = $_POST['signed_pdf_id'];
        $doc_signed = $_POST['doc_signed'];
        $curr_page = $_POST['curr_page'];
        $page_index = $_POST['page_index'];
        $page_id = $_POST['page_id'];
        $sign_id = $_POST['sign_id'];
        $page_option = $_POST['page_option'];
        $sign_pages = $_POST['sign_pages'];
        $page_w = $_POST['page_w'];
        $page_h = $_POST['page_h'];
        $sign_w = $_POST['sign_w'];
        $sign_h = $_POST['sign_h'];
        $sign_x = $_POST['sign_x'];
        $sign_y = $_POST['sign_y'];
        if($is_signed_in){
            $arr = model_doc_get_from_pdf_id($pdf_id);
            $doc_id = $arr['doc_id'];
            $pages = $arr['doc_pages'];
        } else {
            $pages = $_SESSION['docs'][$pdf_id]['pages'];
        }

        $pages_arr = [];    
        switch($page_option) {
            case 2:
                for($i = 1 ; $i <= $pages ; $i++) {
                    $pages_arr[] = $i;
                }
                break;
            case 3:
                $sp = preg_split('/[ ,]+/', $sign_pages);
                for($i = 0 ; $i < sizeof($sp) ; $i++) {
                    if(preg_match('/^([0-9]+)\-([0-9]+)$/', $sp[$i], $matches)) {
                        list(,$first, $last) = $matches;
                        for($j = $first ; $j <= $last ; $j++) {
                            $pages_arr[] = $j;
                        }
                    } else {
                        $pages_arr[] = $sp[$i];
                    }
                }
                break;
            case 4:
                $pages_arr[] = $curr_page;
                break;
            case 1:
            default:
                $pages_arr[] = $pages;
        }

        $pages_numb = sizeof($pages_arr);

        if(($pages_arr[$page_index] >= 1)  && ($pages_arr[$page_index] <= $pages)) {

            if($is_signed_in) {
                $page = model_page_get_from_doc_id_and_index($doc_id, $pages_arr[$page_index]);
            } else {
                $page = false;
                foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                    if(($page_details['page_index'] == $pages_arr[$page_index]) && ($page_details['page_available'] == 1)) {
                        $page = ['page_id' => $page_details['page_id'], 'page_index' => $page_details['page_index']];
                        break;
                    }
                }
            }

            if($page != false) {
                $page_id = $page['page_id'];
                //$page_id =  $pdf_id . (($pages > 1) || ($pages_arr[$page_index] > 1) ? '-' . ($pages_arr[$page_index] - 1)  : '');
                
                $signed_page_id =  $signed_pdf_id . (($pages > 1) || ($pages_arr[$page_index] > 1) ? '-' . ($pages_arr[$page_index] - 1)  : '');
                $res = sign_apply_sign_to_page($page_id, $signed_page_id, $doc_signed, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y);
                $arr = json_decode($res, true);


                if($doc_signed) {
                    if($is_signed_in) {
                        model_doc_update_size($signed_pdf_id, -1);
                        model_page_switch_version($page_id, $arr['signed_page_id'], false);
                    } else {
                        //$_SESSION['docs'][$signed_pdf_id]['page'][] = ['page_id' => $page_id, 'page_index' => $pages_arr[$page_index], 'page_available' => 0];
                        $_SESSION['docs'][$signed_pdf_id]['size'] = -1;
                        foreach($_SESSION['docs'][$signed_pdf_id]['page'] as $page_key => $page_details) {
                            if(($page_details['page_index'] == $pages_arr[$page_index]) && ($page_details['page_available'] == 1)) {
                                $_SESSION['docs'][$signed_pdf_id]['page'][$page_key]['page_available'] = 0;
                                break;
                            }
                        }
                        $_SESSION['docs'][$signed_pdf_id]['page'][] = ['page_id' => $arr['signed_page_id'], 'page_index' => $pages_arr[$page_index], 'page_available' => 1];
                    }
                }
                $signed_page_id = $arr['signed_page_id'];




            } else {
                $arr['err_msg'] = $tr['UNEXPECTED_ERROR'];  
            }
        } else {
            $arr['err_msg'] = $tr['DOCS.SIGN.INVALID_PAGE_INDEX'];
        }

        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
            if($page_index == 0) {
                echo "$('#signPreview').css({'visibility': 'hidden'});\n";
            }
            $img_src = '/' . UPLOAD_DIR . '/img/signed/' . $signed_page_id . '.png';
            echo "$(\".page-container[page_id='{$page_id}']\").find('img.page-preview').attr('src', '" . $img_src . "');\n";
            echo "$(\".page-container[page_id='{$page_id}']\").attr('page_id', '{$signed_page_id}');\n";
            $page_index++;
            if($page_index < $pages_numb) {
                if($is_signed_in) {
                    $page = model_page_get_from_doc_id_and_index($doc_id, $pages_arr[$page_index]);
                } else {
                    $page = false;
                    foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                        if(($page_details['page_index'] == $pages_arr[$page_index]) && ($page_details['page_available'] == 1)) {
                            $page = ['page_id' => $page_details['page_id'], 'page_index' => $page_details['page_index']];
                            break;
                        }
                    }
                }
                if($page != false) {
                    $page_id = $page['page_id'];
                    $percent = ceil($page_index / $pages_numb * 100);
                    echo "$('#validateSignModal .modal-info').html(decodeURIComponent('" . rawurlencode($tr['DOCS.SIGN_DOC.PREPARING'] . " :&nbsp; {$page_index} / {$pages_numb} ({$percent}%)") . "'));\n";
                    echo "$('#validateSignModal .modal-progress-bar').css({'width': {$percent} + '%'});\n";
                    echo "$('#validateSignModal .modal-progress').show();\n";
                    echo "sign.validate({'pdf_id': '{$pdf_id}', 'page_id': '{$page_id}', 'signed_pdf_id': '{$signed_pdf_id}', 'doc_signed': {$doc_signed}, 'curr_page': {$curr_page}, 'sign_id': '{$sign_id}', 'page_index': {$page_index}, 'page_option': {$page_option}, 'sign_pages': '{$sign_pages}', 'pages': {$pages}});\n";
                } else {
                    $arr['err_msg'] = $tr['UNEXPECTED_ERROR'];  
                }
            } else {
                if(!$doc_signed) {
                    sign_copy_unsigned_pages($pdf_id, $signed_pdf_id, $doc_signed, $pages);
                    $signed_pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf/signed';
                    $signed_doc_size = -1;
                    if($is_signed_in) {
                        model_doc_sign($pdf_id, $signed_pdf_id, $signed_doc_size);
                        $signed_doc_id = db_insert_id();
                        model_page_duplicate_from_unsigned($doc_id, $signed_doc_id, $signed_pdf_id);
                    } else {
                        $_SESSION['docs'][$signed_pdf_id]['name'] = $_SESSION['docs'][$pdf_id]['name'];
                        $_SESSION['docs'][$signed_pdf_id]['time'] = time();
                        $_SESSION['docs'][$signed_pdf_id]['size'] = $signed_doc_size;
                        $_SESSION['docs'][$signed_pdf_id]['signed'] = 1;
                        $_SESSION['docs'][$signed_pdf_id]['pages'] = $pages;
                        if(isset($_SESSION['docs'][$pdf_id]['page'])) {
                            foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $details) {
                                $page_id = $details['page_id'];
                                $page_index = $details['page_index'];
                                $signed_page_id = $signed_pdf_id . ($pages > 1 ? '-' . ($page_index - 1) : '');
                                if($details['page_available'] == 1) $_SESSION['docs'][$signed_pdf_id]['page'][] = ['page_id' => $signed_page_id, 'page_index' => $page_index, 'page_available' => 1];
                            }
                        }
                    }
                }
                //echo "$('#signButton').addClass('disabled');\n";
                echo "$('#signPreview').remove();\n";
                echo "$('#validateSignModal .modal-progress').hide();\n";
                echo "$('#validateSignModal').modal('hide');\n";
                echo "docs.preload = [];\n";
                echo "docs.compNum = 0;\n";
                if(!$doc_signed) {
                    echo "docs.preloadPages(docs.changeDocument, '/{$lang}/docs/{$signed_pdf_id}');\n";
                }
            }
        } else {
            write_log('service_sign_page', '*** ERROR *** ' . $arr['err_msg']);
            echo "$('#validateSignModal .global-error').html(decodeURIComponent('" . rawurlencode($arr['err_msg']) . "'));\n";
        }
        break;
    case 'doc_download':
        $pdf_id = $_POST['pdf_id'];
        $page_list = $_POST['page_list'];
        $doc_changed = $_POST['doc_changed'];
        if($doc_changed == 1) {
            if($is_signed_in) {
                $doc = model_doc_get_from_pdf_id($pdf_id);
                model_doc_update_size($pdf_id, -1);
                model_page_confirm_list($doc['doc_id'], $page_list);
            } else {
                $_SESSION['docs'][$pdf_id]['size'] = -1;
                foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                    $_SESSION['docs'][$pdf_id]['page'][$page_key]['page_available'] = (in_array($page_details['page_id'], $page_list) ? 1 : 0);
                }
            }
        }
        if($is_signed_in) {
            $doc = model_doc_get_from_pdf_id($pdf_id);
            $name = $doc['doc_name'];
            $size = $doc['doc_size'];
            $pages = $doc['doc_pages'];
            $signed = $doc['doc_signed'];
        } else {
            $doc = $_SESSION['docs'][$pdf_id];
            $name = $doc['name'];
            $size = $doc['size'];
            $pages = $doc['pages'];
            $signed = $doc['signed'];
        }
        $filename = getcwd() . '/../' . UPLOAD_DIR . '/pdf/' . ($signed ? 'signed/' : '') . $pdf_id . '.pdf';
        if($size == -1) {
            pdf_convert_from_png($pdf_id, $signed, $pages);
            $doc_size = filesize($filename);
            if($is_signed_in) {
                model_doc_update_size($pdf_id, $doc_size);
            } else {
                $_SESSION['docs'][$pdf_id]['size'] = $doc_size;
            }
        }
        echo "clearInterval(docs.animh);\n";
        echo "docs.animh = null;\n";
        echo "$('#downloadDocModal').modal('hide');\n";
        echo "docs.download('{$pdf_id}');\n";
        break;
    case 'doc_confirm':
        $pdf_id = $_POST['pdf_id'];
        $page_list = $_POST['page_list'];
        $destination = $_POST['destination'];
        if($is_signed_in) {
            $doc = model_doc_get_from_pdf_id($pdf_id);
            model_doc_update_size($pdf_id, -1);
            model_page_confirm_list($doc['doc_id'], $page_list);
        } else {
            $_SESSION['docs'][$pdf_id]['size'] = -1;
            foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                $_SESSION['docs'][$pdf_id]['page'][$page_key]['page_available'] = (in_array($page_details['page_id'], $page_list) ? 1 : 0);
            }
        }
        /*
        foreach($page_list as $page_key => $page_id) {
            write_log('doc_confirm', $page_id);
            if($is_signed_in) {
                model_page_switch_version($page_id, $rotated_page_id);
            } else {
                foreach($_SESSION['docs'][$pdf_id]['page'] as $page_key => $page_details) {
                    if($page_details['page_id'] == $page_id) {
                        $_SESSION['docs'][$pdf_id]['page'][$page_key]['page_available'] = 0;
                        break;
                    }
                }
                $_SESSION['docs'][$pdf_id]['page'][] = ['page_id' => $rotated_page_id, 'page_index' => $page_numb, 'page_available' => 1];
            }
        }
        */
        echo "$('#confirmDocModal').modal('hide');\n";
        echo "document.location.href = '{$destination}';\n";
        break;
}
