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
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="/css/screen.<?php echo $version_suffix; ?>.css" />
    <link rel="stylesheet" href="/css/bootstrap-5.3.8.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="/js/jquery-4.0.0.min.js"></script>
    <script src="/js/bootstrap-5.3.8.min.js"></script>
    <script src="/js/global-<?php echo $lang; ?>.<?php echo $version_suffix; ?>.js"></script>
</head>
<body>

<div class="container">
    <nav class="navbar fixed-top navbar-expand-lg border-bottom border-body dark-cyan" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/<?php echo $lang; ?>/"><img src="/favicon-32x32.png" alt="" border="0" align="middle" style="width: 24px; height: 24px; margin: 0px 6px 4px 0px;"><?php echo $tr['SITE_NAME']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myMenu" aria-controls="myMenu" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="myMenu" style="">
                <ul class="navbar-nav ms-0">
                    <li class="nav-item">
                        <a<?php if ($page == 'home') { echo ' class="nav-link active" aria-current="page"'; } else {echo ' class="nav-link"';} ?> href="/<?php echo $lang; ?>/">
                            <i class="bi bi-upload"></i>&nbsp; <?php echo $tr['MENU.SEND_DOCUMENT']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a<?php
                        if ($page == 'docs') {
                            echo ' class="nav-link active" aria-current="page" href="/' . $lang . '/docs"';
                        } else if(!isset($_SESSION['docs']) || (sizeof($_SESSION['docs']) == 0)) {
                            echo ' class="nav-link disabled" aria-disabled="true"';
                        } else { 
                            echo ' class="nav-link" href="/' . $lang . '/docs"';
                        }
                        ?>>
                            <i class="bi bi-file-earmark-fill"></i>&nbsp; <?php echo $tr['MENU.YOUR_DOCUMENTS']; ?>
                            <?php if(isset($_SESSION['docs']) && (sizeof($_SESSION['docs']) > 0)) { echo ' (' . sizeof($_SESSION['docs']) . ')'; } ?>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#"><i class="bi bi-person-fill"></i>&nbsp; <?php echo $tr['MENU.CREATE_ACCOUNT']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-box-arrow-in-right"></i>&nbsp; <?php echo $tr['MENU.SIGN_IN']; ?></a>
                    </li>
                </ul>
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
                        <a class="nav-link" href="#"><?php echo $tr['MENU.TERMS_OF_USE']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo $tr['MENU.LEGAL_NOTICE']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="modal fade" id="uploadModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadModalLabel"><?php echo $tr['UPLOAD.SENDING_DOC']; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-info"><br /></div>
                <div><br /></div>
                <div id="modal-progress" class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 24px;">
                    <div id="modal-progress-bar" class="progress-bar text-bg-success" style="width: 0%"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
});
</script>
</body>
</html>
