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

function model_create_user($values) {
    
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
        . "'key', "
        . "0, "
        . $values['user_optin'] . ", "
        . "now(), "
        . "now()"
        . ")";
    $res = db_query($sql);
    return $res;
}
