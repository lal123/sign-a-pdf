<?php

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    exit();
}

require_once 'utils.php';

$action = '';
if(isset($_POST['action'])) {
	$action = $_POST['action'];
}

$lang = 'en';
if(isset($_POST['lang'])) {
	$lang = $_POST['lang'];
}

$err_msg = '';

switch($action) {
	case 'upload_doc':
		$max_docs_numb = ($is_signed_in ? USER_MAX_DOCS_NUMB : MAX_DOCS_NUMB);
		if($docs_numb >= $max_docs_numb) {
			$res = json_encode(['pdf_id' => '', 'err_msg' => strtr($tr['UPLOAD.MAX_DOCS_NUMB'], ['%%max_docs_numb%%' => $max_docs_numb]), 'name' => ''], JSON_UNESCAPED_UNICODE);
		} else { 
			$res = pdf_convert_to_png();
			$arr = json_decode($res, true);
			if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
				$pdf_id = $arr['pdf_id'];
				if($is_signed_in) {
					$user_id = $user['user_id'];
					model_doc_create(['user_id' => $user_id, 'pdf_id' => $arr['pdf_id'], 'name' => $arr['name'], 'size' => $arr['size']]);
				} else {
					$_SESSION['docs'][$pdf_id]['size'] = $arr['size'];
					$_SESSION['docs'][$pdf_id]['name'] = $arr['name'];
					$_SESSION['docs'][$pdf_id]['time'] = time();
				}
			}
		}
		/*
		if($err_msg == '') {
			$page = 'docs';
			//pdf_convert_from_png($pdf_id);
		}
		*/
		echo $res;
		break;
	case 'delete_doc':
		$pdf_id = $_POST['pdf_id'];
		if($is_signed_in) {
			if(!model_doc_delete($pdf_id)) {
				//??
			}
			$docs_numb = model_doc_get_numb($user['user_id']);
		} else {
			unset($_SESSION['docs'][$pdf_id]);
			$docs_numb = sizeof($_SESSION['docs']);
		}
		if($docs_numb > 0) {
			echo "$('.doc-small-preview[pdf_id=" . $pdf_id . "]').remove();\n";
			echo "$('.docs_numb').html('({$docs_numb})');\n";
		} else {
			echo "document.location.href = '/{$lang}/';\n";
		}
		break;
	case 'delete_account':
		$user_id = $_POST['user_id'];
		$errors = [];
		if (utils_user_delete($user_id, $errors)) {
			echo "document.location.href = '/{$lang}/';\n";
		} else {
			echo "console.log('errors', " . json_encode($errors, JSON_UNESCAPED_UNICODE) . ");\n";
		}
		break;
}
