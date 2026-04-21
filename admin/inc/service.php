<?php

require_once '../../inc/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST') {
	die();
}

$action = $_POST['action'];

switch($action) {
	case 'get_docs':
		$user_id = $_POST['user_id'];
		$docs = model_doc_get_list($user_id);
		$docs_json = json_encode($docs, JSON_UNESCAPED_UNICODE);
		echo "docsShow('{$user_id}', '{$docs_json}');\n";
		break;
	case 'get_signs':
		$user_id = $_POST['user_id'];
		$signs = model_sign_get_list($user_id);
		$signs_json = json_encode($signs, JSON_UNESCAPED_UNICODE);
		echo "signsShow('{$user_id}', '{$signs_json}');\n";
		break;
}


?>
