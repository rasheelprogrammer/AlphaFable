<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-itembuy - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $CharID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $intItemID = $doc->getElementsByTagName('intItemID');
            $item_id = $intItemID->item(0)->nodeValue;

            $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}'");
            $item = $item_result->fetch_assoc();

            $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 0");
            $query_rows = $query->num_rows;
            $query_fetched = $query->fetch_assoc();

            if ($item['Currency'] == 2) {
                $newgold = $result[0]['gold'] - $item['Cost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$CharID}'");
                }
            } else if ($item['Currency'] == 1) {
                $newgold = $result[0]['Coins'] - $item['Cost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $takegold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$CharID}'");
                }
            }
            if ($error != 1) {
                if ($query_fetched['count'] > 0 && $item['MaxStackSize'] > 0) {
                    $newcount = $query_fetched['count'] + 1;
                    $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
                } else {
                    $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$CharID}', '{$item_id}')");
                }
                if ($MySQLi->affected_rows > 0) {
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'There was an updating your character information.');
                }
            } else {
                $Core->returnXMLError("Error!", "Insufficient Funds.");
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