<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragontrain - v0.0.2
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $DragID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;
    $Heal = $doc->getElementsByTagName('intHeal')->item(0)->nodeValue;
    $Magic = $doc->getElementsByTagName('intMagic')->item(0)->nodeValue;
    $Melee = $doc->getElementsByTagName('intMelee')->item(0)->nodeValue;
    $Buff = $doc->getElementsByTagName('intBuff')->item(0)->nodeValue;
    $Debuff = $doc->getElementsByTagName('intDebuff')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE ID = '{$DragID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $query[2] = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$DragID}'");
            $result[2] = $query[2]->fetch_assoc();

            $total = $Heal + $Magic + $Melee + $Buff + $Debuff;
            if ($result[2]['intTotalStats'] - $total >= 0) {
                $MySQLi->query("UPDATE df_dragons SET intHeal='{$Heal}' WHERE CharDragID='{$DragID}'");
                $MySQLi->query("UPDATE df_dragons SET intMelee='{$Melee}' WHERE CharDragID='{$DragID}'");
                $MySQLi->query("UPDATE df_dragons SET intMagic='{$Magic}' WHERE CharDragID='{$DragID}'");
                $MySQLi->query("UPDATE df_dragons SET intBuff='{$Buff}' WHERE CharDragID='{$DragID}'");
                $MySQLi->query("UPDATE df_dragons SET intDebuff='{$Debuff}' WHERE CharDragID='{$DragID}'");
                if ($MySQLi->affected_rows > 0) {
                    $dom = new DOMDocument();
                    $XML = $dom->appendChild($dom->createElement('dragon'));
                    $dragonx = $XML->appendChild($dom->createElement('dragon'));
                    $dragonx->setAttribute("intHeal", $Heal);
                    $dragonx->setAttribute("intMagic", $Magic);
                    $dragonx->setAttribute("intMelee", $Melee);
                    $dragonx->setAttribute("intBuff", $Buff);
                    $dragonx->setAttribute("intDebuff", $Debuff);
                    $status = $XML->appendChild($dom->createElement('status'));
                    $status->setAttribute("status", "SUCCESS");
                } else {
                    $Core->returnXMLError("Error!", "There was an issue updating your dragon information.");
                }
            } else {
                $Core->returnXMLError('Error!', 'There was a problem updating your dragon information');
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