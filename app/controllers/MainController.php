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
    
    /**
     * Ritorniamo i risultati estraendo i dati da DB per mostrarli in un
     * grafico.
     * La domanda a cui risponde è "Quanto dura l'X% di batteria?" con X percentuale
     * 
     * @param bool $divide
     * @return array
     */
    public static function results($divide = FALSE) {
	if($divide) {
	    $imeis = Result::groupBy('imei')->get();
	    foreach ($imeis as $imei) {
		$data = Result::where('imei',$imei->imei)->whereNotNull('data')->get();
		$results[$imei->imei] = self::handleResults($data);
	    }
	} else {
	    $data = Result::whereNotNull('data')->get();
	    $results = self::handleResults($data);
	}
	return $results;
	
    }
    
    /**
     * Il device passa nella POST del risultato un array con i timestamp della scarica,
     * ma non sono in ordine e ci sono duplicati. Questa funzione pulisce questi dati.
     * @param Json $data
     * @return array
     */
    static public function cleanData($data) {
	$json = json_decode($data);
	    
	// Ordiniamo l'array e convertiamo in secondi
	$temp = array();
	foreach ($json as $key => $value) {
	    // Se esiste già un timestamp per questo valore
	    if(isset($temp[intval($value)])) {
		// Se il tempo di quello preso in considerazione è maggiore di 
		// quello presente, lo sostituisco
		if(strtotime($key) > $temp[intval($value)]) {
		    $temp[intval($value)] = strtotime($key);
		}
	    } else {
		$temp[intval($value)] = strtotime($key);
	    }
		
	}
	// Ordinamento verso il basso (eg 59-58-57)
	krsort($temp);
	return $temp;
    }
    
    /**
     * Suddividiamo la luminosità in 4 principali macrocategorie per 
     * rendere i dati più significativi
     * @param int $lvl
     * @return int
     */
    static public function brightnessLevel($lvl) {
	if($lvl >= 87.5) {
	    $lvl = 100;
	} else if($lvl < 87.5 && $lvl >= 62.5) {
	    $lvl = 75;
	} else if($lvl < 62.5 && $lvl >= 37.5) {
	    $lvl = 50;
	} else if($lvl < 37.5 && $lvl >= 12.5) {
	    $lvl = 25;
	} else {
	    $lvl = 0;
	}
	return $lvl;
    }
    
    static public function handleResults($results) {
	foreach ($results as $r) {
	    
	    $lvl = self::brightnessLevel($r->brightness);
	    $temp = self::cleanData($r->data);
	    
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

	return array('mobile' => $mobile, 'wifi-hi' => $wifi_hi, 'wifi-low' => $wifi_low, 'wifi-mid' => $wifi_mid);
	
    }

}
