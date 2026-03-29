<?php

if($_SERVER['REQUEST_METHOD']!='POST'){
    exit();
}

$lang = $_POST['lang'];

require_once 'lang.php';
require_once 'constant.php';

if (is_array($_FILES)) {
    $err_msg = '';
    $pdf_id = '';
    $tmp_name = $_FILES['upload_file']['tmp_name'];
    $size = $_FILES['upload_file']['size'];
    $type = $_FILES['upload_file']['type'];
    if($type != 'application/pdf') {
    	$err_msg = 'not a pdf';
    }
    if($err_msg == '') {
	    if ((isset($tmp_name)) && ($size != 0)) {
	        srand((float) microtime() * 1000000);
	        if ($err_msg == '') {
	        	$pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf';
				if(!file_exists($pdf_dir)){
					mkdir($pdf_dir);
					chmod($pdf_dir, 0777);
				}
	            do {
	                $pdf_id = sprintf("%04x", rand(0, 0x0ffff)) . sprintf("%04x", rand(0, 0x0ffff));
	                $pdf_file = $pdf_dir . '/' . $pdf_id . '.pdf';
	            } while (file_exists($pdf_file));
	            move_uploaded_file($tmp_name, $pdf_file);
	        	$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
				if(!file_exists($img_dir)){
					mkdir($img_dir);
					chmod($img_dir, 0777);
				}
				$command = '/usr/bin/pdftoppm -rx 300 -ry 300 "' . $pdf_file . '" -png ' . $pdf_id;
				$output = [];
			    $return_var = 0;
			    exec($command, $output, $return_var);
			    if($return_var != 0) {
			        $err_msg = "{$program} exited with return_var {$return_var}\n";
			    }
	        }
	    } else {
	        $err_msg = 'No data received!';
	    }
	}
    if($err_msg != '') {
        $js_cmd = "alert(decodeURIComponent('" . rawurlencode($err_msg) . "'));";
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
    <head>
        <script><?php echo $js_cmd; ?></script>
    </head>
</html>
