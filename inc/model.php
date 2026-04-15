<?php

require_once 'write_log.php';
require_once 'get_ip.php';

function model_get_user_list() {
    
    global $base, $cdb;
    
    $ret = array();
    $sql = "select * from `{$base}`.`users`"
            . " where 1"
            . " order by user_creato desc";
    $res = db_query($sql);
    if($res != false){
        while($arr = db_fetch_assoc($res)){
            $ret[] = $arr;
        }
    }
    return $ret;
}

function model_user_create($values) {
    
    global $base, $cdb;
    
    $sql = "insert into `{$base}`.`users` ("
        . "`user_name`, "
        . "`user_email`, "
        . "`user_pass`, "
        . "`user_key`, "
        . "`user_valid`, "
        . "`user_optin`, "
        . "`user_creato`, "
        . "`user_modifo`"
        . ") values ("
        . "'" . db_escape($values['user_name']) . "', "
        . "'" . db_escape($values['user_email']) . "', "
        . "'" . db_escape($values['user_pass']) . "', "
        . "'" . db_escape($values['user_key']) . "', "
        . "0, "
        . $values['user_optin'] . ", "
        . "now(), "
        . "now()"
        . ")";
    $res = db_query($sql);
    return $res;
}

function model_user_update($user_id, $values) {
    
    global $base, $cdb;
    
    $sql = "update `{$base}`.`users`"
        . " set "
        . "`user_name` = '" . db_escape($values['user_name']) . "', "
        . "`user_email` = '" . db_escape($values['user_email']) . "', "
        . "`user_pass` = '" . db_escape($values['user_pass']) . "', "
        . "`user_optin` = " . $values['user_optin'] . ", "
        . "`user_modifo` = now()"
        . " where `user_id` = " . $user_id;
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    return true;
}

function model_user_exists($filters, $excluded, &$user) {

    global $base, $cdb;

    $user = [];    
    $sql = "select * from `{$base}`.`users`"
            . " where 1";
    foreach($filters as $name => $value) {
        $sql.= " and {$name} = '" . db_escape($value) . "'";
    }
    if(sizeof($excluded) != 0) {
        $sql .= " and ( 1";
        foreach($excluded as $name => $value) {
            $sql.= " and {$name} != '" . db_escape($value) . "'";
        }
        $sql.= ")";
    }
    //write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false && (db_num_rows($res) != 0)) {
        $user = db_fetch_assoc($res);
    }
    return $res;    
}

function model_user_validate($user_id, $user_key) {

    global $base, $cdb;
    
    $sql = "update `{$base}`.`users`"
            . " set user_valid = 1"
            . " where 1"
            . " and user_id = '" . db_escape($user_id) . "'"
            . " and user_key = '" . db_escape($user_key) . "'";
    //write_log(__METHOD__, $sql);
    $res = db_query($sql);
    return $res;    
}

function model_user_delete($user_id) {

    global $base, $cdb;
    
    $sql = "delete  from `{$base}`.`users`"
            . " where 1"
            . " and user_id = '" . db_escape($user_id) . "'";
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    return $res;    
}

function model_get_mail_model($mail_lang, $mail_role) {
    
    global $base, $cdb;
    
    $arr = [];
    $sql = "select * from `{$base}`.`mail_models`"
            . " where 1"
            . " and `mail_lang` = '{$mail_lang}'"
            . " and `mail_role` = '{$mail_role}'";
    $res = db_query($sql);
    if($res != false){
        $arr = db_fetch_assoc($res);
    }
    return $arr;
}

function model_doc_create($values) {
    
    global $base, $cdb;
    
    $sql = "insert into `{$base}`.`docs` ("
        . "`doc_user_id`, "
        . "`doc_name`, "
        . "`doc_pdf_id`, "
        . "`doc_size`, "
        . "`doc_pages`, "
        . "`doc_signed`, "
        . "`doc_creato`, "
        . "`doc_modifo`"
        . ") values ("
        . "'" . db_escape($values['user_id']) . "', "
        . "'" . db_escape($values['name']) . "', "
        . "'" . db_escape($values['pdf_id']) . "', "
        . "'" . db_escape($values['size']) . "', "
        . "'" . db_escape($values['pages']) . "', "
        . "0, "
        . "now(), "
        . "now()"
        . ")";
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    return $res;
}

