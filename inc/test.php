<?php

require_once 'constant.php';
require_once 'get_ip.php';
require_once 'write_log.php';
require_once 'private.php';
require_once 'mysql.php';
require_once 'pdf.php';
require_once 'mailer.php';

echo "Hello!\n";

send_mail('alain.maerten@free.fr', 'contact@sign-a-pdf.com', 'bonjour', 'yo', 'yo <b>man</b>');