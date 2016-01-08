<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-toCharFromBank - v0.0.2
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->intCharID) && isset($xml->intCharItemID) && isset($xml->strToken)) {
        $charID = $xml->intCharID;
        $token = $xml->strToken;
        $CharItemID = $xml->intCharItemID;

        $query = array();
        $result = array();

        $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}' LIMIT 1");
        $result[0] = $query[0]->fetch_assoc();

        if ($query[0]->num_rows != 0) {
            $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
            $result[1] = $query[1]->fetch_assoc();

            if ($query[1]->num_rows != 0) {
                $query[2] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$CharItemID}'");
                $result[2] = $query[2]->fetch_assoc();

                if ($query[2]->num_rows != 0) {
                    $query[3] = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$charID}', '{$CharItemID}')");
                    if ($MySQLi->affected_rows > 0) {
                        $query[4] = $MySQLi->query("DELETE FROM `df_bank` WHERE `CharID` = " . $charID . " AND `ItemID` = " . $CharItemID . " LIMIT 1");

                        if ($MySQLi->affected_rows > 0) {
                            $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
                        } else {
                            $Core->returnXMLError("Error!", "Could not update bank information");
                        }
                    } else {
                        $Core->returnXMLError("Error!", "There was an error transferring your item");
                    }
                } else {
                    $Core->returnXMLError('Error!', 'Item does not exist.');
                }
            } else {
                $Core->returnXMLError('User Not Found', 'Character information was unable to be requested.');
            }
        } else {
            $Core->returnXMLError('Character Not Found', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>