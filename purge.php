<?php

chdir('/var/www/sign-a-pdf');

#liste des dossiers à purger
$dir_list=array('uploads/pdf','uploads/img');

//require_once 'inc/mysql.php';

$local_dir="/var/www/sign-a-pdf";
$extension = "(pdf|png)";

echo "[".date("Y-m-d H:i:s")."]------------------------------------------------\n";

function purge_dir($root_dir,$dir){
	global $db;
	global $local_dir,$extension;
	
	$count=0;
	$deleted=0;
	if($local_filelist=@opendir($local_dir.'/'.$root_dir.$dir)){
		while($local_filename=readdir($local_filelist)){
			if(!preg_match('/^\./',$local_filename)){
				if(is_dir($local_dir.'/'.$root_dir.$dir.'/'.$local_filename)){
					purge_dir($root_dir,$dir.'/'.$local_filename);
				}else{
					if(preg_match('/^(.+)' . $extension . '$/',$local_filename,$regs)){
						list(,$prefix)=$regs;
						$local_filedate=filemtime($local_dir.'/'.$root_dir.$dir.'/'.$local_filename);
						# pour supprimer un fichier il faut
						# - qu'il date de 6 heures au moins
						# - qu'il n'appartienne pas à un utilisateur enregistré
						if($local_filedate < (time() - 6*60*60)){
							$img_id="$dir/$prefix";
							if(preg_match('/^\/(.*)$/',$img_id,$regs)){
								list(,$img_id)=$regs;
							}
							$used=false;
							/*
							$used=true;
							$sql="select * from gifmania.$root_dir where img_id='$img_id'";
							$res=db_query($sql,$db);
							if($res!=false){
								if(db_num_rows($res)==0){
									$used=false;
								}
							}
							*/
							if($used==false){
								unlink($local_dir.'/'.$root_dir.$dir.'/'.$local_filename);
								$deleted++;
							}
						}
						$count++;
					}
				}
			}
		}
	}else{
		echo "ERR : ".$local_dir.'/'.$root_dir.$dir." inexistant !\n";;
	}
	echo $root_dir.$dir." $count files, $deleted deleted\n";
}

//$db=db_connect();

foreach($dir_list as $dir){
	purge_dir($dir,'');
}

//db_close();
