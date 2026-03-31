<?php

function pdf_convert_to_png(&$action) {

	global $err_msg;

	$err_msg = '';
	$pdf_id = '';
	$output = [];
	$return_var = 0;

	if (is_array($_FILES) && isset($_FILES['upload_file'])) {
	    $tmp_name = $_FILES['upload_file']['tmp_name'];
	    $size = $_FILES['upload_file']['size'];
	    $type = $_FILES['upload_file']['type'];
	    if($type != 'application/pdf') {
	    	$err_msg = 'not a pdf';
	    }
	    if($err_msg == '') {
		    if ((isset($tmp_name)) && ($size != 0)) {
		        srand((float) microtime() * 1000000);
		        if ($err_msg == '') {
		        	$pdf_dir = getcwd() . '/' . UPLOAD_DIR . '/pdf';
					if(!file_exists($pdf_dir)){
						mkdir($pdf_dir);
						chmod($pdf_dir, 0777);
					}
		            do {
		                $pdf_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
		                $pdf_file = $pdf_dir . '/' . $pdf_id . '.pdf';
		            } while (file_exists($pdf_file));
		            move_uploaded_file($tmp_name, $pdf_file);
		        	$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';
					if(!file_exists($img_dir)){
						mkdir($img_dir);
						chmod($img_dir, 0777);
					}
					$command = '/usr/bin/pdftoppm -rx 150 -ry 150 "' . $pdf_file . '" -png ' . $img_dir . '/' . $pdf_id;
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
	    if($err_msg != '') {
	        
	        $action = '';

	    }
	}

	return $pdf_id;
}

function pdf_convert_from_png($pdf_id) {

	global $err_msg;

	$err_msg = '';
	$output = [];
	$return_var = 0;

	$pdf_dir = getcwd() . '/' . UPLOAD_DIR . '/pdf';
	if(!file_exists($pdf_dir)){
		mkdir($pdf_dir);
		chmod($pdf_dir, 0777);
	}
	$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';
	if(!file_exists($img_dir)){
		mkdir($img_dir);
		chmod($img_dir, 0777);
	}
	$command = '/usr/bin/convert ' . $img_dir . '/' . $pdf_id . '* ' . $pdf_dir . '/' . $pdf_id . '-2.pdf';
	write_log(__METHOD__, $command);
    exec($command, $output, $return_var);
    if($return_var != 0) {
        $err_msg = "{$command} exited with return_var {$return_var}\n";
		write_log(__METHOD__, "*** ERROR *** {$err_msg}");
    }
}