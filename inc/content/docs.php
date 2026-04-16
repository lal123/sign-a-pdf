<?php

$nb_cols = 1;
$bs_dir = 12 / $nb_cols;

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
    $pdf_id = $_GET['pdf_id'];
}

function docs_show_list($docs, $signed) {

    global $lang, $tr;

    $count = 0;
    foreach($docs as $pdf_id_key => $details) {

        if($details['signed'] != $signed) {
            continue;
        } else if($count == 0) {
            echo '<div class="col-lg-6 ms-0 mt-3 mb-0">';
            echo '<h4>' . $tr[$signed ? 'SIGNED' : 'UNSIGNED'] . ' :</h4>';
            echo '</div>';
            echo '<div class="row">';
        }
        echo '<div class="doc-small-preview col col-lg-3 col-md-4 col-sm-6 col-xs-12" pdf_id="' . $pdf_id_key . '">';
        echo '<div class="doc-suppr"><a href="javascript:void(0)" onclick="return docs.confirmDelete(\'' . $pdf_id_key . '\'); return false;" class="act bi bi-x-circle-fill" title="' . $tr['DELETE'] . '"></a></div>';
        echo '<div class="doc-down"><a href="javascript:void(0)" onclick="return docs.prepareDownload(\'' .$pdf_id_key. '\'); return false;" class="act bi bi-arrow-down-circle-fill" title="' . $tr['DOWNLOAD'] . '"></a></div>';
        echo '<div class="doc-date">' . date($tr['DATE_FORMAT'], $details['time']) . '</div>';
        echo '<div class="doc-name"><a href="/' . $lang . '/docs/' . $pdf_id_key . '/" class="common">' . $details['name'] . '</a></div>';
        $img_src = '/' . UPLOAD_DIR . '/img/' . ($details['signed'] == 1 ? 'signed/' : '') . $pdf_id_key . ($details['pages'] > 1 ? '-0' : '') . '.png';
        echo '<span class="doc-preview">';
        echo '<a href="/' . $lang . '/docs/' . $pdf_id_key . '/"><img class="page-preview" src="' . $img_src . '" alt="" border= "0" /></a>';
        echo '</span>';
        echo '</div>';
        $count++;
    }
    if($count > 0) {
        echo '</div>';
    }
}

?>
<div class="container">
    <div class="col-lg-6 ms-0 mt-3 mb-0">
        <h2><?php echo $tr['MENU.YOUR_DOCUMENTS']; ?></h2>
    </div>
   <div class="col-lg-6 ms-0 mt-3 mb-3">
<?php
if($pdf_id != '') {
    if($is_signed_in) {
        $doc = model_doc_get_from_pdf_id($pdf_id);
        $doc_name = $doc['doc_name'];
        $doc_signed = ($doc['doc_signed'] == 1);
        $doc_size = $doc['doc_size'];
        $doc_time = strtotime($doc['doc_creato']);
        $doc_pages = $doc['doc_pages'];
    } else {
        $doc_name = $_SESSION['docs'][$pdf_id]['name'];
        $doc_signed = (isset($_SESSION['docs'][$pdf_id]['signed']) && ($_SESSION['docs'][$pdf_id]['signed'] == 1));
        $doc_size = $_SESSION['docs'][$pdf_id]['size'];
        $doc_time = $_SESSION['docs'][$pdf_id]['time'];
        $doc_pages = $_SESSION['docs'][$pdf_id]['pages'];
    }
    echo $tr['DOCS.YOUR_DOCUMENT'] . ' : ' . $doc_name . " ({$doc_pages} page" . ($doc_pages > 1 ? 's' : '') . ")";
} else {
    echo $tr['DOCS.LIST_DOCUMENTS'];
}
?>
    </div>
<?php
if($pdf_id != '') {
?>
    <div id="sign_toolbar" class="btn-toolbar" style="width: 100%;" role="toolbar" aria-label="">
        <div class="btn-group mx-auto mb-2" role="group" aria-label="">
            <a href="javascript:void(0)" id="signButton" onclick="<?php if(true || (php_uname("n") == 'alain-520-1080fr') || (isset($user) && ($user['user_id'] == 1))) { ?>return docs.initSign('<?php echo $pdf_id; ?>'); <?php } ?>return false;" class="btn btn-primary btn-lg dark-cyan<?php if($doc_signed == 1) { echo ' disabled'; } ?>"><?php echo $tr['DOCS.SIGN_THIS_DOC']; ?></a>
        </div>
        <div class="btn-group mx-auto mb-2" role="group" aria-label="">
            <a href="javascript:void(0)" id="downloadButton" onclick="return docs.prepareDownload('<?php echo $pdf_id; ?>'); return false;" class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DOWNLOAD']; ?></a>
        </div>
        <div class="btn-group mx-auto mb-2" role="group" aria-label="">
            <a href="javascript:void(0)" id="deleteButton" onclick="return docs.confirmDelete('<?php echo $pdf_id; ?>'); return false;" class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DELETE']; ?></a>
        </div>
    </div>

    <div class="container" id="docs-container">
<?php
    $col = 1;
    for($img_numb = 1 ; $img_numb <= $doc_pages ; $img_numb++) {
        if($col == 1) {
            echo '<div class="row">';
        }
        echo '<div class="col col-lg-' . $bs_dir . ' col-md-' . $bs_dir . ' col-sm-' . $bs_dir . ' col-xs-' . $bs_dir . ' page-container" id="' . $pdf_id . '-' . ($img_numb - 1) . '">';
        echo '<div class="page-content">';
        echo '<img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . ($doc_signed ? 'signed/' : '') . $pdf_id . ($doc_pages > 1 ? '-' . ($img_numb - 1) : '') . '.png' . '" alt="" border= "0" />';
        echo '</div>';
        echo '</div>';
        $col++;
        if($col > $nb_cols) {
            echo '</div>';
            $col = 1;
        }
    }
    if($col <= $nb_cols) {
        echo '</div>' . "\n";
    }
?>
    </div>
<?php    
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
    docs_show_list($docs, 1);
    docs_show_list($docs, 0);
}
?>
</div>
<?php
if($pdf_id != '') {
?>
<div class="modal" data-bs-backdrop="static" id="signDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['DOCS.SIGN.TITLE']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer justify-content-between">
                <div class="ms-0 mb-0">
                    <button id="backButton" onclick="return docs.sendSignDocForm(-1); return false;" class="btn btn-secondary dark-cyan normalized"><?php echo $tr['BACK']; ?></button>
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
}
?>
<div class="modal" data-bs-backdrop="static" id="deleteDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
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
<div class="modal" data-bs-backdrop="static" id="downloadDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['DOCS.DOWNLOAD.TITLE']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody"><?php echo $tr['DOCS.DOWNLOAD.PREPARING']; ?> ...</div>
            <div class="modal-footer">
                <button class="btn btn-secondary normalized" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
            </div>
        </div>
    </div>
</div>
