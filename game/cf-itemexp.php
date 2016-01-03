<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemexp - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $intCharItemID = $doc->getElementsByTagName('intCharItemID')->item(0)->nodeValue;
    $intExp = $doc->getElementsByTagName('intExp')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}' LIMIT 1");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = ' {$char['userid']}' AND LoginToken = ' {$token}' LIMIT 1");
    $user = $user_result->num_rows;

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        $item_result = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$charID}' AND CharItemID = '{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
        $item = $item_result->fetch_assoc();

        $exp_total = $item['Exp'] + $exp;
        $exptolevel = $item['Level'] * 20 * $item['Level'];
        $intLevel = $item['Level'] + 1;

        if ($exp_total == $exptolevel) {
            $exp_total2 = 0;
            $levelup = $MySQLi->query("UPDATE df_equipment SET Level='{$intLevel}' WHERE CharID='{$charID}' AND CharItemID='{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
            $addexp = $MySQLi->query("UPDATE df_equipment SET Exp='{$exp_total2}' WHERE CharID='{$charID}' AND CharItemID='{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
        } else if ($exp_total > $exptolevel) {
            $exp_total2 = $exp_total - $exptolevel;
            $levelup = $MySQLi->query("UPDATE df_equipment SET Level = '{$intLevel}' WHERE CharID = '{$charID}' AND CharItemID = '{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
            $addexp = $MySQLi->query("UPDATE df_equipment SET Exp = '{$exp_total2}' WHERE CharID = '{$charID}' AND CharItemID = '{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
        } else {
            $addexp = $MySQLi->query("UPDATE df_equipment SET Exp = '{$exp_total}' WHERE CharID = '{$charID}' AND CharItemID = '{$item_id}' AND HouseID = 0 AND HouseItem = 0 LIMIT 1");
        }
        if ($MySQLi->affected_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('CharItem'));
            $CharItem = $XML->appendChild($dom->createElement('CharItem'));
            $CharItem->setAttribute("intCharLevel", $item['Level']);
            $CharItem->setAttribute("intExp", $item['Exp']);
            $CharItem->setAttribute("intExpToLevel", $exptolevel2);
            $status = $XML->appendChild($dom->createElement('status'));
            $status->setAttribute("status", "SUCCESS");
        } else {
            $Game->returnXMLError('Error!', 'There was an updating your character information.');
        }
    } else {
        $Game->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>