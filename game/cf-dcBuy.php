<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-dcBuy - v0.0.2
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

    $BuyID = $doc->getElementsByTagName('intBuyID')->item(0)->nodeValue;

    $Command = $doc->getElementsByTagName('strCommand')->item(0)->nodeValue;

    $Action = $doc->getElementsByTagName('intAction')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");
    $user = $user_result->num_rows;

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        if ($BuyID == 0) {
            if ($char['Coins'] >= 1000) {
                $newcoins = $char['Coins'] - 1000;
                if ($Command == "M") {
                    $ChangeGender = $MySQLi->query("UPDATE df_characters SET gender='M' WHERE ID='{$charID}'");
                    $ChangeHair = $MySQLi->query("UPDATE df_characters SET hairid='3' WHERE ID='{$charID}'");
                    $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
                } else if ($Command == "F") {
                    $ChangeGender = $MySQLi->query("UPDATE df_characters SET gender='F' WHERE ID='{$charID}'");
                    $ChangeHair = $MySQLi->query("UPDATE df_characters SET hairid='3' WHERE ID='{$charID}'");
                    $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else if ($BuyID == 1) {
            if ($char['Coins'] >= 1000) {
                $newcoins = $char['Coins'] - 1000;
                $ChangeGender = $MySQLi->query("UPDATE df_characters SET name='{$Command}' WHERE ID='{$charID}'");
                $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
            } else {
                $error = 1;
            }
        } else if ($BuyID == 2) {
            if ($char['Coins'] >= 500) {
                $newcoins = $char['Coins'] - 1000;
                if ($Action == "2") {
                    $ChangeClass = $MySQLi->query("UPDATE df_characters SET classid='{$Action}' WHERE ID='{$charID}'");
                    $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
                } else if ($Action == "3") {
                    $ChangeClass = $MySQLi->query("UPDATE df_characters SET classid='{$Action}' WHERE ID='{$charID}'");
                    $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
                } else if ($Action == "4") {
                    $ChangeClass = $MySQLi->query("UPDATE df_characters SET classid='{$Action}' WHERE ID='{$charID}'");
                    $ChangeCoins = $MySQLi->query("UPDATE df_characters SET Coins='{$newcoins}' WHERE ID='{$charID}'");
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else {
            $reason = "Error!";
            $message = "Cannot Process Transaction.";
            $Core->returnXMLError("{$reason}", "{$message}");
        }

        if ($error == 1) {
            $reason = "Error!";
            $message = "Cannot Process Transaction.";
            $Core->returnXMLError("{$reason}", "{$message}");
        } else {
            if ($MySQLi->affected_rows > 0) {
                $Core->returnCustomXMLMessage("status", "status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
            }
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();