<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-buyHouse - v0.0.1
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $result[1] = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $ItemID = $doc->getElementsByTagName('intHouseID')->item(0)->nodeValue;

            $query[2] = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$ItemID}'");
            $result[2] = $query[2]->fetch_assoc();

            if ($result[2]['intCurrency'] == 2) {
                $newgold = $char['gold'] - $result[2]['intCost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$charID}'");
                }
            } else if ($result[2]['intCurrency'] == 1) {
                $newgold = $char['Coins'] - $result[2]['intCost'];
                if ($newgold < 0) {
                    $error = 1;
                } else {
                    $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$charID}'");
                }
            }
            if ($error != 1 && $MySQLi->affected_rows > 0) {
                $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `StartingItem` = '1' AND House = 1");
                $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$charID}', '{$ItemID}', '1', '1', '1', '0', '1', '0', '')");
                if ($MySQLi->affected_rows > 0) {
                    $dom = new DOMDocument();
                    $XML = $dom->appendChild($dom->createElement('buyMech'));
                    $character = $XML->appendChild($dom->createElement('buyMech'));
                    $character->setAttribute('CharHouseID', $ItemID);
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