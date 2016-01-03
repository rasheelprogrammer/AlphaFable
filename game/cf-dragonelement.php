<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-dragonelement - v0.0.2
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

    $intElement = $doc->getElementsByTagName('intElement');
    $Element = $intElement->item(0)->nodeValue;

    $query = array();
    $result = array();

    $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$CharID}'");
    $result[0] = $query[0]->fetch_assoc();
    $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}'");
    $user = $query[1]->num_rows;

    if ($query[1]->num_rows > 0) {
        if ($query[0]->num_rows > 0) {
            switch ($Element) {
                case 5:
                    $newElement = "Fire";
                    break;
                case 6:
                    $newElement = "Water";
                    break;
                case 7:
                    $newElement = "Ice";
                    break;
                case 8:
                    $newElement = "Wind";
                    break;
                case 9:
                    $newElement = "Energy";
                    break;
                case 10:
                    $newElement = "Light";
                    break;
                case 11:
                    $newElement = "Darkness";
                    break;
                case 18:
                    $newElement = "Nature";
                    break;
            }

            $ChangeElement = $MySQLi->query("UPDATE df_dragons SET strElement='{$newElement}' WHERE CharDragID='{$CharID}'");
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('dragon'));
                $character = $XML->appendChild($dom->createElement('dragon'));
                $character->setAttribute("strElement", $newElement);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute("status", "SUCCESS");
            } else {
                $Game->returnXMLError('Error!', 'There was an updating your character information.');
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