<?php

echo "[" . date("Y-m-d H:i:s") . "]\n";

$intro_sitemap = '<?xml version="1.0" encoding="ISO-8859-1"?>
<urlset
xmlns="http://www.google.com/schemas/sitemap/0.84"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84
http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
';

require_once '/var/www/sign-a-pdf/inc/get_ip.php';
require_once '/var/www/sign-a-pdf/inc/write_log.php';
require_once '/var/www/sign-a-pdf/inc/mysql.php';

$rows = array();

array_push($rows, "https://www.sign-a-pdf.com/");

array_push($rows, "https://www.sign-a-pdf.com/en/");
array_push($rows, "https://www.sign-a-pdf.com/en/send-a-document");
array_push($rows, "https://www.sign-a-pdf.com/en/account");
array_push($rows, "https://www.sign-a-pdf.com/en/sign-in");
array_push($rows, "https://www.sign-a-pdf.com/en/contact");
array_push($rows, "https://www.sign-a-pdf.com/en/terms-of-use");
array_push($rows, "https://www.sign-a-pdf.com/en/legal-notice");

array_push($rows, "https://www.sign-a-pdf.com/fr/");
array_push($rows, "https://www.sign-a-pdf.com/fr/envoyer-un-document");
array_push($rows, "https://www.sign-a-pdf.com/fr/compte");
array_push($rows, "https://www.sign-a-pdf.com/fr/se-connecter");
array_push($rows, "https://www.sign-a-pdf.com/fr/contact");
array_push($rows, "https://www.sign-a-pdf.com/fr/conditions");
array_push($rows, "https://www.sign-a-pdf.com/fr/mentions-legales");

/*
db_connect();

db_close();
*/

echo "creating xml file /var/www/sign-a-pdf/sitemap.xml\n";

$fp = fopen("/var/www/sign-a-pdf/sitemap.xml", 'w');

add_row($intro_sitemap);

$row_offset = 0;

while (isset($rows[$row_offset]) && ($row_offset < 100)) {

    add_row("\t<url>\n\t\t<loc>" . $rows[$row_offset] . "</loc>\n\t</url>\n");

    $row_offset++;
}

add_row("</urlset>\n");

fclose($fp);

echo "> {$row_offset} rows inserted\n";

function add_row($row) {

    global $fp;

    fwrite($fp, $row);
}

