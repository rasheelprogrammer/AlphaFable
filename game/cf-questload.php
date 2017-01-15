<?php #FILE NEEDS REDO

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * File: cf-questload - v0.0.3
 */

include ("../includes/classes/Core.class.php");
include ('../includes/config.php');

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');
if (isset($HTTP_RAW_POST_DATA) && !empty($HTTP_RAW_POST_DATA)) {
    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $quest_id = $doc->getElementsByTagName('intQuestID')->item(0)->nodeValue;
    $charID = $doc->getElementsByTagName('intCharID')->item(0)->nodeValue;

    $results = $MySQLi->query("SELECT * FROM df_quests WHERE QuestID = '{$quest_id}' LIMIT 1");
    $questq = $results->fetch_assoc();

    $charQuery = $MySQLi->query("SELECT * FROM df_characters WHERE id = '{$charID}'");
    $char = $charQuery->fetch_assoc();

    $capQuery = $MySQLi->query("SELECT LevelCap,GoldMultiplier,ExpMultiplier  FROM `df_settings` LIMIT 1");
    $caps = $capQuery->fetch_array();

    $BoostQuery = $MySQLi->query("SELECT * FROM `df_equipment` WHERE ItemID = 3610 AND HouseID = 0 AND HouseItem = 0 OR ItemID = 3611 AND HouseID = 0 AND HouseItem = 0 OR ItemID = 3613 AND HouseID = 0 AND HouseItem = 0;");

    if ($results->num_rows > 0) {
        $dom = new DOMDocument();
        $XML = $dom->appendChild($dom->createElement('quest'));
        $quest = $XML->appendChild($dom->createElement('quest'));

        $levelCap = $caps[0];
        if ($char['level'] >= $levelCap) {
            $newEXP = 0;
        } else {
            $newEXP = $questq['MaxExp'];
        }

        $quest->setAttribute("QuestID", $quest_id);
        $quest->setAttribute("strName", $questq['Name']);
        $quest->setAttribute("strDescription", $questq['Description']);
        $quest->setAttribute("strComplete", $questq['Complete']);
        $quest->setAttribute("strFileName", $questq['FileName']);
        $quest->setAttribute("strXFileName", $questq['XFileName']);
        $quest->setAttribute("intMaxSilver", $questq['MaxSilver']);
        $quest->setAttribute("intMaxGold", $questq['MaxGold'] * $caps['GoldMultiplier']);
        $quest->setAttribute("intMaxGems", $questq['MaxGems']);
        $quest->setAttribute("intMaxExp", $newEXP * $caps['ExpMultiplier']);
        $quest->setAttribute("intMinTime", $questq['MinTime']);
        $quest->setAttribute("intCounter", $questq['Counter']);
        $quest->setAttribute("intMonsterMinLevel", $questq['MonsterMinLevel']);
        $quest->setAttribute("intMonsterMaxLevel", $questq['MonsterMaxLevel']);
        $quest->setAttribute("strMonsterType", $questq['MonsterType']);
        $quest->setAttribute("strMonsterGroupFileName", $questq['MonsterGroupFileName']);

        $a = explode(",", $questq['MonsterIDs']);
        $i = 0;
        while ($i <= count($a)) {
            $monsters = $MySQLi->query("SELECT * FROM df_monsters WHERE MonsterID = {$a[$i]}");
            if ($monsters->num_rows > 0) {
                while ($mon = $monsters->fetch_assoc()) {
                    $monster = $quest->appendChild($dom->createElement('monsters'));
                    if ($char['level'] >= $levelCap) {
                        $newMonEXP = 0;
                    } else {
                        $newMonEXP = $mon['Exp'];
                    }
                    if ($BoostQuery->num_rows > 0) {
                        $newExp = $newMonEXP * 1.10;
                        $newGold = $mon['Gold'] * 1.10;
                    } else {
                        $newExp = $newMonEXP;
                        $newGold = $mon['Gold'];
                    }
                    $monster->setAttribute("MonsterID", $mon['MonsterID']);
                    $monster->setAttribute("intMonsterRef", $i);
                    $monster->setAttribute("strCharacterName", $mon['Name']);
                    $monster->setAttribute("intLevel", $mon['Level']);
                    $monster->setAttribute("intExp", $newExp * $caps['ExpMultiplier']);
                    $monster->setAttribute("intHP", $mon['HP']);
                    $monster->setAttribute("intMP", $mon['MP']);
                    $monster->setAttribute("intSilver", $mon['Silver']);
                    $monster->setAttribute("intGold", $newGold * $caps['GoldMultiplier']);
                    $monster->setAttribute("intGems", $mon['Gems']);
                    $monster->setAttribute("intDragonCoins", $mon['Coins']);
                    $monster->setAttribute("strGender", $mon['Gender']);
                    $monster->setAttribute("intHairStyle", $mon['HairStyle']);
                    $monster->setAttribute("intColorHair", $mon['ColorHair']);
                    $monster->setAttribute("intColorSkin", $mon['ColorSkin']);
                    $monster->setAttribute("intColorBase", $mon['ColorBase']);
                    $monster->setAttribute("intColorTrim", $mon['ColorTrim']);
                    $monster->setAttribute("intStr", $mon['STR']);
                    $monster->setAttribute("intDex", $mon['DEX']);
                    $monster->setAttribute("intInt", $mon['INT']);
                    $monster->setAttribute("intLuk", $mon['LUK']);
                    $monster->setAttribute("intCha", $mon['CHA']);
                    $monster->setAttribute("intEnd", $mon['END']);
                    $monster->setAttribute("strArmorName", $mon['ArmorName']);
                    $monster->setAttribute("strArmorDescription", $mon['ArmorDesc']);
                    $monster->setAttribute("strArmorDesignInfo", $mon['ArmorDesignInfo']);
                    $monster->setAttribute("strArmorResists", $mon['ArmorResists']);
                    $monster->setAttribute("intDefMelee", $mon['DefMelee']);
                    $monster->setAttribute("intDefRange", $mon['DefRange']);
                    $monster->setAttribute("intDefMagic", $mon['DefMagic']);
                    $monster->setAttribute("intParry", $mon['Parry']);
                    $monster->setAttribute("intDodge", $mon['Dodge']);
                    $monster->setAttribute("intBlock", $mon['Block']);
                    $monster->setAttribute("strWeaponName", $mon['WeaponName']);
                    $monster->setAttribute("strWeaponDescription", $mon['WeaponDesc']);
                    $monster->setAttribute("strWeaponDesignInfo", $mon['WeaponDesignInfo']);
                    $monster->setAttribute("strWeaponResists", $mon['WeaponResists']);
                    $monster->setAttribute("strType", $mon['Type']);
                    $monster->setAttribute("intCrit", $mon['Crit']);
                    $monster->setAttribute("intDmgMin", $mon['DmgMin']);
                    $monster->setAttribute("intDmgMax", $mon['DmgMax']);
                    $monster->setAttribute("intBonus", $mon['Bonus']);
                    $monster->setAttribute("strElement", $mon['Element']);
                    $monster->setAttribute("strWeaponFile", $mon['WepFile']);
                    $monster->setAttribute("strMovName", $mon['MovName']);
                    $monster->setAttribute("strMonsterFileName", $mon['MonFile']);
                    $monster->setAttribute("RaceID", $mon['RaceID']);
                    $monster->setAttribute("strRaceName", $mon['RaceName']);
                }
            }
            $i++;
        }
        echo $dom->saveXML();
    } else {
        $Core->returnXMLError('Error!', "There was a problem loading the Quest");
    }
} else {
    $Core->returnXMLError('Invalid Data!', 'Message');
}
$MySQLi->close();