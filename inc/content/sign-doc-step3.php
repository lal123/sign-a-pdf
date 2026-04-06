<?php
?>
<div>
<form method="POST" action="" id="signDocForm" onsubmit="return docs.sendSignDocForm(); return false;">
    <input type="hidden" name="action" value="get_sign_step" />
    <input type="hidden" name="pdf_id" value="<?php echo $pdf_id; ?>" />
    <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
    <input type="hidden" name="sign_step" id="signStep" value="3" />
    <input type="hidden" name="img_id" value="<?php echo $img_id; ?>" />
</form>
Yo
<img src="/uploads/img/<?php echo $img_id; ?>.png" alt="" border="0" /> 
</div>