<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-questmerge - v0.0.1
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
    $mergeID = $doc->getElementsByTagName('intQuestMergeID')->item(0)->nodeValue;

    $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $charQuery->fetch_assoc();
    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $mergeQuery = $MySQLi->query("SELECT * FROM `df_quest_merge` WHERE mergeID = '{$mergeID}' LIMIT 1");
    $merge = $mergeQuery->fetch_assoc();
    $query = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$merge['itemID']}' AND HouseID = 0 AND HouseItem = 0 AND count >= '{$merge['itemQty']}'");
    $query_fetched = $query->fetch_assoc();

    if ($userQuery->num_rows > 0 && $charQuery->num_rows > 0 && $mergeQuery->num_rows > 0) {
        $index = $merge['index'];
        $newval = $Core->valueCheck($merge['value']);
        if ($merge['string'] == 0) {
            $quests = $char['strQuests'];
            $result = array();
            for ($i = 0; $i < strlen($quests); $i += 1) {
                $result[] = substr($quests, $i, 1);
            }
            $split = $result;
            $split[$index] = $newval;
            $stringLength = count($split);
            $i = 0;
            while ($i <= $stringLength) {
                $newqstr = $newqstr . $split[$i];
                $i++;
            }
            $savestring = $MySQLi->query("UPDATE df_characters SET strQuests='{$newqstr}' WHERE ID='{$charID}'");
        } else if ($merge['string'] == 1) {
            $quests = $char['strSkills'];
            $result = array();
            for ($i = 0; $i < strlen($quests); $i += 1) {
                $result[] = substr($quests, $i, 1);
            }
            $split = $result;
            $split[$index] = $newval;
            $stringLength = count($split);
            $i = 0;
            while ($i <= $stringLength) {
                $newqstr = $newqstr . $split[$i];
                $i++;
            }

            $savestring = $MySQLi->query("UPDATE df_characters SET strSkills='{$newqstr}' WHERE ID='{$charID}'");
        } else if ($merge['string'] == 2) {
            $quests = $char['strArmor'];
            $result = array();
            for ($i = 0; $i < strlen($quests); $i += 1) {
                $result[] = substr($quests, $i, 1);
            }
            $split = $result;
            $split[$index] = $newval;
            $stringLength = count($split);
            $i = 0;
            while ($i <= $stringLength) {
                $newqstr = $newqstr . $split[$i];
                $i++;
            }

            $savestring = $MySQLi->query("UPDATE df_characters SET strArmor='{$newqstr}' WHERE ID='{$charID}'");
        } else {
            $Core->returnXMLError('Error!', 'Invalid Data Given.');
        }
        if ($query->num_rows >= 1) {
            $newcount = $query_fetched['count'] - $merge['itemQty'];
            if ($newcount > 0) {
                $query = $MySQLi->query("UPDATE df_equipment SET count = '{$newcount}' WHERE id = '{$query_fetched['id']}'");
            } else {
                $removeitem = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = {$charID} AND HouseID = 0 AND HouseItem = 0 AND `ItemID` = {$merge['itemID']} LIMIT 1");
            }
        }
        if ($MySQLi->affected_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('QuestMerge'));
            $quest = $XML->appendChild($dom->createElement('QuestMerge'));
            $quest->setAttribute("intString", $merge['string']);
            $quest->setAttribute("intIndex", $index);
            $quest->setAttribute("intValue", $newval);
            $quest->setAttribute("CharItemID", $merge['itemID']);
            $quest->setAttribute("intQty", $merge['itemQty']);
        } else {
            $Core->returnXMLError('Error!', 'There was an updating your character information.' . $mergeID);
        }
    } else {
        $Core->returnXMLError('Error!', 'Character information was unable to be requested.' . $mergeID);
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>