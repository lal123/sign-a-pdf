<?php

function signs_show_list($signs) {

    global $lang, $tr, $img_dir;

    $count = 1;
    foreach($signs as $sign_file_id => $details) {

        echo '<div class="sign-small-preview col col-lg-3 col-md-4 col-sm-6 col-xs-12" sign_file_id="' . $sign_file_id . '">';
        echo '<div class="sign-suppr"><a href="javascript:void(0)" onclick="return sign.confirmDelete(\'' . $sign_file_id . '\'); return false;" class="act bi bi-x-circle-fill" title="' . $tr['DELETE'] . '"></a></div>';
        echo '<div class="sign-down"><a href="javascript:void(0)" onclick="return sign.download(\'' .$sign_file_id. '\', \'signature-' . $details['order'] . '\'); return false;" class="act bi bi-arrow-down-circle-fill" title="' . $tr['DOWNLOAD'] . '"></a></div>';
        echo '<div class="sign-date">' . date($tr['DATE_FORMAT'], $details['time']) . '</div>';
        echo '<div class="sign-name">#' . $details['order']. '</div>';
        $img_src = '/' . UPLOAD_DIR . '/sign/' . $sign_file_id . '.png';
        echo '<span class="sign-preview">';
        echo '<img class="sign-img-preview" src="' . $img_src . '" alt="" border= "0" />';
        echo '</span>';
        echo '</div>';
        $count++;
    }
}

?>
<div class="container">
    <div class="col-lg-6 ms-0 mt-3 mb-0">
        <h2><?php echo $tr['MENU.YOUR_SIGNATURES']; ?></h2>
    </div>
   <div class="col-lg-6 ms-0 mt-3 mb-3">
<?php
    echo $tr['SIGNS.LIST_SIGNS'];
?>
    </div>
    <div class="row">
<?php
   if($is_signed_in) {
        $signs = model_sign_get_list($user['user_id']);
    } else {
        $signs = [];
        if(isset($_SESSION['signs']) && is_array($_SESSION['signs']) && (sizeof($_SESSION['signs']) > 0)) {
            $signs = $_SESSION['signs'];
            uasort($signs, function($a, $b) {
                return (intval($b['time']) > intval($a['time']) ? 1 : -1);
            });
        }
    }
    signs_show_list($signs);
?>
    </div>
</div>

<div class="modal" data-bs-backdrop="static" id="deleteSignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['CONFIRMATION']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <p><?php echo $tr['SIGNS.DELETE.CONFIRM']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
                <button id="actionConfirm" type="button" class="btn btn-primary dark-cyan"><?php echo $tr['CONFIRM']; ?></button>
            </div>
        </div>
    </div>
</div>
