<?php
?>
<form method="post" action="/inc/up.php" enctype="multipart/form-data" id="upload_form" class="upload-form" target="hidden_frame">
    <input type="file" name="upload_file" id="upload_file" class="upload-file" />
    <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
    <div class="notice" id="notice2">
        <span class="tooltips"><span></span></span>
    </div>
    <input type="submit" />
</form>
<iframe name="hidden_frame" id="hidden_frame" class="hidden-frame" frameborder="0" scroll="no"></iframe>
