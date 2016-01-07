<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-equiphouseitem - v0.0.2
 */

require ("../includes/classes/Core.class.php");
require ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    $CharID = $xml->intCharID;
    $token = $xml->strToken;
    $equipSlot = $xml->intEquipSlot;
    $ItemID = $xml->intCharHouseItemID;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();

    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    $query[2] = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$CharID}' AND ItemID = '{$ItemID}' AND HouseItem = '1'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            if ($query[2]->num_rows > 0) {
                $MySQLi->query("UPDATE `df_equipment` SET `intEquipSlotPos` = '{$equipSlot}' WHERE ItemID =  '{$ItemID}' AND HouseItem = 1;");
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError("Error!", "There was an issue with the item... Please Login and try again");
                }
            } else {
                $Core->returnXMLError("Error!", "There was an issue with your inventory... Please Login and try again");
            }
        } else {
            $Core->returnXMLError('Error!', 'Character not found in the database.');
        }
    } else {
        $Core->returnXMLError('Error!', 'User not found in the database.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>