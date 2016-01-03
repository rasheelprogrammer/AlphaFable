<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Files.class - v0.0.1
 */

	$Files = new Files();

	class Files {
		
		public function dfCurl($url,$payload){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
			$result = curl_exec($ch);
			return $result;
		}

		public function fileDownload ($urlBase, $urlFile) {
			$urlFull = $urlBase . $urlFile;
			$FileName = explode("/", $urlFile);
			$FileName = $FileName[count($FileName) - 1];
			$urlPath = parse_url($urlFull);
			$urlPath = $urlPath["path"];
			$urlPath = str_replace("/$urlPath", "", $urlPath);
			$urlPath = preg_split('(/)', $urlPath, -1, PREG_SPLIT_NO_EMPTY);
			for ($a = 0; $a < count($urlPath) - 1; $a++) {
				if (!file_exists($urlPath[$a])) {
					mkdir($urlPath[$a]);
					chdir($urlPath[$a]);
				} else {
					chdir($urlPath[$a]);
				}
			}
			if (!@file_exists($Path)) {
				copy($urlFull, $FileName);   
				for ($a = 0; $a < count($urlPath) - 1; $a++) {
					chdir("..\\");
				}
				return true;
			} else {
				return false;
			}
		}
	
		public function CheckCreateDir($urlBase, $urlFile, $class) {
			$urlComplete = $urlBase.$urlFile;
			$urlFull = str_replace("game/", "", $urlComplete);

			$urlPath = parse_url($urlFull);
			$urlPath = $urlPath["path"];
			$urlPath = str_replace("/$urlPath", "", $urlPath);
			$urlPath = preg_split('(/)', $urlPath, -1, PREG_SPLIT_NO_EMPTY);

			for ($a = 0; $a < count($urlPath) - 1; $a++) {
				if (!file_exists($urlPath[$a])) {
					mkdir($urlPath[$a]);
					chdir($urlPath[$a]);
				} else {
					chdir($urlPath[$a]);
				}
			}
			for ($a; $a > 1; $a--) {
				chdir("../");
			}
		}
		
		public function writeDownloadLog($theText) {
			$fp = fopen("filelog.txt", "w");
            fwrite($fp, $theText);
            fclose($fp);
			return;
		}
	}
?>