<?php
?>
<div>
    <p><?php echo $tr['DOCS.SIGN.STEP2.INTRO']; ?> :</p>
    <form method="POST" action="" id="signDocForm" onsubmit="return docs.sendSignDocForm(); return false;">
        <input type="hidden" name="action" value="get_sign_step" />
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        <input type="hidden" name="sign_step" id="signStep" value="2" />
        <input type="hidden" name="sign_option" id="signOption" value="<?php echo $sign_option; ?>" />
        <input type="hidden" name="sign_text" id="signText" value="<?php echo $sign_text; ?>" />
        <input type="hidden" name="img_id" value="<?php echo $img_id; ?>" />
        <input type="hidden" name="page_option" id="pageOption" value="<?php echo $page_option; ?>" />
        <input type="hidden" name="sign_pages" id="signPages" value="<?php echo $sign_pages; ?>" />
    </form> 
    <img src="/uploads/img/<?php echo $img_id; ?>.png" alt="" border="0" /> 
</div>