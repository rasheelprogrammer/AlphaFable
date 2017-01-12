<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-hairbuy - v0.0.2
 */

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

    $intHairID = $doc->getElementsByTagName('intHairID');
    $HairID = $intHairID->item(0)->nodeValue;

    $intColorHair = $doc->getElementsByTagName('intColorHair');
    $ColorHair = $intColorHair->item(0)->nodeValue;

    $intColorSkin = $doc->getElementsByTagName('intColorSkin');
    $ColorSkin = $intColorSkin->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $user = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $hair_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
            $hair = $hair_result->fetch_assoc();

            $newgold = $result[0]['gold'] - $hair['gold'];
            if ($newgold >= 0) {
                $sethair = $MySQLi->query("UPDATE df_characters SET hairid='{$HairID}' WHERE ID='{$CharID}'");
                $setColorHair = $MySQLi->query("UPDATE df_characters SET colorhair='{$ColorHair}' WHERE ID='{$CharID}'");
                $setColorSkin = $MySQLi->query("UPDATE df_characters SET colorskin='{$ColorSkin}' WHERE ID='{$CharID}'");
                $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$CharID}'");
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError("Error!", "Insufficient Funds.");
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