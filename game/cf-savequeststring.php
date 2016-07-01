<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-savequeststring - v0.0.4
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$HTTP_RAW_POST_DATA = file_get_contents("php://input");
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $index = $doc->getElementsByTagName('intIndex')->item(0)->nodeValue;
    $val = $doc->getElementsByTagName('intValue')->item(0)->nodeValue;
    $newval = $Core->valueCheck($val);
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $result[1] = $query[1]->fetch_assoc();

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $quests = $result[0]['strQuests'];
            $result = array();
            for ($i = 0; $i < strlen($quests); $i += 1) {
                $result[] = substr($quests, $i, 1);
            }
            $split = $result;
            $split[$index] = $newval;
            $stringLength = count($split);
            $i = 0;
            while ($i <= $stringLength) {
                $newqstr = $newqstr . $split[$i];
                $i++;
            }

            $savestring = $MySQLi->query("UPDATE df_characters SET strQuests='" . $newqstr . "' WHERE ID='" . $charID . "'");
            if ($MySQLi->affected_rows > 0) {
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
            }
        } else {
            $Core->returnXMLError('Error!', 'User information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>