<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-loadHouseByID - v0.0.1
 */

include("../includes/classes/Core.class.php");
include('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intGuestID')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();

    $Houseresults = $MySQLi->query("SELECT * FROM `df_equipment` WHERE `CharID` = {$charID} AND `StartingItem` = 1 AND `House` = 1 LIMIT 1");
    $HouseR = $Houseresults->fetch_assoc();

    if ($char_result->num_rows > 0 && $Houseresults->num_rows > 0 && $char['HasHouse'] != 0) {
        $results = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$HouseR['ItemID']}' LIMIT 1");
        $houseQ = $results->fetch_assoc();

        $dom = new DOMDocument();
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('houseinfo'));
        $house = $XML->appendChild($dom->createElement('houseinfo'));
        $house->setAttribute('CharName', $char['name']);
        $house->setAttribute('CharID', $charID);
        $house->setAttribute('intHouseID', $houseQ['HouseID']);
        $house->setAttribute('strHouseFileName', $houseQ['strFileName']);

        $inv1 = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$char['id']}' AND HouseItem = 1");
        if ($inv1->num_rows > 0) {
            while ($inv = $inv1->fetch_assoc()) {
                $item_inv = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$inv['ItemID']}'");
                if ($item_inv->num_rows > 0) {
                    $houseQuery = $item_inv->fetch_assoc();
                    $items = $house->appendChild($dom->createElement('houseitems'));
                    $items->setAttribute('HouseItemID', $houseQuery['HouseItemID']);
                    $items->setAttribute('CharHouseItemID', $houseQuery['HouseItemID']);
                    $items->setAttribute('strItemName', $houseQuery['strItemName']);
                    $items->setAttribute('strItemDescription', $houseQuery['strItemDescription']);
                    $items->setAttribute('bitVisible', $houseQuery['bitVisible']);
                    $items->setAttribute('bitDestroyable', $houseQuery['bitDestroyable']);
                    $items->setAttribute('bitEquippable', $houseQuery['bitEquippable']);
                    $items->setAttribute('bitRandomDrop', $houseQuery['bitRandomDrop']);
                    $items->setAttribute('bitSellable', $houseQuery['bitSellable']);
                    $items->setAttribute('bitDragonAmulet', $houseQuery['bitDragonAmulet']);
                    $items->setAttribute('intCost', $houseQuery['intCost']);
                    $items->setAttribute('intCurrency', $houseQuery['intCurrency']);
                    $items->setAttribute('intMaxStackSize', $houseQuery['intMaxStackSize']);
                    $items->setAttribute('intRarity', $houseQuery['intRarity']);
                    $items->setAttribute('intLevel', $houseQuery['intLevel']);
                    $items->setAttribute('intMaxlevel', $houseQuery['intMaxlevel']);
                    $items->setAttribute('intCategory', $houseQuery['intCategory']);
                    $items->setAttribute('intEquipSpot', $houseQuery['intEquipSpot']);
                    $items->setAttribute('intType', $houseQuery['intType']);
                    $items->setAttribute('bitRandom', $houseQuery['bitRandom']);
                    $items->setAttribute('intElement', $houseQuery['intElement']);
                    $items->setAttribute('strType', $houseQuery['strType']);
                    $items->setAttribute('strFileName', $houseQuery['strFileName']);
                    $items->setAttribute('intEquipSlotPos', $inv['intEquipSlotPos']);
                    $items->setAttribute('intHoursOwned', "1");
                }
            }
        }
        echo $dom->saveXML();
    } else {
        $Core->returnXMLError('Error!', "There was a problem loading the House");
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();