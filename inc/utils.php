<?php

$lang = '';

if(isset($_GET['lang']) && ($_GET['lang'] != '')) {
	
	$lang = $_GET['lang'];
    
} else {
	
	if(array_key_exists('lang', $_COOKIE)) {
		$lang = $_COOKIE['lang'];
	} else if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}

	switch($lang) {
		case 'fr':
			$location = './fr/';
			break;
		default:
			$location = './en/';
	}
	
	if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
		$location .= '?' . $_SERVER['QUERY_STRING'];
	}

	header("Location: {$location}");
	exit();
}

$page = 'home';

if(isset($_GET['page']) && ($_GET['page'] != '')) {
	
	$page = $_GET['page'];

}

$action = '';

if(isset($_POST['action']) && ($_POST['action'] != '')) {

	$action = $_POST['action'];
}

require_once 'lang.php';
require_once 'constant.php';
require_once 'get_ip.php';
require_once 'write_log.php';

write_log('utils', "[lang][{$lang}][page][{$page}][action][{$action}]");

if(array_key_exists($page, $page_role)) {

	$page = $page_role[$page];

}

$page_title = $page_title_prefix;

if($page != 'home' && array_key_exists($page, $page_title_suffix)) {

	$page_title.= ' - ' . $page_title_suffix[$page];

} else {

	$page = 'home';
}

$err_msg = '';

if (is_array($_FILES) && isset($_FILES['upload_file'])) {
    $pdf_id = '';
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
				$command = '/usr/bin/pdftoppm -rx 300 -ry 300 "' . $pdf_file . '" -png ' . $img_dir . '/' . $pdf_id;
				$output = [];
			    $return_var = 0;
			    exec($command, $output, $return_var);
			    if($return_var != 0) {
			        $err_msg = "{$command} exited with return_var {$return_var}\n";
			    } else {
			        //$err_msg = "{$command} has suceeded\n";
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
