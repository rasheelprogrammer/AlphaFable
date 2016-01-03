<?php #FILE NEEDS REDO
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-equiphouseitem - v0.0.2
 */
 
    require ("../includes/classes/Core.class.php");
    require ('../includes/config.php');

    $Core->makeXML();
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    if(!empty($HTTP_RAW_POST_DATA)){
        $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

		$charID = $xml->intCharID;
		$token = $xml->strToken;
		$equipSlot = $xml->intEquipSlot;
		$ItemID = $xml->intCharHouseItemID;

		$char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
		$char = $char_result->fetch_assoc();
		$user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");
		$item_result = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$charID}' AND ItemID = '{$ItemID}' AND HouseItem = '1'");

		if($item_result->num_rows > 0 && $user_result->num_rows > 0 && $char_result->num_rows > 0){
			$equipitem = $MySQLi->query("UPDATE `df_equipment` SET `intEquipSlotPos` = '{$equipSlot}' WHERE ItemID =  '{$ItemID}' AND HouseItem = 1;");
			$Core->returnCustomXMLMessage("status", "status", "SUCCESS");
		} else {
			$reason = "Error!";
			$message = "There was an issue with your account... Please Login and try again";
			$Core->returnXMLError("{$reason}", "{$message}");
		}
        echo $dom->saveXML();
    } else {
        $Core->returnXMLError('Invalid Data!', 'Message');
	}
$MySQLi->close();

?>