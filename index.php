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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KCV3E6Q85R"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-KCV3E6Q85R');
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4729392151663601" crossorigin="anonymous"></script>
<?php
if(($page == 'account') && ($action == 'validate')) {
?>
    <script>
      gtag('event', 'conversion_event_signup', {
      });
    </script>
<?php
}
?>
</head>
<body oncontextmenu="return false;">

<div class="container">
    <nav class="navbar fixed-top navbar-expand-lg border-bottom border-body dark-cyan" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/<?php echo $lang; ?>/"><img src="/favicon-32x32.png" alt="" border="0" align="middle" style="width: 24px; height: 24px; margin: 0px 6px 4px 0px;"><?php echo $tr['SITE_NAME']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myMenu" aria-controls="myMenu" aria-expanded="false" aria-label="">
                  <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="myMenu" style="">
                <ul class="navbar-nav ms-0">
                    <li class="nav-item">
                        <a<?php if ($page == 'home') { echo ' class="nav-link reduced active" aria-current="page"'; } else {echo ' class="nav-link reduced"';} ?> href="<?php echo '/' . $lang . '/' . $page_role['home']; ?>">
                            <i class="bi bi-upload"></i>&nbsp; <?php echo $tr['MENU.SEND_DOCUMENT']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'docs') {
                            echo ' class="nav-link active" aria-current="page" href="/' . $lang . '/' . $page_role['docs'] . '"';
                        } else if($docs_numb == 0) {
                            echo ' class="nav-link disabled" aria-disabled="true"';
                        } else { 
                            echo ' class="nav-link" href="/' . $lang . '/' . $page_role['docs'] . '"';
                        }
                        ?>><i class="bi bi-file-earmark"></i>&nbsp; <?php echo $tr['MENU.YOUR_DOCUMENTS']; ?>&nbsp; <span class="docs_numb"><?php if($docs_numb > 0) { echo '(' . $docs_numb . ')'; } ?></span></a>
                    </li>
                </ul>
<?php
if($is_signed_in) {
?>
                <ul class="navbar-nav mx-auto">
                    <li class="navbar-item navbar-text welcome"><?php echo $tr['WELCOME'] . ' <b>' . $user['user_name'] . '</b>'; ?></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i>&nbsp; <?php echo $tr['MENU.YOUR_ACCOUNT']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?php echo "/{$lang}/{$page_role['account']}"; ?>"><i class="bi bi-sliders"></i>&nbsp; <?php echo $tr['MENU.UPDATE_ACCOUNT']; ?></a>
                            </li>
                            <li>
                                <a class="dropdown-item<?php if($docs_numb == 0) { echo ' disabled'; } ?>" href="<?php echo "/{$lang}/{$page_role['docs']}"; ?>"><i class="bi bi-file-earmark"></i>&nbsp; <?php echo $tr['MENU.YOUR_DOCUMENTS']; ?>&nbsp; <span class="docs_numb"><?php if($docs_numb > 0) { echo '(' . $docs_numb . ')'; } ?></span></a>
                            </li>
                            <li>
                                <a class="dropdown-item<?php if($signs_numb == 0) { echo ' disabled'; } ?>" href="<?php echo "/{$lang}/{$page_role['signs']}"; ?>"><i class="bi bi-pen"></i>&nbsp; <?php echo $tr['MENU.YOUR_SIGNATURES']; ?>&nbsp; <span class="signs_numb"><?php if($signs_numb > 0) { echo '(' . $signs_numb . ')'; } ?></span></a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo "/{$lang}/{$page_role['sign-out']}"; ?>"><i class="bi bi-box-arrow-right"></i>&nbsp; <?php echo $tr['MENU.SIGN_OUT']; ?></a>
                            </li>
                        </ul>
                    </li>
                </ul>
<?php
} else {
?>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'account') {
                            echo ' class="nav-link active" aria-current="page"';
                        } else {
                            echo ' class="nav-link"';
                        }
                        ?> href="<?php echo "/{$lang}/{$page_role['account']}"; ?>"><i class="bi bi-person"></i>&nbsp; <?php echo $tr['MENU.CREATE_ACCOUNT']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'sign-in') {
                            echo ' class="nav-link active" aria-current="page"';
                        } else {
                            echo ' class="nav-link"';
                        }
                        ?> href="<?php echo "/{$lang}/{$page_role['sign-in']}"; ?>"><i class="bi bi-box-arrow-in-right"></i>&nbsp; <?php echo $tr['MENU.SIGN_IN']; ?></a>
                    </li>
                </ul>
<?php
}
?>
                <ul class="navbar-nav me-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe2"></i>&nbsp; <?php echo $tr['EXPLICT_LANG']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/en/">English</a></li>
                            <li><a class="dropdown-item" href="/fr/">Français</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container-fluid" style="margin: 70px 0px 70px 0px;">
<?php

include "inc/content/{$page}.php";

?>
</div>

<div class="container">
    <nav class="navbar navbar-expand fixed-bottom border-top border-body dark-cyan" data-bs-theme="dark">
        <div class="container-fluid justify-content-center">
            <div class="flex-grow-0">
                <ul class="navbar-nav flex-row text-center">
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'terms-of-use') {
                            echo ' class="nav-link limited active" aria-current="page"';
                        } else {
                            echo ' class="nav-link limited"';
                        }
                        ?> href="<?php echo "/{$lang}/{$page_role['terms-of-use']}"; ?>"><?php echo $tr['MENU.TERMS_OF_USE']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'legal-notice') {
                            echo ' class="nav-link limited active" aria-current="page"';
                        } else {
                            echo ' class="nav-link limited"';
                        }
                        ?> href="<?php echo "/{$lang}/{$page_role['legal-notice']}"; ?>"><?php echo $tr['MENU.LEGAL_NOTICE']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'contact') {
                            echo ' class="nav-link limited active" aria-current="page"';
                        } else {
                            echo ' class="nav-link limited"';
                        }
                        ?> href="<?php echo "/{$lang}/{$page_role['contact']}"; ?>"><?php echo $tr['MENU.CONTACT']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<script>
    $('document').ready(function() {
<?php
if(($page == 'docs') && ($pdf_id != '')) {
    echo '        $(document).on("scroll", function(event) {
            docs.adaptNavBar();
        });' . "\n";
    echo '        docs.adaptNavBar();'."\n";
    if(!utils_is_mobile()) echo '        $("#nav-bar").draggable({cancel: \'.act\'});'."\n";
}
?>
     });
</script>

</body>
</html>
