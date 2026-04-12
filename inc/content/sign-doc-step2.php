<?php
?>
<div>
    <p><?php echo $tr[$sign_option == 1 ? 'DOCS.SIGN.STEP2.SIGN_IT' : 'DOCS.SIGN.STEP2.INTRO']; ?> :</p>
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
        <input type="hidden" name="sign_data" value="" />
    </form>
<?php
    switch($sign_option) {
        case 1:
            echo '    <div class="sign-container"><canvas id="signCanvas" class="sign-canvas"></canvas>' . "\n";
            echo '    <div class="clear-canvas"><a href="javascript:void(0)" onclick="return sign.clearCanvas(); return false;" class="act bi bi-x-circle-fill" title="' . $tr['CLEAR'] . '"></a></div>' . "\n";
            echo '</div>' . "\n";
            break;
        default:
            echo '    <img src="/uploads/sign/' . $sign_id .'.png" alt="" border="0" class="signSetp2Preview" />' . "\n" ;
    }
?>
</div>
<div class="lg-8 ms-0 mt-2 mb-0" id="globalError" style="color: red;"><?php echo $arr['err_msg']; ?></div>
