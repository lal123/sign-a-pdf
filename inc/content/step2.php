<?php

$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';
$img_numb = 0;
while(file_exists($img_dir . '/' . $pdf_id . '-' . ($img_numb + 1) . '.png')) {
    echo '<img style="max-width: 500px;" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '-' . ($img_numb + 1) . '.png' . '" alt="" border= "0" />';
    $img_numb++;
}
