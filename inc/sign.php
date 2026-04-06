<?php

function sign_get_img_from_text($sign_text) {

	$err_msg = '';

	$img_id = '';
	$err_msg = "";

    $font_filename = getcwd() . '/../fonts/saginawbold-webfont.ttf';
    $font_size = 40;

    //write_log(__METHOD__, "{$font_size}, 0, {$font_filename}, {$sign_text}");

    $ar = imagettfbbox($font_size, 0, $font_filename, $sign_text);
    $text_width		= $ar[4] - $ar[6];
    $text_height	= $ar[3] - $ar[5];

    $image = imagecreatetruecolor($text_width + 20 , $text_height + 20);

    $default_color = imagecolorallocate($image, 128, 0, 0);
    $white = imagecolorallocatealpha($image, 255, 255, 255, 1);
    
    imagefill($image, 0, 0, $white);
    imagecolortransparent($image, $white);

    $ar = imagettftext($image, $font_size, 0, 5, $text_height - 5, $default_color, $font_filename, $sign_text);

	$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
	if(!file_exists($img_dir)){
		mkdir($img_dir);
		chmod($img_dir, 0777);
	}

    do {
        $img_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
        $img_file = $img_dir . '/' . $img_id . '.png';
    } while (file_exists($img_file));

    imagepng($image, $img_file);

	$ret = json_encode(['img_id' => $img_id, 'err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
	return $ret;
}
