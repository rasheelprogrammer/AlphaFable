<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemdestroy - v0.0.3
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
    $item_id = $doc->getElementsByTagName('intCharItemID')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $result[1] = $query[1]->fetch_assoc();

    if ($result[1]->num_rows > 0) {
        if ($result[0]->num_rows > 0) {
            $query[2] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}' LIMIT 1");
            $result[2] = $query[2]->fetch_assoc();

            $query[3] = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 0 LIMIT 1");
            if ($query[3]->num_rows == 1) {
                $result[3] = $query[3]->fetch_assoc();
                if ($result[3]['count'] > 1) {
                    $newcount = $result[3]['count'] - 1;
                    $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$result[3]['id']}'");
                } else {
                    $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$CharID} AND `ItemID` = {$item_id} AND House = 0 AND HouseItem = 0 LIMIT 1");
                }
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'There was an updating your character information.');
                }
                echo $dom->saveXML();
            } else {
                $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
            }
        } else {
            $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'User information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>