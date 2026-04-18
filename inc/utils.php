<?php

$version_suffix = '1.56';

$lang = '';

if(isset($_POST['lang']) && ($_POST['lang'] != '')) {
	
	$lang = $_POST['lang'];
	setcookie('lang', $lang, time() + 2 * 365 * 86400, '/');
    
} else if(isset($_GET['lang']) && ($_GET['lang'] != '')) {
	
	$lang = $_GET['lang'];
	setcookie('lang', $lang, time() + 2 * 365 * 86400, '/');
    
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

	setcookie('lang', $lang, time() + 2 * 365 * 86400, '/');
	header("Location: {$location}");
	exit();
}

$page = 'home';

if(isset($_GET['page']) && ($_GET['page'] != '')) {
	$page = $_GET['page'];
}

require_once 'lang.php';
require_once 'constant.php';
require_once 'get_ip.php';
require_once 'write_log.php';
require_once 'private.php';
require_once 'mysql.php';
require_once 'model.php';
require_once 'pdf.php';
require_once 'sign.php';
require_once 'mailer.php';

session_start();

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
	$pdf_id = $_GET['pdf_id'];
}

if($pdf_id != '') {
	if(!file_exists(getcwd() . '/' . UPLOAD_DIR . '/pdf/' . $pdf_id . '.pdf') && !file_exists(getcwd() . '/' . UPLOAD_DIR . '/pdf/signed/' . $pdf_id . '.pdf') && !file_exists(getcwd() . '/' . UPLOAD_DIR . '/img/' . $pdf_id . '.png') && !file_exists(getcwd() . '/' . UPLOAD_DIR . '/img/signed/' . $pdf_id . '.png') && !file_exists(getcwd() . '/' . UPLOAD_DIR . '/img/' . $pdf_id . '-0.png') && !file_exists(getcwd() . '/' . UPLOAD_DIR . '/img/signed//' . $pdf_id . '-0.png')) {
		header("Location: /{$lang}/");
		exit();
	} else {
		$page = 'docs';
	}
}

db_connect();

$user = [];
$is_signed_in = utils_is_signed_in($user);

$err_msg = '';
$action = '';

$pages = array_flip($page_role);

if(array_key_exists($page, $pages)) {

	$page = $pages[$page];

}

if($is_signed_in) {
	$docs_numb = model_doc_get_numb($user['user_id']);
	$signs_numb = model_sign_get_numb($user['user_id']);
} else {
	$docs_numb = (isset($_SESSION['docs']) ? sizeof($_SESSION['docs']) : 0);
	$signs_numb = (isset($_SESSION['signs']) ? sizeof($_SESSION['signs']) : 0);
}

if(($page == 'docs') && ($docs_numb == 0)) {
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
		if($is_signed_in) {
			$action = 'update';
			$values = $user;
			$values['confirm'] = $values['user_pass'];
		} else {
			$action = 'create';					
			$values = [];
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if(isset($_POST['action']) && ($_POST['action'] != '')) {
		        $action = $_POST['action'];
		        switch($action) {
		            case 'create':
		            case 'update':
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
		                if($action == 'create') {
			                if(utils_user_create($values, $errors)) {
			                    $action = 'confirm';
			                }
			            } else {
			                if(utils_user_update($user['user_id'], $values, $errors)) {
			                    $action = 'confirm-update';
			                }
			            }
		                break;
		        }
		    }
		} else if(isset($_GET['s']) && ($_GET['s'] != '')) {
		    
		    list($action, $user_id, $user_key) = utils_get_link($_GET['s']);

		    switch($action) {
		        case 'validate':
		            utils_user_validate($user_id, $user_key, $user, $is_signed_in, $errors);
		            break;
		        case 'update':
		            if(utils_user_reconnect($user_id, $user_key, $user, $is_signed_in, $errors)) {
        				$values = $user;
						$values['confirm'] = $values['user_pass'];
		            }
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
		                    header("Location: /{$lang}/");
		                    exit();
		                }
		                break;
		        }
		    }
		}
		break;
	case 'sign-out':
        if(utils_user_sign_out()) {
            header("Location: /{$lang}/");
            exit();
        }
		break;
	case 'lost-ids':
		$errors = [];
		$values = [];
		$action = 'lost-ids';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if(isset($_POST['action']) && ($_POST['action'] != '')) {
		        $action = $_POST['action'];
		        switch($action) {
		            case 'lost-ids':
		                $values['user_email'] = $_POST['user_email'];
		                if(utils_user_lost_ids($values, $errors)) {
		                	$action = 'mail-sent';
		                }
		                break;
		        }
		    }
		}
		break;
	case 'contact':
		$errors = [];
		$values = $user;
		$action = 'contact';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if(isset($_POST['action']) && ($_POST['action'] != '')) {
		        $action = $_POST['action'];
		        switch($action) {
		            case 'contact':
		                $values['user_name'] = $_POST['user_name'];
		                $values['user_email'] = $_POST['user_email'];
		                $values['contact_text'] = $_POST['contact_text'];
		                if(utils_get_contact_msg($values, $errors)) {
		                	$action = 'mail-sent';
		                }
		                break;
		        }
		    }
		}
		break;
}

