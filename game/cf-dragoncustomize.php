<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dragoncustomize - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $dragID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $Name = $doc->getElementsByTagName('strName')->item(0)->nodeValue;
    $ColorSkin = $doc->getElementsByTagName('intColorSkin')->item(0)->nodeValue;
    $ColorWing = $doc->getElementsByTagName('intColorWing')->item(0)->nodeValue;
    $ColorEye = $doc->getElementsByTagName('intColorEye')->item(0)->nodeValue;
    $ColorHorn = $doc->getElementsByTagName('intColorHorn')->item(0)->nodeValue;
    $Wings = $doc->getElementsByTagName('intWings')->item(0)->nodeValue;
    $Heads = $doc->getElementsByTagName('intHeads')->item(0)->nodeValue;
    $Tails = $doc->getElementsByTagName('intTails')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE ID = '{$dragID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        $drag_result = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$dragID}'");
        $drag = $drag_result->fetch_assoc();

        $change_Name = $MySQLi->query("UPDATE df_dragons SET strName='{$Name}' WHERE CharDragID='{$dragID}'");
        $change_intColorSkin = $MySQLi->query("UPDATE df_dragons SET intColorSkin='{$ColorSkin}' WHERE CharDragID='{$dragID}'");
        $change_intColorWing = $MySQLi->query("UPDATE df_dragons SET intColorWing='{$ColorWing}' WHERE CharDragID='{$dragID}'");
        $change_intColorEye = $MySQLi->query("UPDATE df_dragons SET intColorEye='{$ColorEye}' WHERE CharDragID='{$dragID}'");
        $change_intColorHorn = $MySQLi->query("UPDATE df_dragons SET intColorHorn='{$ColorHorn}' WHERE CharDragID='{$dragID}'");
        $change_intWings = $MySQLi->query("UPDATE df_dragons SET intWings='{$Wings}' WHERE CharDragID='{$dragID}'");
        $change_intHeads = $MySQLi->query("UPDATE df_dragons SET intHeads='{$Heads}' WHERE CharDragID='{$dragID}'");
        $change_intTails = die("UPDATE df_dragons SET intTails='{$Tails}' WHERE CharDragID='{$dragID}'");

        if ($MySQLi->affected_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('dragon'));
            $dragon = $XML->appendChild($dom->createElement('dragon'));
            $dragon->setAttribute('strName', $Name);
            $dragon->setAttribute('intColorSkin', $ColorSkin);
            $dragon->setAttribute('intColorEye', $ColorEye);
            $dragon->setAttribute('intColorHorn', $ColorHorn);
            $dragon->setAttribute('intColorWing', $ColorWing);
            $dragon->setAttribute('intWings', $Wings);
            $dragon->setAttribute('intHeads', $Heads);
            $dragon->setAttribute('intTails', $Tails);
            $character = $XML->appendChild($dom->createElement('status'));
            $character->setAttribute("status", "SUCCESS");
            echo $dom->saveXML();
        } else {
            $Core->returnXMLError('Error!', 'There was an updating your character information.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
