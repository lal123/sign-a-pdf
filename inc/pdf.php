<?php

function pdf_convert_to_png() {

	$err_msg = '';
	$pdf_id = '';
	$name = '';
	$size = 0;
	$output = [];
	$return_var = 0;

	if (is_array($_FILES) && isset($_FILES['upload_file'])) {
	    $tmp_name = $_FILES['upload_file']['tmp_name'];
	    $size = $_FILES['upload_file']['size'];
	    $type = $_FILES['upload_file']['type'];
	    $name = $_FILES['upload_file']['name'];
	    if($size > 20 * 1024 * 1024) {
	    	$err_msg = 'file is too big';
	    } else if($type != 'application/pdf') {
	    	$err_msg = 'not a pdf';
	    }
	    if($err_msg == '') {
		    if ((isset($tmp_name)) && ($size != 0)) {
		        srand((float) microtime() * 1000000);
		        if ($err_msg == '') {
		        	$pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf';
					if(!file_exists($pdf_dir)){
						mkdir($pdf_dir);
						chmod($pdf_dir, 0777);
					}
		            do {
		                $pdf_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
		                $pdf_file = $pdf_dir . '/' . $pdf_id . '.pdf';
		            } while (file_exists($pdf_file));
		            move_uploaded_file($tmp_name, $pdf_file);
		        	$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
					if(!file_exists($img_dir)){
						mkdir($img_dir);
						chmod($img_dir, 0777);
					}
					//$command = '/usr/bin/pdftoppm -rx 150 -ry 150 "' . $pdf_file . '" -png ' . $img_dir . '/' . $pdf_id;
					$command = '/usr/bin/convert -density 192 ' . $pdf_file . ' -quality 100 -alpha remove -density 300  -resize 100% ' . $img_dir . '/' . $pdf_id . '.png';
					write_log(__METHOD__, $command);
				    exec($command, $output, $return_var);
				    if($return_var != 0) {
				        $err_msg = "{$command} exited with return_var {$return_var}\n";
						write_log(__METHOD__, "*** ERROR *** {$err_msg}");
				    }
		        }
		    } else {
		        $err_msg = 'No data received!';
		    }
	    }
	} else {
        $err_msg = 'No data received!';
    }
	$ret = json_encode(['pdf_id' => $pdf_id, 'err_msg' => $err_msg, 'name' => $name, 'size' => $size], JSON_UNESCAPED_UNICODE);
	return $ret;
}

function pdf_import_unsigned_pages($pdf_id) {

	global $err_msg;

	$err_msg = '';

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
    $fh = opendir($img_dir);
	while($fn = readdir($fh)) {
		write_log(__METHOD__, "fn: [{$fn}]");
		if(preg_match("/^{$pdf_id}/", $fn)) {
			copy($img_dir . '/' . $fn, $signed_img_dir . '/' . $fn);
		}
	}

	$ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
	return $ret;
}

function pdf_convert_from_png($pdf_id) {

	global $err_msg;

	$err_msg = '';
	$output = [];
	$return_var = 0;

    $signed_img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img/signed';
    if(!file_exists($signed_img_dir)){
        mkdir($signed_img_dir);
        chmod($signed_img_dir, 0777);
    }
	$signed_pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf/signed';
	if(!file_exists($signed_pdf_dir)){
		mkdir($signed_pdf_dir);
		chmod($signed_pdf_dir, 0777);
	}
	$command = '/usr/bin/convert ' . $signed_img_dir . '/' . $pdf_id . '* ' . $signed_pdf_dir . '/' . $pdf_id . '.pdf';
	write_log(__METHOD__, $command);
    exec($command, $output, $return_var);
    if($return_var != 0) {
        $err_msg = "{$command} exited with return_var {$return_var}\n";
		write_log(__METHOD__, "*** ERROR *** {$err_msg}");
    }

	$ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
	return $ret;
}