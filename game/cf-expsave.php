<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-expsave - v0.0.4
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $gold = $doc->getElementsByTagName('intGold')->item(0)->nodeValue;
    $exp = $doc->getElementsByTagName('intExp')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $charQuery->fetch_assoc();

    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $userQuery->fetch_assoc();

    $capQuery = $MySQLi->query("SELECT LevelCap  FROM `df_settings` LIMIT 1");
    $caps = $capQuery->fetch_array();

    if ($userQuery->num_rows > 0 && $charQuery->num_rows > 0) {
        $gold_total = $char['gold'] + $gold;
        $exp_total = $char['exp'] + $exp;
        $exptolevel = $Core->calcEXPtoLevel($char['level']);

        $levelCap = $caps[0];
        if ($char['level'] >= $levelCap) {
            $intLevel = $char['level'];
        } else {
            $intLevel = $char['level'] + 1;
            $intHP = $char['hp'] + 20;
            $intMP = $char['mp'] + 5;
        }
        $intStatPoints = $char['intStatPoints'] + 5;
        $addgold = $MySQLi->query("UPDATE df_characters SET gold='{$gold_total}' WHERE ID='{$charID}'");
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('questreward'));
        $questreward = $XML->appendChild($dom->createElement('questreward'));
        if ($exp_total >= $exptolevel) {
            if ($intLevel < $levelCap) {
                $exptolevel = $Core->calcEXPtoLevel($intLevel);
                $questreward->setAttribute("intEarnedExp", 0);
                $questreward->setAttribute("intEarnedGold", $gold);
                $questreward->setAttribute("intEarnedGems", 0);
                $questreward->setAttribute("intEarnedSilver", 0);
                $questreward->setAttribute("intLevel", $intLevel);
                $questreward->setAttribute("intGold", $gold_total);
                $questreward->setAttribute("intHP", $intHP);
                $questreward->setAttribute("intMP", $intMP);
                $questreward->setAttribute("intExp", 0);
                $questreward->setAttribute("intSilver", 0);
                $questreward->setAttribute("intGems", 0);
                $questreward->setAttribute("intExpToLevel", $exptolevel);

                $addexp = $MySQLi->query("UPDATE df_characters SET exp='0' WHERE ID='{$charID}'");
                $addexp = $MySQLi->query("UPDATE df_characters SET gold='{$gold_total}' WHERE ID='{$charID}'");
                $addstatpoints = $MySQLi->query("UPDATE df_characters SET intStatPoints='{$intStatPoints}' WHERE ID='{$charID}'");
                $addhp = $MySQLi->query("UPDATE df_characters SET hp='{$intHP}' WHERE ID='{$charID}'");
                $addmp = $MySQLi->query("UPDATE df_characters SET mp='{$intMP}' WHERE ID='{$charID}'");
                $levelup = $MySQLi->query("UPDATE df_characters SET level='{$intLevel}' WHERE ID='{$charID}'");
            } else {
                $questreward->setAttribute("intEarnedExp", 0);
                $questreward->setAttribute("intEarnedGold", $gold);
                $questreward->setAttribute("intEarnedGems", 0);
                $questreward->setAttribute("intEarnedSilver", 0);
                $questreward->setAttribute("intLevel", $intLevel);
                $questreward->setAttribute("intGold", $gold_total);
                $questreward->setAttribute("intHP", $intHP);
                $questreward->setAttribute("intMP", $intMP);
                $questreward->setAttribute("intExp", 0);
                $questreward->setAttribute("intSilver", 0);
                $questreward->setAttribute("intGems", 0);
                $questreward->setAttribute("intExpToLevel", $exptolevel);

                $addexp = $MySQLi->query("UPDATE df_characters SET exp='0' WHERE ID='{$charID}'");
                $addexp = $MySQLi->query("UPDATE df_characters SET gold='{$gold_total}' WHERE ID='{$charID}'");
            }
        } else {
            $questreward->setAttribute("intEarnedExp", $exp);
            $questreward->setAttribute("intEarnedGold", $gold);
            $questreward->setAttribute("intEarnedGems", 0);
            $questreward->setAttribute("intEarnedSilver", 0);
            $questreward->setAttribute("intLevel", $char['level']);
            $questreward->setAttribute("intGold", $gold_total);
            $questreward->setAttribute("intHP", $intHP);
            $questreward->setAttribute("intMP", $intMP);
            $questreward->setAttribute("intExp", $exp_total);
            $questreward->setAttribute("intSilver", 0);
            $questreward->setAttribute("intGems", 0);
            $questreward->setAttribute("intExpToLevel", $exptolevel);
            $addexp = $MySQLi->query("UPDATE df_characters SET exp='{$exp_total}' WHERE ID='{$charID}'");
            $addexp = $MySQLi->query("UPDATE df_characters SET gold='{$gold_total}' WHERE ID='{$charID}'");
        }
        $status = $XML->appendChild($dom->createElement('status'));
        $status->setAttribute("status", "SUCCESS");
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();