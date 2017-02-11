<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-statstrain - v0.0.2
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $STR = $doc->getElementsByTagName('intSTR')->item(0)->nodeValue;
    $INT = $doc->getElementsByTagName('intINT')->item(0)->nodeValue;
    $DEX = $doc->getElementsByTagName('intDEX')->item(0)->nodeValue;
    $END = $doc->getElementsByTagName('intEND')->item(0)->nodeValue;
    $LUK = $doc->getElementsByTagName('intLUK')->item(0)->nodeValue;
    $CHA = $doc->getElementsByTagName('intCHA')->item(0)->nodeValue;
    $WIS = $doc->getElementsByTagName('intWIS')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        $newSTR = $char['intSTR'] + $STR;
        $newINT = $char['intINT'] + $INT;
        $newDEX = $char['intDEX'] + $DEX;
        $newEND = $char['intEND'] + $END;
        $newLUK = $char['intLUK'] + $LUK;
        $newCHA = $char['intCHA'] + $CHA;
        $newWIS = $char['intWIS'] + $WIS;

        $points_used = $STR + $INT + $DEX + $END + $LUK + $CHA + $WIS;
        $points_left = $char['intStatPoints'] - $points_used;

        if ($points_left >= 0) {
            $cost = $points_used * 5;
            $gold_left = $char['gold'] - $cost;
            if ($gold_left >= 0) {
                $addSTR = $MySQLi->query("UPDATE df_characters SET intSTR='{$newSTR}' WHERE ID='{$charID}'");
                $addINT = $MySQLi->query("UPDATE df_characters SET intINT='{$newINT}' WHERE ID='{$charID}'");
                $addDEX = $MySQLi->query("UPDATE df_characters SET intDEX='{$newDEX}' WHERE ID='{$charID}'");
                $addEND = $MySQLi->query("UPDATE df_characters SET intEND='{$newEND}' WHERE ID='{$charID}'");
                $addLUK = $MySQLi->query("UPDATE df_characters SET intLUK='{$newLUK}' WHERE ID='{$charID}'");
                $addCHA = $MySQLi->query("UPDATE df_characters SET intCHA='{$newCHA}' WHERE ID='{$charID}'");
                $addWIS = $MySQLi->query("UPDATE df_characters SET intWIS='{$newWIS}' WHERE ID='{$charID}'");
                $ChangePoints = $MySQLi->query("UPDATE df_characters SET intStatPoints='{$points_left}' WHERE ID='{$charID}'");
                $ChangeGold = $MySQLi->query("UPDATE df_characters SET gold='{$gold_left}' WHERE ID='{$charID}'");
                if ($MySQLi->affected_rows > 0) {
                    $dom = new DOMDocument();
                    $XML = $dom->appendChild($dom->createElement('character'));
                    $character = $XML->appendChild($dom->createElement('character'));
                    $character->setAttribute("intStatPoints", $points_left);
                    $character->setAttribute("intSTR", $newSTR);
                    $character->setAttribute("intINT", $newINT);
                    $character->setAttribute("intDEX", $newDEX);
                    $character->setAttribute("intEND", $newEND);
                    $character->setAttribute("intLUK", $newLUK);
                    $character->setAttribute("intCHA", $newCHA);
                    $character->setAttribute("intWIS", $newWIS);
                    $status = $XML->appendChild($dom->createElement('status'));
                    $status->setAttribute("status", "SUCCESS");
                } else {
                    $Core->returnXMLError('Error!', 'Could not save stat Information.');
                }
            } else {
                $Core->returnXMLError('Error!', 'Insufficient Gold.');
            }
        } else {
            $Core->returnXMLError('Error!', 'Insufficient Stat Points.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();