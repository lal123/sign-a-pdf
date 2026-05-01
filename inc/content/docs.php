<?php

$nb_cols = 1;
$bs_dir = 12 / $nb_cols;

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
    $pdf_id = $_GET['pdf_id'];
}

function docs_show_list($docs, $signed) {

    global $lang, $tr, $is_signed_in;

    $count = 0;
    foreach($docs as $pdf_id_key => $details) {
        
        if($is_signed_in) {
            $doc_id = $details['doc_id'];
            $page = model_page_get_from_doc_id_and_index($doc_id, 1);
            $img_filename = getcwd() . '/' . UPLOAD_DIR . '/img/' . ($details['signed'] == 1 ? 'signed/' : '') . $page['page_id'] . '.png';
            $img_src = '/' . UPLOAD_DIR . '/img/' . ($details['signed'] == 1 ? 'signed/' : '') . $page['page_id'] . '.png';
        } else {
            foreach($_SESSION['docs'][$pdf_id_key]['page'] as $page_key => $page_details) {
                if(($page_details['page_index'] == 1) && ($page_details['page_available'] == 1)) {
                    $img_filename = getcwd() . '/' . UPLOAD_DIR . '/img/' . ($details['signed'] == 1 ? 'signed/' : '') . $page_detaila['page_id'] . '.png';
                    $img_src = '/' . UPLOAD_DIR . '/img/' . ($details['signed'] == 1 ? 'signed/' : '') . $page_details['page_id'] . '.png';
                    break;
                }
            }
        }

        /*
        if(!file_exists($img_filename)) {
            unset($_SESSION['docs'][$signed][$pdf_id_key]);
            continue;
        }
        */

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
        echo '<div class="doc-name"><a href="/' . $lang . '/docs/' . $pdf_id_key . '" class="common">' . $details['name'] . '</a></div>';
        echo '<span class="doc-preview">';
        echo '<a href="/' . $lang . '/docs/' . $pdf_id_key . '"><img class="page-preview" src="' . $img_src . '" alt="" border= "0" /></a>';
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
        $doc_id = $doc['doc_id'];
        $doc_name = $doc['doc_name'];
        $doc_signed = $doc['doc_signed'];
        $doc_size = $doc['doc_size'];
        $doc_time = strtotime($doc['doc_creato']);
        $doc_pages = $doc['doc_pages'];
        /*
        $pages = pdf_check_pages_numb(getcwd() . '/' . UPLOAD_DIR . '/img' . ($doc_signed ? '/signed' : ''), $pdf_id);
        if($pages != $doc_pages) {
            model_doc_update_pages($pdf_id, $pages);
            $doc_pages = $pages;
        }
        */
        $page_enum = model_page_get_list_from_doc_id($doc_id);
    } else {
        //print_r($_SESSION);
        $doc_name = $_SESSION['docs'][$pdf_id]['name'];
        $doc_signed = (isset($_SESSION['docs'][$pdf_id]['signed']) && ($_SESSION['docs'][$pdf_id]['signed'] == 1) ? 1 : 0);
        $doc_size = $_SESSION['docs'][$pdf_id]['size'];
        $doc_time = $_SESSION['docs'][$pdf_id]['time'];
        $doc_pages = $_SESSION['docs'][$pdf_id]['pages'];
        /*
        $pages = pdf_check_pages_numb(getcwd() . '/' . UPLOAD_DIR . '/img' . ($doc_signed ? '/signed' : ''), $pdf_id);
        if($pages != $doc_pages) {
            $_SESSION['docs'][$pdf_id]['pages'] = $pages;
            $doc_pages = $pages;
        }
        */
        $page_enum = [];
        if(isset($_SESSION['docs'][$pdf_id]['page'])) {
            foreach($_SESSION['docs'][$pdf_id]['page'] as $page_index => $details) {
                if($details['page_available'] == 1) $page_enum[] = ['page_id' => $details['page_id'], 'page_numb' => $details['page_index']];
            }
        }

        uasort($page_enum, function($a, $b) {
            return (intval($a['page_numb']) > intval($b['page_numb']) ? 1 : -1);
        });

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
            <a href="javascript:void(0)" id="signButton" onclick="return docs.initSign('<?php echo $pdf_id; ?>'); return false;" class="btn btn-primary btn-lg dark-cyan<?php if($doc_signed == 1) { echo ' disabled'; } ?>"><?php echo $tr['DOCS.SIGN_THIS_DOC']; ?></a>
        </div>
        <div class="btn-group mx-auto mb-2" role="group" aria-label="">
            <a href="javascript:void(0)" id="downloadButton" onclick="return docs.prepareDownload('<?php echo $pdf_id; ?>'); return false;" class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DOWNLOAD']; ?></a>
        </div>
        <div class="btn-group mx-auto mb-2" role="group" aria-label="">
            <a href="javascript:void(0)" id="deleteButton" onclick="return docs.confirmDelete('<?php echo $pdf_id; ?>'); return false;" class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['DELETE']; ?></a>
        </div>
    </div>

    <div class="container" id="doc-container">
<?php


$col = 1;
foreach($page_enum as $page_index => $page_details) {
    if($col == 1) {
        echo '<div class="row">';
    }
    echo '<div class="col col-lg-' . $bs_dir . ' col-md-' . $bs_dir . ' col-sm-' . $bs_dir . ' col-xs-' . $bs_dir . ' page-container" page_id="' . $page_details['page_id'] . '">';
    echo '<div class="page-content">';
    echo '<img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . ($doc_signed == 1 ? 'signed/' : '') . $page_details['page_id'] . '.png' . '" alt="" border= "0" />';
    echo '</div>';
    echo '</div>';
    $col++;
    if($col > $nb_cols) {
        echo '</div>';
        $col = 1;
    }
}
if($col < $nb_cols) {
    echo '</div>' . "\n";
}
?>
        <div id="nav-bar">
            <div class="form-group row g-3">
                <div class="col-sm-5 col-auto">
                    <label for="navPage">Page&nbsp;</label>
                    <select name="nav_page" class="act" id="navPage" onchange="return docs.initChangePage(this.value); return false;"<?php if($doc_pages <= 1) echo ' disabled="disabled"'; ?>>
<?php
for($img_numb = 1 ; $img_numb <= $doc_pages ; $img_numb++) {
    echo '                            <option value="' . $img_numb .'">' . $img_numb .'</option>' . "\n";
}
?>
                    </select>
                </div>
                <div class="col-sm-4 col-auto" style="text-align: right">
                    <a href="javascript:void(0)" class="bi bi-skip-start-fill act" title="<?php echo $tr['DOCS.NAV_PAGE.FIRST_PAGE']; ?>" onmousedown="return docs.initChangePage(1); return false;"></a>
                    <a href="javascript:void(0)" class="bi bi-caret-left-fill act" title="<?php echo $tr['DOCS.NAV_PAGE.PREV_PAGE']; ?>" onmousedown="return docs.initChangePage(parseInt($('#navPage').val()) - 1); return false;"></a>
                    <a href="javascript:void(0)" class="bi bi-caret-right-fill act small" title="<?php echo $tr['DOCS.NAV_PAGE.NEXT_PAGE']; ?>" onmousedown="return docs.initChangePage(parseInt($('#navPage').val()) + 1); return false;"></a>
                    <a href="javascript:void(0)" class="bi bi-skip-end-fill act" title="<?php echo $tr['DOCS.NAV_PAGE.LAST_PAGE']; ?>" onmousedown="return docs.initChangePage(<?php echo $doc_pages; ?>); return false;"></a>
                </div>
                <div class="col-sm-3 col-auto" style="text-align: right">
                    <a href="javascript:void(0)" class="bi bi-arrow-counterclockwise act" title="<?php echo $tr['DOCS.NAV_PAGE.ROTATE_LEFT']; ?>" onmousedown="return docs.rotatePage($('#navPage').val(), <?php echo $doc_signed; ?>, -1); return false;"></a>
                    <a href="javascript:void(0)" class="bi bi-arrow-clockwise act" title="<?php echo $tr['DOCS.NAV_PAGE.ROTATE_RIGHT']; ?>" onmousedown="return docs.rotatePage($('#navPage').val(), <?php echo $doc_signed; ?>, 1); return false;"></a>
                </div>
            </div>
        </div>
    </div>
<?php    
} else {
   if($is_signed_in) {
        $docs = model_doc_get_list($user['user_id']);
    } else {
        $docs = (isset($_SESSION['docs']) ? $_SESSION['docs'] : []);
        uasort($docs, function($a, $b) {
            return (intval($b['time']) > intval($a['time']) ? 1 : -1);
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
<div class="modal" data-bs-backdrop="static" id="confirmDocModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['CONFIRMATION']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <p><?php echo $tr['DOCS.MODIFY.CONFIRM']; ?></p>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="ms-0 mb-0">
                    <button type="button" class="btn btn-secondary normalized" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
                </div>
                <div class="me-0 mb-0">
                    <button id="actionConfirmNo" class="btn btn-primary dark-cyan normalized"><?php echo $tr['NO']; ?></button>
                    <button id="actionConfirmOk" type="button" class="btn btn-primary dark-cyan normalized"><?php echo $tr['YES']; ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" data-bs-backdrop="static" id="validateSignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $tr['DOCS.SIGN_DOC.TITLE']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="modal-info"><?php echo $tr['DOCS.SIGN_DOC.PREPARING']; ?></div>
                <div><br /></div>
                <div class="modal-progress progress" role="progressbar" aria-label="validateProgress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height: 24px;">
                    <div class="modal-progress-bar progress-bar text-bg-success" style="width: 0%"></div>
                </div>
            </div>
            <div class="col-lg-6 ms-3 mt-2 mb-0 global-error" style="color: red;"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary normalized" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
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
            <div class="modal-body" id="modalBody"><?php echo $tr['DOCS.DOWNLOAD.PREPARING']; ?> <span class="waiting"></span></div>
            <div class="modal-footer">
                <button class="btn btn-secondary normalized" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
            </div>
        </div>
    </div>
</div>
