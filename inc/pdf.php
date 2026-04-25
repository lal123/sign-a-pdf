<?php

function pdf_convert_to_png() {

	global $lang, $tr;

	$err_msg = '';
	$pdf_id = '';
	$name = '';
	$size = 0;
	$pages = 1;
	$output = [];
	$return_var = 0;

	if (is_array($_FILES) && isset($_FILES['upload_file'])) {
	    $tmp_name = $_FILES['upload_file']['tmp_name'];
	    $size = $_FILES['upload_file']['size'];
	    $type = $_FILES['upload_file']['type'];
	    $name = $_FILES['upload_file']['name'];
	    if($size > 20 * 1024 * 1024) {
	    	$err_msg = $tr['UPLOAD.FILE_TOO_BIG'];
	    } else if($type != 'application/pdf') {
	    	$err_msg = $tr['UPLOAD.NOT_A_PDF'];
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

					$pages = 1;

					$command = '/usr/bin/pdfinfo ' . $pdf_file . ' | grep -- ^Pages';
				    exec($command, $output, $return_var);
				    if(preg_match('/([0-9]+)$/', $output[0], $matches)) {
				    	list(, $pages) = $matches;
				    }
				    
				    /*
					$pages = preg_match_all("/\/Page\W/", file_get_contents($pdf_file), $matches);
					if(!isset($pages) || ($pages == 0)) {
						$pages = 1;
					}
					*/

					write_log(__METHOD__, $pdf_file . " => " . $pages . " pages");

		        	$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
					if(!file_exists($img_dir)){
						mkdir($img_dir);
						chmod($img_dir, 0777);
					}
					//$command = '/usr/bin/pdftoppm -rx 150 -ry 150 "' . $pdf_file . '" -png ' . $img_dir . '/' . $pdf_id;
					//$command = '/usr/bin/convert -density 192 ' . $pdf_file . ' -quality 100 -alpha remove -resize 100% ' . $img_dir . '/' . $pdf_id . '.png';

					$command = '/usr/bin/convert -density 192 -units pixelsperinch -alpha remove ' . $pdf_file . ' -quality 100 -resize 100% ' . $img_dir . '/' . $pdf_id . '.png > /dev/null &';
					// -type TrueColor
					///write_log(__METHOD__, $command);

					write_log(__METHOD__, "[pdf_id][{$pdf_id}][command][{$command}]");

				    exec($command, $output, $return_var);

				    if($return_var != 0) {
				        $err_msg = "{$command} exited with return_var {$return_var}\n";
						write_log(__METHOD__, "*** ERROR *** {$err_msg}");
				    }

				    /*
			        if(!file_exists($img_dir . '/' . $pdf_id .'.png')) {
			        	$pages = 0;
			        	while(file_exists($img_dir . '/' . $pdf_id . '-' . $pages . '.png')) {
			        		$pages++;
			        	}

			        }
			        */
		        }
		    } else {
		        $err_msg = 'No data received!';
		    }
	    }
	} else {
        $err_msg = 'No data received!';
    }
	$ret = json_encode(['pdf_id' => $pdf_id, 'err_msg' => $err_msg, 'name' => $name, 'size' => $size, 'pages' => $pages], JSON_UNESCAPED_UNICODE);
	return $ret;
}

function pdf_check_pages_numb($img_dir, $pdf_id) {

	$pages = 1;

    if(!file_exists($img_dir . '/' . $pdf_id . '.png')) {
    	$pages = 0;
    	while(file_exists($img_dir . '/' . $pdf_id . '-' . $pages . '.png')) {
    		$pages++;
    	}

    }

    return $pages;
}

function pdf_count_pages($pdf_id, $pages, $signed) {

	$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img' . ($signed ? '/signed' : '');
    
    $count = 0;

    $fh = opendir($img_dir);
	while($filename = readdir($fh)) {
		if(preg_match("/^{$pdf_id}\-?([0-9]+)?\.png$/", $filename, $matches)) {
			list(, $page_index) = $matches;
			if(isset($page_index) && ($page_index != '')) {
				$page_numb = intval($page_index + 1);
				if(($pages == 1) || ($page_numb > $pages)) {
					write_log(__METHOD__, 'unexpected file ' . $img_dir . '/' . $filename . ' - ' . $pages . ' page' . ($pages > 1  ? 's' : '') . ' expected');
					sleep(2);
				}
			} else {
				$page_numb = 1;
			}
			$img_file = $img_dir . '/' . $filename;
			$tmp_img = imagecreatefrompng($img_file);
			if($tmp_img != false) {
				imagedestroy($tmp_img);
				$count++;
			}
		}
	}
	return $count;
}

function pdf_create_signed_doc() {

	global $err_msg;

	$err_msg = '';
	$signed_pdf_id = '';

	$signed_pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf/signed';
	if(!file_exists($signed_pdf_dir)){
		mkdir($signed_pdf_dir);
		chmod($signed_pdf_dir, 0777);
	}
	
    srand((float) microtime() * 1000000);
    do {
        $signed_pdf_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
        $signed_pdf_file = $signed_pdf_dir . '/' . $signed_pdf_id . '.pdf';
    } while (file_exists($signed_pdf_file));

	$ret = json_encode(['err_msg' => $err_msg, 'signed_pdf_id' => $signed_pdf_id], JSON_UNESCAPED_UNICODE);
	return $ret;
}

function pdf_convert_from_png($signed_pdf_id, $pages) {

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

	$file_list = [];
	for($i = 1; $i <= $pages ; $i++) {
		$signed_img_file = $signed_img_dir . '/' . $signed_pdf_id . ($pages > 1 ? '-' . ($i - 1) : '') . '.png';
		$file_list[] = $signed_img_file;
	}

	$command = '/usr/bin/convert -density 192 -units pixelsperinch ' . implode(' ', $file_list) . ' -quality 100 -resize 100% ' . $signed_pdf_dir . '/' . $signed_pdf_id . '.pdf';
	// -type TrueColor
	write_log(__METHOD__, "[command][{$command}]");
    
    exec($command, $output, $return_var);
    
    if($return_var != 0) {
        $err_msg = "{$command} exited with return_var {$return_var}\n";
		write_log(__METHOD__, "*** ERROR *** {$err_msg}");
    }

	$ret = json_encode(['err_msg' => $err_msg], JSON_UNESCAPED_UNICODE);
	return $ret;
}