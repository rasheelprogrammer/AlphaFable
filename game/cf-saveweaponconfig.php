<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-saveweaponconfig - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ("../includes/classes/Security.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $CharID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $Items = $doc->getElementsByTagName('strItems')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $result[1] = $query[1]->fetch_assoc();

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $Items = explode(",", $Items);
            $CanPlay = $Security->checkAccessLevel($result[1]['access'], 5);
            switch ($CanPlay) {
                case ("Banned"):
                    $Core->returnXMLError('Banned!', 'You have been <b>banned</b> from <b>AlphaFable</b>. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
                    break;
                case ("Invalid"):
                    $Core->returnXMLError('Invalid Rank!', 'Sorry, this action is currently unavailable for your account. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
                    break;
                case ("OK"):
                    $query = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '0' WHERE `CharID` = {$CharID} AND `StartingItem` = '1' AND House = 0 AND HouseItem = 0");
                    for ($a = 0; $a < count($Items); $a++) {
                        $query2 = $MySQLi->query("UPDATE `df_equipment` SET `StartingItem` = '1' WHERE `ItemID` = {$Items[$a]} AND House = 0 AND HouseItem = 0");
                    }
                    $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                    break;
                default:
                    break;
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
