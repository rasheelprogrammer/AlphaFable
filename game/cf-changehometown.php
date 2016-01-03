<?php #FILE NEEDS REDO
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-changehometown - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $CharID = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $intTownID = $doc->getElementsByTagName('intTownID');
    $TownID = $intTownID->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $query = $MySQLi->query("SELECT * FROM `df_quests` WHERE QuestID = '{$TownID}' LIMIT 1");
            $db_town = $query->fetch_assoc();
            $addexp = $MySQLi->query("UPDATE df_characters SET HomeTownID='{$TownID}' WHERE ID='{$CharID}'");
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('newTown'));
                $character = $XML->appendChild($dom->createElement('newTown'));
                $character->setAttribute('intTownID', $TownID);
                $character->setAttribute('strQuestFileName', $db_town['FileName']);
                $character->setAttribute('strQuestXFileName', $db_town['XFileName']);
                $character->setAttribute('strExtra', $db_town['Extra']);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute("status", "SUCCESS");
            } else {
                $reason = "Error!";
                $message = "There was an issue updating your character information.";
                $Game->returnXMLError("{$reason}", "{$message}");
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
