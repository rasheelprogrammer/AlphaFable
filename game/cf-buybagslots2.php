<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-buybagslots - v0.0.3
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $CharID = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $intNumSlots = $doc->getElementsByTagName('intNumSlots');
    $NumSlots = $intNumSlots->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $result[1] = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            if ($result[0]['MaxBagSlots'] <= 20) {
                $cost = $NumSlots * 50;
            } else if ($result[0]['MaxBagSlots'] > 20 && $result[0]['MaxBagSlots'] <= 30) {
                $cost = $NumSlots * 100;
            } else if ($result[0]['MaxBagSlots'] > 30 && $result[0]['MaxBagSlots'] <= 35) {
                $cost = $NumSlots * 300;
            } else if ($result[0]['MaxBagSlots'] > 35 && $result[0]['MaxBagSlots'] <= 110) {
                $cost = $NumSlots * 500;
            }
            $newcoins = $result[0]['Coins'] - $cost;
            $takecoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$CharID}'");

            $newslots = $result[0]['MaxBagSlots'] + $NumSlots;
            $addslots = $MySQLi->query("UPDATE df_characters SET MaxBagSlots='{$newslots}' WHERE ID='{$CharID}'");
            if ($MySQLi->affected_rows > 0) {
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError("Error!", "There was an issue updating your character information.");
            }
        } else {
            $Core->returnXMLError('Error!', 'User not found in the database.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character not found in the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>