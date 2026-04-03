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

$err_msg = '';

switch($action) {
	case 'upload_doc':
		$res = pdf_convert_to_png();
		/*
		if($err_msg == '') {
			$page = 'docs';
			//pdf_convert_from_png($pdf_id);
		}
		*/
		echo $res;
		break;
	case 'delete_doc':
		$lang = $_POST['lang'];
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
