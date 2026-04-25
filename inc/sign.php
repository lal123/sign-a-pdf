<?php

function sign_get_img_from_file() {

    global $lang, $tr;

    $image_width = 0;
    $image_height = 0;
    $err_msg = '';

    if (is_array($_FILES) && isset($_FILES['sign_file'])) {
        $tmp_name = $_FILES['sign_file']['tmp_name'];
        $size = $_FILES['sign_file']['size'];
        $type = $_FILES['sign_file']['type'];
        $name = $_FILES['sign_file']['name'];
        if(!in_array($type, ['image/gif', 'image/png', 'image/jpeg'])) {
            $err_msg = $tr['NOT_AN_IMAGE'];
        } else if($size > 1 * 1024 * 1024) {
            $err_msg = $tr['SIGN.FILE_TOO_BIG'];
        }

        if($err_msg == '') {

            switch($type) {
                case 'image/png':
                    $image = imagecreatefrompng($_FILES['sign_file']['tmp_name']);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($_FILES['sign_file']['tmp_name']);
                    break;
                case 'image/jpeg':
                default:
                    $image = imagecreatefromjpeg($_FILES['sign_file']['tmp_name']);
            }

            $image_width = imagesx($image);
            $image_height = imagesy($image);

            $sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
            if(!file_exists($sign_dir)){
                mkdir($sign_dir);
                chmod($sign_dir, 0777);
            }

            srand((float) microtime() * 1000000);
            do {
                $sign_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
                $sign_file = $sign_dir . '/' . $sign_id . '.png';
            } while (file_exists($sign_file));

            imagepng($image, $sign_file);

            imagedestroy($image);
        }
    }

    $ret = json_encode(['sign_id' => $sign_id, 'sign_width' => $image_width, 'sign_height' => $image_height, 'err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);

    return $ret;
}


function sign_get_img_from_text($sign_text, $text_font, $text_color) {

	$err_msg = '';
	$sign_id = '';

    $font_filename = getcwd() . '/../fonts/' . $text_font . '.ttf';
    $font_size = 160;

    $dims = imagettfbbox($font_size, 0, $font_filename, $sign_text);
    $ascent = abs($dims[7]);
    $descent = abs($dims[1]);

    $text_width = abs($dims[0]) + abs($dims[2]);
    $text_height = $ascent + $descent;

    $image_width = $text_width + 60;
    $image_height = $text_height + 40;

    $text_x = 5;
    $text_y = (($image_height / 2) - ($text_height / 2)) + $ascent;


    $image = imagecreatetruecolor($image_width , $image_height);

    $default_color = imagecolorallocate($image, hexdec(substr($text_color, 1, 2)), hexdec(substr($text_color, 3, 2)), hexdec(substr($text_color, 5, 2)));
    $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
    
    imagefill($image, 0, 0, $transparent);
    imagecolortransparent($image, $transparent);

    $ar = imagettftext($image, $font_size, 0, $text_x, $text_y, $default_color, $font_filename, $sign_text);

	$sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
	if(!file_exists($sign_dir)){
		mkdir($sign_dir);
		chmod($sign_dir, 0777);
	}

    srand((float) microtime() * 1000000);
    do {
        $sign_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
        $sign_file = $sign_dir . '/' . $sign_id . '.png';
    } while (file_exists($sign_file));

    imagepng($image, $sign_file);

    imagedestroy($image);

	$ret = json_encode(['sign_id' => $sign_id, 'sign_width' => $image_width, 'sign_height' => $image_height, 'err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
	return $ret;
}

function sign_get_img_from_data($sign_data) {

    $err_msg = '';
    $sign_id = '';

    $sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
    if(!file_exists($sign_dir)){
        mkdir($sign_dir);
        chmod($sign_dir, 0777);
    }

    srand((float) microtime() * 1000000);
    do {
        $sign_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
        $sign_file = $sign_dir . '/' . $sign_id . '.png';
    } while (file_exists($sign_file));

    $sign_data = base64_decode(str_replace('data:image/png;base64,', '', $sign_data));

    $fh = fopen($sign_file, "wb");
    fputs($fh, $sign_data);
    fclose($fh);

    /*        
    write_log(__METHOD__, "[sign_file][{$sign_file}]");

    $sign_img = imagecreatefrompng($sign_file);

    imagealphablending($sign_img, false);

    $white = imagecolorallocatealpha($sign_img, 255, 255, 255, 0);

    imagecolortransparent($sign_img, $white);

    imagesavealpha($sign_img, true);

    imagepng($sign_img, $sign_file);
    */
    
    $ret = json_encode(['sign_id' => $sign_id, 'err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
    return $ret;
}

function sign_apply_sign_to_page($page_id, $signed_page_id, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y) {


    write_log(__METHOD__, "sign_apply_sign_to_page($page_id, $signed_page_id, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y)");

    $err_msg = '';

    $img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';

    $page0_img = imagecreatefrompng($img_dir . '/' . $page_id . '.png');
    $intr_w = imagesx($page0_img);
    $intr_h = imagesy($page0_img);

    $page_img = imagecreatetruecolor($intr_w, $intr_h);
    
    $transparent = imagecolorallocatealpha($page_img, 255, 255, 255, 127);
    imagefill($page_img, 0, 0, $transparent);

    imagecopy($page_img, $page0_img, 0, 0, 0, 0, $intr_w, $intr_h);

    //imagealphablending($page_img, true);

    //imagesavealpha($page_img, true);

    $sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
    $sign_img = imagecreatefrompng($sign_dir . '/' . $sign_id . '.png');
    $s_in_w = imagesx($sign_img);
    $s_in_h = imagesy($sign_img);

    //imagealphablending($sign_img, false);

    //imagesavealpha($sign_img, true);

    $dst_x = intval($sign_x * ($intr_w / $page_w));
    $dst_y = intval($sign_y * ($intr_h / $page_h));
    $dst_w = intval($sign_w * ($intr_w / $page_w));
    $dst_h = intval($sign_h * ($intr_h / $page_h));

    imagecopyresized($page_img, $sign_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $s_in_w, $s_in_h);
    //imagecopyresampled($page_img, $sign_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $s_in_w, $s_in_h);
    //imagecopy($page_img, $sign_img, $dst_x, $dst_y, 0, 0, $s_in_w, $s_in_h);

    $signed_img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img/signed';
    if(!file_exists($signed_img_dir)){
        mkdir($signed_img_dir);
        chmod($signed_img_dir, 0777);
    }

    imagepng($page_img, $signed_img_dir . '/' . $signed_page_id . '.png');

    imagedestroy($page0_img);
    imagedestroy($page_img);
    imagedestroy($sign_img);

    $ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
    return $ret;
}

function sign_copy_unsigned_pages($pdf_id, $signed_pdf_id, $pages) {

    global $err_msg;

    $err_msg = '';
    $output = [];
    $return_var = 0;

    $img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
    if(!file_exists($img_dir)){
        mkdir($img_dir);
        chmod($img_dir, 0777);
    }
    
    $signed_img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img/signed';
    if(!file_exists($signed_img_dir)){
        mkdir($signed_img_dir);
        chmod($signed_img_dir, 0777);
    }
    
    $file_list = [];
    for($i = 1; $i <= $pages ; $i++) {
        $signed_img_file = $signed_img_dir . '/' . $signed_pdf_id . ($pages > 1 ? '-' . ($i - 1) : '') . '.png';
        if(!file_exists($signed_img_file)) {
            $img_file = $img_dir . '/' . $pdf_id . ($pages > 1 ? '-' . ($i - 1) : '') . '.png';
            copy($img_file, $signed_img_file);
        }
        $file_list[] = $signed_img_file;
    }

    $ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
    return $ret;
}