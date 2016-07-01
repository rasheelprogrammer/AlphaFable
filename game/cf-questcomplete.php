<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-questcomplete - v0.0.3
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $gold = $doc->getElementsByTagName('intGold')->item(0)->nodeValue;
    $exp = $doc->getElementsByTagName('intExp')->item(0)->nodeValue;
    $QuestID = $doc->getElementsByTagName('intQuestID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $user_result->num_rows;
    $capQuery = $MySQLi->query("SELECT LevelCap FROM `df_settings` LIMIT 1");
    $caps = $capQuery->fetch_array();

    if ($char_result->num_rows > 0 && $user_result->num_rows > 0) {
        $quest_result = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$QuestID}'");
        $quest = $quest_result->fetch_assoc();

        $gold_total = $char['gold'] + $gold;
        $exptolevel = $Core->calcEXPtoLevel($char['level'], $exp_total);
        $levelCap = $caps[0];

        if ($gold != 0) {
            $addgold = $MySQLi->query("UPDATE df_characters SET gold = '{$gold_total}' WHERE id = '{$charID}'");
        }
        if ($exp != 0) {
            if ($char['level'] < $levelCap) {
                $exp_total = $char['exp'] + $exp;
                $addexp = $MySQLi->query("UPDATE df_characters SET exp = '{$exp_total}' WHERE id = '{$charID}'");
            } else {
                $exp_total = 0;
            }
        } else {
            $exp_total = 0;
        }
        switch ($QuestID) {
            case (54):
                $set_newhome = $MySQLi->query("UPDATE df_characters SET HomeTownID='40' WHERE id = '{$charID}'");
                break;
            case (932):
                $set_newhome = $MySQLi->query("UPDATE df_characters SET HomeTownID='933' WHERE id = '{$charID}'");
                break;
            case (938):
                $set_newhome = $MySQLi->query("UPDATE df_characters SET HomeTownID='935' WHERE id = '{$charID}'");
                break;
        }
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('questreward'));
        $questreward = $XML->appendChild($dom->createElement('questreward'));
        $questreward->setAttribute('intGold', $gold_total);
        $questreward->setAttribute('intExp', $exp_total);
        $questreward->setAttribute('intSilver', 0);
        $questreward->setAttribute('intGems', 0);
        $questreward->setAttribute('intExpToLevel', $exptolevel);
        $questreward->setAttribute('intLevel', $char['level']);

        if ($quest['Rewards'] != '0' && $quest['Rewards'] != NULL && $quest['Rewards'] != 'None') {
            $replaced = str_replace(",", " OR ItemID = ", $quest['Rewards']);
            $rewards = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = " . $replaced . " ORDER BY RAND() LIMIT 1");
            while ($shop = $rewards->fetch_assoc()) {
                $items = $questreward->appendChild($dom->createElement('items'));
                $items->setAttribute('ItemID', $shop['ItemID']);
                $items->setAttribute('CharItemID', $shop['ItemID']);
                $items->setAttribute('strItemName', $shop['ItemName']);
                $items->setAttribute('intCount', 1);
                $items->setAttribute('intHP', $shop['hp']);
                $items->setAttribute('intMaxHP', $shop['hp']);
                $items->setAttribute('intMP', $shop['mp']);
                $items->setAttribute('intMaxMP', $shop['mp']);
                $items->setAttribute('bitEquipped', 0);
                $items->setAttribute('bitDefault', 0);
                $items->setAttribute('intCurrency', $shop['Currency']);
                $items->setAttribute('intCost', $shop['Cost']);
                $items->setAttribute('intHP', 0);
                $items->setAttribute('intLevel', $shop['Level']);
                $items->setAttribute('strItemDescription', $shop['ItemDescription']);
                $items->setAttribute('bitDragonAmulet', $shop['DragonAmulet']);
                $items->setAttribute('strEquipSpot', $shop['EquipSpot']);
                $items->setAttribute('strCategory', $shop['Category']);
                $items->setAttribute('strItemType', $shop['ItemType']);
                $items->setAttribute('strType', $shop['Type']);
                $items->setAttribute('strFileName', $shop['FileName']);
                $items->setAttribute('intMin', $shop['Min']);
                $items->setAttribute('intCrit', $shop['intCrit']);
                $items->setAttribute('intDefMelee', $shop['intDefMelee']);
                $items->setAttribute('intDefPierce', $shop['intDefPierce']);
                $items->setAttribute('intDodge', $shop['intDodge']);
                $items->setAttribute('intParry', $shop['intParry']);
                $items->setAttribute('intDefMagic', $shop['intDefMagic']);
                $items->setAttribute('intBlock', $shop['intBlock']);
                $items->setAttribute('intDefRange', $shop['intDefRange']);
                $items->setAttribute('intMax', $shop['Max']);
                $items->setAttribute('intBonus', $shop['Bonus']);
                $items->setAttribute('strResists', $shop['Resists']);
                $items->setAttribute('strElement', $shop['Element']);
                $items->setAttribute('intRarity', $shop['Rarity']);
                $items->setAttribute('intMaxStackSize', $shop['MaxStackSize']);
                $items->setAttribute('strIcon', $shop['Icon']);
                $items->setAttribute('bitSellable', $shop['Sellable']);
                $items->setAttribute('bitDestroyable', 1);
            }
        }
        $status = $dom->appendChild($dom->createElement('status'));
        $status->setAttribute('status', "SUCCESS");
        echo $dom->saveXML();
    } else {
		//Dump Log
		file_put_contents("logs/Quest Error/Quest {$QuestID} - {$charID} - {$char['userid']}.txt", $result, FILE_APPEND | LOCK_EX);
		$Core->returnXMLError('Error!', "There was a problem updating your character info. Bug Report Sent to Admin.");
    }
} else {
}
$MySQLi->close();
?>