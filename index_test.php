<?php

/*
if(php_uname("n") != 'alain-520-1080fr') {
    echo 'Hello, World!';die();
}
*/

require_once 'inc/utils.php';

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title><?php echo $page_title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo $tr['META.KEYWORDS']; ?>" />
    <meta name="description" content="<?php echo $tr['META.DESCRIPTION']; ?>" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="robots" content="index,follow,all" />
    <meta name="revisit-after" content="2 days" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="/css/fonts.<?php echo $version_suffix; ?>.css" />
    <link rel="stylesheet" href="/css/screen.<?php echo $version_suffix; ?>.css" />
    <link rel="stylesheet" href="/css/bootstrap-5.3.8.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.1.14.2.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.structure.1.14.2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="/js/jquery-4.0.0.min.js"></script>
    <script src="/js/jquery-ui.1.14.2.min.js"></script>
    <script src="/js/bootstrap-5.3.8.min.js"></script>
    <script src="/js/global-<?php echo $lang; ?>.<?php echo $version_suffix; ?>.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KCV3E6Q85R"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-KCV3E6Q85R');
    </script>
</head>
<body>

<div class="container">
    <nav class="navbar fixed-top navbar-expand-lg border-bottom border-body dark-cyan" data-bs-theme="dark" style="height: 91px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/<?php echo $lang; ?>/"><img src="/favicon-32x32.png" alt="" border="0" align="middle" style="width: 24px; height: 24px; margin: 0px 6px 4px 0px;"><?php echo $tr['SITE_NAME']; ?></a>
            <div class="collapse navbar-collapse" id="myMenu" style="">
                <ul class="navbar-nav ms-5">
                    <li class="navbar-item navbar-text">
                        <div style="font-family: saginaw_bold; font-size: 50px; line-height: 40px; margin: -5px 0px 0px 10px;">
                            <?php if($lang == 'en') {
                                echo 'Sign all your documents for free !';
                            } else {
                                echo 'Signez vos documents gratuitement !';
                            }
                            ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<!--<div style="position: absolute; top: 0px; left: 0px; width: 970px; height: 90px; border: dotted 1px white; z-index: 1031;"></div>-->
</body>
</html>
