<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-mergeshopload - v0.0.1
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $shopID = $doc->getElementsByTagName('intMergeShopID')->item(0)->nodeValue;

    $vendor_result = $MySQLi->query("SELECT * FROM df_merge_vendors WHERE ShopID = '{$shopID}'");
    $vendor = $vendor_result->fetch_assoc();

    if ($vender_result->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('mergeshop'));
        $character = $XML->appendChild($dom->createElement('mergeshop'));

        $character->setAttribute('strName', $vendor['ShopName']);
        $character->setAttribute('MSID', $shop_id);
        if ($vendor['ItemIDs'] != NULL && $vendor['ItemIDs'] != "None" && $vendor['ItemIDs'] != '0') {
            $replaced = str_replace(",", " OR ItemID = ", $vendor['ItemIDs']);
            $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = {$replaced}");
            if ($items->num_rows >= 1) {
                while ($item = $items->fetch_assoc()) {
                    $merges_result = $MySQLi->query("SELECT * FROM df_merges WHERE ResultID = '{$item['ItemID']}'");
                    $merges = $merges_result->fetch_assoc();
                    $item_needed1 = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$merges['RequiredID1']}'");
                    $item_needed2 = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$merges['RequiredID2']}'");
                    $item1 = $item_needed1->fetch_assoc();
                    $item2 = $item_needed2->fetch_assoc();

                    $itemsx = $character->appendChild($dom->createElement('items'));
                    $itemsx->setAttribute('ItemID', $item['ItemID']);
                    $itemsx->setAttribute('ID', $item['ItemID']);
                    $itemsx->setAttribute('ItemID1', $merges['RequiredID1']);
                    $itemsx->setAttribute('Item1', $item1['ItemName']);
                    $itemsx->setAttribute('Qty1', $merges['RequiredQTY1']);
                    $itemsx->setAttribute('ItemID2', $merges['RequiredID2']);
                    $itemsx->setAttribute('Item2', $item2['ItemName']);
                    $itemsx->setAttribute('Qty2', $merges['RequiredQTY2']);
                    $itemsx->setAttribute('strItemName', $item['ItemName']);
                    $itemsx->setAttribute('intCount', $inv['count']);
                    $itemsx->setAttribute('intHP', $item['hp']);
                    $itemsx->setAttribute('intMaxHP', $item['hp']);
                    $itemsx->setAttribute('intMP', $item['mp']);
                    $itemsx->setAttribute('intMaxMP', $item['mp']);
                    $itemsx->setAttribute('bitEquipped', $inv['StartingItem']);
                    $itemsx->setAttribute('bitDefault', $inv['StartingItem']);
                    $itemsx->setAttribute('intCurrency', $item['Currency']);
                    $itemsx->setAttribute('intCost', $item['Cost']);
                    $itemsx->setAttribute('intLevel', $item['Level']);
                    $itemsx->setAttribute('strItemDescription', $item['ItemDescription']);
                    $itemsx->setAttribute('bitDragonAmulet', $item['DragonAmulet']);
                    $itemsx->setAttribute('strEquipSpot', $item['EquipSpot']);
                    $itemsx->setAttribute('strCategory', $item['Category']);
                    $itemsx->setAttribute('strItemType', $item['ItemType']);
                    $itemsx->setAttribute('strType', $item['Type']);
                    $itemsx->setAttribute('strFileName', $item['FileName']);
                    $itemsx->setAttribute('intMin', $item['Min']);
                    $itemsx->setAttribute('intCrit', $item['intCrit']);
                    $itemsx->setAttribute('intDefMelee', $item['intDefMelee']);
                    $itemsx->setAttribute('intDefPierce', $item['intDefPierce']);
                    $itemsx->setAttribute('intDodge', $item['intDodge']);
                    $itemsx->setAttribute('intParry', $item['intParry']);
                    $itemsx->setAttribute('intDefMagic', $item['intDefMagic']);
                    $itemsx->setAttribute('intBlock', $item['intBlock']);
                    $itemsx->setAttribute('intDefRange', $item['intDefRange']);
                    $itemsx->setAttribute('intMax', $item['Max']);
                    $itemsx->setAttribute('intBonus', $item['Bonus']);
                    $itemsx->setAttribute('strResists', $item['Resists']);
                    $itemsx->setAttribute('strElement', $item['Element']);
                    $itemsx->setAttribute('intRarity', $item['Rarity']);
                    $itemsx->setAttribute('intMaxStackSize', $item['MaxStackSize']);
                    $itemsx->setAttribute('strIcon', $item['Icon']);
                    $itemsx->setAttribute('bitSellable', $item['Sellable']);
                    $itemsx->setAttribute('bitDestroyable', $item['Destroyable']);
                    $itemsx->setAttribute('intStr', $item['intStr']);
                    $itemsx->setAttribute('intDex', $item['intDex']);
                    $itemsx->setAttribute('intInt', $item['intInt']);
                    $itemsx->setAttribute('intLuk', $item['intLuk']);
                    $itemsx->setAttribute('intCha', $item['intCha']);
                    $itemsx->setAttribute('intEnd', $item['intEnd']);
                    $itemsx->setAttribute('intWis', $item['intWis']);
                }
            }
        }
    } else {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('mergeshop'));
        $character = $XML->appendChild($dom->createElement('mergeshop'));
        $character->setAttribute('strName', "Empty Shop");
        $character->setAttribute('MSID', $shop_id);
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>



