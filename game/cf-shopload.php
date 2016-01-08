<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-shopload - v0.0.2
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    $shop_id = $doc->getElementsByTagName('intShopID')->item(0)->nodeValue;
    $vendor_result = $MySQLi->query("SELECT * FROM df_vendors WHERE ShopID = '{$shop_id}'");
    $vendor = $vendor_result->fetch_assoc();
    if ($vender_result->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('shop'));
        $character = $XML->appendChild($dom->createElement('shop'));
        $character->setAttribute('strCharacterName', $vendor['ShopName']);
        $character->setAttribute('ShopID', $shop_id);
        $character->setAttribute('intCount', -100);
        if ($vendor['ItemIDs'] != NULL && $vendor['ItemIDs'] != "None" && $vendor['ItemIDs'] != '0') {
            $replaced = str_replace(",", " OR ItemID = ", $vendor['ItemIDs']);
            $items = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = {$replaced}");
            if ($items->num_rows >= 1) {
                while ($item = $items->fetch_assoc()) {
                    $shop = $character->appendChild($dom->createElement('items'));
                    $shop->setAttribute('ItemID', $item['ItemID']);
                    $shop->setAttribute('CharItemID', $item['ItemID']);
                    $shop->setAttribute('strItemName', $item['ItemName']);
                    $shop->setAttribute('intCount', '1');
                    $shop->setAttribute('intHP', $item['hp']);
                    $shop->setAttribute('intMaxHP', $item['hp']);
                    $shop->setAttribute('intMP', $item['mp']);
                    $shop->setAttribute('intMaxMP', $item['mp']);
                    $shop->setAttribute('bitEquipped', $invent['StartingItem']);
                    $shop->setAttribute('bitDefault', '0');
                    $shop->setAttribute('intCurrency', $item['Currency']);
                    $shop->setAttribute('intCost', $item['Cost']);
                    $shop->setAttribute('intLevel', $item['Level']);
                    $shop->setAttribute('strItemDescription', $item['ItemDescription']);
                    $shop->setAttribute('bitDragonAmulet', $item['DragonAmulet']);
                    $shop->setAttribute('strEquipSpot', $item['EquipSpot']);
                    $shop->setAttribute('strCategory', $item['Category']);
                    $shop->setAttribute('strItemType', $item['ItemType']);
                    $shop->setAttribute('strType', $item['Type']);
                    $shop->setAttribute('strFileName', $item['FileName']);
                    $shop->setAttribute('intMin', $item['Min']);
                    $shop->setAttribute('intCrit', $item['intCrit']);
                    $shop->setAttribute('intDefMelee', $item['intDefMelee']);
                    $shop->setAttribute('intDefPierce', $item['intDefPierce']);
                    $shop->setAttribute('intDodge', $item['intDodge']);
                    $shop->setAttribute('intParry', $item['intParry']);
                    $shop->setAttribute('intDefMagic', $item['intDefMagic']);
                    $shop->setAttribute('intBlock', $item['intBlock']);
                    $shop->setAttribute('intDefRange', $item['intDefRange']);
                    $shop->setAttribute('intMax', $item['Max']);
                    $shop->setAttribute('intBonus', $item['Bonus']);
                    $shop->setAttribute('strResists', $item['Resists']);
                    $shop->setAttribute('strElement', $item['Element']);
                    $shop->setAttribute('intRarity', $item['Rarity']);
                    $shop->setAttribute('intMaxStackSize', $item['MaxStackSize']);
                    $shop->setAttribute('strIcon', $item['Icon']);
                    $shop->setAttribute('bitSellable', $item['Sellable']);
                    $shop->setAttribute('bitDestroyable', $item['Destroyable']);
                    $shop->setAttribute('intStr', $item['intStr']);
                    $shop->setAttribute('intDex', $item['intDex']);
                    $shop->setAttribute('intInt', $item['intInt']);
                    $shop->setAttribute('intLuk', $item['intLuk']);
                    $shop->setAttribute('intCha', $item['intCha']);
                    $shop->setAttribute('intEnd', $item['intEnd']);
                    $shop->setAttribute('intWis', $item['intWis']);
                }
            }
        }
    } else {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('shop'));
        $character = $XML->appendChild($dom->createElement('shop'));
        $character->setAttribute('strCharName', "Empty Shop");
        $character->setAttribute('ShopID', $shop_id);
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
