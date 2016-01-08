<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-sellhouse - v0.0.2
 */
include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows > 0 && $char_result->num_rows > 0) {
        $item_id = $doc->getElementsByTagName('intCharHouseID')->item(0)->nodeValue;

        $item_result = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$item_id}'");
        $item = $item_result->fetch_assoc();

        $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 1 AND HouseItem = 0 LIMIT 1");
        $query_rows = $query->num_rows;
        $query_fetched = $query->fetch_assoc();
        if ($item['intCurrency'] == 2) {
            $newgold = $char['gold'] + $item['intCost'];
            if ($newgold < 0) {
                $error = 1;
            } else {
                $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$charID}'");
            }
        } else if ($item['intCurrency'] == 1) {
            $newgold = $char['Coins'] + $item['intCost'];
            if ($newgold < 0) {
                $error = 1;
            } else {
                $takegold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$charID}'");
            }
        }
        if ($error != 1) {
            if ($query_fetched['count'] > 1) {
                $newcount = $query_fetched['count'] - 1;
                $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
            } else {
                $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 1 AND HouseItem = 9 LIMIT 1");
                if ($query_fetched['StartingItem'] == 1) {
                    $MySQLi->query("UPDATE `df_characters` SET `HasHouse` = '0' WHERE `id` = {$charID};");
                }
                $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$charID} AND `ItemID` = {$item_id} AND House = 1 AND HouseItem = 0 LIMIT 1");
            }
            if ($MySQLi->affected_rows > 0) {
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', "There was an error up{$item_id}dating your character information.");
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