<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-changearmor - v0.0.2
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

    $intColorBase = $doc->getElementsByTagName('intColorBase');
    $ColorBase = $intColorBase->item(0)->nodeValue;

    $intColorTrim = $doc->getElementsByTagName('intColorTrim');
    $ColorTrim = $intColorTrim->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $user = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $cost = 100;
            $gold_left = $result[0]['gold'] - $cost;
            if ($gold_left >= 0) {
                $ChangeColors = $MySQLi->query("UPDATE df_characters SET colorbase = '{$ColorBase}', colortrim = '{$ColorTrim}' WHERE id = '{$CharID}'");
                $ChangeGold = $MySQLi->query("UPDATE df_characters SET gold='{$gold_left}' WHERE ID='{$CharID}'");
                if ($MySQLi->affected_rows > 0) {
                    $dom = new DOMDocument();
                    $XML = $dom->appendChild($dom->createElement('character'));
                    $character = $XML->appendChild($dom->createElement('character'));
                    $character->setAttribute("intColorBase", $result[0]['colorbase']);
                    $character->setAttribute("intColorTrim", $result[0]['colortrim']);
                    $status = $XML->appendChild($dom->createElement('status'));
                    $status->setAttribute("status", "SUCCESS");
                } else {
                    $reason = "Error!";
                    $message = "There was an issue updating your character information.";
                    $Game->returnXMLError("{$reason}", "{$message}");
                }
            } else {
                $reason = "Error!";
                $message = "Insufficient funds.";
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