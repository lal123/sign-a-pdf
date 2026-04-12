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


function sign_get_img_from_text($sign_text) {

	$err_msg = '';
	$sign_id = '';

    $font_filename = getcwd() . '/../fonts/saginawbold-webfont.ttf';
    $font_size = 80;

    $dims = imagettfbbox($font_size, 0, $font_filename, $sign_text);
    $ascent = abs($dims[7]);
    $descent = abs($dims[1]);
    $text_width = abs($dims[0]) + abs($dims[2]);
    $text_height = $ascent + $descent;
    $image_height = $text_height + 20;
    $text_x = 5;
    $text_y = (($image_height / 2) - ($text_height / 2)) + $ascent;

    $image_width = $text_width + 20;
    $image_height = $text_height + 20;

    $image = imagecreatetruecolor($image_width , $image_height);

    $default_color = imagecolorallocate($image, 0, 0, 0);
    $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
    
    imagefill($image, 0, 0, $transparent);
    imagecolortransparent($image, $transparent);

    $ar = imagettftext($image, $font_size, 0, $text_x, $text_y, $default_color, $font_filename, $sign_text);

	$sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
	if(!file_exists($sign_dir)){
		mkdir($sign_dir);
		chmod($sign_dir, 0777);
	}

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

    do {
        $sign_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
        $sign_file = $sign_dir . '/' . $sign_id . '.png';
    } while (file_exists($sign_file));

    $sign_data = base64_decode(str_replace('data:image/png;base64,', '', $sign_data));

    $fh = fopen($sign_file, "wb");
    fputs($fh, $sign_data);
    fclose($fh);

    $ret = json_encode(['sign_id' => $sign_id, 'err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
    return $ret;
}

function sign_apply_sign_to_page($page_id, $signed_page_id, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y) {

    $err_msg = '';

    $img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';

    $page_img = imagecreatefrompng($img_dir . '/' . $page_id . '.png');
    $intr_w = imagesx($page_img);
    $intr_h = imagesy($page_img);
    
    imagealphablending($page_img, false);

    imagesavealpha($page_img, true);

    $sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
    $sign_img = imagecreatefrompng($sign_dir . '/' . $sign_id . '.png');
    $s_in_w = imagesx($sign_img);
    $s_in_h = imagesy($sign_img);

    $dst_x = intval($sign_x * ($intr_w / $page_w));
    $dst_y = intval($sign_y * ($intr_h / $page_h));
    $dst_w = intval($sign_w * ($intr_w / $page_w));
    $dst_h = intval($sign_h * ($intr_h / $page_h));

    imagecopyresized($page_img, $sign_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $s_in_w, $s_in_h);

    $signed_img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img/signed';
    if(!file_exists($signed_img_dir)){
        mkdir($signed_img_dir);
        chmod($signed_img_dir, 0777);
    }

    imagepng($page_img, $signed_img_dir . '/' . $signed_page_id . '.png');

    imagedestroy($page_img);
    imagedestroy($sign_img);

    $ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
    return $ret;
}
