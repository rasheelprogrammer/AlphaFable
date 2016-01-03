<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-goldsubtract - v0.0.3
 */

    require ("../includes/classes/Core.class.php");
    require ('../includes/config.php');

    $Core->makeXML();
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    if(!empty($HTTP_RAW_POST_DATA)){
        $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);
		if(!empty($xml->intCharID) && !empty($xml->strToken) && !empty($xml->intGold)){
			$charID = $xml->intCharID;
			$token = $xml->strToken;
			$gold = $xml->intGold;

			$query = array();
			$result = array();
			
			$query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
			$result[0] = $query[0]->fetch_assoc();

			$query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");

			if($query[1]->num_rows > 0 && $query[0]->num_rows > 0){
					$gold_left = $result[0]['gold'] - $Gold;
					$ChangeGold = $MySQLi->query("UPDATE df_characters SET gold='{$gold_left}' WHERE ID='{$charID}'");
					if($MySQLi->affected_rows == 0){
						$Core->returnXMLError('Error!', 'There was an updating your character information.');
					}
			} else {
				$Core->returnXMLError('Error!', 'Character information was unable to be requested.');
			}
		} else {
			$Core->returnXMLError('Error!', 'There was a problem communicating with the client.');
		}
    } else {
        $Core->returnXMLError('Invalid Data!', 'Message');
	}
echo $dom->saveXML();
$MySQLi->close();
?>