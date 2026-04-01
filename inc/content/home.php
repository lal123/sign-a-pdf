<?php

?>
<form method="post" enctype="multipart/form-data" id="upload_form" class="upload-form" style="display: none;">
    <input type="file" name="upload_file" id="upload_file" class="upload-file" onchange="return upload.prepare(); return false;">
    <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
    <input type="hidden" name="action" value="step2" />
    <div class="notice" id="notice2">
        <span class="tooltips"><span><?php echo $err_msg; ?></span></span>
    </div>
    <input type="submit" />
</form>


<a href="javascript:void(0)" onclick="this.blur(); return upload.dialog(); return false;" title="Ajouter un PDF" class="option-holder"><img src="/img/upload.png" alt="" border="0" /></a>