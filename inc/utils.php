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

switch($page) {
	case 'account':
		$errors = [];
		$values = [];
		$action = 'create';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if(isset($_POST['action']) && ($_POST['action'] != '')) {
		        $action = $_POST['action'];
		        switch($action) {
		            case 'create':
		                $values['user_name'] = $_POST['user_name'];
		                if((strlen($values['user_name']) < 4) || (strlen($values['user_name']) > 24)) {
		                    $errors['user_name'] = $tr['ACCOUNT.USER_NAME.ERROR'];
		                }
		                $values['user_email'] = $_POST['user_email'];
		                if(!utils_is_valid_email_address($values['user_email'])) {
		                    $errors['user_email'] = $tr['ACCOUNT.USER_MAIL.ERROR'];
		                }
		                $values['user_pass'] = $_POST['user_pass'];
		                if((strlen($values['user_pass']) < 4) || (strlen($values['user_pass']) > 24)) {
		                    $errors['user_pass'] = $tr['ACCOUNT.USER_PASS.ERROR'];
		                }
		                $values['confirm'] = $_POST['confirm'];
		                if($values['confirm'] != $values['user_pass']) {
		                    $errors['confirm'] = $tr['ACCOUNT.CONFIRM.ERROR'];
		                }
		                $values['user_optin'] = (isset($_POST['user_optin']) && ($_POST['user_optin'] == 'on') ? 1 : 0);
		                $values['user_accept'] = (isset($_POST['user_accept']) && ($_POST['user_accept'] == 'on') ? 1 : 0);
		                if(!isset($values['user_accept']) || ($values['user_accept'] != 1)) {
		                    $errors['user_accept'] =  $tr['ACCOUNT.USER_ACCEPT.ERROR'];
		                }
		                if(utils_user_create($values, $errors)) {
		                    $action = 'confirm';
		                }
		                break;
		        }
		    }
		} else if(isset($_GET['action']) && ($_GET['action'] != '')) {
		    $action = $_GET['action'];
		    switch($action) {
		        case 'validate':
		            $user_id = $_GET['user_id'];
		            $user_key = $_GET['user_key'];
		            utils_user_validate($user_id, $user_key, $errors);
		            break;
		    }
		}
		break;
	case 'sign-in':
		$errors = [];
		$values = [];
		$action = 'sign-in';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if(isset($_POST['action']) && ($_POST['action'] != '')) {
		        $action = $_POST['action'];
		        switch($action) {
		            case 'sign-in':
		                $values['user_name'] = $_POST['user_name'];
		                $values['user_pass'] = $_POST['user_pass'];
		                if(utils_user_sign_in($values, $errors)) {
		                    $action = '';
		                    $page = 'home';
		                }
		                break;
		        }
		    }
		}
		break;
}

function utils_user_sign_in($values, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$user_name = $values['user_name'];
	$user_pass = $values['user_pass'];
	$res = model_user_exists(['user_name' => $user_name, 'user_pass' => $user_pass], $user);
	if($res != false) {
		if(sizeof($user) != 0) {
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_key'] = $user['user_key'];
			return true;
		} else {
			$errors['general'] = $tr['ACCOUNT.LOGIN_ERROR'];
			return false;
		}
	}
	return false;
}

function utils_is_signed_in() {

	$user= [];
	if(isset($_SESSION['user_id']) && isset($_SESSION['user_key'])) {
		$user_id = $_SESSION['user_id'];
		$user_key = $_SESSION['user_key'];
		$res = model_user_exists(['user_id' => $user_id, 'user_key' => $user_key], $user);
		if($res != false) {
			return (sizeof($user) != 0);
		}
	}
	return false;
}

function utils_is_valid_email_address($email) {
    
    return preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$email);
}

function utils_user_create($values, &$errors) {

	global $lang, $tr, $page_role;

	if(sizeof($errors) != 0) {
		return false;
	}

	$user = [];
	$res = model_user_exists(['user_name' => $values['user_name']], $user);

	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(sizeof($user) != 0) {
		$errors['user_name'] = $tr['ACCOUNT.NAME_ALREADY_EXISTS'];
	}

	$user = [];
	$res = model_user_exists(['user_email' => $values['user_email']], $user);

	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(sizeof($user) != 0) {
		$errors['user_email'] = $tr['ACCOUNT.EMAIL_ALREADY_EXISTS'];
	}

	$values['user_key'] = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));

	if(sizeof($errors) != 0) {
		return false;
	}

	$res = model_user_create($values);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	$user_id = db_insert_id();

	$scheme = (php_uname("n") == 'alain-520-1080fr' ? 'http' : 'https');
	$confirm_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/' . $lang . '/' . $page_role['account'] . '?action=validate&user_id=' . $user_id . '&user_key=' . $values['user_key'];
	$text_msg = $confirm_url;
	$html_msg = '<html><body><a href="' . $confirm_url . '">Confirmer</a></body</html>';

	send_mail(
		['name' => $values['user_name'], 'mail' => $values['user_email']],
		['name' => 'Contact Sign-a-pdf.com', 'mail' => 'contact@sign-a-pdf.com'],
		'Votre inscription',
		$text_msg,
		$html_msg
	);

	return true;
}

function utils_user_validate($user_id, $user_key, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$res = model_user_exists(['user_id' => $user_id, 'user_key' => $user_key], $user);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}
	if(sizeof($user) != 0) {
		if($user['user_valid'] == 1) {
			$errors['general'] = $tr['ACCOUNT.ALREADY_VALIDATED'];;
		} else {
			$res = model_user_validate($user_id, $user_key);
			if($res == false) {
				$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
				return false;
			}
		}
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_key'] = $user_key;
	} else {
		$errors['general'] = $tr['ACCOUNT.VALIDATION_ERROR'];
	}
	return true;
}