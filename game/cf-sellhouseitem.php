<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-sellhouseitem - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $CharID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $item_id = $doc->getElementsByTagName('intCharHouseItemID')->item(0)->nodeValue;

            $item_result = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$item_id}'");
            $item = $item_result->fetch_assoc();

            $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 1 LIMIT 1");
            $query_rows = $query->num_rows;
            $query_fetched = $query->fetch_assoc();
            if ($item['intCurrency'] == 2) {
                $newgold = $result[0]['gold'] + $item['intCost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$CharID}'");
                }
            } else if ($item['intCurrency'] == 1) {
                $newgold = $result[0]['Coins'] + $item['intCost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $takegold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$CharID}'");
                }
            }
            if ($error != 1) {
                if ($query_fetched['count'] > 1 && $item['intMaxStackSize'] > 1) {
                    $newcount = $query_fetched['count'] - 1;
                    $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
                } else {
                    $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$CharID} AND `ItemID` = {$item_id} AND House = 0 AND HouseItem = 1 LIMIT 1");
                }
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'There was an updating your character information.');
                }
            } else {
                $reason = "Error!";
                $message = "Insufficient Funds";
                $Core->returnXMLError("{$reason}", "{$message}");
            }
        } else {
            $Core->returnXMLError('Error!', 'User not found in the database.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character not found in the database.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>