<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-characterload - v0.0.7
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $item_id = $doc->getElementsByTagName('intNewItemID')->item(0)->nodeValue;
    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $charQuery->fetch_assoc();

    $userQuery = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}' LIMIT 1");
    $user = $userQuery->fetch_assoc();

    if ($userQuery->num_rows > 0 && $charQuery->num_rows > 0) {
        $item_result = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$item_id}'");
        $item = $item_result->fetch_array();
        $inv_result = $MySQLi->query("SELECT * FROM df_equipment WHERE ItemID = '{$item_id}' AND House = 0 AND HouseItem = 0 ");
        $inv = $inv_result->fetch_array();
        if ($inv_result->num_rows > 0) {
            $count = $inv['count'] + 1;
            $additem = $MySQLi->query("UPDATE `df_equipment` SET `count` =  '{$count}' WHERE `ItemID` = '{$item_id}' AND House = 0 AND HouseItem = 0 ;");
        } else {
            $additem = $MySQLi->query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '{$charID}', '{$item['ItemID']}')");
        }
        if ($MySQLi->affected_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('CharItemID'));
            $items = $XML->appendChild($dom->createElement('CharItemID'));
            $items->setAttribute('ItemID', $item['ItemID']);
            $items->setAttribute('CharItemID', $item['ItemID']);
            $items->setAttribute('strItemName', $item['ItemName']);
            $items->setAttribute('intCount', $inv['count']);
            $items->setAttribute('intHP', $item['hp']);
            $items->setAttribute('intMaxHP', $item['hp']);
            $items->setAttribute('intMP', $item['mp']);
            $items->setAttribute('intMaxMP', $item['mp']);
            $items->setAttribute('bitEquipped', $inv['StartingItem']);
            $items->setAttribute('bitDefault', $inv['StartingItem']);
            $items->setAttribute('intCurrency', $item['Currency']);
            $items->setAttribute('intCost', $item['Cost']);
            $items->setAttribute('intLevel', $item['Level']);
            $items->setAttribute('strItemDescription', $item['ItemDescription']);
            $items->setAttribute('bitDragonAmulet', $item['DragonAmulet']);
            $items->setAttribute('strEquipSpot', $item['EquipSpot']);
            $items->setAttribute('strCategory', $item['Category']);
            $items->setAttribute('strItemType', $item['ItemType']);
            $items->setAttribute('strType', $item['Type']);
            $items->setAttribute('strFileName', $item['FileName']);
            $items->setAttribute('intMin', $item['Min']);
            $items->setAttribute('intCrit', $item['intCrit']);
            $items->setAttribute('intDefMelee', $item['intDefMelee']);
            $items->setAttribute('intDefPierce', $item['intDefPierce']);
            $items->setAttribute('intDodge', $item['intDodge']);
            $items->setAttribute('intParry', $item['intParry']);
            $items->setAttribute('intDefMagic', $item['intDefMagic']);
            $items->setAttribute('intBlock', $item['intBlock']);
            $items->setAttribute('intDefRange', $item['intDefRange']);
            $items->setAttribute('intMax', $item['Max']);
            $items->setAttribute('intBonus', $item['Bonus']);
            $items->setAttribute('strResists', $item['Resists']);
            $items->setAttribute('strElement', $item['Element']);
            $items->setAttribute('intRarity', $item['Rarity']);
            $items->setAttribute('intMaxStackSize', $item['MaxStackSize']);
            $items->setAttribute('strIcon', $item['Icon']);
            $items->setAttribute('bitSellable', $item['Sellable']);
            $items->setAttribute('bitDestroyable', $item['Destroyable']);
            $items->setAttribute('intStr', $item['intStr']);
            $items->setAttribute('intDex', $item['intDex']);
            $items->setAttribute('intInt', $item['intInt']);
            $items->setAttribute('intLuk', $item['intLuk']);
            $items->setAttribute('intCha', $item['intCha']);
            $items->setAttribute('intEnd', $item['intEnd']);
            $items->setAttribute('intWis', $item['intWis']);
        } else {
            $Core->returnXMLError('Error!', 'There was an updating your character information.');
        }
    } else {
        $reason = "Error!";
        $message = "There was a problem with your character.";
        $Core->returnXMLError("{$reason}", "{$message}");
    }
    echo $dom->saveXML();
} else {
    $reason = "Error!";
    $message = "Invalid Data.";
    $Core->returnXMLError("{$reason}", "{$message}");
}
$MySQLi->close();
?>