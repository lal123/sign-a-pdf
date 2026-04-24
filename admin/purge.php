<?php

chdir('/var/www/sign-a-pdf');

#liste des dossiers à purger
$dir_list = [
	'pdfs' => 'uploads/pdf',
	'pages' => 'uploads/img',
	'signs' => 'uploads/sign',
];

require_once 'inc/mysql.php';

db_connect();

$local_dir="/var/www/sign-a-pdf";
$extension = "(pdf|png)";

echo "[".date("Y-m-d H:i:s")."]------------------------------------------------\n";

function purge_dir($type, $root_dir, $dir){
	global $db;
	global $local_dir,$extension;
	
	$count = 0;
	$deleted = 0;
	if($local_filelist = @opendir($local_dir . '/' . $root_dir.$dir)){
		while($local_filename = readdir($local_filelist)){
			if(!preg_match('/^\./', $local_filename)){
				if(is_dir($local_dir . '/' . $root_dir . $dir . '/' . $local_filename)){
					purge_dir($type, $root_dir, $dir . '/' . $local_filename);
				}else{
					if(preg_match('/^([a-f0-9]{16})([0-9\-]*)\.' . $extension . '$/', $local_filename, $regs)){
						list(, $prefix, $index)=$regs;
						$full_path = $local_dir . '/' . $root_dir . $dir . '/' . $local_filename;
						$local_filedate = filemtime($full_path);
						$str_filedate = date('Y-m-d H:i:s', $local_filedate);
						# pour supprimer un fichier il faut
						# - qu'il date de 24 heures au moins
						# - qu'il n'appartienne pas à un utilisateur enregistré
						if($local_filedate < (time() - 24 * 60 * 60)){

							$used = true;

							if($type == 'signs') {
								$sql = "select * from `sign-a-pdf`.`signs` where sign_file_id = '{$prefix}'";
								//echo "[{$sql}]\n";
								$res = db_query($sql);
								if($res != false){
									if(db_num_rows($res) == 0){
										$used = false;
									} else{
										//echo "preserved: {$str_filedate} {$full_path}\n";
									}
								}
							} else {
								$sql = "select * from `sign-a-pdf`.`docs` where doc_pdf_id = '{$prefix}'";
								//echo "[{$sql}]\n";
								$res = db_query($sql);
								if($res != false){
									if(db_num_rows($res) == 0){
										$used = false;
									} else{
										//echo "preserved: {$str_filedate} {$full_path}\n";
									}
								}
							}
							
							if($used == false){
								unlink($local_dir . '/' . $root_dir.$dir . '/' . $local_filename);
								$deleted++;
							}
						}
						$count++;
					}
				}
			}
		}
	}else{
		echo "ERR : " . $local_dir . '/' . $root_dir . $dir . " inexistant !\n";;
	}
	echo $root_dir.$dir . " {$count} files, {$deleted} deleted\n";
}

foreach($dir_list as $type => $dir){
	purge_dir($type, $dir, '');
}

db_close();
