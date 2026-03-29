<?php
function utils_get_ip(){

    $ip='';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else if(isset($_SERVER['REMOTE_ADDR'])){
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    if($ip==''){
    	return $ip;
    }
    $parts=preg_split("/[\s,]+/",$ip);
    return $parts[0];
}
