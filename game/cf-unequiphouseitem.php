<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-unequiphouseitem - v0.0.3
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->intCharID) && isset($xml->strToken)) {
        $charID = $xml->intCharID;
        $token = $xml->strToken;
        $equipSlot = $xml->intEquipSlot;

        $query = array();
        $result = array();

        $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
        $result[0] = $query[0]->fetch_assoc();

        if ($query[0]->num_rows != 0) {
            $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
            if ($query[1]->num_rows != 0) {
                $query[2] = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$charID}' AND intEquipSlotPos = '{$equipSlot}' AND HouseItem = '1'");
                if ($query[2]->num_rows > 0) {
                    $equipitem = $MySQLi->query("UPDATE `df_equipment` SET `intEquipSlotPos` = '0' WHERE intEquipSlotPos =  '{$equipSlot}' AND HouseItem = 1 AND CharID = {$charID};");
                    if ($MySQLi->affected_rows > 0) {
                        $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                    } else {
                        $Core->returnXMLError('Inventory Error', 'Inventory information could not be modified.');
                    }
                } else {
                    $Core->returnXMLError('Inventory Error', 'Inventory information was unable to be requested.');
                }
            } else {
                $Core->returnXMLError('User Not Found', 'Character information was unable to be requested.');
            }
        } else {
            $Core->returnXMLError('Character Not Found', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>