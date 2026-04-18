<?php
?>
<div>
    <div id="colorpicker"></div>
    <p><?php echo $tr['DOCS.SIGN.STEP1.INTRO']; ?> :</p>
    <form method="POST" action="" id="signDocForm" enctype="multipart/form-data" onsubmit="return docs.sendSignDocForm(1); return false;" name="sign_create_step1">
        <input type="hidden" name="action" value="get_sign_step" />
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        <input type="hidden" name="sign_step" id="signStep" value="1" />
        <input type="hidden" name="sign_inc" id="signInc" value="1" />
        <input type="hidden" name="page_option" id="pageOption" value="<?php echo $page_option; ?>" />
        <input type="hidden" name="sign_pages" id="signPages" value="<?php echo $sign_pages; ?>" />
        <input type="hidden" name="text_color" id="textColor" value="<?php echo $text_color; ?>" ?>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="4" id="signOption4"<?php if(($sign_option == 4) && $is_signed_in && ($signs_numb != 0)) { echo ' checked="checked"'; } ?> onclick="return sign.showSignPanel(4); return true;"<?php if(($signs_numb == 0) || !$is_signed_in) { echo ' disabled="disabled"'; } ?> />
            <label class="form-check-label" for="signOption4">
                <?php echo $tr['SIGN.OPTIONS.PREV.INVITE']; ?>
            </label>
            <div class="form-panel mt-1<?php if($sign_option == 4) { echo ' showed'; } ?>" id="formPanel4">
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="1" id="signOption1"<?php if(($sign_option == 1) || (($sign_option == 4) && (!$is_signed_in || ($signs_numb == 0)))) { echo ' checked="checked"'; } ?> onclick="return sign.showSignPanel(1); return true;" />
            <label class="form-check-label" for="signOption1">
                <?php echo $tr['SIGN.OPTIONS.CREA.INVITE']; ?>
            </label>
            <div class="form-panel mt-1<?php if(($sign_option == 1) || (($sign_option == 4) && (!$is_signed_in || ($signs_numb == 0))))  { echo ' showed'; } ?>" id="formPanel1">
                <div class="form-group row g-2">
                    <div class="col-sm-4 col-auto">
                        <label for="textThickness"><?php echo $tr['SIGN.OPTIONS.CREA.THICK.LABEL']; ?> :</label>
                        <select name="text_thickness" id="textThickness" class="form-control" aria-describedby="textThicknessHelp">
<?php
for($thick = 1 ; $thick <= 6 ; $thick++) {
    echo '                            <option value="' . $thick . '"' . (isset($text_thickness) && ($text_thickness == $thick) ? ' selected="selected"' : '') . '>' . $thick . 'px</option>' . "\n";
}
?>
                        </select>
                    </div>
                    <div class="col-sm-3 col-auto">
                        <label for="textColorHolder2"><?php echo $tr['SIGN.OPTIONS.CREA.COLOR.LABEL']; ?> :</label>
                        <div id="textColorHolder2" class="text-color-holder">
                            <a href="javascript:void(0)" onclick="return sign.showColorPicker(this, 'right'); return false;"><span class="bi bi-palette-fill text-color-preview" id="textColorPreview2" style="color: <?php echo $text_color; ?>"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="2" id="signOption2"<?php if($sign_option == 2) { echo ' checked="checked"'; } ?> onclick="return sign.showSignPanel(2); return true;" />
            <label class="form-check-label" for="signOption2">
                <?php echo $tr['SIGN.OPTIONS.STOR.INVITE']; ?>
            </label>
            <div class="form-panel mt-1<?php if($sign_option == 2) { echo ' showed'; } ?>" id="formPanel2">
                <div class="form-group">
                    <label for="signFile"><?php echo $tr['SIGN.OPTIONS.STOR.LABEL']; ?> :</label>
                    <input type="file" name="sign_file" id="signFile" class="form-control" aria-describedby="signFileHelp" onchange="$(this).removeClass('is-invalid'); $('#globalError').empty(); return docs.prepareSignFile(this); return false;" onfocus="" value="" />
                      <div class="invalid-feedback"><?php echo $tr['SIGN.OPTIONS.STOR.INVALID']; ?></div>
                </div>
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="sign_option" value="3" id="signOption3"<?php if($sign_option == 3) { echo ' checked="checked"'; } ?> onclick="return sign.showSignPanel(3); return true;" />
            <label class="form-check-label" for="signOption3">
                <?php echo $tr['SIGN.OPTIONS.TEXT.INVITE']; ?>
            </label>
            <div class="form-panel mt-1<?php if($sign_option == 3) { echo ' showed'; } ?>" id="formPanel3">
                <div class="form-group row g-3">
                    <div class="col-sm-5 col-auto">
                        <label for="signText"><?php echo $tr['SIGN.OPTIONS.TEXT.LABEL']; ?> :</label>
                        <input type="text" name="sign_text" id="signText" maxlength="50" class="form-control" aria-describedby="signTextHelp" onfocus="$(this).removeClass('is-invalid'); $('#globalError').empty();" placeholder="<?php echo $tr['SIGN.OPTIONS.TEXT.PLACEHOLDER']; ?>" value="<?php echo $sign_text; ?>" />
                        <div class="invalid-feedback"><?php echo $tr['SIGN.OPTIONS.TEXT.INVALID']; ?></div>
                    </div>
                    <div class="col-auto">
                        <label for="textFont"><?php echo $tr['SIGN.OPTIONS.TEXT.FONT.LABEL']; ?> :</label>
                        <select name="text_font" id="textFont" class="form-control" aria-describedby="textFontHelp">
                            <option value="allegro-webfont"<?php if(isset($text_font) && ($text_font == 'allegro-webfont')) { echo ' selected="selected"'; } ?>>Allegro</option>
                            <option value="beautiful_es-webfont"<?php if(isset($text_font) && ($text_font == 'beautiful_es-webfont')) { echo ' selected="selected"'; } ?>>Beautiful</option>
                            <option value="marydale_bold-webfont"<?php if(isset($text_font) && ($text_font == 'marydale_bold-webfont')) { echo ' selected="selected"'; } ?>>Marydale</option>
                            <option value="saginawbold-webfont"<?php if(isset($text_font) && ($text_font == 'saginawbold-webfont')) { echo ' selected="selected"'; } ?>>Saginaw</option>
                        </select>
                    </div>
                    <div class="col-sm-3 col-auto">
                        <label for="textColorHolder"><?php echo $tr['SIGN.OPTIONS.TEXT.COLOR.LABEL']; ?> :</label>
                        <div id="textColorHolder" class="text-color-holder">
                            <a href="javascript:void(0)" onclick="return sign.showColorPicker(this, 'top'); return false;"><span class="bi bi-palette-fill text-color-preview" id="textColorPreview" style="color: <?php echo $text_color; ?>"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg-8 ms-0 mt-2 mb-0" id="globalError" style="color: red;"><?php echo $arr['err_msg']; ?></div>
    </form>
</div>