<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-buyhouseitem - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows > 0 && $char_result->num_rows > 0) {
        $item_id = $doc->getElementsByTagName('intHouseItemID')->item(0)->nodeValue;

        $item_result = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$item_id}'");
        $item = $item_result->fetch_assoc();

        $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 1");
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
            if ($query_fetched['count'] > 0 && $item['intMaxStackSize'] > 0) {
                $newcount = $query_fetched['count'] + 1;
                $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
            } else {
                $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$charID}', '{$item_id}', '0', '1', '1', '0', '0', '1', '');");
            }
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('buyMech'));
                $character = $XML->appendChild($dom->createElement('buyMech'));
                $character->setAttribute('CharHouseItemID', $item_id);
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
            }
        } else {
            $reason = "Error!";
            $message = "Insufficient Funds";
            $Core->returnXMLError("{$reason}", "{$message}");
        }
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