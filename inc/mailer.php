<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

function send_mail($_to, $_from, $_subject, $_text_msg, $_html_msg, $_return_path = null, $_reply_to = null, $_bcc = null, $_fis = false){

    // temporarily add bcc for all email
    $_bcc = "contact@sign-a-pdf.com";

    write_log(__METHOD__, "\n" . str_repeat('=', 80) . "\n");

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->Host     = OVH_MX_HOST;
    $mail->Port     = OVH_MX_PORT;
    $mail->Username = OVH_MX_USERNAME;
    $mail->Password = OVH_MX_PASSWORD;
    if(is_array($_from)) {
        $mail->setFrom($_from['mail'], $_from['name']);
        write_log(__METHOD__, 'From: ' . $_from['name'] . ' <' . $_from['mail'] . '>');
    } else {
        $mail->setFrom($_from);
        write_log(__METHOD__, 'From: ' . $_from);
    }
    if(is_array($_to)) {
        $mail->addAddress($_to['mail'], $_to['name']);
        write_log(__METHOD__, 'To: ' . $_to['name'] . ' <' . $_to['mail'] . '>');
    } else {
        $mail->addAddress($_to);
        write_log(__METHOD__, 'To: ' . $_to);
    }
    if($_return_path != null) {
        $mail->AddCustomHeader('Return-Path: ' . $_return_path);
        write_log(__METHOD__, 'Return-Path: ' . $_return_path);
    }
    if($_reply_to != null) {
        $mail->addReplyTo($_reply_to['mail'], $_reply_to['name']);
        write_log(__METHOD__, 'Reply-To: ' . $_reply_to['name'] . ' <' . $_reply_to['mail'] . '>');
    }
    if($_bcc != null) {
        $mail->addBcc($_bcc);
        write_log(__METHOD__, 'Bcc: ' . $_bcc);
    }
    $mail->CharSet = 'utf-8';
    $mail->Subject = $_subject;
    write_log(__METHOD__, 'Subject: ' . $_subject);
    //$mail->msgHTML(file_get_contents('mail_test.html'), __DIR__);
    $mail->AltBody = $_text_msg;
    //write_log(__METHOD__, 'Text msg: ' . $_text_msg);
    $mail->Body = $_html_msg;
    //write_log(__METHOD__, 'Html msg: ' . $_html_msg);
    if (is_array($_fis)) {
        foreach ($_fis as $label => $filename) {
            if (is_readable($filename)) {
                $mail->addEmbeddedImage($filename, $label . '@gif-mania.net');
                //write_log(__METHOD__, 'Embedded image: ' . $label . '@gif-mania.net => ' . $filename);
            }
        }
    }

    $return = $mail->send();

    if (!$return) {
        write_log(__METHOD__, '*** ERR *** Mailer Error:'. $mail->ErrorInfo);
    } else {
        write_log(__METHOD__, 'The email has been sent.');
    }

    return $return;
}
