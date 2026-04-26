<?php

require_once '../inc/utils.php';

db_connect();

$users = model_get_user_list();

$an_docs = [];
$an_signs = [];

function get_dir($type, $dir, $rel_dir, &$items) {
    $fh = opendir($dir);
    while($fn = readdir($fh)) {
        if(!preg_match('/^\./', $fn)){
            if(is_dir($dir . '/' . $fn)){
                get_dir($type, $dir . '/' . $fn, $rel_dir . $fn . '/', $items);
            } else {
                if(($type == 'pdf') && preg_match('/^([0-9a-f]{16})\.(pdf)$/', $fn, $matches)) {
            		list(, $pdf_id) = $matches;
            		if(model_doc_get_from_pdf_id($pdf_id) === false) {
                        $signed = (preg_match('/signed$/', $dir) ? 1 : 0);
                        $pages = pdf_check_pages_numb(getcwd() . '/../' . UPLOAD_DIR . '/img' . ($signed == 1 ? '/signed' : ''), $pdf_id);
                        $time = filemtime($dir . '/' . $fn);
                        $preview = '/' . UPLOAD_DIR . '/img/' . ($signed == 1 ? 'signed/' : '') . $pdf_id . ($pages > 1 ? '-0' : '') . '.png';
            			$items[$signed][] = ['pdf_id' => $pdf_id, 'path' => $rel_dir . $pdf_id, 'pages' => $pages, 'signed' => $signed, 'preview' => $preview, 'time' => $time];
            		}
            	} else if(($type == 'sign') && preg_match('/^([0-9a-f]{16})\.(png)$/', $fn, $matches)) {
                    list(, $sign_id) = $matches;
                    $res = model_sign_get_from_file_id($sign_id);
                    if(($res == null) || (sizeof($res) == 0)) {
                        $time = filemtime($dir . '/' . $fn);
                        $preview = '/' . UPLOAD_DIR . '/sign/' . $sign_id . '.png';
                        $items[] = ['sign_id' => $sign_id, 'path' => $rel_dir . $sign_id, 'preview' => $preview, 'time' => $time];
                    }
                }
            }
        }
    }
}

