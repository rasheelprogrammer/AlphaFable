<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-buyHouse - v0.0.1
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows > 0 && $char_result->num_rows > 0) {
        $item_id = $doc->getElementsByTagName('intHouseID')->item(0)->nodeValue;

        $item_result = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$item_id}'");
        $item = $item_result->fetch_assoc();

        $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND CharID = '{$charID}' AND House = 1 AND HouseItem = 0 LIMIT 1");
        $query_rows = $query->num_rows;
        $query_fetched = $query->fetch_assoc();

        if ($item['intCurrency'] == 2) {
            $newgold = $char['gold'] - $item['intCost'];
            if ($newgold < 0) {
                $error = 1;
            } else {
                $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$charID}'");
            }
        } else if ($item['intCurrency'] == 1) {
            $newgold = $char['Coins'] - $item['intCost'];
            if ($newgold < 0) {
                $error = 1;
            } else {
                $takegold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$charID}'");
            }
        }
        if ($error != 1) {
            $removedefault = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `StartingItem` = '1' AND House = 1");
            $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$charID}', '{$item_id}', '1', '1', '1', '0', '1', '0', '')");
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('buyMech'));
                $character = $XML->appendChild($dom->createElement('buyMech'));
                $character->setAttribute('CharHouseID', $item_id);
            } else {
                $Game->returnXMLError('Error!', 'There was an updating your character information.');
            }
        } else {
            $reason = "Error!";
            $message = "Insufficient Funds";
            $Game->returnXMLError("{$reason}", "{$message}");
        }
    } else {
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";
        $Game->returnXMLError("{$reason}", "{$message}");
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>