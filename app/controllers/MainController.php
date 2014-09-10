<?php

class MainController extends BaseController {
    
    /**
     * Endpoint per il caricamento dei file con Plupload
     */
    public function upload() {
        $targetDir = Config::get('mvideo.upload-folder');
            
            
        // Creiamo la cartella se non esiste
        if (!file_exists($targetDir)) {
                @mkdir($targetDir);
        }
            
        // Prendiamo il nome del file
        if (isset($_REQUEST["name"])) {
                $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
                $fileName = $_FILES["file"]["name"];
        } else {
                $fileName = uniqid("file_");
        }
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
            
        // Chunking !
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
            
            
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
            
        if (!empty($_FILES)) {
                if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }
                    
                if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
        } else {	
                if (!$in = @fopen("php://input", "rb")) {
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
        }
            
        while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
        }
            
        @fclose($out);
        @fclose($in);
            
        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
                // Strip the temp .part suffix off 
                rename("{$filePath}.part", $filePath);
        }
            
        Session::put('video-uploaded', $filePath);
        die('{"jsonrpc" : "2.0", "result" : "'.$filePath.'", "id" : "id"}');
    }
    
    
    public static function results() {
	$results = Result::whereNotNull('data')->get();
	$wifi_hi = array();
	$wifi_mid = array();
	$wifi_low = array();
	$mobile = array();
	foreach ($results as $r) {



	    if($r->brightness >= 87.5) {
		    $lvl = 100;
		} else if($r->brightness < 87.5 && $r->brightness >= 62.5) {
		    $lvl = 75;
		} else if($r->brightness < 62.5 && $r->brightness >= 37.5) {
		    $lvl = 50;
		} else if($r->brightness < 37.5 && $r->brightness >= 12.5) {
		    $lvl = 25;
		} else {
		    $lvl = 0;
		}

	    $json = json_decode($r->data);
	    // Ordiniamo l'array e convertiamo in secondi
		$temp = array();
		foreach ($json as $key => $value) {
		    $temp[intval($value)] = strtotime($key);
		}
		krsort($temp);


		$values = array();
		foreach ($temp as $key => $value) {
		    if(isset($temp[$key-1])) {
			if($r->wifi == 'Connected') {
			    if($r->signal_strength >= 90) {
				$wifi_hi[$lvl][] = ($temp[$key-1]-$temp[$key])/60;
			    } else if($r->signal_strength >= 70) {
				$wifi_mid[$lvl][] = ($temp[$key-1]-$temp[$key])/60;
			    } else {
				$wifi_low[$lvl][] = ($temp[$key-1]-$temp[$key])/60;
			    }

			} else {
			    $mobile[$lvl][] = ($temp[$key-1]-$temp[$key])/60;
		    }
		}

	    }

	}
	foreach ($wifi_hi as $key => $value) {
	    $wifi_hi[$key] = round(array_sum($value) / count($value),2);
	}
	foreach ($wifi_mid as $key => $value) {
	    $wifi_mid[$key] = round(array_sum($value) / count($value),2);
	}
	
	foreach ($wifi_low as $key => $value) {
	    $wifi_low[$key] = round(array_sum($value) / count($value),2);
	}
	foreach ($mobile as $key => $value) {
	    $mobile[$key] = round(array_sum($value) / count($value),2);
	}
	
	ksort($wifi_hi);
	ksort($wifi_mid);
	ksort($wifi_low);
	ksort($mobile);

	 return array('mobile' => $mobile, 'wifi-hi' => $wifi_hi, 'wifi-low' => $wifi_low, 'wifi-mid' => $wifi_low);
    }

}
