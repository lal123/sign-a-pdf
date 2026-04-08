<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: application/pdf");

$file = $_GET['file'];
$name = $_GET['name'];

header("Content-Disposition: attachment; filename={$name}");

$content = file_get_contents(getcwd() . '/../' . $file);

echo $content;
