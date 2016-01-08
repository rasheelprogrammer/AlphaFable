<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-loadhouseitemshop - v0.0.1
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    $shop_id = $doc->getElementsByTagName('intHouseItemShopID')->item(0)->nodeValue;
    $vendor_result = $MySQLi->query("SELECT * FROM df_house_item_vendors WHERE ShopID = '{$shop_id}'");
    $vendor = $vendor_result->fetch_assoc();
    if ($vender_result->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('houseitemshop'));
        $character = $XML->appendChild($dom->createElement('houseitemshop'));
        $character->setAttribute('strName', $vendor['ShopName']);
        $character->setAttribute('houseItemShopID', $shop_id);
        $character->setAttribute('intCount', -100);
        if ($vendor['ItemIDs'] != NULL && $vendor['ItemIDs'] != "None" && $vendor['ItemIDs'] != '0') {
            $replaced = str_replace(",", " OR HouseItemID = ", $vendor['ItemIDs']);
            $items = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = {$replaced}");
            if ($items->num_rows >= 1) {
                while ($item = $items->fetch_assoc()) {
                    $shop = $character->appendChild($dom->createElement('houseitems'));
                    $shop->setAttribute("HouseItemID", $item['HouseItemID']);
                    $shop->setAttribute("strItemName", $item['strItemName']);
                    $shop->setAttribute("strItemDescription", $item['strItemDescription']);
                    $shop->setAttribute("bitVisible", $item['bitVisible']);
                    $shop->setAttribute("bitDestroyable", $item['bitDestroyable']);
                    $shop->setAttribute("bitEquippable", $item['bitEquippable']);
                    $shop->setAttribute("bitRandomDrop", $item['bitRandomDrop']);
                    $shop->setAttribute("bitSellable", $item['bitSellable']);
                    $shop->setAttribute("bitDragonAmulet", $item['bitDragonAmulet']);
                    $shop->setAttribute("bitEnc", $item['bitEnc']);
                    $shop->setAttribute("intCost", $item['intCost']);
                    $shop->setAttribute("intCurrency", $item['intCurrency']);
                    $shop->setAttribute("intMaxStackSize", $item['intMaxStackSize']);
                    $shop->setAttribute("intRarity", $item['intRarity']);
                    $shop->setAttribute("intLevel", $item['intLevel']);
                    $shop->setAttribute("intMaxLevel", $item['intMaxLevel']);
                    $shop->setAttribute("intCategory", $item['intCategory']);
                    $shop->setAttribute("intEquipSpot", $item['intEquipSpot']);
                    $shop->setAttribute("intType", $item['intType']);
                    $shop->setAttribute("bitRandom", $item['bitRandom']);
                    $shop->setAttribute("intElement", $item['intElement']);
                    $shop->setAttribute("strType", $item['strType']);
                    $shop->setAttribute("strFileName", $item['strFileName']);
                }
            }
        }
    } else {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('shop'));
        $character = $XML->appendChild($dom->createElement('shop'));
        $character->setAttribute('strName', "Empty Shop");
        $character->setAttribute('houseItemShopID', $shop_id);
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
