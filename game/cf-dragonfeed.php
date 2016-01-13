<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragonfeed - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;
    $foodid = $doc->getElementsByTagName('intFoodID')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows > 0 || $char_result->num_rows > 0) {
        $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$foodid}'");
        $item = $item_result->fetch_assoc();
        $drag_result = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$charID}'");
        $drag = $drag_result->fetch_assoc();
        if ($foodid == 879) {
            //Normal Dragon Chow
            $newstats = $drag['intTotalStats'] + 1;
            $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = " . $charID . " AND `ItemID` = " . $foodid . " AND HouseID != 1 AND HouseItem != 1 LIMIT 1");
        } else if ($foodid == 880) {
            //Special Dragon Chow
            $newstats = $drag['intTotalStats'] + 2;
        } else if ($foodid == 881 || $foodid == 907) {
            //Really Special Dragon Chow
            $newstats = $drag['intTotalStats'] + 5;
        } else if ($foodid == 3456) {
            //Super Special Dragon Chow
            $newstats = $drag['intTotalStats'] + 5;
        }
        date_default_timezone_set('America/Los_Angeles');
        $date = date('Y') . "-" . date('j') . "-" . date('d') . "T" . date('H') . ":" . date('i') . ":" . date('s') . "." . date('u');
        $changedate = $MySQLi->query("UPDATE df_dragons SET dateLastFed='" . $date . "' WHERE CharDragID='" . $charID . "'");
        $addpoints = $MySQLi->query("UPDATE df_dragons SET intTotalStats='" . $newstats . "' WHERE CharDragID='" . $charID . "'");
        $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = " . $charID . " AND `ItemID` = " . $foodid . " AND HouseID != 1 AND HouseItem != 1 LIMIT 1");
        if ($MySQLi->affected_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('dragon'));
            $character = $XML->appendChild($dom->createElement('dragon'));
            $character->setAttribute('dateLastFed', $date);
            $character->setAttribute('intTotalStats', $newstats);
            $status = $XML->appendChild($dom->createElement('status'));
            $status->setAttribute("status", "SUCCESS");
        } else {
            $Core->returnXMLError('Error!', 'There was an updating your character information.');
        }
    } else {
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";
        $Core->returnXMLError("{$reason}", "{$message}");
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>