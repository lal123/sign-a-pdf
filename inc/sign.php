<?php

function sign_get_img_from_text($sign_text) {

	$err_msg = '';
	$sign_id = '';

    $font_filename = getcwd() . '/../fonts/saginawbold-webfont.ttf';
    $font_size = 40;

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
