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
    <link href="/css/bootstrap-5.3.8.min.css" rel="stylesheet" />
    <script src="/js/jquery-4.0.0.min.js"></script>
    <script src="/js/bootstrap-5.3.8.min.js"></script>
    <script src="/js/global-<?php echo $lang; ?>.<?php echo $version_suffix; ?>.js"></script>
</head>
<body>

<div class="container">
    <nav class="navbar fixed-top navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./"><img src="/favicon-32x32.png" alt="" border="0" align="middle" style="width: 24px; height: 24px; margin: 0px 6px 4px 0px;"><?php echo $tr['SITE_NAME']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myMenu" aria-controls="myMenu" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="myMenu" style="">
              <div id="menu-left">
                  <ul class="navbar-nav" style="text-align: right;">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                      </a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                      </ul>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                  </ul>
              </div>  
              <div id="menu-right">
                <ul class="navbar-nav" style="text-align: right;">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#">Home2</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown lang-select" id="lang-select-dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="flag flag-<?php echo $lang; ?>"></span>
                      </a>
                      <ul id="lang-select-menu" class="dropdown-menu">
                        <li><a class="nav-link" href="/en/" title="English version"><span class="flag flag-en"></span> English version</a></li>
                        <li><a class="nav-link" href="/fr/" title="Version française"><span class="flag flag-fr"></span> Version française</a></li>
                      </ul>
                    </li>
                </ul>
            </div>
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
    <nav class="navbar navbar-expand fixed-bottom bg-dark" data-bs-theme="dark">
      <div class="container-fluid justify-content-center">
        <div class="flex-grow-0">
          <ul class="navbar-nav flex-row text-center">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</div>

<script>
$(document).ready(function(){
    //$('#lang-select-menu').css({'right': '220px'});
    console.log($('#lang-select-menu').position());
});
</script>
</body>
</html>
