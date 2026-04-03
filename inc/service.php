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
		$pdf_id = $_POST['pdf_id'];
		unset($_SESSION['docs'][$pdf_id]);
		echo "$('.doc-small-preview[pdf_id=" . $pdf_id . "]').remove();\n";
		break;
}
