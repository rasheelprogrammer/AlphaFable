<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-hairshopload - v0.0.1
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $shop_id = $doc->getElementsByTagName('intHairShopID')->item(0)->nodeValue;
    $gender = $doc->getElementsByTagName('strGender')->item(0)->nodeValue;

    $query = [];
    $result = [];

    $query[0] = $MySQLi->query("SELECT * FROM df_hair_vendors WHERE ShopID = '{$shop_id}'");
    $vendor = $query[0]->fetch_assoc();

    if ($query[0]->num_rows == 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('HairShop'));
        $character = $XML->appendChild($dom->createElement('HairShop'));
        $character->setAttribute('strHairShopName', $result[0]['ShopName']);
        $character->setAttribute('strFileName', $result[0]['ShopName']);
        $character->setAttribute('HairShopID', $shop_id);
        if ($result[0]['ItemIDs'] != NULL && $result[0]['ItemIDs'] != "None" && $result[0]['ItemIDs'] != '0') {
            $replaced = str_replace(",", " AND Gender = '{$gender}' OR HairID = ", $result[0]['ItemIDs']);
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
        $Core->returnXMLError('Invalid Data!', 'Message');
    }
    echo $dom->saveXML();
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();
?>
