<?php

require_once 'constant.php';

$sign_file = $_GET['sign_file'];
$sign_name = $_GET['sign_name'];

$filename = getcwd() . '/../' . UPLOAD_DIR . '/sign/'. $sign_file . '.png';

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: image/png");

header("Content-Disposition: attachment; filename={$sign_name}.png");

$content = file_get_contents($filename);

echo $content;
