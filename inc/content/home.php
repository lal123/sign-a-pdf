<?php

?>
<center>
    <div class="container">
        <h3><?php echo $tr['HOME.ADD_PDF']; ?> :</h3>
        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload-form" style="display: none;">
            <input type="hidden" name="<?php echo ini_get('session.upload_progress.prefix') . ini_get('session.upload_progress.name'); ?>" value="pdf_upload" />
            <input type="file" name="upload_file" id="upload_file" class="upload-file" onchange="return upload.prepare(this); return false;">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
            <input type="hidden" name="action" value="upload_doc" />
            <input type="submit" />
        </form>
        <a href="javascript:void(0)" onclick="this.blur(); return upload.dialog(); return false;" title="Ajouter un PDF" class="option-holder"><img src="/img/upload.png" alt="" border="0" /></a>
        <div class="notice" id="notice2">
            <span class="tooltips"><span><?php echo $err_msg; ?></span></span>
        </div>
    </div>
</center>