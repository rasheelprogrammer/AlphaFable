<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-hairshopload - v0.0.1
 */

include ("../includes/classes/GameFunctions.class.php");
include ('../includes/config.php');

$Game->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (!empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $shop_id = $doc->getElementsByTagName('intHairShopID')->item(0)->nodeValue;
    $gender = $doc->getElementsByTagName('strGender')->item(0)->nodeValue;

    $query = array();
    $result = array();

    $vendor_result = $MySQLi->query("SELECT * FROM df_hair_vendors WHERE ShopID = '{$shop_id}'");
    $vendor = $vendor_result->fetch_assoc();

    if ($vender_result->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('HairShop'));
        $character = $XML->appendChild($dom->createElement('HairShop'));
        $character->setAttribute('strHairShopName', $vendor['ShopName']);
        $character->setAttribute('strFileName', $vendor['ShopName']);
        $character->setAttribute('HairShopID', $shop_id);
        if ($vendor['ItemIDs'] != NULL && $vendor['ItemIDs'] != "None" && $vendor['ItemIDs'] != '0') {
            $replaced = str_replace(",", " AND Gender = '{$gender}' OR HairID = ", $vendor['ItemIDs']);
            $items = $MySQLi->query("SELECT * FROM df_hairs WHERE Gender = '{$gender}' AND HairID = {$replaced}");
            if ($items->num_rows >= 1) {
                while ($item = $items->fetch_assoc()) {
                    $shop = $character->appendChild($dom->createElement('hair'));
                    $shop->setAttribute('HairID', $item['HairID']);
                    $shop->setAttribute('strName', $item['HairName']);
                    $shop->setAttribute('strFileName', $item['HairSWF']);
                    $shop->setAttribute('intFrame', $item['Frame']);
                    $shop->setAttribute('intPrice', $item['Price']);
                    $shop->setAttribute('strGender', $item['Gender']);
                    $shop->setAttribute('RaceID', $item['RaceID']);
                    $shop->setAttribute('bitEarVisible', $item['EarVisible']);
                }
            }
        }
    } else {
        $Game->returnXMLError('Invalid Data!', 'Message');
    }
    echo $dom->saveXML();
} else {
    $Game->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
