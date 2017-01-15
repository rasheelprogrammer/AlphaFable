<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemsell - v0.0.2
 */

//TODO: Fix Item stacking and CharItemID

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $CharID = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $intCharItemID = $doc->getElementsByTagName('intCharItemID');
    $ItemID = $intCharItemID->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $query[2] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$ItemID}'");
            $result[2] = $query[2]->fetch_assoc();

            $query[3] = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$ItemID}' AND House = 0 AND HouseItem = 0 AND count >= '1'");
            $result[3] = $query[3]->fetch_assoc();

            if ($query[3]->num_rows >= 1) {
                if ($result[2]['Currency'] == 2) {
                    $newgold = $result[0]['gold'] + $result[2]['Cost'];
                    $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$CharID}'");
                } else if ($result[2]['Currency'] == 1) {
                    $newgold = $result[0]['Coins'] + $result[2]['Cost'];
                    $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$CharID}'");
                }
                if ($result[3]['count'] > 1) {
                    $newcount = $result[3]['count'] - 1;
                    $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$result[3]['id']}'");
                } else {
                    $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$CharID} AND House = 0 AND HouseItem = 0 AND `ItemID` = {$ItemID} LIMIT 1");
                }
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'There was an updating your character information.');
                }
            } else {
                $Core->returnXMLError("Error!", "There was an issue updating your inventory.");
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