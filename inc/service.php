<?php

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    exit();
}

require_once 'constant.php';
require_once 'get_ip.php';
require_once 'write_log.php';
require_once 'pdf.php';

session_start();

$action = '';
if(isset($_POST['action'])) {
	$action = $_POST['action'];
}

$lang = 'en';
if(isset($_POST['lang'])) {
	$lang = $_POST['lang'];
}

require_once 'lang_' . $lang . '.php';

$err_msg = '';

switch($action) {
	case 'upload_doc':
		if(isset($_SESSION['docs']) && (sizeof($_SESSION['docs']) >= MAX_DOCS_NUMB)) {
			$res = json_encode(['pdf_id' => '', 'err_msg' => $tr['UPLOAD.MAX_DOCS_NUMB'], 'name' => ''], JSON_UNESCAPED_UNICODE);
		} else { 
			$res = pdf_convert_to_png();
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
		unset($_SESSION['docs'][$pdf_id]);
		$docs_numb = sizeof($_SESSION['docs']);
		if($docs_numb > 0) {
			echo "$('.doc-small-preview[pdf_id=" . $pdf_id . "]').remove();\n";
			echo "$('#docs_numb').html('({$docs_numb})');\n";
		} else {
			echo "document.location.href = '/{$lang}/';\n";
		}
		break;
}
