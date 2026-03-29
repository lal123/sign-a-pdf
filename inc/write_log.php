<?php
function write_log($object, $what) {
	
	global $log_dir;
	
	if(!isset($log_dir)){
		$log_dir=dirname(realpath(__FILE__)).'/../logs/'.date("Ym");
		if(!file_exists($log_dir)){
			mkdir($log_dir);
			chmod($log_dir, 0777);
		}
	}
	$filename=$log_dir.'/'.date("Ymd").'_'.$object.'.log';
	if(!file_exists($filename)){
	    touch($filename);
		chown($filename, 'www-data'); 
	    chgrp($filename, 'www-data'); 
	}
	$fh=fopen($filename,'a');
	fputs($fh,date('Y-m-d H:i:s').' - '.utils_get_ip().' - '.session_id().' - '.$what."\n");
	fclose($fh);
}

