<?php

/*
 * AlphaFable (DragonFable Private Server) 
 * Made by MentalBlank
 * File: cf-toBank - v0.0.2
 */


//TODO: Fix Item stacking and CharItemID


require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->intCharID) && isset($xml->intCharItemID) && isset($xml->strToken)) {
        $charID = $xml->intCharID;
        $token = $xml->strToken;
        $CharItemID = $xml->intCharItemID;

        $query = [];
        $result = [];

        $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}' LIMIT 1");
        $result[0] = $query[0]->fetch_assoc();

        if ($query[0]->num_rows != 0) {
            $query[1] = $MySQLi->query("SELECT * FROM df_users WHERE id = '{$result[0]['userid']}' AND LoginToken = '{$token}' LIMIT 1");
            $result[1] = $query[1]->fetch_assoc();

            if ($query[1]->num_rows != 0) {
                $query[2] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$CharItemID}'");
                $result[2] = $query[2]->fetch_assoc();

                if ($query[2]->num_rows != 0) {
                    $query[3] = $MySQLi->query("INSERT INTO `df_bank` (`id`, `CharID`, `ItemID`) VALUES ('', '{$charID}', '{$CharItemID}')");
                    if ($MySQLi->affected_rows > 0) {
                        $query[4] = $MySQLi->query("SELECT * FROM df_bank WHERE CharID = '{$charID}'");
                        $query[5] = $MySQLi->query("DELETE FROM `df_equipment` WHERE `CharID` = '{$charID}' AND `ItemID` = '{$CharItemID}' LIMIT 1");

                        if ($MySQLi->affected_rows > 0) {
                            if ($query[4]->num_rows) {
                                $dom = new DOMDocument();
                                $XML = $dom->appendChild($dom->createElement('bank'));
                                $character = $XML->appendChild($dom->createElement('bank'));
                                while ($result[4] = $query[4]->fetch_assoc()) {
                                    $query[6] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$bank['ItemID']}'");
                                    $result[6] = $query[6]->fetch_assoc();
                                    $items = $character->appendChild($dom->createElement('items'));
                                    $items->setAttribute('ItemID', $result[6]['ItemID']);
                                    $items->setAttribute('CharItemID', $result[6]['ItemID']);
                                    $items->setAttribute('strItemName', $result[6]['ItemName']);
                                    $items->setAttribute('intCount', 1);
                                    $items->setAttribute('intHP', $result[6]['hp']);
                                    $items->setAttribute('intMaxHP', $result[6]['hp']);
                                    $items->setAttribute('intMP', $result[6]['mp']);
                                    $items->setAttribute('intMaxMP', $result[6]['mp']);
                                    $items->setAttribute('bitEquipped', $inv['StartingItem']);
                                    $items->setAttribute('bitDefault', $inv['StartingItem']);
                                    $items->setAttribute('intCurrency', $result[6]['Currency']);
                                    $items->setAttribute('intCost', $result[6]['Cost']);
                                    $items->setAttribute('intLevel', $result[6]['Level']);
                                    $items->setAttribute('strItemDescription', $result[6]['ItemDescription']);
                                    $items->setAttribute('bitDragonAmulet', $result[6]['DragonAmulet']);
                                    $items->setAttribute('strEquipSpot', $result[6]['EquipSpot']);
                                    $items->setAttribute('strCategory', $result[6]['Category']);
                                    $items->setAttribute('strItemType', $result[6]['ItemType']);
                                    $items->setAttribute('strType', $result[6]['Type']);
                                    $items->setAttribute('strFileName', $result[6]['FileName']);
                                    $items->setAttribute('intMin', $result[6]['Min']);
                                    $items->setAttribute('intCrit', $result[6]['intCrit']);
                                    $items->setAttribute('intDefMelee', $result[6]['intDefMelee']);
                                    $items->setAttribute('intDefPierce', $result[6]['intDefPierce']);
                                    $items->setAttribute('intDodge', $result[6]['intDodge']);
                                    $items->setAttribute('intParry', $result[6]['intParry']);
                                    $items->setAttribute('intDefMagic', $result[6]['intDefMagic']);
                                    $items->setAttribute('intBlock', $result[6]['intBlock']);
                                    $items->setAttribute('intDefRange', $result[6]['intDefRange']);
                                    $items->setAttribute('intMax', $result[6]['Max']);
                                    $items->setAttribute('intBonus', $result[6]['Bonus']);
                                    $items->setAttribute('strResists', $result[6]['Resists']);
                                    $items->setAttribute('strElement', $result[6]['Element']);
                                    $items->setAttribute('intRarity', $result[6]['Rarity']);
                                    $items->setAttribute('intMaxStackSize', $result[6]['MaxStackSize']);
                                    $items->setAttribute('strIcon', $result[6]['Icon']);
                                    $items->setAttribute('bitSellable', $result[6]['Sellable']);
                                    $items->setAttribute('bitDestroyable', $result[6]['Destroyable']);
                                    $items->setAttribute('intStr', $result[6]['intStr']);
                                    $items->setAttribute('intDex', $result[6]['intDex']);
                                    $items->setAttribute('intInt', $result[6]['intInt']);
                                    $items->setAttribute('intLuk', $result[6]['intLuk']);
                                    $items->setAttribute('intCha', $result[6]['intCha']);
                                    $items->setAttribute('intEnd', $result[6]['intEnd']);
                                    $items->setAttribute('intWis', $result[6]['intWis']);
                                }
                                $status = $XML->appendChild($dom->createElement('status'));
                                $status->setAttribute("status", "SUCCESS");
                            } else {
                                $Core->returnXMLError("Error!", "Could not load Bank items");
                            }
                        } else {
                            $Core->returnXMLError("Error!", "Could not update bank information");
                        }
                    } else {
                        $Core->returnXMLError("Error!", "There was an error transferring your item");
                    }
                } else {
                    $Core->returnXMLError('Error!', 'Item does not exist.');
                }
            } else {
                $Core->returnXMLError('User Not Found', 'Character information was unable to be requested.');
            }
        } else {
            $Core->returnXMLError('Character Not Found', 'Character information was unable to be requested.');
        }
    } else {
        $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();