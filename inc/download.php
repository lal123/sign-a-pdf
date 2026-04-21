<?php

require_once 'utils.php';

$pdf_id = $_GET['file'];

if($is_signed_in) {
	$doc = model_doc_get_from_pdf_id($pdf_id);
	$name = $doc['doc_name'];
	$pages = $doc['doc_pages'];
	$signed = $doc['doc_signed'];
	$doc_size = $doc['doc_size'];
	if($doc['doc_user_id'] != $user['user_id']) die();
} else {
	$doc = $_SESSION['docs'][$pdf_id];
	if(!isset($doc)) die();
	$name = $doc['name'];
	$pages = $doc['pages'];
	$signed = $doc['signed'];
	$doc_size = $doc['size'];
}

$filename = getcwd() . '/../' . UPLOAD_DIR . '/pdf/' . ($signed ? 'signed/' : '') . $pdf_id . '.pdf';

if($signed == 1) {
	if($doc_size == -1) {
		pdf_convert_from_png($pdf_id, $pages);
		$doc_size = filesize($filename);
		if($is_signed_in) {
			model_doc_update_size($pdf_id, $doc_size);
		} else {
			$_SESSION['docs'][$pdf_id]['size'] = $doc_size;
		}
	}
}

db_close();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: application/pdf");

header("Content-Disposition: attachment; filename={$name}");

$content = file_get_contents($filename);

echo $content;
