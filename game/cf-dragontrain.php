<?php #FILE NEEDS REDO
/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragontrain - v0.0.2
 */

include("../includes/classes/GameFunctions.class.php");
include('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    
    $drag_id = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token   = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;
    $Heal    = $doc->getElementsByTagName('intHeal')->item(0)->nodeValue;
    $Magic   = $doc->getElementsByTagName('intMagic')->item(0)->nodeValue;
    $Melee   = $doc->getElementsByTagName('intMelee')->item(0)->nodeValue;
    $Buff    = $doc->getElementsByTagName('intBuff')->item(0)->nodeValue;
    $Debuff  = $doc->getElementsByTagName('intDebuff')->item(0)->nodeValue;
    
    $query  = array();
    $result = array();
    
    $query[0]  = $MySQLi->query("SELECT * FROM df_characters WHERE ID = '{$drag_id}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1]  = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    
    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $drag_result = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$drag_id}'");
            $drag        = $drag_result->fetch_assoc();
            
            $total = $Heal + $Magic + $Melee + $Buff + $Debuff;
            if ($drag['intTotalStats'] - $total >= 0) {
                $addHeal   = $MySQLi->query("UPDATE df_dragons SET intHeal='{$Heal}' WHERE CharDragID='{$drag_id}'");
                $addMelee  = $MySQLi->query("UPDATE df_dragons SET intMelee='{$Melee}' WHERE CharDragID='{$drag_id}'");
                $addMagic  = $MySQLi->query("UPDATE df_dragons SET intMagic='{$Magic}' WHERE CharDragID='{$drag_id}'");
                $addBuff   = $MySQLi->query("UPDATE df_dragons SET intBuff='{$Buff}' WHERE CharDragID='{$drag_id}'");
                $addDebuff = $MySQLi->query("UPDATE df_dragons SET intDebuff='{$Debuff}' WHERE CharDragID='{$drag_id}'");
                $dom       = new DOMDocument();
                $XML       = $dom->appendChild($dom->createElement('dragon'));
                $dragonx   = $XML->appendChild($dom->createElement('dragon'));
                $dragonx->setAttribute("intHeal", $Heal);
                $dragonx->setAttribute("intMagic", $Magic);
                $dragonx->setAttribute("intMelee", $Melee);
                $dragonx->setAttribute("intBuff", $Buff);
                $dragonx->setAttribute("intDebuff", $Debuff);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute("status", "SUCCESS");
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
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();

?>