function model_doc_get_list($doc_user_id) {
    
    global $base, $cdb;
    
    $ret = [];
    $sql = "select doc_pdf_id, doc_name, UNIX_TIMESTAMP(doc_creato) doc_time, doc_size, doc_pages, doc_signed from `{$base}`.`docs`"
            . " where 1"
            . " and doc_user_id = '" . db_escape($doc_user_id) . "'";
            $sql.= " order by doc_creato desc";
    //write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false){
        while($arr = db_fetch_assoc($res)){
            $pdf_id = $arr['doc_pdf_id'];
            $ret[$pdf_id] = ['name' => $arr['doc_name'], 'time' => $arr['doc_time'], 'size' => $arr['doc_size'], 'pages' => $arr['doc_pages'], 'signed' => $arr['doc_signed']];
        }
    }
    //write_log(__METHOD__, print_r($ret, true));
    return $ret;
}

function model_doc_get_numb($doc_user_id) {
    
    global $base, $cdb;
    
    $doc_numb = 0;
    $sql = "select count(*) from `{$base}`.`docs`"
            . " where 1"
            . " and doc_user_id='" . db_escape($doc_user_id) . "'";
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false){
        list($doc_numb) = db_fetch_row($res);
    }
    write_log(__METHOD__, "[doc_numb][{$doc_numb}]");
    return $doc_numb;
}

function model_doc_delete($doc_pdf_id) {

    global $base, $cdb;
    
    $ret = false;
    $sql = "delete from `{$base}`.`docs`"
            . " where 1"
            . " and `doc_pdf_id` = '" . db_escape($doc_pdf_id) . "'";
    //write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false) {
        $ret = true;
    }
    return $ret;
}

function model_doc_update_size($doc_pdf_id, $doc_size) {

    global $base, $cdb;
    
    $ret = false;
    $sql = "update `{$base}`.`docs`"
            . " set `doc_size` = '" . db_escape($doc_size) . "'"
            . " where 1"
            . " and `doc_pdf_id` = '" . db_escape($doc_pdf_id) . "'";
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false) {
        $ret = true;
    }
    return $ret;
}

function model_doc_get_from_pdf_id($doc_pdf_id) {

    global $base, $cdb;
    
    $ret = [];
    $sql = "select * from `{$base}`.`docs`"
            . " where 1"
            . " and doc_pdf_id='" . db_escape($doc_pdf_id) . "'";
    //write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false) {
        $ret = db_fetch_assoc($res);
    }
    //write_log(__METHOD__, print_r($ret, true));
    return $ret;
}

function model_doc_sign($doc_pdf_id, $doc_signed_pdf_id, $signed_doc_size) {

    global $base, $cdb;

    $ret = false;    
    $sql = "insert into `{$base}`.`docs` ("
        . "`doc_user_id`, "
        . "`doc_name`, "
        . "`doc_pdf_id`, "
        . "`doc_size`, "
        . "`doc_pages`, "
        . "`doc_signed`, "
        . "`doc_creato`, "
        . "`doc_modifo`"
        . ") select "
        . "`doc_user_id`, "
        . "`doc_name`, "
        . "'" . db_escape($doc_signed_pdf_id) . "', "
        . "'" . db_escape($signed_doc_size) . "', "
        . "`doc_pages`, "
        . "1, "
        . "now(), "
        . "now()"
        . " from  `{$base}`.`docs` "
        . "where 1 "
        . "and `doc_pdf_id` = '" . db_escape($doc_pdf_id) . "' ";
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    if($res != false) {
        $ret = true;
    }
    return $ret;
}

