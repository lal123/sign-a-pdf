<?php

switch($action) {

    case 'step2': 
        $img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';
        $img_numb = 0;
        while(file_exists($img_dir . '/' . $pdf_id . '-' . ($img_numb + 1) . '.png')) {
            echo '<img src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '-' . ($img_numb + 1) . '.png' . '" alt="" border= "0" />';

            $img_numb++;
        }
        echo "[pdf_id][{$pdf_id}][img_seq][{$img_numb}]";




        break;

    default:
?>
<form method="post" enctype="multipart/form-data" id="upload_form" class="upload-form">
    <input type="file" name="upload_file" id="upload_file" class="upload-file" />
    <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
    <input type="hidden" name="action" value="step2" />
    <div class="notice" id="notice2">
        <span class="tooltips"><span><?php echo $err_msg; ?></span></span>
    </div>
    <input type="submit" />
</form>

<?php

}