get_dir('pdf', getcwd() . '/../' . UPLOAD_DIR . '/pdf', '', $an_docs);
get_dir('sign', getcwd() . '/../' . UPLOAD_DIR . '/sign', '', $an_signs);

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title>Admin - Index</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="/css/fonts.<?php echo $version_suffix; ?>.css" />
    <link rel="stylesheet" href="/css/bootstrap-5.3.8.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui-1.14.2.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui-structure-1.14.2.min.css" />
    <link rel="stylesheet" href="/css/farbtastic-1.2.css" />
    <link rel="stylesheet" href="/css/screen.<?php echo $version_suffix; ?>.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="/js/jquery-4.0.0.min.js"></script>
    <script src="/js/jquery-ui-1.14.2.min.js"></script>
    <script src="/js/jquery-ui-touch-punch-0.2.3.min.js"></script>
    <script src="/js/bootstrap-5.3.8.min.js"></script>
    <script src="/js/farbtastic-1.2.js"></script>
    <script src="/js/global-<?php echo $lang; ?>.<?php echo $version_suffix; ?>.js"></script>
    <style>
        li.user {
            margin: 0px 0px 6px 0px;*
        }
        li.user.invalid {
            color: #808080;
        }
        li.user.invalid a.common{
            color: #808080;
            border-bottom: dotted 1px #808080;
        }
        .doc-preview-div {
            text-align: center;
            margin: 0px 0px 20px 0px;"
        }
        .doc-preview-container {
            height: 360px;
            max-width: 100%;
            padding: 4px;
        }
        .doc-preview-img {
            max-height: 100%;
            max-width: 249px;
        }
        .sign-preview-div {
            text-align: center;
            margin: 0px 0px 20px 9px;
            border: dotted 1px #606060;
            border-radius: 18px;
            padding: 9px;
        }
        .sign-preview-container {
            width: 300px;
            max-width: 100%;
            padding: 4px;
        }
        .sign-preview-img {
            max-width: 100%;
        }
        .signs_row h5, .docs_row h5 {
            margin: 10px 0px 10px 0px;
        }
        .accept-language {
            max-width: 100%;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
    <script>
        var lang = '<?php echo $lang; ?>';
        function getDocs(user_id) {
            if($('.docs_row[user_id=' + user_id + '] .doc-preview-div.by-user').is(':visible')) {
                $('.docs_row').html('');
                $('.signs_row').html('');
                $('.docs_row[user_id=' + user_id + '] .doc-preview-div.by-user').hide();
            } else {
                $.ajax({
                    url: './inc/service.php',
                    type: 'POST',
                    data: {'action': 'get_docs', 'user_id' : user_id, 'lang': lang}
                }).done(function(data) {
                    eval(data);
                });
            }
            return false;
        }
        function docsShow(user_id, docs_json) {
            $('.docs_row').html('');
            $('.signs_row').html('');
            var docs = JSON.parse(docs_json);
            html_signed = '<h5>Signed docs</h5>';
            html_unsigned = '<h5>Unsigned docs</h5>';
            for(doc_id in docs) {
                html = '';
                var time = new Date(docs[doc_id]['time'] * 1000);
                html+= '<div class="col col-lg-2 col-md-4 col-sm-6 col-xs-12 doc-preview-div by-user">';
                html+= time.toLocaleDateString("fr-FR") + ' ' + time.toLocaleTimeString("fr-FR") + '<br />';
                html+= '<a href="/uploads/pdf/' + (docs[doc_id]['signed'] == 1 ? 'signed/' : '') + doc_id + '.pdf" target="_blank" class="common">' + doc_id + '</a> (' + docs[doc_id]['pages'] +' page' + (docs[doc_id]['pages'] > 1 ? 's' : '') + ')<br />';
                html+= '<div class="doc-preview-container">';
                html+= '<a href="/uploads/pdf/' + (docs[doc_id]['signed'] == 1 ? 'signed/' : '') + doc_id + '.pdf" target="_blank"><img src="/uploads/img/' + (docs[doc_id]['signed'] == 1 ? 'signed/' : '') + doc_id + (docs[doc_id]['pages'] > 1 ? '-0' : '') + '.png" alt="" border="0"  class="doc-preview-img" /></a>';
                html+= '</div>';
                html+= '</div>';
                if(docs[doc_id]['signed'] == 1) {
                    html_signed+= html;
                } else {
                    html_unsigned+= html;
                }
            }
            $('.docs_row.signed[user_id=' + user_id + ']').html(html_signed);
            $('.docs_row.unsigned[user_id=' + user_id + ']').html(html_unsigned);
            $('.docs_row[user_id=' + user_id + '] .doc-preview-div.by-user').show();
            return false;
        }
        function getSigns(user_id) {
            if($('.signs_row[user_id=' + user_id + '] .sign-preview-div.by-user').is(':visible')) {
                $('.docs_row').html('');
                $('.signs_row').html('');
                $('.signs_row[user_id=' + user_id + '] .sign-preview-div.by-user').hide();
            } else {
                $.ajax({
                    url: './inc/service.php',
                    type: 'POST',
                    data: {'action': 'get_signs', 'user_id' : user_id, 'lang': lang}
                }).done(function(data) {
                    eval(data);
                });
            }
            return false;
        }
        function signsShow(user_id, signs_json) {
            $('.docs_row').html('');
            $('.signs_row').html('');
            var signs = JSON.parse(signs_json);
            html = '<h5>Signs</h5>';
            for(sign_id in signs) {
                var time = new Date(signs[sign_id]['time'] * 1000);
                html+= '<div class="col col-lg-2 col-md-4 col-sm-6 col-xs-12 sign-preview-div by-user">';
                html+= time.toLocaleDateString("fr-FR") + ' ' + time.toLocaleTimeString("fr-FR") + '<br />';
                html+= '<a href="/uploads/sign/' + sign_id + '.png" target="_blank" class="common">' + sign_id + '</a><br />';
                html+= '<div class="sign-preview-container">';
                html+= '<a href="/uploads/sign/' + sign_id + '.png" target="_blank"><img src="/uploads/sign/' + sign_id + '.png" alt="" border="0"  class="sign-preview-img" /></a>';
                html+= '</div>';
                html+= '</div>';
            }
            $('.signs_row[user_id=' + user_id + ']').html(html);
            $('.signs_row[user_id=' + user_id + '] .sign-preview-div.by-user').show();
            return false;
        }
    </script>
</head>
<body oncontextmenu="return false;">

<br />

<div class="container-fluid">

    <h4>Registered users</h4>

	<ul style="list-style-type: circle; padding: 0px 0px 0px 20px;">
<?php
foreach($users as $index => $user) {
    $doc_numb = model_doc_get_numb($user['user_id']);
    $doc_total_size = model_doc_get_total_size($user['user_id']);
    $sign_numb = model_sign_get_numb($user['user_id']);
	echo '<li class="user' . ($user['user_valid'] == 1 ? ' valid' : ' invalid') . '">';
    echo '<div class="row">';
	echo '<div class="col-sm-2"><a href="' . utils_create_link('account', 'update', $user['user_id'], $user['user_key']) . '" target= "_blank" class="common">' . $user['user_name'] . '</a></div>';
    echo '<div class="col-sm-2">' . date('d/m/Y H:i:s', strtotime($user['user_creato'])) . '</div>';
    echo '<div class="col-sm-2">';
    if($doc_numb > 0) echo '<a href="javascript:void(0)" class="common" onclick="return getDocs(' . $user['user_id'] . ')">' . $doc_numb . ' document' . ($doc_numb > 1 ? 's' : '') . '</a> (' . utils_formatSizeUnits($doc_total_size) . ')';
    echo '</div>';
    echo '<div class="col-sm-2">';
    if($sign_numb > 0) echo '<a href="javascript:void(0)" class="common" onclick="return getSigns(' . $user['user_id'] . ')">' . $sign_numb . ' signature' . ($sign_numb > 1 ? 's' : '') . '</a>';
    echo '</div>';
    echo '<div class="col-sm-1"><a href="https://whatismyipaddress.com/ip/'. $user['user_ip_address'] . '" target="_blank" class="common">' . $user['user_ip_address'] . '</a></div>';
    echo '<div class="col-sm-3 accept-language">' . $user['user_accept_language'] . '</div>';
    echo '</div>';
    echo '<div class="row docs_row signed" user_id="' . $user['user_id'] . '"></div>';
    echo '<div class="row docs_row unsigned" user_id="' . $user['user_id'] . '"></div>';
    echo '<div class="row signs_row" user_id="' . $user['user_id'] . '"></div>';
	echo '</li>';
}
?>
	</ul>
</div>

<hr style="margin: 0px 0px 14px 0px; padding: 0;" />

<div class="container-fluid">

<h4>Anonymous uploads</h4>


<?php

uksort($an_docs, function($a, $b) {
    global $an_docs;
    return strcasecmp($b, $a);
});

foreach($an_docs as $signed => $docs) {

    uksort($docs, function($a, $b) {
        global $docs;
        return strcasecmp($docs[$b]['time'], $docs[$a]['time']);
    });

    echo '<h5>' . ($signed ? 'Signed' : 'Unsigned') . ' docs (' . sizeof($docs) . ')</h5>';

    echo '<div class="row">';
    foreach($docs as $index => $doc) {
        

        $pages = pdf_check_pages_numb(getcwd() . '/../' . UPLOAD_DIR . '/img' . ($doc['signed'] ? '/signed' : ''), $doc['pdf_id']);
        if($pages != $doc['pages']) {
            $doc['pages'] = $pages;
        }

    	echo '<div class="col col-lg-2 col-md-4 col-sm-6 col-xs-12 doc-preview-div">';
    	echo date('d/m/Y H:i:s', $doc['time']) . '<br />';
        echo '<a href="/' . UPLOAD_DIR . '/pdf/' . ($doc['signed'] ? 'signed/' : '') . $doc['pdf_id'] . '.pdf" target="_blank" class="common">' . $doc['pdf_id'] . '</a> (' . $doc['pages'] . ' page' . ($doc['pages'] > 1 ? 's' : '') . ')' . '<br />';
        echo '<div class="doc-preview-container">';
        echo '<a href="/' . UPLOAD_DIR . '/pdf/' . ($doc['signed'] ? 'signed/' : '') . $doc['pdf_id'] . '.pdf" target="_blank"><img src="' . $doc['preview'] . '" alt="" border="0" class="doc-preview-img" /></a>';
        echo '</div>';
    	echo '</div>';
    }
    echo '</div>';
}
?>
</div>

<div class="container-fluid">
<?php

if(sizeof($an_signs) > 0) {

    uksort($an_signs, function($a, $b) {
        global $an_signs;
        return strcasecmp($an_signs[$b]['time'], $an_signs[$a]['time']);
    });

    echo '<h5>Signs (' . sizeof($an_signs) . ')</h5>';

    echo '<div class="row">';
    foreach($an_signs as $index => $sign) {
        echo '<div class="col col-lg-2 col-md-4 col-sm-6 col-xs-12 sign-preview-div">';
        echo date('d/m/Y H:i:s', $sign['time']) . '<br />';
        echo '<a href="/' . UPLOAD_DIR . '/sign/' . $sign['sign_id'] . '.png" target="_blank" class="common">' . $sign['sign_id'] . '</a><br />';
        echo '<div class="sign-preview-container">';
        echo '<a href="/' . UPLOAD_DIR . '/sign/' . $sign['sign_id'] . '.png" target="_blank"><img src="' . $sign['preview'] . '" alt="" border="0"  class="sign-preview-img" /></a>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

}
?>
</div>
</body>
</html>