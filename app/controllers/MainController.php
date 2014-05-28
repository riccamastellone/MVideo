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

}
