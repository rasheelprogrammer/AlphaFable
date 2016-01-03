<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemsell - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $CharID = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $user = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $intCharItemID = $doc->getElementsByTagName('intCharItemID');
            $item_id = $intCharItemID->item(0)->nodeValue;

            $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}'");
            $item = $item_result->fetch_assoc();

            $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 0 AND count >= '1'");
            $query_fetched = $query->fetch_assoc();

            if ($query->num_rows >= 1) {
                if ($item['Currency'] == 2) {
                    $newgold = $result[0]['gold'] + $item['Cost'];
                    $addgold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$CharID}'");
                } else if ($item['Currency'] == 1) {
                    $newgold = $result[0]['Coins'] + $item['Cost'];
                    $addgold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$CharID}'");
                }
                if ($query_fetched['count'] > 1) {
                    $newcount = $query_fetched['count'] - 1;
                    $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
                } else {
                    $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$CharID} AND House = 0 AND HouseItem = 0 AND `ItemID` = {$item_id} LIMIT 1");
                }
                if ($MySQLi->affected_rows > 0) {
                    $Game->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Game->returnXMLError('Error!', 'There was an updating your character information.');
                }
            } else {
                $reason = "Error!";
                $message = "There was an issue with your inventory... Please Login and try again";
                $Game->returnXMLError("{$reason}", "{$message}");
            }
        } else {
            $Core->returnXMLError('Error!', 'User not found in the database.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character not found in the database.');
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>