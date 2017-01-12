<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-equiphouse - v0.0.1
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

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
            $item_id = $doc->getElementsByTagName('intCharHouseID')->item(0)->nodeValue;

            $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND CharID = '{$CharID}' AND House = 1 AND HouseItem = 0 LIMIT 1");
            $query_rows = $query->num_rows;
            $query_fetched = $query->fetch_assoc();

            if ($query_rows == 1) {
                $removedefault = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `StartingItem` = '1' AND House = 1");
                $setdefault = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '1' WHERE `ItemID` = '{$item_id}' AND House = 1 AND CharID = {$CharID}");
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'There was an updating your character information.');
                }
            } else {
                $Core->returnXMLError('Error!', 'No house found in character inventory.');
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