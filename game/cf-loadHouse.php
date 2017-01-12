<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-loadHouse - v0.0.1
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;
    $token = $doc->getElementsByTagName('strToken')->item(0)->nodeValue;

    $char_result = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $char_result->fetch_assoc();

    if ($char['HasHouse'] > 0) {
        $Houseresults = $MySQLi->query("SELECT * FROM `df_equipment` WHERE `CharID` = {$charID} AND `StartingItem` = 1 AND `House` = 1 LIMIT 1");
        $HouseR = $Houseresults->fetch_assoc();

        $results = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$HouseR['ItemID']}' LIMIT 1");
        $item = $results->fetch_assoc();

        $user_result = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$char['userid']}' AND LoginToken = '{$token}'");
        $user = $user_result->fetch_assoc();

        if ($results->num_rows > 0 && $user_result->num_rows > 0 && $char_result->num_rows > 0) {
            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('LoadTown'));
            $house = $XML->appendChild($dom->createElement('vHouseDetails'));
            $house->setAttribute('tag', $item['HouseID']);
            $house->setAttribute('idCore_CharHouseAdj', $charID);
            $house->setAttribute('idCore_Characters', $charID);
            $house->setAttribute('idCore_Houses', $item['HouseID']);
            $house->setAttribute('Expr1', $item['HouseID']);
            $house->setAttribute('HouseID', $item['HouseID']);
            $house->setAttribute('CharHouseID', $char['id']);
            $house->setAttribute('strHouseName', $item['strHouseName']);
            $house->setAttribute('strHouseDescription', $item['strHouseDescription']);
            $house->setAttribute('bitVisible', $item['bitVisible']);
            $house->setAttribute('bitDestroyable', $item['bitDestroyable']);
            $house->setAttribute('bitEquippable', $item['bitEquippable']);
            $house->setAttribute('bitRandomDrop', $item['bitRandomDrop']);
            $house->setAttribute('bitSellable', $item['bitSellable']);
            $house->setAttribute('bitDragonAmulet', $invent['bitDragonAmulet']);
            $house->setAttribute('bitEnc', $invent['bitEnc']);
            $house->setAttribute('intCost', $item['intCost']);
            $house->setAttribute('intCurrency', $item['intCurrency']);
            $house->setAttribute('intRarity', $item['intRarity']);
            $house->setAttribute('intLevel', $item['intLevel']);
            $house->setAttribute('intCategory', $item['intCategory']);
            $house->setAttribute('intEquipSpot', $item['intEquipSpot']);
            $house->setAttribute('intType', $item['intType']);
            $house->setAttribute('bitRandom', $item['bitRandom']);
            $house->setAttribute('intElement', $item['intElement']);
            $house->setAttribute('strType', $item['strType']);
            $house->setAttribute('strIcon', $item['strIcon']);
            $house->setAttribute('strDesignInfo', $item['strDesignInfo']);
            $house->setAttribute('strFileName', $item['strFileName']);
            $house->setAttribute('intRegion', $item['intRegion']);
            $house->setAttribute('intTheme', $item['intTheme']);
            $house->setAttribute('intSize', $item['intSize']);
            $house->setAttribute('intBaseHP', $item['intBaseHP']);
            $house->setAttribute('intStorageSize', $item['intStorageSize']);
            $house->setAttribute('intMaxGuards', $item['intMaxGuards']);
            $house->setAttribute('intMaxRooms', $item['intMaxRooms']);
            $house->setAttribute('bitEquipped', 1);
            $house->setAttribute('intMaxExtItems', $item['intMaxExtItems']);
            echo $dom->saveXML();
        } else {
            $Core->returnXMLError('Error!', "There was a problem loading the House");
        }
    } else {
        
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
