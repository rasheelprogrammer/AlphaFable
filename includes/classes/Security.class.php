<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Security.class - v0.0.1
 */

	$Security = new Security();

	class Security {
		var $key = 'AlphaFable1337';

		public function safe_b64encode($string){
			$data = base64_encode($string);
			$data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
			return $data;
		}

		public function safe_b64decode($string){
			$data = str_replace(['-', '_'], ['+', '/'], $string);
			$mod4 = strlen($data) % 4;
			if($mod4){
				$data .= substr('====', $mod4);
			}
			return base64_decode($data);
		}

		public function encode($value){
			return trim($this->safe_b64encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		}
		
		public function checkAccessLevel($userAccess, $requiredAccess){
			$status = [
				0  => 'Normal Player',
				5  => 'Guardian',
				10 => 'DragonLord',
				15 => 'Beta Tester',
				20 => 'Alpha Tester',
				25 => 'Moderator',
				30 => 'Staff',
				35 => 'Designer',
				40 => 'Programmer',
				45 => 'Administrator',
				50 => 'Owner'
            ];
				
			switch($status[$userAccess]){
				case ($userAccess < 0):
					return "Banned";
					break;
				case ($userAccess < $requiredAccess):
					return "Invalid";
					break;
				default:
					return "OK";
					break;
			}
		}
		
		public function decode($value){
			return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $this->safe_b64decode($value), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		}
	}
?>