//write_log('utils', "[lang][{$lang}][page][{$page}][action][{$action}][pdf_id][{$pdf_id}]");

function utils_user_sign_in($values, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$user_name = $values['user_name'];
	$user_pass = $values['user_pass'];
	$res = model_user_exists(['user_name' => $user_name, 'user_pass' => $user_pass, 'user_valid' => 1], [], $user);
	if($res != false) {
		if(sizeof($user) != 0) {
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_key'] = $user['user_key'];
			setcookie('user_id', $user['user_id'], time() + 2 * 365 * 86400, '/');
			setcookie('user_key', $user['user_key'], time() + 2 * 365 * 86400, '/');
			return true;
		} else {
			$errors['general'] = $tr['ACCOUNT.LOGIN_ERROR'];
			return false;
		}
	}
	return false;
}

function utils_user_sign_out() {
    session_destroy();
    setcookie('user_id',"",0,"/","",0);
    setcookie('user_key',"",0,"/","",0);
    return true;
}

function utils_user_lost_ids($values, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$user_email = $values['user_email'];
	$res = model_user_exists(['user_email' => $user_email, 'user_valid' => 1], [], $user);
	if($res != false) {
		if(sizeof($user) != 0) {
			if(!isset($_SESSION['mail_sent']['lost_ids']) || ($_SESSION['mail_sent']['lost_ids'] != 1)) {	
				$confirm_url = utils_create_link('account', 'update', $user['user_id'], $user['user_key']);

				$arr = model_get_mail_model($lang, 'lost-ids');
				
				$text_msg = strtr($arr['mail_text'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);
				$html_msg = strtr($arr['mail_html'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);

				send_mail(
					['name' => $user['user_name'], 'mail' => $user['user_email']],
					['name' => 'Contact Sign-a-pdf.com', 'mail' => 'contact@sign-a-pdf.com'],
					'[Sign-a-pdf.com] ' . $tr['ACCOUNT.LOST_IDS.MAIL_TITLE'],
					$text_msg,
					$html_msg
				);
				$_SESSION['mail_sent']['lost_ids'] = 1;
			}
			return true;
		} else {
			$errors['general'] = $tr['ACCOUNT.LOST_IDS_ERROR'];
		}
	}
	return false;
}

function utils_get_contact_msg($values, &$errors) {
	
	if(!isset($_SESSION['mail_sent']['contact']) || ($_SESSION['mail_sent']['contact'] != 1)) {	
		$text_msg = $values['contact_text'];
		$html_msg = '<html><body>' . strtr($values['contact_text'], ["\n" => "<br />"]) . '</body</html>';
		send_mail(
			['name' => 'Contact Sign-a-pdf.com', 'mail' => 'contact@sign-a-pdf.com'],
			['name' => $values['user_name'], 'mail' => $values['user_email']],
			'Contact Sign-a-pdf',
			$text_msg,
			$html_msg
		);
		$_SESSION['mail_sent']['contact'] = 1;
	}
	return true;
}

function utils_is_signed_in(&$user) {

	if(isset($_SESSION['user_id']) && isset($_SESSION['user_key'])) {
		$user_id = $_SESSION['user_id'];
		$user_key = $_SESSION['user_key'];
	} else if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])) {
		$user_id = $_COOKIE['user_id'];
		$user_key = $_COOKIE['user_key'];
	}
	if(isset($user_id) && isset($user_key)) {
		$res = model_user_exists(['user_id' => $user_id, 'user_key' => $user_key], [], $user);
		if($res != false) {
			if(sizeof($user) != 0) {
				setcookie('user_id', $user_id, time() + 2 * 365 * 86400, '/');
				setcookie('user_key', $user_key, time() + 2 * 365 * 86400, '/');
				$_SESSION['user_id'] = $user_id;
				$_SESSION['user_key'] = $user_key;
				return true;
			}
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
	$res = model_user_exists(['user_name' => $values['user_name']], [], $user);

	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(sizeof($user) != 0) {
		$errors['user_name'] = $tr['ACCOUNT.NAME_ALREADY_EXISTS'];
	}

	$user = [];
	$res = model_user_exists(['user_email' => $values['user_email']], [], $user);

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

	if(!isset($_SESSION['mail_sent']['create']) || ($_SESSION['mail_sent']['create'] != 1)) {	
		$confirm_url = utils_create_link('account', 'validate', $user_id, $values['user_key']);

		$arr = model_get_mail_model($lang, 'create');
		
		$text_msg = strtr($arr['mail_text'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);
		$html_msg = strtr($arr['mail_html'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);

		send_mail(
			['name' => $values['user_name'], 'mail' => $values['user_email']],
			['name' => 'Contact Sign-a-pdf.com', 'mail' => 'contact@sign-a-pdf.com'],
			'[Sign-a-pdf.com] ' . $tr['ACCOUNT.CREATE.MAIL_TITLE'],
			$text_msg,
			$html_msg
		);
		$_SESSION['mail_sent']['create'] = 1;
	}
	return true;
}

function utils_user_update($user_id, $values, &$errors) {

	global $lang, $tr, $page_role;

	if(sizeof($errors) != 0) {
		return false;
	}

	$user = [];
	$res = model_user_exists(['user_name' => $values['user_name']], ['user_id' => $user_id], $user);

	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(sizeof($user) != 0) {
		$errors['user_name'] = $tr['ACCOUNT.NAME_ALREADY_EXISTS'];
	}

	$user = [];
	$res = model_user_exists(['user_email' => $values['user_email']], ['user_id' => $user_id], $user);

	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(sizeof($user) != 0) {
		$errors['user_email'] = $tr['ACCOUNT.EMAIL_ALREADY_EXISTS'];
	}

	if(sizeof($errors) != 0) {
		return false;
	}

	$res = model_user_update($user_id, $values);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	if(!isset($_SESSION['mail_sent']['update']) || ($_SESSION['mail_sent']['update'] != 1)) {	
		$confirm_url = utils_create_link('account', 'update', $user_id, $values['user_key']);

		$arr = model_get_mail_model($lang, 'update');
		
		$text_msg = strtr($arr['mail_text'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);
		$html_msg = strtr($arr['mail_html'], ['%%confirm_url%%' => $confirm_url, '%%user_name%%' => $values['user_name']]);

		send_mail(
			['name' => $values['user_name'], 'mail' => $values['user_email']],
			['name' => 'Contact Sign-a-pdf.com', 'mail' => 'contact@sign-a-pdf.com'],
			'[Sign-a-pdf.com] ' . $tr['ACCOUNT.UPDATE.MAIL_TITLE'],
			$text_msg,
			$html_msg
		);
		$_SESSION['mail_sent']['update'] = 1;
	}
	return true;
}

function utils_create_link($page, $action, $user_id, $user_key) {

	global $lang, $tr, $page_role;

	switch($action) {
		case 'update':
			$act = 2;
			break;
		case 'validate':
		default:
			$act = 1;
	}
	$scheme = (php_uname("n") == 'alain-520-1080fr' ? 'http' : 'https');
	$link = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/' . $lang . '/' . $page_role[$page] . '?s=' . base64_encode(sprintf("%04x", rand(0, 0x0ffff)) . $act . $user_key . $user_id);
	return $link;	
}

function utils_get_link($secret) {

	$secret = base64_decode($secret);

	$act = substr($secret, 4, 1);
	switch($act) {
		case '2':
			$action = 'update';
			break;
		case '1':
		default:
			$action = 'validate';
	}
	$user_key = substr($secret, 5, 16);
	$user_id = substr($secret, 21);

	return [$action, $user_id, $user_key];
}

function utils_user_validate($user_id, $user_key, &$user, &$is_signed_in, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$res = model_user_exists(['user_id' => $user_id, 'user_key' => $user_key], [], $user);
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
			$is_signed_in = true;
		}
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_key'] = $user_key;
		setcookie('user_id', $user_id, time() + 2 * 365 * 86400, '/');
		setcookie('user_key', $user_key, time() + 2 * 365 * 86400, '/');
		return true;
	} else {
		$errors['general'] = $tr['ACCOUNT.VALIDATION_ERROR'];
	}
	return false;
}

function utils_user_reconnect($user_id, $user_key, &$user, &$is_signed_in, &$errors) {

	global $lang, $tr, $page_role;

	$user = [];
	$res = model_user_exists(['user_id' => $user_id, 'user_key' => $user_key, 'user_valid' => 1], [], $user);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}
	if(sizeof($user) != 0) {
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_key'] = $user_key;
		setcookie('user_id', $user_id, time() + 2 * 365 * 86400, '/');
		setcookie('user_key', $user_key, time() + 2 * 365 * 86400, '/');
		$is_signed_in = true;
		return true;
	}
	return false;
}

function utils_user_delete($user_id, &$errors) {

	global $lang, $tr, $page_role;

	$res = model_user_delete($user_id);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	return true;
}

function utils_get_mail_model($mail_lang, $mail_role) {

	global $lang, $tr, $page_role;

	$res = model_get_mail_model($mail_lang, $mail_role);
	if($res == false) {
		$errors['general'] = $tr['ACCOUNT.UNEXPECTED_ERROR'];
		return false;
	}

	return true;
}
