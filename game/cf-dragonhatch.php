<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragonhatch - v0.0.2
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $charQuery->fetch_assoc();
    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $userQuery->fetch_assoc();

    if ($userQuery->num_rows > 0 && $charQuery->num_rows > 0) {
        $changevalue = $MySQLi->query("UPDATE df_characters SET HasDragon='1' WHERE ID='{$charID}'");
        $date = date('Y') . "-" . date('j') . "-" . date('d') . "T" . date('H') . ":" . date('i') . ":" . date('s') . "." . date('u');
        $adddragon = $MySQLi->query("INSERT INTO `df_dragons` (`id`, `CharDragID`, dateLastFed) VALUES ('', '{$charID}', '{$date}')");

        if ($char['HasDragon'] == 1) {
            $dragon_check = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$charID}'");
            if ($dragon_check->num_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('character'));
                $character = $XML->appendChild($dom->createElement('character'));
                $drag = $dragon_check->fetch_assoc();
                $dragon = $character->appendChild($dom->createElement('dragon'));
                $dragon->setAttribute('strName', $drag['strName']);
                $dragon->setAttribute('intCrit', $drag['intCrit']);
                $dragon->setAttribute('intMin', $drag['intMin']);
                $dragon->setAttribute('intMax', $drag['intMax']);
                $dragon->setAttribute('strElement', $drag['strElement']);
                $dragon->setAttribute('intPowerBoost', $drag['intPowerBoost']);
                $dragon->setAttribute('dateLastFed', $drag['dateLastFed']);
                $dragon->setAttribute('intTotalStats', $drag['intTotalStats']);
                $dragon->setAttribute('intHeal', $drag['intHeal']);
                $dragon->setAttribute('intMagic', $drag['intMagic']);
                $dragon->setAttribute('intMelee', $drag['intMelee']);
                $dragon->setAttribute('intBuff', $drag['intBuff']);
                $dragon->setAttribute('intDebuff', $drag['intDebuff']);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute('status', "SUCCESS");
                echo $dom->saveXML();
            } else {
                $Core->returnXMLError('Error!', 'Dragon information was unable to be requested.');
            }
        } else {
            $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();