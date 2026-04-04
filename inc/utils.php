<?php

$version_suffix = '1.01';

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

session_start();

require_once 'lang.php';
require_once 'constant.php';
require_once 'get_ip.php';
require_once 'write_log.php';
require_once 'private.php';
require_once 'mysql.php';
require_once 'model.php';
require_once 'pdf.php';
require_once 'mailer.php';

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
	$pdf_id = $_GET['pdf_id'];
}

if($pdf_id != '') {
	if(!file_exists(getcwd() . '/' . UPLOAD_DIR . '/pdf/' . $pdf_id . '.pdf')) {
		header("Location: /{$lang}/");
		exit();
	} else {
		$page = 'docs';
	}
}

db_connect();

//write_log('utils', "[lang][{$lang}][page][{$page}][action][{$action}][pdf_id][{$pdf_id}]");

$err_msg = '';

$js_content = '';


$pages = array_flip($page_role);

if(array_key_exists($page, $pages)) {

	$page = $pages[$page];

}

if(($page == 'docs') && (!isset($_SESSION['docs']) || (sizeof($_SESSION['docs']) == 0))) {
	header("Location: /{$lang}/");
}

$page_title = $page_title_prefix;

if($page != 'home' && array_key_exists($page, $page_title_suffix)) {

	$page_title.= ' - ' . $page_title_suffix[$page];

} else {

	$page = 'home';
}

function utils_is_valid_email_address($email) {
    
    return preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$email);
}

function utils_create_user($values, &$errors) {

	if(sizeof($errors) != 0) {
		return false;
	}

	$res = model_create_user($values);

	if($res !== true) {
		$errors['general'] = 'unexpected error';
		return false;
	}

	return true;
}