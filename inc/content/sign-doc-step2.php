<?php
?>
<div>
    <p>
<?php

switch($sign_option) {
    case 1 :
        echo $tr['DOCS.SIGN.STEP2.SIGN_IT'];
        break;
    case 4 :
        echo $tr['DOCS.SIGN.STEP2.CHOOSE_SIGN'];
        if($is_signed_in) {
            $user_id = $user['user_id'];
            $signs = model_sign_get_list($user_id);
        } else {
            $signs = $_SESSION['signs'];
            if(isset($signs) && is_array($signs) && (sizeof($signs) > 1)) {
                uasort($signs, function($a, $b) {
                    return (intval($b['time']) > intval($a['time']) ? 1 : -1);
                });
            }
        }

        break;
    default:
        echo $tr['DOCS.SIGN.STEP2.INTRO'];
}
?> :</p>
    <form method="POST" action="" id="signDocForm" onsubmit="return docs.sendSignDocForm(1); return false;" name="sign_create_step2">
        <input type="hidden" name="action" value="get_sign_step" />
        <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
        <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        <input type="hidden" name="sign_step" id="signStep" value="2" />
        <input type="hidden" name="sign_option" id="signOption" value="<?php echo $sign_option; ?>" />
        <input type="hidden" name="sign_text" id="signText" value="<?php echo $sign_text; ?>" />
        <input type="hidden" name="text_font" id="textFont" value="<?php echo $text_font; ?>" />
        <input type="hidden" name="text_color" id="textColor" value="<?php echo $text_color; ?>" />
        <input type="hidden" name="text_thickness" id="textThickness" value="<?php echo $text_thickness; ?>" />
        <input type="hidden" name="sign_width" value="<?php echo $sign_width; ?>" />
        <input type="hidden" name="sign_height" value="<?php echo $sign_height; ?>" />
        <input type="hidden" name="page_option" id="pageOption" value="<?php echo $page_option; ?>" />
        <input type="hidden" name="sign_pages" id="signPages" value="<?php echo $sign_pages; ?>" />
        <input type="hidden" name="pages" id="pages" value="<?php echo $pages; ?>" />
        <input type="hidden" name="sign_data" value="" />
<?php
    switch($sign_option) {
        case 1:
            echo '    <input type="hidden" name="sign_id" value="' . $sign_id . '" />';
            echo '    <div class="sign-container"><canvas id="signCanvas" class="sign-canvas"></canvas>' . "\n";
            echo '    <div class="clear-canvas"><a href="javascript:void(0)" onclick="return sign.clearCanvas(); return false;" class="act bi bi-trash-fill" title="' . $tr['CLEAR'] . '"></a></div>' . "\n";
            echo '</div>' . "\n";
            break;
        case 4:
            $first = true;
            foreach($signs as $sign_file_id => $details) {
                echo '<div class="form-check mb-2 sign-list-item">';
                echo '<input class="form-check-input" type="radio" name="sign_id" id="sign_' . $sign_file_id . '" value="' . $sign_file_id . '"' . ((isset($sign_id) && ($sign_id == $sign_file_id)) || ((!isset($sign_id) || ($sign_id == '')) && ($first == true)) ? ' checked="checked"' : '') .'/>';
                //echo '&nbsp; ';
                echo '<label for="sign_' . $sign_file_id . '">#' . date($details['order']) . ' - ' . date($tr['DATE_FORMAT'], $details['time']);
                echo '<div class="prev-sign-preview">';
                echo '<img src="/' . UPLOAD_DIR . '/sign/' . $sign_file_id . '.png" alt="" border="0" />';
                echo '</div>';
                echo '</label>';
                echo '</div>';
                $first = false;
            }
            break;
        default:
            echo '    <input type="hidden" name="sign_id" value="' . $sign_id . '" />';
            echo '    <img src="/uploads/sign/' . $sign_id .'.png" alt="" border="0" class="sign-step2-preview" />' . "\n" ;
    }
?>
    </form>
</div>
<div class="lg-8 ms-0 mt-2 mb-0" id="globalError" style="color: red;"><?php echo $arr['err_msg']; ?></div>
