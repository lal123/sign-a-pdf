<?php
?>
<div>
    <p><?php echo $tr['DOCS.SIGN.STEP2.INTRO']; ?> :</p>
    <form method="POST" action="" id="signDocForm" onsubmit="return docs.sendSignDocForm(1); return false;">
        <input type="hidden" name="action" value="get_sign_step" />
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        <input type="hidden" name="sign_step" id="signStep" value="2" />
        <input type="hidden" name="sign_option" id="signOption" value="<?php echo $sign_option; ?>" />
        <input type="hidden" name="sign_text" id="signText" value="<?php echo $sign_text; ?>" />
        <input type="hidden" name="sign_id" value="<?php echo $sign_id; ?>" />
        <input type="hidden" name="sign_width" value="<?php echo $sign_width; ?>" />
        <input type="hidden" name="sign_height" value="<?php echo $sign_height; ?>" />
        <input type="hidden" name="page_option" id="pageOption" value="<?php echo $page_option; ?>" />
        <input type="hidden" name="sign_pages" id="signPages" value="<?php echo $sign_pages; ?>" />
    </form> 
    <img src="/uploads/sign/<?php echo $sign_id; ?>.png" alt="" border="0" class="signSetp2Preview" /> 
</div>