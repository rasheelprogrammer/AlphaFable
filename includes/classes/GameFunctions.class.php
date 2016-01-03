<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Game.class - v0.0.1
 */

	$Game = new Game();

	class Game {
		var $key = 'RandomKeyBitchesL0043l1';

		public function makeXML(){
			header("Content-Type: application/xml");
			header("Charset: UTF-8");
			error_reporting(1);
			ini_set('error_reporting', 1);
		}

		public function safe_b64encode($string){
			$data = base64_encode($string);
			$data = str_replace(array('+', '/', '='),array('-', '_', ''), $data);
			return $data;
		}

		public function safe_b64decode($string){
			$data = str_replace(array('-', '_'), array('+', '/'), $string);
			$mod4 = strlen($data) % 4;
			if($mod4){
				$data .= substr('====', $mod4);
			}
			return base64_decode($data);
		}

		public function encode($value){
			return trim($this->safe_b64encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		}
		
		public function decode($value){
			return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $this->safe_b64decode($value), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		}

		public function removeNinjaTags($theText) {
			$RemoveNinjaTag1 = str_replace("<ninja2>","",file_get_contents('php://input'));
			$RemoveNinjaTag2 = str_replace("</ninja2>","",$RemoveNinjaTag1);
			return($RemoveNinjaTag2);
		}
		
		public function decodeNinja($theText) {
			$RemoveNinjaTag1 = str_replace("<ninja2>","",$theText);
			$theNewText = str_replace("</ninja2>","",$RemoveNinjaTag1);
			$strKey = "ZorbakOwnsYou";
			$text = strlen($theNewText);
			$key = strlen($strKey);
			$result = "";
		
			for ($_loc1 = 0; $_loc1 < $text; $_loc1 = $_loc1 + 4) {
				$_loc5 = intval(substr($theNewText, $_loc1, 2), 30);
				$_loc4 = intval(substr($theNewText, $_loc1 + 2, 2), 30);
				$_loc2 = ord($strKey{$_loc1 / 4 % $key});
				$result = $result . chr($_loc5 - $_loc4 - $_loc2);
			}
		
			return ($result);
		}	
		
		function encryptNinja($theText) {
			$text = strlen($theText);
			$strKey = "ZorbakOwnsYou";
			$key = strlen($strKey);
			for ($_loc1 = 0; $_loc1 < $text; ++$_loc1) {
				$_loc2 = floor( ( floatval("0.".rand().rand().rand() ) ) * 66 ) + 33;
				$_loc3 = ord($strKey{$_loc1 % $key});
				$_loc4 = $_loc4 . base_convert((ord($theText{$_loc1}) + $_loc2 + $_loc3),10 ,30) . base_convert($_loc2, 10, 30);
			}
			return ("<ninja2>".$_loc4."</ninja2>");
		}
	
		public function writeLog($theText) {
                	$fp = fopen("log.txt", "w");
                	fwrite($fp, $theText);
                	fclose($fp);
		}

		function returnXMLError($title, $message){
			$dom = new DOMDocument();
			$XML = $dom->appendChild($dom->createElement('error'));
			$info = $XML->appendChild($dom->createElement('info'));
			$info->setAttribute('code', '526.14');
			$info->setAttribute('reason', $title);
			$info->setAttribute('message', '<p align="justify">'.$message.'</p>');
			$info->setAttribute('action', 'None');
			echo($dom->saveXML());
			exit;
		}

		function returnCustomXMLMessage($element, $name, $value){
			$dom = new DOMDocument();
			$XML = $dom->appendChild($dom->createElement($element));
			$XML->setAttribute($name, $value);
			echo($dom->saveXML());
			exit;
		}

		function sendVar($name, $value){
			echo("&{$name}={$value}");
		}

		function sendErrorVar($reason, $message){
			echo("&code=527.07&reason={$reason}&message={$message}&action=none");
			exit;
		}

		function reorder($which){
			switch(strToLower($which)){
				case 'characters':
					
					break;
			}
		}
		
		function calcEXPtoLevel($level, $exp){
			switch($level){
                case ($level < 10):
                    $exptolevel = pow(2, $level) * 10;
                    break;
                case ($level >= 10 && $level < 60):
                    $exptolevel = pow($level, 2) * 90;
                    break;
                case ($level >= 60 && $level < 70):
                    $exptolevel = (24200 * $level) - 1032000; //Doesn't Work for any higher then 61
                    break;
                case ($level >= 70 && $level < 80)://The XP needed starts at 1,700,000 and adds 2 Million every level.
                    $exptolevel = $level + 2000000;
                    break;
				case ($level >= 80): //Level Cap.
					$exptolevel = "999999999";
                    break;
			}
			return $exptolevel;
		}
		
		function valueCheck($value){
			switch($value){
                default:
					$result = $value;
					break;
                case 10:
 					$result = "A";
					break;
				case 11:
 					$result = "B";
					break;
				case 12:
 					$result = "C";
					break;
				case 13:
 					$result = "D";
					break;
				case 14:
 					$result = "E";
					break;
				case 15:
 					$result = "F";
					break;
				case 16:
 					$result = "G";
					break;
				case 17:
 					$result = "H";
					break;
				case 18:
 					$result = "I";
					break;
				case 19:
 					$result = "J";
					break;
				case 20:
 					$result = "K";
					break;
				case 21:
 					$result = "L";
					break;
				case 22:
 					$result = "M";
					break;
				case 23:
 					$result = "N";
					break;
				case 24:
 					$result = "O";
					break;
				case 25:
 					$result = "P";
					break;
				case 26:
 					$result = "Q";
					break;
				case 27:
 					$result = "R";
					break;
				case 28:
 					$result = "S";
					break;
				case 29:
 					$result = "T";
					break;
				case 30:
 					$result = "U";
					break;
				case 31:
 					$result = "V";
					break;
				case 32:
 					$result = "W";
					break;
				case 33:
 					$result = "X";
					break;
				case 34:
 					$result = "Y";
					break;
				case 35:
 					$result = "Z";
					break;
			}
			return $result;
		}
	}
?>