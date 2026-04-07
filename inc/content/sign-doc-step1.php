<?php
?>
<div>
    <p><?php echo $tr['DOCS.SIGN.STEP1.INTRO']; ?> :</p>
    <form method="POST" action="" id="signDocForm" onsubmit="return docs.sendSignDocForm(1); return false;">
        <input type="hidden" name="action" value="get_sign_step" />
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        <input type="hidden" name="sign_step" id="signStep" value="1" />
        <input type="hidden" name="page_option" id="pageOption" value="<?php echo $page_option; ?>" />
        <input type="hidden" name="sign_pages" id="signPages" value="<?php echo $sign_pages; ?>" />
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="1" id="signOption1"<?php if($sign_option == 1) { echo ' checked="checked"'; } ?> onclick="return docs.showSignPanel(1); return true;" />
            <label class="form-check-label" for="signOption1">
                <?php echo $tr['SIGN.OPTIONS.CREA.INVITE']; ?>
            </label>
            <div class="form-panel<?php if($sign_option == 1) { echo ' showed'; } ?>" id="formPanel1">
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="2" id="signOption2"<?php if($sign_option == 2) { echo ' checked="checked"'; } ?> onclick="return docs.showSignPanel(2); return true;" />
            <label class="form-check-label" for="signOption2">
                <?php echo $tr['SIGN.OPTIONS.STOR.INVITE']; ?>
            </label>
            <div class="form-panel<?php if($sign_option == 2) { echo ' showed'; } ?>" id="formPanel2">
            </div>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="sign_option" value="3" id="signOption3"<?php if($sign_option == 3) { echo ' checked="checked"'; } ?> onclick="return docs.showSignPanel(3); return true;" />
            <label class="form-check-label" for="radioDefault2">
                <?php echo $tr['SIGN.OPTIONS.TEXT.INVITE']; ?>
            </label>
            <div class="form-panel<?php if($sign_option == 3) { echo ' showed'; } ?>" id="formPanel3">
                <div class="col-lg-8 ms-1 mt-1 mb-0">
                    <div class="form-group">
                        <label for="signText"><?php echo $tr['SIGN.OPTIONS.TEXT.LABEL']; ?> :</label>
                        <input type="text" name="sign_text" id="signText" maxlength="50" class="form-control" aria-describedby="signTextHelp" required="required" onfocus="$(this).removeClass('is-invalid'); $('#globalError').empty();" placeholder="<?php echo $tr['SIGN.OPTIONS.TEXT.PLACEHOLDER']; ?>" value="<?php echo $sign_text; ?>" />
                          <div class="invalid-feedback"><?php echo $tr['SIGN.OPTIONS.TEXT.INVALID']; ?></div>
                     </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ms-0 mt-2 mb-0" id="globalError" style="color: red;"><?php echo $arr['err_msg']; ?></div>
    </form>
</div>