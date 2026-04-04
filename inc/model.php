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
    write_log(__METHOD__, $sql);
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
    write_log(__METHOD__, $sql);
    $res = db_query($sql);
    return $res;    
}

