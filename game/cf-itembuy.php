<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-itembuy - v0.0.2
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
    $item_id = $doc->getElementsByTagName('intItemID')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $query[2] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}'");
            $result[2] = $query[2]->fetch_assoc();
            if ($query[2]->num_rows > 0) {
                switch ($result[2]['Currency']) {
                    case 1:
                        $currency = "Coins";
                        $newVAL = $result[0]["Coins"] - $result[2]['Cost'];
                        break;
                    case 2:
                    default:
                        $currency = "gold";
                        $newVAL = $result[0]["gold"] - $result[2]['Cost'];
                        break;
                }
                if ($newVAL >= 0) {
                    $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 0");
                    $query_fetched = $query->fetch_assoc();

                    $MySQLi->query("UPDATE df_characters SET {$currency}='{$newVAL}' WHERE ID='{$CharID}'");
                    if ($query->num_rows > 0 && $MySQLi->affected_rows > 0) {
                        if ($query_fetched['count'] > 0 && $result[2]['MaxStackSize'] > 0) {
                            $newcount = $query_fetched['count'] + 1;
                            $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
                            if ($MySQLi->affected_rows > 0) {
                                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                            } else {
                                $Core->returnXMLError('Error!', 'There was updating your item stack count.');
                            }
                        } else {
                            $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$CharID}', '{$item_id}')");
                            if ($MySQLi->affected_rows > 0) {
                                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                            } else {
                                $Core->returnXMLError('Error!', 'There was adding the item to your inventory.');
                            }
                        }
                    } else {
                        $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$CharID}', '{$item_id}')");
                        if ($MySQLi->affected_rows > 0) {
                            $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                        } else {
                            $Core->returnXMLError('Error!', 'There was adding the item to your inventory2.');
                        }
                    }
                } else {
                    $Core->returnXMLError("Error!", "Insufficient Funds");
                }
            } else {
                $Core->returnXMLError("Error!", "Item not found in database.");
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