<?php
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragonuntrain - v0.0.3
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    
    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token  = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;
    
    $query  = array();
    $result = array();
    
    $query[0]  = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $result[0] = $query[0]->fetch_assoc();
    
    $query[1]  = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$charID}'");
    $result[1] = $query[1]->fetch_assoc();
    
    $query[2]  = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $result[2] = $query[2]->fetch_assoc();
    
    if ($result[2]->num_rows > 0) {
        if ($result[0]->num_rows > 0) {
            $gold_left = $$result[0]['gold'] - 1000;
            $newstats  = $result[1]['intHeal'] + $result[1]['intMagic'] + $result[1]['intMelee'] + $result[1]['intBuff'] + $result[1]['intDebuff'];
            $MySQLi->query("UPDATE `df_characters` SET `gold` = '{$gold_left}' WHERE `id` = '{$charID}';");
            $MySQLi->query("UPDATE `df_dragons` SET `intTotalStats` = '{$newstats}', `intHeal` = '0', `intMagic` = '0', `intMelee` = '0', `intBuff` = '0', `intDebuff` = '0' WHERE `id` = 1");
            if ($MySQLi->affected_rows > 0) {
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
            }
            echo $dom->saveXML();
        } else {
            $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'User information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();

?>