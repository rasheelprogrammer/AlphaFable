<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemdestroy - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $CharID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;
    $item_id = $doc->getElementsByTagName('intCharItemID')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $result[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $result[0]->fetch_assoc();
    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $userQuery->fetch_assoc();

    if($userQuery->num_rows > 0 && $result[0]->num_rows > 0) {
        $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}' LIMIT 1");
        $item = $item_result->fetch_assoc();

        $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
        if ($query->num_rows == 1) {
            $query_fetched = $query->fetch_assoc();
            if ($query_fetched['count'] > 1) {
                $newcount = $query_fetched['count'] - 1;
                $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
            } else {
                $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$CharID} AND `ItemID` = {$item_id} AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
            }
            if ($MySQLi->affected_rows > 0) {
                $Game->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Game->returnXMLError('Error!', 'There was an updating your character information.');
            }
            echo $dom->saveXML();
        } else {
            $Game->returnXMLError('Error!', 'Character information was unable to be requested.');
        }
    } else {
        $Game->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>