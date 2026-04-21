<?php

require_once '../inc/utils.php';

db_connect();

$users = model_get_user_list();

$an_docs = [];

function get_dir($dir, $rel_dir, &$an_docs) {
    $fh = opendir($dir);
    while($fn = readdir($fh)) {
        if(!preg_match('/^\./', $fn)){
            if(is_dir($dir . '/' . $fn)){
                get_dir($dir . '/' . $fn, $rel_dir . $fn . '/', $an_docs);
            } else {
            	if(preg_match('/^([0-9a-f]{16})\.pdf$/', $fn, $matches)) {
            		list(, $pdf_id) = $matches;
            		if(model_doc_get_from_pdf_id($pdf_id) !== false) {
                        $pages = preg_match_all("/\/Page\W/", file_get_contents($dir . '/' . $fn), $matches);
                        if(!isset($pages) || ($pages == 0)) {
                            $pages = 1;
                        }
                        $signed = (preg_match('/signed$/', $dir) ? 1 : 0);
                        $time = filemtime($dir . '/' . $fn);
                        $preview = '/' . UPLOAD_DIR . '/img/' . ($signed == 1 ? 'signed/' : '') . $pdf_id . ($pages > 1 ? '-0' : '') . '.png';
            			$an_docs[] = ['path' => $rel_dir . $pdf_id, 'pages' => $pages, 'signed' => $signed, 'preview' => $preview, 'time' => $time];
            		}
            	}
            }
        }
    }
}

get_dir(getcwd() . '/../' . UPLOAD_DIR . '/pdf', '', $an_docs);

db_close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title><?php echo $page_title; ?></title>
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
</head>
<body oncontextmenu="return false;">

<div style="padding: 20px;">
	<ul style="list-style-type: circle;">
<?php
foreach($users as $index => $user) {
	echo '<li style="margin: 0px 0px 6px 0px;">';
	echo '<a href="' . utils_create_link('account', 'update', $user['user_id'], $user['user_key']) . '" target= "_blank" class="common">' . $user['user_name'] . '</a>';
	echo '</li>';
}
?>
	</ul>
</div>
<div>
	<ul style="list-style-type: circle;">
<?php

uksort($an_docs, function($a, $b) {
    global $an_docs;
    return strcasecmp($an_docs[$b]['time'], $an_docs[$a]['time']);
});

foreach($an_docs as $index => $doc) {
	echo '<li style="margin: 0px 0px 6px 0px;">';
	echo $doc['path'] . ' (' . $doc['pages'] . ')' . ' (' . date('Y-m-d H:i:s', $doc['time']) . ')';
    echo '<div style="height: 200px;">';
    echo '<img src="' . $doc['preview'] . '" alt="" border="0" style="max-height: 100%;" />';
    echo '</div>';
	echo '</li>';
}
?>
	</ul>
</div>

</body>
</html>