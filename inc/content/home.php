<?php

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

