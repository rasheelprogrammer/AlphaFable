<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-loadpvpchar.php - v0.0.3
 */

require("../includes/classes/Core.class.php");
require('../includes/config.php');

$Core->makeXML();

$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (isset($HTTP_RAW_POST_DATA) && !empty(file_get_contents('php://input'))) {
    $xml = new SimpleXMLElement($HTTP_RAW_POST_DATA);

    if (isset($xml->intPVPCharID)) {
        $charID = $xml->intPVPCharID;

        $query = array();
        $result = array();

        $query[0] = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}' LIMIT 1");
        $result[0] = $query[0]->fetch_assoc();

        if ($query[0]->num_rows != 0) {
            $query[1] = $MySQLi->query("SELECT LevelCap FROM df_settings LIMIT 1");
            $result[1] = $query[1]->fetch_array();

            $exptolevel = $Core->calcEXPtoLevel($result[0]['level'], $result[0]['exp']);
            $levelCap = $result[1][0];
            if ($result[0]['level'] >= $levelCap) {
                $newEXP = 0;
            } else {
                $newEXP = $result[0]['exp'];
            }

            $query[3] = $MySQLi->query("SELECT * FROM df_hairs WHERE HairID = '{$result[0]['hairid']}' AND Gender = '{$result[0]['gender']}' LIMIT 1");
            $result[3] = $query[3]->fetch_assoc();

            $query[4] = $MySQLi->query("SELECT * FROM `df_equipment` WHERE `CharID` = {$charID} AND `StartingItem` = 1 AND `House` = 1 LIMIT 1");
            $result[4] = $query[4]->fetch_assoc();
            if ($query[4]->num_rows > 0 && $result[0]['HasHouse'] != 0) {
                $HouseID = $result[4]['ItemID'];
            } else {
                $HouseID = 0;
            }

            if ($result[0]['HomeTownID'] == 0 && $result[0]['HasHouse'] != 0 && $HouseID != 0) {
                $query[5] = $MySQLi->query("SELECT * FROM df_houses WHERE HouseID = '{$result[4]['ItemID']}'");
                $result[5] = $query[5]->fetch_assoc();
                $result[5]['Name'] = "Home";
                $result[5]['QuestID'] = "0";
                $result[5]['FileName'] = $result[5]['strFileName'];
                $result[5]['XFileName'] = "none";
                $result[5]['Extra'] = "";
            } else {
                $query[5] = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$result[0]['HomeTownID']}'");
                $result[5] = $query[5]->fetch_assoc();
                $result[5]['Name'] = "No Quest";
            }

            $zones = explode(";", $result[5]['Extra']);
            for ($i = 0; $i <= count($zones); $i++) {
                if (isset($extra)) {
                    $extra = $extra . $zones[$i] . "\n";
                } else {
                    $extra = $zones[$i] . "\n";
                }
            }

            $query[6] = $MySQLi->query("SELECT * FROM df_class WHERE ClassID = '{$result[0]['classid']}'");
            $result[6] = $query[6]->fetch_assoc();

            $dom = new DOMDocument();
            $XML = $dom->appendChild($dom->createElement('character'));
            $character = $XML->appendChild($dom->createElement('character'));
            $character->setAttribute('CharID', $result[0]['id']);
            $character->setAttribute('strCharacterName', $result[0]['name']);
            $character->setAttribute('dateCreated', $result[0]['born']);
            $character->setAttribute('isBirthday', 0);
            $character->setAttribute('intHP', $result[0]['hp']);
            $character->setAttribute('intMP', $result[0]['mp']);
            $character->setAttribute('intLevel', $result[0]['level']);
            $character->setAttribute('intExp', $newEXP);
            $character->setAttribute('intAccessLevel', $result[0]['access']);
            $character->setAttribute('intHouseID', $HouseID);
            $character->setAttribute('intSilver', $result[0]['Silver']);
            $character->setAttribute('intGold', $result[0]['gold']);
            $character->setAttribute('intGems', $result[0]['Gems']);
            $character->setAttribute('intCoins', $result[0]['Coins']);
            $character->setAttribute('intMaxBagSlots', $result[0]['MaxBagSlots']);
            $character->setAttribute('intMaxBankSlots', $result[0]['MaxBankSlots']);
            $character->setAttribute('intMaxHouseSlots', $result[0]['MaxHouseSlots']);
            $character->setAttribute('intMaxHouseItemSlots', $result[0]['MaxHouseItemSlots']);
            $character->setAttribute('intDragonAmulet', $result[0]['dragon_amulet']);
            $character->setAttribute('intAccesslevel', '0');
            $character->setAttribute('strGender', $result[0]['gender']);
            $character->setAttribute('intColorHair', $result[0]['colorhair']);
            $character->setAttribute('intColorSkin', $result[0]['colorskin']);
            $character->setAttribute('intColorBase', $result[0]['colorbase']);
            $character->setAttribute('intColorTrim', $result[0]['colortrim']);
            $character->setAttribute('intStr', $result[0]['intSTR']);
            $character->setAttribute('intDex', $result[0]['intDEX']);
            $character->setAttribute('intInt', $result[0]['intINT']);
            $character->setAttribute('intLuk', $result[0]['intLUK']);
            $character->setAttribute('intCha', $result[0]['intCHA']);
            $character->setAttribute('intEnd', $result[0]['intEND']);
            $character->setAttribute('intWis', $result[0]['intWIS']);
            $character->setAttribute('intSkillPoints', '0');
            $character->setAttribute('intStatPoints', $statpoints);
            $character->setAttribute('intCharStatus', '0');
            $character->setAttribute('strArmor', $result[0]['strArmor']);
            $character->setAttribute('strSkills', $result[0]['strSkills']);
            $character->setAttribute('strQuests', $result[0]['strQuests']);
            $character->setAttribute('intExpToLevel', $exptolevel);
            $character->setAttribute('RaceID', $result[0]['raceid']);
            $character->setAttribute('strRaceName', $result[0]['race']);
            $character->setAttribute('GuildID', '1');
            $character->setAttribute('strGuildName', "None");
            $character->setAttribute('QuestID', $result[5]['QuestID']);
            $character->setAttribute('strQuestName', $result[5]['Name']);
            $character->setAttribute('strQuestFileName', $result[5]['FileName']);
            $character->setAttribute('strXQuestFileName', $result[5]['XFileName']);
            $character->setAttribute('strExtra', $extra);
            $character->setAttribute('BaseClassID', $result[0]['BaseClassID']);
            $character->setAttribute('ClassID', $result[0]['classid']);
            $character->setAttribute('PrevClassID', $result[0]['PrevClassID']);
            $character->setAttribute('strClassName', $result[6]['ClassName']);
            $character->setAttribute('strClassFileName', $result[6]['ClassSWF']);
            $character->setAttribute('strArmorName', $result[6]['ArmorName']);
            $character->setAttribute('strArmorDescription', $result[6]['ArmorDescription']);
            $character->setAttribute('strArmorResists', $result[6]['ArmorResists']);
            $character->setAttribute('intDefMelee', $result[6]['DefMelee']);
            $character->setAttribute('intDefRange', $result[6]['DefRange']);
            $character->setAttribute('intDefMagic', $result[6]['DefMagic']);
            $character->setAttribute('intParry', $result[6]['Parry']);
            $character->setAttribute('intDodge', $result[6]['Dodge']);
            $character->setAttribute('intBlock', $result[6]['Block']);
            $character->setAttribute('strWeaponName', $result[6]['WeaponName']);
            $character->setAttribute('strWeaponDescription', $result[6]['WeaponDescription']);
            $character->setAttribute('strWeaponDesignInfo', $result[6]['WeaponDesignInfo']);
            $character->setAttribute('strWeaponResists', $result[6]['WeaponResists']);
            $character->setAttribute('intWeaponLevel', $result[6]['WeaponLevel']);
            $character->setAttribute('strWeaponIcon', $result[6]['WeaponIcon']);
            $character->setAttribute('strType', $result[6]['Type']);
            $character->setAttribute('bitDefault', '1');
            $character->setAttribute('bitEquipped', '1');
            $character->setAttribute('strItemType', $result[6]['ItemType']);
            $character->setAttribute('intCrit', $result[6]['Crit']);
            $character->setAttribute('intDmgMin', $result[6]['DmgMin']);
            $character->setAttribute('intDmgMax', $result[6]['DmgMax']);
            $character->setAttribute('intBonus', $result[6]['Bonus']);
            $character->setAttribute('strElement', $result[6]['Element']);
            $character->setAttribute('strEquippable', "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket");
            $character->setAttribute('strHairFileName', $result[3]['HairSWF']);
            $character->setAttribute('intHairFrame', $result[0]['hairframe']);
            $character->setAttribute('gemReward', '0');
            $query[7] = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$result[0]['id']}' AND HouseItem = 0");
            if ($query[7]->num_rows > 0) {
                while ($result[7] = $query[7]->fetch_assoc()) {
                    $query[8] = $MySQLi->query("SELECT * FROM df_items WHERE ItemID = '{$result[7]['ItemID']}'");
                    if ($query[8]->num_rows > 0) {
                        $items = $character->appendChild($dom->createElement('items'));
                        $result[8] = $query[8]->fetch_assoc();
                        $items->setAttribute('ItemID', $result[8]['ItemID']);
                        $items->setAttribute('CharItemID', $result[8]['ItemID']);
                        $items->setAttribute('strItemName', $result[8]['ItemName']);
                        $items->setAttribute('intCount', $result[7]['count']);
                        $items->setAttribute('intHP', $result[8]['hp']);
                        $items->setAttribute('intMaxHP', $result[8]['hp']);
                        $items->setAttribute('intMP', $result[8]['mp']);
                        $items->setAttribute('intMaxMP', $result[8]['mp']);
                        $items->setAttribute('bitEquipped', $result[7]['StartingItem']);
                        $items->setAttribute('bitDefault', $result[7]['StartingItem']);
                        $items->setAttribute('intCurrency', $result[8]['Currency']);
                        $items->setAttribute('intCost', $result[8]['Cost']);
                        $items->setAttribute('intLevel', $result[8]['Level']);
                        $items->setAttribute('strItemDescription', $result[8]['ItemDescription']);
                        $items->setAttribute('bitDragonAmulet', $result[8]['DragonAmulet']);
                        $items->setAttribute('strEquipSpot', $result[8]['EquipSpot']);
                        $items->setAttribute('strCategory', $result[8]['Category']);
                        $items->setAttribute('strItemType', $result[8]['ItemType']);
                        $items->setAttribute('strType', $result[8]['Type']);
                        $items->setAttribute('strFileName', $result[8]['FileName']);
                        $items->setAttribute('intMin', $result[8]['Min']);
                        $items->setAttribute('intCrit', $result[8]['intCrit']);
                        $items->setAttribute('intDefMelee', $result[8]['intDefMelee']);
                        $items->setAttribute('intDefPierce', $result[8]['intDefPierce']);
                        $items->setAttribute('intDodge', $result[8]['intDodge']);
                        $items->setAttribute('intParry', $result[8]['intParry']);
                        $items->setAttribute('intDefMagic', $result[8]['intDefMagic']);
                        $items->setAttribute('intBlock', $result[8]['intBlock']);
                        $items->setAttribute('intDefRange', $result[8]['intDefRange']);
                        $items->setAttribute('intMax', $result[8]['Max']);
                        $items->setAttribute('intBonus', $result[8]['Bonus']);
                        $items->setAttribute('strResists', $result[8]['Resists']);
                        $items->setAttribute('strElement', $result[8]['Element']);
                        $items->setAttribute('intRarity', $result[8]['Rarity']);
                        $items->setAttribute('intMaxStackSize', $result[8]['MaxStackSize']);
                        $items->setAttribute('strIcon', $result[8]['Icon']);
                        $items->setAttribute('bitSellable', $result[8]['Sellable']);
                        $items->setAttribute('bitDestroyable', $result[8]['Destroyable']);
                        $items->setAttribute('intStr', $result[8]['intStr']);
                        $items->setAttribute('intDex', $result[8]['intDex']);
                        $items->setAttribute('intInt', $result[8]['intInt']);
                        $items->setAttribute('intLuk', $result[8]['intLuk']);
                        $items->setAttribute('intCha', $result[8]['intCha']);
                        $items->setAttribute('intEnd', $result[8]['intEnd']);
                        $items->setAttribute('intWis', $result[8]['intWis']);
                    }
                }
            }
            if ($result[0]['HasDragon'] == 1) {
                $query[9] = $MySQLi->query("SELECT * FROM df_dragons WHERE CharDragID = '{$result[0]['id']}'");
                if ($query[9]->num_rows > 0) {
                    $result[9] = $query[9]->fetch_assoc();
                    $query[10] = $MySQLi->query("SELECT FileName FROM df_dragoncustomize WHERE CustomID = '{$result[9]['intHeads']}' AND Type = 'Head'");
                    $head = $query[10]->fetch_array();
                    $query[11] = $MySQLi->query("SELECT FileName FROM df_dragoncustomize WHERE CustomID = '{$result[9]['intTails']}' AND Type = 'Tail'");
                    $tail = $query[11]->fetch_array();
                    $query[12] = $MySQLi->query("SELECT FileName FROM df_dragoncustomize WHERE CustomID = '{$result[9]['intWings']}' AND Type = 'Wing'");
                    $wing = $query[12]->fetch_array();
                    $dragon = $character->appendChild($dom->createElement('dragon'));
                    $dragon->setAttribute('idCore_CharDragons', $charID);
                    $dragon->setAttribute('strName', $result[9]['strName']);
                    $dragon->setAttribute('dateLastFed', $result[9]['dateLastFed']);
                    $dragon->setAttribute('intTotalStats', $result[9]['intTotalStats']);
                    $dragon->setAttribute('intHeal', $result[9]['intHeal']);
                    $dragon->setAttribute('intMagic', $result[9]['intMagic']);
                    $dragon->setAttribute('intMelee', $result[9]['intMelee']);
                    $dragon->setAttribute('intBuff', $result[9]['intBuff']);
                    $dragon->setAttribute('intDebuff', $result[9]['intDebuff']);
                    $dragon->setAttribute('intColorDskin', $result[9]['intColorSkin']);
                    $dragon->setAttribute('intColorDeye', $result[9]['intColorEye']);
                    $dragon->setAttribute('intColorDhorn', $result[9]['intColorHorn']);
                    $dragon->setAttribute('intColorDwing', $result[9]['intColorWing']);
                    $dragon->setAttribute('intHeadID', $result[9]['intHeads']);
                    $dragon->setAttribute('strHeadFilename', $head[0]);
                    $dragon->setAttribute('intWingID', $result[9]['intWings']);
                    $dragon->setAttribute('strWingFilename', $wing[0]);
                    $dragon->setAttribute('intTailID', $result[9]['intTails']);
                    $dragon->setAttribute('strFileName', $result[9]['FileName']);
                    $dragon->setAttribute('strTailFilename', $tail[0]);
                    $dragon->setAttribute('intMin', $result[9]['intMin']);
                    $dragon->setAttribute('intMax', $result[9]['intMax']);
                    $dragon->setAttribute('strType', $result[9]['strType']);
                    $dragon->setAttribute('strElement', $result[9]['strElement']);
                    $dragon->setAttribute('intColorDelement', $result[9]['intColorDelement']);
                }
            }
            if ($result[0]['HasHouse'] != 0 && $result[0]['HasHouse'] != NULL) {
                $query[7] = $MySQLi->query("SELECT * FROM df_equipment WHERE CharID = '{$result[0]['id']}' AND HouseItem != 0");
                if ($query[7]->num_rows > 0) {
                    while ($result[7] = $query[7]->fetch_assoc()) {
                        $query[13] = $MySQLi->query("SELECT * FROM df_house_items WHERE HouseItemID = '{$result[7]['ItemID']}'");
                        $result[13] = $query[13]->fetch_assoc();
                        if ($query[13]->num_rows > 0) {
                            $house = $character->appendChild($dom->createElement('houseitems'));
                            $house->setAttribute('HouseItemID', $result[13]['HouseItemID']);
                            $house->setAttribute('CharHouseItemID', $result[13]['HouseItemID']);
                            $house->setAttribute('strItemName', $result[13]['strItemName']);
                            $house->setAttribute('strItemDescription', $result[13]['strItemDescription']);
                            $house->setAttribute('bitVisible', $result[13]['bitVisible']);
                            $house->setAttribute('bitDestroyable', $result[13]['bitDestroyable']);
                            $house->setAttribute('bitEquippable', $result[13]['bitEquippable']);
                            $house->setAttribute('bitRandomDrop', $result[13]['bitRandomDrop']);
                            $house->setAttribute('bitSellable', $result[13]['bitSellable']);
                            $house->setAttribute('bitDragonAmulet', $result[13]['bitDragonAmulet']);
                            $house->setAttribute('intCost', $result[13]['intCost']);
                            $house->setAttribute('intCurrency', $result[13]['intCurrency']);
                            $house->setAttribute('intMaxStackSize', $result[13]['intMaxStackSize']);
                            $house->setAttribute('intRarity', $result[13]['intRarity']);
                            $house->setAttribute('intLevel', $result[13]['intLevel']);
                            $house->setAttribute('intMaxlevel', $result[13]['intMaxlevel']);
                            $house->setAttribute('intCategory', $result[13]['intCategory']);
                            $house->setAttribute('intEquipSpot', $result[13]['intEquipSpot']);
                            $house->setAttribute('intType', $result[13]['intType']);
                            $house->setAttribute('bitRandom', $result[13]['bitRandom']);
                            $house->setAttribute('intElement', $result[13]['intElement']);
                            $house->setAttribute('strType', $result[13]['strType']);
                            $house->setAttribute('strFileName', $result[13]['strFileName']);
                            $house->setAttribute('intEquipSlotPos', $result[7]['intEquipSlotPos']);
                            $house->setAttribute('intHoursOwned', "1");
                        }
                    }
                }
            }
        } else {
            $Core->returnCustomXMLMessage("character", "CharID", NULL);
        }
    } else {
        $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
echo $dom->saveXML();
$MySQLi->close();
?>