<?php

require_once 'inc/utils.php';

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title><?php echo $page_title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="robots" content="index,follow,all" />
    <meta name="revisit-after" content="2 days" />
</head>
<body oncontextmenu="return false;">

<?php

include "inc/content/{$page}.php";

?>

    <script><?php echo $js_cmd; ?></script>
</body>
</html>
