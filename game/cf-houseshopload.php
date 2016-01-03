<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-houseshopload - v0.0.2
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $shop_id = $doc->getElementsByTagName('intShopID')->item(0)->nodeValue;
    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;

    $vendor_result = $MySQLi->query("SELECT * FROM df_house_vendors WHERE ShopID = '{$shop_id}'");
    $vendor = $vendor_result->fetch_assoc();

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();
    if ($vender_result->num_rows == 0 || $char_result->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('shop'));
        $character = $XML->appendChild($dom->createElement('shop'));
        $character->setAttribute('strCharacterName', $vendor['ShopName']);
        $character->setAttribute('ShopID', $shop_id);
        $character->setAttribute('intCount', -100);
        if ($vendor['ItemIDs'] != NULL && $vendor['ItemIDs'] != "None" && $vendor['ItemIDs'] != '0') {
            if ($char['HasHouse'] != 0 && $char['HasHouse'] != NULL) {
                $items = $MySQLi->query("SELECT * FROM df_equipment WHERE House = 1");
                if ($items->num_rows >= 1) {
                    while ($item = $items->fetch_assoc()) {
                        $items2 = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = {$item['ItemID']} LIMIT 1");
                        $item2 = $items2->fetch_assoc();
                        $shop = $character->appendChild($dom->createElement('iHouses'));
                        $shop->setAttribute('HouseID', $item2['HouseID']);
                        $shop->setAttribute('CharHouseID', $item2['HouseID']);
                        $shop->setAttribute('strHouseName', $item2['strHouseName']);
                        $shop->setAttribute('strHouseDescription', $item2['strHouseDescription']);
                        $shop->setAttribute('bitVisible', $item2['bitVisible']);
                        $shop->setAttribute('bitDestroyable', $item2['bitDestroyable']);
                        $shop->setAttribute('bitEquippable', $item2['bitEquippable']);
                        $shop->setAttribute('bitRandomDrop', $item2['bitRandomDrop']);
                        $shop->setAttribute('bitSellable', $item2['bitSellable']);
                        $shop->setAttribute('bitDragonAmulet', $invent['bitDragonAmulet']);
                        $shop->setAttribute('bitEnc', $invent['bitEnc']);
                        $shop->setAttribute('intCost', $item2['intCost']);
                        $shop->setAttribute('intCurrency', $item2['intCurrency']);
                        $shop->setAttribute('intRarity', $item2['intRarity']);
                        $shop->setAttribute('intLevel', $item2['intLevel']);
                        $shop->setAttribute('intCategory', $item2['intCategory']);
                        $shop->setAttribute('intEquipSpot', $item2['intEquipSpot']);
                        $shop->setAttribute('intType', $item2['intType']);
                        $shop->setAttribute('bitRandom', $item2['bitRandom']);
                        $shop->setAttribute('intElement', $item2['intElement']);
                        $shop->setAttribute('strType', $item2['strType']);
                        $shop->setAttribute('strIcon', $item2['strIcon']);
                        $shop->setAttribute('strDesignInfo', $item2['strDesignInfo']);
                        $shop->setAttribute('strFileName', $item2['strFileName']);
                        $shop->setAttribute('intRegion', $item2['intRegion']);
                        $shop->setAttribute('intTheme', $item2['intTheme']);
                        $shop->setAttribute('intSize', $item2['intSize']);
                        $shop->setAttribute('intBaseHP', $item2['intBaseHP']);
                        $shop->setAttribute('intStorageSize', $item2['intStorageSize']);
                        $shop->setAttribute('intMaxGuards', $item2['intMaxGuards']);
                        $shop->setAttribute('intMaxRooms', $item2['intMaxRooms']);
                        $shop->setAttribute('bitEquipped', $item['StartingItem']);
                        $shop->setAttribute('intMaxExtItems', $item2['intMaxExtItems']);
                    }
                }
            }
            $replaced = str_replace(",", " OR HouseID = ", $vendor['ItemIDs']);
            $items = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = {$replaced}");
            if ($items->num_rows >= 1) {
                while ($item = $items->fetch_assoc()) {
                    $shop = $character->appendChild($dom->createElement('sHouses'));
                    $shop->setAttribute('HouseID', $item['HouseID']);
                    $shop->setAttribute('strHouseName', $item['strHouseName']);
                    $shop->setAttribute('strHouseDescription', $item['strHouseDescription']);
                    $shop->setAttribute('bitVisible', $item['bitVisible']);
                    $shop->setAttribute('bitDestroyable', $item['bitDestroyable']);
                    $shop->setAttribute('bitEquippable', $item['bitEquippable']);
                    $shop->setAttribute('bitRandomDrop', $item['bitRandomDrop']);
                    $shop->setAttribute('bitSellable', $item['bitSellable']);
                    $shop->setAttribute('bitDragonAmulet', $invent['bitDragonAmulet']);
                    $shop->setAttribute('bitEnc', $invent['bitEnc']);
                    $shop->setAttribute('intCost', $item['intCost']);
                    $shop->setAttribute('intCurrency', $item['intCurrency']);
                    $shop->setAttribute('intRarity', $item['intRarity']);
                    $shop->setAttribute('intLevel', $item['intLevel']);
                    $shop->setAttribute('intCategory', $item['intCategory']);
                    $shop->setAttribute('intEquipSpot', $item['intEquipSpot']);
                    $shop->setAttribute('intType', $item['intType']);
                    $shop->setAttribute('bitRandom', $item['bitRandom']);
                    $shop->setAttribute('intElement', $item['intElement']);
                    $shop->setAttribute('strType', $item['strType']);
                    $shop->setAttribute('strIcon', $item['strIcon']);
                    $shop->setAttribute('strDesignInfo', $item['strDesignInfo']);
                    $shop->setAttribute('strFileName', $item['strFileName']);
                    $shop->setAttribute('intRegion', $item['intRegion']);
                    $shop->setAttribute('intTheme', $item['intTheme']);
                    $shop->setAttribute('intSize', $item['intSize']);
                    $shop->setAttribute('intBaseHP', $item['intBaseHP']);
                    $shop->setAttribute('intStorageSize', $item['intStorageSize']);
                    $shop->setAttribute('intMaxGuards', $item['intMaxGuards']);
                    $shop->setAttribute('intMaxRooms', $item['intMaxRooms']);
                    $shop->setAttribute('intMaxExtItems', $item['intMaxExtItems']);
                }
            }
        }
    } else {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('shop'));
        $character = $XML->appendChild($dom->createElement('shop'));
        $character->setAttribute('strCharacterName', "Empty Shop");
        $character->setAttribute('ShopID', $shop_id);
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
