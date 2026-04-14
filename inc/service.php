<?php

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    exit();
}

require_once 'utils.php';

$action = '';
if(isset($_POST['action'])) {
	$action = $_POST['action'];
}

$lang = 'en';
if(isset($_POST['lang'])) {
	$lang = $_POST['lang'];
}

$err_msg = '';

switch($action) {
	case 'upload_doc':
		$max_docs_numb = ($is_signed_in ? USER_MAX_DOCS_NUMB : MAX_DOCS_NUMB);
		if($docs_numb >= $max_docs_numb) {
			$res = json_encode(['pdf_id' => '', 'err_msg' => strtr($tr['UPLOAD.MAX_DOCS_NUMB'], ['%%max_docs_numb%%' => $max_docs_numb]), 'name' => ''], JSON_UNESCAPED_UNICODE);
		} else { 
			$res = pdf_convert_to_png();
			$arr = json_decode($res, true);
			if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
				$pdf_id = $arr['pdf_id'];
				if($is_signed_in) {
					$user_id = $user['user_id'];
					model_doc_create(['user_id' => $user_id, 'pdf_id' => $arr['pdf_id'], 'name' => $arr['name'], 'size' => $arr['size'], 'pages' => $arr['pages']]);
				} else {
					$_SESSION['docs'][$pdf_id]['name'] = $arr['name'];
					$_SESSION['docs'][$pdf_id]['size'] = $arr['size'];
					$_SESSION['docs'][$pdf_id]['pages'] = $arr['pages'];
					$_SESSION['docs'][$pdf_id]['signed'] = 0;
					$_SESSION['docs'][$pdf_id]['time'] = time();
				}
			}
		}
		/*
		if($err_msg == '') {
			$page = 'docs';
			//pdf_convert_from_png($pdf_id);
		}
		*/
		echo $res;
		break;
	case 'convert_doc':
		$pdf_id = $_POST['pdf_id'];
		$pages = $_POST['pages'];
    	$count = pdf_count_pages($pdf_id);
		$percent = ceil($count / $pages * 100);
        echo "$('#modal-info').html('{$tr['UPLOAD.PREPARING_DOC']} :&nbsp; {$count} / {$pages} ({$percent}%}');\n";
        echo "$('#modal-progress').show();\n";
        echo "$('#modal-progress-bar').css({'width': {$percent} + '%'});\n";
		echo "console.log('{$count}/{$pages}');\n";
		if($count == $pages) {
			echo "document.location.href = '/{$lang}/docs/{$pdf_id}/';\n";
		} else {
			echo "docs.convertHandle = setTimeout(\"docs.convert('{$pdf_id}', '{$pages}')\", 500);\n";
		}
		break;
	case 'delete_doc':
		$pdf_id = $_POST['pdf_id'];
		if($is_signed_in) {
			if(!model_doc_delete($pdf_id)) {
				//??
			}
			$docs_numb = model_doc_get_numb($user['user_id']);
		} else {
			unset($_SESSION['docs'][$pdf_id]);
			$docs_numb = sizeof($_SESSION['docs']);
		}
		if($docs_numb > 0) {
			echo "$('.doc-small-preview[pdf_id=" . $pdf_id . "]').remove();\n";
			echo "$('.docs_numb').html('({$docs_numb})');\n";
			echo "document.location.href = '/{$lang}/docs';\n";
		} else {
			echo "document.location.href = '/{$lang}/';\n";
		}
		break;
	case 'delete_account':
		$user_id = $_POST['user_id'];
		$errors = [];
		if (utils_user_delete($user_id, $errors)) {
			echo "document.location.href = '/{$lang}/';\n";
		} else {
			echo "console.log('errors', " . json_encode($errors, JSON_UNESCAPED_UNICODE) . ");\n";
		}
		break;
	case 'get_sign_step':
		$arr = [];
		$arr['err_msg'] = '';
		$pdf_id = $_POST['pdf_id'];
		$sign_step = $_POST['sign_step'];
		$sign_inc = $_POST['sign_inc'];
		$sign_option = $_POST['sign_option'];
		$page_option = $_POST['page_option'];
		$sign_pages = $_POST['sign_pages'];
		switch($sign_step) {
			case 1:
				$sign_text = $_POST['sign_text'];
				switch($sign_option) {
					case 2 :
						$res = sign_get_img_from_file();
						$arr = json_decode($res, true);
						//write_log('sign_post_file', print_r($arr, true));
						if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
							$sign_id = $arr['sign_id'];
							$sign_width = $arr['sign_width'];
							$sign_height = $arr['sign_height'];
							$sign_step+= $sign_inc;
						}
						break;
					case 3 :
						$res = sign_get_img_from_text($sign_text);
						$arr = json_decode($res, true);
						if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
							$sign_id = $arr['sign_id'];
							$sign_width = $arr['sign_width'];
							$sign_height = $arr['sign_height'];
							$sign_step+= $sign_inc;
						} else {
							//echo "$('#globalError').html(decodeURIComponent('" . rawurlencode($arr['err_msg']) . "'));\n";
						}
						break;
					case 1 :
					default:
						$sign_width = -1;
						$sign_height = -1;
						$sign_id = '';
						$sign_step+= $sign_inc;
				}
				break;
			case 2:
				$sign_text = $_POST['sign_text'];
				$sign_id = $_POST['sign_id'];
				$sign_width = $_POST['sign_width'];
				$sign_height = $_POST['sign_height'];
				switch($sign_option) {
					case 1:
						$sign_data = $_POST['sign_data'];
						if($sign_inc == 1) {
							$res = sign_get_img_from_data($sign_data);
							$arr = json_decode($res, true);
							$sign_id = $arr['sign_id'];
						}
						break;
					default:
				}
				if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
					$sign_step+= $sign_inc;
				}
				break;
			case 3:
				$sign_text = $_POST['sign_text'];
				$sign_id = $_POST['sign_id'];
				$sign_width = $_POST['sign_width'];
				$sign_height = $_POST['sign_height'];
				if($sign_inc == 1) {
					if($page_option == 3) {
						if(!preg_match('/^([0-9]+)$/', $sign_pages)) {
							//$arr['err_msg'] = $tr['SIGN.PAGES.CUST.INVALID'];
						}
					}
				}
				if(($sign_inc == -1) || (!isset($arr['err_msg']) || ($arr['err_msg'] == ''))) {
					$sign_step+= $sign_inc;
				}
				break;
			case 0:
			default:
				$sign_text = ($is_signed_in ? $user['user_name'] : '');
				$sign_step+= $sign_inc;
		}
		if($sign_step >= 4) {
	        $res = pdf_import_unsigned_pages($pdf_id);
	        $arr = json_decode($res, true);
	        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
	        	$signed_pdf_id = $arr['signed_pdf_id'];
		        echo "$('#signDocModal').modal('hide');\n";
    	        echo "sign.adjust('{$pdf_id}', '{$signed_pdf_id}', '{$sign_id}', {$page_option}, '{$sign_pages}', {$sign_width}, {$sign_height});\n";
    	    }
		} else {
			ob_start();
			include(getcwd() . "/content/sign-doc-step{$sign_step}.php");
			$content = ob_get_contents();
			ob_end_clean();
			echo "$('#modalBody').html(decodeURIComponent('" . rawurlencode($content) . "'));\n";
			if($sign_step == 1) {
				echo "$('#signDocModal #backButton').hide();\n";
			} else {
				echo "$('#signDocModal #backButton').show();\n";
			}
			if(($sign_step == 2) && ($sign_option == 1)) {
            	echo "sign.initCanvas(450, 200);\n";
            	if($sign_inc == -1) {
            		echo "sign.loadCanvas('/uploads/sign/{$sign_id}.png');\n";
            	}
            }
	        echo "$('#signDocModal').modal('show');\n";
	    }
		break;
	case 'sign_page':
		$pdf_id = $_POST['pdf_id'];
		$signed_pdf_id = $_POST['signed_pdf_id'];
		$page_id = $_POST['page_id'];
		$page_w = $_POST['page_w'];
		$page_h = $_POST['page_h'];
		$sign_w = $_POST['sign_w'];
		$sign_h = $_POST['sign_h'];
		$sign_x = $_POST['sign_x'];
		$sign_y = $_POST['sign_y'];
		$signed_page_id = $signed_pdf_id;
		if(preg_match("/^{$pdf_id}(.*)$/", $page_id, $matches)) {
			list(, $suffix) = $matches;
			$signed_page_id = $signed_pdf_id . $suffix;
		}
		$sign_id = $_POST['sign_id'];
		$page_option = $_POST['page_option'];
		$sign_pages = $_POST['sign_pages'];
    	
		$img_dir = getcwd() . '/../' . UPLOAD_DIR . '/img';
	    $file_list = [];
	    $fh = opendir($img_dir);
		while($filename = readdir($fh)) {
			if(preg_match("/^{$pdf_id}(.*)\.png$/", $filename, $matches)) {
				list(, $suffix) = $matches;
				//$file_list[] = $img_dir . '/' . $pdf_id . $suffix . '.png';
				$file_list[] = $pdf_id . $suffix;
			}
		}
		//sort($file_list, SORT_NATURAL);
        //write_log("sign_page", 'file_list: ' . print_r($file_list, true));

        //write_log("sign_page", "page_option: {$page_option}");

    	$pages_arr = [];    
    	switch($page_option) {
    		case 2:
    			for($i = 1 ; $i <= sizeof($file_list) ; $i++) {
    				$pages_arr[] = $i;
    			}
    			break;
    		case 3:
		        $sp = preg_split('/[ ,]+/', $sign_pages);
		        for($i = 0 ; $i < sizeof($sp) ; $i++) {
		        	if(preg_match('/^([0-9]+)\-([0-9]+)$/', $sp[$i], $matches)) {
		        		list(,$first, $last) = $matches;
				        for($j = $first ; $j <= $last ; $j++) {
				        	$pages_arr[] = $j;
				        }
		        	} else {
			        	$pages_arr[] = $sp[$i];
		        	}
		        }
    			break;
			case 1:
    		default:
    			$pages_arr[] = sizeof($file_list);
    	}
    	
        //write_log("sign_page", 'pages_arr: ' . print_r($pages_arr, true));

        for($i = 0 ; $i < sizeof($pages_arr) ; $i++) {
        	$page_id =  $pdf_id . ((sizeof($file_list) > 1) || ($pages_arr[$i] > 1) ? '-' . ($pages_arr[$i] - 1)  : '');
        	$signed_page_id =  $signed_pdf_id . ((sizeof($file_list) > 1) || ($pages_arr[$i] > 1) ? '-' . ($pages_arr[$i] - 1)  : '');
        	if(in_array($page_id, $file_list)) {
        		//write_log('sign_page', 'matched: ' .$pages_arr[$i] . ' => ' . $page_id);
        		//write_log('sign_page', "page_id: {$page_id} ; signed_page_id: {$signed_page_id}");
				$res = sign_apply_sign_to_page($page_id, $signed_page_id, $sign_id, $page_w, $page_h, $sign_w, $sign_h, $sign_x, $sign_y);
				$arr = json_decode($res, true);
        	}
        }

        if(!isset($arr['err_msg']) || ($arr['err_msg'] == '')) {
			pdf_convert_from_png($signed_pdf_id);
			$signed_pdf_dir = getcwd() . '/../' . UPLOAD_DIR . '/pdf/signed';
			$signed_doc_size = filesize($signed_pdf_dir . '/' . $signed_pdf_id . '.pdf');
			if($is_signed_in) {
				model_doc_sign($pdf_id, $signed_pdf_id, $signed_doc_size);
			} else {
				$_SESSION['docs'][$signed_pdf_id]['name'] = $_SESSION['docs'][$pdf_id]['name'];
				$_SESSION['docs'][$signed_pdf_id]['time'] = time();
				$_SESSION['docs'][$signed_pdf_id]['size'] = $signed_doc_size;
				$_SESSION['docs'][$signed_pdf_id]['pages'] = $_SESSION['docs'][$pdf_id]['pages'];
				$_SESSION['docs'][$signed_pdf_id]['signed'] = 1;
				//unset($_SESSION['docs'][$pdf_id]);
			}
			echo "$('.page-container[id={$page_id}] .page-content > .page-preview').attr('src', '/uploads/img/signed/{$signed_page_id}.png');\n";
			echo "$('#signButton').addClass('disabled');\n";
			echo "$('#signPreview').remove();\n";
	        echo '$("*").css("cursor", "default");' . "\n";
			echo "document.location.href = '/{$lang}/docs/{$signed_pdf_id}/';\n";
		}
		break;
}
