<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-itemmerge - v0.0.2
 */

//TODO: Fix Item stacking and CharItemID

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;


    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");
    $user = $user_result->num_rows;

    if ($user_result->num_rows == 1 && $char_result->num_rows == 1) {
        $intMergeID = $doc->getElementsByTagName('intMergeID');
        $merge_id = $intMergeID->item(0)->nodeValue;

        $merges_result = $MySQLi->query("SELECT * FROM df_merges WHERE ResultID = '{$merge_id}'");
        $merges = $merges_result->fetch_assoc();

        $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$merges['ResultID']}'");
        $item = $item_result->fetch_assoc();

        $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$merges['RequiredID1']}' AND count >= '{$merges['RequiredQTY1']} AND House = 0 AND HouseItem = 0 '");
        $q = $query->fetch_assoc();
        $query2 = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$merges['RequiredID2']}' AND count >= '{$merges['RequiredQTY2']} AND House = 0 AND HouseItem = 0 '");
        $q2 = $query->fetch_assoc();
        $newcount1 = $q['count'] - $merges['RequiredQTY1'];
        $newcount2 = $q2['count'] - $merges['RequiredQTY2'];
        if ($newcount1 >= 0 || $newcount2 >= 0) {
            $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$merges['ResultID']}' AND count > '0' AND House = 0 AND HouseItem = 0");
            $query_rows = $query->num_rows;
            $query_fetched = $query->fetch_assoc();

            if ($item['Currency'] == 2) {
                $newgold = $char['gold'] - $item['Cost'];
                $takegold = $MySQLi->query("UPDATE df_characters SET gold='{$newgold}' WHERE ID='{$charID}'");
            } else if ($item['Currency'] == 1) {
                $newgold = $char['Coins'] - $item['Cost'];
                $takegold = $MySQLi->query("UPDATE df_characters SET Coins='{$newgold}' WHERE ID='{$charID}'");
            }

            if ($newcount1 > 0) {
                $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount1}' WHERE ItemID = '{$merges['RequiredID1']}' AND House = 0 AND HouseItem = 0 AND count >= '{$merges['RequiredQTY1']}' LIMIT 1");
            } else {
                $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = " . $charID . " AND `ItemID` = '{$merges['RequiredID1']}' LIMIT 1");
            }
            if ($newcount2 > 0) {
                $query2 = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount2}' WHERE ItemID = '{$merges['RequiredID2']}' AND House = 0 AND HouseItem = 0 AND count >= '{$merges['RequiredQTY2']}' LIMIT 1");
            } else {
                $removeitem2 = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = " . $charID . " AND `ItemID` = '{$merges['RequiredID2']}' AND House = 0 AND HouseItem = 0 LIMIT 1");
            }
            if ($query_rows == 1 && $item['MaxStackSize'] > 1) {
                $newcount = $query_fetched['count'] + 1;
                $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'AND House = 0 AND HouseItem = 0 ");
            } else {
                $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$charID}', '{$merges['ResultID']}')");
            }
            if ($MySQLi->affected_rows > 0) {
                $dom = new DOMDocument();
                $XML = $dom->appendChild($dom->createElement('Merge'));
                $dragonx = $XML->appendChild($dom->createElement('Merge'));
                $dragonx->setAttribute("CharItemID1", $merges['RequiredID1']);
                $dragonx->setAttribute("CharItemID2", $merges['RequiredID2']);
                $dragonx->setAttribute("Qty1", $merges['RequiredQTY1']);
                $dragonx->setAttribute("Qty2", $merges['RequiredQTY2']);
                $dragonx->setAttribute("NewItem", $merges['ResultID']);
                $status = $XML->appendChild($dom->createElement('status'));
                $status->setAttribute("status", "SUCCESS");
            } else {
                $Core->returnXMLError('Error!', 'There was an updating your character information.');
            }
        } else {
            $Core->returnXMLError('Error!', 'Insufficient Funds.');
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();