<?php

setcookie('lang', $lang, time() + 2 * 365 * 86400, '/');

require_once 'lang_'.$lang.'.php';

