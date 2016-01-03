<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: Ninja.class - v0.0.1
 */

	$Ninja = new Ninja();

	class Ninja {
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
	}
?>