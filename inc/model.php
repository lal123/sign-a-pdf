<?php

require_once 'write_log.php';
require_once 'get_ip.php';

function model_get_user_list() {
    
    global $base, $cdb;
    
    $ret = array();
    $sql = "select * from `{$base}`.users"
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

