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
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $result[1] = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $ItemID = $doc->getElementsByTagName('intHouseID')->item(0)->nodeValue;

            $query[2] = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$ItemID}'");
            $result[2] = $query[2]->fetch_assoc();

            switch ($result[2]['intCurrency']) {
                case 1:
                    $currency = "Coins";
                    $newVAL = $result[0]["Coins"] - $result[2]['intCost'];
                    break;
                case 2:
                default:
                    $currency = "gold";
                    $newVAL = $result[0]["gold"] - $result[2]['intCost'];
                    break;
            }
            if ($MySQLi->affected_rows > 0) {
                if ($newVAL >= 0) {
                    $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `StartingItem` = '1' AND House = 1");
                    $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`, `StartingItem`, `count`, `Level`, `Exp`, `House`, `HouseItem`, `intEquipSlotPos`) VALUES (NULL, '{$charID}', '{$ItemID}', '1', '1', '1', '0', '1', '0', '')");
                    $MySQLi->query("UPDATE `df_characters` SET `HasHouse` = '1' WHERE `id` = {$charID};");
                    if ($MySQLi->affected_rows > 0) {
                        $dom = new DOMDocument();
                        $XML = $dom->appendChild($dom->createElement('buyMech'));
                        $character = $XML->appendChild($dom->createElement('buyMech'));
                        $character->setAttribute('CharHouseID', $ItemID);
                    } else {
                        $Core->returnXMLError('Error!', 'There was an issue updating your character information.');
                    }
                } else {
                    $Core->returnXMLError("Error!", "There was an issue updating your {$currency}.");
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