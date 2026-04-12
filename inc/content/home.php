<?php

?>
<div class="container">
    <div class="col-lg-12 ms-0 mb-4">
        <h2><?php echo $tr['MENU.SEND_DOCUMENT']; ?></h2>
    </div>

    <center>
        <h4><?php echo $tr['HOME.ADD_PDF']; ?> :</h4>
        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload-form" style="display: none;">
            <input type="hidden" name="<?php echo ini_get('session.upload_progress.prefix') . ini_get('session.upload_progress.name'); ?>" value="pdf_upload" />
            <input type="file" name="upload_file" id="upload_file" class="upload-file" onchange="return upload.prepare(this); return false;">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
            <input type="hidden" name="action" value="upload_doc" />
            <input type="submit" />
        </form>
        <a href="javascript:void(0)" onclick="this.blur(); return upload.dialog(); return false;" title="<?php echo $tr['HOME.ADD_PDF']; ?>" class="option-holder"><img src="/img/upload.png" alt="" border="0" /></a>
        <div class="notice" id="notice2">
            <span class="tooltips"><span><?php echo $err_msg; ?></span></span>
        </div>
    </center>
<?php
if(!$is_signed_in) {
?>
     <div class="col-lg-12 ms-0 mt-5 mb-3">
        <font size="+1"><i class="bi bi-info-circle-fill" style="color: green;"></i></font>&nbsp; <?php echo strtr($tr['HOME.INTRO'], ['%%account_link%%' => "/{$lang}/{$page_role['account']}"]); ?>
    </div>
<?php
}
?>
</div>

<div style="position: relative;  width: 300px; height: 150px; background-color: rgba(0, 255, 0, 0.2);">
    <canvas id="signCanvas" style="position: absolute; left: 0px; top: 0px; width: 300px; height: 150px;"></canvas>
</div>

<button onclick="return sign.downloadCanvas(); return false;">Click !</button>

<script>
sign.initCanvas();
</script>
