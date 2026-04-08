<?php

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
    $white = imagecolorallocatealpha($image, 255, 255, 255, 1);
    
    imagefill($image, 0, 0, $white);
    imagecolortransparent($image, $white);

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

function sign_apply_sign_to_page($page_id, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y) {

    $img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';

    $page_img = imagecreatefrompng($img_dir . '/' . $page_id . '.png');
    $intr_w = imagesx($page_img);
    $intr_h = imagesy($page_img);
  
    $sign_dir = getcwd() . '/../' . UPLOAD_DIR . '/sign';
    $sign_img = imagecreatefrompng($sign_dir . '/' . $sign_id . '.png');

    $dst_x = $sign_x / $page_w * $intr_w;
    $dst_y = $sign_y / $page_h * $intr_h;
    $dst_w = $sign_w / $page_w * $intr_w;
    $dst_h = $sign_h / $page_h * $intr_h;

    imagecopyresampled($page_img, $sign_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $sign_w, $sign_h);

    $signed_img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img/signed';
    if(!file_exists($signed_img_dir)){
        mkdir($signed_img_dir);
        chmod($signed_img_dir, 0777);
    }

    imagepng($page_img, $signed_img_dir . '/' . $page_id . '.png');

    imagedestroy($page_img);
    imagedestroy($sign_img);

}
