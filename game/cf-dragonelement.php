<?php
/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-dragonelement - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $CharID = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $intElement = $doc->getElementsByTagName('intElement');
    $element = $Core->elementCheck($intElement->item(0)->nodeValue);

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $user = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            $MySQLi->query("UPDATE df_dragons SET strElement='{$element}' WHERE CharDragID='{$CharID}'");
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('dragon'));
                $character = $XML->appendChild($dom->createElement('dragon'));
                $character->setAttribute("strElement", $element);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute("status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
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