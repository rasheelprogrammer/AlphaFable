<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-statsuntrain - v0.0.3
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    $query = [];
    $result = [];

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $result[0] = $query[0]->fetch_assoc();

    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $userQuery->fetch_assoc();

    if ($userQuery->num_rows > 0 && $query[0]->num_rows > 0) {
        $statlevel = $result[0]['level'] - 1;
        $gold_left = $result[0]['gold'] - 1000;
        $statpoints = 5 * $statlevel;
        $MySQLi->query("UPDATE `df_characters` SET `gold` = '{$gold_left}', `intSTR` = '0', `intINT` = '0', `intDEX` = '0', `intEND` = '0', `intLUK` = '0', `intCHA` = '0', `intWIS` = '0', `intStatPoints` = '{$statpoints}' WHERE `id` = '{$charID}';");
        if ($MySQLi->affected_rows > 0) {
            $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
        } else {
            $Core->returnXMLError('Error!', 'Could not save stat information.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>