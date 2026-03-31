<?php

$version_suffix = '1.00';

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
require_once 'pdf.php';

write_log('utils', "[lang][{$lang}][page][{$page}][action][{$action}]");

$err_msg = '';

if($action == 'step2') {
	$pdf_id = pdf_convert_to_png($action);
	if($err_msg == '') {
		$page = 'step2';
		pdf_convert_from_png($pdf_id);
	}
}

if(array_key_exists($page, $page_role)) {

	$page = $page_role[$page];

}

$page_title = $page_title_prefix;

if($page != 'home' && array_key_exists($page, $page_title_suffix)) {

	$page_title.= ' - ' . $page_title_suffix[$page];

} else {

	$page = 'home';
}

