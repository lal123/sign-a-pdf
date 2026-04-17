<?php

?>
<div class="container">
    <div class="col-lg-12 ms-0 mb-0">
        <h2><?php echo $tr['MENU.SEND_DOCUMENT']; ?></h2>
    </div>
    <div class="col-lg-8 ms-0 mt-3 mb-4">
        <?php echo $tr['HOME.INTRO']; ?>
    </div>
    <center>
        <h4><?php echo $tr['HOME.ADD_PDF']; ?> :</h4>
        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload-form" style="display: none;" name="send_pdf">
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
        <font size="+1"><i class="bi bi-info-circle-fill" style="color: green;"></i></font>&nbsp; <?php echo strtr($tr['HOME.ADVICE'], ['%%account_link%%' => "/{$lang}/{$page_role['account']}"]); ?>
    </div>
<?php
}
?>
</div>

<div class="modal fade" id="uploadModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadModalLabel"><?php echo $tr['UPLOAD.SENDING_DOC']; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-info"><br /></div>
                <div><br /></div>
                <div id="modal-progress" class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 24px;">
                    <div id="modal-progress-bar" class="progress-bar text-bg-success" style="width: 0%"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
            </div>
        </div>
    </div>
</div>

