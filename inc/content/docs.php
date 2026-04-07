<?php

$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';

$nb_cols = 1;
$bs_dir = 12 / $nb_cols;

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
    $pdf_id = $_GET['pdf_id'];
}

?>

<div class="container">
    <div class="col-lg-6 ms-0 mt-3 mb-3">
        <h2><?php echo $tr['MENU.YOUR_DOCUMENTS']; ?></h2>
    </div>
   <div class="col-lg-6 ms-0 mt-3 mb-3">
<?php
if($pdf_id != '') {
    if($is_signed_in) {
        $doc = model_doc_get_from_pdf_id($pdf_id);
        $doc_name = $doc['doc_name'];
    } else {
        $doc_name = $_SESSION['docs'][$pdf_id]['name'];
    }
    echo $tr['DOCS.YOUR_DOCUMENT'] . ' : ' . $doc_name;
} else {
    echo $tr['DOCS.LIST_DOCUMENTS'] . ' :<br /><br />';
}
?>
    </div>
    <center>

<?php
if($pdf_id != '') {
?>
        <div class="btn-toolbar" style="width: 100%;" role="toolbar" aria-label="Toolbar with button groups">
            <!--<div class="btn-group ms-0" role="group" aria-label="First group">
                <a href="/<?php echo $lang; ?>/"class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['BACK']; ?></a>
            </div>
            <div class="btn-group mx-auto" role="group" aria-label="Second group">
                <a href="/<?php echo $lang; ?>/docs"class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DOCS.SEE_ALL_DOCS']; ?></a>
            </div>-->
            <div class="btn-group mx-auto mb-2" role="group" aria-label="Third group">
                <a href="javascript:void(0)" onclick="return docs.getSignStep({'action': 'get_sign_step', 'pdf_id': '<?php echo $pdf_id; ?>', 'sign_step': 0, 'sign_inc': 1, 'sign_option': 3, 'page_option': 1, 'sign_pages': '', 'lang': '<?php echo $lang; ?>'}); return false;" class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DOCS.SIGN_THIS_DOC']; ?></a>
            </div>
        </div>
<?php
    if(file_exists($img_dir . '/' . $pdf_id .'.png')) {
        echo '<div class="row">';
        echo '<div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        echo '<img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '.png' . '" alt="" border= "0" />';
        echo '</div>';
        echo '</div>';
    } else {
        $img_numb = 0;
        $col = 1;
        while(file_exists($img_dir . '/' . $pdf_id . '-' . $img_numb . '.png')) {
            if($col == 1) {
                echo '<div class="row">';
            }
            echo '<div class="col col-lg-' . $bs_dir . ' col-md-' . $bs_dir . ' col-sm-' . $bs_dir . ' col-xs-' . $bs_dir . '"><img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '-' . $img_numb . '.png' . '" alt="" border= "0" /></div>';
            $col++;
            if($col > $nb_cols) {
                echo '</div>';
                $col = 1;
            }
            $img_numb++;
        }
        if($col <= $nb_cols) {
            echo '</div>';
        }
    }
} else {
    if($is_signed_in) {
        $docs = model_doc_get_list($user['user_id']);
    } else {
        $docs = $_SESSION['docs'];
        uksort($docs, function($a, $b) {
            global $docs;
            return strcasecmp($docs[$b]['time'], $docs[$a]['time']);
        });
    }
    echo '<div class="row">';
    foreach($docs as $pdf_id_key => $details) {
        echo '<div class="doc-small-preview col col-lg-3 col-md-4 col-sm-6 col-xs-12" pdf_id="' . $pdf_id_key . '">';
        echo '<div class="doc-suppr"><a href="javascript:void(0)" onclick="return docs.confirmDelete(\'' . $pdf_id_key . '\'); return false;" class="btn btn-danger btn-sm doc-suppr-btn dark-cyan">x</a></div>';
        echo '<div class="doc-date">' . date($tr['DATE_FORMAT'], $details['time']) . '</div>';
        echo '<div class="doc-name"><a href="/' . $lang . '/docs/' . $pdf_id_key . '/" class="common">' . $details['name'] . '</a></div>';
        if(file_exists($img_dir . '/' . $pdf_id_key .'.png')) {
            $img_src = '/' . UPLOAD_DIR . '/img/' . $pdf_id_key .'.png';
        } else if(file_exists($img_dir . '/' . $pdf_id_key . '-0.png')) {
            $img_src = '/' . UPLOAD_DIR . '/img/' . $pdf_id_key .'-0.png';
        }
        echo '<a href="/' . $lang . '/docs/' . $pdf_id_key . '/"><img class="page-preview" src="' . $img_src . '" alt="" border= "0" /></a>';
        echo '</div>';
    }
    echo '</div>';
}
?>
    </center>
</div>

<?php
if($pdf_id != '') {
?>
<div class="modal" id="signDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['DOCS.SIGN.TITLE']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer justify-content-between">
                <div class="ms-0 mb-0">
                    <button href="/<?php echo $lang; ?>/" id="backButton" onclick="return docs.sendSignDocForm(-1); return false;" class="btn btn-secondary dark-cyan normalized"><?php echo $tr['BACK']; ?></button>
                </div>
                <div class="me-0 mb-0">
                    <button class="btn btn-secondary normalized" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
                    <button id="actionConfirm" onclick="return docs.sendSignDocForm(1); return false;" class="me-0 btn btn-primary dark-cyan normalized"><?php echo $tr['CONTINUE']; ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
?>
<div class="modal" id="deleteDocModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['CONFIRMATION']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <p><?php echo $tr['DOCS.DELETE.CONFIRM']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
                <button id="actionConfirm" type="button" class="btn btn-primary dark-cyan"><?php echo $tr['CONFIRM']; ?></button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
