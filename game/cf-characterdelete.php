<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-characterdelete - v0.0.4
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->intCharID) && isset($xml->strToken)) {
        $userID = $xml->intCharID;
        $token = $xml->strToken;

        $query = array();
        $result = array();

        $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$userID}'");
        $result[0] = $query[0]->fetch_assoc();
        $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
        $result[1] = $query[1]->fetch_assoc();

        if ($query[0]->num_rows > 0 && $query[1]->num_rows > 0) {
            $MySQLi->query("DELETE FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
            if ($MySQLi->affected_rows > 0) {
                $Core->returnXMLError("Error!", "There was an issue updating your character information.");
            }
        } else {
            $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Invalid Data!', 'Message');
    }
    echo $dom->saveXML();
    $MySQLi->close();
}
?>