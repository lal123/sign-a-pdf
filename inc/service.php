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
	case 'step2':
		$pdf_id = pdf_convert_to_png($action);
		if($err_msg == '') {
			$page = 'step2';
			pdf_convert_from_png($pdf_id);
		}
		echo json_encode(['pdf_id' => $pdf_id, 'action' => $action], JSON_UNESCAPED_UNICODE);
		break;
}
